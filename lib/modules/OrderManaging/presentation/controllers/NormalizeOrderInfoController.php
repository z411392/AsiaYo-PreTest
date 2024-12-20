<?php

namespace AsiaYo\modules\OrderManaging\presentation\controllers;

use AsiaYo\modules\OrderManaging\application\queries\NormalizeOrderInfo;
use AsiaYo\modules\OrderManaging\dtos\CurrencyCodes;
use AsiaYo\modules\OrderManaging\presentation\validators\NormalizingOrderInfo;
use Illuminate\Support\Arr;
use Throwable;

class NormalizeOrderInfoController
{
    public function __invoke(NormalizingOrderInfo $request)
    {
        $query = $request->validated();
        $currencyCode = env('CURRENCY_CODE', CurrencyCodes::TWD);
        $normalizeOrder = app(NormalizeOrderInfo::class, ['currencyCode' => $currencyCode]);
        try {
            $amount = $normalizeOrder(arr::only($query, ['currency', 'amount']));
            $order = array_replace($query, ['amount' => $amount, 'currency' => $currencyCode]);

            return response()->json([
                'payload' => [
                    'order' => $order,
                ],
            ]);
        } catch (Throwable $throwable) {
            return response()->json([
                'error' => [
                    'type' => class_basename($throwable),
                    'message' => $throwable->getMessage(),
                ],
            ], 500);
        }
    }
}
