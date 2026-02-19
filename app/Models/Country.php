<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name', 'slug', 'iso_code', 'region'];

    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    public function artists()
    {
        return $this->hasMany(Artist::class);
    }
}
