<?php
include 'config.php';
if (!class_exists('StudentModel')) {
    class StudentModel {
        private $conn;

        public function __construct() {
            global $conn;
            $this->conn = $conn;
            $this->createTables();
        }

        private function createTables() {
            // students table
            $query = "CREATE TABLE IF NOT EXISTS students (
                student_id VARCHAR(20) PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                phone VARCHAR(20) NOT NULL,
                program VARCHAR(50) NOT NULL,
                level VARCHAR(20) NOT NULL,
                registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX email_index (email),
                INDEX program_index (program),
                INDEX level_index (level)
            )";

            if (!mysqli_query($this->conn, $query)) {
                throw new Exception("Error creating students table: " . mysqli_error($this->conn));
            }

            // payments table
            $query = "CREATE TABLE IF NOT EXISTS payments (
                payment_id INT AUTO_INCREMENT PRIMARY KEY,
                student_id VARCHAR(20) NOT NULL,
                payment_date DATE NOT NULL,
                amount DECIMAL(10,2) NOT NULL,
                payment_method VARCHAR(50) NOT NULL,
                payment_type VARCHAR(50) NOT NULL,
                payment_status VARCHAR(50) NOT NULL DEFAULT 'Pending',
                FOREIGN KEY (student_id) REFERENCES students(student_id)
            )";

            if (!mysqli_query($this->conn, $query)) {
                throw new Exception("Error creating payments table: " . mysqli_error($this->conn));
            }
        }
        
        public function registerStudent(string $student_id, string $name, string $email, string $phone, string $program, string $level, string $_registration_date): bool {
            $query = "INSERT INTO students (student_id, name, email, phone, program, level, registration_date ) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssssss", $student_id, $name, $email, $phone, $program, $level, $_registration_date );
            
            // Check for errors before executing the query
            if ($stmt->errno) {
                throw new Exception($stmt->error);
            }
            
            return $stmt->execute();
        }
        
        
        public function makePayment(string $student_id, string $payment_date, float $amount, string $payment_method, string $payment_type): bool {
            $query = "INSERT INTO payments (student_id, payment_date, amount, payment_method, payment_type) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssdsd", $student_id, $payment_date, $amount, $payment_method, $payment_type);
            
            // Check for errors before executing the query
            if ($stmt->errno) {
                throw new Exception($stmt->error);
            }
            
            return $stmt->execute();
        }
        
        public function getStudentById(string $student_id): mysqli_result {
            $query = "SELECT * FROM students WHERE student_id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new Exception("Error preparing query: " . mysqli_error($this->conn));
            }
            mysqli_stmt_bind_param($stmt, "s", $student_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error executing query: " . mysqli_error($this->conn));
            }
            $result = mysqli_stmt_get_result($stmt);
            return $result;
        }
        public function updateStudent(string $student_id, string $name, string $email, string $phone, string $program, string $level): int {
            $query = "UPDATE students SET name = ?, email = ?, phone = ?, program = ?, level = ? WHERE student_id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new Exception("Error preparing query: " . mysqli_error($this->conn));
            }
            mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $phone, $program, $level, $student_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error executing query: " . mysqli_error($this->conn));
            }
            return mysqli_stmt_affected_rows($stmt);
        }
        public function getPendingPayments(): int {
            $query = "SELECT COUNT(*) AS pending_payments FROM payments WHERE payment_status = 'Pending'";
            $result = mysqli_query($this->conn, $query);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                return $row['pending_payments'];
            } else {
                throw new Exception("Error: " . mysqli_error($this->conn));
            }
        }
        public function getPaymentById(int $payment_id): mysqli_result {
            $query = "SELECT * FROM payments WHERE payment_id = ?";
            $stmt = mysqli_prepare($this->conn, $query);
            if (!$stmt) {
                throw new Exception("Error preparing query: " . mysqli_error($this->conn));
            }
            mysqli_stmt_bind_param($stmt, "i", $payment_id);
            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error executing query: " . mysqli_error($this->conn));
            }
            $result = mysqli_stmt_get_result($stmt);
            return $result;
        }
        public function getNewRegistrations(): int {
            $query = "SELECT COUNT(*) AS new_registrations FROM students WHERE registration_date > DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)";
            $result = mysqli_query($this->conn, $query);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                return $row['new_registrations'];
            } else {
                throw new Exception("Error: " . mysqli_error($this->conn));
            }
        }
    
        public function getRecentPayments(): mysqli_result {
            $query = "SELECT * FROM payments ORDER BY payment_date DESC LIMIT 5";
            $result = mysqli_query($this->conn, $query);
            if ($result) {
                return $result;
            } else {
                throw new Exception("Error: " . mysqli_error($this->conn));
            }
        }
        public function getTotalPayments(): float {
            $query = "SELECT SUM(amount) AS total_payments FROM payments";
            $result = mysqli_query($this->conn, $query);
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                return $row['total_payments'];
            } else {
                throw new Exception("Error: " . mysqli_error($this->conn));
            }
        }
        public function getStudentRegistrationReport() {
            $query = "SELECT * FROM students";
            $result = mysqli_query($this->conn, $query);
            if (!$result) {
                throw new Exception("Error generating report: " . mysqli_error($this->conn));
            }
            return $result;
        }
        
        public function getPaymentReport() {
            $query = "SELECT * FROM payments";
            $result = mysqli_query($this->conn, $query);
            if (!$result) {
                throw new Exception("Error generating report: " . mysqli_error($this->conn));
            }
            return $result;
        }            
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $model = new StudentModel();
    
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'get_recent_activity':
                    $result = $model->getRecentPayments();
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo json_encode($row);
                    }
                    break;
                case 'get_total_payments':
                    echo $model->getTotalPayments();
                    break;
                case 'get_new_registrations':
                    echo $model->getNewRegistrations();
                    break;
                case 'get_pending_payments':
                    echo $model->getPendingPayments();
                    break;
                default:
                    echo 'Invalid action!';
            }
        }
    }
}
?>