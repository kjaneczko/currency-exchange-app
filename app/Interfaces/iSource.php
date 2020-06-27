<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface iSource
{
    public function chceckIfSupportsCurrency($currency);
    public function getCurrencyRate($currency);
    public function calculate($from, $to, $amount);
}
