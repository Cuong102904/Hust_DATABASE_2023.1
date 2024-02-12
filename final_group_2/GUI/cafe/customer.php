<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

try {
    $sql = 'SELECT * FROM customer';
    $stmt = $pdo->query($sql);

    if ($stmt) {
        $customer_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        die('Error getting data from khanh');
    }
} catch (PDOException $e) {
    die('Retrieving data from the database failed: ' . $e->getMessage());
}

require_once './layout/header.php';
?>

<div class="container-fluid px-0">
    <div class="bg-white admin-wraps">
        <div id="admin__nav" class="">
            <ul class="list-unstyled text-capitalize">
                <!-- ... your navigation links ... -->
            </ul>
        </div>
    </div>
    <div class="admin__views">
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
                        <h3>Customer List</h3>
                        <a href="./add-customer.php" class="btn btn-primary">Add Customer</a>
                    </div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Phone Number</th>
                                <th>Expenditure</th>
                                <!-- Add more fields as needed -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($customer_data as $customer) : ?>
                                <tr>
                                    <td><?= $customer['customer_id'] ?></td>
                                    <td><?= $customer['first_name'] ?></td>
                                    <td><?= $customer['last_name'] ?></td>
                                    <td><?= $customer['phone_number'] ?></td>
                                    <td><?= $customer['expenditure'] ?></td>
                                    <!-- Add more cells for additional fields -->
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once './layout/footer.php'; ?>
