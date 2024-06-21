<?php

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 21:00:43
 * @Last Modified by:   undefined
 * @Last Modified time: 2024-05-15 21:00:43
 * @Description: Modelo representativo de la tabla "MENTORS" de la base de datos.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentor extends Model
{
    use HasFactory;
    protected $primaryKey = "USER_ID";
    protected $fillable = ['user_id', 'company', 'job'];
}
