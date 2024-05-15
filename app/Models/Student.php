<?php

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 21:02:09
 * @Last Modified by:   undefined
 * @Last Modified time: 2024-05-15 21:02:09
 * @Description: Modelo representativo de la tabla "STUDENTS" de la base de datos.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = "USER_ID";
    protected $fillable   = ['user_id'   ,
                             'career'    ,
                             'first_year',
                             'duration'];

}
