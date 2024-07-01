<?php

session_start();

require '../require/db.php';

// Setting the limit (number of row per fetch) and offset (number of records to skip) for pagination
$skip = 0;
$rows_per_page = 20;

// Getting the total number of records (pages)
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$total_records = $result->num_rows;

$pages = ceil($total_records / $rows_per_page);

// Getting the page to print
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
                    <td>" . $row['username'] . "</td>
                    <td>" . $row['role'] . "</td>
                    <td>" . $row['first_name']   . "</td>
                    <td>
                        <button onclick='getUserDetails(" . $row['user_id'] . ")' class='btn btn-info btn-sm d-grid py-0 w-100' data-bs-toggle='modal' data-bs-target='#detailsModal'>Details</button>
                    </td>
                    <td>
                        <button onclick='editUser(" . $row['user_id'] . ")' class='btn btn-warning btn-sm d-grid py-0 w-100' data-bs-toggle='modal' data-bs-target='#editModal'>Edit</button>
                    </td>
                    <td>
                        <button onclick='deleteUser(" . $row['user_id'] . ")'class='btn btn-danger btn-sm d-grid py-0 w-100' data-bs-toggle='modal' data-bs-target='#deleteModal'>Delete</button>
                    </td>
                </tr>";
        }
    } else {
        $output .= "<tr>
                <td colspan='7' class='text-center py-3 fw-light fs-6'>No user found</td>
            </tr>";
    }

    echo $output;
    $conn->close();
}


// Fetching all users
if (isset($_POST['action']) && $_POST['action'] == 'fetchUser') {
    $sql = "SELECT * FROM users ORDER BY user_id DESC LIMIT $rows_per_page OFFSET $skip";
    queryDatabase($sql, $counter);
}

// Search for users using the username
if (isset($_POST['action']) && $_POST['action'] == 'searchUsername') {
    $username = $conn->real_escape_string($_POST['userName']);
    $sql = "SELECT * FROM users WHERE username LIKE '%$username%'";
    queryDatabase($sql, $counter);
}


$conn->close();
