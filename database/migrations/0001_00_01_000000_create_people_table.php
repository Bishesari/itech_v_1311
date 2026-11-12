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
        Schema::create('people', function (Blueprint $table) {
            $table->id();

            $table->unsignedTinyInteger('nationality')->nullable();

            $table->string('identity', 20)->nullable()->unique();

            $table->boolean('gender')->nullable();
            $table->string('first_name_fa', 30)->nullable();
            $table->string('last_name_fa', 40)->nullable();
            $table->string('nickname', 30)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
