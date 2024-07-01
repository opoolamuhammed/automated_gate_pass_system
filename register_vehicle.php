<?php

// Start session
session_start();

// Check if the user is logged in and is a manager
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    header("Location: index.php");
    exit();
}

// require 'logic/register_vehicle_logic.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link href="bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/guard-print-style.css">
    <link rel="stylesheet" href="bootstrap/css/sweetalert2.min.css">
    <link rel="stylesheet" href="styles/manager-style.css">
    <title>Register Vehicle</title>
</head>

<body class="text-bg-light">

    <!-- Navbar -->
    <?php

    $dashboard = "";
    $vehicleManagement = "active";
    $manageUser = "";
    $report = "";

    require 'require/manager_navbar.php';

    ?>
    <!-- Navbar End -->

    <!-- Body -->
    <main class="container-fluid">
        <div class="row custom-background-image">
            <!-- Sidebar -->
            <?php

            $registerVehicle = "fw-medium";
            $manageVehicle = "";
            $show = "show";

            require 'require/manager_sidebar.php';

            ?>
            <!-- Sidebar End -->

            <!-- Main -->
            <div class="col-lg-9 min-vh-100 after-navbar-space offset-lg-2 mb-3" id="main">
                <!-- Register Vehicle Form -->
                <div class="row justify-content-center">
                    <div class="col-md-10 rounded m-1 mt-3 p-4 p-md-3 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                        <h4 class="text-start fw-normal mb-4 mt-1">
                            Vehicle Registration
                        </h4>
                        <form id="myForm">
                            <div class="row">
                                <div class="col-md-12 mb-2">
                                    <label for="plateNumber" class="form-label mb-1 small">Plate Number</label>
                                    <input type="text" class="form-control form-control-sm form-input-hover" id="plateNumber" name="plate_number" placeholder="ABC123DE" required autofocus>
                                    <span id="plateNumberFeedback" class="small text-danger"></span>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="vehicleType" class="form-label mb-1 small">Vehicle Type</label>
                                    <select class="form-select form-select-sm form-input-hover" id="vehicleType" name="vehicle_type" required>
                                        <option value="" selected class="select-options-font-size">Choose Vehicle Type</option>
                                        <option value="Car" class="select-options-font-size">Car</option>
                                        <option value="SUV" class="select-options-font-size">SUV</option>
                                        <option value="Bus" class="select-options-font-size">Bus</option>
                                        <option value="Truck" class="select-options-font-size">Truck</option>
                                        <option value="Van" class="select-options-font-size">Van</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="ownerName" class="form-label mb-1 small">Owner Name</label>
                                    <input type="text" class="form-control form-control-sm form-input-hover" id="ownerName" name="owner_name" placeholder="John Doe" required>
                                    <span id="ownerNameFeedback" class="small text-danger"></span>
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label for="phone" class="form-label mb-1 small">Owner Phone</label>
                                    <input type="tel" class="form-control form-control-sm form-input-hover" id="phone" name="owner_phone" placeholder="08012345678" required>
                                    <span id="phoneFeedback" class="small text-danger"></span>
                                </div>
                                <div class="col-md-12 mb-2 mt-3">
                                    <input type="text" class="form-control form-control-sm" id="pass-code-holder" placeholder="Pass Code: " disabled>
                                </div>
                                <div class="col-md-6 d-grid d-md-flex mt-3">
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        Register Vehicle
                                    </button>
                                </div>
                                <div class="col-auto d-grid d-md-flex mt-2 mt-md-3">
                                    <button type="button" class="btn btn-light btn-sm border shadow-sm ms-auto w-100 d-flex align-items-center justify-content-center" id="print-pass-code-button">
                                        <i class="bi bi-printer-fill me-2"></i>
                                        Print Pass Code
                                    </button>
                                </div>
                                <div class="col-md-2 d-grid d-md-flex ms-auto mt-2 mt-md-3">
                                    <a href="manager_dashboard.php" class="btn btn-light btn-sm border shadow-sm ms-auto w-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-house-fill me-2"></i>
                                        Home
                                    </a>
                                </div>
                            </div>
                        </form>
                        <!-- Print Pass Code -->
                        <div class="container-fluid d-none d-print-block printable-container">
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
                                                <td id="print-pass-code-holder"></td>
                                            </tr>
                                            <tr>
                                                <th class="w-25">Vehicle Type</th>
                                                <td id="print-vehicle-type-holder"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Main End -->
        </div>
    </main>
    <!-- Body End -->

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="bootstrap/js/jquery-3.7.1.js"></script>
    <script src="bootstrap/js/sweetalert2@11.js"></script>
    <script src="script/timeoutLogoutUser.js"></script>
    <script>
        $(document).ready(function() {
            // Print the gate pass
            $('#print-pass-code-button').click(function() {
                window.print();
            });

            // Function to validate form fields
            function validateForm() {
                let phone = $('#phone').val().trim();
                let phoneFeedback = $('#phoneFeedback');
                let plateNumber = $('#plateNumber').val().trim();
                let plateNumberFeedback = $('#plateNumberFeedback');
                let ownerName = $('#ownerName').val().trim();
                let ownerNameFeedback = $('#ownerNameFeedback');

                const phonePattern = /^0[789][01]\d{8}$/;
                const plateNumberPattern = /^[A-Za-z]{3}[0-9]{3}[A-Za-z]{2}$/;
                const ownerNamePattern = /^[a-zA-Z\s'\-.]+$/;

                let isValid = true;

                // Validate Phone Number
                if (!phonePattern.test(phone)) {
                    phoneFeedback.text("Please enter a valid phone number.");
                    isValid = false;
                } else {
                    phoneFeedback.text("");
                }

                // Validate Plate Number
                if (!plateNumberPattern.test(plateNumber)) {
                    plateNumberFeedback.text("Please enter a valid plate number.");
                    isValid = false;
                } else {
                    plateNumberFeedback.text("");
                }

                // Validate Owner Name
                if (!ownerNamePattern.test(ownerName)) {
                    ownerNameFeedback.text("Please enter a valid name.");
                    isValid = false;
                } else {
                    ownerNameFeedback.text("");
                }

                return isValid;
            }

            // Add input event listener for real-time validation
            $('#myForm').on('input', validateForm);

            $('#myForm').submit(function(e) {
                e.preventDefault();

                if (validateForm()) {
                    let plateNumber = $('#plateNumber').val().trim();
                    let vehicleType = $('#vehicleType').val().trim();
                    let ownerName = $('#ownerName').val().trim();
                    let phone = $('#phone').val().trim();

                    $.ajax({
                        url: 'logic/register_vehicle_logic.php',
                        method: 'POST',
                        data: {
                            action: 'registerVehicle',
                            plateNumber: plateNumber,
                            vehicleType: vehicleType,
                            ownerName: ownerName,
                            phone: phone
                        },
                        success: function(response) {
                            let data = JSON.parse(response);

                            if (data.success) {
                                // Erase or Clear the form inputs
                                $('#plateNumber').val('');
                                $('#vehicleType').val('');
                                $('#ownerName').val('');
                                $('#phone').val('');

                                // The disabled text field to display pass code
                                $('#pass-code-holder').val(`Pass Code: ${data.pass_code}`);

                                // Populate the print table with data
                                $('#print-pass-code-holder').html(data.pass_code);
                                $('#print-vehicle-type-holder').html(data.vehicle_type);

                                Swal.fire({
                                    title: 'Success!',
                                    text: data.success,
                                    icon: 'success',
                                    showCloseButton: true,
                                    showConfirmButton: false,
                                    timer: 1500,
                                })
                            } else if (data.errors.length > 0) {
                                let output = '';
                                data.errors.forEach(function(error) {
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
                    });
                }
            });
        });
    </script>

</body>

</html>