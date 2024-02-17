<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study_room extends Model{
    use HasFactory;

    protected $primaryKey = "mentor_id";

    protected $fillable = ['mentor_id',
                           'color'    ];
}
