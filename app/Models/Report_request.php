<?php

/*
 * @Author: Felipe Hernández González
 * @Email: felipehg2000@usal.es
 * @Date: 2024-05-15 21:01:25
 * @Last Modified by:   undefined
 * @Last Modified time: 2024-05-15 21:01:25
 * @Description: Modelo representativo de la tabla "REPORT_REQUESTS" de la base de datos.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report_request extends Model
{
    use HasFactory;

    protected $fillable = ['reported',
                           'reporter',
                           'reason'  ,
                           'seen'];
}
