<?php
namespace app\models;

class Translate extends \app\core\Model{
    public $translate_id;
    public $username;
    public $original_string;
    public $converted_string;
    public $original_language;
    public $converted_language;
    public $translate_date;
    public $translate_completed_date;

    public function __construct(){
		parent::__construct();
	}

	public function get($translate_id){
		$SQL = 'SELECT * FROM translate WHERE translate_id LIKE :translate_id';
		$STMT = self::$_connection->prepare($SQL);
		$STMT->execute(['translate_id'=>$translate_id]);
		$STMT->setFetchMode(\PDO::FETCH_CLASS,'app\\models\\Translate');
		return $STMT->fetch();
	}

	public function insert(){
		$SQL = 'INSERT INTO translate(username, original_string, converted_string, original_language, converted_language, translate_date, translate_completed_date) 
                VALUES(:username, :original_string, :converted_string, :original_language, :converted_language, :translate_date, :translate_completed_date)';
		$STMT = self::$_connection->prepare($SQL);
		$STMT->execute([
            'username'=>$this->username,
            'original_string'=>$this->original_string,
            'converted_string'=>$this->converted_string,
            'original_language'=>$this->original_language,
            'converted_language'=>$this->converted_language,
            'translate_date'=>$this->translate_date,
            'translate_completed_date'=>date('Y-m-d H:i:s')
        ]);
	}
}