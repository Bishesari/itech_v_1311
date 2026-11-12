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
        Schema::create('mobile_person', function (Blueprint $table) {
            $table->id();

            $table->foreignId('person_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('mobile_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->boolean('is_primary')->default(false);
            $table->boolean('is_verified')->default(false);

            $table->timestamps();

            $table->unique(['person_id', 'mobile_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_person');
    }
};
