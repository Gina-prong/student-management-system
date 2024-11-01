<?php
include('../Model/config.php');
include('../Model/model1.php');

$model = new StudentModel();

if (isset($_POST['register'])) {
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $program = $_POST['program'];
    $level = $_POST['level'];
    $_registration_date = $_POST['registration_date'] ?? date('Y-m-d H:i:s');


    $result = $model->registerStudent($student_id, $name, $email, $phone, $program, $level, $_registration_date );

    if ($result) {
      echo 'Student registered successfully!';
    } else {
      echo 'Error registering student: ' . mysqli_error($conn);
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Registration</title>
  <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
  </nav>
  <main>
    <div class="container">
      <div class="row">
        <div class="col-md-6 offset-md-3">
          <div class="card">
            <div class="card-body">
              <h2><i class="fa fa-user-plus"></i> Student Registration</h2>
              <form action="" method="post">
                <div style="width:100%;">
                  <div style="width:50%; float:left">
                    <div class="form-group">
                      <label for="student_id"><i class="fa fa-id-card"></i> Student ID:</label>
                      <input type="text" class="form-control" id="student_id" name="student_id" required>
                    </div>
                    <div class="form-group">
                      <label for="name"><i class="fa fa-user"></i> Name:</label>
                      <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                      <label for="email"><i class="fa fa-envelope"></i> Email:</label>
                      <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                      <label for="phone"><i class="fa fa-phone"></i> Phone:</label>
                      <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                  </div>
                  <div style="width:50%; float: right;">
                    <div class="form-group">
                      <label for="program"><i class="fa fa-graduation-cap"></i> Program:</label>
                      <select class="form-control" id="program" name="program" required>
                        <option value="">Select Program</option>
                        <option value="DIT">Diploma in Information Technology</option>
                        <option value="DHN">Diploma in Hardware Networking</option>
                        <option value="DWD">Diploma in Web Development</option>
                     </select>
                    </div>
                    <div class="form-group">
                     <label for="level"><i class="fa fa-level-up"></i> Level:</label>
                      <select class="form-control" id="level" name="level" required>
                        <option value="">Select Level</option>
                        <option value="freshman">Freshman</option>
                        <option value="continuing">Continuing</option>
                      </select>
                   </div>
                    <div class="form-group">
                      <label for="regidtration_date"><i class="fa fa-save"></i> Registration Date:</label>
                      <input type="datetime-local" class="form-control" id="regidtration_date" name="regidtration_date" required>
                    </div>
                  </div>
                  <button type="submit" name="register" class="btn btn-primary"><i class="fa fa-save"></i> Register</button>
                </div> 
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
        if (data.includes('Student registered successfully!')) {
          alert('Student registered successfully!');
          window.location.href = 'dashboard.php';
        } else {
          alert('Error registering student!');
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
        alert('Error registering student!');
      }
    });
  });
});
</script>
</body>
</html>


