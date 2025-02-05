<?php

namespace Sabow\TwilioLaravel\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;
use Sabow\TwilioLaravel\Services\TwilioClient;
use Twilio\Rest\Client; // N'oubliez pas d'importer la classe Client du SDK Twilio

class TwilioServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap des services du package.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
        }
    }

    /**
     * Enregistrer le service dans le conteneur d'injection de dépendances.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/config/twilio.php',
            'twilio'
        );




        // Enregistrer un binding pour TwilioClient
        $this->app->bind('TwilioClient', function () {
            // Vérifie que toutes les valeurs nécessaires de configuration sont renseignées
            $this->ensureConfigValuesAreSet();

            // Récupération des valeurs de configuration
            $accountSid       = config('twilio.account_sid');
            $authToken        = config('twilio.auth_token');
            $fromNumber       = config('twilio.from_number');
            $verifyServiceSid = config('twilio.verify_service_sid');

            // Créer une instance du client Twilio
            $client = new Client($accountSid, $authToken);

            // Retourner une instance de TwilioClient en lui passant le client Twilio et les autres paramètres
            return new TwilioClient($client, $fromNumber, $verifyServiceSid);
        });
    }

    /**
     * Vérifie que les valeurs de configuration obligatoires sont bien définies.
     *
     * @throws \Exception
     */
    protected function ensureConfigValuesAreSet()
    {
        // Récupère la configuration complète du package
        $mandatoryAttributes = config('twilio');

        foreach ($mandatoryAttributes as $key => $value) {
            if (empty($value)) {
                throw new Exception("Veuillez fournir une valeur pour '{$key}' dans la configuration Twilio.");
            }
        }
    }

    /**
     * Publier le fichier de configuration dans le dossier config de l'application.
     */
    protected function publishConfig()
    {
        $this->publishes([
            dirname(__DIR__) . '/config/twilio.php'
            => config_path('twilio.php'),
        ], 'config');
    }
}
