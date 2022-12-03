<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    /**
     * Retourne les abilities liés à ce rôle
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function abilities()
    {
        return $this->belongsToMany(Ability::class);
    }

    /**
     * Ajoute une abilité à ce rôle
     * @param $ability
     * @return void
     */
    public function allowTo($ability)
    {
        $this->abilities()->save($ability);
    }
}
