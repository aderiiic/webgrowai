<?php

use App\Jobs\RunSeoAuditJob;
use App\Livewire\Home;
use App\Livewire\SEO\AuditDetail;
use App\Livewire\SEO\AuditHistory;
use App\Livewire\Sites\WordPressConnect;
use App\Livewire\Wizard;
use App\Livewire\Wp\MetaEditor;
use App\Livewire\Wp\PostEditor;
use App\Livewire\Wp\PostsIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Sites\Index as SitesIndex;
use App\Livewire\Sites\Create as SitesCreate;
use App\Livewire\Sites\Edit as SitesEdit;

use App\Livewire\Admin\Plans\Index as AdminPlansIndex;
use App\Livewire\Admin\Plans\Edit as AdminPlansEdit;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', Home::class)->middleware('onboarded')->name('dashboard');
    Route::get('/onboarding', Wizard::class)->name('onboarding');
});

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


    Route::get('/seo/audits', AuditHistory::class)->name('seo.audit.history');
    Route::get('/seo/audits/{auditId}', AuditDetail::class)->name('seo.audit.detail');
});

Route::middleware(['auth','verified','can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/plans', AdminPlansIndex::class)->name('plans.index');
    Route::get('/plans/{plan}/edit', AdminPlansEdit::class)->name('plans.edit');
});
