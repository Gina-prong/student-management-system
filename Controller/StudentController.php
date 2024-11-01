<?php

include '../Model/config.php';
include '../Model/model.php';

class StudentController {
    private $model;

    public function __construct() {
        $this->model = new StudentModel();

    }
    private function respondWithError($message) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => $message]);
        exit;
    }
    private function respondWithSuccess($message, $data = []) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => $message, 'data' => $data]);
        exit;
    }
    

    // Handle POST requests
    public function handlePost() {
        try {
            if (isset($_POST['action'])) {
                switch ($_POST['action']) {
                    case 'registerStudent':
                        $this->registerStudent($_POST);
                        break;
                    case 'makePayment':
                        $this->makePayment($_POST);
                        break;
                    default:
                        $this->respondWithError("Invalid action!");
                }
            } else {
                $this->respondWithError("Missing action!");
            }
        } catch (Exception $e) {
            $this->respondWithError($e->getMessage());
        }
    }

    // Handle GET requests
    public function handleGet() {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'getStudentById':
                        $this->getStudentById($_GET['student_id']);
                        break;
                    case 'getPaymentById':
                        $this->getPaymentById($_GET['payment_id']);
                        break;
                    case 'getPendingPayments':
                        $this->getPendingPayments();
                        break;
                    case 'getNewRegistrations':
                        $this->getNewRegistrations();
                        break;
                    case 'getRecentPayments':
                        $this->getRecentPayments();
                        break;
                    case 'getTotalPayments':
                        $this->getTotalPayments();
                        break;
                    default:
                        $this->respondWithError("Invalid action!");
                }
            } else {
                $this->respondWithError("Missing action!");
            }
        } catch (Exception $e) {
            $this->respondWithError($e->getMessage());
        }
    }

    // Register Student
    private function registerStudent(array $data) {
        $student_id = filter_var($data['student_id'], FILTER_SANITIZE_STRING);
        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($data['phone'], FILTER_SANITIZE_STRING);
        $program = filter_var($data['program'], FILTER_SANITIZE_STRING);
        $level = filter_var($data['level'], FILTER_SANITIZE_STRING);
        $registration_date = date('Y-m-d H:i:s');

        if ($this->model->registerStudent($student_id, $name, $email, $phone, $program, $level, $registration_date)) {
            $this->respondWithSuccess("Student registered successfully!");
        } else {
            $this->respondWithError("Error registering student!");
        }
    }

    // Make Payment
    private function makePayment(array $data) {
        $student_id = filter_var($data['student_id'], FILTER_SANITIZE_STRING);
        $payment_date = filter_var($data['payment_date'], FILTER_SANITIZE_STRING);
        $amount = filter_var($data['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $payment_method = filter_var($data['payment_method'], FILTER_SANITIZE_STRING);
        $payment_type = filter_var($data['payment_type'], FILTER_SANITIZE_STRING);

        if ($this->model->makePayment($student_id, $payment_date, $amount, $payment_method, $payment_type)) {
            $this->respondWithSuccess("Payment made successfully!");
        } else {
            $this->respondWithError("Error making payment!");
        }
    }

    // Get Student by ID
    private function getStudentById($student_id) {
        $result = $this->model->getStudentById($student_id);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->respondWithSuccess("", $row);
            }
        } else {
            $this->respondWithError("Student not found!");
        }
    }


    // Get Payment by ID
    private function getPaymentById($payment_id) {
        $result = $this->model->getPaymentById($payment_id);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->respondWithSuccess("", $row);
            }
        } else {
            $this->respondWithError("Payment not found!");
        }
    }
    // Get Pending Payments
    private function getPendingPayments() {
        $pending_payments = $this->model->getPendingPayments();
        $this->respondWithSuccess("Pending Payments: $pending_payments");
    }

    // Get New Registrations
    private function getNewRegistrations() {
        $new_registrations = $this->model->getNewRegistrations();
        $this->respondWithSuccess("New Registrations: $new_registrations");
    }

    // Get Recent Payments
    private function getRecentPayments() {
        $result = $this->model->getRecentPayments();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->respondWithSuccess("", $row);
            }
        } else {
            $this->respondWithError("No recent payments!");
        }
    }
// Get Total Payments
private function getTotalPayments() {
    $total_payments = $this->model->getTotalPayments();
    if ($total_payments !== false) {
        $this->respondWithSuccess("Total Payments: $" . number_format($total_payments, 2));
    } else {
        $this->respondWithError("Error retrieving total payments!");
    }
}

}

?>