<?php

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 21:00:21
 * @Last Modified by:   undefined
 * @Last Modified time: 2024-05-15 21:00:21
 * @Description: Modelo representativo de la tabla "FRIEND_REQUESTS" de la base de datos.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend_request extends Model
{
    use HasFactory;

    protected $primaryKey = "mentor_id";

    protected $fillable = ['mentor_id' ,
                           'student_id',
                           'status'    ,
                           'seen_by_mentor',
                           'seen_by_student'];
}
