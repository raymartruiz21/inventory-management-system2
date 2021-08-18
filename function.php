<?php

function get_total_all_record(){
	include 'database_connection.php';
	$statement = $connect->prepare("SELECT * FROM product");
	$statement->execute();
	$result = $statement->fetchAll();
	return $statement->rowCount();
}

// product dropdown for category list 
function fill_category_list($connect) {
	$query = "
	SELECT * FROM category 
	WHERE category_status = 'active' 
	ORDER BY category_name ASC
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["category_id"].'">'.$row["category_name"].'</option>';
	}
	return $output;
}

// function fill_brand_list($connect, $category_id)
// {
// 	$query = "SELECT * FROM brand 
// 	WHERE brand_status = 'active' 
// 	AND category_id = '".$category_id."'
// 	ORDER BY brand_name ASC";
// 	$statement = $connect->prepare($query);
// 	$statement->execute();
// 	$result = $statement->fetchAll();
// 	$output = '<option value="">Select Brand</option>';
// 	foreach($result as $row)
// 	{
// 		$output .= '<option value="'.$row["brand_id"].'">'.$row["brand_name"].'</option>';
// 	}
// 	return $output;
// }

function get_user_name($connect, $user_id)
{
	$query = "
	SELECT user_name FROM user_details WHERE user_id = '".$user_id."'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row['user_name'];
	}
}

function fill_product_list($connect)
{
	$query = "
	SELECT * FROM product 
	WHERE product_status = 'active' 
	ORDER BY product_name ASC";
	
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		$output .= '<option value="'.$row["product_id"].'">'.$row["product_name"].'</option>';
	}
	return $output;
}

// function user_name(){
// 	$statement = $connect->prepare("SELECT * FROM user_details WHERE user_name='$usernameshow';");
// 	$statement->execute();
// 	$usernameshow = $_SESSION['user_name']; $userspro = mysqli_query($connect,"SELECT * FROM `user_details` WHERE `user_name`='$usernameshow';"); echo $userrow=mysqli_fetch_array($userspro);
// }

function fetch_product_details($product_id, $connect)
{
	$query = "
	SELECT * FROM product 
	WHERE product_code = '".$product_id."'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row) {
		$output['product_name'] = $row["product_name"];
		$output['quantity'] = $row["product_quantity"];
		$output['price'] = $row['product_price'];
		$output['code'] = $row['product_code'];
	}
	return $output;
}

//  
function available_product_quantity($connect, $product_id) {
	$product_data = fetch_product_details($product_id, $connect);
	$query = "
	SELECT 	inventory_order_product.quantity FROM inventory_order_product 
	INNER JOIN inventory_order ON inventory_order.inventory_order_id = inventory_order_product.inventory_order_id
	WHERE inventory_order_product.product_id = '".$product_id."' AND
	inventory_order.inventory_order_status = 'active'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$total = 0;

	foreach($result as $row) {
		$total = $total + $row['quantity'];
	}

	$available_quantity = intval($product_data['quantity']) - intval($total);
	if($available_quantity == 0) {
		$update_query = "
		UPDATE product SET 
		product_status = 'inactive' 
		WHERE product_id = '".$product_id."'
		";
		$statement = $connect->prepare($update_query);
		$statement->execute();
	}
	return $available_quantity;
}

function count_total_user($connect)
{
	$query = "
	SELECT * FROM user_details WHERE user_type='user'";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

function count_total_category($connect)
{
	$query = "
	SELECT * FROM category WHERE category_status='active'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

function count_total_order($connect)
{
	$query = "
	SELECT * FROM  order_tbl WHERE order_status='active'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

function count_total_product($connect)
{
	$query = "
	SELECT * FROM product WHERE product_status='active'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	return $statement->rowCount();
}

function count_total_order_value($connect)
{
	$query = "
	SELECT sum(order_total) as total_order_value FROM order_tbl 
	WHERE order_status='active'
	";
	if($_SESSION['type'] == 'user')
	{
		$query .= ' AND user_id = "'.$_SESSION["user_id"].'"';
	}
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return number_format($row['total_order_value'], 2);
	}
}

function count_total_cash_order_value($connect)
{
	$query = "
	SELECT sum(order_total) as total_order_value FROM order_tbl 
	WHERE payment_status = 'paid' 
	AND order_status='active'
	";
	if($_SESSION['type'] == 'user')
	{
		$query .= ' AND user_id = "'.$_SESSION["user_id"].'"';
	}
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return number_format($row['total_order_value'], 2);
	}
}

function count_total_credit_order_value($connect)
{
	$query = "
	SELECT sum(order_total) as total_order_value FROM order_tbl WHERE payment_status = 'unpaid' AND order_status='active'
	";
	if($_SESSION['type'] == 'user')
	{
		$query .= ' AND user_id = "'.$_SESSION["user_id"].'"';
	}
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return number_format($row['total_order_value'], 2);
	}
}

function get_user_wise_total_order($connect)
{
	$query = 'SELECT SUM(order_tbl.order_total) as order_total, 
	SUM(CASE WHEN order_tbl.payment_status = "paid" THEN order_tbl.order_total ELSE 0 END) AS cash_order_total, 
	SUM(CASE WHEN order_tbl.payment_status = "unpaid" THEN order_tbl.order_total ELSE 0 END) AS credit_order_total, 
	user_details.user_name,order_name
	FROM order_tbl 
	INNER JOIN user_details ON user_details.user_id = order_tbl.user_id 
	WHERE order_tbl.order_status = "active"';
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tr>
				<th>Add by</th>
				<th>Reciever</th>
				<th>Total Order Value</th>
				<th>Total Paid Order</th>
				<th>Total Unpaid Order</th>
			</tr>
	';

	$total_order = 0;
	$total_cash_order = 0;
	$total_credit_order = 0;
	foreach($result as $row)
	{
		$output .= '
		<tr>
			<td>'.$row['user_name'].'</td>
			<td>'.$row['order_name'].'</td>
			<td align="right">₱ '.$row["order_total"].'</td>
			<td align="right">₱ '.$row["cash_order_total"].'</td>
			<td align="right">₱ '.$row["credit_order_total"].'</td>
		</tr>
		';

		$total_order = $total_order + $row["order_total"];
		$total_cash_order = $total_cash_order + $row["cash_order_total"];
		$total_credit_order = $total_credit_order + $row["credit_order_total"];
	}
	$output .= '
	<tr>
		<td align="right" colspan="2"><b>Total</b></td>
		<td align="right"><b>₱ '.$total_order.'</b></td>
		<td align="right"><b>₱ '.$total_cash_order.'</b></td>
		<td align="right"><b>₱ '.$total_credit_order.'</b></td>
	</tr></table></div>
	';
	return $output;
}

?>