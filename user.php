<?php
include('database_connection.php');

if(!isset($_SESSION["type"]))
{
	header('location:login.php');
}
if($_SESSION["type"] != 'master')
{
	header("location:index.php");
}
include('header.php');

// if(isset($_POST['btn_action'])) {

// 	// add user
// 	if($_POST['btn_action'] == 'Add') {
// 		$target_dir = "img/profile/";
// 		$photo = '';
// 		#basename is yung pinaka filename
// 		$target_file = $target_dir.basename($_FILES["photo"]["name"]);

// 		$uploadOK = 1;
// 		#kapag ang picture na inupload ay meron na sa img folder

// 		if (file_exists($target_file)) {
// 			#rand means random number
// 			$target_file = $target_dir.rand(1,9).rand(1,9).rand(1,9).rand(1,9)."_".basename($_FILES["photo"]["name"]);
// 			$uploadOK = 1;
// 		}

// 		#Taga kula ng extension file
// 		$imageFileType = pathInfo($target_file, PATHINFO_EXTENSION);

// 		#kapag di nag tugma sa extension name
// 		if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
// 			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
// 				<strong>Sorry, only jpeg, jpg, png, gif are allowed!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
// 			</div>';
// 			$uploadOK = 0;
// 		}
// 		if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)){
// 			// function ran_pass($lenght = 6){
// 			// 	$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
// 			// 	$shuffled = substr(str_shuffle($str), 0, $lenght);
// 			// 	return $shuffled;
// 			// }

// 			// $code = ran_pass(6);
// 			$query = "INSERT INTO user_details (fname, mname, lname, address, gender, photo, user_email, user_password, user_name, user_type, user_status) VALUES (:fname, :mname, :lname, :address, :gender, :target_file, :user_email, :user_password, :user_name, :user_type, :user_status)";	
// 			$statement = $connect->prepare($query);
// 			$statement->execute(
// 				array(
// 					':fname'		=>	$_POST["fname"],
// 					':mname'		=>	$_POST["mname"],
// 					':lname'		=>	$_POST["lname"],
// 					':address'		=>	$_POST["address"],
// 					':gender'		=>	$_POST["gender"],
// 					':target_file'	=>	$target_file,
// 					':user_email'		=>	$_POST["user_email"],
// 					':user_password'	=>	password_hash($_POST["user_password"], PASSWORD_DEFAULT),
// 					':user_name'		=>	$_POST["user_name"],
// 					':user_type'		=>	'user',
// 					':user_status'		=>	'active'
// 				)
// 			);
// 			$result = $statement->fetchAll();
// 			if(isset($result)){
// 				echo '<br><div class="alert alert-success alert-dismissible fade show" role="alert">
// 					<strong>Notice: New User has been Added</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
// 				</div>';
// 			}
// 		}
// 	}
// }

?>
		<br><span id="alert_action"></span>
		<div class="row">
			<div class="col-lg-12">
				<div class="card" style="border-radius: 15px;">
					<div class="card-body">
						<h5 class="card-title"><center><div class="row">
                        	<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h3 class="panel-title">User List</h3>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                            	<button type="button" name="add" id="add_button" data-toggle="modal" data-target="#userModal" class="btn btn-success btn-sm"><i class="fa fa-user-plus"></i>&nbsp;Add User</button>
                        	</div>
                        </div></center></h5><hr>
						<div class="row">
							<div class="col-12">
								<table id="user_data" class="table table-bordered table-striped">
		               				<thead>
										<tr>
											<th>No.</th>
											<th>Profile</th>
											<th>Gender</th>
											<th>Username</th>
											<th>Status</th>
											<th>Edit</th>
											<th>Delete</th>
											<th>Activate/Deactivate</th>
										</tr>
									</thead>
		               			</table>
							</div>
						</div>
					</div>
				</div>
	        </div>
	    </div>
        <div id="userModal" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Add User</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="POST" enctype="multipart/form-data" id="user_form">
	        				<div class="row">
	        					<div class="form-group col-md-12 col-sm-6 col-12">
									<label>First Name</label>
									<input type="text" name="fname" id="fname" class="form-control" placeholder="Enter First Name" required>
								</div>
								<div class="form-group col-md-12 col-sm-6 col-12">
									<label>Middle Name</label>
									<input type="text" name="mname" id="mname" class="form-control" placeholder="Enter Middle Name" required>
								</div>
								<div class="form-group col-md-12 col-sm-6 col-12">
									<label>Last Name</label>
									<input type="text" name="lname" id="lname" class="form-control" placeholder="Enter Last Name" required>
								</div>
								<div class="form-group  col-md-12 col-sm-6 col-12">
									<label>Address</label>
									<input type="text" name="address" id="address" class="form-control" placeholder="Enter your Address" required>
								</div>
								<div class="form-group col-md-12 col-sm-6 col-12">
									<label>Gender</label>
									<select name="gender" id="gender" class="form-control" required>
										<option value="">Select your Gender:</option>
										<option value="Male">Male</option>
										<option value="Female">Female</option>
									</select>
								</div>
								<div class="form-group col-md-12 col-sm-6 col-12">
									<label>Choose your profile picture</label>
									<input type="file" id="photo" name="photo" class="form-control">
								</div>
		        				<div class="form-group  col-md-12 col-sm-6 col-12">
									<label>User Name</label>
									<input type="text" name="user_name" id="username" class="form-control" placeholder="Enter your Username" required>
								</div>
								<div class="form-group col-md-12 col-sm-6 col-12">
									<label>Email</label>
									<input type="text" name="user_email" id="email" class="form-control" placeholder="Enter your Email" required>
								</div>
								<div class="form-group col-md-12 col-sm-6 col-12">
									<label>Password</label>
									<input type="password" name="user_password" id="password" class="form-control" placeholder="Enter your Password">
								</div>
	        				</div>
		        			<div class="modal-footer">
		        				<input type="hidden" name="user_id" id="user_id" />
		        				<input type="hidden" name="btn_action" id="btn_action" />
		        				<input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
		        				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		        			</div>
		        		</form>
					</div>
				</div>
			</div>
		</div>
