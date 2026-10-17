<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_CLIENT = 'client';
    public const ROLE_ADMIN = 'admin';

    /**
     * « role » n'est volontairement PAS dans cette liste : personne ne peut
     * devenir administrateur via un formulaire. Un admin se crée uniquement
     * avec la commande « php artisan fabara:create-admin ».
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'terms_accepted_at',
        'privacy_version',
        'marketing_opt_in',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
            'last_login_at' => 'datetime',
            'marketing_opt_in' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function devisRequests(): HasMany
    {
        return $this->hasMany(DevisRequest::class);
    }

    public function homeRoute(): string
    {
        return $this->isAdmin() ? route('admin.dashboard') : route('client.dashboard');
    }
}
