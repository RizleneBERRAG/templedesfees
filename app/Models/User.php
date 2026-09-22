<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Qui entre dans le back-office.
     *
     * Sans ce contrat, Filament laisse passer n'importe quel utilisateur
     * authentifie des que APP_ENV n'est plus 'local'. Le site n'ouvre aucune
     * inscription : les seuls comptes sont ceux crees a la main pour l'elevage.
     * La liste explicite evite qu'un compte cree plus tard pour autre chose
     * herite de l'acces sans qu'on l'ait decide.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->email, config('chatterie.back_office.emails', []), true);
    }
}
