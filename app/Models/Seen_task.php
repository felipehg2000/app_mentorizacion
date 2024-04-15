<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seen_task extends Model
{
    use HasFactory;

    protected $table = 'SEEN_TASKS';

    protected $primaryKey = ['task_id', 'user_id'];
    public $incrementing  = false;
    protected $keyType    = ['integer', 'integer'];

    protected $fillable = ['task_id',
                           'user_id',
                           'seen_task'];
}
