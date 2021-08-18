<?php
include('database_connection.php');

if(isset($_POST['btn_action'])) {
	// insert category
	if($_POST['btn_action'] == 'Add') {
		$query = "
		INSERT INTO category (category_name) 
		VALUES (:category_name)
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':category_name'	=>	$_POST["category_name"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Category Name Added';
		}
	}
	
	// get single data
	if($_POST['btn_action'] == 'fetch_single') {
		$query = "SELECT * FROM category WHERE category_id = :category_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':category_id'	=>	$_POST["category_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['category_name'] = $row['category_name'];
		}
		echo json_encode($output);
	}

	// update
	if($_POST['btn_action'] == 'Edit') {
		$query = "
		UPDATE category set category_name = :category_name  
		WHERE category_id = :category_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':category_name'	=>	$_POST["category_name"],
				':category_id'		=>	$_POST["category_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Category Name has been Updated';
		}
	}

	// delete
	if($_POST['btn_action'] == 'delete') {
		$query = "
		DELETE FROM category WHERE category_id = :category_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':category_id'		=>	$_POST["category_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Category name has been Deleted';
		}
	}

	// activate/deactivate
	if($_POST['btn_action'] == 'ac_de') {
		$status = 'active';
		if($_POST['status'] == 'active')
		{
			$status = 'inactive';	
		}
		$query = "
		UPDATE category 
		SET category_status = :category_status 
		WHERE category_id = :category_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':category_status'	=>	$status,
				':category_id'		=>	$_POST["category_id"]
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Category status change to ' . $status;
		}
	}
}

?>