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
        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('creator_id')->constrained('users');
            $table->string('code');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('consumed_at')->nullable();
            $table->foreignId('consumer_id')->constrained('users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invites');
    }
};
