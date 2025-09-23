<?php

namespace App\Livewire\Admin\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $q = '';
    public $status = 'all';

    public function updatingQ() { $this->resetPage(); }
    public function updatingStatus() { $this->resetPage(); }

    public function resendWelcomeEmail(int $customerId): void
    {
        Log::info('[Admin/Customers/Index] resendWelcomeEmail called', ['customer_id' => $customerId]);

        try {
            $customer = Customer::findOrFail($customerId);
            $user = $customer->users()->first();

            if (!$user) {
                session()->flash('error', 'Ingen användare kopplad till kunden.');
                $this->dispatch('toast', type: 'error', message: 'Ingen användare kopplad till kunden.');
                return;
            }

            // Skapa nytt reset-token
            $token = Password::createToken($user);

            Mail::send('emails.admin-welcome', [
                'user' => $user,
                'customer' => $customer,
                'reset_url' => url(route('password.reset', ['token' => $token, 'email' => $user->email])),
            ], function ($message) use ($user) {
                $message->to($user->email)->subject('Välkommen till WebGrow AI - Sätt ditt lösenord');
            });

            session()->flash('success', 'Välkomstmejl skickades till ' . $user->email);
            $this->dispatch('toast', type: 'success', message: 'Välkomstmejl skickades.');
            Log::info('[Admin/Customers/Index] resendWelcomeEmail success', ['email' => $user->email]);
        } catch (\Throwable $e) {
            Log::error('[Admin/Customers/Index] resendWelcomeEmail failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Kunde inte skicka mejlet: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Utskick misslyckades.');
        }
    }

    public function render()
    {
        $query = DB::table('customers')
            ->leftJoin('customer_user', 'customers.id', '=', 'customer_user.customer_id')
            ->leftJoin('app_subscriptions', 'customers.id', '=', 'app_subscriptions.customer_id')
            ->leftJoin('plans', 'app_subscriptions.plan_id', '=', 'plans.id')
            ->select([
                'customers.id',
                'customers.name',
                'customers.company_name',
                'customers.contact_email',
                'customers.status',
                'app_subscriptions.status as sub_status',
                'app_subscriptions.trial_ends_at',
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
            $query->where('app_subscriptions.status', $this->status);
        }

        $rows = $query->orderBy('customers.created_at', 'desc')->paginate(10);

        return view('livewire.admin.customers.index', [
            'rows' => $rows
        ])->layout('layouts.app');
    }
}
