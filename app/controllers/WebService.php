<?php

namespace app\controllers;

require dirname(dirname(__DIR__)) . '\vendor\autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class WebService extends \app\core\Controller
{
	// private $uploadedFolder = 'uploads/uploaded/';
	// private $convertedFolder = 'uploads/converted/';

	public static function detect()
	{
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
				"X-RapidAPI-Key: e55b2a925cmshd11e65f5fc8389ap120889jsnc2b7bed1f83a",
				"content-type: application/x-www-form-urlencoded"
			],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
			echo $response;
			$reponse = json_decode($response);
			echo $reponse->data->detections[0][0]->language;
		}
	}

	public function translate()
	{
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
				"X-RapidAPI-Key: e55b2a925cmshd11e65f5fc8389ap120889jsnc2b7bed1f83a",
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

	public function run()
	{
		$this->detect("Hello World");
	}
}
