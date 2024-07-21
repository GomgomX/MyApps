<?php

namespace App\Listeners;

use App\Events\TransactionProcessed;
use App\Mail\PremiumPointsAdded;
use Illuminate\Support\Facades\Mail;

class SendPremiumPointsNotification
{
    public function handle(TransactionProcessed $event): void
    {
        if($event->account && config('custom.send_emails')) {
            $accountPlayers = $event->account->getPlayers;
            $receiver = $accountPlayers->count() ? $accountPlayers->first()->name : app('server_config')['serverName'].' Player';
            Mail::to([['name' => $receiver, 'email' => $event->account->email]])->send(new PremiumPointsAdded($event->points, $event->amount, $event->payingMethod));
        }
    }
}
