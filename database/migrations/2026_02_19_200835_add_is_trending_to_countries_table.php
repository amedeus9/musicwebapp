<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->boolean('is_trending')->default(false)->index();
        });

        // Seed initial values based on current hardcoded logic
        DB::table('countries')
            ->whereIn('iso_code', ['TZ', 'KE', 'NG', 'ZA', 'GH'])
            ->update(['is_trending' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            //
        });
    }
};
