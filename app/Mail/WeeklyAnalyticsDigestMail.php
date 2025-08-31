<?php
// app/Mail/WeeklyAnalyticsDigestMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyAnalyticsDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function build(): self
    {
        return $this->subject('Veckodigest – WebGrow')
            ->view('emails.weekly-digest-mail')
            ->with($this->data);
    }
}
