<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Synchronous_message extends Model {
    use HasFactory;

    protected $table = 'SYNCHRONOUS_MESSAGES';

    protected $fillable = ['study_room_id'      ,
                           'study_room_acces_id',
                           'sender'             ,
                           'message'            ,
                           'seen_by_mentor'     ,
                           'seen_by_student'];
}
