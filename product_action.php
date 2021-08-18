<?php

//product_action.php

include('database_connection.php');
require 'phpqrcode/qrlib.php';

if(isset($_POST['btn_action'])) {
	// insert new data
	if($_POST['btn_action'] == 'Add') {
		function code($lenght = 10){
			$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
			$shuffled = substr(str_shuffle($str), 0, $lenght);
			return $shuffled;
		}

		$code = code(10);
		$path = "img/QRcode/";
		$file = $path.basename($_POST['product_name']).".png";
		// png (text, location to be save, ECC level, pixel size, frame size)
		QRcode::png($code, $file, 'L', 10, 2);
		$query = "
		INSERT INTO product (category_id, product_name, product_quantity, product_price, product_status, product_date,product_code,QRcode_img) 
		VALUES (:category_id, :product_name, :product_quantity, :product_price, :product_status, :product_date,:product_code,:QRcode_img)";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':category_id'			=>	$_POST['category_id'],
				':product_name'			=>	$_POST['product_name'],
				':product_quantity'		=>	$_POST['product_quantity'],
				':product_price'		=>	$_POST['product_price'],
				':product_status'		=>	"active",
				':product_date'			=>	date("Y-m-d"),
				':product_code'			=>	$code,
				':QRcode_img'			=>	$file
			)
		);

		$result = $statement->fetchAll();
		if(isset($result)) {
			echo 'New Product has been Added';
		}
	}

	// view single data 
	if($_POST['btn_action'] == 'product_details') {
		$query = "
		SELECT * FROM product 
		INNER JOIN category ON category.category_id = product.category_id 
		WHERE product.product_id = '".$_POST["product_id"]."'
		";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		$output = '
		<table class="table table-boredered">
		';
		foreach($result as $row)
		{
			$status = '';
			if($row['product_status'] == 'active')
			{
				$status = '<span class="text-success">Active</span>';
			}
			else
			{
				$status = '<span class="text-danger">Inactive</span>';
			}
			$output .= '
			<tr>
				<td>Product Name</td>
				<td>'.$row["product_name"].'</td>
			</tr>
			<tr>
				<td>Category</td>
				<td>'.$row["category_name"].'</td>
			</tr>
			<tr>
				<td>Available Quantity</td>
				<td>'.$row["product_quantity"].'</td>
			</tr>
			<tr>
				<td>Base Price</td>
				<td>₱ '.$row["product_price"].'</td>
			</tr>
			<tr>
				<td>Date Added</td>
				<td>'.$row["product_date"].'</td>
			</tr>
			<tr>
				<td>Status</td>
				<td>'.$status.'</td>
			</tr>
			<tr>
				<td>Product Code</td>
				<td>'.$row["product_code"].'</td>
			</tr>
			<tr>
				<td>QRcode</td>
				<td><img class="rounded" src="'.$row["QRcode_img"].'"></td>
			</tr>
			';
		}
		$output .= '
			</table>
		</div>
		';
		echo $output;
	}

	// fetch
	if($_POST['btn_action'] == 'fetch_single') {
		$query = "SELECT * FROM product WHERE product_id = :product_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':product_id'	=>	$_POST["product_id"]
			)
		);

		$result = $statement->fetchAll();

		foreach($result as $row) {
			$output['category_id'] = $row['category_id'];
			$output['product_name'] = $row['product_name'];
			$output['product_quantity'] = $row['product_quantity'];
			$output['product_price'] = $row['product_price'];
		}
		echo json_encode($output);
	}

	// update
	// if($_POST['btn_action'] == 'Edit') {
	// 	$query = "
	// 	UPDATE product SET 
	// 	category_id = :category_id, 
	// 	product_name = :product_name,
	// 	product_quantity = :product_quantity, 
	// 	product_price = :product_price, 
	// 	WHERE product_id = :product_id";

	// 	$statement = $connect->prepare($query);
	// 	$statement->execute(
	// 		array(
	// 			':product_id'		=>	$_POST['product_id'],
	// 			':category_id'		=>	$_POST['category_id'],
	// 			':product_name'		=>	$_POST['product_name'],
	// 			':product_quantity'	=>	$_POST['product_quantity'],
	// 			':product_price'	=>	$_POST['product_price']
	// 		)
	// 	);
	// 	$result = $statement->fetchAll();
	// 	if(isset($result))
	// 	{
	// 		echo 'Product Details has been Updated';
	// 	}
	// }
	// update 
	if($_POST['btn_action'] == 'Edit') {
			$query = "
			UPDATE product SET 
				category_id = '".$_POST['category_id']."', 
				product_name = '".$_POST['product_name']."', 
				product_quantity = '".$_POST['product_quantity']."', 
				product_price = '".$_POST['product_price']."'
				WHERE product_id = '".$_POST['product_id']."'
			";
		
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();

		if(isset($result)) {
			echo 'Product Details has been Updated';
		}
	}

	// delete 
	if($_POST['btn_action'] == 'delete') {
		$query = "
		DELETE FROM product
		WHERE product_id = :product_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':product_id'		=>	$_POST["product_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Product has been Deleted!';
		}
	}

	// activate_deactivate 
	if($_POST['btn_action'] == 'ac_de')
	{
		$status = 'active';
		if($_POST['status'] == 'active')
		{
			$status = 'inactive';
		}
		$query = "
		UPDATE product 
		SET product_status = :product_status 
		WHERE product_id = :product_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':product_status'	=>	$status,
				':product_id'		=>	$_POST["product_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Product status change to ' . $status;
		}
	}
}


?>