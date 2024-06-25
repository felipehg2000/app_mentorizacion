<?php

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 21:02:27
 * @Last Modified by:   Felipe Hernández González
 * @Last Modified time: 2024-05-15 21:02:58
 * @Description: Modelo representativo de la tabla "STUDY_ROOM_ACCESS" de la base de datos.
 */

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
