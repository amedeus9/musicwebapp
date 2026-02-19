<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SongInteraction extends Model
{
    protected $fillable = ['song_id', 'type', 'user_id', 'country_code'];

    public function song()
    {
        return $this->belongsTo(Song::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
