<?php

namespace Sabow\TwilioLaravel\Services;

use Twilio\Rest\Client;

class TwilioClient
{
    protected $client;
    protected $fromNumber;
    protected $verifyServiceSid;

    /**
     * Constructeur.
     *
     * @param Client $client Instance déjà configurée du client Twilio
     * @param string $fromNumber
     * @param string|null $verifyServiceSid
     */
    public function __construct(Client $client, $fromNumber, $verifyServiceSid = null)
    {
        $this->client = $client;
        $this->fromNumber = $fromNumber;
        $this->verifyServiceSid = $verifyServiceSid;
    }

    // Les méthodes sendSms(), startPhoneVerification() et checkPhoneVerification() restent identiques

    public function sendSms($to, $message)
    {
        return $this->client->messages->create($to, [
            'from' => $this->fromNumber,
            'body' => $message,
        ]);
    }

    public function startPhoneVerification($to, $channel = 'sms')
    {
        if (!$this->verifyServiceSid) {
            throw new \Exception('Le SID du service Verify n\'est pas configuré.');
        }

        return $this->client->verify->v2->services($this->verifyServiceSid)
            ->verifications
            ->create($to, $channel);
    }

    public function checkPhoneVerification($to, $code)
    {
        if (!$this->verifyServiceSid) {
            throw new \Exception('Le SID du service Verify n\'est pas configuré.');
        }

        return $this->client->verify->v2->services($this->verifyServiceSid)
            ->verificationChecks
            ->create($code, ['to' => $to]);
    }
}
