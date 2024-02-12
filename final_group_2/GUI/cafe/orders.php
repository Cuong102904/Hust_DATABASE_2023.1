<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

try {
    $sql = 'SELECT * FROM orders';
    $stmt = $pdo->query($sql);

    if ($stmt) {
        $orders_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        die('Error getting data from orders');
    }
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
                        <h3>Orders List</h3>
                        <div>
                            <a href="./add-order.php" class="btn btn-primary">Add Order</a>
                        </div>
                    </div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer ID</th>
                                <th>Table ID</th>
                                <th>Bartender ID</th>
                                <th>Chef ID</th>
                                <th>Waiter ID</th>
                                <th>Status</th>
                                <th>Total Price</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($orders_data as $order) {
                                echo "<tr>";
                                echo "<td>" . $order['order_id'] . "</td>";
                                echo "<td>" . $order['customer_id'] . "</td>";
                                echo "<td>" . $order['table_id'] . "</td>";
                                echo "<td>" . $order['bartender_id'] . "</td>";
                                echo "<td>" . $order['chef_id'] . "</td>";
                                echo "<td>" . $order['waiter_id'] . "</td>";
                                echo "<td>" . $order['status_'] . "</td>";
                                echo "<td>" . $order['total_price'] . "</td>";
                                echo "<td>" . $order['order_date'] . "</td>";
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

<?php
require_once './layout/footer.php';
?>
