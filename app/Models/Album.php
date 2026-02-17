<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'artist_id',
        'cover_path',
        'release_date',
        'description',
    ];

    public function artistProfile()
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($album) {
            if (empty($album->slug)) {
                $album->slug = \Illuminate\Support\Str::slug($album->title);
            }
        });
    }
}
