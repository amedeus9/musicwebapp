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
            $table->string('album')->nullable()->after('artist');
            $table->string('genre')->nullable()->after('album');
            $table->string('year', 4)->nullable()->after('genre');
            $table->string('duration', 10)->nullable()->after('year'); // Format: "mm:ss"
            $table->integer('duration_seconds')->default(0)->after('duration');
            $table->integer('bitrate')->nullable()->after('duration_seconds'); // In kbps
            $table->bigInteger('file_size')->nullable()->after('bitrate'); // In bytes
            $table->text('lyrics')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropColumn([
                'album', 
                'genre', 
                'year', 
                'duration', 
                'duration_seconds', 
                'bitrate', 
                'file_size', 
                'lyrics'
            ]);
        });
    }
};
