<?php

include('database_connection.php');
include('function.php');

if(!isset($_SESSION['type']))
{
	header("location: login.php");
}

include('header.php');

if ($_GET["order_id"] != '') {
	$order_id = $_GET["order_id"];
	
	$query = "SELECT * FROM order_tbl WHERE order_id = :order_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':order_id'	=>	$_GET["order_id"]
			)
		);

		$result = $statement->fetchAll();
		$output = array();
		foreach($result as $row) {

?>
<br><div class="card">
	<div class="card-body">
		<h5 class="card-title">Update Order</h5>
		<form method="post" id="edit_profile_form">
			<div class="row">
				<div class="col-12">
					<span id="message"></span>
				</div>
				<div class="form-group col-6">
					<label>Customer's Name</label>
					<input type="text" name="name" id="name" class="form-control" value="<?php echo $row['order_name']; ?>" required />
				</div>
				<div class="form-group col-6">
					<label>date</label>
					<input type="date" name="date" id="date" class="form-control" required value="<?php echo $row['order_date']; ?>" />
				</div>
				<div class="form-group col-6">
					<label>Address</label>
					<input type="text" name="address" id="address" class="form-control" required value="<?php echo $row['order_address']; ?>" />
				</div>
				<div class="form-group col-6">
					<label>Payment Status</label>
					<select name="status" class="form-control">
						<option name="status" value="paid" <?php if($row['payment_status'] == 'paid'){echo "selected";}  ?>>Paid</option>
						<option name="status" value="unpaid" <?php if($row['payment_status'] == 'unpaid'){echo "selected";}  ?>>Unpaid</option>
					</select>
				</div><hr>
				
				
<?php
			// $output['order_name'] = $row['order_name'];
			// $output['order_date'] = $row['order_date'];
			// $output['order_address'] = $row['order_address'];
			// $output['payment_status'] = $row['payment_status'];
		}
  		$sub_query = "SELECT product_code, invoice.quantity FROM product 
		JOIN invoice ON product.product_code = invoice.product_id
		JOIN order_tbl ON invoice.order_id=order_tbl.order_id WHERE order_id = '".$order_id."'";

		// $sub_query = "SELECT product FROM invoice WHERE order_id = '".$order_id."'";
		$statement = $connect->prepare($sub_query);
		$statement->execute();
		$sub_result = $statement->fetchAll();
		$product_details = '';
		$count = 0;

		foreach($sub_result as $sub_row) {

			?>
				<div class="form-group col-8">
					<input type="text" name="product_name" id="product_name" value="<?php echo $sub_row["product_code"]; ?>" class="form-control" />
				</div>
				<div class="form-group col-4">
					<input type="text" name="qty" id="qty" value="<?php echo $sub_row["quantity"]; ?>" class="form-control" />
				</div>
			<?php

			$count = $count + 1;
		}
		
		echo $product_details;
}
?>

				<div class="form-group col-12">
					<input type="submit" name="edit_prfile" id="edit_prfile" value="Edit" class="btn btn-info btn-block" />
				</div>
			</div>
		</form>
	</div>
</div>