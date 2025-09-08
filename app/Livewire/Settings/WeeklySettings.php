<?php

namespace App\Livewire\Settings;

use App\Jobs\GenerateWeeklyDigestJob;
use App\Models\Customer;
use App\Models\Site;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WeeklySettings extends Component
{
    public ?Customer $customer = null;
    public ?Site $site = null;

    public string $weekly_recipients = '';
    public string $weekly_brand_voice = '';
    public string $weekly_audience = '';
    public string $weekly_goal = '';
    public string $weekly_keywords = ''; // komma-separerad lista

    public function mount(CurrentCustomer $current): void
    {
        $this->customer = $current->get();
        abort_unless($this->customer, 403);

        $siteId = $current->getSiteId();
        $this->site = $siteId ? Site::query()->where('customer_id', $this->customer->id)->find($siteId) : null;

        $recipients = $this->site?->weekly_recipients ?? $this->customer->weekly_recipients ?? '';
        $this->weekly_recipients = (string) $recipients;

        $this->weekly_brand_voice = (string) ($this->site?->weekly_brand_voice ?? $this->customer->weekly_brand_voice ?? '');
        $this->weekly_audience    = (string) ($this->site?->weekly_audience    ?? $this->customer->weekly_audience    ?? '');
        $this->weekly_goal        = (string) ($this->site?->weekly_goal        ?? $this->customer->weekly_goal        ?? '');

        $keywords = $this->site?->weekly_keywords
            ? (array) json_decode($this->site->weekly_keywords, true)
            : ($this->customer->weekly_keywords ? (array) json_decode($this->customer->weekly_keywords, true) : []);

        $this->weekly_keywords = implode(', ', $keywords);
    }

    public function save(CurrentCustomer $current): void
    {
        abort_unless($this->customer, 403);

        $siteId = $current->getSiteId();
        if (!$siteId) {
            session()->flash('error', 'Välj en aktiv sajt i toppbaren för att spara sajtens inställningar.');
            return;
        }

        $this->site = Site::query()->where('customer_id', $this->customer->id)->findOrFail($siteId);

        $this->validate([
            'weekly_recipients'  => 'nullable|string|max:5000',
            'weekly_brand_voice' => 'nullable|string|max:255',
            'weekly_audience'    => 'nullable|string|max:255',
            'weekly_goal'        => 'nullable|string|max:255',
            'weekly_keywords'    => 'nullable|string|max:2000',
        ]);

        $emails = collect(preg_split('/\s*,\s*/', (string) $this->weekly_recipients, -1, PREG_SPLIT_NO_EMPTY))
            ->filter(fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();

        $keywords = collect(preg_split('/\s*,\s*/', (string) $this->weekly_keywords, -1, PREG_SPLIT_NO_EMPTY))
            ->filter()
            ->unique()
            ->values()
            ->all();

        // Spara på site-nivå (tömda fält sätts till null => ärver från kund)
        $this->site->update([
            'weekly_recipients'  => !empty($emails) ? implode(',', $emails) : null,
            'weekly_brand_voice' => $this->weekly_brand_voice !== '' ? $this->weekly_brand_voice : null,
            'weekly_audience'    => $this->weekly_audience !== '' ? $this->weekly_audience : null,
            'weekly_goal'        => $this->weekly_goal !== '' ? $this->weekly_goal : null,
            'weekly_keywords'    => !empty($keywords) ? json_encode($keywords) : null,
        ]);

        session()->flash('success', 'Inställningarna för sajten sparades.');
    }

    public function sendTest(CurrentCustomer $current): void
    {
        abort_unless($this->customer, 403);

        $siteId = $current->getSiteId();
        if (!$siteId) {
            session()->flash('error', 'Välj en aktiv sajt i toppbaren för att skicka ett test.');
            return;
        }

        dispatch(new GenerateWeeklyDigestJob($siteId, 'monday'))->onQueue('ai');

        session()->flash('success', 'Test-sammandrag köat för vald sajt. Kolla din mail om en liten stund.');
    }

    public function render()
    {
        $recipientsPreview = collect(preg_split('/\s*,\s*/', (string) $this->weekly_recipients, -1, PREG_SPLIT_NO_EMPTY))
            ->filter(fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values();

        // Effektiva värden (visning): site-värde eller kund-värde
        $effective = [
            'brand_voice' => $this->weekly_brand_voice !== '' ? $this->weekly_brand_voice : (string) ($this->customer->weekly_brand_voice ?? ''),
            'audience'    => $this->weekly_audience    !== '' ? $this->weekly_audience    : (string) ($this->customer->weekly_audience ?? ''),
            'goal'        => $this->weekly_goal        !== '' ? $this->weekly_goal        : (string) ($this->customer->weekly_goal ?? ''),
            'keywords'    => $this->weekly_keywords !== '' ? $this->weekly_keywords : implode(', ', $this->customer->weekly_keywords ? (array) json_decode($this->customer->weekly_keywords, true) : []),
            'recipients'  => $this->weekly_recipients !== '' ? $this->weekly_recipients : (string) ($this->customer->weekly_recipients ?? ''),
        ];

        return view('livewire.settings.weekly-settings', [
            'recipientsPreview' => $recipientsPreview,
            'activeSite'        => $this->site,
            'effective'         => $effective,
        ]);
    }
}
