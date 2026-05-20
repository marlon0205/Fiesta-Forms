<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'is_active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class, 'user_id', 'user_id');
    }

    public function votes()
    {
        return $this->hasMany(Votes::class, 'user_id', 'user_id');
    }

    public function assignUnverifiedRole(): void
    {
        if ($this->hasRole('admin')) {
            return;
        }

        $this->syncRoles([Role::findOrCreate('guest')]);
    }

    public function promoteVerifiedCustomerRole(): void
    {
        if ($this->hasRole('admin')) {
            return;
        }

        $this->syncRoles([Role::findOrCreate('customer')]);
    }
}
