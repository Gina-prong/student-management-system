<?php
// Include database connection
include '../Model/config.php';
include '../Model/model1.php';

// Function to generate HTML table
function generateTable($report, $columns) {
    $html = '<table border="1">';
    $html .= '<tr>';
    foreach ($columns as $column) {
        $html .= '<th>' . ucfirst($column) . '</th>';
    }
    $html .= '</tr>';
    foreach ($report as $row) {
        $html .= '<tr>';
        foreach ($columns as $column) {
            $html .= '<td>' . $row[$column] . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
}

// Define functions to retrieve reports
function getStudentRegistrationReport() {
    $model = new StudentModel();
    $result = $model->getStudentRegistrationReport();
    $report = array();
    while ($row = $result->fetch_assoc()) {
        $report[] = $row;
    }
    return $report;
}

function getPaymentReport() {
    $model = new StudentModel();
    $result = $model->getPaymentReport();
    $report = array();
    while ($row = $result->fetch_assoc()) {
        $report[] = $row;
    }
    return $report;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'get_report') {
        $reportType = $_POST['report_type'];
        try {
            if ($reportType == 'student_registration') {
                $report = getStudentRegistrationReport();
                $columns = array('student_id', 'name', 'email', 'phone', 'program', 'level');
                echo generateTable($report, $columns);
            } elseif ($reportType == 'payment') {
                $report = getPaymentReport();
                $columns = array('payment_id', 'student_id', 'payment_date', 'amount', 'payment_method', 'payment_type');
                echo generateTable($report, $columns);
            }
        } catch (Exception $e) {
            echo 'Error generating report: ' . $e->getMessage();
        }
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System Reports</title>
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
                    <a class="nav-link" href="report.php"><i class="fa fa-file"></i> Reports</a>
                </li>
            </ul>
        </div>
    </nav>
    <main>
        <div class="container">
            <h2>Student Management System Reports</h2>
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-primary" id="student-report-btn">Student Registration Report</button>
                    <div id="student-report"></div>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-primary" id="payment-report-btn">Payment Report</button>
                    <div id="payment-report"></div>
                </div>
            </div>
        </div>
    </main>

<script src="jQuery.js">
    $(document).ready(function() {
    $('#student-report-btn').click(function() {
        $.ajax({
            type: 'POST',
            url: 'report.php',
                    data: {action: 'get_report', report_type: 'student_registration'},
                    success: function(data) {
                        $('#student-report').html(data);
                    }
                });
            });

            $('#payment-report-btn').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'windows.locaton.href',
                    data: {action: 'get_report', report_type: 'payment'},
                    success: function(data) {
                        $('#payment-report').html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
