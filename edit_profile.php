<?php

//edit_profile.php

include('database_connection.php');

if(isset($_POST['user_name']))
{
	if($_POST["user_new_password"] != '')
	{
		if ($_SESSION['type'] == "master") {
			$query = "
			UPDATE user_details SET 
			user_name = '".$_POST["user_name"]."', 
			user_email = '".$_POST["user_email"]."', 
			user_password = '".password_hash($_POST["user_new_password"], PASSWORD_DEFAULT)."' 
			WHERE user_id = '".$_SESSION["user_id"]."'
			";
		}else{
			$query = "
			UPDATE user_details SET 
				fname = '".$_POST["fname"]."', 
				mname = '".$_POST["mname"]."', 
				lname = '".$_POST["lname"]."', 
				address = '".$_POST["address"]."', 
				gender = '".$_POST["gender"]."', 
				user_name = '".$_POST["user_name"]."', 
				user_email = '".$_POST["user_email"]."', 
				user_password = '".password_hash($_POST["user_new_password"], PASSWORD_DEFAULT)."' 
				WHERE user_id = '".$_SESSION["user_id"]."'
			";
		}
		
	}
	else
	{
		if ($_SESSION['type'] == "master") {
			$query = "
			UPDATE user_details SET 
			user_name = '".$_POST["user_name"]."', 
			user_email = '".$_POST["user_email"]."'
			WHERE user_id = '".$_SESSION["user_id"]."'
			";
		}else{
			$query = "
			UPDATE user_details SET 
				fname = '".$_POST["fname"]."', 
				mname = '".$_POST["mname"]."', 
				lname = '".$_POST["lname"]."', 
				address = '".$_POST["address"]."', 
				gender = '".$_POST["gender"]."', 
				user_name = '".$_POST["user_name"]."', 
				user_email = '".$_POST["user_email"]."'
				WHERE user_id = '".$_SESSION["user_id"]."'
			";
		}
		
	}

	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	if(isset($result))
	{
		echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>Profile Details has been Updated</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		</div>';
	}
}

?>