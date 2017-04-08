<?php

define("HOST", "54.212.198.16");
define("USER", "********");
define("PASSWORD", "*******");
define("DB_NAME", "shop_db");


class DBcon {
	private static $conn;

	public static function get_conn() {
	return self::$conn;
	}
	public static function connect() {
		self::$conn = new mysqli(HOST, USER, PASSWORD, DB_NAME);

		// Check if connection is OK
		if ($connection->connect_error) {
			//Couldn't connect, so abort
			return FALSE;
		} 
		return TRUE;
	}

	public static function terminate_conn() {
		mysqli_close(self::$conn);
	}
}
	
?>
