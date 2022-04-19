<?php
namespace app\controllers;

require dirname(dirname(__DIR__)).'\vendor\autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends \app\core\Controller
{

	public function auth()
	{
        // Getting all the headers from the request.
        $headers = apache_request_headers();
        
        // Getting the authorization element from the headers.
        $authorization = $headers['authorization'];

        // Seperating bearer and the API Key.
        $elements = explode(" ",$authorization);

        // Getting the API Key.
        $apiKey = $elements[1];

        // Getting the body of the request.
        $request_body = file_get_contents("php://input");

        // Decoding the body.
        $request_body = json_decode($request_body, true);

        //print_r($request_body);
        
        // Extracting the client's ID.
        $clientID = $request_body['clientID'];

        // Extracting the client's license
        $licenseNumber = $request_body['licenseNumber'];
        
        // Getting the Client via the ID.
        $client = new \app\models\Client();
        $client = $client->get($clientID);
        
        // Checking if client exists.
        if ($client == null){
            echo "HTTP/1.1 404 CLIENT DOES NOT EXIST.";
        }
        else{
            // Checking if API key corresponds to the client.
            if($apiKey != $client->apiKey){
                echo "HTTP/1.1 401 INVALID API KEY.";
            }
            else{
                // Checking if license corresponds to the client.
                if($licenseNumber != $client->licenseNumber){
                    echo "HTTP/1.1 401 INVALID LICENSE NUMBER.";
                }
                // Checking if license is expired.
                else if(date('Y-m-d H:i:s a', time()) > $client->licenseEndDate){
                    echo "HTTP/1.1 401 EXPIRED LICENSE.";
                }
                else{
                    // All checks are done.
                    // Generating a token to be sent back.
                    $iat = time();
                    $exp = time() + 1.577e+7;

                    $key = "random_key";
                    $date = date('Y-m-d');
                    $payload = [
                        "iss" => "http://localhost/AuthController/auth",
                        "aud" => "http://localhost/client",
                        "iat" => $iat,
                        "exp" => $exp
                    ];

                    $jwt = JWT::encode($payload, $key, 'HS256');
                    header('WWW-Authenticate: Bearer '.$jwt);
                }
            }
        }

	}
}
