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
                echo "hola Mundo";
        }


	public function translate(){
                echo "hola Mundo kool guy";
        }
}
