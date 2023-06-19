<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NameRegex implements Rule
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
        $name_pattern = '/^[A-z]{1,}\s?([A-z]{1,}\'?\-?[A-z]{1,}\s?)+([A-z]{1,})?$/';
        return preg_match($name_pattern, $value);
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
