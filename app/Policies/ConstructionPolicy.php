<?php

namespace App\Policies;

use App\Models\Construction;
use App\Models\User;

class ConstructionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Construction $construction): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Construction $construction): bool
    {
        return true;
    }

    public function delete(User $user, Construction $construction): bool
    {
        return true;
    }
}