<?php

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 21:01:04
 * @Last Modified by:   undefined
 * @Last Modified time: 2024-05-15 21:01:04
 * @Description: Modelo representativo de la tabla "SEEN_TASKS" de la base de datos.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seen_task extends Model
{
    use HasFactory;

    protected $table = 'SEEN_TASKS';

    protected $primaryKey = ['task_id', 'user_id'];
    public $incrementing  = false;
    protected $keyType    = ['integer', 'integer'];

    protected $fillable = ['task_id',
                           'user_id',
                           'seen_task'];
}
