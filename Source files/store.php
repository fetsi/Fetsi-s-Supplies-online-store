<?php
session_set_cookie_params(time() + 3600, '/~tuomahy/');
session_start();

//include constants needed for connecting to mySQL server
require_once '../../conf_files/dbconn_conf.php';
require_once 'connect.php';

if ( !isset( $_SESSION["username"] )   ||   !isset ( $_SESSION["user_role"] ) ) {
	session_destroy();
	header("Location: index.html");
	exit();
}
if( isset( $_POST["action_type"] ) ) {
	//user did something, find the correct procedure
	switch ( $_POST["action_type"] ) {
		case 'buy':
			buy_products( $_POST["product_id"], $_POST["product_quantity"] );
			break;
		case 'add_prod':
			add_new_product( $_POST["product_id"], $_POST["product_name"], $_POST["category_select_new_prod"], $_POST["product_quantity"] );
			break;
		case 'remove_prod':
			remove_product( $_POST["product_id"] );
			break;
		case 'increment':
			increment( $_POST["product_id"], $_POST["product_quantity"] );
			break;	
	}
}


?>


<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
		<link href="style.css" rel="stylesheet">
		<title>Fetsi's Supplies</title>
		<script type="text/javascript" src="client_side.js"></script>
	</head>
	<body>

		<div class="parent_container">
			<header>
				<img src="img/store_logo.png" alt="Logo">
			</header>
			<nav class="navbar">
				<ul>
					<li>
						<form action="logout.php" method="get" name="logout_form">
							<button type="submit" id="logout">Logout</button>
						</form>
					</li>
					<li>
						<?php 
							echo "<p>Logged in as: </p><br>"."<p>".$_SESSION["username"]."</p>";
						?>
					</li>
			</nav>
			<main class="content">
				<div class="search_frame">
					<table class="product_table">
						<th>ID</th>
						<th>Product</th>
						<th>Category</th>
						<th>Quantity</th>
						
						<?php display_products();?>
							
					</table>
					<form class="refresh_by_category"name="refresh_by_category" method="post" action="store.php">
						<select name="category_select_display">
							<option value="all" selected>All categories</option>
							<option value="fruit">Fruit</option>
							<option value="vegetables">Vegetables</option>
							<option value="dairy">Dairy</option>
							<option value="meat">Meat</option>
						</select>
						<input type="submit" value="Refresh">
					</form>
					
					<?php display_db_tools($_SESSION["user_role"]); ?>
					
						<table class="input_field_table">
							<tr id="product_id">
								<td><input type="text" name="product_id"></input> </td>
								<td> Product ID </td>
							</tr>
							<tr id="product_name">
								<td><input type="text" name="product_name"></input> </td>
								<td> Product name </td>
							</tr>
							<tr id="product_quantity">
								<td><input type="text" name="product_quantity"></input> </td>
								<td> Quantity </td>
							</tr>
							<tr id="category_select_new_prod">
								<td><select name="category_select_new_prod">
									<option selected>Choose category</option>
									<option value="fruit">Fruit</option>
									<option value="vegetables">Vegetables</option>
									<option value="dairy">Dairy</option>
									<option value="meat">Meat</option>
									</select> 
								</td>
								<td> Category </td>
							</tr>
						</table>	 
						<button type="submit">Submit</button>
					</form>						
						
				</div>
			</main>
		</div>
	</body>
<html/>



<?php

function display_products() {
	
	$stmt = "";
	
	if( isset ( $_POST["category_select_display"] ) && $_POST["category_select_display"] != "all" ) {
		$stmt = DBcon::get_conn()->prepare("SELECT * FROM products WHERE prod_category = ? ORDER BY prod_name");
		$stmt->bind_param("s", $_POST["category_select_display"]);
	}
	else {
		$stmt = DBcon::get_conn()->prepare("SELECT * FROM products ORDER BY prod_name");
	}
	
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->free_result();
	$stmt->close();
	
	if( $result->num_rows > 0 ) {
		while ( $products = $result->fetch_assoc() ) {
		echo "<tr>";
		echo "<td>".$products["prod_id"]."</td>"; 
		echo "<td>".$products["prod_name"]."</td>";    
		echo "<td>".$products["prod_category"]."</td>"; 
		echo "<td>".$products["prod_quantity"]."</td>"; 
		echo "</tr>";
		}
	}
}

function display_db_tools($user_role) {
	switch ($user_role ) {
		case 3:
			echo '<form class="user_action"name="user_action" method="post" action="store.php">';
			echo '<input type="radio" name="action_type" id="buy"         value="buy"> Buy products </input><br>';
			echo '<input type="radio" name="action_type" id="increment"   value="increment"> Stock shelves </input><br>';
			echo '<input type="radio" name="action_type" id="add_prod"    value="add_prod"> Add new product </input><br>';
			echo '<input type="radio" name="action_type" id="remove_prod" value="remove_prod"> Remove product </input>';
			echo '<br><br>';
			break;
		case 2:
			echo '<form class="user_action"name="user_action" method="post" action="store.php">';
			echo '<input type="radio" name="action_type" id="buy"         value="buy"> Buy products </input><br>';
			echo '<input type="radio" name="action_type" id="increment"   value="increment"> Stock shelves </input><br>';
			echo '<br><br>';

			break;
		case 1:
			echo '<form class="user_action"name="user_action" method="post" action="store.php">';
			echo '<input type="radio" name="action_type" id="buy"         value="buy"> Buy products </input><br>';
			echo '<br><br>';
			break;
	}
	
}

function buy_products ($id, $quantity ) {
	if($quantity > 0 ) {
		$stmt = DBcon::get_conn()->prepare("UPDATE products SET prod_quantity = (prod_quantity - ?) WHERE prod_id = ? AND (prod_quantity - ?) >= 0");
		$stmt->bind_param("iii", $quantity, $id, $quantity);
		$stmt->execute();
		$stmt->close();
	}
}

function add_new_product($id, $name, $category, $quantity ) {
	if($quantity > 0 ) {
		$stmt = DBcon::get_conn()->prepare("INSERT INTO products values (?,?,?,?)");
		$stmt->bind_param("ssis", $id, $name, $quantity, $category);
		$stmt->execute();
		$stmt->close();
	}
}

function remove_product($id) {
	$stmt = DBcon::get_conn()->prepare("DELETE FROM products WHERE prod_id = ?");
	$stmt->bind_param("s", $id);
	$stmt->execute();
	$stmt->close();
}

function increment ($id, $quantity) {
	if($quantity > 0) {
		$stmt = DBcon::get_conn()->prepare("UPDATE products SET prod_quantity = (prod_quantity + ?) WHERE prod_id = ?");
		$stmt->bind_param("is", $quantity, $id);
		$stmt->execute();
		$stmt->close();
	}
}

?>