<?php
//profile.php

include('database_connection.php');

if(!isset($_SESSION['type']))
{
	header("location: login.php");
}

$query = "SELECT * FROM user_details WHERE user_id = '".$_SESSION["user_id"]."'";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$msg = $fname = $mname = $lname = $address = $gender = $photo = $username = $email = $user_id = '';
foreach($result as $row)
{
	$fname = $row['fname'];
	$mname = $row['mname'];
	$lname = $row['lname'];
	$address = $row['address'];
	$gender = $row['gender'];
	// $photo = $row['photo'];
	$name = $row['user_name'];
	$email = $row['user_email'];
}

if (isset($_POST["edit_img"])) {
	$target_dir = "img/profile/";
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
	if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
		$query = "UPDATE user_details SET photo = '".$target_file."' WHERE user_id = '".$_SESSION["user_id"]."'";
		$statement = $connect->prepare($query);
		$statement->execute();
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo "<script>window.location.href='profile?notif=Profile Picture has been Updated'</script>";
		}
	}
}
include('header.php');

?>		<?php
		if($_SESSION['type'] == 'user')
		{
		?>
		<br><div class="card">
			<div class="card-body">
				<h4 class="card-title">Edit Profile</h4><hr>
				<div class="row">
					<div class="col-6">
						<div class="row">
							<span>
								<?php
									if (empty($_GET["notif"])) {

									}else{
										echo '<div class="toast alert alert-success alert-dismissible fade hide" role="alert" data-delay="5000">
											<strong>'.$_GET["notif"].'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										</div>';
									}
								?>
							</span>
							<form method="POST" enctype="multipart/form-data">
								<div class="form-group col-12">
									<!-- <img class="img-thumbnail" alt="ako" style="height: 500px; width: 500px;" src=""> -->
									<?php echo '<img class="img-thumbnail" style="height: 500px; width: 500px;" src="'.$row['photo'].'" alt="profile" style="height: 60px; width: 60px; ">'; ?>
								</div>
								<div class="form-group col-12">
									<label>Choose your profile picture</label>
									<input type="file" id="profile_pic" name="profile_pic" class="form-control">
								</div>
								<div class="form-group col-12">
									<input type="submit" name="edit_img" id="editimg" value="Update Profile" class="btn btn-info btn-block" />
								</div>
							</form>
						</div>
					</div>
					<div class="col-6">
						<form method="post" id="edit_profile_form">
							<span id="message"></span>
							<div class="form-group col-12">
								<label>First Name</label>
								<input type="text" name="fname" id="fname" class="form-control" value="<?php echo $fname; ?>" />
							</div>
							<div class="form-group col-12">
								<label>Middle Name</label>
								<input type="text" name="mname" id="mname" class="form-control" value="<?php echo $mname; ?>" required />
							</div>
							<div class="form-group col-12">
								<label>Last Name</label>
								<input type="text" name="lname" id="lname" class="form-control" value="<?php echo $lname; ?>" required />
							</div>
							<div class="form-group col-12">
								<label>Address</label>
								<input type="text" name="address" id="address" class="form-control" value="<?php echo $address; ?>" required />
							</div>
							<div class="form-group col-12">
								<label>Gender</label>
								<select class="form-control" name="gender" id="gender">
									<option <?php if($gender == "Male"){echo "selected";} ?> value="Male">Male</option>
									<option <?php if($gender == "Female"){echo "selected";} ?> value="Female">Female</option>
								</select>
							</div>
							<div class="form-group col-12">
								<label>Username</label>
								<input type="text" name="user_name" id="user_name" class="form-control" value="<?php echo $name; ?>" required />
							</div>
							<div class="form-group col-12">
								<label>Email</label>
								<input type="email" name="user_email" id="user_email" class="form-control" required value="<?php echo $email; ?>" />
							</div>
							<hr>
							<label>Leave Password blank if you do not want to change</label><br>
							<div class="form-group col-12">
								<label>New Password</label>
								<input type="password" name="user_new_password" id="user_new_password" class="form-control" />
							</div>
							<div class="form-group col-12">
								<label>Re-enter Password</label>
								<input type="password" name="user_re_enter_password" id="user_re_enter_password" class="form-control" />
								<span id="error_password"></span>	
							</div>
							<div class="form-group col-12">
								<input type="submit" name="edit_prfile" id="edit_prfile" value="Edit" class="btn btn-info btn-block" />
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<?php
		}
		if($_SESSION['type'] == 'master')
		{
		?>
		<br><div class="card">
			<div class="card-body">
				<h5 class="card-title">Edit Profile</h5>
				<div class="row">
					<div class="col-6">
						<img class="img-thumbnail" src="img/801997_user_512x512.png">
					</div>
					<div class="col-6">
						<form method="post" id="edit_profile_form">
							<div class="row">
								<span id="message" class="col-12"></span>
								<div class="form-group col-12">
									<label>Username</label>
									<input type="text" name="user_name" id="user_name" class="form-control" value="<?php echo $name; ?>" required />
								</div>
								<div class="form-group col-12">
									<label>Email</label>
									<input type="email" name="user_email" id="user_email" class="form-control" required value="<?php echo $email; ?>" />
								</div><hr>
								<label>Leave Password blank if you do not want to change</label><br>
								<div class="form-group col-12">
									<label>New Password</label>
									<input type="password" name="user_new_password" id="user_new_password" class="form-control" />
								</div>
								<div class="form-group col-12">
									<label>Re-enter Password</label>
									<input type="password" name="user_re_enter_password" id="user_re_enter_password" class="form-control" />
									<span id="error_password"></span>	
								</div>
								<div class="form-group col-12">
									<input type="submit" name="edit_prfile" id="edit_prfile" value="Edit" class="btn btn-info btn-block" />
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
		}
		?>
<script type="text/javascript">
	$('.toast').toast('show')
</script>
<script>
$(document).ready(function(){
	$('#edit_profile_form').on('submit', function(event){
		event.preventDefault();

		if($('#user_new_password').val() != '')
		{
			if($('#user_new_password').val() != $('#user_re_enter_password').val())
			{
				$('#error_password').html('<label class="text-danger">Password Not Match</label>');
				return false;
			}
			else
			{
				$('#error_password').html('');
			}
		}
		$('#edit_prfile').attr('disabled', 'disabled');
		var form_data = $(this).serialize();
		$('#user_re_enter_password').attr('required',false);
		$.ajax({
			url:"edit_profile.php",
			method:"POST",
			data:form_data,
			success:function(data)
			{
				$('#edit_prfile').attr('disabled', false);
				$('#user_new_password').val('');
				$('#user_re_enter_password').val('');
				$('#message').html(data);
			}
		})
	});
});
</script>


			
