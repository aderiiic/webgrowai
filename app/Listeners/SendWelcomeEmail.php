<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(Verified $event): void
    {
        $user = $event->user;
        if ($user && $user->email) {
            Mail::to($user->email)->queue(new WelcomeMail($user));
        }
    }
}
