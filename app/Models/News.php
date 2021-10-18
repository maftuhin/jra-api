<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model{

    protected $hidden = [
        'updated_at','published_at','created_by','updated_by'
    ];
}