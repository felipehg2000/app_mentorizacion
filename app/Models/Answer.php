<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'ANSWERS';

    protected $primaryKey = ['task_id', 'study_room_acces_id'];
    public $incrementing  = false;
    protected $keyType    = ['integer', 'integer'];

    protected $fillable   = ['task_id'            ,
                             'study_room_acces_id',
                             'type_of_document'   ,
                             'document'           ];
}
