<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutoring extends Model
{
    use HasFactory;

    protected $table = 'TUTORING';
    protected $fillable = ['study_room_id'      ,
                           'study_room_acces_id',
                           'date'               ,
                           'status'             ];
}
