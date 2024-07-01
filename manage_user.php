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
    <title>Manage User</title>
</head>

<body class="text-bg-light">

    <!-- Navbar -->
    <?php

    $dashboard = "";
    $vehicleManagement = "";
    $manageUser = "active";
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
            <div class="col-lg-9 min-vh-100 after-navbar-space offset-lg-2" id="main">
                <!-- Manage User -->
                <div class="row justify-content-center">
                    <div class="col-md-12 rounded m-1 p-4 p-md-3 pt-1 mb-2 pb-md-0 text-bg-light shadow-sm" style="outline: 0.01rem lightgrey solid; --bs-bg-opacity: 0.5;">

                        <!-- Header and Search Field -->
                        <div class="row d-flex justify-content-between mt-1 mb-3">
                            <div class="col-auto">
                                <h5 class="text-start fw-normal">
                                    User Management
                                </h5>
                            </div>
                            <div class="col-auto">
                                <form action="" role="search" id="searchForm">
                                    <div class="input-group input-group-sm">
                                        <input type="search" class="form-control form-control-sm search-input-radius px-3" name="search" placeholder="Search by Username" id="search-username" autofocus>
                                        <button class="disabled btn btn-sm bg-white text-dark border border-start-0 search-button-radius px-3">
                                            <i class="bi bi-search text-success"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12 col-md-auto">
                                <a href="#addUserModal" role="button" class="add-user-button btn btn-success border-0 btn-sm rounded-3 d-flex align-items-center justify-content-center mt-1 mt-md-0" data-bs-toggle="modal">
                                    <i class="bi bi-person-fill-add me-1 me-md-2 fs-6"></i>
                                    <span>Add User</span>
                                </a>
                            </div>
                        </div>

                        <!-- List of Users -->
                        <div class="table-responsive">
                            <table class="table table-hover small shadow-sm">
                                <thead>
                                    <tr class="table-secondary">
                                        <th class="fw-medium">SN</th>
                                        <th class="fw-medium">Username</th>
                                        <th class="fw-medium">Role</td>
                                        <th class="fw-medium">First Name</th>
                                        <th class="fw-medium">Details</th>
                                        <th class="fw-medium">Edit</th>
                                        <th class="fw-medium">Delete</th>
                                    </tr>
                                </thead>
                                <tbody class=" table-group-divider" id="user-list">

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination and Home Button -->
                    <?php
                    require 'require\db.php';

                    $rows_per_page = 20;

                    // Getting the total number of records (pages)
                    $sql = "SELECT * FROM users";
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
    <div class="modal fade" id="detailsModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered small">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h5 class="fw-light">User Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-end">
                    <div class="table-responsive">
                        <table class="table table-sm text-start align-middle mb-0">
                            <tr>
                                <th class="w-50">User ID</th>
                                <td id="detail_user_id"></td>
                            </tr>
                            <tr>
                                <th class="w-50">Username</th>
                                <td id="detail_username"></td>
                            </tr>
                            <tr>
                                <th class="w-50">Password</th>
                                <td id="detail_password"></td>
                            </tr>
                            <tr>
                                <th class="w-50">Role</th>
                                <td id="detail_role"></td>
                            </tr>
                            <tr>
                                <th class="w-50">First Name</th>
                                <td id="detail_first_name"></td>
                            </tr>
                            <tr>
                                <th class="w-50">Last Name</th>
                                <td id="detail_last_name"></td>
                            </tr>
                            <tr>
                                <th class="w-50">Phone Number</th>
                                <td id="detail_phone_number"></td>
                            </tr>
                        </table>
                    </div>
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
                <form action="" id="editForm">
                    <div class="modal-header py-2">
                        <h5 class="fw-light">User Registration</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="editUsername" class="form-label mb-1">Username</label>
                                <input type="text" class="form-control form-control-sm form-input-hover" id="editUsername" placeholder="johndoe" required>
                                <span id="editUsernameFeedback" class="text-danger"></span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="editPassword" class="form-label mb-1">Password</label>
                                <input type="text" class="form-control form-control-sm form-input-hover" id="editPassword" placeholder="password" required>
                                <span id="editPasswordFeedback" class="text-danger"></span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="editRole" class="form-label mb-1">Role</label>
                                <select id="editRole" class="form-select form-select-sm form-input-hover" required>
                                    <option value="" class="select-options-font-size">Select Role</option>
                                    <option value="Guard" class="select-options-font-size">Guard</option>
                                    <option value="Manager" class="select-options-font-size">Manager</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="editFirstName" class="form-label mb-1">First Name</label>
                                <input type="text" class="form-control form-control-sm form-input-hover" id="editFirstName" placeholder="John Doe" required>
                                <span id="editFirstNameFeedback" class="text-danger"></span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="editLastName" class="form-label mb-1">Last Name</label>
                                <input type="text" class="form-control form-control-sm form-input-hover" id="editLastName" placeholder="John Doe" required>
                                <span id="editLastNameFeedback" class="text-danger"></span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="editPhone" class="form-label mb-1">Phone Number</label>
                                <input type="tel" class="form-control form-control-sm form-input-hover" id="editPhone" placeholder="08012345678" required>
                                <span id="editPhoneFeedback" class="text-danger"></span>
                                <span class="text-danger" id="edit-user-errors-feedback"></span>
                                <span class="text-success" id="edit-user-success-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-1">
                        <button type="button" class="btn btn-sm btn-danger me-1 px-5 px-md-4" data-bs-dismiss="modal" onclick="refreshOnClose()">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-sm btn-success px-5 px-md-4">
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
            <form id="delete-user-modal">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h5 class="fw-light">Are you sure you want to delete this vehicle?</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" onclick="refreshOnClose()"></button>
                    </div>
                    <div class="modal-body text-end p-2">
                        <button type="button" class="btn btn-sm btn-danger me-1 px-4 px-md-2" data-bs-dismiss="modal" onclick="refreshOnClose()">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-success btn-sm px-5 px-md-3">
                            Continue
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Delete Modal End -->

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered small">
            <div class="modal-content">
                <form id="addForm">
                    <div class="modal-header py-2">
                        <h5 class="fw-light">User Registration</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label for="addUsername" class="form-label mb-1">Username</label>
                                <input type="text" class="form-control form-control-sm form-input-hover" id="addUsername" placeholder="johndoe" required>
                                <span id="addUsernameFeedback" class="text-danger"></span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="addPassword" class="form-label mb-1">Password</label>
                                <input type="text" class="form-control form-control-sm form-input-hover" id="addPassword" placeholder="password" required>
                                <span id="addPasswordFeedback" class="text-danger"></span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="addRole" class="form-label mb-1">Role</label>
                                <select id="addRole" class="form-select form-select-sm form-input-hover" required>
                                    <option value="" class="select-options-font-size">Select Role</option>
                                    <option value="Guard" class="select-options-font-size">Guard</option>
                                    <option value="Manager" class="select-options-font-size">Manager</option>
                                </select>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="addFirstName" class="form-label mb-1">First Name</label>
                                <input type="text" class="form-control form-control-sm form-input-hover" id="addFirstName" placeholder="John" required>
                                <span id="addFirstNameFeedback" class="text-danger"></span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="addLastName" class="form-label mb-1">Last Name</label>
                                <input type="text" class="form-control form-control-sm form-input-hover" id="addLastName" placeholder="Doe" required>
                                <span id="addLastNameFeedback" class="text-danger"></span>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="addPhone" class="form-label mb-1">Phone Number</label>
                                <input type="tel" class="form-control form-control-sm form-input-hover" id="addPhone" placeholder="08012345678" required>
                                <span id="addPhoneFeedback" class="text-danger"></span>
                                <span class="text-danger" id="add-user-errors-feedback"></span>
                                <span class="text-success" id="add-user-success-feedback"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-1">
                        <button type="button" class="btn btn-sm btn-danger me-1 px-5 px-md-4" data-bs-dismiss="modal" onclick="refreshOnClose()">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-sm btn-success px-5 px-md-4">
                            Add User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add User Modal End -->

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="bootstrap/js/jquery-3.7.1.js"></script>
    <script src="bootstrap/js/sweetalert2@11.js"></script>
    <script src="script/timeoutLogoutUser.js"></script>

    <script>
        // Displaying list of users in the manage users
        function fetchUser() {
            $.ajax({
                url: 'logic/fetch_users.php',
                method: 'POST',
                data: {
                    action: 'fetchUser'
                },
                success: function(response) {
                    $('#user-list').html(response);
                }
            });
        }

        $('document').ready(function() {

            fetchUser();

            $('#search-username').keyup(function(e) {
                e.preventDefault();

                let userName = $('#search-username').val().trim();
                let action = 'searchUsername';
                if (userName.length <= 0) {
                    fetchUser();
                }
                $.ajax({
                    url: 'logic/fetch_users.php',
                    method: 'POST',
                    data: {
                        userName: userName,
                        action: action
                    },
                    success: function(response) {
                        $('#user-list').html(response);
                    }
                });
            });

        });

        // Listen for submit event for adding user
        document.getElementById("addForm").addEventListener("submit", addUser);

        // Get User Details Asynchronously
        function getUserDetails(user_id) {
            let xhr = new XMLHttpRequest();

            xhr.open('GET', 'logic/user_details.php?id=' + user_id, true);

            xhr.onload = function() {
                if (this.status == 200) {
                    let userDetails = JSON.parse(this.responseText);

                    document.getElementById('detail_user_id').innerHTML = userDetails.user_id;
                    document.getElementById('detail_username').innerHTML = userDetails.username;
                    document.getElementById('detail_password').innerHTML = userDetails.password;
                    document.getElementById('detail_role').innerHTML = userDetails.role;
                    document.getElementById('detail_first_name').innerHTML = userDetails.first_name;
                    document.getElementById('detail_last_name').innerHTML = userDetails.last_name;
                    document.getElementById('detail_phone_number').innerHTML = userDetails.phone_number;
                }
            }

            xhr.send();
        }

        // Execute when the edit button is clicked for each user
        function editUser(user_id) {
            $.ajax({
                type: 'GET',
                url: 'logic/user_details.php',
                data: {
                    id: user_id
                },
                success: function(response) {
                    let userDetails = JSON.parse(response);

                    $('#editUsername').val(userDetails.username);
                    $('#editPassword').val(userDetails.password);
                    $('#editRole').val(userDetails.role);
                    $('#editFirstName').val(userDetails.first_name);
                    $('#editLastName').val(userDetails.last_name);
                    $('#editPhone').val(userDetails.phone_number);
                }
            });

            // Add User Form Validation using jQuery
            $('#editForm').on('input', function(editEvent) {
                let editUsername = $('#editUsername').val().trim();
                let editUsernameFeedback = $('#editUsernameFeedback');
                let editFirstName = $('#editFirstName').val().trim();
                let editFirstNameFeedback = $('#editFirstNameFeedback');
                let editLastName = $('#editLastName').val().trim();
                let editLastNameFeedback = $('#editLastNameFeedback');
                let editPhone = $('#editPhone').val().trim();
                let editPhoneFeedback = $('#editPhoneFeedback');

                const editUsernamePattern = /^[A-Za-z][A-Za-z0-9_]{5,29}$/;
                const editFirstNamePattern = /^[a-zA-Z\s'\-.]+$/;
                const editPhonePattern = /^0[789][01]\d{8}$/;

                // Validate Username
                if (!editUsernamePattern.test(editUsername)) {
                    editUsernameFeedback.text("Username must be 6-30 characters, letters, numbers, and underscores only, starting with a letter.");
                    editEvent.preventDefault();
                    editEvent.stopPropagation();
                } else {
                    editUsernameFeedback.text("");
                }

                // Validate First Name
                if (!editFirstNamePattern.test(editFirstName)) {
                    editFirstNameFeedback.text("Please enter a valid name.");
                    editEvent.preventDefault();
                    editEvent.stopPropagation();
                } else {
                    editFirstNameFeedback.text("");
                }

                // Validate Last Name
                if (!editFirstNamePattern.test(editLastName)) {
                    editLastNameFeedback.text("Please enter a valid name.");
                    editEvent.preventDefault();
                    editEvent.stopPropagation();
                } else {
                    editLastNameFeedback.text("");
                }

                // Validate Phone Number
                if (!editPhonePattern.test(editPhone)) {
                    editPhoneFeedback.text("Please enter a valid phone number.");
                    editEvent.preventDefault();
                    editEvent.stopPropagation();
                } else {
                    editPhoneFeedback.text("");
                }
            });

            // Function to update user details using the user_id sent on clicking the edit button
            $('#editForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default behavior of form reloading when submitted

                let username = $('#editUsername').val().trim();
                let password = $('#editPassword').val().trim();
                let role = $('#editRole').val().trim();
                let firstName = $('#editFirstName').val().trim();
                let lastName = $('#editLastName').val().trim();
                let phone = $('#editPhone').val().trim();

                let params = {
                    user_id: user_id,
                    username: username,
                    password: password,
                    first_name: firstName,
                    last_name: lastName,
                    role: role,
                    phone_number: phone
                };

                $.ajax({
                    type: 'POST',
                    url: 'logic/update_user.php',
                    data: $.param(params),
                    dataType: 'json',
                    contentType: 'application/x-www-form-urlencoded',
                    success: function(data) {
                        if (data.success) {
                            $('#editModal').modal('hide');
                            fetchUser();
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
        }

        // Manual refresh to fix the problem of user_id not chaning when close button is clicked
        function refreshOnClose() {
            fetchUser();
        }

        // Delete User 
        function deleteUser(user_id) {
            $('#delete-user-modal').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                let params = `user_id=${user_id}`;

                $.ajax({
                    type: 'POST',
                    url: 'logic/delete_user.php',
                    data: params,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#deleteModal').modal('hide');
                            fetchUser();
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
                            fetchUser();
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

        // Function to add user
        function addUser(e) {
            e.preventDefault(); // Prevent default behavior of form reloading when submitted

            // Add User Form Validation
            let addUsername = $('#addUsername').val().trim();
            let addUsernameFeedback = $('#addUsernameFeedback');
            let addFirstName = $('#addFirstName').val().trim();
            let addFirstNameFeedback = $('#addFirstNameFeedback');
            let addLastName = $('#addLastName').val().trim();
            let addLastNameFeedback = $('#addLastNameFeedback');
            let addPhone = $('#addPhone').val().trim();
            let addPhoneFeedback = $('#addPhoneFeedback');

            const addUsernamePattern = /^[A-Za-z][A-Za-z0-9_]{5,29}$/;
            const addFirstNamePattern = /^[a-zA-Z\s'\-.]+$/;
            const addPhonePattern = /^0[789][01]\d{8}$/;

            let valid = true;

            // Validate Username
            if (!addUsernamePattern.test(addUsername)) {
                addUsernameFeedback.text("Username must be 6-30 characters, letters, numbers, and underscores only, starting with a letter.");
                valid = false;
            } else {
                addUsernameFeedback.text("");
            }

            // Validate First Name
            if (!addFirstNamePattern.test(addFirstName)) {
                addFirstNameFeedback.text("Please enter a valid name.");
                valid = false;
            } else {
                addFirstNameFeedback.text("");
            }

            // Validate Last Name
            if (!addFirstNamePattern.test(addLastName)) {
                addLastNameFeedback.text("Please enter a valid name.");
                valid = false;
            } else {
                addLastNameFeedback.text("");
            }

            // Validate Phone Number
            if (!addPhonePattern.test(addPhone)) {
                addPhoneFeedback.text("Please enter a valid phone number.");
                valid = false;
            } else {
                addPhoneFeedback.text("");
            }

            if (!valid) {
                return;
            }

            let password = $('#addPassword').val().trim();
            let role = $('#addRole').val().trim();

            let params = {
                username: addUsername,
                password: password,
                first_name: addFirstName,
                last_name: addLastName,
                role: role,
                phone_number: addPhone
            };

            $.ajax({
                type: 'POST',
                url: 'logic/add_user.php',
                data: $.param(params),
                dataType: 'json',
                contentType: 'application/x-www-form-urlencoded',
                success: function(data) {
                    if (data.success) {
                        $('#addUsername').val('');
                        $('#addFirstName').val('');
                        $('#addLastName').val('');
                        $('#addPhone').val('');
                        $('#addPassword').val('');
                        $('#addRole').val('');
                        $('#addUserModal').modal('hide');
                        fetchUser();
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
        }

        // Add event listener for input validation
        $('#addForm').on('input', function(e) {
            let addUsername = $('#addUsername').val().trim();
            let addUsernameFeedback = $('#addUsernameFeedback');
            let addFirstName = $('#addFirstName').val().trim();
            let addFirstNameFeedback = $('#addFirstNameFeedback');
            let addLastName = $('#addLastName').val().trim();
            let addLastNameFeedback = $('#addLastNameFeedback');
            let addPhone = $('#addPhone').val().trim();
            let addPhoneFeedback = $('#addPhoneFeedback');

            const addUsernamePattern = /^[A-Za-z][A-Za-z0-9_]{5,29}$/;
            const addFirstNamePattern = /^[a-zA-Z\s'\-.]+$/;
            const addPhonePattern = /^0[789][01]\d{8}$/;

            // Validate Username
            if (!addUsernamePattern.test(addUsername)) {
                addUsernameFeedback.text("Username must be 6-30 characters, letters, numbers, and underscores only, starting with a letter.");
                e.preventDefault();
                e.stopPropagation();
            } else {
                addUsernameFeedback.text("");
            }

            // Validate First Name
            if (!addFirstNamePattern.test(addFirstName)) {
                addFirstNameFeedback.text("Please enter a valid name.");
                e.preventDefault();
                e.stopPropagation();
            } else {
                addFirstNameFeedback.text("");
            }

            // Validate Last Name
            if (!addFirstNamePattern.test(addLastName)) {
                addLastNameFeedback.text("Please enter a valid name.");
                e.preventDefault();
                e.stopPropagation();
            } else {
                addLastNameFeedback.text("");
            }

            // Validate Phone Number
            if (!addPhonePattern.test(addPhone)) {
                addPhoneFeedback.text("Please enter a valid phone number.");
                e.preventDefault();
                e.stopPropagation();
            } else {
                addPhoneFeedback.text("");
            }
        });
    </script>

</body>

</html>