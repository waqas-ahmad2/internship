<?php 
if (session_status() === PHP_SESSION_NONE){
    session_start();
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>
    <title>Login</title>
</head>

<body class="d-flex flex-column align-items-center gap-4 min-vh-100">

    <h2 class="bg-dark p-2 text-center text-white w-100 mb-4">Login</h2>

    
    <?php 
    if(isset($_SESSION['message'])){
        echo
        "<div class='alert alert-danger w-100 alert-dismissible fade show' role='alert'>
        <strong>{$_SESSION['message']}!</strong> Please Try Again.
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";

        unset($_SESSION['message']);
    }
    ?>


    <div style="width:100%; max-width:450px;">
        <form class=" form-control shadow p-4" action="server.php" method="POST">
            <div class="mb-3">
            <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="enter usermame">
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="enter password">
                    <button id='eye_icon' type="button" class="input-group-text"><i class="fa-solid fa-eye "></i></button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100" name="submit">Submit</button>
        </form>
    </div>
<script src='script.js'></script>
</body>
</html>
