<?php

use App\Http\Controllers\LinkedInSuggestionController;
use App\Models\Invoice;
use App\Models\Post;
use App\Models\WpIntegration;
use App\Services\Billing\InvoicePdf;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\TrackController;
use App\Jobs\AnalyzeConversionJob;
use App\Jobs\RunSeoAuditJob;
use App\Livewire\CRO\SuggestionDetail;
use App\Livewire\CRO\SuggestionIndex;
use App\Livewire\Dashboard\PublicationsIndex;
use App\Livewire\Home;
use App\Livewire\Marketing\MailchimpHistory;
use App\Livewire\Marketing\NewsletterCompose;
use App\Livewire\SEO\AuditDetail;
use App\Livewire\SEO\AuditHistory;
use App\Livewire\Settings\MailchimpSettings;
use App\Livewire\Settings\SocialSettings;
use App\Livewire\Settings\WeeklySettings;
use App\Livewire\Sites\WordPressConnect;
use App\Livewire\Wizard;
use App\Livewire\Wp\MetaEditor;
use App\Livewire\Wp\PostEditor;
use App\Livewire\Wp\PostsIndex;
use App\Models\AiContent;
use Illuminate\Support\Facades\Artisan;
use App\Livewire\Sites\Index as SitesIndex;
use App\Livewire\Sites\Create as SitesCreate;
use App\Livewire\Sites\Edit as SitesEdit;
use App\Livewire\AI\Index as AiIndex;
use App\Livewire\AI\Compose as AiCompose;
use App\Livewire\AI\Detail as AiDetail;
use App\Livewire\Leads\Index as LeadsIndex;
use App\Livewire\Leads\Detail as LeadDetail;
use App\Jobs\FetchRankingsJob;
use App\Jobs\AnalyzeKeywordsJob;
use App\Livewire\SEO\KeywordSuggestionsIndex;
use App\Livewire\SEO\KeywordSuggestionDetail;
use App\Livewire\Account\Usage as AccountUsage;
use App\Livewire\Account\Upgrade as AccountUpgrade;

use App\Livewire\Admin\Customers\Show as AdminCustomerShow;
use App\Livewire\Admin\Plans\Index as AdminPlansIndex;
use App\Livewire\Admin\Plans\Edit as AdminPlansEdit;
use App\Livewire\Admin\Usage\Index as AdminUsageIndex;
use App\Livewire\Admin\Blog\Index as AdminBlogIndex;
use App\Livewire\Admin\Blog\Edit as AdminBlogEdit;
use App\Livewire\Admin\Invoices\Index as AdminInvoicesIndex;
use App\Livewire\Admin\Invoices\Show as AdminInvoicesShow;
use App\Livewire\Admin\Subscriptions\RequestsIndex as AdminSubRequests;
use App\Livewire\Admin\Customers\Index as AdminCustomersIndex;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
Route::get('/news', function () {
    $posts = Post::whereNotNull('published_at')->latest('published_at')->paginate(12);
    return view('news.index', compact('posts'));
})->name('news.index');

Route::get('/news/{slug}', function (string $slug) {
    $post = Post::where('slug', $slug)->whereNotNull('published_at')->firstOrFail();
    return view('news.show', compact('post'));
})->name('news.show');

// Prissida
Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

Route::post('/demo-request', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:120',
        'email' => 'required|email|max:255',
        'company' => 'nullable|string|max:180',
        'notes' => 'nullable|string|max:2000',
    ]);
    \App\Models\DemoRequest::create($data);
    return back()->with('success', 'Tack! Vi återkommer snarast för att boka en demo.');
})->name('demo.request');

Route::get('/integritet', function () {
    return view('policy');
})->name('privacy');
Route::get('/villkor', function () {
    return view('terms');
})->name('terms');


Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', Home::class)->middleware('onboarded')->name('dashboard');
    Route::get('/onboarding', Wizard::class)->name('onboarding');
});

Route::middleware(['auth','verified'])->get('/onboarding/tracker', \App\Livewire\Onboarding\Tracker::class)
    ->name('onboarding.tracker');

Route::middleware(['auth','verified'])->get('/downloads/webbi-lead-tracker', function () {
    $path = public_path('downloads/webbi-lead-tracker.zip'); // placera zip här
    abort_unless(file_exists($path), 404);
    return response()->download($path, 'webbi-lead-tracker.zip');
})->name('downloads.webbi-lead-tracker');

