<?php
namespace app\controllers;

require dirname(dirname(__DIR__)).'\vendor\autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class WebService extends \app\core\Controller
{
	// private $uploadedFolder = 'uploads/uploaded/';
	// private $convertedFolder = 'uploads/converted/';

        public function detect(){
                
        }


	// public function index()
	// {
        //         // Reading the request payload sent from the Client POST request.
        //         // Reads input stream when Apache loads the file.
        //         $request_body = file_get_contents("php://input");

        //         // Getting all the headers from the request.
        //         $headers = apache_request_headers();

        //         try{
                        // extracting the token from the autorization header.
                        // $authorization = $headers['authorization'];
                        // $authorization = explode(" ",$authorization);
                        // $token = $authorization[1];

                        // if($token == null){
                        // echo "HTTP/1.1 401 UNAUTHORIZED.";     
                        // }
                        // else{
                        //         // Decoding - Checking signature.
                        //         $token = JWT::decode($token, new Key("random_key",'HS256'));

                        //         // Decode the json-encoded data.
                        //         $data = json_decode($request_body, true);

                        //         $filename = uniqid() . $data['targetFormat']; // Setting the filename.
                        //         $filepath = $this->uploadedFolder . $filename; // Setting the location to save it.

                        //         // Seeting the client info based on the info received by the POST request.
                        //         // $conversion = new \app\Models\VideoConversion();
                        //         // $conversion->clientID = $data['clientID'];
                        //         // $conversion->requestDate = date('Y-m-d H:i:s a', time());
                        //         // $conversion->originalFormat = $data['originalFormat'];
                        //         // $conversion->targetFormat = $data['targetFormat'];
                        //         // $conversion->inputFile = $data['inputFilePath'];


                        //         // Setting the name of the new converted file.
                        //         $convertedFile = uniqid() . $conversion->targetFormat;

                        //         // Setting the URL of the new converted file.
                        //         $convertedFilepath = $this->convertedFolder . $convertedFile;

                        //         // Setting and running the ffmpeg conversion command.
                        //         $command = "ffmpeg -i $filepath $convertedFilepath";
                        //         exec($command);

                        //         // Setting the output file after the conversion has been done.
                        //         $conversion->outputFile = $convertedFilepath;

                        //         // Setting the data and time after the conversion has been done.
                        //         $conversion->requestCompleteDate = date('Y-m-d H:i:s a', time());
                                
                        //         // Inserting a record of the conversion in the database.
                        //         $conversion->insertVideoConversion();
                //         //         echo "HTTP/1.1 200 OK.";
                //         }
                // }
                // catch (\Exception $e){
                //         echo "HTTP/1.1 401 UNAUTHORIZED.";
        //         // }
	// }
}
