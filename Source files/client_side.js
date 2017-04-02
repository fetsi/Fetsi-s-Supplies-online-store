		
		
window.onload = function() {

var buy = document.getElementById('buy');
var increment = document.getElementById('increment');
var remove_prod = document.getElementById('remove_prod');
var add_prod = document.getElementById('add_prod');

//initially hide everything until uer chooses an action
document.getElementById("product_id").style.display = "none";
document.getElementById("product_name").style.display = "none";
document.getElementById("product_quantity").style.display = "none";
document.getElementById("category_select_new_prod").style.display = "none";

buy.onclick = buy_increment_handler;
increment.onclick = buy_increment_handler;
remove_prod.onclick = remove_prod_handler;
add_prod.onclick = add_prod_handler;

}

function buy_increment_handler() {
	document.getElementById("product_id").style.display = "block";
	document.getElementById("product_name").style.display = "none";
	document.getElementById("product_quantity").style.display = "block";
	document.getElementById("category_select_new_prod").style.display = "none";
}

function add_prod_handler() {
	document.getElementById("product_id").style.display = "block";
	document.getElementById("product_name").style.display = "block";
	document.getElementById("product_quantity").style.display = "block";
	document.getElementById("category_select_new_prod").style.display = "block";
}

function remove_prod_handler() {
	document.getElementById("product_id").style.display = "block";
	document.getElementById("product_name").style.display = "none";
	document.getElementById("product_quantity").style.display = "none";
	document.getElementById("category_select_new_prod").style.display = "none";
}