Route::middleware(['auth','verified','onboarded', 'paidOrTrial'])->group(function () {
    Route::get('/account/usage', AccountUsage::class)->name('account.usage');
    Route::get('/account/upgrade', AccountUpgrade::class)->name('account.upgrade');

    Route::get('/sites', SitesIndex::class)->name('sites.index');
    Route::get('/sites/create', SitesCreate::class)->name('sites.create');
    Route::get('/sites/{site}/edit', SitesEdit::class)->name('sites.edit')->whereNumber('site');

    Route::get('/sites/{site}/wordpress', WordPressConnect::class)->name('sites.wordpress')->whereNumber('site');

    Route::get('/sites/{site}/wp/posts', PostsIndex::class)->name('wp.posts.index')->whereNumber('site');
    Route::get('/sites/{site}/wp/posts/create', PostEditor::class)->name('wp.posts.create')->whereNumber('site');
    Route::get('/sites/{site}/wp/posts/{postId}/edit', PostEditor::class)->name('wp.posts.edit')->whereNumber('site')->whereNumber('postId');

    Route::get('/sites/{site}/wp/posts/{postId}/meta', MetaEditor::class)->name('wp.posts.meta')->whereNumber('site')->whereNumber('postId');

    Route::get('/seo/audit/run', function () {
        $customer = app(\App\Support\CurrentCustomer::class)->get();
        abort_unless($customer, 403);

        $siteId = $customer->sites()->value('id');
        abort_unless($siteId, 404, 'Ingen sajt i aktuell kund.');

        if (request()->boolean('sync')) {
            dispatch_sync(new RunSeoAuditJob($siteId));
        } else {
            dispatch((new RunSeoAuditJob($siteId))->onQueue('seo'));
        }

        return back()->with('success', 'SEO audit startad. Uppdatera sidan om en stund.');
    })->name('seo.audit.run');

    Route::post('/sites/{site}/seo/audit/run', function (\App\Models\Site $site) {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            abort_unless($user->customers()->whereKey($site->customer_id)->exists(), 403);
        }

        if (request()->boolean('sync')) {
            dispatch_sync(new RunSeoAuditJob($site->id));
        } else {
            dispatch((new RunSeoAuditJob($site->id))->onQueue('seo'));
        }

        return back()->with('success', 'SEO audit startad för '.$site->name.'.');
    })->name('sites.seo.audit.run')->whereNumber('site');

    Route::get('/ai/{id}/export', function (int $id) {
        $content = AiContent::findOrFail($id);
        if (method_exists($content, 'customer')) {
            abort_unless(auth()->user()?->isAdmin() || auth()->user()?->customers()->whereKey($content->customer_id)->exists(), 403);
        }
        $md = $content->body_md ?? '';
        $name = \Illuminate\Support\Str::slug($content->title ?: 'ai-content').'.md';

        return response($md, 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$name\"",
        ]);
    })->name('ai.export')->whereNumber('id');

    Route::get('/seo/audits', AuditHistory::class)->name('seo.audit.history');
    Route::get('/seo/audits/{auditId}', AuditDetail::class)->name('seo.audit.detail')->whereNumber('auditId');

    Route::get('/ai', AiIndex::class)->name('ai.list');
    Route::get('/ai/compose', AiCompose::class)->name('ai.compose');
    Route::get('/ai/{id}', AiDetail::class)->name('ai.detail')->whereNumber('id');

    Route::get('/publications', PublicationsIndex::class)->name('publications.index');

    Route::get('/settings/weekly', WeeklySettings::class)->name('settings.weekly');
    Route::get('/settings/social', SocialSettings::class)->name('settings.social');
    Route::get('/settings/mailchimp', MailchimpSettings::class)->name('settings.mailchimp');

    Route::get('/marketing/newsletter', NewsletterCompose::class)->name('marketing.newsletter');
    Route::get('/marketing/mailchimp/history', MailchimpHistory::class)->name('marketing.mailchimp.history');

    Route::get('/auth/facebook/redirect', [SocialAuthController::class, 'facebookRedirect'])->name('auth.facebook.redirect');
    Route::get('/auth/facebook/callback', [SocialAuthController::class, 'facebookCallback'])->name('auth.facebook.callback');

    Route::get('/auth/linkedin/redirect', [SocialAuthController::class, 'linkedinRedirect'])->name('auth.linkedin.redirect');
    Route::get('/auth/linkedin/callback', [SocialAuthController::class, 'linkedinCallback'])->name('auth.linkedin.callback');

    Route::get('/auth/instagram/redirect', [SocialAuthController::class, 'instagramRedirect'])->name('auth.instagram.redirect');
    Route::get('/auth/instagram/callback', [SocialAuthController::class, 'instagramCallback'])->name('auth.instagram.callback');

    Route::get('/api/linkedin/suggestions', [LinkedInSuggestionController::class, 'index'])->name('linkedin.suggestions.index');
    Route::post('/api/linkedin/suggestions', [LinkedInSuggestionController::class, 'store'])->name('linkedin.suggestions.store');
    Route::delete('/api/linkedin/suggestions/{suggestion}', [LinkedInSuggestionController::class, 'destroy'])->name('linkedin.suggestions.destroy');
    Route::post('/api/linkedin/publish', [LinkedInSuggestionController::class, 'publish'])->name('linkedin.publish');

    Route::get('/leads', LeadsIndex::class)->name('leads.index');
    Route::get('/leads/{id}', LeadDetail::class)->name('leads.detail')->whereNumber('id');

    Route::get('/cro/suggestions', SuggestionIndex::class)->name('cro.suggestions.index');

    // Flytta knappar (fetch/analyze) före den parameteriserade routen för att undvika krockar
    Route::get('/seo/keywords', KeywordSuggestionsIndex::class)->name('seo.keywords.index');

    Route::get('/seo/keywords/fetch', function () {
        $customer = app(\App\Support\CurrentCustomer::class)->get();
        abort_unless($customer, 403);
        $siteId = $customer->sites()->value('id');
        abort_unless($siteId, 404, 'Ingen sajt.');
        dispatch(new FetchRankingsJob($siteId))->onQueue('default');
        return back()->with('success', 'Hämtning av rankingar köad.');
    })->name('seo.keywords.fetch');

    Route::get('/seo/keywords/analyze', function () {
        $customer = app(\App\Support\CurrentCustomer::class)->get();
        abort_unless($customer, 403);
        $siteId = $customer->sites()->value('id');
        abort_unless($siteId, 404, 'Ingen sajt.');

        // Nytt: kräver WP-koppling
        $hasWp = WpIntegration::where('site_id', $siteId)->exists();
        if (!$hasWp) {
            return back()->with('success', 'Koppla din WordPress-sajt under “Sajter → WordPress” för att köra SEO‑analysen av nyckelord.');
        }

        dispatch(new AnalyzeKeywordsJob($siteId))->onQueue('ai');
        return back()->with('success', 'AI-analys köad.');
    })->name('seo.keywords.analyze');

    // Den parameteriserade routen kommer sist + begränsad till numeriskt id
    Route::get('/cro/suggestions/{id}', SuggestionDetail::class)->name('cro.suggestion.detail')->whereNumber('id');
    Route::get('/seo/keywords/{id}', KeywordSuggestionDetail::class)->name('seo.keywords.detail')->whereNumber('id');

    Route::get('/cro/analyze/run', function () {
        $customer = app(\App\Support\CurrentCustomer::class)->get();
        abort_unless($customer, 403);
        $siteId = $customer->sites()->value('id');
        abort_unless($siteId, 404, 'Ingen sajt.');

        // Nytt: kontrollera WP-koppling innan vi köar
        $hasWp = WpIntegration::where('site_id', $siteId)->exists();
        if (!$hasWp) {
            return back()->with('success', 'Koppla din WordPress-sajt under “Sajter → WordPress” för att köra CRO-analysen.');
        }

        dispatch(new AnalyzeConversionJob($siteId))->onQueue('default');
        return back()->with('success', 'Analys köad. Uppdatera sidan om en stund.');
    })->name('cro.analyze.run');

    Route::post('/sites/{site}/cro/analyze', function (\App\Models\Site $site) {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            abort_unless($user->customers()->whereKey($site->customer_id)->exists(), 403);
        }

        $hasWp = WpIntegration::where('site_id', $site->id)->exists();
        if (!$hasWp) {
            return back()->with('success', 'Koppla din WordPress-sajt under “Sajter → WordPress” för att köra CRO-analysen.');
        }

        dispatch(new AnalyzeConversionJob($site->id))->onQueue('default');

        return back()->with('success', 'CRO-analys köad för '.$site->name.'. Uppdatera om en stund.');
    })->name('sites.cro.analyze')->whereNumber('site');
});

