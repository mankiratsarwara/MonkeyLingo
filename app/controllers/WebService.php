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
			$reponse = json_decode($response);
			echo $reponse->data->detections[0][0]->language;
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
			// print_r($response);
			echo $response['data']['translations'][0]['translatedText'];
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
