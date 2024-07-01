<?php

// $dashboard = "";
// $generateGatePass = "";
// $verifyGatePass = "";

?>

<!-- Sidebar -->
<div class="col-lg-3 text-bg-light min-vh-100 d-none d-lg-block border border-end shadow-sm ps-3 after-navbar-space position-fixed" id="sidebar">
            <p class="fw-light text-secondary small mb-1 mt-1">MAIN NAVIGATION</p>
            <div>
                <!-- Nav items list -->
                <ul class="navbar navbar-nav nav-pills d-flex flex-column align-items-start">
                    <li class="nav-item w-100 ps-2 mb-1 hover clicked small fw-medium <?php echo $dashboard; ?>" aria-current="page">
                        <a href="guard_dashboard.php" class="nav-link text-dark">
                            <i class="bi bi-house-fill me-1"></i>
                            <span class="d-none d-lg-inline">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item w-100 ps-2 hover clicked mb-1 small fw-medium <?php echo $generateGatePass; ?>">
                        <a href="generate_gate_pass.php" class="nav-link text-dark">
                            <i class="bi bi-pass-fill me-1"></i>
                            <span class="d-none d-lg-inline">Generate Gate Pass</span>
                        </a>
                    </li>
                    <li class="nav-item w-100 ps-2 hover clicked small fw-medium <?php echo $verifyGatePass; ?>">
                        <a href="verify_gate_pass.php" class="nav-link text-dark">
                            <i class="bi bi-shield-fill-check me-1"></i>
                            <span class="d-none d-lg-inline">Verify Gate Pass</span>
                        </a>
                    </li>
                </ul>
                <!-- Nav items list End -->
            </div>
        </div>
        <!-- Sidebar End -->