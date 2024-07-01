<style>
    .page-item.active .page-link {
        background-color: #4284b0;
        color: #fff !important;
        border: thin solid #4592c5;
    }
</style>
<!-- Pagination and Home Button -->
<div class="row align-items-start d-grid d-md-flex justify-content-between mb-3 mt-2 px-0 mx-0">
    <!-- Pagination -->
    <div class="col-8 px-md-0">
        <ul class="pagination pagination-sm justify-content-start">
            <!-- First Page Button -->
            <li class="page-item">
                <?php 
                    if (!isset($_SESSION['page_number'])) {
                        ?> 
                            <a class="page-link text-dark px-md-4 disabled" aria-current="page">First</a>
                        <?php
                    } else {
                        if ($_SESSION['page_number'] == 1) {
                            ?> 
                                <a class="page-link text-dark px-md-4 disabled" aria-current="page">First</a>
                            <?php
                        } else {
                            ?>
                                <a href="?page_number=1" class="page-link text-dark px-md-4" aria-current="page">First</a>
                            <?php
                        }
                    }
                ?>
            </li>
            <!-- Previous Page Button -->
            <li class="page-item">
                <?php 
                    // Display the previous button only if we are on page 2 and above.
                    if (isset($_SESSION['page_number']) && $_SESSION['page_number'] > 1) {
                        ?> 
                            <a href="?page_number=<?php echo $_SESSION['page_number'] - 1; ?>" class="page-link text-dark px-md-4">&lt; Previous</a>
                        <?php
                    } else {
                        ?>
                            <!-- Else, You can optionally display a disabled previous button -->
                            <a class="page-link text-dark px-md-4 disabled">&lt; Previous</a>
                        <?php
                    }
                ?>
            </li>
            <!-- Output the page numbers -->
            <?php
                $current_page = 1;

                if (isset($_SESSION['page_number'])) {
                    $current_page = $_SESSION['page_number'];
                }
                
                $start_page = max(1, $current_page - 1);
                $end_page = min($pages, $current_page + 1);
                
                if ($start_page > 1) {
                    // Display first page and ellipsis if needed
                    ?>
                    <li class="page-item">
                        <a href="?page_number=1" class="page-link text-dark px-md-4" aria-current="page">1</a>
                    </li>
                    <?php
                    if ($start_page > 2) {
                        ?>
                        <li class="page-item">
                            <span class="page-link text-dark px-md-4">...</span>
                        </li>
                        <?php
                    }
                }
                
                for ($counter = $start_page; $counter <= $end_page; $counter++) {
                    ?> 
                    <li class="page-item <?php echo ($counter == $current_page) ? 'active' : ''; ?>">
                        <a href="?page_number=<?php echo $counter; ?>" class="page-link text-dark px-md-4" aria-current="page"><?php echo $counter; ?></a>
                    </li>
                    <?php
                }
                
                if ($end_page < $pages) {
                    if ($end_page < $pages - 1) {
                        ?>
                        <li class="page-item">
                            <span class="page-link text-dark px-md-4">...</span>
                        </li>
                        <?php
                    }
                    // Display last page
                    ?>
                    <li class="page-item">
                        <a href="?page_number=<?php echo $pages; ?>" class="page-link text-dark px-md-4" aria-current="page"><?php echo $pages; ?></a>
                    </li>
                    <?php
                }
            ?>
            <!-- Next Page Button -->
            <li class="page-item">
                <?php 
                    if (!isset($_SESSION['page_number'])) {
                        if ($pages <= 1) {
                            ?>
                                <a class="page-link text-dark px-md-4 disabled">Next &gt;</a>
                            <?php
                        } else {
                            ?> 
                            <a href="?page_number=2" class="page-link text-dark px-md-4">Next &gt;</a>
                        <?php
                        }
                    } else {
                        if ($_SESSION['page_number'] >= $pages) {
                            ?> 
                                <!-- Optionally, you can display disabled next button -->
                                <a class="page-link text-dark px-md-4 disabled">Next &gt;</a>
                            <?php
                        } else {
                            ?> 
                                <a href="?page_number=<?php echo $_SESSION['page_number'] + 1; ?>" class="page-link text-dark px-md-4">Next &gt;</a>
                            <?php
                        }
                    }
                ?>
            </li>
            <!-- Last Page Button -->
            <li class="page-item">
                <?php 
                    if (isset($_SESSION['page_number']) && $_SESSION['page_number'] == $pages || $pages <= 1) {
                        ?>
                            <a class="page-link text-dark px-md-4 disabled" aria-current="page">Last</a>
                        <?php
                    } else {
                        ?> 
                            <a href="?page_number=<?php echo $pages ?>" class="page-link text-dark px-md-4" aria-current="page">Last</a>
                        <?php 
                    }
                ?>
            </li>
        </ul>
    </div>

    <!-- Page Information -->
    <div class="col-auto me-auto mb-3 mb-md-0">
        <span class="small">
            <?php
                if (isset($_SESSION['page_number'])) {
                    $page = $_SESSION['page_number'];
                } else {
                    $page = 1;
                }
                echo "Page " . "<span class='fw-medium'>". $page ."</span> of " .  "<span class='fw-medium'>" . $pages . "</span>";
            ?>
        </span>
    </div>

    <!-- Home Button -->
    <div class="col-2 d-grid d-md-flex px-md-0">
        <a href="guard_dashboard.php" class="btn btn-light btn-sm border shadow-sm ms-auto w-100 d-flex align-items-center justify-content-center">
            <i class="bi bi-house-fill me-2"></i>
            Home
        </a>
    </div>
</div>