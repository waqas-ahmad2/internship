<?php
require_once "server.php";
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
    <title>Master View</title>
</head>
<body>
    
    <div id="searchbar" class="container-fluid w-100 p-2 bg-dark text-light d-flex justify-content-center justify-item-center align-content-center align-items-center flex-wrap gap-3 w-auto">
        <span>Search: </span>
        <span><input type='search' placeholder="Type to Search" id="search" class="form-control"></span>
        <button class="btn btn-primary" onclick="OpenModal('addvoucher')" id='addbtn'>Add Voucher</button>
    </div>


    <div class="container">
            <!--Add Modal -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <h3 id="modalTitleAdd" class="text-center mb-3">Add Fee Record</h3>
                <h3 id="modalTitleUpdate" class="text-center mb-3">Update Fee Record</h3>


                <form id="recordform" method="POST" action="server.php">
                    <input type="hidden" id="record_id" name="id" value="">
                    
                    <div class="mb-3 text-start">
                        <label for="studentId" class="form-label">Student ID</label>
                        <input type="number" class="form-control" id="studentId" name="student_id" value="" min="1000" max="9999" title="ID must be a 4-digit number (1000-9999)" required>
                    </div>

                    <div class="mb-3 text-start">
                        <label for="invoiceNo" class="form-label">Invoice Number</label>
                        <input type="number" class="form-control" id="invoiceNo" name="invoice_no" value="" required>
                    </div>

                    <div class="mb-3 text-start">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>

                    <div class="mb-3 text-start">
                        <label for="monthlyFee" class="form-label">Monthly Fee</label>
                        <input type="number" step="0.01" class="form-control" id="monthlyFee" name="monthly_fee" value="0" min="0" max="999.99">
                    </div>

                    <div class="mb-3 text-start">
                        <label for="uniformFee" class="form-label">Uniform Fee</label>
                        <input type="number" step="0.01" class="form-control" id="uniformFee" name="uniform_fee" value="0" min="0" max="999.99">
                    </div>

                    <div class="mb-3 text-start">
                        <label for="sportsFee" class="form-label">Sports Fee</label>
                        <input type="number" step="0.01" class="form-control" id="sportsFee" name="sports_fee" value="0" min="0" max="999.99">
                    </div>

                    <div class="mb-3 text-start">
                        <label for="fine" class="form-label">Fine</label>
                        <input type="number" step="0.01" class="form-control" id="fine" name="fine" value="0" min="0">
                    </div>
                    
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary" id="submitBtn" name='savebtn'>Save</button>
                        <button type="submit" class="btn btn-warning" id="updateModalBtn" name='updatebtn'>Update</button>
                        <button type="button" class="btn btn-danger" id="closeModalBtn" onclick="CloseModal()">Close</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- receiptModal -->
        <div id="receiptModal" class="modal">
            <div class="modal-content">
                <h3 class="text-center mb-3">Voucher Detail</h3>

                <div class="mb-2 text-start"><strong>Student ID:</strong> <span id="r_studentId"></span></div>
                <div class="mb-2 text-start"><strong>Invoice No:</strong> <span id="r_invoiceNo" name='invoice_no'></span></div>
                <div class="mb-2 text-start"><strong>Date:</strong> <span id="r_date"></span></div>
                <hr>
                <div class="mb-2 text-start"><strong>Monthly Fee:</strong> $<span id="r_monthlyFee"></span></div>
                <div class="mb-2 text-start"><strong>Uniform Fee:</strong> $<span id="r_uniformFee"></span></div>
                <div class="mb-2 text-start"><strong>Sports Fee:</strong> $<span id="r_sportsFee"></span></div>
                <div class="mb-2 text-start"><strong>Fine:</strong> $<span id="r_fine"></span></div>
                <hr>
                <div class="text-end fw-bold fs-5">Total: $<span id="r_totalAmount"></span></div>

                <form method="POST" action="server.php">

                    <input type="hidden" name="r_invoice_no" id='hidden_invoice'>

                    <div class="modal-footer d-flex justify-content-center mt-4">
                        <button type="button" class="btn btn-primary" onclick="window.print()">Print</button>
                        <button type="button" class="btn btn-warning" onclick="CloseReceipt()">Close</button>
                        <button type="submit" class="btn btn-danger" name="deletebtn">Delete</button>

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

            if (isset($_SESSION['warning'])) {
                echo
                '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>' . $_SESSION['warning'] . '</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                unset($_SESSION['warning']);
            }
        ?>
        <div id="display">
            <!-- for JS  -->
        </div>

    </div>

<script src="script.js"></script>
</body>
</html>