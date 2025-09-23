<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\User;
use App\Mail\AdminCreatedCustomerMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component
{
    // Användaruppgifter
    public $name = '';
    public $email = '';
    public $contact_phone = '';

    // Företagsuppgifter
    public $company_name = '';
    public $org_nr = '';
    public $vat_nr = '';
    public $contact_name = '';
    public $billing_email = '';
    public $billing_address = '';
    public $billing_zip = '';
    public $billing_city = '';
    public $billing_country = 'SE';

    // Plan
    public $selected_plan_id = null;

    public $plans = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'contact_phone' => 'nullable|string|max:255',
        'company_name' => 'required|string|max:255',
        'org_nr' => 'required|string|max:255',
        'vat_nr' => 'nullable|string|max:255',
        'contact_name' => 'required|string|max:255',
        'billing_email' => 'required|string|email|max:255',
        'billing_address' => 'required|string|max:255',
        'billing_zip' => 'required|string|max:10',
        'billing_city' => 'required|string|max:255',
        'billing_country' => 'required|string|size:2',
        'selected_plan_id' => 'nullable|exists:plans,id',
    ];

    protected $messages = [
        'name.required' => 'Namn är obligatoriskt.',
        'email.required' => 'E-post är obligatorisk.',
        'email.email' => 'E-postadressen måste vara giltig.',
        'email.unique' => 'Denna e-postadress används redan.',
        'company_name.required' => 'Företagsnamn är obligatoriskt.',
        'org_nr.required' => 'Organisationsnummer är obligatoriskt.',
        'contact_name.required' => 'Kontaktperson är obligatorisk.',
        'billing_email.required' => 'Faktura e-post är obligatorisk.',
        'billing_email.email' => 'Faktura e-postadressen måste vara giltig.',
        'billing_address.required' => 'Fakturaadress är obligatorisk.',
        'billing_zip.required' => 'Postnummer är obligatoriskt.',
        'billing_city.required' => 'Ort är obligatorisk.',
        'billing_country.required' => 'Land är obligatoriskt.',
        'billing_country.size' => 'Land måste vara en 2-bokstavs ISO-kod.',
        'selected_plan_id.exists' => 'Vald plan existerar inte.',
    ];

    public function mount()
    {
        // Använd is_active istället för active
        $this->plans = Plan::where('is_active', true)->orderBy('price_monthly')->get();
        $this->billing_email = $this->email;
    }

    public function updatedEmail()
    {
        if (empty($this->billing_email)) {
            $this->billing_email = $this->email;
        }
    }

    public function create()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                // Skapa användare med slumpmässigt lösenord
                $temporaryPassword = Str::random(32);

                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($temporaryPassword),
                    'role' => 'user',
                ]);

                // Skapa kund med DB query precis som RegisterCompany
                $customerId = DB::table('customers')->insertGetId([
                    'name'            => $this->company_name,
                    'company_name'    => $this->company_name,
                    'contact_email'   => $this->billing_email,
                    'contact_name'    => $this->contact_name,
                    'contact_phone'   => $this->contact_phone,
                    'org_nr'          => $this->org_nr,
                    'vat_nr'          => $this->vat_nr,
                    'billing_email'   => $this->billing_email,
                    'billing_address' => $this->billing_address,
                    'billing_zip'     => $this->billing_zip,
                    'billing_city'    => $this->billing_city,
                    'billing_country' => strtoupper($this->billing_country),
                    'status'          => 'active',
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                // Koppla användaren till kunden
                if (method_exists($user, 'customers')) {
                    $user->customers()->syncWithoutDetaching([$customerId => ['role_in_customer' => 'owner']]);
                }

                // Skapa prenumeration enkelt om plan är vald
                if ($this->selected_plan_id) {
                    $selectedPlan = DB::table('plans')->where('id', $this->selected_plan_id)->first();
                    if ($selectedPlan) {
                        DB::table('app_subscriptions')->insert([
                            'customer_id' => $customerId,
                            'plan_id'     => $selectedPlan->id,
                            'status'      => 'trial',
                            'trial_ends_at' => now()->addDays(14),
                            'billing_cycle' => 'monthly',
                            'current_period_start' => now()->startOfMonth(),
                            'current_period_end'   => now()->endOfMonth(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Skicka välkomstmejl med lösenordsåterställning
                $token = Password::createToken($user);

                try {
                    // Hämta customer-objekt för mejlet
                    $customer = Customer::find($customerId);

                    Mail::send('emails.admin-welcome', [
                        'user' => $user,
                        'customer' => $customer,
                        'reset_url' => url(route('password.reset', ['token' => $token, 'email' => $user->email]))
                    ], function ($message) use ($user) {
                        $message->to($user->email)
                            ->subject('Välkommen till WebGrow AI - Sätt ditt lösenord');
                    });
                } catch (\Exception $e) {
                    logger('Failed to send welcome email: ' . $e->getMessage());
                }
            });

            session()->flash('success', 'Kunden har skapats framgångsrikt! Ett välkomstmejl har skickats.');

            return redirect()->route('admin.customers.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Ett fel uppstod när kunden skulle skapas: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.customers.create')
            ->layout('layouts.app', ['title' => 'Skapa ny kund']);
    }
}
