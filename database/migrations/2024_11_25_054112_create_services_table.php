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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->text('description')->nullable();

            $table->foreignId('node_id')->constrained();
            $table->string('provider');
            $table->jsonb('provider_params');
            $table->float('traffic_rate')->default(1);
            $table->boolean('inbound')->default(true);
            $table->boolean('outbound')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
