<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'answer_option_id',
        'comment',
        'status',
        'name',
        'phone'
    ];  

}
