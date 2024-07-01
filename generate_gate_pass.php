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
    <link rel="stylesheet" href="bootstrap/css/sweetalert2.min.css">
    <link rel="stylesheet" href="styles/guard-style.css">
    <link rel="stylesheet" href="styles/guard-print-style.css">
    <title>Generate Pass Card</title>
</head>

<body class="text-bg-light">

    <!-- Navbar -->
    <?php

    $dashboard = "";
    $generateGatePass = "custom-active";
    $verifyGatePass = "";

    require "require/guard_navbar.php";

    ?>
    <!-- Navbar End -->

    <!-- Body -->
    <main class="container-fluid">
        <div class="row custom-background-image">
            <!-- Sidebar -->
            <?php

            $dashboard = "";
            $generateGatePass = "active";
            $verifyGatePass = "";

            require "require/guard_sidebar.php";

            ?>
            <!-- Sidebar End -->

            <!-- Main -->
            <div class="col-lg-9 min-vh-100 after-navbar-space offset-lg-2" id="main">
                <!-- Gate Passes -->
                <div class="row justify-content-center">
                    <div class="card mb-4 mt-2 px-0">
                        <div class="card-header text-dark" style="background-color: rgb(234, 234, 234);">
                            <h5 class="text-start fw-normal mb-3">
                                Generate Gate Pass
                            </h5>
                            <ul class="nav nav-tabs nav-fill nav-justified card-header-tabs">
                                <!-- Unregistered Vehicle Button -->
                                <button type="button" class="nav-link text-dark active tab-hover" aria-current="true" data-bs-toggle="tab" data-bs-target="#unregisteredTabPane">
                                    Unregistered Vehicle
                                </button>

                                <!-- Registered Vehicle Button -->
                                <button type="button" class="nav-link text-dark tab-hover" data-bs-toggle="tab" data-bs-target="#registeredTabPane">
                                    Registered Vehicle
                                </button>
                            </ul>
                        </div>
                        <div class="card-body pb-2">
                            <div class="tab-content">
                                <!-- Unregistered Vehicle Generate Pass Form -->
                                <div class="tab-pane fade show active" id="unregisteredTabPane">
                                    <form id="unregisteredVehicleGatePassForm">
                                        <div class="row pt-4 gx-3 mb-3">
                                            <div class="col-md-5 mb-1 mb-md-0">
                                                <input type="text" class="form-control form-control-sm form-input-hover" placeholder="Plate Number" id="plateNumber" required autofocus>
                                                <span class="text-danger small" id="plateNumberFeedback"></span>
                                            </div>
                                            <div class="col-md-3 mb-1 mb-md-0">
                                                <select id="vehicleType" class="form-select form-select-sm form-input-hover" required>
                                                    <option selected value="">Select Vehicle Type</option>
                                                    <option value="Car" class="select-options-font-size">Car</option>
                                                    <option value="SUV" class="select-options-font-size">SUV</option>
                                                    <option value="Bus" class="select-options-font-size">Bus</option>
                                                    <option value="Truck" class="select-options-font-size">Truck</option>
                                                    <option value="Van" class="select-options-font-size">Van</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 ms-auto">
                                                <button type="submit" class="btn btn-success btn-sm px-5 px-md-2 w-100 add-user-button">
                                                    Sign in vehicle
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-8 mb-1 mb-md-0">
                                            <input type="text" class="form-control form-control-sm" id="unregister-vehicle-pass-code-holder" placeholder="Pass Code: " disabled>
                                        </div>
                                        <div class="col-md-2 ms-auto">
                                            <button type="button" class="btn btn-sm btn-light w-100" id="printGatePass" style="outline: 1px solid rgb(173, 171, 171);">
                                                <i class="bi bi-printer-fill me-2"></i>
                                                Print Gate Pass
                                            </button>
                                        </div>
                                        <!-- Print Gate Pass For Unregistered Vehicle -->
                                        <div class="container d-none d-print-block printable-container">
                                            <div class="row justify-content-center mb-5">
                                                <div class="col-auto">
                                                    <!-- Print Header -->
                                                    <a class="navbar-brand d-inline-flex align-items-center small me-5">
                                                        <img src="images/fug-logo.png" alt="Navbar Image" class="img-fluid rounded custom-navbar-image me-3">
                                                        <h5 class="fw-medium text-success m-0 p-0">
                                                            <span>Federal University Gusau</span>
                                                            <p class="fs-6 m-0 p-0 fw-normal small">
                                                                Knowledge, Innovation and Service
                                                            </p>
                                                        </h5>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center mt-5">
                                                <div class="col-md-12">
                                                    <!-- Print Body -->
                                                    <div>
                                                        <table class="table table-bordered w-100">
                                                            <tr>
                                                                <th class="w-25">Pass Code</th>
                                                                <td id="unregisterd-vehicle-print-pass-code-holder"></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="w-25">Vehicle Type</th>
                                                                <td id="unregisterd-vehicle-print-vehicle-type-holder"></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Registered Vehicle Generate Pass Form -->
                                <div class="tab-pane fade" aria-current="true" id="registeredTabPane">
                                    <form action="" id="registeredVehicleGatePassForm">
                                        <div class="row pt-4 gx-3 mb-3">
                                            <div class="col-md-8">
                                                <input type="text" class="form-control form-control-sm form-input-hover" placeholder="Pass Code" id="registered-vehicle-pass-code" required>
                                                <span class="text-danger small" id="registered-vehicle-feedback"></span>
                                            </div>
                                            <div class="col-md-2 ms-auto">
                                                <button type="submit" class="btn btn-success btn-sm w-100 px-md-2 add-user-button">
                                                    Sign in vehicle
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row gx-3 mb-3">
                                        <div class="col-md-4 mb-2">
                                            <input type="text" class="form-control form-control-sm" placeholder="Plate Number:" id="registered-vehicle-plate-number" disabled>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <input type="text" class="form-control form-control-sm" placeholder="Vehice Type:" id="registered-vehicle-vehicle-type" disabled>
                                        </div>
                                        <div class="w-100"></div>
                                        <div class="col-md-4 mb-2">
                                            <input type="text" class="form-control form-control-sm" placeholder="Owner Name:" id="registered-vehicle-owner-name" disabled>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <input type="text" class="form-control form-control-sm" placeholder="Owner Phone:" id="registered-vehicle-owner-phone" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gate Passes Report -->
                    <div class="col-md-12 rounded m-1 p-4 pb-3 p-md-3 pt-1 mb-2 pb-md-1 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                        <!-- Header and Search Field -->
                        <div class="row d-flex justify-content-between mt-1 mb-3">
                            <div class="col-auto">
                                <h5 class="text-start fw-normal">
                                    Gate Passes
                                </h5>
                            </div>
                            <div class="col-auto">
                                <form action="" role="search" id="searchForm">
                                    <div class="input-group input-group-sm">
                                        <input type="search" class="form-control form-control-sm search-input-radius px-3" placeholder="Search By Plate Number" id="search-pass-code">
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
                                        <th class="fw-medium">Pass Code</th>
                                        <th class="fw-medium">Plate Number</td>
                                        <th class="fw-medium">Vehicle Type</td>
                                        <th class="fw-medium">Issued By</th>
                                        <th class="fw-medium">Issued Date</th>
                                        <th class="fw-medium">Status</th>
                                        <th class="fw-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider" id="pass-codes-list">

                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!-- Pagination and Home Button -->
                    <?php
                    require 'require\db.php';

                    $rows_per_page = 10;    // Update partner in 'logic/generate_gate_pass_logic.php'

                    // Getting the total number of records (pages)
                    $sql = "SELECT * FROM gate_passes";
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
    <script src="bootstrap/js/jquery-3.7.1.js"></script>
    <script src="bootstrap/js/sweetalert2@11.js"></script>
    <script src="script/print-gatePass-scipt.js"></script>
    <script src="script/timeoutLogoutUser.js"></script>
    <script>
        // Function to fetch list of passcodes
        function fetchPassCodes() {
            $.ajax({
                url: 'logic/generate_gate_pass_logic.php',
                method: 'POST',
                data: {
                    action: 'fetchPassCodes'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.errors);
                    $('#pass-codes-list').html(response.data);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error); // Log any errors to console
                }
            });
        }

        // Function to sign out vehicle
        function signOutVehicle(pass_code) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Sign out this vehicle",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, sign out!',
                reverseButtons: true,
            }).then(value => {
                if (value.isConfirmed) {
                    $.ajax({
                        url: 'logic/verify_gate_pass_logic.php',
                        method: 'POST',
                        data: {
                            action: 'signOutVehicle',
                            passCode: pass_code,
                        },
                        success: function(response) {
                            console.log(response);
                            let data = JSON.parse(response);
                            if (data.success) {
                                fetchPassCodes();
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
        }

        $(document).ready(function() {
            // Calling the function to fetch the pass codes
            fetchPassCodes();

            // Function to validate form fields
            function validateForm() {
                let plateNumber = $('#plateNumber').val().trim();
                let plateNumberFeedback = $('#plateNumberFeedback');

                const plateNumberPattern = /^[A-Za-z]{3}[0-9]{3}[A-Za-z]{2}$/;

                let isValid = true;

                // Validate Plate Number
                if (!plateNumberPattern.test(plateNumber)) {
                    plateNumberFeedback.text("Please enter a valid plate number.");
                    isValid = false;
                } else {
                    plateNumberFeedback.text("");
                }

                return isValid;
            }

            // Function to validate form fields for pass code
            function validatePassCode() {
                let passCode = $('#registered-vehicle-pass-code').val().trim();
                let passCodeFeedback = $('#registered-vehicle-feedback');

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

            // Search for pass code using plate number on key press
            $('#search-pass-code').keyup(function(e) {
                e.preventDefault();

                let plateNumber = $('#search-pass-code').val().trim();
                let action = 'searchPassCode';
                if (plateNumber.length <= 0) {
                    fetchPassCodes();
                }
                $.ajax({
                    url: 'logic/generate_gate_pass_logic.php',
                    method: 'POST',
                    data: {
                        plateNumber: plateNumber,
                        action: action
                    },
                    dataType: 'json',
                    success: function(response) {
                        // console.log(response);
                        $('#pass-codes-list').html(response.data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error); // Log any errors to console
                    }
                });
            });

            // Add input event listener for real-time validation
            $('#unregisteredVehicleGatePassForm').on('input', validateForm);

            // Generate gate pass for unregistered vehicle
            $('#unregisteredVehicleGatePassForm').submit(function(e) {
                e.preventDefault();

                if (validateForm()) {
                    let plateNumber = $('#plateNumber').val().trim();
                    let vehicleType = $('#vehicleType').val().trim();

                    $.ajax({
                        url: 'logic/generate_gate_pass_logic.php',
                        method: 'POST',
                        data: {
                            action: 'unregisterGatePass',
                            plateNumber: plateNumber,
                            vehicleType: vehicleType,
                        },
                        success: function(response) {
                            let data = JSON.parse(response);
                            if (data.success) {
                                $('#plateNumber').val('');
                                $('#vehicleType').val('');
                                $('#unregister-vehicle-pass-code-holder').val(`Pass Code: \t ${data.pass_code}`);
                                $('#unregisterd-vehicle-print-pass-code-holder').text(data.pass_code);
                                $('#unregisterd-vehicle-print-vehicle-type-holder').text(data.vehicle_type);
                                fetchPassCodes();
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

            // Add input event listener for real-time validation
            $('#registeredVehicleGatePassForm').on('input', validatePassCode);

            // Generate gate pass for registered vehicle
            $('#registeredVehicleGatePassForm').submit(function(e) {
                e.preventDefault();

                if (validatePassCode()) {
                    let passCode = $('#registered-vehicle-pass-code').val().trim();

                    $.ajax({
                        url: 'logic/generate_gate_pass_logic.php',
                        method: 'POST',
                        data: {
                            action: 'registerGatePass',
                            passCode: passCode,
                        },
                        success: function(response) {
                            // console.log(response);
                            let data = JSON.parse(response);
                            if (data.success) {
                                $('#registered-vehicle-pass-code').val('');
                                $('#registered-vehicle-plate-number').val(`Plate Number:  ${data.plate_number}`);
                                $('#registered-vehicle-vehicle-type').val(`Vehicle Type:  ${data.vehicle_type}`);
                                $('#registered-vehicle-owner-name').val(`Owner Name:  ${data.owner_name}`);
                                $('#registered-vehicle-owner-phone').val(`Owner Phone:  ${data.owner_phone}`);
                                fetchPassCodes();
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