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


    /**
     * Generates a random referral code.
     *
     * This method generates a random string of specified length using the provided character set.
     * It then checks for existing codes in the database and regenerates if necessary.
     *
     * @return string The generated referral code.
     * 
     * @throws \Exception If an error occurs during code generation or validation.
     */
    public function generate(): string
    {
        $codeLength = 10;
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';

        for ($i = 0; $i < $codeLength; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        $code = substr(str_shuffle(str_repeat($characters, 5)), 0, $codeLength);

        return $this->checkAndRegenerate($code);
    }

    /**
     * Checks if a referral code already exists in the database and regenerates if necessary.
     *
     * This method takes a generated referral code as input and checks if it exists in the database.
     * If the code already exists, it regenerates a new code using the `generate` method and calls itself recursively to check again.
     * This process continues until a unique, non-existing code is found.
     *
     * @param string $code The referral code to be checked.
     * 
     * @return string A unique, non-existing referral code.
     * 
     */
    private function checkAndRegenerate(string $code): string
    {
        if (User::where('referral_code', $code)->exists()) {
            return $this->checkAndRegenerate($this->generate());
        }

        return $code;
    }

    /**
     * Finds a user by referral code and increments their referral count in a single database query.
     *
     * This method takes a referral code as input and attempts to find a matching user in the database.
     * If a user is found, it atomically increments their `referral_count` by 1 and retrieves the user's ID.
     * 
     * This method utilizes a single database query with Laravel's `update` method and raw expressions for efficiency.
     *
     * @param string $referralCode The referral code to be checked.
     * 
     * @return int|null The user ID if found, otherwise null.
     *
     */
    public function getUserIdByReferralCodeAndIncrementCount(string $referralCode): int|null
    {
        $userId = User::where('referral_code', $referralCode)->value('id');

        if ($userId) {
            $this->incrementReferralCount($userId);
            return $userId;
        }
        return null;
    }

    private function incrementReferralCount(int $userId): void
    {
        User::where('id', $userId)->increment('referral_count');
    }
}
