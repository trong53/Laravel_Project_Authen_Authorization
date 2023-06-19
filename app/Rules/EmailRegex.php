<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EmailRegex implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $email_pattern = '/^[A-z][A-z0-9_\.\-]{2,}@[A-z0-9\-]{2,}(\.[A-z]{2,}){1,2}$/';
        return preg_match($email_pattern, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not correct';
    }
}
