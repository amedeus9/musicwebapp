<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'artist',
        'description',
        'file_path',
        'cover_path',
        'downloads',
        'country_id',
        'artist_id',
        'album_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function artistProfile()
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class)
                    ->withPivot('position')
                    ->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($song) {
            if (empty($song->slug)) {
                $song->slug = static::generateUniqueSlug($song->title);
            }
        });
    }

    protected static function generateUniqueSlug(string $title): string
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
