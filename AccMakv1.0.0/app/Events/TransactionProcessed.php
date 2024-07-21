<?php

namespace App\Events;

use App\Models\Account;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TransactionProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $account;
    public $points;
    public $amount;
    public $payingMethod;

    public function __construct(Account $account, int $points, string $amount, string $payingMethod)
    {
        $this->account = $account;
        $this->points = $points;
        $this->amount = $amount;
        $this->payingMethod = $payingMethod;
    }
}
