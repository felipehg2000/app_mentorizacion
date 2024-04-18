<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend_request extends Model
{
    use HasFactory;

    protected $primaryKey = "mentor_id";

    protected $fillable = ['mentor_id' ,
                           'student_id',
                           'status'    ,
                           'seen_by_mentor',
                           'seen_by_student'];
}
