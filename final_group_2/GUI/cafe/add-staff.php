<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

if (isset($_POST['add-staff'])) {
    $staff_id = get_next_staff_id($pdo); // Function to get the next staff ID
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $address = sanitize($_POST['address']);
    $phone_number = sanitize($_POST['phone_number']);
    $selected_role = sanitize($_POST['role']);

    if (strlen($first_name) < 2 || strlen($first_name) > 100 ||
        strlen($last_name) < 2 || strlen($last_name) > 100) {
        $error = 'First name and last name must be between 2 and 100 characters.';
    }

// ...

if (!isset($error)) {
    try {
        $pdo->beginTransaction();

        // Check if the staff with the given name already exists
        $sql_check_staff = 'SELECT * FROM staff WHERE first_name = ? AND last_name = ?';
        $stmt_check_staff = $pdo->prepare($sql_check_staff);
        $stmt_check_staff->execute([$first_name, $last_name]);
        $result_check_staff = $stmt_check_staff->fetch(PDO::FETCH_ASSOC);

        if (!$result_check_staff) {
            // Staff does not exist, insert a new one
            $sql_insert_staff = 'INSERT INTO staff(staff_id, first_name, last_name, address_staff, phone_number, total_star, rating_quantity, rating) VALUES (?, ?, ?, ?, ?, 5, 1, 5)';
            $stmt_insert_staff = $pdo->prepare($sql_insert_staff);
            $stmt_insert_staff->execute([$staff_id, $first_name, $last_name, $address, $phone_number]);

            $successfully = 'New staff added successfully';

            // Fetch the address from the staff table
            $sql_fetch_address = 'SELECT address_staff FROM staff WHERE staff_id = ?';
            $stmt_fetch_address = $pdo->prepare($sql_fetch_address);
            $stmt_fetch_address->execute([$staff_id]);
            $address_staff_result = $stmt_fetch_address->fetch(PDO::FETCH_ASSOC);

            $address_staff = $address_staff_result['address_staff'];

            // Check the selected role and insert additional data
            switch ($selected_role) {
                case 'chef':
                    // Example chef-specific fields and queries
                    $year_experiment = isset($_POST['chef_years_experiment']) ? sanitize($_POST['chef_years_experiment']) : null;
                    $award = isset($_POST['chef_award']) ? sanitize($_POST['chef_award']) : null;

                    $sql_chef = 'INSERT INTO chef(staff_id, address_staff, year_experiment, award, phone_number) VALUES (?, ?, ?, ?, ?)';
                    $stmt_chef = $pdo->prepare($sql_chef);
                    $stmt_chef->execute([$staff_id, $address_staff, $year_experiment, $award, $phone_number]);

                    // Add chef-specific certificate if needed
                    break;

                // Add cases for other roles if needed

                default:
                    // No additional action for unknown roles
                    break;
            }

            $pdo->commit();
        } else {
            $error = 'Staff with the given name already exists';
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        die('Could not add staff: ' . $e->getMessage());
    }
}

// ...

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
                <h3>
                    Add Staff
                </h3>
                <a href="./staff.php" class="btn btn-danger lh-100">Back</a>
            </div>
            <label class="form__label" for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" class="form-control mb-3 form__inp" placeholder="First Name"
                minlength="2" maxlength="100" required>

            <label class="form__label" for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" class="form-control mb-3 form__inp" placeholder="Last Name"
                minlength="2" maxlength="100" required>

            <label class="form__label" for="address">Address</label>
            <input type="text" name="address" id="address" class="form-control mb-3 form__inp" placeholder="Address"
                minlength="2" maxlength="200" required>

            <label class="form__label" for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control mb-3 form__inp" placeholder="Phone Number"
                minlength="2" maxlength="20" required>

            <label class="form__label" for="role">Select Role:</label>
            <select name="role" id="role" onchange="toggleForms()">
                <option value="waiter">Waiter</option>
                <option value="chef">Chef</option>
                <option value="bartender">Bartender</option>
            </select>

            <div id="chefFields" style="display:none;">
                <label class="form__label" for="chef_years_experiment">Years of experiment</label>
                <input type="text" name="chef_years_experiment" id="chef_years_experiment" class="form-control mb-3 form__inp" placeholder="Years of experiment"
                    minlength="1" maxlength="100">

                <label class="form__label" for="chef_award">Award</label>
                <input type="text" name="chef_award" id="chef_award" class="form-control mb-3 form__inp" placeholder="Award"
                    minlength="2" maxlength="100">

                <label class="form__label" for="chef_certificate_name">Certificate Name</label>
                <input type="text" name="chef_certificate_name" id="chef_certificate_name" class="form-control mb-3 form__inp" placeholder="Certificate Name"
                    minlength="2" maxlength="100">
            </div>

            <div id="bartenderFields" style="display:none;">
                <label class="form__label" for="bartender_years_experiment">Years of experiment</label>
                <input type="text" name="bartender_years_experiment" id="bartender_years_experiment" class="form-control mb-3 form__inp" placeholder="Years of experiment"
                    minlength="1" maxlength="100">

                <label class="form__label" for="bartender_award">Award</label>
                <input type="text" name="bartender_award" id="bartender_award" class="form-control mb-3 form__inp" placeholder="Award"
                    minlength="2" maxlength="100">

                <label class="form__label" for="bartender_certificate_name">Certificate Name</label>
                <input type="text" name="bartender_certificate_name" id="bartender_certificate_name" class="form-control mb-3 form__inp" placeholder="Certificate Name"
                    minlength="2" maxlength="100">
            </div>

            <input type="submit" class="btn btn-dark w-100 form__btn" name="add-staff" value="Add Staff">
        </form>
    </div>
</div>

<script>
    function toggleForms() {
        var role = document.getElementById('role').value;
        var chefFields = document.getElementById('chefFields');
        var bartenderFields = document.getElementById('bartenderFields');

        if (role === 'chef') {
            chefFields.style.display = 'block';
            bartenderFields.style.display = 'none';
        } else if (role === 'bartender') {
            chefFields.style.display = 'none';
            bartenderFields.style.display = 'block';
        } else {
            chefFields.style.display = 'none';
            bartenderFields.style.display = 'none';
        }
    }
</script>

<?php
require_once './layout/footer.php';
?>
