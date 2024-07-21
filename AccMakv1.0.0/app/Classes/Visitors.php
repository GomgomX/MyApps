<?php

namespace App\Classes;

use Illuminate\Support\Facades\Cache;

class Visitors
{
    private $sessionTime;
    private $data;

    public function __construct($sessionTime = 10)
    {
        $this->data = Cache::get('visitors', []);

        $this->sessionTime = $sessionTime;
        $this->cleanVisitors();
        $this->addVisitor(request()->ip());
    }

    public function __destruct()
    {
        Cache::put('visitors', $this->data, now()->addSeconds(120));
    }

    private function cleanVisitors()
    {
        $timeNow = time();
        foreach ($this->data as $ip => $lastVisit) {
            if ($timeNow - (int)$lastVisit > $this->sessionTime * 60) {
                unset($this->data[$ip]);
            }
        }
        
    }

    private function addVisitor($ip)
    {
        $this->data[$ip] = time();
    }

    public function getAmountVisitors()
    {
        return count($this->data);
    }
}