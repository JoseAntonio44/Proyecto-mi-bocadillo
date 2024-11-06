<?php
/**
* PDO Clase Singleton
*/
class DB {

	protected static $instance;

	protected function __construct() {}

	public static function getInstance() {
		if (empty(self::$instance)) {

			
			$host = 'localhost';
			$dbname = 'bocatav5';
			$user = 'root';
			$pass = '';

			try {
				self::$instance = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$instance->query('SET NAMES utf8');
				self::$instance->query('SET CHARACTER SET utf8');

			} catch (PDOException $error) {
				echo $error->getMessage();
			}
		}

		return self::$instance;
	}

	public static function setCharsetEncoding() {
		if (self::$instance == null) {
			self::getInstance(); 
		}

		self::$instance->exec(
			"SET NAMES 'utf8';
			SET character_set_connection=utf8;
			SET character_set_client=utf8;
			SET character_set_results=utf8");
	}
}
?>
