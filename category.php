<?php
include('database_connection.php');

if(!isset($_SESSION['type'])) {
	header('location:login.php');
}

if($_SESSION['type'] != 'master') {
	header("location:index.php");
}

include('header.php');

?>

	<br><span id="alert_action"></span>
	<div class="row">
		<div class="col-lg-12">
			<div class="card" style="border-radius: 15px;">
				<div class="card-body">
					<h5 class="card-title"><center><div class="row">
                    	<div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                        	<h3 class="panel-title">Category List</h3>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                        	<button type="button" name="add" id="add_button" data-toggle="modal" data-target="#categoryModal" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp;Add Category</button>   	
                    	</div>
                    </div></center></h5><hr>
					<div class="row">
						<div class="col-12">
							<table id="category_data" class="table table-bordered table-striped">
                    			<thead><tr>
									<th>No.</th>
									<th>Category Name</th>
									<th>Status</th>
									<th>Edit</th>
									<th>Delete</th>
									<th>activate/Deactivate</th>
								</tr></thead>
                    		</table>
						</div>
					</div>
				</div>
			</div>
        </div>
    </div>
    <div id="categoryModal" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add Category</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="post" id="category_form">
					<div class="modal-body">
    					<label>Category Name</label>
						<input type="text" name="category_name" id="category_name" class="form-control" placeholder="Enter Category Name" required />
					</div>
					<div class="modal-footer">
						<input type="hidden" name="category_id" id="category_id"/>
						<input type="hidden" name="btn_action" id="btn_action"/>
						<input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<script>
$(document).ready(function(){

	$('#add_button').click(function(){
		$('#category_form')[0].reset();
		$('.modal-title').html("Add Category");
		$('#action').val('Add');
		$('#btn_action').val('Add');
	});

	$(document).on('submit','#category_form', function(event){
		event.preventDefault();
		$('#action').attr('disabled','disabled');
		var form_data = $(this).serialize();
		$.ajax({
			url:"category_action.php",
			method:"POST",
			data:form_data,
			success:function(data)
			{
				$('#category_form')[0].reset();
				$('#categoryModal').modal('hide');
				$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'+data+'!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				$('#action').attr('disabled', false);
				categorydataTable.ajax.reload();
			}
		})
	});

	$(document).on('click', '.update', function(){
		var category_id = $(this).attr("id");
		var btn_action = 'fetch_single';
		$.ajax({
			url:"category_action.php",
			method:"POST",
			data:{category_id:category_id, btn_action:btn_action},
			dataType:"json",
			success:function(data)
			{
				$('#categoryModal').modal('show');
				$('#category_name').val(data.category_name);
				$('.modal-title').html("Edit Category");
				$('#category_id').val(category_id);
				$('#action').val('Edit');
				$('#btn_action').val("Edit");
			}
		})
	});

	var categorydataTable = $('#category_data').DataTable({
		"processing":true,
		"serverSide":true,
		"order":[],
		"ajax":{
			url:"category_fetch.php",
			type:"POST"
		},
		"columnDefs":[
			{
				"targets":[3, 4],
				"orderable":false,
			},
		],
		"pageLength": 10
	});

	$(document).on('click', '.delete', function(){
		var category_id = $(this).attr('id');
		var btn_action = 'delete';
		if(confirm("Are you sure you want to Delete this category name?"))
		{
			$.ajax({
				url:"category_action.php",
				method:"POST",
				data:{category_id:category_id, btn_action:btn_action},
				success:function(data)
				{
					$('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'+data+'!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					categorydataTable.ajax.reload();
				}
			})
		}
		else
		{
			return false;
		}
	});

	// activate
	$(document).on('click', '.ac_de', function(){
		var category_id = $(this).attr('id');
		var status = $(this).data("status");
		var btn_action = 'ac_de';
		if(confirm("Are you sure you want to change status?"))
		{
			$.ajax({
				url:"category_action.php",
				method:"POST",
				data:{category_id:category_id, status:status, btn_action:btn_action},
				success:function(data)
				{
					$('#alert_action').fadeIn().html('<div class="alert alert-info alert-dismissible fade show" role="alert"><strong>'+data+'!</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
					categorydataTable.ajax.reload();
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


				