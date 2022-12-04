<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'grid_card',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var mixed
     */
    private $role;

    /**
     * @var int
     */
    static public $gridSize = 9;

    /**
     * @var int
     */
    static public $challengeSize = 3;

    /**
     * Renvoie les rôles d'un utilisateur
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Assigne un rôle à un utilisateur
     * @param $role
     * @return void
     */
    public function assignRole($role)
    {
        $this->role = $role;
        $this->roles()->save($role);
    }

    /**
     * Retourne les rôles de l'utilisateur
     * @return mixed
     */
    public function abilities()
    {
        return $this->roles->map->abilities->flatten()->pluck('name')->unique();
    }

    /**
     * Génére une grid card
     * @return string
     */
    static public function generateGridCard()
    {
        $size = User::$gridSize;
        $gridCard = Str::random($size);
        error_log($gridCard);
        return $gridCard;
    }
}
