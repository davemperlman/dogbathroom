<?php

/**
* 
*/
class Tracker {
	public $currentpage;
	public $currentlog;
	public $whole_log;
	public $date;
	
	function __construct($pdo, $start) {
		$this->currentpage = $pdo->query("SELECT * FROM log WHERE date = CURDATE() LIMIT $start, 5")->fetchAll(PDO::FETCH_ASSOC);
		$this->currentlog = $pdo->query("SELECT * FROM log WHERE date = CURDATE()");
		$this->whole_log = $pdo->query("SELECT * FROM log")->fetchAll(PDO::FETCH_ASSOC);
		$this->date = date('Ymd');
	}

	public function set($arr) {
		global $pdo;
		$pdo->query("INSERT INTO log (poop, pee, `date`, `time`, is_accident) VALUES('$arr[poop]', '$arr[pee]', '$this->date', CURTIME(), $arr[is_accident])");
	}

	public function avg() {
		global $pdo;
		$this->whole_log = $pdo->query("SELECT * FROM log WHERE time > '05:00:00' AND time < '21:00:00' ORDER BY date")->fetchAll(PDO::FETCH_ASSOC);
	}

}