<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $hidden = [
        "created_at","updated_at"
    ];

    protected $casts = [
        'tanggal' => 'datetime:d M Y H:s',
    ];
    
}
