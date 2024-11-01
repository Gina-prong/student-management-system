<?php
include("../Model/config.php");
include('../Model/model1.php');

$model = new StudentModel();

function postRecord() {
    global $conn;
    global $model;

    if (isset($_POST['make_payment'])) {
        $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
        $payment_date = mysqli_real_escape_string($conn, $_POST['payment_date']);
        $amount = mysqli_real_escape_string($conn, $_POST['amount']);
        $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
        $payment_type = mysqli_real_escape_string($conn, $_POST['payment_type']);

        if (empty($student_id) || empty($payment_date) || empty($amount) || empty($payment_method) || empty($payment_type)) {
            echo 'Please fill in all fields!';
            return;
        }

        $result = $model->makePayment($student_id, $payment_date, $amount, $payment_method, $payment_type);

        if ($result) {
            echo 'Payment made successfully!';
        } else {
            echo 'Error making payment: ' . mysqli_error($conn);
        }
    }
}

postRecord();
?>
<!DOCTYPE html> 
<html lang="en"> 
<head> 
  <meta charset="UTF-8"> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <title>Make Payment</title> 
  <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.min.css"> 
  <link rel="stylesheet" href="style.css"> 
</head> 
<body> 
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark "> 
    <a class="navbar-brand" href="#"><i class="fa fa-graduation-cap"></i> Student Management System</a> 
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"> 
      <span class="navbar-toggler-icon"></span> 
    </button> 
    <div class="collapse navbar-collapse" id="navbarSupportedContent"> 
      <ul class="navbar-nav mr-auto"> 
        <li class="nav-item active"> 
          <a class="nav-link" href="dashboard.php"><i class="fa fa-dashboard"></i> Dashboard <span class="sr-only">(current)</span></a> 
        </li> 
        <li class="nav-item"> 
          <a class="nav-link" href="student.php"><i class="fa fa-users"></i> Students</a> 
        </li> 
        <li class="nav-item"> 
          <a class="nav-link" href="payment.php"><i class="fa fa-money-check-alt"></i> Payments</a> 
        </li> 
        <li class="nav-item">
         <a class="nav-link" href="reports.php"><i class="fa fa-file"></i> Reports</a>
        </li>
      </ul> 
    </div> 
    <div class="user-info">
            <div class="user-info">
              <div class="user-details">
               <span><i class="fa fa-user"></i> Username: </span>
               <span><i class="fa fa-crown"></i> Rank: </span>
             </div>
             <div class="date-time">
              <span id="date"></span>
              <span id="time"></span>
             </div>
            </div>
    </div>
  </nav> 
  <main> 
    <div class="container"> 
      <div class="row"> 
        <div class="col-md-6 offset-md-3"> 
          <div class="card"> 
            <div class="card-body"> 
              <h2><i class="fa fa-credit-card"></i> Make Payment</h2>
              <form action="" method="post"> 
                <div class="form-group"> 
                  <label for="student_id"><i class="fa fa-user"></i> Student ID:</label> 
                  <input type="text" class="form-control" id="student_id" name="student_id" required> 
                </div> 
                <div class="form-group"> 
                  <label for="payment_date"><i class="fa fa-calendar"></i> Payment Date:</label> 
                  <input type="date" class="form-control" id="payment_date" name="payment_date" required> 
                </div> 
                <div class="form-group"> 
                  <label for="amount"><i class="fa fa-money-bill-alt"></i> Amount:</label> 
                  <input type="number" class="form-control" id="amount" name="amount" required> 
                </div> 
                <div class="form-group"> 
                  <label for="payment_method"><i class="fa fa-credit-card"></i> Payment Method:</label> 
                  <select class="form-control" id="payment_method" name="payment_method" required> 
                    <option value="">Select Method</option> 
                    <option value="cash">Cash</option> 
                    <option value="bank_transfer">Bank Transfer</option> 
                    <option value="online_payment">Online Payment</option> 
                  </select> 
                </div> 
              <div class="form-group">
                <label for="payment_type"><i class="fa fa-credit-card"></i> Payment Type:</label>
                <select class="form-control" id="payment_type" name="payment_type" required>
                  <option value="">Select Type</option>
                  <option value="tuition">Tuition</option>
                  <option value="department_dues">Department Dues</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <button type="submit" name="make_payment" class="btn btn-primary"><i class="fa fa-credit-card"></i>  Payment</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
 </main>
 <script scr="jQuery.js"></script>
 <script scr="bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
 <script>
$(document).ready(function() {
  $('form').submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: '', // Same page, so no URL needed
      data: $('form').serialize(),
      success: function(data) {
        console.log(data);
        // Handle success response
        if (data.includes('Payment made successfully!')) {
          alert('Payment made successfully!');
          window.location.href = 'dashboard.php';
        } else {
          alert('Error making payment!');
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
        alert('Error making payment!');
      }
    });
  });
});
</script>
</body>
</html>




