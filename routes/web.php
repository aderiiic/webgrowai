<?php

use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\TrackController;
use App\Jobs\RunSeoAuditJob;
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
use Illuminate\Support\Facades\Route;
use App\Livewire\Sites\Index as SitesIndex;
use App\Livewire\Sites\Create as SitesCreate;
use App\Livewire\Sites\Edit as SitesEdit;
use App\Livewire\AI\Index as AiIndex;
use App\Livewire\AI\Compose as AiCompose;
use App\Livewire\AI\Detail as AiDetail;
use App\Livewire\Leads\Index as LeadsIndex;
use App\Livewire\Leads\Detail as LeadDetail;

use App\Livewire\Admin\Plans\Index as AdminPlansIndex;
use App\Livewire\Admin\Plans\Edit as AdminPlansEdit;
use App\Livewire\Admin\Usage\Index as AdminUsageIndex;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', Home::class)->middleware('onboarded')->name('dashboard');
    Route::get('/onboarding', Wizard::class)->name('onboarding');
});

Route::middleware(['auth','verified','onboarded'])->get('/onboarding/tracker', \App\Livewire\Onboarding\Tracker::class)
    ->name('onboarding.tracker');

Route::middleware(['auth','verified','onboarded'])->get('/downloads/webbi-lead-tracker', function () {
    $path = public_path('downloads/webbi-lead-tracker.zip'); // placera zip här
    abort_unless(file_exists($path), 404);
    return response()->download($path, 'webbi-lead-tracker.zip');
})->name('downloads.webbi-lead-tracker');

Route::middleware(['auth','verified','onboarded'])->group(function () {
    Route::get('/sites', SitesIndex::class)->name('sites.index');
    Route::get('/sites/create', SitesCreate::class)->name('sites.create');
    Route::get('/sites/{site}/edit', SitesEdit::class)->name('sites.edit');

    Route::get('/sites/{site}/wordpress', WordPressConnect::class)->name('sites.wordpress');

    Route::get('/sites/{site}/wp/posts', PostsIndex::class)->name('wp.posts.index');
    Route::get('/sites/{site}/wp/posts/create', PostEditor::class)->name('wp.posts.create');
    Route::get('/sites/{site}/wp/posts/{postId}/edit', PostEditor::class)->name('wp.posts.edit');

    Route::get('/sites/{site}/wp/posts/{postId}/meta', MetaEditor::class)->name('wp.posts.meta');

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
        // auktorisera: admin eller tillhör aktiv kund
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
    })->name('sites.seo.audit.run');

    Route::get('/ai/{id}/export', function (int $id) {
        $content = AiContent::findOrFail($id);
        // Policy/ägarskap (om du har policy aktiverad)
        if (method_exists($content, 'customer')) {
            abort_unless(auth()->user()?->isAdmin() || auth()->user()?->customers()->whereKey($content->customer_id)->exists(), 403);
        }
        $md = $content->body_md ?? '';
        $name = \Illuminate\Support\Str::slug($content->title ?: 'ai-content').'.md';

        return response($md, 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$name\"",
        ]);
    })->name('ai.export');

    Route::get('/seo/audits', AuditHistory::class)->name('seo.audit.history');
    Route::get('/seo/audits/{auditId}', AuditDetail::class)->name('seo.audit.detail');

    Route::get('/ai', AiIndex::class)->name('ai.list');
    Route::get('/ai/compose', AiCompose::class)->name('ai.compose');
    Route::get('/ai/{id}', AiDetail::class)->name('ai.detail');

    Route::get('/publications', PublicationsIndex::class)->name('publications.index');

    Route::get('/settings/weekly', WeeklySettings::class)->name('settings.weekly');
    Route::get('/settings/social', SocialSettings::class)->name('settings.social');
    Route::get('/settings/mailchimp', MailchimpSettings::class)->name('settings.mailchimp');

    Route::get('/marketing/newsletter', NewsletterCompose::class)->name('marketing.newsletter');
    Route::get('/marketing/mailchimp/history', MailchimpHistory::class)->name('marketing.mailchimp.history');

    Route::get('/auth/facebook/redirect', [SocialAuthController::class, 'facebookRedirect'])->name('auth.facebook.redirect');
    Route::get('/auth/facebook/callback', [SocialAuthController::class, 'facebookCallback'])->name('auth.facebook.callback');

    // Instagram-knappen triggar samma flöde genom Facebook OAuth
    Route::get('/auth/instagram/redirect', [SocialAuthController::class, 'instagramRedirect'])->name('auth.instagram.redirect');
    Route::get('/auth/instagram/callback', [SocialAuthController::class, 'instagramCallback'])->name('auth.instagram.callback');

    Route::get('/leads', LeadsIndex::class)->name('leads.index');
    Route::get('/leads/{id}', LeadDetail::class)->name('leads.detail');
});

Route::middleware(['auth','verified','can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/plans', AdminPlansIndex::class)->name('plans.index');
    Route::get('/plans/{plan}/edit', AdminPlansEdit::class)->name('plans.edit');

    Route::get('/usage', AdminUsageIndex::class)->name('usage.index');
});

Route::middleware('throttle:60,1')->post('/track', [TrackController::class, 'store'])->name('track.store');
