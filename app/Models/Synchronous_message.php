<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Synchronous_message extends Model {
    use HasFactory;

    protected $table = 'SYNCHRONOUS_MESSAGES';

    protected $fillable = ['id'           ,
                           'study_room_id',
                           'sender'       ,
                           'message'];
}
