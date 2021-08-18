<?php
include('database_connection.php');
include('function.php');

if(!isset($_SESSION['type']))
{
	header('location:login.php');
}

include('header.php');
?>

<br>
<body>
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Add Order</h5><hr>
			<span id="error"></span>
			<form name="add" method="POST" id="insert_form" autocomplete="off">
				<div class="row">
					<div class="col-lg-4">
						<label>Customer Name</label>
						<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Enter your Full name" autocomplete="off" />
					</div>
					<div class="col-lg-4">
						<label>Address</label>
						<input type="text" class="form-control" id="customer_address" name="customer_address" placeholder="Enter your Address" autocomplete="off" >
					</div>
					<div class="col-lg-4 mb-3">
						<label>Phone Number</label>
						<input type="text" class="form-control" id="customer_phone" name="customer_phone" placeholder="Enter Phone Number" autocomplete="off" >
					</div><br>
			    </div>
			    <br>
                <table class="table autocomplete_table" id="autocomplete_table">
                    <tr>
                        <th style="width:20%">Product Name</th>
                        <th style="width:25%">Product Name</th>
                        <th style="width:20%">Price</th>
                        <th style="width:20%">Quantity</th>
                        <th style="width:15%"><button type="button" name="add" id="addNew" class="btn btn-success btn-sm add">+</button></th>
                    </tr>
                    <tbody>
                        <tr id="row_1">
                            <td>
                                <input type="text" data-type="code" class="form-control form-control-sm autocomplete_txt" name="code[]" id="code_1" placeholder="QRcode">
                            </td>
                            <td>
                                <input type="text" data-type="name" id="name_1" class="form-control form-control-sm autocomplete_txt" name="name[]" placeholder="Product Name">
                            </td>
                            <td>
                                <input type="text" data-type="price" class="form-control form-control-sm autocomplete_txt" name="price[]" id="price_1" readonly>
                            </td>
                            <td>
                                <input type="number" data-type="quantity" class="form-control form-control-sm autocomplete_txt" name="quantity[]" id="quantity_1" placeholder="Qty">
                            </td>
                            <td>
                                <button type="button" name="remove" id="delete_1" class="btn btn-danger btn-sm delete_row">-</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
			    <!-- <table class="table" id="item_table">
		    		<tr>
		    			<th style="width:20%">Product Name</th>
		    			<th style="width:25%">Product Name</th>
		    			<th style="width:20%">Price</th>
		    			<th style="width:20%">Quantity</th>
		    			<th style="width:15%"><button type="button" name="add" class="btn btn-success btn-sm add">+</button></th>
		    		</tr>
		    		<tr>
		    			<td>
		    				<input type="text" class="form-control form-control-sm" id="code" placeholder="QRcode">
		    			</td>
		    			<td>
	                        <input type="text" id="name" class="form-control form-control-sm" placeholder="Product Name">
		    			</td>
		    			<td>
		    				<input type="text" class="form-control form-control-sm" id="price" readonly>
		    			</td>
		    			<td>
		    				<input type="number" class="form-control form-control-sm" id="quantity" placeholder="Qty">
		    			</td>
		    			<td>
		    				<button type="button" name="remove" id="'+count+'" class="btn btn-danger btn-sm remove">-</button>
		    			</td>
		    		</tr>
			    </table> -->
			    <div class="text-right">
	              <a href="order" class="btn btn-warning" style="color: white;">Cancel</a>
	              <!-- <button type="submit" name="make_print" class="btn btn-primary">Submit</button> -->
	              <input type="submit" name="submit" class="btn btn-primary" value="Insert">
	            </div>
			</form>
		</div>
	</div>
</body>
<script src="js/app.js"></script>
<!-- <script type="text/javascript">
	$( function() {
  
        $("#code").autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "Add_order_ajax.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response(data);
                    }
                });
            },
            select: function (event, ui) {
                $('#code').val(ui.item.label); // display the selected text
                $('#name').val(ui.item.value); // save selected id to input
                $('#price').val(ui.item.value1); // save selected id to input
                return false;
            },
            focus: function(event, ui){
                $("#code").val( ui.item.label);
                $("name").val( ui.item.value);
                $("price").val( ui.item.value1);
                return false;
            },
        });
    });
	$(document).ready(function(){
 
 function add_product_row(count = ''){
  var html = '';
  html += '<tr>';
  html += '<td><input type="text" class="form-control form-control-sm" id="code" placeholder="QRcode" name="code[]"></td>';
  
  html += '<td><input type="text" id="name" class="form-control form-control-sm" placeholder="Product Name" name="name[]"></td>';

  html += '<td><input type="text" class="form-control form-control-sm" id="price" readonly name="price[]"></td>';

  html += '<td><input type="number" class="form-control form-control-sm" id="quantity" placeholder="Qty" name="quantity[]"></td>';

  html += '<td><button type="button" name="remove" id="'+count+'" class="btn btn-danger btn-sm remove">-</button></td></tr>';

  $('#item_table').append(html);
 };

 var count = 0;

$(document).on('click', '.add', function(){
	count = count + 1;
	add_product_row(count);
});


 $(document).on('click', '.remove', function(){
  $(this).closest('tr').remove();
 });
 
 $('#insert_form').on('submit', function(event){
  event.preventDefault();
  var error = '';
  $('#code').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
    error += "<p>Enter Product QRcode at "+count+" Row</p>";
    return false;
   }
   count = count + 1;
  });
  
  $('#name').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
    error += "<p>Enter Product Name at "+count+" Row</p>";
    return false;
   }
   count = count + 1;
  });
  
  $('#price').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
    error += "<p>Enter Product Price at "+count+" Row</p>";
    return false;
   }
   count = count + 1;
  });

  $('#quantity').each(function(){
   var count = 1;
   if($(this).val() == '')
   {
    error += "<p>Enter Quantity at "+count+" Row</p>";
    return false;
   }
   count = count + 1;
  });

  var form_data = $(this).serialize();
  if(error == '')
  {
   $.ajax({
    url:"Add_order_ajax.php",
    method:"POST",
    data:form_data,
    success:function(data)
    {
     if(data == 'ok')
     {
      $('#item_table').find("tr:gt(0)").remove();
      $('#error').html('<div class="alert alert-success">Item Details Saved</div>');
     }
    }
   });
  }
  else
  {
   $('#error').html('<div class="alert alert-danger">'+error+'</div>');
  }
 });
 
});
</script> -->
