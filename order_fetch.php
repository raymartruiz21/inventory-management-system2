<?php

//order_fetch.php

include('database_connection.php');

include('function.php');

$query = '';

$output = array();

$query .= "
	SELECT * FROM order_tbl WHERE 
";

if($_SESSION['type'] == 'user') {
	$query .= 'user_id = "'.$_SESSION["user_id"].'" AND ';
}

if(isset($_POST["search"]["value"])) {
	$query .= '(order_id LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR order_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR order_total LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR order_status LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR order_date LIKE "%'.$_POST["search"]["value"].'%") ';
}

if(isset($_POST["order"])) {
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';

} else {
	$query .= 'ORDER BY order_id ASC ';
}

if($_POST["length"] != -1) {
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i = 0;

foreach($result as $row) {
	$payment_status = '';

	if($row['payment_status'] == 'paid') {
		$payment_status = '<span class="text-success"><b>Paid</b></span>';

	} else {
		$payment_status = '<span class="text-warning"><b>Unpaid</b></span>';
	}

	$status = '';
	
	if($row['order_status'] == 'active') {
		$status = '<span class="text-success"><b>Active</b></span>';
	} else {
		$status = '<span class="text-danger"><b>Inactive</b></span>';
	}
	
	$sub_array = array();
	$i++;
	$sub_array[] = $i;
	$sub_array[] = $row['order_name'];
	$sub_array[] = $row['order_total'];
	$sub_array[] = $payment_status;
	// $sub_array[] = $status;
	$sub_array[] = $row['order_date'];

	if($_SESSION['type'] == 'master') {
		$sub_array[] = get_user_name($connect, $row['user_id']);
	}

	$sub_array[] = '<a href="view_order.php?pdf=1&order_id='.$row["order_id"].'" class="btn btn-info btn-sm"><i class="fa fa-file-pdf"></i></a>';

	$sub_array[] = '<button type="button" name="delete" id="'.$row["order_id"].'" class="btn btn-danger btn-sm delete" data-status="'.$row["order_status"].'"><i class="fa fa-trash"></i></button>';
	$sub_array[] = '<button type="button" name="ac_de" id="'.$row["order_id"].'" class="btn btn-warning btn-sm ac_de" data-status="'.$row["payment_status"].'"><i class="fa fa-ban"></i></button>';
	$data[] = $sub_array;
}

function get_total_all_records($connect) {
	$statement = $connect->prepare("SELECT * FROM order_tbl");
	$statement->execute();
	return $statement->rowCount();
}

$output = array(
	"draw"    			=> 	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"    			=> 	$data
);	

echo json_encode($output);

?>