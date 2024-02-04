<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study_room extends Model{
    use HasFactory;

    protected $fillable = ['id'       ,
                           'mentor_id',
                           'color'];
}
