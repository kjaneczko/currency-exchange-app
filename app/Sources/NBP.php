<?php

namespace App\Sources;

use App\Source;
use App\Currency;
use Illuminate\Support\Facades\Http;

class NBP extends AbstractSource
{
    /**
     * NBP constructor. Create object and fetch NBP data from database
     */
    public function __construct()
    {
        $this->source = Source::where('name', 'NBP')->first();
    }

    /**
     * Check if this API supports choosed currency
     * @param $currency
     * @return mixed
     */
    public function chceckIfSupportsCurrency($currency) {
        return Currency::where([
            ['nbp', true],
            ['code', '=', $currency]
        ])->first();
    }

    /**
     * Fetch currency rate from API
     * @param $currency
     * @return bool|int
     */
    public function getCurrencyRate($currency) {
        if($currency === $this->source->base_currency) {
            return 1;
        }
        $table = Currency::select('nbp_table')->where('code', $currency)->first()->nbp_table;
        $response = Http::get($this->source->url . "/$table/$currency");
        if($response->status() !== 200) {
            $this->output['message'] = '503: Error occurred with API! Try again later.';
            $this->output['statusCode'] = 503;
            return false;
        }
        $rate = json_decode($response->body());
        $this->output['lastUpdate'] = $rate->rates[0]->effectiveDate;
        return $rate->rates[0]->mid;
    }

    /**
     * Calculate
     * @param $from
     * @param $to
     * @param $amount
     * @return array
     */
    public function calculate($from, $to, $amount) {
        if(!$this->chceckIfSupportsCurrency($from) || !$this->chceckIfSupportsCurrency($to)) {
            $this->output['message'] =  '400: Such currency does not exist!';
            $this->output['statusCode'] = 400;
            return $this->output;
        }
        // 4 cases:
        //      1) PLN -> PLN
        //      2) PLN -> XYZ
        //      3) XYZ -> PLN
        //      4) XYZ -> ZYX
        // case 1)
        if($from === $to) {
            $this->output['result'] = $amount;
        }
        // case 2), 3), 4)
        else {
            $currencyRateFrom = $this->getCurrencyRate($from);
            $currencyRateTo = $this->getCurrencyRate($to);
            if($currencyRateFrom && $currencyRateTo) {
                $this->output['result'] = round($amount * ($currencyRateFrom / $currencyRateTo), 2, PHP_ROUND_HALF_DOWN);
            }
        }
        return $this->output;
    }
}
