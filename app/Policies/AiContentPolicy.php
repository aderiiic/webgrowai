<?php

namespace App\Policies;

use App\Models\AiContent;
use App\Models\User;

class AiContentPolicy
{
    public function view(User $user, AiContent $content): bool
    {
        if ($user->role === 'admin') return true;
        return $user->customers()->whereKey($content->customer_id)->exists();
    }

    public function create(User $user): bool
    {
        // admin eller kundansluten användare
        return $user->role === 'admin' || $user->customers()->exists();
    }

    public function update(User $user, AiContent $content): bool
    {
        if ($user->role === 'admin') return true;
        return $user->customers()->whereKey($content->customer_id)->exists();
    }
}
