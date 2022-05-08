<?php

namespace app\controllers;

require dirname(dirname(__DIR__)) . '\vendor\autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class WebClient extends \app\core\Controller
{

    public function login()
    {
        // Creating some log handlers.
        $stream = new StreamHandler(dirname(__DIR__) . '\monkeylingo.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Creating the main logger.
        $logger = new Logger('monkeylingo');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

        //TODO: register session variables to stay logged in
        if (isset($_POST['action'])) { //verify that the user clicked the submit button
            $client = new \app\models\Client();

            if (trim($_POST['password']) == '' || trim($_POST['username']) == '') {
                $this->view('Client/login', ['error' => 'Username and Password can not be empty!', 'image' => $this->getFromCDN()]);
                return;
            }

            $client = $client->get($_POST['username']);

            if ($client != false && password_verify($_POST['password'], $client->password_hash)) {
                $_SESSION['username'] = $client->username;

                $user = new \app\models\Client();
                $user = $user->get($_SESSION['username']);

                $logger->info('Client: ' . $user->username . ' logged in.');

                header("Location:/WebClient/translate");
            } else {
                $this->view('Client/login', ['error' => 'Wrong username and password combination!', 'image' => $this->getFromCDN()]);
            }
        } else //1 present a form to the user
            $this->view('Client/login', ['image' => $this->getFromCDN()]);
    }

    public function register()
    {
        // Creating some log handlers.
        $stream = new StreamHandler(dirname(__DIR__) . '\monkeylingo.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Creating the main logger.
        $logger = new Logger('monkeylingo');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

        if (isset($_POST['action'])) { //verify that the user clicked the submit button
            if (
                trim($_POST['username']) == '' || trim($_POST['password']) == '' || trim($_POST['first_name']) == ''
                || trim($_POST['last_name']) == ''
            ) {
                $this->view('Client/register', ['error' => "Make sure that all fields are filled up!", 'image' => $this->getFromCDN()]);
                return;
            }

            $client = new \app\models\Client();

            if ($client->get($_POST['username'])) {
                $this->view('Client/register', ['error' => "This username already exists", 'image' => $this->getFromCDN()]);
                return;
            }
            if ($_POST['password'] != $_POST['password_confirm']) {
                $this->view('Client/register', ['error' => "The passwords do not match", 'image' => $this->getFromCDN()]);
                return;
            }

            $client->username = $_POST['username'];
            $client->first_name = $_POST['first_name'];
            $client->last_name = $_POST['last_name'];
            $client->api_key = uniqid(); // Generating a unique api key.
            $client->license_number = uniqid(); // Generating a unique license number
            $client->password = $_POST['password'];
            $client->insert();
            $logger->info('REGISTRATION: ' . $client->username . ' signed up.');
            header("Location:/WebClient/login");
        } else //1 present a form to the user
            $this->view('Client/register', ['image' => $this->getFromCDN()]);
    }

    public function about()
    {
        $client = new \app\models\Client();
        $client = $client->get($_SESSION['username']);
        $this->view('Client/about', ['client' => $client, 'image' => $this->getFromCDN()]);
    }

    public function detect()
    {

        // Creating some log handlers.
        $stream = new StreamHandler(dirname(__DIR__) . '\monkeylingo.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Creating the main logger.
        $logger = new Logger('monkeylingo');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

        // Checks if the user clicks the detect button.
        if (isset($_POST['action'])) {
            // Checks if the user has entered an empty string in the text area.
            if (trim($_POST['string']) == '') {
                $this->view('Client/detect', ['error' => 'The text area can not be empty!', 'image' => $this->getFromCDN()]);
                return;
            }

            // Creating a Client instance and getting the client's info based on the username.
            $client = new \app\models\Client();
            $client = $client->get($_SESSION['username']);

            $logger->warning('Client: ' . $client->username . ' has started authenticating.');

            // Authenticate the user.
            $responseCode = $this->sendAuthentication($client);
            if ($responseCode != 200) {
                $logger->error('Client: ' . $client->username . ' has not authenticated.');
                return;
            }

            $logger->info('Client: ' . $client->username . ' is authenticated.');

            $logger->warning('Client: ' . $client->username . ' has started detecting.');

            // Getting the languages before the page loads
            $languageResponse = $this->getLanguages($client);

            // Request to WebService to detect the text.
            try {

                $guzzleClient = new Client();

                // POST Request Body to Web Service Detect.
                $body = [
                    'username' => $client->username,
                    'string' => $_POST['string'],
                ];

                // JSON Encoding the body.
                $body = json_encode($body);

                $response = $guzzleClient->request('POST', 'http://localhost/WebService/detect', [
                    'headers' => ['content-type' => 'application/json', 'Authorization' => 'Bearer ' . $client->token],
                    'body' => $body,
                ]);
            } catch (\GuzzleHttp\Exception\ClientException $e) { // Catching any exceptions.
                // echo "Status code {$e->getResponse()->getStatusCode()} <br>";
                $response = $response->getBody()->getContents();
                $response = json_decode($response);
                $logger->error('Detect Error: ' . $e->getResponse()->getStatusCode() . ' ' . $response);
            }
            $languageName = $this->findLanguageCode($response->getBody()->getContents(), $languageResponse);

            $logger->info('Client: ' . $client->username . ' has stopped detecting.');

            $this->view('Client/detect', ['client' => $client, 'language' => $languageName, "original" => $_POST['string'], 'image' => $this->getFromCDN()]);
        } else {
            $client = new \app\models\Client();
            $client = $client->get($_SESSION['username']);
            $this->view('Client/detect', ['client' => $client, 'image' => $this->getFromCDN()]);
        }
    }

    public function translate()
    {

        // Creating some log handlers.
        $stream = new StreamHandler(dirname(__DIR__) . '\monkeylingo.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Creating the main logger.
        $logger = new Logger('monkeylingo');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

        $client = new \app\models\Client();
        $client = $client->get($_SESSION['username']);

        if (isset($_POST['action'])) {
            if (trim($_POST['string']) == '') {
                // Getting the languages before the page loads
                $languageResponse = $this->getLanguages($client);
                $this->view('Client/translate', ['error' => 'The text area can not be empty!', "languages" => $languageResponse, 'image' => $this->getFromCDN()]);
                return;
            }

            $logger->warning('Client: ' . $client->username . ' has started authenticating.');

            // Authenticate the user.
            $responseCode = $this->sendAuthentication($client);
            if ($responseCode != 200) {
                $logger->error('Client: ' . $client->username . ' has not authenticated.');
                return;
            }

            // Getting the languages before the page loads
            $languageResponse = $this->getLanguages($client);

            $logger->info('Client: ' . $client->username . ' is authenticated.');

            $logger->warning('Client: ' . $client->username . ' has started translating.');

            // Request to WebService to translate the text.
            try {

                $guzzleClient = new Client();

                // POST Request Body to Web Service Translate.
                $body = [
                    'username' => $client->username,
                    'original_string' => $_POST['string'],
                    'original_language' => $_POST['ogLanguage'],
                    'converted_language' => $_POST['convertedLanguage']
                ];

                // JSON Encoding the body.
                $body = json_encode($body);

                $response = $guzzleClient->request('POST', 'http://localhost/WebService/translate', [
                    'headers' => ['content-type' => 'application/json', 'Authorization' => 'Bearer ' . $client->token],
                    'body' => $body,
                ]);
            } catch (\GuzzleHttp\Exception\ClientException $e) { // Catching any exceptions.
                // echo "Status code {$e->getResponse()->getStatusCode()} <br>";
                $response = $response->getBody()->getContents();
                $response = json_decode($response);
                $logger->error('Translate Error: ' . $e->getResponse()->getStatusCode() . ' ' . $response);
            }

            $logger->info('Client: ' . $client->username . ' has stopped translating.');

            $this->view('Client/translate', [
                'client' => $client, "languages" => $languageResponse, 'ogLanguage' => $_POST['ogLanguage'],
                "convertedLanguage" => $_POST['convertedLanguage'], "original" => $_POST['string'],
                "translated" => $response->getBody()->getContents(), 'image' => $this->getFromCDN()
            ]);
        } else {
            $client = new \app\models\Client();
            $client = $client->get($_SESSION['username']);
            // Getting the languages before the page loads
            $languageResponse = $this->getLanguages($client);
            $this->view('Client/translate', ['client' => $client, "languages" => $languageResponse, 'image' => $this->getFromCDN()]);
        }
    }

    public function logout()
    {
        // Creating some log handlers.
        $stream = new StreamHandler(dirname(__DIR__) . '\monkeylingo.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Creating the main logger.
        $logger = new Logger('monkeylingo');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

        $logger->info('Client: ' . $_SESSION['username'] . ' has logged out.');
        session_destroy();
        header("Location:/WebClient/login");
    }

    public function sendAuthentication($client)
    {
        // Creating some log handlers.
        $stream = new StreamHandler(dirname(__DIR__) . '\monkeylingo.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Creating the main logger.
        $logger = new Logger('monkeylingo');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

        // Starting the authentication process.
        $guzzleClient = new Client();
        $response = "";

        // Checking if client has a token.
        if (!is_null($client->token)) {
            try {
                // POST Request Authentication with the token.
                $response = $guzzleClient->request('POST', 'http://localhost/AuthController/auth/', [
                    'headers' => ['content-type' => 'application/json', 'Authorization' => 'Bearer ' . $client->token]
                ]);
                return $response->getStatusCode();
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                echo $response;
                // echo "Status code {$e->getResponse()->getStatusCode()} <br>";
                $res = $e->getResponse();
                $res = $res->getBody()->getContents();
                $json = json_decode($res);
                // echo $json->error;
                $this->view('Client/4XXerror', [
                    'errorCode' => $e->getResponse()->getStatusCode(), 'errorMessage' => $json->error,
                    'image' => $this->getFromCDN()
                ]);
                return $e->getResponse()->getStatusCode();
            }
        } else {

            // POST Request Authentication Body (Without the token).
            $post = [
                'username' => $client->username,
                'license_number' => $client->license_number
            ];

            // JSON Encoding the Authentication Request Body.
            $post = json_encode($post);

            // POST Request Authentication without the token. 
            try {
                $response = $guzzleClient->request('POST', 'http://localhost/AuthController/auth', [
                    'headers' => ['content-type' => 'application/json', 'Authorization' => 'Bearer ' . $client->api_key],
                    'body' => $post,
                ]);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                echo $response;
                // echo "Status code {$e->getResponse()->getStatusCode()} <br>";
                $res = $e->getResponse();
                $res = $res->getBody()->getContents();
                $json = json_decode($res);
                // echo $json->error;
                $this->view('Client/4XXerror', [
                    'errorCode' => $e->getResponse()->getStatusCode(), 'errorMessage' => $json->error,
                    'image' => $this->getFromCDN()
                ]);
                return $e->getResponse()->getStatusCode();
            }
            // Extracting the token from the response.
            $statusCode = $response->getStatusCode();
            $response = $response->getHeader('WWWW-Authenticate')[0];
            $token = explode(' ', $response)[1];

            // Updating the client's token.
            $client->token = $token;
            $client->setToken();
            return $statusCode;
        }
    }

    public function getLanguages($client)
    {
        // Creating some log handlers.
        $stream = new StreamHandler(dirname(__DIR__) . '\monkeylingo.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Creating the main logger.
        $logger = new Logger('monkeylingo');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

        $response = "";

        // Creating a new Detect instance and setting the username, text to be detected and the time of detection.
        $detect = new \app\models\Detect();
        $detect->username = $client->username;
        $detect->detect_data = date('Y-m-d H:i:s');
        try {

            $guzzleClient = new Client();

            // POST Request Body to Web Service Detect.
            $body = [
                'username' => $client->username,
            ];

            // JSON Encoding the body.
            $body = json_encode($body);

            $response = $guzzleClient->request('POST', 'http://localhost/WebService/getLanguages', [
                'headers' => ['content-type' => 'application/json', 'Authorization' => 'Bearer ' . $client->token],
                'body' => $body,
            ]);
        } catch (\GuzzleHttp\Exception\ClientException $e) { // Catching any exceptions.
            // echo "Status code {$e->getResponse()->getStatusCode()} <br>";
            $response = $response->getBody()->getContents();
            $response = json_decode($response);
            $logger->error('API Error: ' . $e->getResponse()->getStatusCode() . ' ' . $response);
        }
        $response = $response->getBody()->getContents();
        $response = json_decode($response, true);

        return $response["data"]["languages"];
    }

    public function findLanguageCode($languageCode, $array)
    {
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i]["language"] == $languageCode) {
                return $array[$i]["name"];
            }
        }
    }

    public function getFromCDN()
    {

        $s3Client = new S3Client([
            'region'  => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
                'key'    => "AKIAUTZKO3SNYPVE6ILQ",
                'secret' => "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",
            ]
        ]);

        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => 'cnkbucket',
            'Key'    => "monkey.png",
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');

        // Get the actual presigned-url
        $presignedUrl = (string)$request->getUri();

        return $presignedUrl;
    }
}
