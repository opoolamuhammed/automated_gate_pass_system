<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    header("Location: index.php"); // Redirect users who are not logged in or not Security Manager
    exit();
}

//  Function to format date
function formatDateTimeToCustom12Hour($datetime_string) {
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
    <link rel="stylesheet" href="styles/manager-style.css">
    <title>Manager Dashboard</title>
</head>
<body class="text-bg-light">

<!-- Navbar -->
<?php

    $dashboard = "active";
    $vehicleManagement = "";
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
            $manageVehicle = "";
            $show = "";

            require 'require/manager_sidebar.php';

        ?>
        <!-- Sidebar End -->

        <!-- Main -->
        <div class="col-lg-9 min-vh-100 after-navbar-space offset-lg-2 mb-3 mb-md-0" id="main">
            <!-- Overview Row -->
            <div class="row justify-content-center text-center rounded m-1 mt-2 pb-2 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                <h5 class="text-start mb-3 mt-1 dashboard-header">
                    <span class="">
                        Overview    
                    </span>
                </h5>
                <!-- Manage Vehicles Card -->
                <div class="col-md-4 mb-1 mb-md-0">
                    <div class="card shadow-sm text-bg-success text-light">
                        <div class="card-body p-1">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-4 flex-shrink-0">
                                    <img src="images/cars-fill.png" alt="Car Front" class="img-fluid rounded image-color-white">
                                </div>
                                <div class="col-8 flex-grow-1">
                                    <span class="d-block fs-2 fw-medium">
                                        <?php
                                            // Import the database connection file
                                            require 'require/db.php';

                                            // SQL query to count the total number of vehicles
                                            $sql = "SELECT COUNT(*) AS total_vehicles FROM vehicles";
                                            $result = $conn->query($sql);

                                            // Fetch the result
                                            $total_vehicles = 0;
                                            if ($result->num_rows > 0) {
                                                $row = $result->fetch_assoc();
                                                $total_vehicles = $row['total_vehicles'];
                                            }

                                            echo $total_vehicles;

                                            // Close connection
                                            // $conn->close();
                                        ?>
                                    </span>
                                    <span class="small">Registered Vehicles</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="manage_vehicle.php" class="link-underline-light link-offset-2-hover link-underline-opacity-0 link-underline-opacity-100-hover text-light small">
                                Manage Vehicles
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Manage User Card -->
                <div class="col-md-4 mb-1 mb-md-0">
                    <div class="card shadow-sm text-bg-success text-light">
                        <div class="card-body p-1">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-4 flex-shrink-0">
                                    <img src="images/people-fill.png" alt="Car Front" class="img-fluid rounded image-color-white">
                                </div>
                                <div class="col-8 flex-grow-1">
                                    <span class="d-block fs-2 fw-medium">
                                    <?php
                                            // Import the database connection file
                                            // require 'require/db.php';

                                            // SQL query to count the total number of users
                                            $sql = "SELECT COUNT(*) AS total_users FROM users";
                                            $result = $conn->query($sql);

                                            // Fetch the result
                                            $total_users = 0;
                                            if ($result->num_rows > 0) {
                                                $row = $result->fetch_assoc();
                                                $total_users = $row['total_users'];
                                            }

                                            echo $total_users;

                                            // Close connection
                                            // $conn->close();
                                        ?>
                                    </span>
                                    <span class="small">Registered Users</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="manage_user.php" class="link-underline-light link-offset-2-hover link-underline-opacity-0 link-underline-opacity-100-hover text-light small">
                                Manage Users
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Generate Report Card -->
                <div class="col-md-4">
                    <div class="card shadow-sm text-bg-success text-light">
                        <div class="card-body p-1">
                            <div class="row align-items-center justify-content-center">
                                <div class="col-4 flex-shrink-0">
                                    <img src="images/access-card-fill.png" alt="Car Front" class="img-fluid rounded image-color-white" id="custom-color">
                                </div>
                                <div class="col-8 flex-grow-1">
                                    <span class="d-block fs-2 fw-medium">
                                    <?php
                                        // Import the database connection file
                                        // require 'require/db.php';

                                        // SQL query to count the total number of vehicles
                                        $sql = "SELECT COUNT(*) AS total_gate_activities FROM gate_activities";
                                        $result = $conn->query($sql);

                                        // Fetch the result
                                        $total_gate_activities = 0;
                                        if ($result->num_rows > 0) {
                                            $row = $result->fetch_assoc();
                                            $total_gate_activities = $row['total_gate_activities'];
                                        }

                                        echo $total_gate_activities;

                                        // // Close connection
                                        // $conn->close();
                                    ?>
                                    </span>
                                    <span class="small">Vehicles Signed Out</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="report.php" class="link-underline-light link-offset-2-hover link-underline-opacity-0 link-underline-opacity-100-hover text-light small">
                                Generate Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registered Vehicle Table -->
            <div class="row justify-content-center text-center rounded m-1 mt-4 mt-md-4 pb-0 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                <h6 class="text-start mb-3 mt-1 dashboard-header d-flex justify-content-between">
                    <span class="">
                        Recently Registered Vehicles
                    </span>
                    <a href="manage_vehicle.php" class="link-success link-underline-opacity-0 link-underline-opacity-100-hover fs-6 pe-2 link-offset-1-hover">
                        <span class="small">View all</span>
                    </a>
                </h6>
                <div class="table-responsive">
                    <table class="table table-success table-hover small shadow-sm">
                        <thead class="table-success">
                            <tr>
                                <th class="fw-medium">SN</th>
                                <th class="fw-medium">Plate Number</th>
                                <th class="fw-medium">Vehicle Type</td>
                                <th class="fw-medium">Owner Name</th>
                                <th class="fw-medium">Owner Contact</th>
                            </tr>
                        </thead>
                        <?php
                            // Import the database connection file
                            // require 'require/db.php';
                            
                            // SQL query to fetch 3 most recently registered vehicles based on auto-incremented vehicle_id
                            $sql = "SELECT plate_number, vehicle_type, owner_name, owner_phone FROM vehicles ORDER BY vehicle_id DESC LIMIT 5";
                            $result = $conn->query($sql);

                            // Check for errors
                            if (!$result) {
                                die("Query failed: " . $conn->error);
                            }

                            // Close connection
                            // $conn->close();
                        ?>
                        <tbody class=" table-group-divider">
                            <?php if ($result->num_rows > 0): ?>
                                <?php $sn = 1; ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($sn++); ?></td>
                                        <td><?php echo htmlspecialchars($row['plate_number']); ?></td>
                                        <td><?php echo htmlspecialchars($row['vehicle_type']); ?></td>
                                        <td><?php echo htmlspecialchars($row['owner_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['owner_phone']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5">No vehicles found</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Registered Users Table -->
            <div class="row justify-content-center text-center rounded m-1 mt-4 mt-md-4 pb-0 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">
                <h6 class="text-start mb-3 mt-1 dashboard-header d-flex justify-content-between">
                    <span class="">
                        Recently Registered Users
                    </span>
                    <a href="manage_user.php" class="small link-success link-underline-opacity-0 link-underline-opacity-100-hover fs-6 pe-2 link-offset-1-hover">
                        <span class="small">View all</span>
                    </a>
                </h6>
                <div class="table-responsive">
                    <table class="table table-success table-hover small shadow-sm">
                        <thead class="table-success">
                            <tr>
                                <th class="fw-medium">SN</th>
                                <th class="fw-medium">Username</th>
                                <th class="fw-medium">First Name</td>
                                <th class="fw-medium">Last Name</th>
                                <th class="fw-medium">Role</th>
                                <th class="fw-medium">Phone Number</th>
                            </tr>
                        </thead>
                        <?php
                            // Import the database connection file
                            // require 'require/db.php';
                            
                            // SQL query to fetch 3 most recently registered users based on auto-incremented vehicle_id
                            $sql = "SELECT username, first_name, last_name, role, phone_number FROM users ORDER BY user_id DESC LIMIT 5";
                            $result = $conn->query($sql);

                            // Check for errors
                            if (!$result) {
                                die("Query failed: " . $conn->error);
                            }

                            // Close connection
                            // $conn->close();
                        ?>
                        <tbody class=" table-group-divider">
                            <?php if ($result->num_rows > 0): ?>
                                <?php $sn = 1; ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($sn++); ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                                        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="7">No user found</td></tr>
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
                    <a href="report.php" class="link-success link-underline-opacity-0 link-underline-opacity-100-hover fs-6 pe-2 link-offset-1-hover">
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
                                <th class="fw-medium">Signed-Out Time</th>
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
                            <?php if ($result->num_rows > 0): ?>
                                <?php $sn = 1; ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
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
                            <?php else: ?>
                                <tr><td colspan="7">No gate activity found</td></tr>
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