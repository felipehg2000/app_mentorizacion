<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study_room_acces extends Model {
    use HasFactory;

    protected $table = 'STUDY_ROOM_ACCESS';

    protected $fillable = ['id'           ,
                           'student_id'   ,
                           'study_room_id',
                           'logic_cancel'];
}
