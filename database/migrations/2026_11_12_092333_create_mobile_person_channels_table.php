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
        Schema::create('mobile_person_channels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mobile_person_id')
                ->constrained('mobile_person')
                ->cascadeOnDelete();

            $table->string('channel', 30);

            $table->timestamps();

            $table->unique(['mobile_person_id', 'channel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_person_channels');
    }
};
