<?php
// session_start();
include "../config/config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get username and pw
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $query = "SELECT * FROM `user` WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $id = $row['id'];
        $_SESSION["username"] = $username;
        $_SESSION["id"] = $id; 
        header("Location: ../index.php?id=$id");
    } else {
        $_SESSION["error"] = "Invalid username or password.";
        header("Location: login.php");
    }
} else {
    header("Location: login.php");
}
?>
