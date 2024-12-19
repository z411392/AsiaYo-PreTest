<?php

namespace AsiaYo\modules\OrderManaging\presentation\validators\NormalizingOrderInfo;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NameShouldBeTitleCased implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /**
         * @var string $value
         **/
        $words = preg_split('/ +/', $value);
        foreach ($words as $word) {
            $valid = preg_match('/^[A-Z][a-z]*$/', $word);
            if ($valid) {
                continue;
            }
            $fail(':attribute is not capitalized.');

            return;
        }
    }
}
