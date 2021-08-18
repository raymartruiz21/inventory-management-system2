<?php
//order.php

include('database_connection.php');

include('function.php');

if(!isset($_SESSION['type']))
{
	header('location:login.php');
}

include('header.php');

// $name = $code = $product_name = '';

// 	if (isset($_POST["submit"])) {
		
// 		$query = "SELECT * FROM product WHERE product_code=':QRcode'";
// 		$statement = $connect->prepare($query);
// 		$statement->execute(
// 			array(
// 				':QRcode'					=>	$_POST["QRcode"]
// 			)
// 		);
// 		$name = $_POST["name"];
// 		$result = $statement->fetchAll();
// 		foreach ($result as $row) {
// 			echo $product_name = $row["product_name"];
// 			echo $code = $row["product_code"];
// 		}
// 		if ($_POST["QRcode"]) {
// 					if ($_POST["QRcode"] == $code) {
// 						$product_name = $name;
// 					}
// 				}
		
// 	}
?>
	<link rel="stylesheet" href="css/datepicker.min.css">
	<script src="js/datepicker.min.js"></script>
	<br><span id="alert_action"></span>
	<div class="row">
		<div class="col-lg-12">
			<div class="card" style="border-radius: 15px;">
				<div class="card-body">
					<h5 class="card-title"><center><div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                        	<h3 class="panel-title">Order List</h3>
                        </div>
                    	<div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                        	<!-- <a href="insert" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add Order</a> -->
                        	<button type="button" name="add" id="add_button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp;&nbsp;Add</button>
                    	</div>
                    </div></center></h5><hr>
					<div class="row">
						<div class="col-12">
							<table id="order_data" class="table table-bordered table-striped">
		                		<thead>
									<tr>
										<th>No</th>
										<th>Customer Name</th>
										<th>Total Amount</th>
										<th>Payment Status</th>
										<!-- <th>Order Status</th> -->
										<th>Order Date</th>
										<?php
										if($_SESSION['type'] == 'master')
										{
											echo '<th>Created By</th>';
										}
										?>
										<th>Invoice</th>
										<th>Delete</th>
										<th>Change status</th>
										<th></th>
									</tr>
								</thead>
		                	</table>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
    <!-- start orderModal -->
    <div id="orderModal" class="modal fade">

    	<div class="modal-dialog modal-lg">
    		<form method="post" id="order_form">
    			<div class="modal-content">
    				<div class="modal-header">
						<h5 class="modal-title">Create Order</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
    				<div class="modal-body">
    					<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Enter Receiver Name</label>
									<input type="text" name="order_name" id="order_name" class="form-control" required />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Date</label>
									<input type="date" name="order_date" id="order_date" class="form-control" required />
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Enter Receiver Address</label>
							<input type="text" name="order_address" id="order_address" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Enter Product Details</label>
							<hr />
							<span id="span_product_details"></span>
							<hr />
						</div>
						<div class="form-group">
							<label>Select Payment Status</label>
							<select name="payment_status" id="payment_status" class="form-control">
								<option value="paid">Paid</option>
								<option value="unpaid">Unpaid</option>
							</select>
						</div>
    				</div>
    				<div class="modal-footer">
    					<input type="hidden" name="order_id" id="order_id" />
    					<input type="hidden" name="btn_action" id="btn_action" />
    					<input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
    				</div>
    			</div>
    		</form>
    	</div>

    </div>
