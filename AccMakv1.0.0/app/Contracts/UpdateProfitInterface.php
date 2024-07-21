<?php

namespace App\Contracts;

interface UpdateProfitInterface {
    public function updateProfit(float $amount, int $points);
}