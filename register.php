<?php
//login.php

include('database_connection.php');


$msg = $fname = $mname = $lname = $address = $gender = $username = $email = $cpassword = $profile_pic = "";
$target_dir = "img/profile/";

	if (isset($_POST["register"])) {
		if (empty($_POST["cpassword"])) {
			$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Confirm password is required!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>';
		}else{
			$cpassword = $_POST["cpassword"];
		}
		if (empty($_POST["password"])) {
			$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Password is required!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>';
		}else{
			$password = $_POST["password"];
		}
		if (empty($_POST["email"])) {
			$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Email is required!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>';
		}else{
			$email = $_POST["email"];
		}
		if (empty($_POST["username"])) {
			$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Username is required!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>';
		}else{
			$username = $_POST["username"];
		}
		if (empty($_POST["gender"])) {
			$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Gender is required!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>';
		}else{
			$gender = $_POST["gender"];
		}
		if (empty($_POST["address"])) {
			$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Address is required!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>';
		}else{
			$address = $_POST["address"];
		}
		if (empty($_POST["lname"])) {
			$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Last name is required!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>';
		}else{
			$lname = $_POST["lname"];
		}
		if (empty($_POST["mname"])) {
			$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>Middle name is required!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>';
		}else{
			$mname = $_POST["mname"];
		}
		if (empty($_POST["fname"])) {
			$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<strong>First name is required!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>';
		}else{
			$fname = $_POST["fname"];
		}

		if ($fname && $mname && $lname && $address && $gender && $username && $email && $cpassword && $password) {

			#basename is yung pinaka filename
			$target_file = $target_dir.basename($_FILES["profile_pic"]["name"]);

			$uploadOK = 1;
			#kapag ang picture na inupload ay meron na sa img folder

			if (file_exists($target_file)) {
				#rand means random number
				$target_file = $target_dir.rand(1,9).rand(1,9).rand(1,9).rand(1,9)."_".basename($_FILES["profile_pic"]["name"]);
				$uploadOK = 1;
			}

			#Taga kula ng extension file
			$imageFileType = pathInfo($target_file	, PATHINFO_EXTENSION);

			#kapag di nag tugma sa extension name
			if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
				$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Sorry, only jpeg, jpg, png, gif are allowed!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>';
				$uploadOK = 0;
			}

			if (!preg_match("/^[ a-zA-Z ]*$/", $fname) || !preg_match("/^[ a-zA-Z ]*$/", $mname) || !preg_match("/^[ a-zA-Z ]*$/", $lname)) 
			{
				$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Space and letters are only allowed in first name, middle name and last name!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>';
			}else{
				$c_fname = strlen($fname);
				if ($c_fname < 3) {
					$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong>First name is toot short!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>';
				}else{
					$c_mname = strlen($mname);
					if ($c_mname < 3) {
						$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<strong>Middle name is toot short!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						</div>';
					}else{
						$c_lname = strlen($lname);
						if ($c_lname < 3) {
							$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Last name is toot short!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							</div>';
						}else{
							if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
								$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
									<strong>Invalid Email. Try again!!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>';
							}else{
								$query = "SELECT * FROM user_details WHERE user_email=:user_email";
								$statement = $connect->prepare($query);
								$statement->execute(
									array(
											'user_email'	=>	$email
										)
								);
								$count = $statement->rowCount();
								if ($count > 0) {
									$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
										<strong>Email is already taken!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									</div>';
								}else{
									$query = "SELECT * FROM user_details WHERE user_name=:user_name";
									$statement = $connect->prepare($query);
									$statement->execute(
										array(
												'user_name'	=>	$username
											)
									);
									$count = $statement->rowCount();
									if ($count > 0) {
										$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
											<strong>Username is already taken!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										</div>';
									}else{
										$c_password = strlen($password);
										if ($c_password < 8) {
											$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
												<strong>Password is too short, at least 8 digit!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
											</div>';
										}else{
											if ($password != $cpassword) {
												$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
													<strong>Password did not match!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												</div>';
											}else{
												if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
													$query = "INSERT INTO user_details (fname, mname, lname, address, gender, photo, user_email, user_password, user_name, user_type, user_status) 
												 		VALUES (:fname,:mname,:lname,:address,:gender,:target_file,:user_email, :user_password, :user_name, :user_type, :user_status)";	
													$statement = $connect->prepare($query);
													$statement->execute(
														array(
															':fname'		=>	$fname,
															':mname'		=>	$mname,
															':lname'		=>	$lname,
															':address'		=>	$address,
															':gender'		=>	$gender,
															':target_file'	=>	$target_file,
															':user_email'	=>	$email,
															':user_password'=>	password_hash($password, PASSWORD_DEFAULT),
															':user_name'	=>	$username,
															':user_type'	=>	'user',
															':user_status'	=>	'active'
														)
													);
													$result = $statement->fetchAll();
													if(isset($result)){
														echo "<script>window.location.href='login?notif=New user has been added!'</script>";
													}
												}else{
													$msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
														<strong>Sorry, there was an error on uploading your file</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													</div>';

												}
											}
										}
									}
								}
							}
						}
					}
				}
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
	</head>
	<body>
		<br />
		<div class="container"><br><br><br><br>
			<div class="card" style="border-radius: 15px;">
				<div class="card-body">
					<center><h3 class="card-title">Inventory Management System</h3></center><hr>
					<div class="row">
						<div class="col-lg-6 col-md-12 col-sm-12">
							<center><img src="img/undraw_welcome_3gvl.png" style="height: 300px; width: 400px;"></center>
						</div>
						<div class="col-lg-6 col-md-12 col-sm-12">
							<h4 class="card-title mb-2 text-muted"><center>Register Account</center></h4><hr>
							<form method="POST" enctype="multipart/form-data">
								<div class="form-group">
									<font color="red"><?php echo $msg; ?></font>
								</div>
								<div class="row">
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<label>First name</label>
										<input type="text" name="fname" class="form-control" value="<?php echo $fname; ?>"/>
									</div>
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<label>Middle name</label>
										<input type="text" name="mname" class="form-control" value="<?php echo $mname; ?>"/>
									</div>
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<label>Last name</label>
										<input type="text" name="lname" class="form-control" value="<?php echo $lname; ?>"/>
									</div>
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<label>Address</label>
										<input type="text" name="address" class="form-control" value="<?php echo $address; ?>"/>
									</div>
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<label>Gender</label>
										<select name="gender" class="form-control">
											<option value="">Select Gender</option>
											<option <?php if($gender == "Male"){echo "selected";} ?> value="Male">Male</option>
											<option <?php if($gender == "Female"){echo "selected";} ?> value="Female">Female</option>
										</select>
									</div>
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<label>Choose your profile picture</label>
										<input type="file" id="profile_pic" name="profile_pic" class="form-control" required>
									</div>
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<label>Username</label>
										<input type="text" name="username" class="form-control" value="<?php echo $username; ?>"/>
									</div>
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<label>Email</label>
										<input type="text" name="email" class="form-control" value="<?php echo $email; ?>"/>
									</div>
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<label>Password</label>
										<input type="password" name="password" class="form-control" />
									</div>
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<label>Confirm Password</label>
										<input type="password" name="cpassword" class="form-control" />
									</div>
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<input type="submit" name="register" value="Register" class="btn btn-info btn-block" />
									</div>
									<div class="form-group col-lg-6 col-md-12 col-sm-12 col-xs-12">
										<center><a href="login">If you have account, you can Login your account!</a></center>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>