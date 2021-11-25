<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model{

    protected $hidden = [
        'created_at','updated_at'
    ];
}