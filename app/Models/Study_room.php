<?php

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 21:03:10
 * @Last Modified by:   undefined
 * @Last Modified time: 2024-05-15 21:03:10
 * @Description: Modelo representativo de la tabla "STUDY_ROOMS" de la base de datos.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Study_room extends Model{
    use HasFactory;

    protected $primaryKey = "mentor_id";

    protected $fillable = ['mentor_id',
                           'color'    ];
}
