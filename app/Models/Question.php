<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['type', 'question_text', 'is_required', 'order'];

    protected $casts = [
        'is_required' => 'boolean',
        'order' => 'integer',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
