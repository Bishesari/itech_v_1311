<?php

namespace App\Contracts;

interface SmsGateway
{
    /**
     * @throws \Throwable
     */
    public function sendOtp(string $mobile, string $verificationCode): void;

    /**
     * @throws \Throwable
     */
    public function sendPassword(
        string $mobile,
        string $username,
        string $password,
    ): void;
}