<script type="text/javascript">
    $(document).ready(function(){

    	var orderdataTable = $('#order_data').DataTable({
			"processing":true,
			"serverSide":true,
			"order":[],
			"ajax":{
				url:"order_fetch.php",
				type:"POST"
			},
			<?php
			if($_SESSION["type"] == 'master'){
			?>
			"columnDefs":[
				{
					"targets":[4, 5, 6, 7, 8, 9],
					"orderable":false,
				},
			],
			<?php
			}else{
			?>
			"columnDefs":[
				{
					"targets":[4, 5, 6, 7, 8],
					"orderable":false,
				},
			],
			<?php
			}
			?>
			"pageLength": 10
		});
// insert modal
		$('#add_button').click(function(){
			$('#orderModal').modal('show');
			$('#order_form')[0].reset();
			$('.modal-title').html("Create Order");
			$('#action').val('Add');
			$('#btn_action').val('Add');
			$('#span_product_details').html('');
			add_product_row();
		});
		function add_product_row(count = '')
		{
			var html = '';
			html += '<span id="row'+count+'"><div class="row">';
			html += '<div class="col-md-4">';
			html += '<input type="text" class="form-control" name="product_id[]" placeholder="Enter QRcode" id="product_id'+count+'">';
			html += '</div>';
			// html += '<div class="col-md-3">';
			// html += '<input type="text" class="form-control" name="name" placeholder="Product Name" disabled id="name">';
			// html += '<select name="product_id[]" id="product_id'+count+'" class="form-control selectpicker" data-live-search="true" required>';
			// html += '<?php echo fill_product_list($connect); ?>';
			// html += '</select><input type="hidden" name="hidden_product_id[]" id="hidden_product_id'+count+'" />';
			// html += '</div>';
			// html += '<div class="col-md-2">';
			// html += '<input type="text" class="form-control" name="price" placeholder="Price" disabled id="price">';
			// html += '</div>';
			html += '<div class="col-md-4">';
			html += '<input type="text" name="quantity[]" class="form-control" required placeholder="Enter Quantity"/>';
			html += '</div>';
			html += '<div class="col-md-4">';
			if(count == '')
			{
				html += '<button type="button" name="add_more" id="add_more" class="btn btn-success btn-xs">+</button>';
			}
			else
			{
				html += '<button type="button" name="remove" id="'+count+'" class="btn btn-danger btn-xs remove">-</button>';
			}
			html += '</div>';
			html += '</div></div><br /></span>';
			$('#span_product_details').append(html);
		}

		var count = 0;

		$(document).on('click', '#add_more', function(){
			count = count + 1;
			add_product_row(count);
		});
		$(document).on('click', '.remove', function(){
			var row_no = $(this).attr("id");
			$('#row'+row_no).remove();
		});

		$(document).on('submit', '#order_form', function(event){
			event.preventDefault();
			$('#action').attr('disabled', 'disabled');
			var form_data = $(this).serialize();
			$.ajax({
				url:"order_action.php",
				method:"POST",
				data:form_data,
				success:function(data){
					$('#order_form')[0].reset();
					$('#orderModal').modal('hide');
					$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'+data+'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					$('#action').attr('disabled', false);
					orderdataTable.ajax.reload();
				}
			});
		});
		// end of insert modal

		// update
		// $(document).on('click', '.update', function(){
		// 	var order_id = $(this).attr("id");
		// 	var btn_action = 'fetch_single';
		// 	$.ajax({
		// 		url:"order_action.php",
		// 		method:"POST",
		// 		data:{order_id:order_id, btn_action:btn_action},
		// 		dataType:"json",
		// 		success:function(data)
		// 		{
		// 			$('#orderModal').modal('show');
		// 			$('#order_name').val(data.order_name);
		// 			$('#order_date').val(data.order_date);
		// 			$('#order_address').val(data.order_address);
		// 			$('#payment_status').val(data.payment_status);
		// 			$('#order_id').val(order_id);
		// 			$('.modal-title').html("Update Order");
		// 			$('#action').val('Edit');
		// 			$('#btn_action').val('Edit');
		// 		}
		// 	})
		// });
// delete
		$(document).on('click', '.delete', function(){
			var order_id = $(this).attr("id");
			var status = $(this).data("status");
			var btn_action = "delete";
			if(confirm("Are you sure you want to delete your Order?"))
			{
				$.ajax({
					url:"order_action.php",
					method:"POST",
					data:{order_id:order_id, status:status, btn_action:btn_action},
					success:function(data)
					{
						$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'+data+'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
						orderdataTable.ajax.reload();
					}
				})
			}
			else
			{
				return false;
			}
		});
// activate-deactivate
		$(document).on('click', '.ac_de', function(){
			var order_id = $(this).attr("id");
			var status = $(this).data("status");
			var btn_action = "ac_de";
			if(confirm("Are you sure you want to change your Order Status?"))
			{
				$.ajax({
					url:"order_action.php",
					method:"POST",
					data:{order_id:order_id, status:status, btn_action:btn_action},
					success:function(data)
					{
						$('#alert_action').fadeIn().html('<div class="alert alert-info alert-dismissible fade show" role="alert"><strong>'+data+'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
						orderdataTable.ajax.reload();
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
<script type="text/javascript" src="js/app.js"></script>
<?php include('footer.php'); ?>