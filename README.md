# Sabow TwilioLaravel

**Sabow TwilioLaravel** est un package Laravel qui intègre l'API [Twilio](https://www.twilio.com) pour l'envoi de SMS et la vérification de numéros de téléphone via l'API Verify de Twilio. Ce package vous permet d'ajouter facilement des fonctionnalités de communication et de sécurité par SMS à vos applications Laravel.

## Caractéristiques

- **Envoi de SMS** : Envoyez des messages SMS en toute simplicité grâce à l'API Twilio.
- **Vérification de numéros** : Démarrez et vérifiez le processus de vérification des numéros via SMS ou appel vocal avec l'API Verify.
- **Intégration Laravel** : Profitez de l'auto-discovery de Laravel pour enregistrer automatiquement le Service Provider et la façade.
- **Configuration flexible** : Publiez et personnalisez le fichier de configuration pour adapter le package à votre compte Twilio.
- **Utilisation simplifiée** : Utilisez la façade statique ou l'injection de dépendances pour intégrer rapidement les fonctionnalités de Twilio dans vos contrôleurs et services.

## Prérequis

- PHP >= 7.3
- Laravel 8.x, 9.x ou 10.x
- Un compte Twilio avec les informations suivantes :  
  - **Account SID**
  - **Auth Token**
  - **From Number** (numéro d'envoi)
  - **Verify Service SID** (pour la vérification de numéros)

## Installation

Installez le package via Composer :

```bash
composer require sabow/twilio-laravel

Laravel détecte automatiquement le Service Provider et la façade grâce à l'auto-discovery. Si vous utilisez une version de Laravel qui ne supporte pas cette fonctionnalité, ajoutez manuellement le Service Provider et l'alias dans le fichier config/app.php :

'providers' => [
    // ...
    Sabow\TwilioLaravel\Providers\TwilioServiceProvider::class,
],

'aliases' => [
    // ...
    'Twilio' => Sabow\TwilioLaravel\Facades\Twilio::class,
],

Publication de la configuration

Pour publier le fichier de configuration dans le répertoire config de votre application, exécutez :

php artisan vendor:publish --tag=config

Cela créera le fichier config/twilio.php dans lequel vous pourrez renseigner vos informations de connexion Twilio.
Configuration

Modifiez le fichier config/twilio.php pour y inclure vos informations :

<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Twilio Account SID
    |--------------------------------------------------------------------------
    |
    | Votre Account SID trouvé dans la console Twilio.
    |
    */
    'account_sid' => env('TWILIO_ACCOUNT_SID', ''),

    /*
    |--------------------------------------------------------------------------
    | Twilio Auth Token
    |--------------------------------------------------------------------------
    |
    | Votre Auth Token pour l'API Twilio.
    |
    */
    'auth_token' => env('TWILIO_AUTH_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Twilio From Number
    |--------------------------------------------------------------------------
    |
    | Le numéro de téléphone depuis lequel les messages seront envoyés.
    |
    */
    'from_number' => env('TWILIO_FROM_NUMBER', ''),

    /*
    |--------------------------------------------------------------------------
    | Twilio Verify Service SID
    |--------------------------------------------------------------------------
    |
    | Le SID du service Verify, nécessaire pour la vérification de numéros.
    |
    */
    'verify_service_sid' => env('TWILIO_VERIFY_SERVICE_SID', ''),
];

Ajoutez ces variables dans votre fichier .env :

TWILIO_ACCOUNT_SID=your_account_sid_here
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_FROM_NUMBER=your_twilio_number_here
TWILIO_VERIFY_SERVICE_SID=your_verify_service_sid_here

Utilisation
Via la Façade

La façade Twilio permet d'accéder aux méthodes de votre service de manière statique et simple. Par exemple :

use Twilio;

// Envoyer un SMS
$message = Twilio::sendSms('+1234567890', 'Bonjour depuis Laravel avec Twilio !');

// Démarrer la vérification d'un numéro
$verification = Twilio::startPhoneVerification('+1234567890', 'sms');

// Vérifier le code de vérification (par exemple, depuis un formulaire)
$check = Twilio::checkPhoneVerification('+1234567890', '123456');

if ($check->status === 'approved') {
    // Le numéro est vérifié
}

Via l'Injection de Dépendances

Vous pouvez également injecter le client dans vos contrôleurs ou services :
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sabow\TwilioLaravel\Services\TwilioClient;

class SmsController extends Controller
{
    protected $twilio;

    public function __construct(TwilioClient $twilio)
    {
        $this->twilio = $twilio;
    }

    public function sendSms(Request $request)
    {
        $this->twilio->sendSms($request->input('to'), 'Message de test');
        return response()->json(['status' => 'Message envoyé']);
    }

    public function startVerification(Request $request)
    {
        $verification = $this->twilio->startPhoneVerification($request->input('to'));
        return response()->json($verification);
    }

    public function checkVerification(Request $request)
    {
        $check = $this->twilio->checkPhoneVerification(
            $request->input('to'),
            $request->input('code')
        );

        if ($check->status === 'approved') {
            return response()->json(['status' => 'Numéro vérifié']);
        }

        return response()->json(['status' => 'Échec de la vérification'], 422);
    }
}

Documentation Complémentaire
Envoi de SMS

La méthode sendSms($to, $message) envoie un SMS au numéro spécifié :

    Paramètres :
        $to : Numéro du destinataire (format international recommandé, ex. +1234567890)
        $message : Message texte à envoyer

    Retour :
        Instance de \Twilio\Rest\Api\V2010\Account\MessageInstance en cas de succès.

Vérification de Numéro

Le processus de vérification se fait en deux étapes :

    Démarrer la vérification
    Utilisez startPhoneVerification($to, $channel) pour initier la vérification via SMS ou appel vocal.
        $channel peut être 'sms' ou 'call'.

    Vérifier le code
    Utilisez checkPhoneVerification($to, $code) pour vérifier le code saisi par l'utilisateur.
        Si la réponse a un statut approved, la vérification est réussie.

FAQ

Q : Que faire si je reçois une exception indiquant que le SID du service Verify n'est pas configuré ?
R : Vérifiez bien que la variable d'environnement TWILIO_VERIFY_SERVICE_SID est renseignée dans votre .env et que le fichier config/twilio.php utilise env('TWILIO_VERIFY_SERVICE_SID').

Q : Comment puis-je mettre à jour les paramètres de Twilio après l'installation ?
R : Modifiez simplement le fichier config/twilio.php ou mettez à jour les variables d'environnement dans votre fichier .env puis, si nécessaire, exécutez à nouveau php artisan config:cache.

Q : Mon application n'arrive pas à envoyer des SMS, que vérifier ?
R : Vérifiez que vos identifiants Twilio (TWILIO_ACCOUNT_SID et TWILIO_AUTH_TOKEN) et votre numéro d'envoi (TWILIO_FROM_NUMBER) sont corrects. Vous pouvez tester la connexion directement en appelant la méthode sendSms() dans un contrôleur ou via Tinker.
Contribution

Les contributions sont les bienvenues !
Pour contribuer au développement de Sabow TwilioLaravel :

    Forkez le dépôt.
    Créez une branche pour votre fonctionnalité ou correction :

git checkout -b feature/ma-fonctionnalite

Commitez vos modifications avec un message clair :

git commit -am 'Ajout de la fonctionnalité XYZ'

Poussez votre branche :

    git push origin feature/ma-fonctionnalite

    Ouvrez une Pull Request sur GitHub en expliquant vos changements.

License

Ce package est distribué sous licence MIT. Consultez le fichier LICENSE pour plus de détails.
À Propos

Créé et maintenu par Sabow.
Pour toute question, suggestion ou rapport de bug, merci d'ouvrir une issue sur le dépôt GitHub du projet.

Merci d'utiliser Sabow TwilioLaravel pour intégrer Twilio dans vos projets Laravel !
N'hésitez pas à contribuer pour améliorer ce package et à partager vos retours.

