<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Guard') {
    header("Location: index.php"); // Redirect users who are not logged in or not Security Guard
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link href="bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/guard-style.css">
    <link rel="stylesheet" href="bootstrap/css/sweetalert2.min.css">
    <link rel="stylesheet" href="styles/guard-print-style.css">
    <title>Verify Pass Card</title>
</head>

<body class="text-bg-light">

    <!-- Navbar -->
    <?php

    $dashboard = "";
    $generateGatePass = "";
    $verifyGatePass = "custom-active";

    require "require/guard_navbar.php";

    ?>
    <!-- Navbar End -->

    <!-- Body -->
    <main class="container-fluid">
        <div class="row custom-background-image">
            <!-- Sidebar -->
            <?php

            $dashboard = "";
            $generateGatePass = "";
            $verifyGatePass = "active";

            require "require/guard_sidebar.php";

            ?>
            <!-- Sidebar End -->

            <!-- Main -->
            <div class="col-lg-9 min-vh-100 after-navbar-space offset-lg-2" id="main">
                <!-- Gate Passes -->
                <div class="row justify-content-center">
                    <!-- Verify Gate Pass -->
                    <div class="col-md-12 rounded m-1 mt-2 p-4 pb-3 p-md-3 pt-1 mb-4 pb-md-1 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                        <!-- Header  -->
                        <div class="row d-flex justify-content-between mt-1 mb-3">
                            <div class="col-auto">
                                <h5 class="text-start fw-normal">
                                    Verify Gate Pass
                                </h5>
                            </div>
                        </div>

                        <!-- Verify Gate Pass Form -->
                        <form action="" id="verify-gate-pass-form">
                            <div class="row mb-3 gx-3">
                                <div class="col-md-8 mb-1 mb-md-0">
                                    <input type="text" class="form-control form-control-sm" placeholder="Enter Pass Code" id="verify-pass-code" autofocus>
                                    <span class="text-danger small" id="verify-pass-code-feedback"></span>
                                </div>
                                <div class="col-md-2 ms-auto">
                                    <button type="submit" class="btn btn-sm btn-success add-user-button w-100">
                                        Sign out vehicle
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="row mb-3 gx-2">
                            <div class="col-md-8">
                                <input type="text" class="form-control form-control-sm" placeholder="Verification status: " id="verify-status-holder" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Gate Activities Report -->
                    <div class="col-md-12 rounded m-1 p-4 pb-3 p-md-3 pt-1 mb-2 pb-md-1 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                        <!-- Header and Search Field -->
                        <div class="row d-flex justify-content-between mt-1 mb-3">
                            <div class="col-auto">
                                <h5 class="text-start fw-normal">
                                    Gate Activities
                                </h5>
                            </div>
                            <div class="col-auto">
                                <form action="" role="search" id="searchForm">
                                    <div class="input-group input-group-sm">
                                        <input type="search" class="form-control form-control-sm search-input-radius px-3" placeholder="Search By Plate Number" id="search-gate-activity">
                                        <button type="submit" class="disabled btn btn-sm bg-white text-dark border border-start-0 search-button-radius px-3">
                                            <i class="bi bi-search text-success"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Report List -->
                        <div class="table-responsive">
                            <table class="table table-hover small shadow-sm">
                                <thead>
                                    <tr class="table-secondary">
                                        <th class="fw-medium">SN</th>
                                        <th class="fw-medium">Plate Number</td>
                                        <th class="fw-medium">Vehicle Type</th>
                                        <th class="fw-medium">Signed-In By</th>
                                        <th class="fw-medium">Signed-In Time</th>
                                        <th class="fw-medium">Signed-Out By</th>
                                        <th class="fw-medium">Signed-Out Time</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider" id="gate-activities-list">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination and Home Button -->
                    <?php
                    require 'require\db.php';

                    $rows_per_page = 10;  // Update partner in 'logic/verify_gate_pass_logic.php'

                    // Getting the total number of records (pages)
                    $sql = "SELECT * FROM gate_activities";
                    $result = $conn->query($sql);
                    $total_records = $result->num_rows;

                    $pages = ceil($total_records / $rows_per_page);

                    // Unset the session variable to ensure that page reload will reload the $_SESSION['page_number']
                    if (isset($_SESSION['page_number'])) {
                        unset($_SESSION['page_number']);
                    }

                    if (isset($_GET['page_number'])) {
                        $_SESSION['page_number'] = $_GET['page_number'];
                    }

                    require "require/pagination_guard.php";

                    $conn->close();
                    ?>
                </div>
            </div>
            <!-- Main End -->
        </div>
    </main>
    <!-- Body End -->

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script/timeoutLogoutUser.js"></script>
    <script src="bootstrap/js/jquery-3.7.1.js"></script>
    <script src="bootstrap/js/sweetalert2@11.js"></script>
    <script>
        // Function to fetch the gate activities from the database
        function fetchGateActivities() {
            $.ajax({
                url: 'logic/verify_gate_pass_logic.php',
                method: 'POST',
                data: {
                    action: 'fetchGateActivities'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.errors);
                    $('#gate-activities-list').html(response.data);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error); // Log any errors to console
                }
            });
        }

        $(document).ready(function() {
            // Call the function to fetch the gate activities.
            fetchGateActivities();

            // Function to validate form fields for pass code
            function validatePassCode() {
                let passCode = $('#verify-pass-code').val().trim();
                let passCodeFeedback = $('#verify-pass-code-feedback');

                const passCodePattern = /^[A-Za-z]{3}[0-9]{3}[A-Za-z]{2}[0-9]{4}$/;

                let isValid = true;

                // Validate Pass Code
                if (!passCodePattern.test(passCode)) {
                    passCodeFeedback.text("Please enter a valid pass code.");
                    isValid = false;
                } else {
                    passCodeFeedback.text("");
                }

                return isValid;
            }

            // Add input event listener for real-time validation
            $('#verify-gate-pass-form').on('input', validatePassCode);

            // Search for gate activity plate number on key press
            $('#search-gate-activity').keyup(function(e) {
                e.preventDefault();

                let plateNumber = $('#search-gate-activity').val().trim();
                let action = 'searchGateActivity';
                if (plateNumber.length <= 0) {
                    fetchGateActivities();
                }
                $.ajax({
                    url: 'logic/verify_gate_pass_logic.php',
                    method: 'POST',
                    data: {
                        plateNumber: plateNumber,
                        action: action
                    },
                    dataType: 'json',
                    success: function(response) {
                        // console.log(response);
                        $('#gate-activities-list').html(response.data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error); // Log any errors to console
                    }
                });
            });

            // verify pass code upon form submission
            $('#verify-gate-pass-form').submit(function(e) {
                e.preventDefault();

                if (validatePassCode()) {
                    let passCode = $('#verify-pass-code').val().trim();

                    $.ajax({
                        url: 'logic/verify_gate_pass_logic.php',
                        method: 'POST',
                        data: {
                            action: 'verifyPassCode',
                            passCode: passCode,
                        },
                        success: function(response) {
                            // console.log(response);
                            let data = JSON.parse(response);
                            if (data.success) {
                                $('#verify-status-holder').val(`Verification status: ${data.pass_code} is signed out.`);
                                fetchGateActivities();
                                Swal.fire({
                                    title: 'Success!',
                                    text: data.success,
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 1500,
                                    showCloseButton: true,
                                });
                            } else if (data.errors.length > 0) {
                                let output = '';
                                data.errors.forEach(function(error) {
                                    output += error + " \n";
                                });
                                Swal.fire({
                                    title: 'Error!',
                                    text: `${output}`,
                                    icon: 'error',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#198754',
                                    showCloseButton: true,
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
                }
            });

        });
    </script>

</body>

</html>