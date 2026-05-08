<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Form extends Model
{
    protected $fillable = [
        'user_id',
        'uuid',
        'slug',
        'title',
        'description',
        'is_public'
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($form) {
            if (!$form->uuid) {
                $form->uuid = (string) \Illuminate\Support\Str::uuid();
            }
            if (!$form->slug) {
                $form->generateUniqueSlug();
            }
        });

        static::updating(function ($form) {
            if ($form->isDirty('title')) {
                $form->generateUniqueSlug();
            }
        });
    }

    public function generateUniqueSlug()
    {
        $baseSlug = \Illuminate\Support\Str::slug($this->title);
        $slug = strtoupper($baseSlug);
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = strtoupper($baseSlug) . '-' . $count++;
        }

        $this->slug = $slug;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }
}
