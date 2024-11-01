<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System | Login</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome-free-6.6.0-web/css/all.min.css">
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="text-center"><i class="fas fa-graduation-cap"></i> Student Management System</h3>
                        <h5 class="text-center">Login</h5>
                        <form id="login-form" action="dashboard.php">
                            <div class="form-group">
                                <label for="username"><i class="fas fa-user"></i> Username</label>
                                <input type="text" class="form-control" id="username" placeholder="Enter username">
                            </div>
                            <div class="form-group">
                                <label for="password"><i class="fas fa-lock"></i> Password</label>
                                <input type="password" class="form-control" id="password" placeholder="Enter password">
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember-me">
                                    <label class="custom-control-label" for="remember-me">Remember me</label>
                                </div>
                            </div>
                            <a href="dashboard.php"><button type="submit" id="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Login</button>
                            </a>
                        </form>
                        <p class="text-center">Forgot password? <a href="contact admin">Reset password</a></p>
                        <p class="text-center">Don't have an account? <a href="contact admin">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="jQuery.js"></script>
    <script src="bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script>
        // Define allowed usernames and passwords
        const allowedUsers = [
            { username: 'admin', password: 'Admin123!' },
            // Add more users here
        ];

        // Form submission event
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get input values
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            // Check username and password
            const user = allowedUsers.find(user => user.username === username && user.password === password);

            if (!user) {
                document.getElementById('error-message').innerphp = 'Invalid username or password';
            } else {
                window.location.href = 'dashboard.php';
            }
        });
    </script>
</body>
</html>


