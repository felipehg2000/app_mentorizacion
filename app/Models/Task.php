<?php
/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 20:58:40
 * @Last Modified by:   Felipe Hernández González
 * @Last Modified time: 2024-05-15 20:59:12
 * @Description: Modelo representativo de la tabla "TASKS" de la base de datos.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['study_room_id',
                           'task_title'   ,
                           'description'  ,
                           'last_day'     ,
                           'logic_cancel' ,
                           'created_at'   ];
}
