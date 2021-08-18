<?php
// include('function.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Sweet Treat Inventory Management System</title>
		<script src="js/jquery-3.4.1.min.js"></script>
		<link rel="stylesheet" href="bootstrap4/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script src="bootstrap4/js/bootstrap.bundle.min.js"></script>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="js/dataTables.bootstrap.min.js"></script>		
		<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="js/all.js"></script>
	</head>
	<body>
		<nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light">
				<a class="navbar-brand" href="#"><img src="img/st2.png"></a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
						<?php
						if($_SESSION['type'] == 'master')
						{
						?>
						<!-- <li class="nav-item"><a class="nav-link" href="user.php">User</a></li> -->
						<li class="nav-item"><a class="nav-link" href="category.php">Category</a></li>
						<li class="nav-item"><a class="nav-link" href="product.php">Product</a></li>
						<?php
						}
						?>
						<li  class="nav-item"><a class="nav-link" href="order.php">Order</a></li>
						<!-- <li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Manage Order
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="Add_order.php">Add order</a>
								<a class="dropdown-item" href="order.php">View Order List</a>
							</div>
						</li> -->
					</ul>
					<ul class="navbar-nav ml-auto" style="margin-right: 80px;">
						<li class="nav-item">
							<?php
								$query = "SELECT * FROM user_details WHERE user_id = '".$_SESSION['user_id']."'";
								$statement = $connect->prepare($query);
								$statement->execute();
								$result = $statement->fetchAll();
								$photo = '';
								foreach($result as $row) {
									if ($_SESSION['type'] == 'user') {
										$user_name = $row['user_name'];
										$photo = '<center><img class="rounded-circle" src="'.$row['photo'].'" alt="profile" style="height: 40px; width: 40px; "></center>';
									}else{
										$user_name = $row['user_name'];
										$photo = '<center><img class="rounded-circle" src="img/801997_user_512x512.png" alt="profile" style="height: 40px; width: 40px; "></center>';
									}
									
								}
								echo $photo;
							?>
							
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="nav-item"><?php echo ucfirst($user_name);?></i></a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="profile.php"><i class="fa fa-users-cog"></i>&nbsp;&nbsp;Profile</a>
								<a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out-alt"></i>&nbsp;&nbsp;Logout</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>
		<div class="container">
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Document</title>
		</head>
		<body>
			
		</body>
		</html>	