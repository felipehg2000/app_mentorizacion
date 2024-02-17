<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['id'           ,
                           'study_room_id',
                           'statment'     ,
                           'last_day'     ,
                           'created_at'   ];
}
