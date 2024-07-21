<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Contracts\UpdateProfitInterface;

class UpdateProfitService implements UpdateProfitInterface {
    public function updateProfit(float $amount, int $points) {
        $profitRecord = DB::table('profit')->where('month', date("m"))->where('end', '>', time())->first('points');
        if($profitRecord) {
            DB::table('profit')->where('month', date("m"))->where('end', '>', time())->increment('amount', $amount, ['points' => $profitRecord->points+$points, 'lastupdated' => date("g:i:s (A) | d-m-Y")]);
        } else {
            $values = [
                'month' => date("m"),
                'amount' => $amount,
                'points' => $points,
                'inserted' => date("g:i:s (A) | d-m-Y"),
                'lastupdated' => date("g:i:s (A) | d-m-Y"),
                'end' => time() + (30 * 24 * 60 * 60)
            ];

            DB::table('profit')->insert($values);
        }
    }
}