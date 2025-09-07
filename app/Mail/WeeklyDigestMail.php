<?php

namespace App\Mail;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $sections = [];
    public array $summary  = [];

    public function __construct(
        public Customer $customer,
        public string $runTag,
        array $payload = []
    ) {
        $this->onQueue('mail');

        // Tolka $payload och mappa
        if (isset($payload['sites']) || isset($payload['date']) || isset($payload['cta_url'])) {
            // Nytt format (summary)
            $this->summary = $payload;
        } else {
            // Gammalt format (sections)
            $this->sections = $payload;
        }
    }


    public function build()
    {
        $tag = $this->runTag === 'friday' ? 'Fredag' : 'Måndag';

        if (!empty($this->summary)) {
            // Ny, kort notis-layout med CTA
            return $this->subject("Ny veckosammanställning ({$tag}) – {$this->customer->name}")
                ->view('emails.weekly_digest_notification', [
                    'customer' => $this->customer,
                    'runTag'   => $this->runTag,
                    'summary'  => $this->summary,
                ]);
        }

        // Fallback (gammal layout som förväntar $sections). Om du inte längre använder den kan du ta bort detta block.
        return $this->subject("Veckodigest ({$this->runTag}) – ".$this->customer->name)
            ->markdown('emails.weekly_digest', [
                'customer' => $this->customer,
                'sections' => $this->sections,
            ]);
    }
}
