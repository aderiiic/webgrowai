<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Customer $customer,
        public string $runTag,     // monday|friday
        public array $sections     // ['campaigns' => md, 'topics' => md, 'next_week' => md]
    ) {
        $this->onQueue('mail');
    }

    public function build()
    {
        return $this->subject("Veckodigest ({$this->runTag}) – ".$this->customer->name)
            ->markdown('emails.weekly_digest', [
                'customer' => $this->customer,
                'sections' => $this->sections,
            ]);
    }
}
