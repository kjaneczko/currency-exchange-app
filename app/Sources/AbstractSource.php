<?php


namespace App\Sources;
use App\Interfaces\iSource;


abstract class AbstractSource implements iSource
{
    protected $source;
    protected $output = [
        'message' => '',
        'result' => '',
        'lastUpdate' => 'n/d',
        'statusCode' => 200
    ];
}
