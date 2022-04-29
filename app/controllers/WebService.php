<?php

namespace app\controllers;

require dirname(dirname(__DIR__)) . '\vendor\autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

/**
 *  @OA\Info(
 * 	version="1.0.0",
 * 	title="Web Service",
 * 	description="The Web Service makes calls to the Google API and returns the results depending on the request.",
 * )
 * 
 */
class WebService //extends \app\core\Controller
{

	/**
	 * @OA\POST(
	 * 	path="/webservice/detect",
	 * 	tags={"Web Service"},
	 * 	summary="Detects the language of the text.",
	 * 	@OA\RequestBody(
	 * 		required=true,
	 * 		@OA\MediaType(
	 * 			mediaType="application/json",
	 * 			@OA\Schema(
	 * 				type="object",
	 * 				@OA\Property(
	 * 					property="String",
	 * 					type="string",
	 * 					description="The text to be analyzed.",
	 * 				),
	 * )
	 * )),
	 * 	@OA\Response(
	 * 		response="200",
	 * 		description="Successful operation.",
	 * 		@OA\JsonContent(
	 * 			type="object",
	 * 			@OA\Property(
	 * 				property="language",
	 * 				type="string",
	 * 				description="The detected language.",
	 * 			),
	 * )),
	 * 	@OA\Response(
	 * 		response="400",
	 * 		description="Invalid request.",
	 * 		@OA\JsonContent(
	 * 			type="object",
	 * 			@OA\Property(
	 * 				property="error",
	 * 				type="string",
	 * 				description="The error message.",
	 * 			)))
	 * )
	 */
	public static function detect()
	{
		// Creating some log handlers.
        $stream = new StreamHandler(dirname(__DIR__).'\monkeylingo.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Creating the main logger.
        $logger = new Logger('monkeylingo');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

		// Getting the body of the request.
		$request_body = file_get_contents("php://input");
		header("content-type: application/json");
		$request_body = json_decode($request_body, true);
		$string = $request_body['string'];

		// Creating a new Detect instance and setting the username, text to be detected and the time of detection.
		$detect = new \app\models\Detect();
		$detect->username = $request_body['username'];
		$detect->original_string = $string;
		$detect->detect_date = date('Y-m-d H:i:s');;

		$curl = curl_init();
		$string = urlencode($string);

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://google-translate1.p.rapidapi.com/language/translate/v2/detect",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "q=$string",
			CURLOPT_HTTPHEADER => [
				"Accept-Encoding: application/gzip",
				"X-RapidAPI-Host: google-translate1.p.rapidapi.com",
				"X-RapidAPI-Key: 48207c7bf1mshb1ee172e5e7d60cp11c2ddjsn4b336e0db6cf",
				"content-type: application/x-www-form-urlencoded"
			],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			$logger->error("cURL Error #:" . $err);
		} else {
			$response = json_decode($response);
			$detect->detected_language = $response->data->detections[0][0]->language;
			$detect->detect_completed_date = date('Y-m-d H:i:s');
			$detect->insert();
			echo $detect->detected_language;
		}
	}


