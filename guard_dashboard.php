<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Guard') {
    header("Location: index.php"); // Redirect users who are not logged in or not Security Guard
    exit();
}

//  Function to format date
function formatDateTimeToCustom12Hour($datetime_string)
{
    $datetime = new DateTime($datetime_string);
    return $datetime->format('j-n-Y, g:i A');
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
    <title>Guard Dashboard</title>
</head>

<body class="text-bg-light">

    <!-- Navbar -->
    <?php

    $dashboard = "custom-active";
    $generateGatePass = "";
    $verifyGatePass = "";

    require "require/guard_navbar.php";

    ?>
    <!-- Navbar End -->

    <!-- Body -->
    <main class="container-fluid">
        <div class="row custom-background-image">
            <!-- Sidebar -->
            <?php

            $dashboard = "active";
            $generateGatePass = "";
            $verifyGatePass = "";

            require "require/guard_sidebar.php";

            ?>
            <!-- Sidebar End -->

            <!-- Main -->
            <div class="col-lg-9 min-vh-100 after-navbar-space offset-lg-2 mb-3 mb-md-0" id="main">
                <!-- Overview Row -->
                <div class="row justify-content-center text-center rounded m-1 mt-2 pb-2 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                    <h5 class="text-start mb-3 mt-1 dashboard-header">
                        Overview
                    </h5>
                    <!-- Generate Gate Pass Card -->
                    <div class="col-md-6 mb-1 mb-md-0">
                        <div class="card shadow-sm text-bg-success text-light">
                            <div class="card-body p-1">
                                <div class="row align-items-center justify-content-center">
                                    <div class="col-4 flex-shrink-0">
                                        <img src="images/access-card-fill-2.png" alt="Car Front" class="img-fluid rounded image-color-white" id="custom-color">
                                    </div>
                                    <div class="col-8 flex-grow-1">
                                        <span class="d-block fs-2 fw-medium">
                                            <?php
                                            // Import the database connection file
                                            require 'require/db.php';

                                            // SQL query to count the total number of vehicles
                                            $sql = "SELECT COUNT(*) AS total_gate_passes FROM gate_passes";
                                            $result = $conn->query($sql);

                                            // Fetch the result
                                            $total_gate_passes = 0;
                                            if ($result->num_rows > 0) {
                                                $row = $result->fetch_assoc();
                                                $total_gate_passes = $row['total_gate_passes'];
                                            }

                                            echo $total_gate_passes;

                                            // // Close connection
                                            // $conn->close();
                                            ?>
                                        </span>
                                        <span class="small">Gate Passes Issued</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="generate_gate_pass.php" class="link-underline-light link-offset-2-hover link-underline-opacity-0 link-underline-opacity-100-hover text-light small">
                                    Generate Gate Pass
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Verify Gate Pass Card-->
                    <div class="col-md-6">
                        <div class="card shadow-sm text-bg-success text-light">
                            <div class="card-body p-1">
                                <div class="row align-items-center justify-content-center">
                                    <div class="col-4 flex-shrink-0">
                                        <img src="images/cars-fill-2.png" alt="Car Front" class="img-fluid rounded image-color-white">
                                    </div>
                                    <div class="col-8 flex-grow-1">
                                        <span class="d-block fs-2 fw-medium">
                                            <?php
                                            // SQL query to count the total number of vehicles
                                            $sql = "SELECT COUNT(*) AS total_valid_gate_passes FROM gate_passes WHERE verification_status = 'Valid'";
                                            $result = $conn->query($sql);

                                            // Fetch the result
                                            $total_valid_gate_passes = 0;
                                            if ($result->num_rows > 0) {
                                                $row = $result->fetch_assoc();
                                                $total_valid_gate_passes = $row['total_valid_gate_passes'];
                                            }

                                            echo $total_valid_gate_passes;

                                            // Close connection
                                            // $conn->close();
                                            ?>
                                        </span>
                                        <span class="small">Vehicles Currently IN</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="verify_gate_pass.php" class="link-underline-light link-offset-2-hover link-underline-opacity-0 link-underline-opacity-100-hover text-light small">
                                    Verify Gate Pass
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Gate Passes -->
                <div class="row justify-content-center text-center rounded m-1 mt-4 mt-md-4 pb-0 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                    <h6 class="text-start mb-3 mt-1 dashboard-header d-flex justify-content-between">
                        <span>
                            Recent Gate Passes
                        </span>
                        <a href="generate_gate_pass.php" class="link-success link-underline-opacity-0 link-underline-opacity-100-hover fs-6 pe-2 link-offset-1-hover">
                            <span class="small">View all</span>
                        </a>
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-success table-hover small shadow-sm">
                            <thead>
                                <tr>
                                    <th class="fw-medium">SN</th>
                                    <th class="fw-medium">Pass Code</td>
                                    <th class="fw-medium">Plate Number</th>
                                    <th class="fw-medium">Vehicle Type</th>
                                    <th class="fw-medium">Issued By</th>
                                    <th class="fw-medium">Issued Date</th>
                                    <th class="fw-medium">Verification Status</th>
                                </tr>
                            </thead>
                            <?php
                            // SQL query to fetch 5 most recently registered vehicles based on auto-incremented vehicle_id
                            $sql = "SELECT * FROM gate_passes ORDER BY pass_id DESC LIMIT 5";
                            $result = $conn->query($sql);

                            // Check for errors
                            if (!$result) {
                                die("Query failed: " . $conn->error);
                            }

                            // Close connection
                            // $conn->close();
                            ?>
                            <tbody class="table-group-divider">
                                <?php if ($result->num_rows > 0) : ?>
                                    <?php $sn = 1; ?>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($sn++); ?></td>
                                            <td><?php echo htmlspecialchars($row['pass_code']); ?></td>
                                            <td><?php echo htmlspecialchars($row['plate_number']); ?></td>
                                            <td><?php echo htmlspecialchars($row['vehicle_type']); ?></td>
                                            <td><?php echo htmlspecialchars($row['issued_by']); ?></td>
                                            <td><?php echo formatDateTimeToCustom12Hour(htmlspecialchars($row['issued_date'])); ?></td>
                                            <td class="ps-5 text-start">
                                                <?php
                                                if (htmlspecialchars($row['verification_status']) == 'Valid') {
                                                    echo "<span class='text-success'><i class='bi bi-circle-fill me-2' style='font-size: 0.3rem;'></i>IN</span>";
                                                } else {
                                                    echo "<span class='text-danger'><i class='bi bi-circle-fill me-2' style='font-size: 0.3rem;'></i>OUT</span>";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7">No gate pass found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Gate Recent Activities -->
                <div class="row justify-content-center text-center rounded m-1 mt-4 mb-5 mt-md-4 pb-0 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                    <h6 class="text-start mb-3 mt-1 dashboard-header d-flex justify-content-between">
                        <span>
                            Recent Gate Activities
                        </span>
                        <a href="verify_gate_pass.php" class="link-success link-underline-opacity-0 link-underline-opacity-100-hover fs-6 pe-2 link-offset-1-hover">
                            <span class="small">View all</span>
                        </a>
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-success table-hover small shadow-sm">
                            <thead>
                                <tr>
                                    <th class="fw-medium">SN</th>
                                    <th class="fw-medium">Plate Number</td>
                                    <th class="fw-medium">Vehicle Type</th>
                                    <th class="fw-medium">Signed-In By</th>
                                    <th class="fw-medium">Signed-In Time</th>
                                    <th class="fw-medium">Signed-Out By</th>
                                    <th class="fw-medium">Signed-Out By Time</th>
                                </tr>
                            </thead>
                            <?php
                            // SQL query to fetch 3 most recently registered vehicles based on auto-incremented vehicle_id
                            $sql = "SELECT * FROM gate_activities ORDER BY activity_id DESC LIMIT 5";
                            $result = $conn->query($sql);

                            // Check for errors
                            if (!$result) {
                                die("Query failed: " . $conn->error);
                            }

                            // Close connection
                            $conn->close();
                            ?>
                            <tbody class="table-group-divider">
                                <?php if ($result->num_rows > 0) : ?>
                                    <?php $sn = 1; ?>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($sn++); ?></td>
                                            <td><?php echo htmlspecialchars($row['plate_number']); ?></td>
                                            <td><?php echo htmlspecialchars($row['vehicle_type']); ?></td>
                                            <td><?php echo htmlspecialchars($row['issued_by']); ?></td>
                                            <td class="text-success fw-medium"><?php echo formatDateTimeToCustom12Hour(htmlspecialchars($row['issued_time'])); ?></td>
                                            <td><?php echo htmlspecialchars($row['signed_out_by']); ?></td>
                                            <td class="text-danger fw-medium"><?php echo formatDateTimeToCustom12Hour(htmlspecialchars($row['signed_out_time'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7">No gate activity found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Main End -->
        </div>
    </main>
    <!-- Body End -->

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script/timeoutLogoutUser.js"></script>

</body>

</html>