<?php
include('database_connection.php');
include('function.php');

$query = '';

$output = array();

$query .= "SELECT * FROM product INNER JOIN category ON category.category_id = product.category_id ";

// search 
if(isset($_POST["search"]["value"])) {
	$query .= 'WHERE category.category_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR product.product_name LIKE "%'.$_POST["search"]["value"].'%" ';
}

if(isset($_POST["order"])) {
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
} else {
	$query .= 'ORDER BY product_id DESC ';
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
	$status = '';
	if($row["product_status"] == 'active') {
		$status = '<span class="text-success"><b>Active</b></span>';
	} else {
		$status = '<span class="text-danger"><b>Inactive</b></span>';
	}

	$i++;
	$sub_array = array();

	$sub_array[] = $i;
	$sub_array[] = $row['category_name'];
	$sub_array[] = $row['product_name'];
	$sub_array[] = $row['product_quantity'];
	$sub_array[] = $row['product_price'];
	$sub_array[] = $status;
	$sub_array[] = '<button type="button" name="update" id="'.$row["product_id"].'" class="btn btn-info btn-sm update"><i class="fa fa-edit"></i>&nbsp;</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["product_id"].'" class="btn btn-danger btn-sm delete"><i class="fas fa-trash"></i>&nbsp;</button>';
	$sub_array[] = '<button type="button" name="view" id="'.$row["product_id"].'" class="btn btn-info btn-sm view"><i class="far fa-eye"></i>&nbsp;</button>';
	$sub_array[] = '<button type="button" name="ac_de" id="'.$row["product_id"].'" class="btn btn-warning btn-sm ac_de" data-status="'.$row["product_status"].'"><i class="fas fa-ban"></i>&nbsp;</button>';
	$data[] = $sub_array;
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"    			=> 	$data
);
echo json_encode($output);

function get_total_all_records($connect) {
	$statement = $connect->prepare("SELECT * FROM product");
	$statement->execute();
	return $statement->rowCount();
}
?>