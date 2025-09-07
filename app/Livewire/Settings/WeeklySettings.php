<?php

namespace App\Livewire\Settings;

use App\Jobs\GenerateWeeklyDigestJob;
use App\Models\Customer;
use App\Support\CurrentCustomer;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WeeklySettings extends Component
{
    public ?Customer $customer = null;

    public string $weekly_recipients = '';
    public string $weekly_brand_voice = '';
    public string $weekly_audience = '';
    public string $weekly_goal = '';
    public string $weekly_keywords = ''; // komma-separerad lista

    public function mount(CurrentCustomer $current): void
    {
        $this->customer = $current->get();
        abort_unless($this->customer, 403);

        $this->weekly_recipients  = (string) ($this->customer->weekly_recipients ?? '');
        $this->weekly_brand_voice = (string) ($this->customer->weekly_brand_voice ?? '');
        $this->weekly_audience    = (string) ($this->customer->weekly_audience ?? '');
        $this->weekly_goal        = (string) ($this->customer->weekly_goal ?? '');

        $keywords = $this->customer->weekly_keywords
            ? (array) json_decode($this->customer->weekly_keywords, true)
            : [];
        $this->weekly_keywords = implode(', ', $keywords);
    }

    public function save(): void
    {
        abort_unless($this->customer, 403);

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

        $this->customer->update([
            'weekly_recipients'  => implode(',', $emails),
            'weekly_brand_voice' => $this->weekly_brand_voice ?: null,
            'weekly_audience'    => $this->weekly_audience ?: null,
            'weekly_goal'        => $this->weekly_goal ?: null,
            'weekly_keywords'    => !empty($keywords) ? json_encode($keywords) : null,
        ]);

        session()->flash('success', 'Inställningarna sparades.');
    }

    public function sendTest(CurrentCustomer $current): void
    {
        abort_unless($this->customer, 403);

        $siteId = $current->getSiteId();
        if (!$siteId) {
            session()->flash('error', 'Välj en aktiv sajt i toppbaren för att skicka ett test.');
            return;
        }

        // Kör måndagsvarianten som test för den aktiva sajten
        dispatch(new GenerateWeeklyDigestJob($siteId, 'monday'))->onQueue('ai');

        session()->flash('success', 'Test-sammandrag köat för vald sajt. Kolla din mail om en liten stund.');
    }

    public function render()
    {
        $recipientsPreview = collect(preg_split('/\s*,\s*/', (string) $this->weekly_recipients, -1, PREG_SPLIT_NO_EMPTY))
            ->filter(fn($e) => filter_var($e, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values();

        return view('livewire.settings.weekly-settings', [
            'recipientsPreview' => $recipientsPreview,
        ]);
    }
}
