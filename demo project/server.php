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


function fetch_student_data($con) {
    $result = $con->query("SELECT * FROM students");
    $student_json = json_encode($result->fetch_all(MYSQLI_ASSOC));
    return $student_json;
}


function add($con) {
    session_start();

    if (isset($_POST['studsubmit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        // Check for duplicate email
        $count = $con->query("SELECT 1 FROM students WHERE email ='$email'");

        if ($count && $count->num_rows > 0) {
            $_SESSION['error'] = "Duplicate Email address can't be inserted";
        } else {
            if ($con->query("INSERT INTO students (name, email, age) VALUES ('$name', '$email', '$age')") === TRUE) {
                $_SESSION['success'] = "Student added successfully!";
            } else {
                $_SESSION['error'] = "Failed to add student: " . $con->error;
            }
        }

        header("Location: dashboard.php");
        exit();
    }
}

function delete($con){
    if(isset($_POST['delete'])){
        $id = $_POST['id'];
        $con->query("DELETE FROM students WHERE id='$id'");
        header("Location: dashboard.php");
        exit();
    }
}


function edit($con) {
    session_start();

    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        $query = "UPDATE students SET name='$name', email='$email', age='$age' WHERE id='$id'";

        if ($con->query($query) === TRUE) {
            $_SESSION['update'] = 'updated field';
            header('Location: dashboard.php');
        } else {
            echo "Error updating: " . $con->error;
        }
        exit();
    }
}

function login(){
    session_start();
    
    if(isset($_POST['submit'])){
        $username = $_POST["username"];
        $password = $_POST["password"]; 

        if($username==='waqas' && $password === 'waqas'){
            header('Location: dashboard.php');
            exit();
        }
        else{
            $_SESSION['message'] = 'Invalid Credentials';
            header('Location: index.php');

        }
    }
}

login();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if (isset($_POST['studsubmit'])) {
        add($con);
    }

    if(isset($_POST['delete'])){
        delete($con);
    }

    if(isset($_POST['update'])){
        edit($con);
    }

}

?>