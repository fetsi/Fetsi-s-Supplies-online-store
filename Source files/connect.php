<?php
require_once '../../conf_files/dbconn_conf.php';

//store the DB connection in a publically accessible static class
//to use it all across the session without the need to reconnect
if( DBcon::connect() === FALSE) {
	echo "Couldn't connect to database";
	header("Location: index.html");
	exit();
}




?>