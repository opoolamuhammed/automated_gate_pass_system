<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    header("Location: index.php"); // Redirect users who are not logged in or not Security Manager
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
    <link rel="stylesheet" href="styles/manager-style.css">
    <title>Manage Vehicle</title>
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
            $registerVehicle = "";
            $manageVehicle = "fw-medium";
            $show = "show";
            require 'require/manager_sidebar.php';
            ?>
            <!-- Sidebar End -->

            <!-- Main -->
            <div class="col-lg-9 min-vh-100 after-navbar-space offset-lg-2" id="main">
                <!-- Manage Vehicle -->
                <div class="row justify-content-center">
                    <div class="col-md-12 rounded m-1 p-4 p-md-3 pt-1 mb-2 pb-md-0 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                        <!-- Header and Search Field -->
                        <div class="d-flex justify-content-between mt-1 mb-3">
                            <h5 class="text-start fw-normal">
                                Vehicle Management
                            </h5>
                            <form role="search">
                                <div class="input-group input-group-sm">
                                    <input type="search" id="search-plate-number" class="form-control form-control-sm search-input-radius px-3" placeholder="Search by Plate Number" autofocus>
                                    <button class="disabled btn btn-sm bg-white text-dark border border-start-0 search-button-radius px-3">
                                        <i class="bi bi-search text-success"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- List of Registered Vehicle -->
                        <div class="table-responsive">
                            <table class="table table-hover small shadow-sm">
                                <thead>
                                    <tr class="table-secondary">
                                        <th class="fw-medium">SN</th>
                                        <th class="fw-medium">Plate Number</th>
                                        <th class="fw-medium">Vehicle Type</td>
                                        <th class="fw-medium">Owner Name</th>
                                        <th class="fw-medium">Pass Code</th>
                                        <th class="fw-medium">Details</th>
                                        <th class="fw-medium">Edit</th>
                                        <th class="fw-medium">Delete</th>
                                    </tr>
                                </thead>
                                <tbody class=" table-group-divider" id="vehicle-list">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination and Home Button -->
                    <?php
                    require 'require\db.php';

                    $rows_per_page = 20;

                    // Getting the total number of records (pages)
                    $sql = "SELECT * FROM vehicles";
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

                    require "require\pagination.php";

                    $conn->close();
                    ?>
                </div>
            </div>
            <!-- Main End -->
        </div>
    </main>
    <!-- Body End -->

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal">
        <div class="modal-dialog modal-dialog-centered small">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="fw-light">Vehicle Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-end">
                    <table class="table table-sm text-start align-middle mb-0">
                        <tr>
                            <th class="w-50">Vehicle ID</th>
                            <td id="vehicle_id"></td>
                        </tr>
                        <tr>
                            <th class="w-50">Plate Number</th>
                            <td id="plate_number"></td>
                        </tr>
                        <tr>
                            <th class="w-50">Pass Code</th>
                            <td id="pass_coce"></td>
                        </tr>
                        <tr>
                            <th class="w-50">Vehicle Type</th>
                            <td id="vehicle_type"></td>
                        </tr>
                        <tr>
                            <th class="w-50">Owner Name</th>
                            <td id="owner_name"></td>
                        </tr>
                        <tr>
                            <th class="w-50">Owner Phone</th>
                            <td id="owner_phone"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer p-1">
                    <button class="btn btn-danger btn-sm px-5 px-md-4" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Details Modal End -->

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered small">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="fw-light">Edit Vehicle Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="myForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="plateNumber" class="form-label mb-1">Plate Number</label>
                                <input type="text" class="form-control form-control-sm form-input-hover" name="plate_number" id="plateNumber" placeholder="ABC123DE" required>
                                <span id="plateNumberFeedback" class="text-danger"></span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="vehicleType" class="form-label mb-1">Vehicle Type</label>
                                <select class="form-select form-select-sm form-input-hover" id="vehicleType" name="vehicle_type" required>
                                    <option value="" class="select-options-font-size">Choose Vehicle Type</option>
                                    <option value="Car" class="select-options-font-size">Car</option>
                                    <option value="SUV" class="select-options-font-size">SUV</option>
                                    <option value="Bus" class="select-options-font-size">Bus</option>
                                    <option value="Truck" class="select-options-font-size">Truck</option>
                                    <option value="Van" class="select-options-font-size">Van</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="ownerName" class="form-label mb-1">Owner Name</label>
                                <input type="text" class="form-control form-control-sm form-input-hover" name="owner_name" id="ownerName" placeholder="John Doe" required>
                                <span id="ownerNameFeedback" class=" text-danger"></span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="phone" class="form-label mb-1">Owner Phone</label>
                                <input type="tel" class="form-control form-control-sm form-input-hover" name="owner_phone" id="phone" placeholder="08012345678" required>
                                <span id="phoneFeedback" class="text-danger"></span>
                                <span class="text-danger" id="errorsFeedback"></span>
                                <span class="text-success" id="successFeedback"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-1">
                        <button type="button" class="btn btn-danger btn-sm px-5 px-md-4" data-bs-dismiss="modal" onclick="fetchVehicles()">
                            Close
                        </button>
                        <button type="submit" class="btn btn-sm btn-success me-1 px-5 px-md-4">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Edit Modal End -->

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog modal-dialog-centered small">
            <div class="modal-content">
                <form id="deleteVehicleForm">
                    <div class="modal-header py-2">
                        <h5 class="fw-light">Are you sure you want to delete this vehicle?</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="fetchVehicles()"></button>
                    </div>
                    <div class="modal-body text-end p-2">
                        <button type="button" class="btn btn-sm btn-danger me-1 px-4 px-md-2" data-bs-dismiss="modal" onclick="fetchVehicles()">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-success btn-sm px-5 px-md-3">
                            Continue
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete Modal End -->

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="bootstrap/js/jquery-3.7.1.js"></script>
    <script src="bootstrap/js/sweetalert2@11.js"></script>
    <script src="script/timeoutLogoutUser.js"></script>

    <script>
        // Displaying list of vehicles in the manage vehicle
        function fetchVehicles() {
            $.ajax({
                url: 'logic/fetch_vehicles.php',
                method: 'POST',
                data: {
                    action: 'fetchVehicles'
                },
                success: function(response) {
                    $('#vehicle-list').html(response);
                }
            });
        }

        $('document').ready(function() {

            fetchVehicles();

            // Search vehicle by by platenumber on key press
            $('#search-plate-number').keyup(function(e) {
                e.preventDefault();

                let plateNumber = $('#search-plate-number').val().trim();
                let action = 'searchPlateNumber';
                if (plateNumber.length <= 0) {
                    fetchVehicles();
                }
                $.ajax({
                    url: 'logic/fetch_vehicles.php',
                    method: 'POST',
                    data: {
                        plateNumber: plateNumber,
                        action: action
                    },
                    success: function(response) {
                        $('#vehicle-list').html(response);
                    }
                });
            });

        });

        //  Get vehicle details
        function getVehicleDetails(vehicle_id) {
            let xhr = new XMLHttpRequest();

            xhr.open('GET', 'logic/vehicle_details.php?id=' + vehicle_id, true);

            xhr.onload = function() {
                if (this.status == 200) {
                    let vehicleDetails = JSON.parse(this.responseText);

                    document.getElementById('vehicle_id').innerHTML = vehicleDetails.vehicle_id;
                    document.getElementById('plate_number').innerHTML = vehicleDetails.plate_number;
                    document.getElementById('vehicle_type').innerHTML = vehicleDetails.vehicle_type;
                    document.getElementById('owner_name').innerHTML = vehicleDetails.owner_name;
                    document.getElementById('owner_phone').innerHTML = vehicleDetails.owner_phone;
                    document.getElementById('pass_coce').innerHTML = vehicleDetails.pass_code;
                }
            }

            xhr.send();
        }

        // Edit vehicle details function
        function editVehicle(vehicle_id) {
            // Perform a GET request to retrieve vehicle details
            $.ajax({
                url: 'logic/vehicle_details.php',
                type: 'GET',
                data: {
                    id: vehicle_id
                },
                dataType: 'json',
                success: function(vehicleDetails) {
                    $('#plateNumber').val(vehicleDetails.plate_number);
                    $('#vehicleType').val(vehicleDetails.vehicle_type);
                    $('#ownerName').val(vehicleDetails.owner_name);
                    $('#phone').val(vehicleDetails.owner_phone);
                }
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

            // Function to update vehicle details using the vehicle_id sent on clicking the edit button
            $('#myForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default behavior of form reloading when submitted

                if (validateForm()) {
                    let action = 'updateVehicle';
                    let plateNumber = $('#plateNumber').val().trim();
                    let vehicleType = $('#vehicleType').val().trim();
                    let ownerName = $('#ownerName').val().trim();
                    let ownerPhone = $('#phone').val().trim();

                    let params = {
                        vehicle_id: vehicle_id,
                        plate_number: plateNumber,
                        vehicle_type: vehicleType,
                        owner_name: ownerName,
                        owner_phone: ownerPhone,
                        action: action
                    };

                    // Perform a POST request to update vehicle details
                    $.ajax({
                        url: 'logic/register_vehicle_logic.php',
                        type: 'POST',
                        data: params,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#editModal').modal('hide');
                                fetchVehicles();
                                Swal.fire({
                                    title: 'Sucess!',
                                    text: response.success,
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                    confirmButtonColor: '#198754',
                                });
                            } else if (response.errors.length > 0) {
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
                        }
                    });
                }
            });
        }

        // Manual refresh to fix the problem of vehicle_id not chaning when close button is clicked
        function refreshOnClose() {
            fetchVehicles();
        }

        // Delete Vehicle 
        function deleteVehicle(vehicle_id) {
            $('#deleteVehicleForm').on('submit', function(e) {
                e.preventDefault();

                let action = 'deleteVehicle';
                let params = {
                    action: action,
                    vehicle_id: vehicle_id
                };

                $.ajax({
                    url: 'logic/delete_vehicle_logic.php',
                    type: 'POST',
                    data: params,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#deleteModal').modal('hide');
                            fetchVehicles();
                            Swal.fire({
                                title: 'Sucess!',
                                text: response.success,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#198754',
                            });
                        } else if (response.errors.length > 0) {
                            let output = '';
                            response.errors.forEach(function(error) {
                                output += error + "\n";
                            });
                            fetchVehicles();
                            Swal.fire({
                                title: 'Error!',
                                text: `${output}`,
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#198754',
                            });
                        }
                    }
                });
            });
        }
    </script>

</body>

</html>