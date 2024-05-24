<?php
session_start();
include '../config/config.php';

if (isset($_SESSION['id_user'], $_SESSION['name'], $_SESSION['email'], $_SESSION['phone'], $_SESSION['status'])) {
    $id_user = $_SESSION['id_user'];
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $phone = $_SESSION['phone'];
    $status = $_SESSION['status'];
} else {
    echo "<script>alert('client couldn't be edit.')</script>";

    exit();
}


if(isset($_GET['id'])) {
    $idClient=$_GET['id'];
  



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nameClient = $_POST["nameClient"];
    $emailClient = $_POST["emailClient"];
    $phoneClient = $_POST["phoneClient"];
    $status = $_POST["status"];
    // $id_user = $_POST["id"];

    $query="UPDATE client SET name='$nameClient', email='$emailClient', phone='$phoneClient', status='$status' WHERE id='$idClient'";

    $result = mysqli_query($conn, $query);
    if ($result) {
        $_SESSION["error"] = "saved";
    // echo $id_user;


        header("Location: ../index.php?id=$id_user"); // 
    } else {
        $_SESSION["error"] = "failed";
        header("Location: register.php"); 
    }

} 
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Client</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 400px;
        }
        .container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btnStyle {
            display: inline-block;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
            width: 100%;
        }
        .btnStyle:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Client</h2>
        <form id="eventtForm" method="post" action="update_client.php?id=<?php echo $idClient?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nameClient">Name</label>
                <input type="text" id="nameClient" name="nameClient" value="<?php echo $name?>" required>
            </div>
            <div class="form-group">
                <label for="emailClient">Email</label>
                <input type="email" id="emailClient" name="emailClient" value="<?php echo $email?>" required>
            </div>
            <div class="form-group">
                <label for="phoneClient">Phone</label>
                <input type="text" id="phoneClient" name="phoneClient" value="<?php echo $phone?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" id="status" name="status" value="<?php echo $status?>" required>
            </div>
            <button type="submit" class="btnStyle">Save</button>
        </form>
    </div>
</body>
</html>
