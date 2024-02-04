<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study_room_acces extends Model {
    use HasFactory;

    protected $fillable = ['id'        ,
                           'student_id',
                           'study_room_id'];
}
