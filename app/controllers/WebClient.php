<?php
namespace app\controllers;

require dirname(dirname(__DIR__)).'\vendor\autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;

class WebClient extends \app\core\Controller
{

	public function login()
	{
        //TODO: register session variables to stay logged in
		if (isset($_POST['action'])) { //verify that the user clicked the submit button
			$client = new \app\models\Client();

			if (trim($_POST['password']) == '' || trim($_POST['username']) == '') {
				$this->view('Client/login', 'Username and Password can not be empty!');
				return;
			}

			$client = $client->get($_POST['username']);

			if ($client != false && password_verify($_POST['password'], $client->password_hash)) {
				$_SESSION['username'] = $client->username;

				$username = new \app\models\Client(); // why we do this lmao.
				$username = $username->get($_SESSION['username']); // why we do this lmao pt.2
				header("Location:/WebClient/translate");
			} else {
				$this->view('Client/login', 'Wrong username and password combination!');
			}
		} else //1 present a form to the user
			$this->view('Client/login');
	}

    public function register()
    {
        if (isset($_POST['action'])) { //verify that the user clicked the submit button
			if (
				trim($_POST['username']) == '' || trim($_POST['password']) == '' || trim($_POST['first_name']) == ''
				|| trim($_POST['last_name']) == ''
			) {
				$this->view('Client/register', "Make sure that all fields are filled up!");
				return;
			}

			$client = new \app\models\Client();
			
            if ($client->get($_POST['username'])) {
				$this->view('Client/register', "This username already exists");
				return;
			}
			if ($_POST['password'] != $_POST['password_confirm']) {
				$this->view('Client/register', "The passwords do not match");
				return;
			}

			$client->username = $_POST['username'];
			$client->first_name = $_POST['first_name'];
			$client->last_name = $_POST['last_name'];
			$client->api_key = uniqid(); // Generating a unique api key.
            $client->license_number = uniqid(); // Generating a unique license number
			$client->password = $_POST['password'];
			$client->insert();
			header("Location:/WebClient/login");
		} else //1 present a form to the user
			$this->view('Client/register');
    }

    public function about(){
        $client = new \app\models\Client();
        $client = $client->get($_SESSION['username']);
        $this->view('Client/about', ['client'=>$client]);
    }

    public function detect(){

        // Checks if the user clicks the detect button.
        if(isset($_POST['action'])){
            // Checks if the user has entered an empty string in the text area.
            if(trim($_POST['string']) == ''){
                $this->view('Client/detect', ['error' => 'The text area can not be empty!']);
                return;
            }

            // Creating a Client instance and getting the client's info based on the username.
            $client = new \app\models\Client();
            $client = $client->get($_SESSION['username']);
            
            // Creating a new Detect instance and setting the username, text to be detected and the time of detection.
            $detect = new \app\models\Detect();
            $detect->username = $client->username;
            $detect->original_string = $_POST['string'];
            $detect->detect_date = date('Y-m-d H:i:s');

            // Authenticate the user.
            $this->sendAuthentication($client);

            // Getting the languages before the page loads
            $languageResponse = $this->getLanguages($client);

            // Request to WebService to detect the text.
            try{

                $guzzleClient = new Client();

                // POST Request Body to Web Service Detect.
                $body = [
                    'username' => $client->username,
                    'string' => $detect->original_string
                ];

                // JSON Encoding the body.
                $body = json_encode($body);

                $response = $guzzleClient->request('POST', 'http://localhost/WebService/detect', [
                    'headers' => ['content-type' => 'application/json','Authorization' => 'Bearer '.$client->token],
                    'body' => $body,
                ]);

            } catch(\GuzzleHttp\Exception\ClientException $e){ // Catching any exceptions.
                echo "Status code {$e->getResponse()->getStatusCode()} <br>";
                $response = $response->getBody()->getContents();
                echo $response;
            }
            $languageName = $this->findLanguageCode($response->getBody()->getContents(), $languageResponse);

            // Inserting the detected language into the database.
            $detect->detected_language = $languageName;
            $detect->detect_completed_date = date('Y-m-d H:i:s');
            $detect->insert();

            $this->view('Client/detect', ['client' => $client, 'language' => $languageName, "original" => $detect->original_string]);

        }
        else{
            $client = new \app\models\Client();
            $client = $client->get($_SESSION['username']);
            $this->view('Client/detect', ['client'=>$client]);
        }
    }

