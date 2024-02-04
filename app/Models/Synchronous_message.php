<?php

namespace Apps\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Synchronous_message extends Model {
    use HasFactory;

    protected $fillable = ['id'           ,
                           'study_room_id',
                           'sender'       ,
                           'message'];
}
