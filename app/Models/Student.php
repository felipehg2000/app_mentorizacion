<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = "USER_ID";
    protected $fillable   = ['user_id', 'career', 'first_year', 'duration'];
}
