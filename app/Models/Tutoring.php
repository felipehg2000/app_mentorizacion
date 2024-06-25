<?php

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 21:04:38
 * @Last Modified by:   undefined
 * @Last Modified time: 2024-05-15 21:04:38
 * @Description: Modelo representativo de la tabla "TUTORINGS" de la base de datos.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutoring extends Model
{
    use HasFactory;

    protected $table = 'TUTORING';
    protected $fillable = ['study_room_id'      ,
                           'study_room_acces_id',
                           'date'               ,
                           'status'             ,
                           'seen_by_mentor'     ,
                           'seen_by_student'];
}
