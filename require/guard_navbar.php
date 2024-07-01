<?php 

    // $dashboard = "";
    // $generateGatePass = "";
    // $verifyGatePass = "";

?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg text-bg-light border shadow-sm custom-navbar fixed-top py-0 py-lg-1">
    <div class="container-fluid">
        <!-- Navbar Brand -->
        <a href="guard_dashboard.php" class="navbar-brand d-inline-flex align-items-center small me-5">
            <img src="images/fug-logo.png" alt="Navbar Image" class="img-fluid rounded custom-navbar-image me-3">
            <h5 class="fw-medium text-success m-0 p-0">
                <span>Federal University Gusau</span>
                <p class="fs-6 m-0 p-0 fw-normal small">
                    Knowledge, Innovation and Service
                </p>
            </h5>
        </a>
        <!-- Navbar Toggler Button -->
        <button type="button" class="btn btn-sm navbar-toggler border-0"  data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <i class="bi bi-list"></i>
        </button>

        <!-- Collapsible Content -->
        <div class="collapse navbar-collapse mt-3 mt-md-0" id="navbarContent">
            <!-- Change Password Mobile -->
            <div class="mt-2 dropdown d-lg-none mb-2 rounded shadow-sm">
                <button class="btn btn-sm d-flex w-100 text-start align-items-center dropdown-toggle hover clicked border-0" data-bs-toggle="dropdown">
                    <i class="bi bi-person-fill me-2"></i>
                    <span class="text-secondary">
                        <?php
                            echo $_SESSION['username'];
                        ?>
                    </span>
                </button>
                <ul class="dropdown-menu position-relative border-0 py-0 pt-1">
                    <li>
                        <a href="guard_change_password.php" class="dropdown-item small hover clicked dropdown-mobile-font-size">
                            Change Password
                        </a>
                    </li>
                </ul>
            </div>
        
            <!-- Nav items list -->
            <ul class="navbar-nav d-lg-none">
                <li class="nav-item ps-3 ps-lg-0 px-lg-1 hover clicked small">
                    <a href="guard_dashboard.php" class="nav-link <?php echo $dashboard; ?>" aria-current="page">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item ps-3 ps-lg-0 px-lg-1 hover clicked small">
                    <a href="generate_gate_pass.php" class="nav-link <?php echo $generateGatePass; ?>">Generate Gate Pass</a>
                </li>
                <li class="nav-item ps-3 ps-lg-0 px-lg-1 hover clicked small">
                    <a href="verify_gate_pass.php" class="nav-link <?php echo $verifyGatePass; ?>">Verify Gate Pass</a>
                </li>
            </ul>

            <div class="ms-auto d-grid d-lg-flex align-items-center mb-2 mb-lg-0 mt-1 mt-lg-0">
                <!-- Change Password Desktop -->
                <div class="change-password dropdown dropdown-center d-none d-lg-block me-2">
                    <button class="btn btn-sm d-inline-flex align-items-center dropdown-toggle hover clicked border-0" data-bs-toggle="dropdown">
                        <i class="bi bi-person-fill me-2"></i>
                        <span class="text-secondary">
                            <?php
                                echo $_SESSION['username'];
                            ?>
                        </span>
                    </button>
                    <ul class="dropdown-menu py-0 border-0 shadow-sm text-center">
                        <li>
                            <a href="guard_change_password.php" class="dropdown-item hover small clicked dropdown-desktop-font-size">
                                Change Password
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- Logout -->
                <div>
                    <a href="logout.php" class="btn btn-sm d-flex align-items-center rounded w-100 hover clicked border-0">
                        <i class="bi bi-power me-1 text-danger"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
<!-- Navbar End -->