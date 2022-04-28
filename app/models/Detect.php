<?php
namespace app\models;

class Detect extends \app\core\Model{
    public $detect_id;
    public $username;
    public $original_string;
	public $detected_language;
	public $detect_date;
    public $detect_completed_date;

    public function __construct(){
		parent::__construct();
	}

	public function get($detect_id){
		$SQL = 'SELECT * FROM detect WHERE detect_id LIKE :detect_id';
		$STMT = self::$_connection->prepare($SQL);
		$STMT->execute(['detect_id'=>$detect_id]);
		$STMT->setFetchMode(\PDO::FETCH_CLASS,'app\\models\\Detect');
		return $STMT->fetch();
	}

	public function insert(){
		$SQL = 'INSERT INTO detect(username, original_string, detected_language, detect_date, detect_completed_date) 
				VALUES (:username, :original_string, :detected_language, :detect_date, :detect_completed_date)';
		$STMT = self::$_connection->prepare($SQL);
		$STMT->execute(['username'=>$this->username, 'original_string'=>$this->original_string, 'detected_language'=>$this->detected_language, 
                        'detect_date'=>$this->detect_date, 'detect_completed_date'=>$this->detect_completed_date]);
	}
}