<?php

session_start();

require '../require/db.php';

// Setting the limit (number of row per fetch) and offset (number of records to skip) for pagination
$skip = 0;
$rows_per_page = 20;

// Getting the total number of records (pages)
$sql = "SELECT * FROM vehicles";
$result = $conn->query($sql);
$total_records = $result->num_rows;

$pages = ceil($total_records / $rows_per_page);

// Getting the number of pages
if (isset($_SESSION['page_number'])) {
    $page = $_SESSION['page_number'] - 1;
    $skip = $page * $rows_per_page;
}

// Display records with serial numbers
$counter = $skip + 1;

// Function to query the database given an sql statement.
function queryDatabase($query, $counter)
{
    require '../require/db.php';
    $result = $conn->query($query);
    $output = '';

    if ($result->num_rows > 0) {
        foreach ($result as $row) {
            $output .= "<tr>
                    <td>" . $counter++   . "</td>
                    <td>" . $row['plate_number'] . "</td>
                    <td>" . $row['vehicle_type'] . "</td>
                    <td>" . $row['owner_name']   . "</td>
                    <td>" . $row['pass_code']   . "</td>
                    <td>
                        <button onclick='getVehicleDetails(" . $row['vehicle_id'] . ")' class='btn btn-info btn-sm d-grid py-0 w-100' data-bs-toggle='modal' data-bs-target='#detailsModal'>Details</button>
                    </td>
                    <td>
                        <button onclick='editVehicle(" . $row['vehicle_id'] . ")' class='btn btn-warning btn-sm d-grid py-0 w-100' data-bs-toggle='modal' data-bs-target='#editModal'>Edit</button>
                    </td>
                    <td>
                        <button onclick='deleteVehicle(" . $row['vehicle_id'] . ")'class='btn btn-danger btn-sm d-grid py-0 w-100' data-bs-toggle='modal' data-bs-target='#deleteModal'>Delete</button>
                    </td>
                </tr>";
        }
    } else {
        $output .= "<tr>
                <td colspan='7' class='text-center py-3 fw-light fs-6'>No vehicle found</td>
            </tr>";
    }

    echo $output;
    $conn->close();
}

// Fetching all vehicles
if (isset($_POST['action']) && $_POST['action'] == 'fetchVehicles') {
    $sql = "SELECT * FROM vehicles ORDER BY vehicle_id DESC LIMIT $rows_per_page OFFSET $skip";
    queryDatabase($sql, $counter);
}

// Search for vehicles using the plate_number
if (isset($_POST['action']) && $_POST['action'] == 'searchPlateNumber') {
    $plate_number = $conn->real_escape_string($_POST['plateNumber']);
    $sql = "SELECT * FROM vehicles WHERE plate_number LIKE '%$plate_number%'";
    queryDatabase($sql, $counter);
}


$conn->close();
