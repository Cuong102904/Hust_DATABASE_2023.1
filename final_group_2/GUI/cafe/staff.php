<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

try {
    // Fetch data from bartender table
    $sql = 'SELECT *, \'bartender\' as role FROM bartender';
    $stmt = $pdo->query($sql);
    $bartender_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch data from chef table
    $sql = 'SELECT *, \'chef\' as role FROM chef';
    $stmt = $pdo->query($sql);
    $chef_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch data from waiter table
    $sql = 'SELECT *, \'waiter\' as role FROM waiter';
    $stmt = $pdo->query($sql);
    $waiter_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Merge data from all three tables
    $staff_data = array_merge($bartender_data, $chef_data, $waiter_data);

    // Calculate average rating for each staff
    foreach ($staff_data as &$staff) {
        if ($staff['rating_quantity'] > 0) {
            $staff['average_rating'] = $staff['total_star'] / $staff['rating_quantity'];
        } else {
            $staff['average_rating'] = 0;
        }
    }
    unset($staff); // Unset reference variable to avoid accidental modification
} catch (PDOException $e) {
    die('Retrieving data from database failed: ' . $e->getMessage());
}

require_once './layout/header.php';
?>

<div class="container-fluid px-0">
    <div class="bg-white admin-wraps">
        <ul class="list-unstyled text-capitalize">
            <!-- List of menu items (unchanged) -->
        </ul>
    </div>

    <div class=" admin__views">
        <div class="row admin__view-page">
            <div class="col-md-12 mt-4">
                <div class="p-3 bg-white rounded">
                <ul class="list-unstyled text-capitalize">
                <li class="mb-1 admin__nav-item">
                    <a href="./orders.php" class="p-2 rounded admin__nav-item-link">Order</a>
                </li>
                <li class="mb-1 admin__nav-item">
                    <a href="./product.php" class="p-2 rounded admin__nav-item-link">Product</a>
                </li>
                <li class="mb-1 admin__nav-item">
                    <a href="./customer.php" class="p-2 rounded admin__nav-item-link">Customer</a>
                </li>
                <li class="mb-1 admin__nav-item">
                    <a href="./staff.php" class="p-2 rounded admin__nav-item-link admin__nav-item-link--active">Staff</a>
                </li>

            </ul>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3>Staff List</h3>
                        <div>
                            <a href="./add-staff.php" class="btn btn-primary">Add Staff</a>
                        </div>
                    </div>

                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Address</th>
                                <th>Phone Number</th>
                                <th>Total Star</th>
                                <th>Rating Quantity</th>
                                <th>Average Rating</th>
                                <th>Role</th>
                                <th>Functions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
foreach ($staff_data as $staff) {
    echo "<tr>";
    echo "<td>" . $staff['staff_id'] . "</td>";
    echo "<td>" . $staff['first_name'] . "</td>";
    echo "<td>" . $staff['last_name'] . "</td>";
    echo "<td>" . $staff['address_staff'] . "</td>";
    echo "<td>" . $staff['phone_number'] . "</td>";
    echo "<td>" . $staff['total_star'] . "</td>";
    echo "<td>" . $staff['rating_quantity'] . "</td>";
    echo "<td>" . number_format($staff['average_rating'], 2) . "</td>";
    echo "<td>" . $staff['role'] . "</td>";

    echo '<td><a class="btn btn-warning me-2" href="./edit-staff.php?id=' . $staff['staff_id'] . '">Edit</a>  <a onclick="deletestaff(event, ' . $staff['staff_id'] . ')" class="btn btn-danger">Delete</a></td>';
    echo "</tr>";
}

                        
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Other code remains unchanged -->
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.27/sweetalert2.min.js"
    integrity="sha512-mJQ9oQHzLM2zXe1cwiHmnMddNrmjv1YlaKZe1rM4J7q8JTnNn9UgeJVBV9jyV/lVGdXymVx6odhgwNZjQD8AqA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.27/sweetalert2.css"
    integrity="sha512-eRBMRR/qeSlGYAb6a7UVwNFgXHRXa62u20w4veTi9suM2AkgkJpjcU5J8UVcoRCw0MS096e3n716Qe9Bf14EyQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>
    function deletestaff(event, staff_id) {
        event.preventDefault();
        Swal.fire({
            title: 'Do you really want to delete this staff?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user confirms, navigate to the delete-staff.php page
                window.location.href = './delete-staff.php?id=' + staff_id;
            }
        });
    }
</script>

<?php
require_once './layout/footer.php';
?>
