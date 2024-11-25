<?php

use App\Models\Bridge;
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
        Schema::create('bridge_service', function (Blueprint $table) {
            $table->foreignId('bridge_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->timestamps();

            $table->bigInteger('batch');
            $table->bigInteger('priority');

            // in, out, relay
            $table->string('type');

            $table->primary(['bridge_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bridge_service');
    }
};
