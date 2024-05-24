<?php
include 'config/config.php';
session_start();

if(isset($_GET['id'])) {
    $id_user = $_GET['id'];
    $_SESSION['id_user'] = $id_user; 
} else if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
} else {
    die("User ID not set.");
}

// echo "User ID: " . $id_user; // For debugging

if(isset($_POST['export'])){
    // Export CSV data
    $query = "SELECT * FROM client WHERE id_user='$id_user'";
    $result = mysqli_query($conn, $query);
    
    if($result && mysqli_num_rows($result) > 0){
        $filename = "client_data.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $fp = fopen('php://output', 'w');
        
        // Add CSV headers
        $headers = array('ID', 'Name', 'Email', 'Phone', 'Status', 'User ID');
        fputcsv($fp, $headers);
        
        // Add data rows
        while($row = mysqli_fetch_assoc($result)){
            fputcsv($fp, $row);
        }
        fclose($fp);
        exit;
    } else {
        echo "No data available to export.";
    }
}

if(isset($_POST['import'])){
    if($_FILES['csv_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['csv_file']['tmp_name'])){
        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, "r");
        $header = true; 
        
        while(($row = fgetcsv($handle, 1000, ",")) !== false){
            if($header){
                $header = false;
                continue;
            }
            $name = mysqli_real_escape_string($conn, $row[0]);
            $email = mysqli_real_escape_string($conn, $row[1]);
            $phone = mysqli_real_escape_string($conn, $row[2]);
            $status = mysqli_real_escape_string($conn, $row[3]);
            $imported_id_user = mysqli_real_escape_string($conn, $row[4]);
            
            $id_user_to_use = $id_user ?: $imported_id_user;

            // Insert data into database
            $query = "INSERT INTO client (name, email, phone, status, id_user) VALUES ('$name', '$email', '$phone', '$status', '$id_user_to_use')";
            $result = mysqli_query($conn, $query);
            
            if(!$result){
                echo "Error inserting data: " . mysqli_error($conn);
                exit;
            }
        }
        fclose($handle);
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management</title>
    <link rel="stylesheet" href="styles/index.css" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="addClt">
   
        <button class="btnAdd" href="#" data-toggle="modal" data-target="#exampleModal">Add new client</button>
        <form method="post">
            <button type="submit" name="export" class="btnExport">Export CSV</button>
        </form>
        <form method="post" enctype="multipart/form-data">
        <button type="submit" name="import" class="btnImport">Import CSV</button>

            <input type="file" name="csv_file" accept=".csv" required>
        </form>
        <button type="submit"  class="btnLogout"><a style='text-decoration:none;' href='login.php'>Log out</a></button>

    </div>
    
    <br>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Client</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form to add new client -->
                    <form id="eventtForm" action="addClient.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $id_user; ?>">

                        <label for="nameClient">Name</label><br>
                        <input type="text" id="nameClient" name="nameClient" required><br>

                        <label for="emailClient">Email</label><br>
                        <input type="email" id="emailClient" name="emailClient" required><br>

                        <label for="phoneClient">Phone</label><br>
                        <input type="text" id="phoneClient" name="phoneClient" required><br>

                        <label for="status">Status</label><br>
                        <input type="text" id="status" name="status" required><br>

                        <button type="submit" class="btn btn-primary btnStyle">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                <th>User ID</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM client WHERE id_user='$id_user' ";
            $result = mysqli_query($conn, $query);
            if($result){
                while($row = mysqli_fetch_assoc($result)){
                    $id_client = $row['id'];
                    $name = $row['name'];
                    $email = $row['email'];
                    $phone = $row['phone'];
                    $status = $row['status'];
                    $id_user = $row['id_user'];
                    
                    $_SESSION['id_user'] = $id_user;
                    $_SESSION['name'] = $name;
                    $_SESSION['email'] = $email;
                    $_SESSION['phone'] = $phone;
                    $_SESSION['status'] = $status;

                    // Display clients details
                    echo "<tr>
                        <td>$id_client</td>
                        <td>$name</td>
                        <td>$email</td>
                        <td>$phone</td>
                        <td>$status</td>
                        <td>$id_user</td>
                        <td>
                            <button class='delete-btn'><a style='text-decoration:none;color:white;'href='actions/delete_client.php?id=$id_client'>Delete</a></button>
                            <button class='edit-btn'><a style='text-decoration:none;color:white;;'href='actions/update_client.php?id=$id_client'>Edit</a></button>
                        </td>
                    </tr>";
                }
                mysqli_free_result($result);
            } else {
                echo "Can't get the data";
            }
            ?>
        </tbody>
    </table>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
