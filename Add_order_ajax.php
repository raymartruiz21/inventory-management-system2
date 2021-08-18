<?php 
/*
Site : https://smarttutorials.net
Author :muni
 */
$connect = mysqli_connect("localhost", "root", "", "inventory_management_system");

$code = !empty($_GET['code']) ? $_GET['code'] : '';
$name = !empty($_GET['name']) ? strtolower(trim($_GET['name'])) : '';

$fieldName = 'product_code';

switch ($code) {
    case 1:
        $fieldName = 'product_name';
        break;
    case 2:
        $fieldName = 'product_price';
        break;
}

$data = array();
if (!empty($_GET['name'])) {
    $name = $_GET['name'];
    $sql = "SELECT product_name, product_price, product_code FROM product where '$fieldName' LIKE '" . $name . "%'";
    $result = mysqli_query($connect, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $name = $row['product_code'] . '|' . $row['product_name'] . '|' . $row['product_price'] ;
        array_push($data, $name);
    }
}
echo json_encode($data);exit;
// include('database_connection.php');
// $connect = mysqli_connect("localhost", "root", "", "inventory_management_system");
// if(isset($_POST['search'])){
//     $search = mysqli_real_escape_string($connect,$_POST['search']);

//     $query = "SELECT * FROM product WHERE product_code LIKE'%".$search."%'";
//     $result = mysqli_query($connect,$query);
    
//     while($row = mysqli_fetch_array($result) ){
//         $response[] = array(
//             "value"=>$row['product_name'],
//             "value1"=>$row['product_price'],
//             "label"=>$row['product_code']
//         );
//     }

//     echo json_encode($response);
// }

// exit;

//insert.php;

// if(isset($_POST["code"])){
    
//  $order_id = uniqid();
//  for($count = 0; $count < count($_POST["item_name"]); $count++)
//  {  
//   $query = "INSERT INTO order_tbl 
//   (order_id, item_name, item_quantity, item_unit) 
//   VALUES (:order_id, :item_name, :item_quantity, :item_unit)
//   ";
//   $statement = $connect->prepare($query);
//   $statement->execute(
//    array(
//     ':order_id'   => $order_id,
//     ':item_name'  => $_POST["item_name"][$count], 
//     ':item_quantity' => $_POST["item_quantity"][$count], 
//     ':item_unit'  => $_POST["item_unit"][$count]
//    )
//   );
//  }
//  $result = $statement->fetchAll();
//  if(isset($result))
//  {
//   echo 'ok';
//  }
// }
?>