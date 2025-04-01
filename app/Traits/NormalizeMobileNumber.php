<?php

namespace App\Traits;

trait NormalizeMobileNumber
{
    /**
     * Normalize a mobile number to a standard format.
     *
     * @param  string  $mobile  The mobile number to normalize
     * @return string The normalized mobile number
     */
    protected function normalizeMobileNumber(string $mobile): string
    {
        // Clean the mobile number by removing any non-numeric characters
        $mobile = preg_replace('/[^0-9]/', '', $mobile);

        // If number starts with 09, replace with 989
        if (str_starts_with($mobile, '09')) {
            return '989'.substr($mobile, 2);
        }
        // If number starts with just 0 (but not 09), replace with 98
        elseif (str_starts_with($mobile, '0')) {
            return '98'.substr($mobile, 1);
        }

        // Return the cleaned mobile number
        return $mobile;
    }

    /**
     * Check if the given string is an email address
     *
     * @param  string  $identify  The string to check
     * @return bool True if the string is an email address, false otherwise
     */
    protected function isEmail(string $identify): bool
    {
        return filter_var($identify, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check if a given identifier is a mobile number (not an email)
     *
     * @param  string  $identifier  The identifier to check
     * @return bool True if the identifier is a mobile number, false if it's an email
     */
    protected function isMobileNumber(string $identifier): bool
    {
        return ! filter_var($identifier, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Normalize an identifier if it's a mobile number, otherwise return it unchanged
     *
     * @param  string  $identifier  The identifier (mobile or email)
     * @return string The normalized identifier
     */
    protected function normalizeIdentifier(string $identifier): string
    {
        if ($this->isMobileNumber($identifier)) {
            return $this->normalizeMobileNumber($identifier);
        }

        return $identifier;
    }
}
