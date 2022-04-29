<?php

require dirname(dirname(__DIR__)) . '\vendor\autoload.php';

require("C:\\xampp\\htdocs\\app\\controllers\\WebService.php");
$openapi = \OpenApi\Generator::scan(['C:\\xampp\\htdocs\\app\\controllers\\WebService.php']);
header('Content-Type: text/plain');
echo $openapi->toYaml();