<?php

namespace app\controllers;

require dirname(dirname(__DIR__)) . '\vendor\autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends \app\core\Controller
{

    public function auth()
    {
        // Getting all the headers from the request.
        $headers = apache_request_headers();

        // Getting the authorization element from the headers.
        $authorization = $headers['Authorization'];

        // Seperating bearer and the API Key.
        $elements = explode(" ", $authorization);

        // Getting the body of the request.
        $request_body = file_get_contents("php://input");
        header("content-type: application/json");


        // Decoding the body.
        $request_body = json_decode($request_body, true);

        // Checking if client has sent the token or the API Key.
        if (!isset($request_body['license_number'])) {
            // Checking the token starts here.
            $token = $elements[1];

            try {
                // Decoding the token.
                $token = JWT::decode($token, new Key("key", 'HS256'));

                // Response
                echo "HTTP/1.1 200 OK";
            } catch (\Exception $e) {
                // If the token is invalid, return an error.
                echo $e;
                echo "HTTP/1.1 401 INVALID TOKEN.";
            }
        } else {
            // Checking the API Key and license number starts here.
            $api_key = $elements[1];

            // Extracting the client's username.
            $username = $request_body['username'];

            // Extracting the client's license number.
            $license_number = $request_body['license_number'];

            // Getting the Client via the username.
            $client = new \app\models\Client();
            $client = $client->get($username);

            // Checking if client exists.
            if ($client == null) {
                echo "HTTP/1.1 404 CLIENT DOES NOT EXIST.";
            } else {
                // Checking if API key corresponds to the client.
                if ($api_key != $client->api_key) {
                    echo "HTTP/1.1 401 INVALID API KEY.";
                } else {
                    // Checking if license corresponds to the client.
                    if ($license_number != $client->license_number) {
                        echo "HTTP/1.1 401 INVALID LICENSE NUMBER.";
                    }
                    // Checking if license is expired.
                    else if (date('Y-m-d H:i:s a', time()) > $client->license_end_date) {
                        echo "HTTP/1.1 401 EXPIRED LICENSE.";
                    } else {
                        // All checks are done.
                        // Generating a token to be sent back.
                        $iat = time();
                        $exp = time() + 1.577e+7;

                        $key = "key";
                        $payload = [
                            "iss" => "http://localhost/authcontroller/auth",
                            "aud" => "http://localhost/webclient/detect",
                            "iat" => $iat,
                            "exp" => $exp
                        ];
                        $jwt = JWT::encode($payload, $key, 'HS256');
                        http_response_code(200);
                        header('HTTP/1.1 200 OK');
                        header("WWWW-Authenticate: Bearer $jwt");
                        header('content-type: application/json');
                    }
                }
            }
        }
    }
}
