<?php

namespace App\Exports;

class OrderExport extends Export
{
    private $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function data()
    {
        return fractal($this->orders, new Transformers\OrderTransformer)->toArray();
    }
}
