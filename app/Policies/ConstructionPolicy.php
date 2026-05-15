<?php

namespace App\Policies;

use App\Models\Construction;
use App\Models\User;

class ConstructionPolicy
{
    public function view(User $user, Construction $construction)
    {
        return $user->id === $construction->user_id;
    }

    public function update(User $user, Construction $construction)
    {
        return $user->id === $construction->user_id;
    }

    public function delete(User $user, Construction $construction)
    {
        return $user->id === $construction->user_id;
    }
}