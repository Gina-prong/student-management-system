<?php
// Include database connection
include '../Model/config.php';
include '../Model/model1.php';
// Establish database connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle AJAX requests
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_recent_activity':
            $result = getRecentActivity($conn);
            echo json_encode($result);
            break;
        case 'get_total_payments':
            $result = getTotalPayments($conn);
            echo json_encode($result);
            break;
        case 'get_new_registrations':
            $result = getNewRegistrations($conn);
            echo json_encode($result);
            break;
        case 'get_pending_payments':
            $result = getPendingPayments($conn);
            echo json_encode($result);
            break;
        default:
            echo json_encode('Invalid action');
    }
}

// Define functions to retrieve data
function getRecentActivity($conn) {
    // Database query to retrieve recent activity
    $query = "SELECT * FROM recent_activity";
    $result = mysqli_query($conn, $query);

    // Check for query errors
    if (!$result) {
        return array('error' => 'Database query failed: ' . mysqli_error($conn));
    }

    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

function getTotalPayments($conn) {
    // Database query to retrieve total payments
    $query = "SELECT SUM(amount) AS total_payments FROM payments";
    $result = mysqli_query($conn, $query);

    // Check for query errors
    if (!$result) {
        return array('error' => 'Database query failed: ' . mysqli_error($conn));
    }

    $data = mysqli_fetch_assoc($result);
    return $data;
}

function getNewRegistrations($conn) {
    // Database query to retrieve new registrations
    $query = "SELECT COUNT(*) AS new_registrations FROM students WHERE registration_date > DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";
    $result = mysqli_query($conn, $query);

    // Check for query errors
    if (!$result) {
        return array('error' => 'Database query failed: ' . mysqli_error($conn));
    }

    $data = mysqli_fetch_assoc($result);
    return $data;
}

function getPendingPayments($conn) {
    // Database query to retrieve pending payments
    $query = "SELECT COUNT(*) AS pending_payments FROM payments WHERE payment_status = 'pending'";
    $result = mysqli_query($conn, $query);

    // Check for query errors
    if (!$result) {
        return array('error' => 'Database query failed: ' . mysqli_error($conn));
    }

    $data = mysqli_fetch_assoc($result);
    return $data;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMPSSA-IDCE Management System</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
  <a class="navbar-brand" href="#">
    <i class="fa fa-tachometer-alt"></i> 
    Student Management System 
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="dashboard.php">
          <i class="fa fa-dashboard"></i> 
          Dashboard 
          <span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="student.php">
          <i class="fa fa-users"></i> 
          Students
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="payment.php">
          <i class="fa fa-money-check-alt"></i> 
          Payments
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="reports.php">
          <i class="fa fa-file"></i> 
          Reports
        </a>
      </li>
    </ul>
    <span id="current-date" style="color: #fff;  float: right; margin-left: 20px;"></span>
    <span id="current-time" style="color: #fff; float: right; margin-left: 20px;"></span>   
  </div>
</nav>
<div class="row">
    <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fa fa-table"></i> Recent Activity
                    </h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Registration Date</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody id="recent-activity-table">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fa fa-money-check-alt"></i> Total Payments
                    </h5>
                    <h1 class="display-4" id="total-payments">
                    </h1>
                </div>
            </div>
        </div>
       <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fa fa-user-plus"></i> New Registrations
                    </h5>
                    <h1 class="display-4" id="new-registrations">
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fa fa-clock"></i> Pending Payments
                    </h5>
                    <h1 class="display-4" id="pending-payments">
                    </h1>
                </div> 
            </div>
        </div> 
    </div>
    <script src="jQuery.js"></script>
    <script src="bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const urls = {
        recentActivity: '../Model/model1.php?action=get_recent_activity',
        totalPayments: '../Model/model1.php?action=get_total_payments',
        newRegistrations: '../Model/model1.php?action=get_new_registrations',
        pendingPayments: '../Model/model1.php?action=get_pending_payments'
    };
    function loadDashboardData(url, containerId) {
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (containerId === '#recent-activity-table') {
                    const tableBody = document.querySelector(containerId);
                    tableBody.innerHTML = '';
                    data.forEach(row => {
                        const rowElement = document.createElement('tr');
                        rowElement.innerHTML = `
                            <td>${row.student_id}</td>
                            <td>${row.registration_date}</td>
                            <td>${row.payment_status}</td>
                        `;
                        tableBody.appendChild(rowElement);
                    });
                } else {
                    document.querySelector(containerId).textContent = data.value;
                }
            })
            .catch(error => console.error('Error loading data:', error));
    }

    loadDashboardData(urls.recentActivity, '#recent-activity-table');
    loadDashboardData(urls.totalPayments, '#total-payments');
    loadDashboardData(urls.newRegistrations, '#new-registrations');
    loadDashboardData(urls.pendingPayments, '#pending-payments');

    const dateElement = document.getElementById('current-date');
    const timeElement = document.getElementById('current-time');

    function formatDate() {
        const date = new Date();
        return `${date.getFullYear()}-${padZero(date.getMonth() + 1)}-${padZero(date.getDate())}`;
    }

    function padZero(number) {
        return number.toString().padStart(2, '0');
    }

    function formatTime() {
        const date = new Date();
        return `${padZero(date.getHours())}:${padZero(date.getMinutes())}:${padZero(date.getSeconds())}`;
    }

    function updateDate() {
        dateElement.textContent = formatDate();
    }

    function updateTime() {
        timeElement.textContent = formatTime();
        setTimeout(updateTime, 1000); // Update every second
    }

    updateDate();
    updateTime();
});
</script>
</body>
</html>



