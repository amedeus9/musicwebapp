<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function collaboratedPlaylists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_user');
    }

    public function likedSongs()
    {
        return $this->hasManyThrough(
            Song::class,
            Like::class,
            'user_id',
            'id',
            'id',
            'likeable_id'
        )->where('likes.likeable_type', Song::class)
         ->orderBy('likes.created_at', 'desc');
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
    /**
     * Follow system methods
     */
    public function follow($entity)
    {
        return \App\Models\Follow::firstOrCreate([
            'user_id' => $this->id,
            'followable_id' => $entity->id,
            'followable_type' => get_class($entity)
        ]);
    }

    public function unfollow($entity)
    {
        return \App\Models\Follow::where([
            'user_id' => $this->id,
            'followable_id' => $entity->id,
            'followable_type' => get_class($entity)
        ])->delete();
    }

    public function isFollowing($entity): bool
    {
        return \App\Models\Follow::where([
            'user_id' => $this->id,
            'followable_id' => $entity->id,
            'followable_type' => get_class($entity)
        ])->exists();
    }

    public function followedArtists()
    {
        return $this->morphedByMany(Artist::class, 'followable', 'follows', 'user_id', 'followable_id');
    }

    public function followedPlaylists()
    {
        return $this->morphedByMany(Playlist::class, 'followable', 'follows', 'user_id', 'followable_id');
    }
}
