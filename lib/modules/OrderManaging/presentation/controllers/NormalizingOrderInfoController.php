<?php

namespace AsiaYo\modules\OrderManaging\presentation\controllers;

use AsiaYo\modules\OrderManaging\application\queries\NormalizeOrderInfo;
use AsiaYo\modules\OrderManaging\presentation\validators\NormalizingOrderInfo;

class NormalizingOrderInfoController
{
    public function __invoke(NormalizingOrderInfo $request)
    {
        $query = $request->validated();
        $normalizeOrder = new NormalizeOrderInfo;
        $order = $normalizeOrder($query);

        return response()->json([
            'payload' => [
                'order' => $order,
            ],
        ]);
    }
}
