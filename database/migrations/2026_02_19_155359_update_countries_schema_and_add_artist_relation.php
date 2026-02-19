<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            if (!Schema::hasColumn('countries', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('countries', 'region')) {
                $table->string('region')->nullable()->after('name');
            }
            if (Schema::hasColumn('countries', 'code') && !Schema::hasColumn('countries', 'iso_code')) {
                $table->renameColumn('code', 'iso_code');
            }
            if (!Schema::hasColumn('countries', 'code') && !Schema::hasColumn('countries', 'iso_code')) {
                 $table->string('iso_code')->nullable()->after('slug');
            }
        });

        Schema::table('artists', function (Blueprint $table) {
            if (!Schema::hasColumn('artists', 'country_id')) {
                $table->foreignId('country_id')->nullable()->after('bio')->constrained('countries')->nullOnDelete();
            }
        });

        Schema::table('songs', function (Blueprint $table) {
            if (!Schema::hasColumn('songs', 'country_id')) {
                $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artists', function (Blueprint $table) {
             $table->dropForeign(['country_id']);
             $table->dropColumn('country_id');
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('region');
            $table->renameColumn('iso_code', 'code');
        });
    }
};
