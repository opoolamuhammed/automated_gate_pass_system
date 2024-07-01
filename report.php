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
    <link rel="stylesheet" href="styles/print-style.css">
    <title>Report</title>
</head>

<body class="text-bg-light">

    <!-- Navbar -->
    <?php

    $dashboard = "";
    $vehicleManagement = "";
    $manageUser = "";
    $report = "active";

    require 'require/manager_navbar.php';

    ?>
    <!-- Navbar End -->

    <!-- Body -->
    <main class="container-fluid">
        <div class="row custom-background-image">
            <!-- Sidebar -->
            <?php

            $registerVehicle = "";
            $manageVehicle = "";
            $show = "";

            require 'require/manager_sidebar.php';

            ?>
            <!-- Sidebar End -->

            <!-- Main -->
            <div class="col-lg-9 min-vh-100 after-navbar-space offset-lg-2" id="main">
                <!-- Report -->
                <div class="row justify-content-center">
                    <div class="col-md-12 rounded m-1 p-4 pb-3 p-md-3 pt-1 mb-2 pb-md-1 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                        <!-- Header and Search Field -->
                        <div class="row d-flex justify-content-between mt-1 mb-3">
                            <div class="col-auto">
                                <h5 class="text-start fw-normal">
                                    Gate Activities Report
                                </h5>
                            </div>
                            <div class="col-auto">
                                <form role="search" id="searchForm">
                                    <div class="input-group input-group-sm">
                                        <input type="search" class="form-control form-control-sm search-input-radius px-3" id="search-report" placeholder="Search plate number" autofocus>
                                        <button class="disabled btn btn-sm bg-white text-dark border border-start-0 search-button-radius px-3">
                                            <i class="bi bi-search text-success"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 col-md-auto">
                                <a href="#createNewReportModal" role="button" class="add-user-button btn btn-success border-0 btn-sm rounded-3 d-flex align-items-center justify-content-center mt-1 mt-md-0" data-bs-toggle="modal">
                                    <i class="bi bi-journal-check me-1 me-md-2"></i>
                                    <span>Create New Report</span>
                                </a>
                            </div>
                        </div>

                        <!-- Report List -->
                        <div class="table-responsive d-print-block printable-container ms-auto overflow-hidden">
                            <!-- Print Header -->
                            <div class="d-none d-print-flex navbar-brand align-items-center justify-content-center me-5 mb-3">
                                <img src="images/fug-logo.png" alt="Navbar Image" class="img-fluid rounded custom-navbar-image me-3">
                                <h5 class="fw-medium text-success m-0 p-0">
                                    <span>Federal University Gusau</span>
                                    <p class="fs-6 m-0 p-0 fw-normal small">
                                        Knowledge, Innovation and Service
                                    </p>
                                </h5>
                            </div>
                            <table class="table table-hover small shadow-sm d-print-table">
                                <thead>
                                    <tr class="table-secondary d-print-table-row">
                                        <th class="fw-medium d-print-table-cell">SN</th>
                                        <th class="fw-medium d-print-table-cell">Plate Number</td>
                                        <th class="fw-medium d-print-table-cell">Vehicle Type</th>
                                        <th class="fw-medium d-print-table-cell">Signed-In By</th>
                                        <th class="fw-medium d-print-table-cell">Signed-In Time</th>
                                        <th class="fw-medium d-print-table-cell">Signed-Out By</th>
                                        <th class="fw-medium d-print-table-cell">Signed-Out Time</th>
                                    </tr>
                                </thead>
                                <tbody class=" table-group-divider" id="report-list">
                                </tbody>
                            </table>
                        </div>

                        <!-- Print Report Button -->
                        <div class="p-1 d-flex justify-content-end mb-1">
                            <a role="button" type="button" id="printReportButton" class="btn btn-sm px-5 px-md-2 d-md-inline-flex btn-light border border-1 shadow-sm text-center align-items-center justify-content-center">
                                <i class="bi bi-printer-fill me-2"></i>
                                Print Report
                            </a>
                        </div>
                    </div>

                    <!-- Pagination and Home Button -->
                    <?php
                    require 'require\db.php';

                    $rows_per_page = 20;

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

    <!-- Create New Report -->
    <div class="modal fade" id="createNewReportModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered small">
            <div class="modal-content">
                <form id="createNewReportForm">
                    <div class="modal-header py-2">
                        <h5 class="fw-light">Custom Report</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="startDate" class="form-label mb-1">From</label>
                                <input type="date" class="form-control form-control-sm form-input-hover" id="startDate" required>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="endDate" class="form-label mb-1">To</label>
                                <input type="date" class="form-control form-control-sm form-input-hover" id="endDate" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-1">
                        <button type="button" class="btn btn-sm btn-danger me-1 px-5 px-md-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-sm btn-success px-5 px-md-4">
                            Generate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Create New Report End -->

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script/print-scipt.js"></script>
    <script src="script/timeoutLogoutUser.js"></script>
    <script src="bootstrap/js/jquery-3.7.1.js"></script>
    <script src="bootstrap/js/sweetalert2@11.js"></script>
    <script>
        // Function to fetch the gate activities from the database
        function fetchGateActivities() {
            $.ajax({
                url: 'logic/report_logic.php',
                method: 'POST',
                data: {
                    action: 'fetchGateActivities'
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.errors);
                    $('#report-list').html(response.data);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error); // Log any errors to console
                }
            });
        }

        $(document).ready(function() {
            // Call the function to fetch the gate activities.
            fetchGateActivities();

            // Creating new report on submitting the form
            $('#createNewReportForm').submit(function(e) {
                e.preventDefault(); // Prevent default behavior of form reloading when submitted

                let startDate = $('#startDate').val();
                let endDate = $('#endDate').val();

                let data = {
                    action: 'createNewReport',
                    startDate: startDate,
                    endDate: endDate,
                };

                $.ajax({
                    type: 'POST',
                    url: 'logic/report_logic.php',
                    data: data,
                    dataType: 'json',
                    success: function(data) {
                        $('#report-list').html(data.data);
                        if (data.success) {
                            $('#createNewReportModal').modal('hide');
                            Swal.fire({
                                title: 'Success!',
                                text: data.success,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500,
                            });
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
                    }
                });
            });

            // Search for gate activity plate number on key press
            $('#search-report').keyup(function(e) {
                e.preventDefault();

                let plateNumber = $('#search-report').val().trim();
                let action = 'searchGateActivity';
                if (plateNumber.length <= 0) {
                    fetchGateActivities();
                }
                $.ajax({
                    url: 'logic/report_logic.php',
                    method: 'POST',
                    data: {
                        plateNumber: plateNumber,
                        action: action
                    },
                    dataType: 'json',
                    success: function(response) {
                        // console.log(response);
                        $('#report-list').html(response.data);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error); // Log any errors to console
                    }
                });
            });

        });
    </script>

</body>

</html>