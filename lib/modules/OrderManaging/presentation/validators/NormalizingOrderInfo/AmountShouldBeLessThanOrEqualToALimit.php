<?php

namespace AsiaYo\modules\OrderManaging\presentation\validators\NormalizingOrderInfo;

use AsiaYo\modules\OrderManaging\Constants;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AmountShouldBeLessThanOrEqualToALimit implements ValidationRule
{
    protected string $limit;

    public function __construct(string $limit)
    {
        $this->limit = $limit;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /**
         * @var int $value
         **/
        $valid = bccomp($value, Constants::MAX_ALLOWED_AMOUNT, Constants::FLOAT_PRECISION) <= 0;
        if ($valid) {
            return;
        }
        $fail(vsprintf(':attribute is over %s.', [Constants::MAX_ALLOWED_AMOUNT]));
    }
}
