<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend_request extends Model
{
    use HasFactory;

    protected $fillable = ['mentor_id', 'student_id', 'status'];
}
