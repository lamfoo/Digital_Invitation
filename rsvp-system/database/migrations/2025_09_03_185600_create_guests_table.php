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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->uuid('unique_link_token')->unique();
            $table->enum('rsvp_status', ['pending', 'yes', 'no', 'maybe'])->default('pending');
            $table->datetime('rsvp_confirmed_at')->nullable();
            $table->timestamps();

            $table->index(['event_id']);
            $table->index(['unique_link_token']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
