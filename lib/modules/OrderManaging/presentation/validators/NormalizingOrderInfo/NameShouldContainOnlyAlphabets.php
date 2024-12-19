<?php

namespace AsiaYo\modules\OrderManaging\presentation\validators\NormalizingOrderInfo;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NameShouldContainOnlyAlphabets implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /**
         * @var string $value
         **/
        $words = preg_split('/ +/', $value);
        foreach ($words as $word) {
            $valid = preg_match('/^[a-z]+$/i', $word);
            if ($valid) {
                continue;
            }
            $fail(':attribute contains non-English characters.');

            return;
        }
    }
}
