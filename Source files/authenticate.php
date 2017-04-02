<?php
session_start();

//include constants needed for connecting to mySQL server
require_once 'connect.php';


//remove whitespace & escape HTML-type tags from input to prevent cross-site scripting
$username = trim ( stripslashes ( htmlspecialchars( $_POST['username'] ) ) ); 
$password = trim ( stripslashes ( htmlspecialchars( $_POST['password'] ) ) );

if ( authenticate_user($username, $password) === TRUE ) {
	header("Location: store.php");
	exit();
}
else {
	echo "Invalid username or password";
	abort();
}


function authenticate_user($username, $password) {
	// Use prepared statements to prevent SQL injections
	$stmt = DBcon::get_conn()->prepare("SELECT * FROM users WHERE username = ?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->free_result();
	$stmt->close();
	
	//check if the username exists in the database
	if($result === FALSE) {
		return FALSE;
	}
	
	$rowcount = $result->num_rows;
	//result must contain 1 row
	if($rowcount === 1) {
		$user_info = $result->fetch_assoc();
		
		//check if the password matches with database
		if( password_verify( $password, $user_info["pwd_hash"] ) ) {
			//initialize session variables
			$_SESSION["username"]  = $username;
			$_SESSION["user_role"] = $user_info["user_role"];
			
			return TRUE;
		}
	}
	return FALSE;
}

//closes DB connection, destroys session and exits the script
function abort( ) {
	DBcon::terminate_conn();
	session_destroy();
	exit();
}

?>

