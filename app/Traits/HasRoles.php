<?php

namespace App\Traits;

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

    public function isMitra(): bool
    {
        return $this->HasRole('mitra');
    }

    public function HasRole(string $role): bool
    {
        return $this->role === $role;
    }
}