	/**
	 * @OA\POST(
	 * 	path="/webservice/translate",
	 * 	tags={"Web Service"},
	 * 	summary="Translates the text to the specified language.",
	 * 	@OA\RequestBody(
	 * 		required=true,
	 * 		@OA\MediaType(
	 * 			mediaType="application/json",
	 * 			@OA\Schema(
	 * 				type="object",
	 * 				@OA\Property(
	 * 					property="String",
	 * 					type="string",
	 * 					description="The text to be translated.",
	 * 				),
	 * 				@OA\Property(
	 * 					property="TargetLanguage",
	 * 					type="string",
	 * 					description="The language to translate the text to.",
	 * 				),
	 * 				@OA\Property(
	 * 					property="SourceLanguage",
	 * 					type="string",
	 * 					description="The language of the text.",
	 * 				),
	 * 			)
	 * )),
	 * 	@OA\Response(
	 * 		response="200",
	 * 		description="Successful operation.",
	 * 		@OA\JsonContent(
	 * 			type="object",
	 * 			@OA\Property(
	 * 				property="translated_string",
	 * 				type="string",
	 * 				description="The translated string.",
	 * 			),
	 * )),
	 * 	@OA\Response(
	 * 		response="400",
	 * 		description="Invalid request.",
	 * 		@OA\JsonContent(
	 * 			type="object",
	 * 			@OA\Property(
	 * 				property="error",
	 * 				type="string",
	 * 				description="The error message.",
	 * 			)))
	 * )
	 */
	public function translate()
	{

		// Creating some log handlers.
        $stream = new StreamHandler(dirname(__DIR__).'\monkeylingo.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Creating the main logger.
        $logger = new Logger('monkeylingo');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

		// Getting the body of the request.
		$request_body = file_get_contents("php://input");
		header("content-type: application/json");
		$request_body = json_decode($request_body, true);
		$string = $request_body['original_string'];
		$sourceLanguage = $request_body['original_language'];
		$targetLanguage = $request_body['converted_language'];

		// Instantiating a Translate Object.
		$translate = new \app\models\Translate();
		$translate->username = $request_body['username'];
		$translate->original_string = $string;
		$translate->original_language = $sourceLanguage;
		$translate->converted_language = $targetLanguage;
		$translate->translate_date = date('Y-m-d H:i:s');


		$curl = curl_init();
		$string = urlencode($string);
		curl_setopt_array($curl, [
			CURLOPT_URL => "https://google-translate1.p.rapidapi.com/language/translate/v2",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "q=$string&target=$targetLanguage&source=$sourceLanguage",
			CURLOPT_HTTPHEADER => [
				"Accept-Encoding: application/gzip",
				"X-RapidAPI-Host: google-translate1.p.rapidapi.com",
				"X-RapidAPI-Key: 48207c7bf1mshb1ee172e5e7d60cp11c2ddjsn4b336e0db6cf",
				"content-type: application/x-www-form-urlencoded"
			],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			$logger->error("cURL Error #:" . $err);
		} else {
			$response = json_decode($response, true);
			// Inserting the translate data into the database.
            $translate->converted_string = $response['data']['translations'][0]['translatedText'];
            $translate->insert();
			echo $translate->converted_string;
		}
	}

	/**
	 * @OA\GET(
	 * 	path="/webservice/getLanguages",
	 * 	tags={"Web Service"},
	 * 	summary="Gets the list of languages supported by the Google Translate API.",
	 * 	@OA\Response(
	 * 		response="200",
	 * 		description="Successful operation.",
	 * 		@OA\JsonContent(
	 * 			type="object",
	 * 			@OA\Property(
	 * 				property="languages",
	 * 				type="array",
	 * 				description="The list of languages.",
	 * 				@OA\Items(
	 * 					type="object",
	 * 					@OA\Property(
	 * 						property="language",
	 * 						type="string",
	 * 						description="The language code.",
	 * 					),
	 * )),
	 * 				),
	 * )),
	 * 	@OA\Response(
	 * 		response="400",
	 * 		description="Invalid request.",
	 * 		@OA\JsonContent(
	 * 			type="object",
	 * 			@OA\Property(
	 * 				property="error",
	 * 				type="string",
	 * 				description="The error message.",
	 * 			)))
	 * )
	 */
	public function getLanguages() {

		// Creating some log handlers.
        $stream = new StreamHandler(dirname(__DIR__).'\monkeylingo.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();

        // Creating the main logger.
        $logger = new Logger('monkeylingo');
        $logger->pushHandler($stream);
        $logger->pushHandler($firephp);

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://google-translate1.p.rapidapi.com/language/translate/v2/languages?target=en&model=nmt",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => [
				"Accept-Encoding: application/gzip",
				"X-RapidAPI-Host: google-translate1.p.rapidapi.com",
				"X-RapidAPI-Key: 48207c7bf1mshb1ee172e5e7d60cp11c2ddjsn4b336e0db6cf",
			],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			$logger->error("cURL Error #:" . $err);
		} else {
			echo $response;
		}
	}
}
