<?php
//login.php

include('database_connection.php');

if(isset($_SESSION['type']))
{
	header("location:index.php");
}

$msg = $user_name = $user_password = '';

if (empty($_GET["notif"])) {

}else{
	$msg = '<div class="toast alert alert-success alert-dismissible fade hide" role="alert" data-delay="5000">
		<strong>'.$_GET["notif"].'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	</div>';
}

if(isset($_POST["login"])) {
	
	if (empty($_POST["user_password"])) {
		$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Password is required!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		</div>';
	}else{
		$user_password = $_POST["user_password"];
	}
	if (empty($_POST["user_name"])) {
		$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Username is required!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		</div>';
	}else{
		$user_name = $_POST["user_name"];
	}

	if ($user_name && $user_password) {
		$query = "
		SELECT * FROM user_details 
			WHERE user_name = :user_name
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
					'user_name'	=>	$user_name
				)
		);
		$count = $statement->rowCount();
		if($count > 0) {
			$result = $statement->fetchAll();
			foreach($result as $row) {

				if($row['user_status'] == 'Active') {
					if(password_verify($_POST["user_password"], $row["user_password"]))
				{
					$_SESSION['type'] = $row['user_type'];
					$_SESSION['user_id'] = $row['user_id'];
					$_SESSION['user_name'] = $row['user_name'];
					echo "<script>window.location.href='index.php'</script>";
				} else {
					$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong>Wrong Password!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>';
					}
				} else {
					$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong>Your account is disabled, Contact Master!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>';
				}
			}
		} else {
			$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Username is not registered!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>';
		}
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Inventory Management System</title>		
		<script src="js/jquery-3.4.1.min.js"></script>
		<link rel="stylesheet" href="bootstrap4/css/bootstrap.min.css" />
		<script src="bootstrap4/js/bootstrap.bundle.min.js"></script>
		<script src="js/all.js"></script>
	</head>
	<body>
		<br />
		<div class="container"><br><br><br><br>
			<div class="card" style="border-radius: 15px;">
				<div class="card-body">
					<center><h3 class="card-title">Inventory Management System</h3></center><hr>
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12">
							<img src="img/undraw_sign_in_e6hj.png" style="height: 300px; width: 400px;">
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12">
							<h4 class="card-title mb-2 text-muted"><center>Login</center></h4><hr>
							<form method="post">
								<div class="form-group">
									<span><?php echo $msg; ?></span>
								</div>
								<div class="form-group">
									<!-- <label>Username</label>
									<input type="text" id="user" name="user_name" class="form-control" value="<?php echo $user_name; ?>"> -->
									<label class="sr-only" for="inlineFormInputGroup">Username</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-user-tie"></i></div>
										</div>
										<input type="text" id="user" name="user_name" class="form-control" value="<?php echo $user_name; ?>" placeholder="Username" autocomplete="off">
									</div>
								</div>
								<div class="form-group">
									<!-- <label>Password</label>
									<input type="password" name="user_password" class="form-control" value="<?php echo $user_password; ?>"> -->
									<label class="sr-only" for="inlineFormInputGroup">Password</label>
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<div class="input-group-text"><i class="fa fa-user-lock"></i></div>
										</div>
										<input type="password" name="user_password" class="form-control" value="<?php echo $user_password; ?>" placeholder="Password">
									</div>
								</div>
								<div class="form-group">
									<input type="submit" name="login" value="Login" class="btn btn-info btn-block">
								</div>
								<div class="form-group">
									<!-- <a href="register">Haven't Register yet? click here.</a> -->
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
	    	$('.toast').toast('show');
	    	$('#user').focus();
	    </script>
	</body>
</html>