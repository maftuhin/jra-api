<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model{

    protected $hidden = [
        'updated_at','updated_by','created_by','published_at'
    ];

    protected $table = "news";
}