<?php

namespace AsiaYo\modules\OrderManaging\presentation\validators;

use AsiaYo\modules\OrderManaging\Constants;
use AsiaYo\modules\OrderManaging\presentation\validators\NormalizingOrderInfo\AmountShouldBeLessThanOrEqualToALimit;
use AsiaYo\modules\OrderManaging\presentation\validators\NormalizingOrderInfo\CurrencyCodeShouldBeInAFormatWeSupport;
use AsiaYo\modules\OrderManaging\presentation\validators\NormalizingOrderInfo\NameShouldBeTitleCased;
use AsiaYo\modules\OrderManaging\presentation\validators\NormalizingOrderInfo\NameShouldContainOnlyAlphabets;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class NormalizingOrderInfo extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => [
                'type' => class_basename(ValidationException::class),
                'message' => $validator->errors()->getMessageBag()->first(),
            ],
        ], 400));
    }

    public function rules()
    {
        return [
            'id' => [
                'bail',
                'required',
                'string',
            ],
            'name' => [
                'bail',
                'required',
                'string',
                new NameShouldContainOnlyAlphabets,
                new NameShouldBeTitleCased,
            ],
            'amount' => [
                'bail',
                'required',
                'numeric',
                // 'min: 0',
                new AmountShouldBeLessThanOrEqualToALimit(Constants::MAX_ALLOWED_AMOUNT),
            ],
            'currency' => [
                'bail',
                'required',
                'string',
                new CurrencyCodeShouldBeInAFormatWeSupport,
            ],
            'address.city' => [
                'bail',
                'required',
                'string',
            ],
            'address.district' => [
                'bail',
                'required',
                'string',
            ],
            'address.street' => [
                'bail',
                'required',
                'string',
            ],
        ];
    }
}
