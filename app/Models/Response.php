<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Response extends Model
{
    protected $fillable = ['form_id', 'verification_code'];

    protected static function booted(): void
    {
        static::creating(function (Response $response) {
            $response->verification_code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        });
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
