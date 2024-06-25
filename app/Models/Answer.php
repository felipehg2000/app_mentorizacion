<?php

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 21:00:00
 * @Last Modified by:   undefined
 * @Last Modified time: 2024-05-15 21:00:00
 * @Description: Modelo representativo de la tabla "ANSWERS" de la base de datos.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'ANSWERS';

    protected $primaryKey = ['task_id', 'study_room_acces_id'];
    public $incrementing  = false;
    protected $keyType    = ['integer', 'integer'];

    protected $fillable   = ['task_id'            ,
                             'study_room_acces_id',
                             'name'               ,
                             'seen_by_mentor'];
}
