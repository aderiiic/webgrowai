<?php

namespace App\Actions\Fortify;

use App\Actions\Auth\RegisterCompany;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * @param  array<string, string|int|null>  $input
     */
    public function create(array $input): User
    {
        $validator = Validator::make($input, [
            'name'            => ['required','string','max:255'],
            'email'           => ['required','string','email','max:255','unique:users,email'],
            'password'        => ['required','string','min:8','confirmed'],
            // Företag/billing
            'company_name'    => ['required','string','max:255'],
            'contact_name'    => ['required','string','max:255'],
            'billing_email'   => ['required','email','max:255'],
            'billing_address' => ['required','string','max:255'],
            'billing_zip'     => ['required','string','max:20'],
            'billing_city'    => ['required','string','max:120'],
            'billing_country' => ['required','string','size:2'],
            'org_nr'          => ['required','string','max:50'], // ändrat: required
            'vat_nr'          => ['nullable','string','max:50'],
            'contact_phone'   => ['nullable','string','max:50'],

            // Enkel anti‑spam
            'website'         => ['prohibited'], // honeypot (måste vara tomt)
            'form_started_at' => ['required','integer'],
        ]);

        $validator->after(function ($v) use ($input) {
            // Tidsfälla: kräver minst 5 s mellan laddning och submit
            $started = (int)($input['form_started_at'] ?? 0);
            if ($started > 0 && (time() - $started) < 5) {
                $v->errors()->add('form', 'Formuläret skickades för snabbt. Försök igen.');
            }
        });

        $validator->validate();

        $user = User::create([
            'name'     => (string) $input['name'],
            'email'    => (string) $input['email'],
            'password' => Hash::make((string) $input['password']),
            'role'     => 'user',
            'onboarding_step' => 1,
        ]);

        // Skapa kund + trial (Starter 14d)
        app(RegisterCompany::class)->handle($user, $input);

        // Säkerställ att verifikationsmail skickas
        if ($user instanceof MustVerifyEmail) {
            $user->sendEmailVerificationNotification();
        }

        return $user;
    }
}
