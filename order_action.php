<?php

//order_action.php

include('database_connection.php');

include('function.php');

// insert order
if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add'){
		$query = "
		INSERT INTO order_tbl (user_id, order_total, order_date, order_name, order_address, payment_status, order_status, order_created_date) 
		VALUES (:user_id, :order_total, :order_date, :order_name, :order_address, :payment_status, :order_status, :order_created_date)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':user_id'						=>	$_SESSION["user_id"],
				':order_total'					=>	0,
				':order_date'					=>	$_POST['order_date'],
				':order_name'					=>	$_POST['order_name'],
				':order_address'				=>	$_POST['order_address'],
				':payment_status'				=>	$_POST['payment_status'],
				':order_status'					=>	'active',
				':order_created_date'			=>	date("Y-m-d")
			)
		);

		$result = $statement->fetchAll();
		$statement = $connect->query("SELECT LAST_INSERT_ID()");
		$order_id = $statement->fetchColumn();

		if(isset($order_id)) {
			$total_amount = 0;
			for($count = 0; $count<count($_POST["product_id"]); $count++) {

				$product_details = fetch_product_details($_POST["product_id"][$count], $connect);
					$sub_query = "
					INSERT INTO invoice (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)
					";
					$statement = $connect->prepare($sub_query);
					$statement->execute(
						array(
							':order_id'				=>	$order_id,
							':product_id'			=>	$_POST["product_id"][$count],
							':quantity'				=>	$_POST["quantity"][$count],
							':price'				=>	$product_details['price']
						)
					);
					$base_price = $product_details['price'] * $_POST["quantity"][$count];
					$total_amount = $total_amount + $base_price;
			}

			$update_query = "
			UPDATE order_tbl 
			SET order_total = '".$total_amount."' 
			WHERE order_id = '".$order_id."'
			";
			$statement = $connect->prepare($update_query);
			$statement->execute();
			$result = $statement->fetchAll();

			if(isset($result)) {
				echo 'New order has been Added';
			}
		}
	}

// fetch
	if($_POST['btn_action'] == 'fetch_single') {

		$query = "SELECT * FROM order_tbl WHERE order_id = :order_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':order_id'	=>	$_POST["order_id"]
			)
		);

		$result = $statement->fetchAll();
		$output = array();
		foreach($result as $row) {

			$output['order_name'] = $row['order_name'];
			$output['order_date'] = $row['order_date'];
			$output['order_address'] = $row['order_address'];
			$output['payment_status'] = $row['payment_status'];
		}

		$sub_query = "SELECT * FROM invoice WHERE order_id = '".$_POST["order_id"]."'";
		$statement = $connect->prepare($sub_query);
		$statement->execute();
		$sub_result = $statement->fetchAll();
		$product_details = '';
		$count = '';

		foreach($sub_result as $sub_row) {

			$product_details .= '
			<script>
			$(document).ready(function(){
				$("#product_id'.$count.'").selectpicker("val", '.$sub_row["product_id"].');
				$(".selectpicker").selectpicker();
			});
			</script>
			<span id="row'.$count.'">
				<div class="row">
					<div class="col-md-7">
						<select name="product_id[]" id="product_id'.$count.'" class="form-control selectpicker" data-live-search="true" required>
							'.fill_product_list($connect).'
						</select>
						<input type="hidden" name="hidden_product_id[]" id="hidden_product_id'.$count.'" value="'.$sub_row["product_id"].'" />
					</div>
					<div class="col-md-3">
						<input type="text" name="quantity[]" class="form-control" value="'.$sub_row["quantity"].'" required />
					</div>
					<div class="col-md-1">
			';

			if($count == '')
			{
				$product_details .= '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-sm">+</button>';
			}
			else
			{
				$product_details .= '<button type="button" name="remove" id="'.$count.'" class="btn btn-danger btn-sm remove">-</button>';
			}
			$product_details .= '
						</div>
					</div>
				</div><br />
			</span>
			';
			$count = $count + 1;
		}
		
		$output['product_details'] = $product_details;
		echo json_encode($output);
	}

// update
	// if($_POST['btn_action'] == 'Edit')
	// {
	// 	$delete_query = "
	// 	DELETE FROM invoice 
	// 	WHERE order_id = '".$_POST["order_id"]."'
	// 	";
	// 	$statement = $connect->prepare($delete_query);
	// 	$statement->execute();
	// 	$delete_result = $statement->fetchAll();
	// 	if(isset($delete_result))
	// 	{
	// 		$total_amount = 0;
	// 		for($count = 0; $count < count($_POST["product_id"]); $count++)
	// 		{
	// 			$product_details = fetch_product_details($_POST["product_id"][$count], $connect);
	// 			$sub_query = "
	// 			INSERT INTO invoice (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)
	// 			";
	// 			$statement = $connect->prepare($sub_query);
	// 			$statement->execute(
	// 				array(
	// 					':order_id'				=>	$_POST["order_id"],
	// 					':product_id'			=>	$_POST["product_id"][$count],
	// 					':quantity'				=>	$_POST["quantity"][$count],
	// 					':price'				=>	$product_details['price']
	// 				)
	// 			);
	// 			$base_price = $product_details['price'] * $_POST["quantity"][$count];
	// 			$total_amount = $total_amount + $base_price;
	// 		}
	// 		$update_query = "
	// 		UPDATE order_tbl SET 
	// 		order_total = :order_total, 
	// 		order_date = :order_date, 
	// 		order_name = :order_name, 
	// 		order_address = :order_address, 
	// 		payment_status = :payment_status
	// 		WHERE order_id = :order_id";
			
	// 		$statement = $connect->prepare($update_query);
	// 		$statement->execute(
	// 			array(
	// 				':order_total'			=>	$total_amount,
	// 				':order_date'			=>	$_POST["order_date"],
	// 				':order_name'			=>	$_POST["order_name"],
	// 				':order_address'		=>	$_POST["order_address"],
	// 				':payment_status'		=>	$_POST["payment_status"],
	// 				':order_id'				=>	$_POST["order_id"]
	// 			)
	// 		);
	// 		$result = $statement->fetchAll();
	// 		if(isset($result))
	// 		{
	// 			echo 'Order has been Updated';
	// 		}
	// 	}
	// }

// delete
	if($_POST['btn_action'] == 'delete') {
		
		$delete_query = "
		DELETE FROM order_tbl 
		WHERE order_id = '".$_POST["order_id"]."'
		";
		$statement = $connect->prepare($delete_query);
		$statement->execute(
			array(
				':order_id'		=>	$_POST["order_id"]
			)
		);

		$result = $statement->fetchAll();
		if(isset($result)) {
			$delete_query = "
				DELETE FROM invoice 
				WHERE order_id = '".$_POST["order_id"]."'
				";
				$statement = $connect->prepare($delete_query);
				$statement->execute(
				array(
					':order_id'		=>	$_POST["order_id"]
				)
			);
			echo 'Order has been deleted';
		}
	}

	#ac_de
	if($_POST['btn_action'] == 'ac_de')
	{
		$status = 'paid';
		if($_POST['status'] == 'paid')
		{
			$status = 'unpaid';
		}
		$query = "
		UPDATE order_tbl 
		SET payment_status = :order_status 
		WHERE order_id = :order_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':order_status'	=>	$status,
				':order_id'		=>	$_POST["order_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Order status change to ' . ucfirst($status);
		}
	}
}

?>