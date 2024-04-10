<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report_request extends Model
{
    use HasFactory;

    protected $fillable = ['reported',
                           'reporter',
                           'reason'  ,
                           'seen'];
}
