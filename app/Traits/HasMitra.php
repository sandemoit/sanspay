<?php

namespace App\Traits;

trait HasMitra
{
    public function notCustomer(): bool
    {
        return $this->HasMitra(['admin', 'mitra']);
    }

    public function HasMitra(array $role): bool
    {
        return in_array($this->role, $role);
    }
}