<script>
$(document).ready(function(){

	$('#add_button').click(function(){
		$('#user_form')[0].reset();
		$('.modal-title').html("Add User");
		$('#action').val("Add");
		$('#btn_action').val("Add");
	});

	// view record
	var userdataTable = $('#user_data').DataTable({
		"processing": true,
		"serverSide": true,
		"order": [],
		"ajax":{
			url:"user_fetch.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"target":[4,5],
				"orderable":false
			}
		],
		"pageLength": 10
	});
	
	// insert data
	$(document).on('submit', '#user_form', function(event){
		event.preventDefault();
		$('#action').attr('disabled','disabled');
		var form_data = $(this).serialize();
		$.ajax({
			url:"user_action.php",
			method:"POST",
			data:form_data,
			success:function(data)
			{
				$('#user_form')[0].reset();
				$('#userModal').modal('hide');
				$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'+data+'!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				$('#action').attr('disabled', false);
				userdataTable.ajax.reload();
			}
		})
	});
	// update data
	$(document).on('click', '.update', function(){
		var user_id = $(this).attr("id");
		var btn_action = 'fetch_single';
		$.ajax({
			url:"user_action.php",
			method:"POST",
			data:{user_id:user_id, btn_action:btn_action},
			dataType:"json",
			success:function(data)
			{
				$('#userModal').modal('show');
				$('#fname').val(data.fname);
				$('#mname').val(data.mname);
				$('#lname').val(data.lname);
				$('#address').val(data.address);
				$('#gender').val(data.gender);
				$('#username').val(data.user_name);
				$('#email').val(data.user_email);
				$('.modal-title').html("User Update");
				$('#user_id').val(user_id);
				$('#action').val('Edit');
				$('#btn_action').val('Edit');
				$('#user_password').attr('required', false);
			}
		})
	});

	// delete data
	$(document).on('click', '.delete', function(){
		var user_id = $(this).attr("id");
		var btn_action = "delete";
		if(confirm("Are you sure you want to Delete this User?"))
		{
			$.ajax({
				url:"user_action.php",
				method:"POST",
				data:{user_id:user_id, btn_action:btn_action},
				success:function(data)
				{
					$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'+data+'!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					userdataTable.ajax.reload();
				}
			})
		}
		else
		{
			return false;
		}
	});

	// activate/deactivate user
	$(document).on('click', '.ac_de', function(){
		var user_id = $(this).attr("id");
		var status = $(this).data('status');
		var btn_action = "ac_de";
		
		if(confirm("Are you sure you want to change status?")) {
			$.ajax({
				url:"user_action.php",
				method:"POST",
				data:{user_id:user_id, status:status, btn_action:btn_action},
				success:function(data)
				{
					$('#alert_action').fadeIn().html('<div class="alert alert-info alert-dismissible fade show" role="alert"><strong>'+data+'!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					userdataTable.ajax.reload();
				}
			})
		}
		else
		{
			return false;
		}
	});

});
</script>

<?php
include('footer.php');
?>
