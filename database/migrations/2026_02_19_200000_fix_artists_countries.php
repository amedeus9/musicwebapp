<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Country;
use App\Models\Artist;

return new class extends Migration
{
    public function up(): void
    {
        // Add Global Country acting as fallback
        $global = Country::firstOrCreate(
            ['slug' => 'global'], 
            [
                'name' => 'Global', 
                'iso_code' => 'GL', // Use iso_code
                'region' => 'Unknown'
            ]
        );

        // Try to find Tanzania and assign to Ali Kiba specifically as requested
        $tanzania = Country::where('slug', 'tanzania')
                           ->orWhere('name', 'Tanzania')
                           ->orWhere('iso_code', 'TZ') // Use iso_code
                           ->first();
                           
        if ($tanzania) {
            $commonTZ = [
                '%Ali Kiba%',
                '%Diamond Platnumz%',
                '%Harmonize%',
                '%Rayvanny%',
                '%Zuchu%'
            ];
            foreach ($commonTZ as $name) {
                Artist::where('name', 'like', $name)->update(['country_id' => $tanzania->id]);
            }
        }

        // Set specific artists to Nigeria if possible (common ones)
        $nigeria = Country::where('slug', 'nigeria')
                          ->orWhere('name', 'Nigeria')
                          ->orWhere('iso_code', 'NG') // Use iso_code
                          ->first();
                          
        if ($nigeria) {
             $commonNG = [
                 '%Burna Boy%',
                 '%Davido%',
                 '%Wizkid%'
             ];
             foreach ($commonNG as $name) {
                 Artist::where('name', 'like', $name)->update(['country_id' => $nigeria->id]);
             }
        }

        // Set remaining artists with no country to Global to prevent 404s
        Artist::whereNull('country_id')->update(['country_id' => $global->id]);
    }

    public function down(): void
    {
        // Data migration, down method left empty intentionally.
    }
};
