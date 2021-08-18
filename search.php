<?php
include('database_connection.php');

$qrcode = $_REQUEST['qrcode'];
if ($qrcode != "") {
	$query = mysqli_query($connect, "SELECT * FROM product WHERE product_code='$qrcode'");
	$row = mysqli_fetch_assoc($query);
	$product_name = $row["product_name"];
	$product_price = $row["product_price"];
}

$result = array("$product_name", "$product_price");
$myjson = json_encode($result);
echo $myjson;
?>