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
        $accountType = $input['account_type'] ?? 'personal';

        $rules = [
            'name'            => ['required','string','max:255'],
            'email'           => ['required','string','email','max:255','unique:users,email'],
            'password'        => ['required','string','min:8','confirmed'],
            'account_type'    => ['required','in:company,personal'],
            'contact_phone'   => ['nullable','string','max:50'],

            // Anti-spam
            'website'         => ['prohibited'],
            'form_started_at' => ['required','integer'],
        ];

        // Add company-specific validation only if company type
        if ($accountType === 'company') {
            $rules = array_merge($rules, [
                'company_name'    => ['required','string','max:255'],
                'contact_name'    => ['required','string','max:255'],
                'billing_email'   => ['required','email','max:255'],
                'billing_address' => ['required','string','max:255'],
                'billing_zip'     => ['required','string','max:20'],
                'billing_city'    => ['required','string','max:120'],
                'billing_country' => ['required','string','size:2'],
                'org_nr'          => ['required','string','max:50'],
                'vat_nr'          => ['nullable','string','max:50'],
            ]);
        }

        $validator = Validator::make($input, $rules);

        $validator->after(function ($v) use ($input) {
            // Time trap: requires at least 5s between load and submit
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

        // Create customer + trial (Starter 14d)
        app(RegisterCompany::class)->handle($user, $input);

        // Ensure verification email is sent
        if ($user instanceof MustVerifyEmail) {
            $user->sendEmailVerificationNotification();
        }

        return $user;
    }
}
