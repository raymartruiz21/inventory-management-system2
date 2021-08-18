<?php
include('database_connection.php');

$query = '';

$output = array();

$query .= "
SELECT * FROM user_details 
WHERE user_type = 'user' AND 
";
// search email/username
if(isset($_POST["search"]["value"])) {
	$query .= '(user_email LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR user_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR user_status LIKE "%'.$_POST["search"]["value"].'%") ';
}

if(isset($_POST["order"])) {
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
} else {
	$query .= 'ORDER BY user_id DESC ';
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
	if($row["user_status"] == 'Active') {
		$status = '<span class="text-success"><b>Active</b></span>';
	} else {
		$status = '<span class="text-danger"><b>Inactive</b></span>';
	}
	$i++;
	$sub_array = array();
	$sub_array[] = $i;
	$sub_array[] = '<center><img class="rounded-circle" src="'.$row['photo'].'" alt="profile" style="height: 60px; width: 60px; "></center>';
	$sub_array[] = $row['gender'];
	$sub_array[] = ucfirst($row['user_name']);
	$sub_array[] = $status;
	$sub_array[] = '<button type="button" name="update" id="'.$row["user_id"].'" class="btn btn-info btn-sm update"><i class="fa fa-user-edit"></i>&nbsp;</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["user_id"].'" class="btn btn-danger btn-sm delete"><i class="fas fa-user-times"></i>&nbsp;</button>';
	$sub_array[] = '<button type="button" name="ac_de" id="'.$row["user_id"].'" class="btn btn-warning btn-sm ac_de" data-status="'.$row["user_status"].'"><i class="fas fa-user-lock" style="color: white;"></i>&nbsp;</button>';
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
	$statement = $connect->prepare("SELECT * FROM user_details WHERE user_type='user'");
	$statement->execute();
	return $statement->rowCount();
}

?>