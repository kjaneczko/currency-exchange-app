<?php
namespace App\Sources;

use App\Currency;
use App\Source;
use Illuminate\Support\Facades\Http;

class FOREX extends AbstractSource
{
    /**
     * FOREX constructor. Create object and fetch FOREX data from database
     */
    public function __construct()
    {
        $this->source = Source::where('name', 'FOREX')->first();
    }

    /**
     * Check if this API supports choosed currency
     * @param $currency
     * @return mixed
     */
    public function chceckIfSupportsCurrency($currency) {
        return Currency::where([
            ['forex', true],
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
        $response = Http::get($this->source->url. "&to=$currency&key=" . $this->source->key);
        if($response->status() !== 200) {
            $this->output['message'] = '503: Error occurred with API! Try again later.';
            $this->output['statusCode'] = 503;
            return false;
        }
        $rate = json_decode($response->body());
        $this->output['lastUpdate'] = $rate->infos->date;
        return $rate->rates->$currency;
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
        //      1) USD -> USD
        //      2) USD -> XYZ
        //      3) XYZ -> USD
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
                $this->output['result'] = round(($amount * ($currencyRateTo / $currencyRateFrom)), 2, PHP_ROUND_HALF_DOWN);
            }
        }
        return $this->output;
    }
}
