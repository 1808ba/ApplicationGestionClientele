<?php
$host='localhost';
$username='root';
$password='';
$db_name='gestionC';

$conn =mysqli_connect($host, $username,$password,$db_name);
//Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>