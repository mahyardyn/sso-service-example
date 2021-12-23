<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'permissions'
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'name', 'role');
    }
}
