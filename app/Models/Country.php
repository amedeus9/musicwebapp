<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name', 'code'];

    public function songs()
    {
        return $this->hasMany(Song::class);
    }
}
