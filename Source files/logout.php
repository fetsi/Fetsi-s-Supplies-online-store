<?php
require_once '../../conf_files/dbconn_conf.php';

session_start();
session_destroy();
DBcon::terminate_conn();
header("Location: index.html");
exit();

?>