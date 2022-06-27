<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;
    protected $fillable = [
        'main_title',
        'short_tick',
        'short_desc',
        'long_desc',
        'image',
    ];

}
