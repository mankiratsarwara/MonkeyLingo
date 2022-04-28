<?php
namespace app\core;

class WebserviceModel{

	protected static $_connection = null;

	public function __construct(){
		$config = simplexml_load_file("app\\core\\webservice_config.xml");

		$username = $config->username;
		$password = $config->password;
		$host = $config->host;//where we find the MySQL DB server
		$DBname = $config->dbname; //the DB name for your Web application

		//connect the objects to the storage medium
		if(self::$_connection == null){
			self::$_connection = new \PDO("mysql:host=$host;dbname=$DBname",$username,$password);
		}
	}

}