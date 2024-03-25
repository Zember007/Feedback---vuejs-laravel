<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'survey_id',
        'text',
    ];
    public function answer_options(): HasMany
    {
        return $this->hasMany(Answer_option::class);
    }
}
