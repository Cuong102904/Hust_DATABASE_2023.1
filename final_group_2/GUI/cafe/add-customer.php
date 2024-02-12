<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

if (isset($_POST['add-customer'])) {
    $first_name = sanitize($_POST['first-name']);
    $last_name = sanitize($_POST['last-name']);
    $phone_number = sanitize($_POST['phone-number']);

    if (strlen($first_name) < 2 || strlen($first_name) > 50 ||
        strlen($last_name) < 2 || strlen($last_name) > 50 ||
        strlen($phone_number) < 10 || strlen($phone_number) > 15) {
        $error = 'Invalid input. Please check the length of your entries.';
    }

    if (!isset($error)) {
        try {
            $pdo->beginTransaction();

            // Manually increment customer ID
            $sql = 'SELECT MAX(customer_id) + 1 AS next_customer_id FROM customer';
            $stmt = $pdo->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $customer_id = $result['next_customer_id'];

            // Insert into customer table
            $sql = 'INSERT INTO customer(customer_id, first_name, last_name, phone_number, expenditure) VALUES (?, ?, ?, ?, 0)';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$customer_id, $first_name, $last_name, $phone_number]);

            $successfully = 'New customer added successfully';
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            die('Could not add customer: ' . $e->getMessage());
        }
    }
}

require_once './layout/header.php';
?>

<div class="bg-light admin__main">
    <div class="container">
        <?php
        if (isset($error)):
            ?>
            <p class="alert alert-danger fw-500">
                <?= $error ?>
            </p>
            <?php
        endif;
        if (isset($successfully)):
            ?>
            <p class="alert alert-success fw-500">
                <?= $successfully ?>
            </p>
            <?php
        endif;
        ?>
        <form class="p-4 rounded bg-white form" method="post" enctype="multipart/form-data">
            <div class="d-flex align-items-center justify-content-between">
                <h3>Add Customer</h3>
                <a href="./customer.php" class="btn btn-danger lh-100">Back</a>
            </div>
            <label class="form__label" for="first-name">First Name</label>
            <input type="text" name="first-name" id="first-name" class="form-control mb-3 form__inp" placeholder="First Name"
                minlength="2" maxlength="50" required>
            <label class="form__label" for="last-name">Last Name</label>
            <input type="text" name="last-name" id="last-name" class="form-control mb-3 form__inp" placeholder="Last Name"
                minlength="2" maxlength="50" required>
            <label class="form__label" for="phone-number">Phone Number</label>
            <input type="tel" name="phone-number" id="phone-number" class="form-control mb-3 form__inp" placeholder="Phone Number"
                minlength="10" maxlength="15" required>

            <input type="submit" class="btn btn-dark w-100 form__btn" name="add-customer" value="Add Customer">
        </form>
    </div>
</div>

<?php
require_once './layout/footer.php';
?>
