<?php
namespace app\models;

class Client extends \app\core\Model{
    public $username;
    public $first_name;
	public $last_name;
    public $password_hash;
	public $api_key;
	public $license_number;
    public $license_start_date;
    public $license_end_date;

    public function __construct(){
		parent::__construct();
	}

	public function get($username){
		$SQL = 'SELECT * FROM client WHERE username LIKE :username';
		$STMT = self::$_connection->prepare($SQL);
		$STMT->execute(['username'=>$username]);
		$STMT->setFetchMode(\PDO::FETCH_CLASS,'app\\models\\Client');
		return $STMT->fetch();
	}

	public function insert(){
		$this->password_hash = password_hash($this->password, PASSWORD_DEFAULT);
		$SQL = 'INSERT INTO client(username, first_name, last_name, password_hash, api_key, license_number, license_end_date) 
				VALUES (:username, :first_name, :last_name, :password_hash, :api_key, :license_number, :license_end_date)';
		$STMT = self::$_connection->prepare($SQL);
		$STMT->execute(['username'=>$this->username, 'first_name'=>$this->first_name, 'last_name'=>$this->last_name, 'password_hash'=>$this->password_hash, 
						'api_key'=>$this->api_key, 'license_number'=>$this->license_number, 'license_end_date'=>date('Y-m-d H:i:s', strtotime('+6 month'))]);
	}
}