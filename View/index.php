<?php
include "../Model/config.php";
include "../Model/model1.php";
include "../Controller/StudentController.php"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>
    <link rel="stylesheet" href="../compssa/bootstrap-5.3.3-dist/css/bootstrap.min.css"> 
  <link rel="stylesheet" href="../compssa/fontawesome-free-6.6.0-web/css/all.min.css"> 
  <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Student Management System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <span id="current-date" style="color: #fff;  float: right; margin-left: 20px;"></span>
            <span id="current-time" style="color: #fff; float: right; margin-left: 20px;"></span>  
        </div>
    </nav>
    <div class="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1>Welcome to Student Management System!</h1>
                    <p>Manage student data, courses, payment and reports efficiently.</p>
                    <a href="../compssa/login.php"><button class="btn btn-primary">Get Started</button>
                    </a>
                </div>
                <div class="col-md-6">
                    <i class="fas fa-graduation-cap fa-5x" alt="Graduation Cap Icon"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="features">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <i class="fas fa-user fa-2x" alt="User Icon"></i>
                    <h3>Student Management</h3>
                    <p>View, add, edit, and delete student records.</p>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-book fa-2x" alt="Book Icon"></i>
                    <h3>Course Management</h3>
                    <p>Create, assign, and track courses for students.</p>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-book fa-2x" alt="Book Icon"></i>
                    <h3>Payment Management</h3>
                    <p>Check and track payment of students.</p>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-chart-line fa-2x" alt="Chart Icon"></i>
                    <h3>Reporting and Analytics</h3>
                    <p>Generate reports and analyze student performance.</p>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    &copy; 2024 Student Management System. All rights reserved.
                </div>
            </div>
            <div class="col-md-6">
              <a href="#" class="fa fa-facebook"></a>
              <a href="#" class="fa fa-twitter"></a>
              <a href="#" class="fa fa-linkedin"></a>
            </div>
          </div>
        </div>
      </footer>
      <script src="../compssa/jQuery.js"></script>
      <script src="../compssa/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
      <script>
          const dateElement = document.getElementById('current-date');
  const timeElement = document.getElementById('current-time');

  const formatDate = () => {
    const date = new Date();
    return `${date.getFullYear()}-${padZero(date.getMonth() + 1)}-${padZero(date.getDate())}`;
  };

  const padZero = (number) => {
    return number.toString().padStart(2, '0');
  };

  const formatTime = () => {
    const date = new Date();
    return `${padZero(date.getHours())}:${padZero(date.getMinutes())}:${padZero(date.getSeconds())}`;
  };

  const updateDate = () => {
    dateElement.textContent = formatDate();
  };

  const updateTime = () => {
    timeElement.textContent = formatTime();
    setTimeout(updateTime, 1000); // Update every second
  };

  updateDate();
  updateTime();
      </script>
</body>
</html>
      