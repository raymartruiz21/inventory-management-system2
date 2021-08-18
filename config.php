<?php
/*
Site : http:www.smarttutorials.net
Author :muni
*/
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'autocomplete');
// define('DB_USER', 'root');
// define('DB_PASSWORD', 'mysql');

$conn = mysqli_connect('localhost', 'root', '', 'inventory_management_system');
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}  