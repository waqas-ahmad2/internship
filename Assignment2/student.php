<?php
  require_once('server.php');
  if (session_status() === PHP_SESSION_NONE){
    session_start();
}?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="style.css"/>
  <title>School Management</title>

</head>
<body class="d-flex m-0 p-0 row bg-light">
  
<!-- sidebar   -->
  <div class="d-flex flex-row flex-md-column col-md-2 text-white gap-3 sidebar_style m-0 align-items-center" id="sidebar">
    
    <!-- heading -->
    <h5 class="mt-4 d-none d-md-block fw-semibold">NAVIGATION</h5>
    
    <!-- buttons student-->
    <a href="#" class="w-100 p-2 m-0 sidelink_style active_btn">
      <span class="fa-solid fa-user"></span>
      <span class="text-decoration-none d-none d-md-inline ms-3">Student</span>
    </a>
 
    <!-- buttons Detail-->
    <a href="detail.php" class="w-100 p-2 m-0 sidelink_style">
      <span class="fa-solid fa-receipt"></span>
      <span class="d-none d-md-inline ms-3">Fee Voucher</span>
    </a>

    <!-- buttons Logout-->
    <a href="index.php" class="w-100 p-2 m-0 sidelink_style">
      <span class="fa-solid fa-right-from-bracket"></span>
      <span class="d-none d-md-inline ms-3">Logout</span>
    </a>
  
  </div>

  <!-- Main content -->
  <div class="col" id="content">
    
    <!--top navbar  -->
    <div id="navbar" class="row bg-white d-flex align-items-center">
        <div class="input-group p-3" style="max-width: 500px;">
          <span class="input-group-text bg-white border-end-0">
            <i class="fa fa-search text-secondary"></i>
          </span>
          <input class="form-control border-start-0 border-end-0" type="search" placeholder="Search..." id="search">
          <button class="btn btn-primary" type="submit">Search</button>
        </div>
      </div>
    
    <!--heading div-->
    <?php
    if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];

    echo "
    <div class='alert alert-{$message_type} w-100 alert-dismissible fade show' role='alert'>
        <strong>{$message}</strong>
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";

    // to clear the message after displaying it
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
  }
  ?>

    <div class="row p-2 bg-grey">
      <h4 class="col-9">Student Details</h4>
      <button type='button' class="btn btn-warning col-2 me-2"  onclick="OpenModal('add_stud')">Add</button>
    </div>

    <!--table area-->
    <div class="mx-2 card shadow" style="max-height:550px;">
      <div class="card-body overflow-auto pt-0 px-1 " style="max-height: 70vh;">
        <table class="table table-hover .table-responsive">
          <thead class="table-light">
            <tr>
              <th>S.No.</th>
              <th>Name</th>
              <th>Email</th>
              <th>Age</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="bubble">
            <?php
              $sql = "SELECT * FROM students";
              $result  = $con->query($sql);
          
              $count = 0;
              while($row = $result->fetch_assoc()){
                  $count+=1;
                  echo "<tr>
                      <td> $count </td>
                      <td>$row[name] </td>
                      <td>$row[email] </td>
                      <td>$row[age] </td>
                      <td>
                          <a href='#' class='text-primary me-2 text-decoration-none'>
                          <i  data-studedit='{$row['id']}' class='fas fa-edit edit_stud'></i>
                          </a>

                          <a href='#'  class='text-danger text-decoration-none'>
                          <i data-studdel='{$row['id']}' class='fas fa-trash-alt del_stud'></i>
                          </a>
                      </td>
                      </tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Student Modal -->
  <div id="StudentsModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitleAdd" class="text-center mb-3">Add Student</h3>
        <h3 id="modalTitleUpdate" class="text-center mb-3">Update Student</h3>

        <form id="studentForm" method="POST" action="server.php?action=addStudent">
            <!-- Hidden field for student ID -->
            <input type="hidden" id="studentId" name="id" value="">
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
                <input type="number" step="1" class="form-control" id="studentAge" name="age" value="" min=0 max=50 required>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" id="modalUpdateButton" class="btn btn-warning " name="update">Update</button>
                <button type="submit" id="modalSubmitButton" class="btn btn-primary" name="studsubmit">Submit</button>
                <button type="button" class="btn btn-secondary" onclick="CloseModal()">Cancel</button>
            </div>
        </form>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>
