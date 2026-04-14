<?php

namespace App\Models;

use App\Enums\Role;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'email_verified_at', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasName, MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_at' => 'datetime',
        ];
    }

    protected function allPermissions(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getAllPermissions()->pluck('name')
        );
    }

    public function canAccessPanel(?Panel $panel = null): bool
    {
        return $this->hasRole([Role::SUPER, Role::ADMIN]);
    }

    public function isSuspended(): bool
    {
        return $this->getAttributeFromArray('suspended_at') !== null;
    }

    public function suspend(): void
    {
        $this->forceFill([
            'remember_token' => Str::random(60),
            'suspended_at' => now(),
        ])->save();
    }

    public function reactivate(): void
    {
        $this->forceFill([
            'suspended_at' => null,
        ])->save();
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }
}
