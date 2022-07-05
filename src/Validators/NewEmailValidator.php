<?php

namespace Emails\Validators;

class NewEmailValidator
{
    public static function validate(array $email): bool
    {
        return (
            !empty($email['name']) &&
            !empty($email['email']) &&
            !empty($email['subject']) &&
            !empty($email['body']) &&
            (
                (
                    !empty($email['reply']) &&
                    is_numeric($email['reply'])
                ) ||
                empty($email['reply'])
            )
        );
    }
}