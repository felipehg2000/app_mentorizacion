<?php

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 21:03:31
 * @Last Modified by:   undefined
 * @Last Modified time: 2024-05-15 21:03:31
 * @Description: Modelo representativo de la tabla "SYNCHRONOUS_MESSAGES" de la base de datos.
 */


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Synchronous_message extends Model {
    use HasFactory;

    protected $table = 'SYNCHRONOUS_MESSAGES';

    protected $fillable = ['study_room_id'      ,
                           'study_room_acces_id',
                           'sender'             ,
                           'message'            ,
                           'seen_by_mentor'     ,
                           'seen_by_student'];
}
