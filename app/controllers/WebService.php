<?php

namespace app\controllers;

require dirname(dirname(__DIR__)) . '\vendor\autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class WebService extends \app\core\Controller
{

	public static function detect()
	{
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
			echo "cURL Error #:" . $err;
		} else {
			$response = json_decode($response);
			$detect->detected_language = $response->data->detections[0][0]->language;
			$detect->detect_completed_date = date('Y-m-d H:i:s');
			$detect->insert();
			echo $detect->detected_language;
		}
	}

	public function translate()
	{
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
			echo "cURL Error #:" . $err;
		} else {
			$response = json_decode($response, true);
			// Inserting the translate data into the database.
            $translate->converted_string = $response['data']['translations'][0]['translatedText'];
            $translate->insert();
			echo $translate->converted_string;
		}
	}

	public function getLanguages() {
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
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}
	}
}
