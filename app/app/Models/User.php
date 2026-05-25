<?php

namespace App\Models;

use App\Enums\StaffRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];
    protected $hidden   = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role'              => StaffRole::class,
            'is_active'         => 'boolean',
        ];
    }

    public function isManager(): bool { return $this->role === StaffRole::Manager; }
    public function isWaiter(): bool  { return $this->role === StaffRole::Waiter; }
    public function isChef(): bool    { return $this->role === StaffRole::Chef; }
    public function isCashier(): bool { return $this->role === StaffRole::Cashier; }

    public function hasRole(StaffRole|string $role): bool
    {
        if (is_string($role)) { $role = StaffRole::from($role); }
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function canAccess(string $permission): bool
    {
        return in_array($permission, $this->role->permissions());
    }

    public function getDashboardRoute(): string { return $this->role->dashboardRoute(); }
    public function getRoleLabel(): string       { return $this->role->label(); }
    public function getRoleIcon(): string        { return $this->role->icon(); }
    public function getRoleColor(): string       { return $this->role->color(); }
}
