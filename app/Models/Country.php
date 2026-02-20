<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name', 'slug', 'iso_code', 'region', 'is_trending'];

    protected $casts = [
        'is_trending' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($country) {
            $country->slug = $country->slug ?? \Illuminate\Support\Str::slug($country->name);
        });
    }

    public function songs()
    {
        return $this->hasManyThrough(Song::class, Artist::class);
    }

    public function artists()
    {
        return $this->hasMany(Artist::class);
    }
}
