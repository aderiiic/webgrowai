<?php

use App\Livewire\Home;
use App\Livewire\Wizard;
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
});

Route::middleware(['auth','verified','can:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/plans', AdminPlansIndex::class)->name('plans.index');
    Route::get('/plans/{plan}/edit', AdminPlansEdit::class)->name('plans.edit');
});
