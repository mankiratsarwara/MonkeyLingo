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
        if(isset($_POST['action'])){
            if(trim($_POST['string']) == ''){
                $this->view('Client/detect', ['error' => 'The text area can not be empty!']);
                return;
            }

            $client = new \app\models\Client();
            $client = $client->get($_SESSION['username']);
            
            $detect = new \app\models\Detect();
            $detect->username = $client->username;
            $detect->original_string = $_POST['string'];
            $detect->detect_data = date('Y-m-d H:i:s');

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

                // POST Request Authentication without the token.
                $post = [
                    'username' => $client->username,
                    'license_number' => $client->license_number
                    // 'api_key' => $client->api_key
                ];
                // print_r($post);
                $post = json_encode($post);
                // echo strval($post) . "this is string";
                $response;
                try {
                    $response = $guzzleClient->request('POST', 'http://localhost/AuthController/auth', [
                        'headers' => ['content-type' => 'application/json'],
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
                // $jwt = $response->getHeader('wwww-authenticate');
                // $jwt = str_replace('Bearer ', '', $jwt[0]);
                // echo 'something' . $jwt;
                // echo "aodsifjaosdifj????";
                // // Getting the response headers.
                // // print_r($response->getHeaders());
                // print_r($jwt);
                // echo $responseBodyAsString;
            }

            // $res = json_decode($response->getBody()->getContents());

            // $res = $response->getBody()->getContents();
            // var_dump($res);

            //$detect->detected_language = "hello";
            //$detect->insert();
            //$this->view('Client/detect',['client'=>$client, 'language'=>$detect->detected_language]);
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


            // REQUEST TO API CODE HERE.

            // $translate->converted_string = 'something';
            // $translate->insert();

            $translate->converted_string = 'Cool car man, is that ferrarri?';

            $this->view('Client/translate',['client'=>$client, 'translated'=>$translate->converted_string]);
        }
        else{
            $client = new \app\models\Client();
            $client = $client->get($_SESSION['username']);
            $this->view('Client/translate', ['client'=>$client]);
        }
    }

    public function logout(){
        session_destroy();
        header("Location:/WebClient/login");
    }

}