Route::middleware(['auth','verified','can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/plans', AdminPlansIndex::class)->name('plans.index');
    Route::get('/plans/{plan}/edit', AdminPlansEdit::class)->name('plans.edit')->whereNumber('plan');

    Route::get('/blog', AdminBlogIndex::class)->name('blog.index');
    Route::get('/blog/{id}', AdminBlogEdit::class)->whereNumber('id')->name('blog.edit');

    Route::get('/usage', AdminUsageIndex::class)->name('usage.index');
    Route::get('/customers/{id}', AdminCustomerShow::class)->whereNumber('id')->name('customers.show');

    Route::get('/customers', AdminCustomersIndex::class)->name('customers.index');

    Route::get('/invoices', AdminInvoicesIndex::class)->name('invoices.index');
    Route::get('/invoices/{id}', AdminInvoicesShow::class)->whereNumber('id')->name('invoices.show');

    Route::get('/subscription-requests', AdminSubRequests::class)->name('subscription.requests');
    Route::get('/admin/invoices/{id}/download', function (int $id, InvoicePdf $pdf) {
        $inv = Invoice::with('customer')->findOrFail($id);
        $doc = $pdf->render($inv);
        return response($doc['content'], 200, [
            'Content-Type' => $doc['contentType'],
            'Content-Disposition' => 'attachment; filename="'.$doc['filename'].'"',
        ]);
    })->name('admin.invoices.download');
});

// Tracking-endpoint
Route::middleware('throttle:60,1')->post('/track', [TrackController::class, 'store'])->name('track.store');
