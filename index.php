<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link href="bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="bootstrap/css/sweetalert2.min.css">
    <link rel="stylesheet" href="styles/style.css">
    <title>Login</title>
</head>
<body class="text-bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg text-bg-light border shadow-sm py-1 fixed-top">
    <div class="container">
        <a href="index.php" class="navbar-brand d-inline-flex align-items-center small">
            <img src="images/fug-logo.png" alt="Navbar Image" class="img-fluid rounded custom-navbar-image me-3">
            <h3 class="fw-medium text-success m-0 p-0">
                <span>Federal University Gusau</span>
                <p class="fs-6 m-0 p-0 fw-normal small text-danger">
                    Knowledge, Innovation and Service
                </p>
            </h3>
        </a>
    </div>
</nav>
<!-- Navbar End -->

<!-- Login Page Content -->
<section class="pt-3 pb-3">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <!-- Car Access Gate Image -->
            <div class="col-md-7 mb-md-0">
                <img src="images/login-image.png" alt="Login Page Image" class="img-fluid">
            </div>

            <!-- Vertical Divider -->
            <div class="login-page-divider bg-dark p-0 mt-5 d-none d-md-block"></div>
            
            <!-- Login Form -->
            <div class="col-md-4 ms-md-auto mb-5 mb-md-0">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Please Sign In</h3>
                        <form action="login.php" method="post">
                            <div class="row">
                                <div class="col-md-12 mt-4 mt-md-5 mb-2 mb-md-3">
                                    <input type="text" class="form-control hover" placeholder="Username" name="username" required autofocus>
                                </div>
                                <div class="col-md-12 mb-3 mb-md-4 mb-md-4">
                                    <input type="password" class="form-control hover" placeholder="Password" name="password" required>
                                    <span class="text-danger p-1">
                                        <?php
                                            session_start();

                                            if(isset($_SESSION['error'])) {
                                                echo $_SESSION['error'];
                                                unset($_SESSION['error']);
                                            }
                                        ?>
                                    </span>
                                </div>
                                <div class="col-md-12 mb-2 d-grid">
                                    <button type="submit" class="btn btn-success d-inline-flex align-items-center justify-content-center">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>
                                        Sign In
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Login Page Content End -->

<!-- Footer -->
<footer class="py-2 bg-secondary-subtle text-dark fixed-bottom shadow-sm border">
    <div class="container">
        <div class="text-center small fw-medium">
            <span class="text-secondary">&copy; 2024 Research Project: </span>
            <span>Automated Gate Pass System</span>
        </div>
    </div>
</footer>
<!-- Footer End -->

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>