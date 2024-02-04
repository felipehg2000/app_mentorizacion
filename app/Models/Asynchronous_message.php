<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asynchronous_message extends Model {
    use HasFactory;

    protected $fillable = ['id'           ,
                           'study_room_id',
                           'sender'       ,
                           'message'      ,
                           'document'     ,
                           'type_of_document'];
}
