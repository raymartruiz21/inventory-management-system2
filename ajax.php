<?php
require_once 'config.php';

$fieldNo = !empty($_POST['fieldNo']) ? $_POST['fieldNo'] : '';
$name = !empty($_POST['name']) ? $_POST['name'] : '';

$fieldName = 'product_code';

switch ($fieldNo) {
    case 1:
        $fieldName = 'product_name';
        break;
    case 2:
        $fieldName = 'product_price';
        break;
}

$data = array();
if (!empty($_POST['name'])) {
    $name = $_POST['name'];
    $sql = "SELECT product_name, product_price, product_code FROM product where '$fieldName' LIKE '" . $name . "%'";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['product_code'] . '|' . $row['product_name'] . '|' . $row['product_price'];
        array_push($data, $name);
    }
}
echo json_encode($data);exit;

