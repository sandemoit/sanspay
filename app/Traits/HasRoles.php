<?php

namespace App\Traits;

use App\Models\User;

trait HasRoles
{
    public function isAdmin(): bool
    {
        return $this->HasRole('admin');
    }

    public function isCustomer(): bool
    {
        return $this->HasRole('customer');
    }

    public function HasRole(string $role): bool
    {
        return $this->role === $role;
    }
}
