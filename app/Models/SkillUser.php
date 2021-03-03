<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillUser extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    protected $table = "skill_users";
}
