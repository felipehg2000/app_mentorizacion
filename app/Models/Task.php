<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['study_room_id',
                           'task_title'   ,
                           'description'  ,
                           'statement'    ,
                           'last_day'     ,
                           'created_at'   ];
}
