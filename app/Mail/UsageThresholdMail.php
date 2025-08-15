<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsageThresholdMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $customerName,
        public string $metricLabel,
        public int $used,
        public int $quota,
        public string $level // warn|stop
    ) {}

    public function build()
    {
        $subject = $this->level === 'stop'
            ? "Kvotgräns nådd: {$this->metricLabel}"
            : "Hög förbrukning: {$this->metricLabel} ({$this->used}/{$this->quota})";

        return $this->subject($subject)
            ->view('emails.usage-threshold', [
                'customerName' => $this->customerName,
                'metricLabel' => $this->metricLabel,
                'used' => $this->used,
                'quota' => $this->quota,
                'level' => $this->level,
            ]);
    }
}
