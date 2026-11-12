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
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->id();

            $table->string('purpose', 30);
            $table->string('channel', 20);
            $table->string('recipient', 100);

            // مقدار خام OTP ذخیره نمی‌شود؛ این ستون hash کد را نگه می‌دارد.
            $table->string('code');

            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();

            $table->unsignedTinyInteger('attempts')->default(0);
            $table->unsignedTinyInteger('max_attempts')->default(5);

            $table->timestamps();

            $table->index(
                ['purpose', 'channel', 'recipient'],
                'otp_verifications_lookup_index',
            );

            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');
    }
};
