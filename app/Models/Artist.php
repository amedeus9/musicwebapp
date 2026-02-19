<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'bio',
        'image_path',
        'country_id',
    ];

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function followers()
    {
        return $this->morphToMany(User::class, 'followable', 'follows', 'followable_id', 'user_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($artist) {
            if (empty($artist->slug)) {
                $artist->slug = \Illuminate\Support\Str::slug($artist->name);
            }
        });
    }
}
