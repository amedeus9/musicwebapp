<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{

    protected $fillable = [
        // 'user_id', // Removed
        'title',
        'slug',
        // 'artist', // Removed
        'description',
        'file_path',
        'cover_path',
        'downloads',
        // 'country_id', // Removed
        // 'album', // Removed
        'artist_id',
        'album_id',
        'genre',
        'year',
        'duration',
        'duration_seconds',
        'bitrate',
        'file_size',
        'lyrics',
    ];

    public function getArtistAttribute()
    {
        return $this->artistProfile?->name ?? 'Unknown Artist';
    }

    public function getAlbumAttribute()
    {
        return $this->albumRelation?->title;
    }

    /*
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    */

    public function artistProfile()
    {
        return $this->belongsTo(Artist::class, 'artist_id');
    }

    public function albumRelation()
    {
        return $this->belongsTo(Album::class, 'album_id');
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

    public function interactions()
    {
        return $this->hasMany(SongInteraction::class);
    }

    public function scopeTrending($query, $artistCountryId = null, $interactionCountryCode = null)
    {
        $cutoff = now()->subHours(24);

        $query->withCount([
            'interactions as recent_plays_count' => function ($q) use ($cutoff, $interactionCountryCode) {
                $q->where('type', 'play')->where('created_at', '>=', $cutoff);
                if ($interactionCountryCode) {
                    $q->where('country_code', $interactionCountryCode);
                }
            },
            'interactions as recent_downloads_count' => function ($q) use ($cutoff, $interactionCountryCode) {
                $q->where('type', 'download')->where('created_at', '>=', $cutoff);
                if ($interactionCountryCode) {
                    $q->where('country_code', $interactionCountryCode);
                }
            },
            'likes as recent_likes_count' => function ($q) use ($cutoff) {
                $q->where('created_at', '>=', $cutoff);
            }
        ]);

        if ($artistCountryId) {
             $query->whereHas('artistProfile', function ($q) use ($artistCountryId) {
                 $q->where('country_id', $artistCountryId);
             });
        }

        return $query->orderByRaw('(recent_plays_count + recent_downloads_count + recent_likes_count) DESC')->latest();
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
