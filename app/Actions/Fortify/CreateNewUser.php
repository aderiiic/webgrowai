<?php

namespace App\Actions\Fortify;

use App\Actions\Auth\RegisterCompany;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
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
            'org_nr'          => ['nullable','string','max:50'],
            'vat_nr'          => ['nullable','string','max:50'],
            'contact_phone'   => ['nullable','string','max:50'],
        ])->validate();

        $user = User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
            'role'     => 'user',
            'onboarding_step' => 3, // direkt klar för MVP
        ]);

        // Skapa kund + trial (Starter 14d)
        app(RegisterCompany::class)->handle($user, $input);

        return $user;
    }
}
