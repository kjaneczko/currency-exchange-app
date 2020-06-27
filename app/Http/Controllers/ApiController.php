<?php

namespace App\Http\Controllers;

use App\Source;
use App\Currency;
use App\Sources\NBP;
use App\Sources\FOREX;

class ApiController extends Controller
{
    /**
     * Fetch all sources from database and return as json object
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSourcesList() {
        $list = Source::all();
        if($list->count() > 0) {
            return response()->json(['list' => $list], 200);
        }
        else {
            return response()->json(['message' => '500: Source does not exist!', 'statusCode' => 500], 500);
        }
    }

    /**
     * Fetch all currencies from database that are available for specific API
     * @param $api
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrenciesList($api) {
        $list = Currency::where(strtolower($api), true)->get();
        if($list->count() > 0) {
            return response()->json(['list' => $list], 200);
        }
        else {
            return response()->json(['message' => '503: Source does not exist!', 'statusCode' => 503], 503);
        }
    }

    /**
     * Convert currency using choosed API
     * @param $api
     * @param $from
     * @param $to
     * @param $amount
     * @return \Illuminate\Http\JsonResponse
     */
    public function convert($api, $from, $to, $amount) {
        if(!$this->checkApiCallFormat($from, $to, $amount)) {
            return response()->json(["message" => "400: Wrong api call format. Should be '/ three letters currency / three letters currency / amount as natural number greater than 0', e.g. /USD/EUR/100"], 400);
        }
        switch($api) {
            case 'NBP':
                $source = new NBP();
                $result = $source->calculate($from, $to, $amount);
                break;
            case 'FOREX':
                $source = new FOREX();
                $result = $source->calculate($from, $to, $amount);
                break;
            default:
                $result = [
                    'message' => "$api API is not supported!",
                    'statusCode' => 400
                ];
                break;
        }
        return response()->json($result, $result['statusCode']);
    }

    /**
     * Check if received data are correct for api call
     * @param $from
     * @param $to
     * @param $amount
     * @return bool
     */
    private function checkApiCallFormat($from, $to, $amount) {
        if( strlen($from) !== 3 || !preg_match('/[a-zA-Z]{3}/', $from) ||
            strlen($to)   !== 3 || !preg_match('/[a-zA-Z]{3}/', $to)   ||
            (int)$amount <= 0) {
            return false;
        }
        return true;
    }
}
