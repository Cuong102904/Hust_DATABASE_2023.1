<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

$success_message = '';

if (isset($_POST['add-order'])) {
    // Process the form submission
    try {
        $pdo->beginTransaction();

        // Manually increment order ID
        $sql = 'SELECT MAX(order_id) + 1 AS next_order_id FROM orders';
        $stmt = $pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $order_id = $result['next_order_id'];

        // Insert into orders table
        $customer_id = $_POST['customer'];
        $table_id = $_POST['table'];
        $bartender_id = $_POST['bartender'];
        $chef_id = $_POST['chef'];
        $waiter_id = $_POST['waiter'];
        $status_ = 'DONE';
        $total_price = 0; // You can adjust this based on your actual calculation
        $order_date = date('Y-m-d H:i:s');

        $sql = 'INSERT INTO orders(order_id, customer_id, table_id, bartender_id, chef_id, waiter_id, status_, total_price, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$order_id, $customer_id, $table_id, $bartender_id, $chef_id, $waiter_id, $status_, $total_price, $order_date]);

        // Insert into orderline table
        foreach ($_POST['product'] as $key => $product_id) {
            $quantity = $_POST['quantity'][$key];
            $sql = 'INSERT INTO orderline (order_id, product_id, quantity) VALUES (?, ?, ?)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$order_id, $product_id, $quantity]);
        }

        $pdo->commit();
        $success_message = 'Order added successfully';
    } catch (PDOException $e) {
        $pdo->rollBack();
        die('Could not add order: ' . $e->getMessage());
    }
}

// Fetch select options for customers
$customerOptions = fetch_select_options($pdo, 'customer', 'customer_id', 'CONCAT(first_name, \' \', last_name)');

// Fetch select options for tables
$tableOptions = fetch_select_options($pdo, 'sitting_area', 'table_id', 'CONCAT(table_id, \' - \', status_)');

// Fetch select options for chefs, bartenders, and waiters
$chefOptions = fetch_select_options($pdo, 'chef', 'staff_id', 'CONCAT(first_name, \' \', last_name)');
$bartenderOptions = fetch_select_options($pdo, 'bartender', 'staff_id', 'CONCAT(first_name, \' \', last_name)');
$waiterOptions = fetch_select_options($pdo, 'waiter', 'staff_id', 'CONCAT(first_name, \' \', last_name)');

// Fetch select options for products
$productOptions = fetch_select_options($pdo, 'product', 'prod_id', 'prod_name');

require_once './layout/header.php';
?>

<div class="bg-light admin__main">
    <div class="container">
        <?php
        if (!empty($success_message)):
            ?>
            <p class="alert alert-success fw-500">
                <?= $success_message ?>
            </p>
            <?php
        endif;
        ?>
        <form class="p-4 rounded bg-white form" method="post" enctype="multipart/form-data">
            <div class="d-flex align-items-center justify-content-between">
                <h3>Add Order</h3>
                <a href="./orders.php" class="btn btn-danger lh-100">Back</a>
            </div>

            <!-- Add select dropdown for customers -->
            <label class="form__label" for="customer">Customer</label>
            <select name="customer" id="customer" class="form-control mb-3 form__inp" required>
                <?php foreach ($customerOptions as $customer): ?>
                    <option value="<?= $customer['customer_id'] ?>"><?= $customer['concat'] ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Add select dropdown for tables -->
            <label class="form__label" for="table">Table</label>
            <select name="table" id="table" class="form-control mb-3 form__inp" required>
                <?php foreach ($tableOptions as $table): ?>
                    <option value="<?= $table['table_id'] ?>"><?= $table['concat'] ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Add select dropdowns for chefs, bartenders, waiters, and products -->
            <!-- Chef -->
            <label class="form__label" for="chef">Chef</label>
            <select name="chef" id="chef" class="form-control mb-3 form__inp" required>
                <?php foreach ($chefOptions as $chef): ?>
                    <option value="<?= $chef['staff_id'] ?>"><?= $chef['concat'] ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Bartender -->
            <label class="form__label" for="bartender">Bartender</label>
            <select name="bartender" id="bartender" class="form-control mb-3 form__inp" required>
                <?php foreach ($bartenderOptions as $bartender): ?>
                    <option value="<?= $bartender['staff_id'] ?>"><?= $bartender['concat'] ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Waiter -->
            <label class="form__label" for="waiter">Waiter</label>
            <select name="waiter" id="waiter" class="form-control mb-3 form__inp" required>
                <?php foreach ($waiterOptions as $waiter): ?>
                    <option value="<?= $waiter['staff_id'] ?>"><?= $waiter['concat'] ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Product Section with Quantity -->
            <div id="product-section">
                <div class="product-entry mb-3">
                    <label class="form__label" for="product">Product</label>
                    <select name="product[]" class="form-control form__inp product-select" required>
                        <?php foreach ($productOptions as $product): ?>
                            <option value="<?= $product['prod_id'] ?>"><?= $product['prod_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label class="form__label" for="quantity">Quantity</label>
                    <input type="number" name="quantity[]" class="form-control form__inp" required>
                </div>
            </div>

            <!-- Plus Button to Add More Products -->
            <button type="button" class="btn btn-primary" onclick="addProductEntry()">Add Product</button>

            <input type="submit" class="btn btn-dark w-100 form__btn" name="add-order" value="Add Order">
        </form>
    </div>
</div>

<script>
    // Function to add more product entries
    function addProductEntry() {
        const productSection = document.getElementById('product-section');
        const newProductEntry = document.createElement('div');
        newProductEntry.className = 'product-entry mb-3';
        newProductEntry.innerHTML = `
            <label class="form__label" for="product">Product</label>
            <select name="product[]" class="form-control form__inp product-select" required>
                <?php foreach ($productOptions as $product): ?>
                    <option value="<?= $product['prod_id'] ?>"><?= $product['prod_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <label class="form__label" for="quantity">Quantity</label>
            <input type="number" name="quantity[]" class="form-control form__inp" required>
        `;
        productSection.appendChild(newProductEntry);
    }
</script>

<?php require_once './layout/footer.php'; ?>
