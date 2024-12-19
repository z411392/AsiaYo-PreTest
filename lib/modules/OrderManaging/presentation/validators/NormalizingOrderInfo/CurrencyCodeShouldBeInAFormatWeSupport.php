<?php

namespace AsiaYo\modules\OrderManaging\presentation\validators\NormalizingOrderInfo;

use AsiaYo\modules\OrderManaging\dtos\CurrencyCodes;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CurrencyCodeShouldBeInAFormatWeSupport implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /**
         * @var string $value
         **/
        if ($value === CurrencyCodes::TWD) {
            return;
        }
        if ($value === CurrencyCodes::USD) {
            return;
        }
        $fail(':attribute format is wrong.');
    }
}
