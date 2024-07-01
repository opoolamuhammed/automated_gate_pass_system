<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link href="bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/change-password-style.css">
    <link rel="stylesheet" href="bootstrap/css/sweetalert2.min.css">
    <title>Change Password</title>
</head>

<body class="custom-background-image">

    <!-- Change Password -->
    <div class="container">
        <div class="row align-items-center justify-content-center min-vh-100">
            <div class="col-auto col-md-10 col-lg-6">
                <div class="card shadow-sm py-4 m-4">
                    <div class="card-body px-md-0">
                        <!-- Header Link and Image -->
                        <a href="change_password.php" class="navbar-brand d-flex align-items-center justify-content-center small text-center">
                            <img src="images/fug-logo.png" alt="Navbar Image" class="img-fluid rounded custom-navbar-image me-4">
                            <h3 class="fw-medium text-success m-0 p-0">
                                <span>Federal University Gusau</span>
                                <p class="fs-6 m-0 p-0 fw-normal small text-danger">
                                    Knowledge, Innovation and Service
                                </p>
                            </h3>
                        </a>

                        <!-- Change Password Text -->
                        <p class="text-center mt-4 mb-5 fw-light fs-5 d-flex align-items-center justify-content-center">
                            <i class="bi bi-unlock-fill me-2"></i>
                            Enter new password to change your password
                        </p>

                        <!-- Change Password Form -->
                        <form method="POST" id="changePasswordForm">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-md-10 mb-3">
                                    <label for="newPassword1" class="form-label mb-1 small">New Password</label>
                                    <input type="password" class="form-control form-control-sm form-input-hover" id="newPassword1" required autofocus>
                                </div>
                                <div class="col-md-10 mb-4">
                                    <label for="newPassword2" class="form-label mb-1 small">Confirm Password</label>
                                    <input type="password" class="form-control form-control-sm form-input-hover" id="newPassword2" required>
                                </div>
                                <div class="col-md-10 d-flex justify-content-between">
                                    <a href="manager_dashboard.php" role="button" type="button" class="btn btn-success btn-sm px-5 px-md-3">
                                        <i class="bi bi-chevron-left me-1"></i>
                                        Back
                                    </a>
                                    <button type="submit" class="btn btn-sm btn-primary text-white px-5 px-md-3" style="background-color: #367fa9;">
                                        Change Password
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Change Password End -->

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="bootstrap/js/jquery-3.7.1.js"></script>
    <script src="bootstrap/js/sweetalert2@11.js"></script>
    <script src="script/timeoutLogoutUser.js"></script>
    <script>
        $(document).ready(function() {

            $('#changePasswordForm').submit(function(e) {
                e.preventDefault();

                let newPassword = $('#newPassword1').val().trim();
                let confirmPassword = $('#newPassword2').val().trim();

                let data = {
                    newPassword: newPassword,
                    confirmPassword: confirmPassword
                }

                $.ajax({
                    url: 'logic/change_password_logic.php',
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.success,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#198754',
                            }).then((value) => {
                                window.location.href = 'manager_dashboard.php'; // redirect to dashboard
                            });
                        } else if (response.errors && response.errors.length > 0) {
                            let output = '';
                            response.errors.forEach(function(error) {
                                output += error + "\n";
                            });
                            Swal.fire({
                                title: 'Error!',
                                text: `${output}`,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#198754',
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error); // Log any errors to console
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while processing your request.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#198754',
                        });
                    }
                });

            });

        });
    </script>

</body>

</html>