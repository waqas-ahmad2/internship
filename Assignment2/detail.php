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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <link rel="stylesheet" href="style.css"/>
  <title>School Management</title>

</head>
<body class="d-flex m-0 p-0 row bg-light">
  
<!-- sidebar   -->
  <div class="d-flex flex-row flex-md-column col-md-2 text-white gap-3 sidebar_style m-0 align-items-center" id="sidebar">
    
    <!-- heading -->
    <h5 class="mt-4 d-none d-md-block fw-semibold">NAVIGATION</h5>
    
    <!-- buttons student-->
    <a href="student.php" class="w-100 p-2 m-0 sidelink_style ">
      <span class="fa-solid fa-user"></span>
      <span class="text-decoration-none d-none d-md-inline ms-3">Student</span>
    </a>

    <!-- buttons Detail-->
    <a href="#" class="w-100 p-2 m-0 sidelink_style  active_btn">
      <span class="fa-solid fa-receipt"></span>
      <span class="d-none d-md-inline ms-3 ">Fee Voucher</span>
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

    // Clear the message after displaying it
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
  }
  ?>

    <div class="row p-2 bg-grey">
      <h4 class="col-9">Details</h4>
      <button type='button' class="btn btn-warning col-2 me-2"  onclick="OpenVoucherModal('add_voucher')">Add Voucher</button>
    </div>

    <!--table area-->
    <div class="mx-2 card" style="max-height:550px;">
      <div class="card-body overflow-auto pt-0" style="max-height: 70vh;">
        <table class="table table-hover .table-responsive table-center ">
          <thead class="table-light">
            <tr>
              <th>item #</th>
              <th>Voucher#</th>
              <th>Date</th>
              <th>Total Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="bubble">
            <?php
              $sql = "SELECT * FROM master";
              $result  = $con->query($sql);
              $count = 0;
              while($row = $result->fetch_assoc()){
                  $count+=1;
                  echo "<tr>
                      <td> $count </td>
                      <td>{$row['voucher_id']} </td>
                      <td>{$row['date']} </td>
                      <td>{$row['total_amount']} </td>
                      <td>
                          <a href='#' class='text-success me-2 text-decoration-none'>
                            <i data-vouchedit={$row['voucher_id']} class='fas fa-search view_entry'></i>
                          </a>

                          <a href='#' class='text-primary me-2 text-decoration-none'>
                            <i data-vouchedit={$row['voucher_id']} class='fas fa-edit edit_entry'></i>
                          </a>

                          <a href='#'  class='text-danger text-decoration-none'>
                            <i data-vouchdel={$row['voucher_id']} class='fas fa-trash-alt del_entry'></i>
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

  <!-- Add Voucher Modal -->
  <div id="VoucherModal" class="modal">
    <div class="modal-content p-2">
        <h3 id="modalTitleAdd" class="text-center mb-3">Add Voucher</h3>

        <form id="voucher_form" class="m-1 row overflow-auto pt-0" style="max-height: 70vh;" action="server.php?action=addVoucher" method="POST">
            
            <div>
              <input type="number" id='sumtotal' name='sumtotal' value="" hidden>
            </div>
            <div class="mb-3 col-6">
                <label class="form-label fw-semibold" for="date">Date:</label>
                <input class="form-control" name='date' id="date" type="date" required>
            </div>

            <div class="mb-3 col-6">
                <label class="form-label fw-semibold" for="select">Student Id</label>
                <select class="form-select" id="select" name='id' required>
                    <option disabled selected>choose Id...</option>
                    <?php 
                        $std = $con->query("SELECT * FROM students");
                        $std_list = [];
                        while($stds = $std->fetch_assoc()){
                          $std_list[] = $stds['id'];
                        }?>
                    <?php 
                      foreach($std_list as $item){
                        echo "<option>{$item}</option>";
                      }
                    ?>
                </select>
            </div>

            <div class="fw-semibold fs-5">Expense Details</div>
            <div>
                <table class="table table-resonsive table-bordered text-center">
                    <thead class="table-dark">
                        <tr>
                            <th class="fw-semibold">Description</th>
                            <th class="fw-semibold">Amount</th>
                            <th class="fw-semibold">Action</th>
                        </tr>
                    </thead>

                    <hr>
                    
                    <tbody id="table_body">
                        <tr class="mb-3 form-group">
                            
                            <td>
                                <input class="form-control"  name="description[]" type="text" required>
                            </td>
                            <td>
                                <input class="form-control"  name="amount[]" type="number" required min=1>                      
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning add_row">Add</button>
                                <button type="button" class="btn btn-danger d-none del_row">Delete</button>
                            </td>
                        </tr>
                    
                    </tbody>
                </table>
            </div>

            <div class="d-flex gap-3">
                <button class="btn btn-outline-success" id="modalSubmitButton">Save</button>
                <button type="button" class="btn btn-outline-secondary" onclick="CloseVoucherModal()">Cancel</button>
            </div>

        </form>
      

    </div>
  </div>

<!-- Receipt Modal -->
<div id="ReceiptModal" class="modal">
  <div id='modal_content' class="modal-content p-4">
    <h3 id="receiptModalTitle" class="text-center mb-3">Fee Invoice</h3>
    <form id="receiptForm">
      <!-- Voucher and Student Details -->
      <div class="mb-3">
        <label for="receiptVoucherId" class="form-label fw-semibold">Voucher ID:</label>
        <input type="text" id="receiptVoucherId" name="voucher_id" class="form-control" disabled>
      </div>
      <input id="receiptStudentId" type="hidden" name="student_id">
      <div class="mb-3">
        <label for="receiptStudentName" class="form-label fw-semibold">Student Name:</label>
        <input type="text" id="receiptStudentName" name="student_name" class="form-control" disabled>
      </div>
      <div class="mb-3">
        <label for="receiptStudentEmail" class="form-label fw-semibold">Student Email:</label>
        <input type="text" id="receiptStudentEmail" name="student_email" class="form-control" disabled>
      </div>

      <!-- Invoice Details -->
      <div>
        <h5 class="fw-semibold mt-4">Invoice Details</h5>
      </div>
      <div>
        <table class="table table-bordered table-hover text-center">
          <thead class="table-dark">
            <tr>
                <th id="Serial_number" class="d-none">S.No.</th>
                <th>Description</th>
                <th>Amount</th>
                <th id='edit_entry_th_action' class="d-none">Action</th>
            </tr>
          </thead>
          <button id='addEntryBtn' type="button" class="btn btn-primary mb-3 d-none" onclick="addNewEntryRow()">Add Entry</button>

          <tbody id="receiptDetails">
              <!-- Rows will be dynamically populated here -->
          </tbody>
        </table>
      </div>

      <h6 class="fw-semibold mt-4">Total Amount: <span id="amt_sum"></span> </h6> 

      <div class="d-flex justify-content-end gap-3 mt-3 modal-footer">
        <button type="button" id="saveButton" class="btn btn-success d-none" onclick="generatePDF()">Save</button>
        <button type="button" id="updateButton" class="btn btn-warning d-none" >Update</button>
        <button type="button" id="printButton" class="btn btn-info d-none" onclick="window.print()">Print</button>
        <button type="button" class="btn btn-secondary" onclick="CloseReceiptModal('refresh')">Close</button>
      </div>
    </form>
  </div>
</div>

  <script src="script.js"></script>
</body>
</html>
