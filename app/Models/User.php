<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel; // <-- TAMBAHKAN INI
use Filament\Models\Contracts\FilamentUser; // <-- INI PATH YANG BENAR
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

// IMPLEMENTASIKAN FILAMENTUSER DI SINI
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role' // Kolom ini penting
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function Transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    // ==============================================================
    // METHOD OTORISASI FILAMENT YANG BENAR ADA DI SINI
    // ==============================================================
    public function canAccessPanel(Panel $panel): bool
    {
        // Logika ini mengizinkan akses jika ID panel adalah 'admin'
        // DAN peran pengguna di database adalah 'admin'.
        return $panel->getId() === 'admin' && $this->role === 'admin';
    }
}
