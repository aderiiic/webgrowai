<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $q = '';
    public $status = 'all';

    public function updatingQ()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function resendWelcomeEmail($customerId)
    {
        try {
            $customer = Customer::findOrFail($customerId);

            // Hämta den första användaren för denna kund
            $user = $customer->users()->first();

            if (!$user) {
                session()->flash('error', 'Ingen användare hittades för denna kund.');
                return;
            }

            // Skapa nytt password reset token
            $token = Password::createToken($user);

            // Skicka välkomstmejl
            Mail::send('emails.admin-welcome', [
                'user' => $user,
                'customer' => $customer,
                'reset_url' => url(route('password.reset', ['token' => $token, 'email' => $user->email]))
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Välkommen till WebGrow AI - Sätt ditt lösenord');
            });

            session()->flash('success', 'Välkomstmejl har skickats om till ' . $user->email);

        } catch (\Exception $e) {
            session()->flash('error', 'Fel vid sändning av mejl: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = DB::table('customers')
            ->leftJoin('customer_user', 'customers.id', '=', 'customer_user.customer_id')
            ->leftJoin('subscriptions', 'customers.id', '=', 'subscriptions.customer_id')
            ->leftJoin('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->select([
                'customers.id',
                'customers.name',
                'customers.company_name',
                'customers.contact_email',
                'customers.status',
                'subscriptions.status as sub_status',
                'subscriptions.trial_ends_at',
                'plans.name as plan_name'
            ]);

        if ($this->q) {
            $query->where(function ($q) {
                $q->where('customers.name', 'like', '%' . $this->q . '%')
                    ->orWhere('customers.company_name', 'like', '%' . $this->q . '%')
                    ->orWhere('customers.contact_email', 'like', '%' . $this->q . '%');
            });
        }

        if ($this->status !== 'all') {
            $query->where('subscriptions.status', $this->status);
        }

        $rows = $query->orderBy('customers.created_at', 'desc')->paginate(10);

        return view('livewire.admin.customers.index', [
            'rows' => $rows
        ])->layout('layouts.app');
    }
}
