<?php

namespace Sabow\TwilioLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class Twilio extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Sabow\TwilioLaravel\Services\TwilioClient::class;
    }
}
