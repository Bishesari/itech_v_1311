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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();

            $table->string('short_name', 30);
            $table->string('code', 7)->unique();

            $table->boolean('is_main')->default(false)->index();

            $table->foreignId('province_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedBigInteger('city_id');

            $table->foreign(['city_id', 'province_id'])
                ->references(['id', 'province_id'])
                ->on('cities')
                ->cascadeOnDelete();

            $table->string('address', 150)->nullable();
            $table->string('postal_code', 10)->nullable()->index();
            $table->string('phone', 15)->nullable();
            $table->string('mobile', 15)->nullable();

            $table->boolean('is_active')->default(true)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
