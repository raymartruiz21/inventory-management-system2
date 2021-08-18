<?php
//index.php
include('database_connection.php');
include('function.php');

if(!isset($_SESSION["type"]))
{
	header("location:login.php");
}

include('header.php');

?>
	<br><div class="row">
	<?php
	if($_SESSION['type'] == 'master')
	{
	?>
	<!-- <div class="col-md-3 mb-4">
		<div class="card" style="border-radius: 15px;">
			<div class="card-body">
				<h5 class="card-title"><center><i class="fas fa-user-friends text-info" style="font-size: 40px;"></i>&nbsp;Total User</center></h5><hr>
				<h6 class="card-subtitle mb-2 text-muted">Current User</h6>
				<h4 class="card-text"><?php echo count_total_user($connect); ?></h4>
			</div>
		</div>
	</div> -->
	<div class="col-md-4 mb-4">
		<div class="card" style="border-radius: 15px;">
			<div class="card-body">
				<h5 class="card-title"><center><i class="fas fa-folder-plus" style="font-size: 40px; color: #cc33ff;"></i>&nbsp;Total Category</center></h5><hr>
				<h6 class="card-subtitle mb-2 text-muted">Current Category</h6>
				<h4 class="card-text"><center><?php echo count_total_category($connect); ?></center></h4>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card" style="border-radius: 15px;">
			<div class="card-body">
				<h5 class="card-title"><center><i class="fas fa-dolly-flatbed" style="font-size: 40px; color: #ff6600;"></i>&nbsp;Total Order</center></h5><hr>
				<h6 class="card-subtitle mb-2 text-muted">Current Order</h6>
				<h4 class="card-text"><?php echo count_total_order($connect); ?></h4>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="card" style="border-radius: 15px;">
			<div class="card-body">
				<h6 class="card-title"><center><i class="fas fa-archive text-danger" style="font-size: 40px;"></i>&nbsp;Total Product in Stock</center></h6><hr>
				<h6 class="card-subtitle mb-2 text-muted">Current Product</h6>
				<h4 class="card-text"><?php echo count_total_product($connect); ?></h4>
			</div>
		</div>
	</div>
	<?php
	}
	?>
		<div class="col-md-4 mb-4">
			<div class="card" style="border-radius: 15px;">
				<div class="card-body">
					<h5 class="card-title"><center><i class="fas fa-cash-register" style="font-size: 40px; color: #996633;"></i>&nbsp;Total Sales</center></h5><hr>
					<h6 class="card-subtitle mb-2 text-muted">Current Sales</h6>
					<h4 class="card-text">₱ <?php echo count_total_order_value($connect); ?></h4>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card" style="border-radius: 15px;">
				<div class="card-body">
					<h5 class="card-title"><center><i class="fas fa-money-bill-wave" style="font-size: 40px; color: #33cc33;"></i>&nbsp;Total Paid Order Value</center></h5><hr>
					<h6 class="card-subtitle mb-2 text-muted">Current Paid Order</h6>
					<h4 class="card-text">₱ <?php echo count_total_cash_order_value($connect); ?></h4>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card" style="border-radius: 15px;">
				<div class="card-body">
					<h5 class="card-title"><center><i class="fas fa-address-card text-info" style="font-size: 40px;"></i>&nbsp;Total Unpaid Order Value</center></h5><hr>
					<h6 class="card-subtitle mb-2 text-muted">Current Unpaid Order</h6>
					<h4 class="card-text">₱ <?php echo count_total_credit_order_value($connect); ?></h4>
				</div>
			</div>
		</div>
		<hr />
		<?php
		if($_SESSION['type'] == 'master')
		{
		?>
		<!-- <div class="col-12">
			<div class="card" style="border-radius: 15px;">
				<div class="card-body">
					<h5 class="card-title"><center>Top Customer Buyer</center></h5><hr>
					<?php echo get_user_wise_total_order($connect); ?>
				</div>
			</div>
		</div> -->
		<?php
		}
		?>
	</div>

<?php
include("footer.php");
?>