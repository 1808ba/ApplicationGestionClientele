<?php
include '../config/config.php';
echo json_encode("deboging".$_POST);
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $username =$_POST["username"];
    $Email=$_POST["Email"];
    $password=$_POST["password"];


    $query = "INSERT INTO user (username,email,password) VALUES ('$username','$Email','$password')";
    $result=mysqli_query($conn,$query);
    if($result){
        $_SESSION["error"] = "saved";
        header("location: ../login.php");
    }else{
        $_SESSION["error"]="failed";
        header("location: register.php");

    }

} else {
    header("location: register.php");
}       



?>
