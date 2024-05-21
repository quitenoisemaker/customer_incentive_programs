<?php

namespace App\Service;

use App\Models\User;

class ReferralCodeGenerator
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function generate(): string
    {
        $codeLength = 10; // Adjust length as needed
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';

        for ($i = 0; $i < $codeLength; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        $code = substr(str_shuffle(str_repeat($characters, 5)), 0, $codeLength);

        return $this->checkAndRegenerate($code);
    }

    private function checkAndRegenerate(string $code): string
    {
        if (User::where('referral_code', $code)->exists()) {
            return $this->checkAndRegenerate($this->generate()); // Regenerate if duplicate
        }

        return $code;
    }
}
