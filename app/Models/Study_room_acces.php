<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study_room_acces extends Model {
    use HasFactory;

    protected $table      = 'STUDY_ROOM_ACCESS';
    protected $primaryKey = "student_id"       ;

    protected $fillable = ['student_id'   ,
                           'study_room_id',
                           'logic_cancel'];
}
