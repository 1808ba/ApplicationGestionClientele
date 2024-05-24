<?php
include 'config/config.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nameClient = $_POST["nameClient"];
    $emailClient = $_POST["emailClient"];
    $phoneClient = $_POST["phoneClient"];
    $status = $_POST["status"];
    $id = $_POST["id"];

    $query = "INSERT INTO client (name, email, phone, status, id_user) VALUES ('$nameClient', '$emailClient', '$phoneClient', '$status', '$id')";

    $result = mysqli_query($conn, $query);
    if ($result) {
        $_SESSION["error"] = "saved";
        header("Location: ../index.php?id=$id"); // idClient
    } else {
        $_SESSION["error"] = "failed";
        header("Location: register.php"); 
    }
} else {
    echo json_encode(['result' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>
