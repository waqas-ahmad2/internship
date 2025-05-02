<?php 
 session_start()
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Login</title>
</head>

<body>
    <?php 
    if(isset($_SESSION['message'])){
        echo
        '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Invalid Credentials!</strong> Please Try Again.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';

        unset($_SESSION['message']);
    }
    ?>

    <div class="container-fluid d-flex flex-column mb-0 justify-content-center align-content-center align-items-center" style="height: 100vh;">
        <h1 style="width: 100%; background:goldenrod; text-align:center">LOGIN PAGE</h1>
        <form class="form-control p-5 w-50 m-auto mt-5" action="server.php" method="POST" style="min-width: 300px; max-width:500px">
           
            <label for="Username" class="form-label mb-3">Username</label>
            <input id="Username" class="form-control mb-3" name="username" placeholder="enter username" type='text' required>
           
            <label for="Password" class="form-label mb-3">Password</label>
            <input id="Password" class="form-control mb-3" name="password" placeholder="enter password" type="password" required>
           
            <button type="submit" class="btn btn-primary" name='submit'>Submit</button>
        </form>
        
    </div>
</body>
</html>