<?php
include('database_connection.php');
include('function.php');

if(!isset($_SESSION["type"]))
{
    header('location:login.php');
}
if($_SESSION["type"] != 'master')
{
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
                                <h3>Product List</h3>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align="right">
                                <button type="button" name="add" id="add_button" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>&nbsp;Add Product</button>
                            </div>
                        </div></center></h5><hr>
                        <div class="row">
                            <div class="col-12">
                                <table id="product_data" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Category Name</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th>Update</th>
                                            <th>Delete</th>
                                            <th>View</th>
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
        <div id="productModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="product_form">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-md-6 col-sm-12 col-12">
                                    <label>Select Category</label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">Select Category</option>
                                        <?php echo fill_category_list($connect);?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6 col-sm-12 col-12">
                                    <label>Product Name</label>
                                    <input type="text" name="product_name" id="product_name" class="form-control" placeholder="Enter Product Name" required />
                                </div>
                                <div class="form-group col-md-6 col-sm-12 col-12">
                                    <label>Product Quantity</label>
                                    <input type="number" name="product_quantity" id="product_quantity" class="form-control" placeholder="Enter Product Quantity" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                                </div>
                                <div class="form-group col-md-6 col-sm-12 col-12">
                                    <label>Product Price</label>
                                    <input type="number" name="product_price" id="product_price" class="form-control" placeholder="Enter Product Price" required pattern="[+-]?([0-9]*[.])?[0-9]+" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="product_id" id="product_id" />
                            <input type="hidden" name="btn_action" id="btn_action" />
                            <input type="submit" name="action" id="action" class="btn btn-info" value="Add" />
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="productdetailsModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="product_form">
                        <div class="modal-body">
                            <div id="product_details"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<script>
$(document).ready(function(){

    $('#add_button').click(function(){
        $('#productModal').modal('show');
        $('#product_form')[0].reset();
        $('.modal-title').html("Add Product");
        $('#action').val("Add");
        $('#btn_action').val("Add");
    });

    // view record
    var productdataTable = $('#product_data').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax":{
            url:"product_fetch.php",
            type:"POST"
        },
        "columnDefs":[
            {
                "target":[4,5,6,7,8],
                "orderable":false
            }
        ],
        "pageLength": 10
    });
    
    // insert data
    $(document).on('submit', '#product_form', function(event){
        event.preventDefault();
        $('#action').attr('disabled', 'disabled');
        var form_data = $(this).serialize();
        $.ajax({
            url:"product_action.php",
            method:"POST",
            data:form_data,
            success:function(data)
            {
                $('#product_form')[0].reset();
                $('#productModal').modal('hide');
                $('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'+data+'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                $('#action').attr('disabled', false);
                productdataTable.ajax.reload();
            }
        })
    });

    $(document).on('click', '.view', function(){
        var product_id = $(this).attr("id");
        var btn_action = 'product_details';
        $.ajax({
            url:"product_action.php",
            method:"POST",
            data:{product_id:product_id, btn_action:btn_action},
            success:function(data){
                $('#productdetailsModal').modal('show');
                $('#product_details').html(data);
            }
        })
    });

    // update data
    $(document).on('click', '.update', function(){
        var product_id = $(this).attr("id");
        var btn_action = 'fetch_single';
        $.ajax({
            url:"product_action.php",
            method:"POST",
            data:{product_id:product_id, btn_action:btn_action},
            dataType:"json",
            success:function(data)
            {
                $('#productModal').modal('show');
                $('#category_id').val(data.category_id);
                $('#product_name').val(data.product_name);
                $('#product_quantity').val(data.product_quantity);
                $('#product_price').val(data.product_price);
                $('.modal-title').html("Edit Product");
                $('#product_id').val(product_id);
                $('#action').val("Edit");
                $('#btn_action').val("Edit");
            }
        })
    });

    // delete data
    $(document).on('click', '.delete', function(){
        var product_id = $(this).attr("id");
        var btn_action = 'delete';
        if(confirm("Are you sure you want to Delete this product?"))
        {
            $.ajax({
                url:"product_action.php",
                method:"POST",
                data:{product_id:product_id, btn_action:btn_action},
                success:function(data){
                    $('#alert_action').fadeIn().html('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'+data+'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    productdataTable.ajax.reload();
                }
            });
        }
        else
        {
            return false;
        }
    });

    // activate/deactivate
    $(document).on('click', '.ac_de', function(){
        var product_id = $(this).attr("id");
        var status = $(this).data('status');
        var btn_action = "ac_de";
        
        if(confirm("Are you sure you want to change status?")) {
            $.ajax({
                url:"product_action.php",
                method:"POST",
                data:{product_id:product_id, status:status, btn_action:btn_action},
                success:function(data)
                {
                    $('#alert_action').fadeIn().html('<div class="alert alert-info alert-dismissible fade show" role="alert"><strong>'+data+'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    productdataTable.ajax.reload();
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
