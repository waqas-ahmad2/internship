<?php

$servername = "wsc5531.encs.concordia.ca";
$username = "wsc55314";
$password = "Database5531";
$dbname = "wsc55314";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}


function fetchdata($con){
    $sql = "SELECT *,(`Monthly Fee` + `Uniform Fee` + `Sports Fee` + `Fine`) AS `Total Amount` FROM Record";
    $result = $con->query($sql);

    $record = json_encode($result->fetch_all(MYSQLI_ASSOC));

    return $record;
}
function add($con) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST['savebtn'])) {
        $student_id = $_POST['student_id'];
        $invoice_no = $_POST['invoice_no'];
        $date = $_POST['date'];
        $monthly_fee = $_POST['monthly_fee'];
        $uniform_fee = $_POST['uniform_fee'];
        $sports_fee = $_POST['sports_fee'];
        $fine = $_POST['fine'];

        // Check for duplicate invoice number
        $check = $con->query("SELECT 1 FROM Record WHERE invoice_no = '$invoice_no'");

        if ($check && $check->num_rows > 0) {
            $_SESSION['error'] = "Duplicate invoice number can't be inserted";
        } else {
            $sql = "INSERT INTO Record (student_id, invoice_no, Date, `Monthly Fee`, `Uniform Fee`, `Sports Fee`, `Fine`)
                    VALUES ('$student_id', '$invoice_no', '$date', '$monthly_fee', '$uniform_fee', '$sports_fee', '$fine')";

            if ($con->query($sql) === TRUE) {
                $_SESSION['success'] = "Record added successfully!";
            } else {
                $_SESSION['error'] = "Failed to add record: " . $con->error;
            }
        }

        header("Location: main.php");
        exit();
    }
}

function edit($con) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST['updatebtn'])) {
        $student_id = $_POST['student_id'];
        $invoice_no = $_POST['invoice_no'];
        $date = $_POST['date'];
        $monthly_fee = $_POST['monthly_fee'];
        $uniform_fee = $_POST['uniform_fee'];
        $sports_fee = $_POST['sports_fee'];
        $fine = $_POST['fine'];

        // Check if record exists for this invoice_no before updating
        $check = $con->query("SELECT 1 FROM Record WHERE invoice_no = '$invoice_no'");

        if ($check && $check->num_rows > 0) {
            $sql = "UPDATE Record 
                    SET student_id = '$student_id',
                        Date = '$date',
                        `Monthly Fee` = '$monthly_fee',
                        `Uniform Fee` = '$uniform_fee',
                        `Sports Fee` = '$sports_fee',
                        `Fine` = '$fine'
                    WHERE invoice_no = '$invoice_no'";

            if ($con->query($sql) === TRUE) {
                $_SESSION['success'] = "Record updated successfully!";
            } else {
                $_SESSION['error'] = "Failed to update record: " . $con->error;
            }
        } else {
            $_SESSION['error'] = "Record not found for invoice number: $invoice_no";
        }

        header("Location: main.php");
        exit();
    }
}

function delete($con) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST['deletebtn'])) {
        $invoice_no = $_POST['r_invoice_no'];
        // Check if record exists
        $check = $con->query("SELECT 1 FROM Record WHERE invoice_no = '$invoice_no'");

        if ($check && $check->num_rows > 0) {
            $sql = "DELETE FROM Record WHERE invoice_no = '$invoice_no'";

            if ($con->query($sql) === TRUE) {
                $_SESSION['success'] = "Record deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete record: " . $con->error;
            }
        } else {
            $_SESSION['error'] = "Record not found for invoice number: " .$invoice_no;
        }

        header("Location: main.php");
        exit();
    }
}


function login(){
    if(isset($_POST['submit'])){
        $username = $_POST["username"];
        $password = $_POST["password"]; 

        if($username==='waqas' && $password === 'waqas'){
            header('Location: main.php');
            exit();
        }
        else{
            $_SESSION['message'] = 'Invalid Credentials';
            header('Location: index.php');

        }
    }
}

login();
add($con);
edit($con);
delete($con);

?>