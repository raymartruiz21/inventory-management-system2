<?php
include('database_connection.php');
include('header.php');
?>
<body>
<br><br>
    <div class="container">
        <div class="card-title">
            <h3>Create Order</h3>
        </div><hr>
        <!-- <form method="POST"> -->
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
                </div><br><hr>
            </div>
                <div class="table-responsive">
                    <table id="autocomplete_table" class="table table-hover autocomplete_table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">Clear</span></th>
                                <th scope="col">QRcode</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Rate</th>
                                <th scope="col">Quantity</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr id="row_1">
                                <td>
                                    <button scope="row" id="delete_1" class="btn btn-sm btn-danger delete_row">-</button>
                                </td>
                                <td>
                                    <input type="text" data-type="countryname" name="countryname[]" id="countryname_1" class="form-control autocomplete_txt" autocomplete="off">
                                </td>
                                <td>
                                    <input type="text" data-type="countryno" name="countryno[]" id="countryno_1" class="form-control autocomplete_txt" autocomplete="off" readonly>
                                </td>
                                <td>
                                    <input type="text" data-type="phone_code" name="phone_code[]" id="phone_code_1" class="form-control autocomplete_txt" autocomplete="off" readonly>
                                </td>
                                <td>
                                    <input type="number" data-type="country_code" name="country_code[]" id="country_code_1" class="form-control autocomplete_txt" autocomplete="off">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>  
                <div class="btn-container">
                    <button class="btn btn-success" id="addNew">Add New</button>
                </div> 
            
            <form method="POST" id="insert_form">
                <div class="text-right">
                  <a href="order" class="btn btn-warning" style="color: white;">Cancel</a>
                  <input type="submit" name="submit" class="btn btn-primary" value="Insert">
                </div>
            </form>
        <!-- </form> -->
    </div>

    <script src="app.js"></script>
    
</body>
</html>