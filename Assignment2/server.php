<?php
if (session_status() === PHP_SESSION_NONE){
    session_start();
    
}

$servername = "wsc5531.encs.concordia.ca";
$username = "wsc55314";
$password = "Database5531";
$dbname = "wsc55314";

$con = new mysqli($servername,$username,$password,$dbname);

if($con->connect_error){
    die('connection error');
}

//this will check for action value in the url
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action === 'deleteStudent') {
        $id = $_GET['id'];
        $sql = "DELETE FROM students WHERE id=$id";
        if ($con->query($sql) === TRUE) {
            $_SESSION['message'] = "User ID: {$id} deleted successfully";
            $_SESSION['message_type'] = "success"; 
        } else {
            $_SESSION['message'] = "Error: User ID: {$id} not deleted";
            $_SESSION['message_type'] = "danger";
        }
        exit();
    }

    if ($action === 'getStudent') {
        $id = $_GET['id'];
        $sql = "SELECT * FROM students WHERE id=$id";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_assoc());
        } else {
            echo json_encode(['error' => 'Student not found']);
        }
        exit();
    }

    if ($action === 'editStudent') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        $sql = "UPDATE students SET name='$name', email='$email', age=$age WHERE id=$id";
        if ($con->query($sql) === TRUE) {
            $_SESSION['message'] = "Student ID {$id} updated successfully";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: Update for Student ID {$id} was not successful";
            $_SESSION['message_type'] = "danger";
        }
        header("Location: student.php");
        exit();
    }

    

    if ($action === 'addStudent') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        $sql = "INSERT INTO students (name, email, age) VALUES ('$name', '$email', $age)";
        if ($con->query($sql) === TRUE) {
            $_SESSION['message'] = "Student added successfully";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: Student not added";
            $_SESSION['message_type'] = "danger";
        }
        header("Location: student.php");
        exit();
    }

    if ($action === 'addVoucher') {
        $date = $_POST['date'];
        $stud_id = (int) $_POST['id'];
        $descriptions = $_POST['description'];
        $amounts = $_POST['amount'];
    
        // Calculate total from input
        $TotalAmount = 0;
        foreach ($amounts as $amt) {
            $TotalAmount += floatval($amt);
        }
    
        // Insert into master
        $sql = "INSERT INTO master (date, total_amount) VALUES('$date', $TotalAmount)";
        if ($con->query($sql) === TRUE) {
            $master_id = $con->insert_id;
            $success = true;
    
            // Insert into detail
            for ($i = 0; $i < count($descriptions); $i++) {
                $desc = $con->real_escape_string($descriptions[$i]);
                $amt = floatval($amounts[$i]);
    
                $sql2 = "INSERT INTO detail (voucher_id, student_id, description, amount)
                         VALUES ('$master_id', '$stud_id', '$desc', '$amt')";
                if (!$con->query($sql2)) {
                    $success = false;
                    break;
                }
            }
    
            if ($success) {
                $_SESSION['message'] = "Voucher created successfully";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error: voucher not added";
                $_SESSION['message_type'] = "danger";
            }
            header("Location: detail.php");
            exit();
        } else {
            $_SESSION['message'] = "Error: master record not created";
            $_SESSION['message_type'] = "danger";
        }
    }

    if ($action === 'getrecord') {
        $id = $_GET['id'];
        $sql = "SELECT detail.id as detail_id, students.id as std_id, voucher_id,name,email,amount,description FROM detail join students on students.id = detail.student_id WHERE voucher_id=$id";
        $result = $con->query($sql);
    
        if ($result->num_rows > 0) {
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        } else {
            echo json_encode(['error' => 'record not found']);
        }
        exit();
        
    }


    if ($action === 'deleterecord') {
        $id = (int) $_GET['id'];

        $result = $con->query("DELETE FROM master WHERE voucher_id = $id");

        if($result == true){
            $_SESSION['message'] = "Record ID: {$id} deleted successfully";
            $_SESSION['message_type'] = "success";
        }
        else{
            $_SESSION['message'] = "Error: Record ID: {$id} not deleted";
            $_SESSION['message_type'] = "danger";
        }

    
        exit();
     }


     
    
     if ($action === 'updateentry') {
         // Get the JSON payload from the request
         $input = json_decode(file_get_contents('php://input'), true);
     
         $id = (int) $input['id'];
         $description = $con->real_escape_string($input['description']);
         $amount = floatval($input['amount']);
     
         // Update the entry in the database
         $sql = "UPDATE detail SET description = '$description', amount = $amount WHERE id = $id";
     
         if ($con->query($sql) === TRUE) {
             // Recalculate the total amount for the voucher
             $result = $con->query("SELECT voucher_id FROM detail WHERE id = $id");
             if ($result && $row = $result->fetch_assoc()) {
                 $voucher_id = (int) $row['voucher_id'];
     
                 $sql2 = "UPDATE master 
                          SET total_amount = (SELECT IFNULL(SUM(amount), 0) FROM detail WHERE voucher_id = $voucher_id)
                          WHERE voucher_id = $voucher_id";
     
                 $con->query($sql2);
             }
     
             echo json_encode(['success' => true]);
         } else {
             echo json_encode(['success' => false, 'message' => 'Error updating entry: ' . $con->error]);
         }
         exit();
     }

     if ($action === 'addentry') {
        $input = json_decode(file_get_contents("php://input"), true);
        $voucher_id = (int)$input['voucher_id'];
        $student_id = (int)$input['student_id'];
        $description = $con->real_escape_string($input['description']);
        $amount = floatval($input['amount']);
    
        $sql = "INSERT INTO detail (voucher_id, student_id, description, amount) VALUES ($voucher_id, $student_id, '$description', $amount)";
        
        if ($con->query($sql) === TRUE) {
            $entry_id = $con->insert_id;
    
            // Recalculate the master total
            $sql2 = "UPDATE master 
                     SET total_amount = (SELECT IFNULL(SUM(amount), 0) FROM detail WHERE voucher_id = $voucher_id)
                     WHERE voucher_id = $voucher_id";
            $con->query($sql2);
    
            echo json_encode(['success' => true, 'entry_id' => $entry_id]);
        } else {
            echo json_encode(['success' => false, 'message' => $con->error]);
        }
        exit();
    }
     

    

    if ($action == 'deleteentry') {
        $id = (int) $_GET['id'];
    
        // Fetch the voucher_id of the entry
        $result = $con->query("SELECT voucher_id FROM detail WHERE id = $id LIMIT 1");
        if ($result && $row = $result->fetch_assoc()) {
            $voucher_id = $row['voucher_id'];
    
            $sql1 = "DELETE FROM detail WHERE id = $id";
            $sql2 = "SELECT * FROM detail WHERE voucher_id = $voucher_id";
            $sql3 = "DELETE FROM master WHERE voucher_id = $voucher_id";
            $sql4 = "UPDATE master SET total_amount = (SELECT IFNULL(SUM(amount), 0) FROM detail WHERE voucher_id = $voucher_id)
            WHERE voucher_id = $voucher_id";
    
    
            if ($con->query($sql1) === TRUE) {
                //update amoint in the master
                $con->query($sql4);

                // Delete from master only if no more details exist
                if ($con->query($sql2)->num_rows === 0) {
                    $con->query($sql3);
                }
    
                $_SESSION['message'] = "Entry {$id} deleted successfully";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Error: Could not delete Entry ID {$id}";
                $_SESSION['message_type'] = "danger";
            }
        } else {
            $_SESSION['message'] = "Error: Entry not found";
            $_SESSION['message_type'] = "danger";
        }
        exit();

    }
}
function login(){
    
    if(isset($_POST['submit'])){
        $username = $_POST["username"];
        $password = $_POST["password"]; 

        if($username==='waqas' && $password === 'waqas'){
            header('Location: student.php');
            exit();
        }
        else{
            $_SESSION['message'] = 'Invalid Credentials';
            header('Location: index.php');

        }
    }
}

login();


?>