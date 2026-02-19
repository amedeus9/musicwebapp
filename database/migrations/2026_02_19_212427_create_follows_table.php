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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Polymorphic relation: followable_id, followable_type (e.g., App\Models\Artist or App\Models\Playlist)
            $table->morphs('followable');

            $table->timestamps();
            
            // Prevent duplicate follows
            $table->unique(['user_id', 'followable_id', 'followable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
