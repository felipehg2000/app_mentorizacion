<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2023-03-06 23:20:03
 * @Last Modified by: Felipe Hernández González
 * @Last Modified time: 2024-04-07 23:01:12
 * @Description: Modelo encargado de gestionar la tabla Users de la base de datos.
 */


class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
    use HasFactory;

    protected $fillable = ['name', 'surname', 'email', 'user', 'password', 'user_type', 'study_area', 'description', 'banned'];
}
