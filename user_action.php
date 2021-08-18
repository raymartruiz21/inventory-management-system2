<?php

//user_action.php

include('database_connection.php');

if(isset($_POST['btn_action'])) {

	// add user
	if($_POST['btn_action'] == 'Add') {
		$query = "INSERT INTO user_details (fname, mname, lname, address, gender, user_email, user_password, user_name, user_type, user_status) VALUES (:fname, :mname, :lname, :address, :gender, :user_email, :user_password, :user_name, :user_type, :user_status)";	
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':fname'		=>	$_POST["fname"],
				':mname'		=>	$_POST["mname"],
				':lname'		=>	$_POST["lname"],
				':address'		=>	$_POST["address"],
				':gender'		=>	$_POST["gender"],
				':user_email'		=>	$_POST["user_email"],
				':user_password'	=>	password_hash($_POST["user_password"], PASSWORD_DEFAULT),
				':user_name'		=>	$_POST["user_name"],
				':user_type'		=>	'user',
				':user_status'		=>	'active'
			)
		);
		$result = $statement->fetchAll();
		if(isset($result)){
			echo 'Notice: New User has been Added';
		}
	}

	// get single data
	if($_POST['btn_action'] == 'fetch_single') {
		$query = "SELECT * FROM user_details WHERE user_id = :user_id";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':user_id'	=>	$_POST["user_id"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row) {
			$output['fname'] = $row['fname'];
			$output['mname'] = $row['mname'];
			$output['lname'] = $row['lname'];
			$output['address'] = $row['address'];
			$output['gender'] = $row['gender'];
			$output['photo'] = $row['photo'];
			$output['user_email'] = $row['user_email'];
			$output['user_name'] = $row['user_name'];
		}
		echo json_encode($output);
	}

	// update user
	if($_POST['btn_action'] == 'Edit') {
		if($_POST['user_password'] != '') {
			$query = "
			UPDATE user_details SET 
				fname = '".$_POST["fname"]."', 
				mname = '".$_POST["mname"]."', 
				lname = '".$_POST["lname"]."', 
				address = '".$_POST["address"]."', 
				gender = '".$_POST["gender"]."', 
				user_name = '".$_POST["user_name"]."', 
				user_email = '".$_POST["user_email"]."',
				user_password = '".password_hash($_POST["user_password"], PASSWORD_DEFAULT)."' 
				WHERE user_id = '".$_POST["user_id"]."'
			";
		} else {
			$query = "
			UPDATE user_details SET 
				fname = '".$_POST["fname"]."', 
				mname = '".$_POST["mname"]."', 
				lname = '".$_POST["lname"]."', 
				address = '".$_POST["address"]."', 
				gender = '".$_POST["gender"]."',
				user_name = '".$_POST["user_name"]."', 
				user_email = '".$_POST["user_email"]."'
				WHERE user_id = '".$_POST["user_id"]."'
			";
		}
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		if(isset($result)) {
			echo 'Notice: User Details has been Updated!';
		}
	}

	// delete
	if($_POST['btn_action'] == 'delete') {
		$query = "
		DELETE FROM user_details
		WHERE user_id = :user_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':user_id'	=>	$_POST["user_id"]
			)
		);	
		$result = $statement->fetchAll();	
		if(isset($result)) {
			echo 'Notice: User has been Deleted!';
		}
	}

	// activate/deactivate account
	if($_POST['btn_action'] == 'ac_de') {
		$status = 'Active';
		if($_POST['status'] == 'Active') {
			$status = 'Inactive';
		}
		$query = "
		UPDATE user_details 
		SET user_status = :user_status 
		WHERE user_id = :user_id
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':user_status'	=>	$status,
				':user_id'		=>	$_POST["user_id"]
			)
		);	
		$result = $statement->fetchAll();	
		if(isset($result)) {
			echo 'User Status change to ' . $status;
		}
	}
}

?>