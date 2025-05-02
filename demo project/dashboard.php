<?php
require_once 'server.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="style.css" />
  <title>Student Management</title>

</head>
<body>
    
  <div class="container-fluid">
    <div class="row flex-nowrap">

      <!-- Sidebar -->
      <nav class="col-auto col-md-2 px-0 sidebar d-flex flex-column align-items-center">
        <h2 class="my-3 d-flex align-items-center">
          <i class="fa-solid fa-bars me-2 d-md-none"></i>
          <span class="d-none d-md-inline">Menu</span>
        </h2>
        <a href="#" class="link text-decoration-none p-3 text-center w-100 active">
          <i class="fa-solid fa-user me-2"></i><span class="d-none d-md-inline">Students</span>
        </a>
        <a href="index.php" class="link text-decoration-none p-3 text-center w-100">
          <i class="fa-solid fa-right-from-bracket me-2"></i><span class="d-none d-md-inline">Logout</span>
        </a>
      </nav>

      <!-- main display -->
      <main class="col ps-md-3 pt-3">
        <div class="bg-dark text-white p-3 rounded mb-4 d-flex flex-column flex-md-row align-items-md-center gap-3">
          <label class="mb-0">Search:</label>
          <input id='searchbar' class="form-control w-100 w-md-50 searchbox" type="search" placeholder="Search..." />
          <button id='addbtn' class="btn btn-primary">Add</button>
        </div>

        <!-- Student Modal -->
        <div id="StudentsModal" class="modal">
            <div class="modal-content">
                <h3 id="modalTitleAdd" class="text-center mb-3">Add Student</h3>
                <h3 id="modalTitleUpdate" class="text-center mb-3">Update Student</h3>

                <form id="studentForm" method="POST" action="server.php">
                    <input type="hidden" id="studentId" name="id" value=""> <!-- Hidden field for student ID -->
                    <div class="mb-3 text-start">
                        <label for="studentName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="studentName" name="name" value="" required>
                    </div>

                    <div class="mb-3 text-start">
                        <label for="studentEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="studentEmail" name="email" value="" required>
                    </div>

                    <div class="mb-3 text-start">
                        <label for="studentAge" class="form-label">Age</label>
                        <input type="number" step="1" class="form-control" id="studentAge" name="age" value="" required>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" id="modalUpdateButton" class="btn btn-warning " name="update">Update</button>
                        <button type="submit" id="modalSubmitButton" class="btn btn-primary" name="studsubmit">Submit</button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    </div>
                </form>
            </div>
          </div>
          <?php 
            if(isset($_SESSION['update'])){
                echo
                '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Data Updated Successfully!</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';

                unset($_SESSION['update']);
            }

            if (isset($_SESSION['error'])) {
                echo
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>' . $_SESSION['error'] . '</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                unset($_SESSION['error']);
            }
            
            if (isset($_SESSION['success'])) {
                echo
                '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>' . $_SESSION['success'] . '</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                unset($_SESSION['success']);
            }
                    
          ?>

        <h2>Students</h2>

        <div id="display">
          <!-- For JS -->
        </div>
      </main>

    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>
