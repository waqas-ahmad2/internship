<?php

// Database config
$hostname = "mh5ew.h.filess.io";
$database = "SchoolManager123_neighborwe";
$username = "SchoolManager123_neighborwe";
$password = "e8408d1008f01db705eb7ef1ecdfa3e5a08dab80";
$port = 61002;

// Create connection
$con = new mysqli($hostname, $username, $password, $database, $port);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}


function fetch_student_data($con) {
    $result = $con->query("SELECT * FROM students");
    $student_json = json_encode($result->fetch_all(MYSQLI_ASSOC));
    return $student_json;
}



function add($con){
    if(isset($_POST['studsubmit'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        $count = $con->query("SELECT 1 FROM students WHERE email ='$email'");

        if ($count && $count->num_rows > 0) {
            echo "<script>
                alert('Duplicate Email address can't be inserted');
                window.location.href = 'index.php';
            </script>";
        } else {
            $con->query("INSERT INTO students (name, email, age) VALUES ('$name', '$email', '$age')");
            header("Location: index.php");
            exit();
        }
    }

}


function delete($con){
    if(isset($_POST['delete'])){
        $id = $_POST['id'];
        $con->query("DELETE FROM students WHERE id='$id'");
        header("Location: index.php");
        exit();
    }
}


function edit($con) {
    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
    
        $result = $con->query("SELECT * FROM students WHERE id='$id'");
        if ($result && $result->num_rows > 0) {
            $student = $result->fetch_assoc();
    
            echo "<form method='POST' action='server.php' style='width: 100%; max-width: 400px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; background-color: #f9f9f9;'>
            <input type='hidden' name='id' value='{$student['id']}'>
            
            <div style='margin-bottom: 15px;'>
                <label for='name' style='display: block; margin-bottom: 5px; font-weight: bold;'>Name:</label>
                <input type='text' name='name' value='{$student['name']}' required style='width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;'>
            </div>
            
            <div style='margin-bottom: 15px;'>
                <label for='email' style='display: block; margin-bottom: 5px; font-weight: bold;'>Email:</label>
                <input type='email' name='email' value='{$student['email']}' required style='width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;'>
            </div>
            
            <div style='margin-bottom: 15px;'>
                <label for='age' style='display: block; margin-bottom: 5px; font-weight: bold;'>Age:</label>
                <input type='number' name='age' value='{$student['age']}' required style='width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;'>
            </div>
            
            <button type='submit' name='update' style='padding: 10px 20px; border: none; border-radius: 5px; background-color: #007bff; color: white; cursor: pointer;'>Update</button>
          </form>";
    
    
        } else {
            echo "<script>
                    alert('Record not found.');
                    window.location.href = 'index.php';
                  </script>";
        }
    }
    
    // Handle the update request
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];
    
        // Update the record in the database
        $query = "UPDATE students SET name='$name', email='$email', age='$age' WHERE id='$id'";
        if ($con->query($query) === TRUE) {
            header("Location: index.php");
            exit();
        }
        else {
            echo "<script>
                    alert('Error updating record: " . $con->error . "');
                    window.location.href = 'index.php';
                  </script>";
        }
    }
}

function login(){
    if(isset($_POST['submit'])){
        $username = $_POST["username"];
        $password = $_POST["password"];

        if($username==='waqas' && $password === 'waqas'){
            header('Location: index.php');
            exit();
        }
        else{
            echo "<script>
                alert('invalid credential, login unsucessful')
                window.location.href = 'login.php'
            </script>";
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

    if(isset($_POST['edit'])){
        edit($con);
    }

}

?>