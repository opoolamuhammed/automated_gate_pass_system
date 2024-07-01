<?php

// $registerVehicle = "";
// $manageVehicle = "";
// $show = "";

?>

<!-- Sidebar -->
<div class="col-lg-3 text-bg-light min-vh-100 d-none d-lg-block border border-end shadow-sm ps-3 after-navbar-space position-fixed" id="sidebar">
            <p class="fw-light text-secondary small mb-1 mt-1">MAIN NAVIGATION</p>
            <div>
                <!-- Nav items list -->
                <ul class="navbar navbar-nav nav-pills d-flex flex-column align-items-start">
                    <li class="nav-item w-100 ps-2 mb-1 hover clicked small fw-medium">
                        <a href="manager_dashboard.php" class="nav-link text-dark">
                            <i class="bi bi-house-fill me-1"></i>
                            <span class="d-none d-lg-inline">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item w-100 dropdown mb-1 small fw-medium">
                        <a type="button" class="nav-link ps-2 text-dark dropdown-toggle hover clicked" data-bs-toggle="dropdown">
                            <i class="bi bi-car-front-fill me-1"></i>
                             <span class="d-none d-lg-inline">Vehicle Management</span>
                        </a>
                        <ul class="dropdown-menu py-0 w-100 border-0 <?php echo $show; ?>">
                            <li>
                                <a href="register_vehicle.php" class="dropdown-item ps-2 hover clicked dropdown-desktop-font-size <?php echo $registerVehicle; ?>">
                                    <i class="bi bi-file-earmark-plus me-1"></i>
                                    <span class="d-none d-lg-inline">Register Vehicle</span>
                                </a>
                            </li>
                            <li>
                                <a href="manage_vehicle.php" class="dropdown-item ps-2 hover clicked dropdown-desktop-font-size <?php echo $manageVehicle; ?>">
                                    <i class="bi bi-folder-plus me-1"></i>
                                    <span class="d-none d-lg-inline">Manage Vehicle</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item w-100 ps-2 hover clicked mb-1 small fw-medium">
                        <a href="manage_user.php" class="nav-link text-dark">
                            <i class="bi bi-people-fill me-1"></i>
                            <span class="d-none d-lg-inline">User Management</span>
                        </a>
                    </li>
                    <li class="nav-item w-100 ps-2 hover clicked small fw-medium">
                        <a href="report.php" class="nav-link text-dark">
                            <i class="bi bi-journal-check me-1"></i>
                            <span class="d-none d-lg-inline">Report</span>
                        </a>
                    </li>
                </ul>
                <!-- Nav items list End -->
            </div>
        </div>
        <!-- Sidebar End -->