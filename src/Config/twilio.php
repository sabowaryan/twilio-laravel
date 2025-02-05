<?php



return [
    /*
    |--------------------------------------------------------------------------
    | Identifiant de compte Twilio
    |--------------------------------------------------------------------------
    */
    'account_sid' => env('TWILIO_ACCOUNT_SID', ''),

    /*
    |--------------------------------------------------------------------------
    | Jeton d'authentification Twilio
    |--------------------------------------------------------------------------
    */
    'auth_token' => env('TWILIO_AUTH_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Numéro d'envoi Twilio
    |--------------------------------------------------------------------------
    */
    'from_number' => env('TWILIO_FROM_NUMBER', ''),

    /*
    |--------------------------------------------------------------------------
    | SID du service Verify Twilio
    |--------------------------------------------------------------------------
    | Pour utiliser l'API Verify (vérification de numéro), vous devez créer un
    | service dans la console Twilio et récupérer son SID.
    */
    'verify_service_sid' => env('TWILIO_VERIFY_SERVICE_SID', ''),
];
