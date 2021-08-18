<?php

//category_fetch.php

include('database_connection.php');

$query = '';

$output = array();

$query .= "SELECT * FROM category ";

// search
if(isset($_POST["search"]["value"])) {
	$query .= 'WHERE category_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR category_status LIKE "%'.$_POST["search"]["value"].'%" ';
}

if(isset($_POST['order'])) {
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
} else {
	$query .= 'ORDER BY category_id ASC ';
}

if($_POST['length'] != -1) {
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
	if($row['category_status'] == 'active') {
		$status = '<span class="text-success"><b>Active</b></span>';
	} else {
		$status = '<span class="text-danger"><b>Inactive</b></span>';
	}

	$i++;
	$sub_array = array();
	$sub_array[] = $i;
	$sub_array[] = $row['category_name'];
	$sub_array[] = $status;
	$sub_array[] = '<button type="button" name="update" id="'.$row["category_id"].'" class="btn btn-info btn-sm update"><i class="fas fa-edit"></i></button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["category_id"].'" class="btn btn-danger btn-sm delete"><i class="fas fa-trash"></i>&nbsp;</button>';
	$sub_array[] = '<button type="button" name="ac_de" id="'.$row["category_id"].'" class="btn btn-warning btn-sm ac_de" data-status="'.$row["category_status"].'"><i class="fas fa-ban"></i>&nbsp;</button>';
	$data[] = $sub_array;
}

$output = array(
	"draw"			=>	intval($_POST["draw"]),
	"recordsTotal"  	=>  $filtered_rows,
	"recordsFiltered" 	=> 	get_total_all_records($connect),
	"data"				=>	$data
);

function get_total_all_records($connect) {
	$statement = $connect->prepare("SELECT * FROM category");
	$statement->execute();
	return $statement->rowCount();
}
echo json_encode($output);

?>