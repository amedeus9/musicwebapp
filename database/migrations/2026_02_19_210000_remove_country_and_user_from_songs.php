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
        Schema::table('songs', function (Blueprint $table) {
            // Check if columns exist before dropping to avoid errors
            if (Schema::hasColumn('songs', 'country_id')) {
                $table->dropForeign(['country_id']); // Drop foreign key if exists
                $table->dropColumn('country_id');
            }
            if (Schema::hasColumn('songs', 'user_id')) {
                 $table->dropForeign(['user_id']); // Drop foreign key if exists
                 $table->dropColumn('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('country_id')->nullable()->constrained()->onDelete('set null');
        });
    }
};
