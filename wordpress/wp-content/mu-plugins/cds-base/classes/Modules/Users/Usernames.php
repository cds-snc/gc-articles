<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

class Usernames
{
    public static function sanitizeUsernameAsEmail(string $username, string $raw_username = null, bool $strict): string
    {
        if ($username === $raw_username) {
            return $username;
        }

        return sanitize_email($raw_username);
    }


    public static function removeEmailColumn(array $columns): array
    {
        unset($columns['email']);
        $columns['username'] = __("Email");

        return $columns;
    }
}
