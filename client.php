<?php

	require __DIR__ . '/vendor/autoload.php';
	use Firebase\JWT\JWT;
	use Firebase\JWT\Key;

	$data = [
		"clientID"=> "1",
		"clientName" => "Rey Mysterio",
		"licenseNumber"=>"123456",
		"apiKey"=>"1234567",
		"originalFormat"=>".mp4",
		"targetFormat"=>".avi",
		"inputFilePath"=>"C:\\xampp\htdocs\uploads\uploaded\\620f228621206.mp4"
	];

	// Encoding the date with json_encode to be able to send it.
	$json = json_encode($data);

	// Create a new cURL ressource
	$ch = curl_init("http://localhost/AuthController/auth");

	// Setting headers for the request. 
	$headers = [
		'content-type: application/json',
		'authorization: Bearer '. $data["apiKey"]
	];

	curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

	// To get the response from curl_exec.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	// Set the request to POST.
	curl_setopt($ch, CURLOPT_POSTFIELDS,$json);

	// Opts to recieves headers from the response.
	curl_setopt($ch, CURLOPT_HEADER,1);
	
	// Grab URL and pass it to the browser.
	$response = curl_exec($ch);

	// Getting the response headers.
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
  	$headers = substr($response, 0, $header_size);
  	$body = substr($response, $header_size);

	// Making an array with the String of headers.
	$headers = headersToArray($headers);

	//Extracting the token.
	$authorization = $headers['wwww-authenticate'];
	$authorization = explode(" ", $authorization);
	$token = $authorization[2];

	// Close the cURL.
	curl_close($ch);

	// Create a new cURL ressource
	$ch = curl_init("http://localhost/WebService/index");

	// Setting headers for the request. 
	$headers = [
		'content-type: application/json',
		'authorization: Bearer '.$token
	];
	
	curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

	// To get the response from curl_exec.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	// Set the request to POST.
	curl_setopt($ch, CURLOPT_POSTFIELDS,$json);

	// Grab URL and pass it to the browser.
	$response = curl_exec($ch);

	// Closing the curl.
	curl_close($ch);

	// Printing the response of the POST request.
	echo $response;


	function headersToArray($str)
	{
		$headers = array();
		$headersTmpArray = explode( "\r\n" , $str );
		for ( $i = 0 ; $i < count( $headersTmpArray ) ; ++$i )
		{
			// we dont care about the two \r\n lines at the end of the headers
			if ( strlen( $headersTmpArray[$i] ) > 0 )
			{
				// the headers start with HTTP status codes, which do not contain a colon so we can filter them out too
				if ( strpos( $headersTmpArray[$i] , ":" ) )
				{
					$headerName = substr( $headersTmpArray[$i] , 0 , strpos( $headersTmpArray[$i] , ":" ) );
					$headerValue = substr( $headersTmpArray[$i] , strpos( $headersTmpArray[$i] , ":" )+1 );
					$headers[$headerName] = $headerValue;
				}
			}
		}
		return $headers;
	}
?>
