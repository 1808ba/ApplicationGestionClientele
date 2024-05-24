<?php
session_start(); 
    include "../config/config.php";
    if (isset($_SESSION['id_user'])) {
        $id_user = $_SESSION['id_user'];
       
    
    }
    
    if(isset($_GET['id'])){
    $clientId = $_GET['id'];


    $query="DELETE FROM client WHERE id='$clientId'";
    $result=mysqli_query($conn,$query);
    if($result){
        $_SESSION["error"]="saved";

        echo "<script>alert('event deleted.')</script>";
        
        header("location: ../index.php?id=$id_user");
    }else{
        $_SESSION["error"]="failed";
        echo "<script>alert('client couldn't be delete.')</script>";
        
        header("location: ../index.php?id=$id_user");


    }
    }