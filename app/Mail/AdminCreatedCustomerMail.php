<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminCreatedCustomerMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Customer $customer,
        public string $resetToken
    ) {}

    public function build(): self
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->resetToken,
            'email' => $this->user->email,
        ]));

        return $this->subject('Välkommen till WebGrow AI')
            ->markdown('emails.admin-created-customer', [
                'user' => $this->user,
                'customer' => $this->customer,
                'resetUrl' => $resetUrl,
            ]);
    }
}