    public function translate(){
        if(isset($_POST['action'])){
            if(trim($_POST['string']) == ''){
                $this->view('Client/translate', ['error' => 'The text area can not be empty!']);
                return;
            }

            $client = new \app\models\Client();
            $client = $client->get($_SESSION['username']);
            
            $translate = new \app\models\Translate();
            $translate->username = $client->username;
            $translate->original_string = $_POST['string'];
            $translate->original_language = $_POST['ogLanguage'];
            $translate->converted_language = $_POST['convertedLanguage'];
            $translate->translate_date = date('Y-m-d H:i:s');

            // Authenticate the user.
            $this->sendAuthentication($client);

            // Getting the languages before the page loads
            $languageResponse = $this->getLanguages($client);

            // Request to WebService to translate the text.
            try{

                $guzzleClient = new Client();

                // POST Request Body to Web Service Translate.
                $body = [
                    'username' => $client->username,
                    'original_string' => $translate->original_string,
                    'original_language' => $translate->original_language,
                    'converted_language' => $translate->converted_language
                ];

                // JSON Encoding the body.
                $body = json_encode($body);

                $response = $guzzleClient->request('POST', 'http://localhost/WebService/translate', [
                    'headers' => ['content-type' => 'application/json','Authorization' => 'Bearer '.$client->token],
                    'body' => $body,
                ]);

            } catch(\GuzzleHttp\Exception\ClientException $e){ // Catching any exceptions.
                echo "Status code {$e->getResponse()->getStatusCode()} <br>";
                $response = $response->getBody()->getContents();
                echo $response;
            }

            // Inserting the translate data into the database.
            $translate->converted_string = $response->getBody()->getContents();
            $translate->insert();


            $this->view('Client/translate', ['client' => $client, "languages" => $languageResponse, 'ogLanguage' => $translate->original_language,
                "convertedLanguage" => $translate->converted_language, "original" => $translate->original_string,
                "translated" => $translate->converted_string]);
            
        }
        else{
            $client = new \app\models\Client();
            $client = $client->get($_SESSION['username']);
            // Getting the languages before the page loads
            $languageResponse = $this->getLanguages($client);
            $this->view('Client/translate', ['client'=>$client, "languages" => $languageResponse]);
        }
    }

    public function logout(){
        session_destroy();
        header("Location:/WebClient/login");
    }

    public function sendAuthentication($client){
         
        // Starting the authentication process.
         $guzzleClient = new Client();
         $response = "";

         // Checking if client has a token.
         if(!is_null($client->token)){
             
             // POST Request Authentication with the token.
             $response = $guzzleClient->request('POST','http://localhost/AuthController/auth/',[
                 'headers' => ['content-type' => 'application/json','Authorization' => 'Bearer '.$client->token]
             ]);
         }
         else{

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
                    'headers' => ['content-type' => 'application/json','Authorization' => 'Bearer '.$client->api_key],
                    'body' => $post,
                ]);
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                echo $response;
                echo $response->getHeader('HTTP/1.1');
                echo "Status code {$e->getResponse()->getStatusCode()} <br>";
                echo $response->getHeader('WWWW-Authenticate');
                $response = $e->getResponse();
                $response = $response->getBody()->getContents();
                echo $response;
            }

            // Extracting the token from the response.
            $response = $response->getHeader('WWWW-Authenticate')[0];
            $token = explode(' ', $response)[1];
            
            // Updating the client's token.
            $client->token = $token;
            $client->setToken();
        }
    }

    public function getLanguages($client)
    {
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
            echo "Status code {$e->getResponse()->getStatusCode()} <br>";
            $response = $response->getBody()->getContents();
            echo $response;
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
}