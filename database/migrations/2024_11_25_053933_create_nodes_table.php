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
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('node_group_id')->nullable()->constrained();
            $table->string('name');
            $table->ipAddress('ip_address');
            $table->string('domain')->nullable();
            $table->text('public_key');
            $table->boolean('active')->default(true);
            $table->boolean('up')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};
