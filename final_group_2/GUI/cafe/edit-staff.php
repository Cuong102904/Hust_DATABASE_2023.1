<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

if (isset($_GET['id'])) {
    $staff_id = intval($_GET['id']);
    try {
        $sql = 'SELECT * FROM staff WHERE staff_id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$staff_id]);
        $staff_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$staff_data) {
            $error = 'Staff member with this ID does not exist';
        }
    } catch (\Exception $e) {
        die('Get data from table staff failed: ' . $e->getMessage());
    }
}

if (isset($_POST['edit-staff'])) {
    $staff_id = sanitize($_POST['id']);
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $phone_number = sanitize($_POST['phone_number']);
    $address_staff = sanitize($_POST['address_staff']);

    if (empty($first_name) || empty($last_name) || empty($address_staff) || empty($phone_number)) {
        $error = 'All fields are required';
    }

    if (!isset($error)) {
        try {
            $pdo->beginTransaction();

            $sql = 'SELECT * FROM staff WHERE staff_id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$staff_id]);
            $staff_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($staff_data) {
                $sql = 'UPDATE staff SET first_name = ?, last_name = ?, phone_number = ?, address_staff = ? WHERE staff_id = ?';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$first_name, $last_name, $phone_number, $address_staff, $staff_id]);

                $pdo->commit();
                header('location: ./staff.php');
            } else {
                $error = 'Staff member with this ID does not exist';
            }
        } catch (\Exception $e) {
            $pdo->rollback();
            die('Editing staff member failed: ' . $e->getMessage());
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
        ?>
        <form class="p-4 rounded bg-white form" method="post" enctype="multipart/form-data">
            <div class="d-flex align-items-center justify-content-between">
                <h3>
                    Edit Staff Member
                </h3>
                <a href="./staff.php" class="btn btn-danger lh-100">Back</a>
            </div>
            <?php
            if (isset($staff_data)):
                ?>
                <input type="text" name="id" hidden value="<?= $staff_data['staff_id'] ?>">
                <?php
            endif;
            ?>
            <label class="form__label" for="first_name">First Name</label>
            <input type="text" name="first_name" id="first_name" class="form-control mb-3 form__inp" placeholder="First Name"
                value="<?= (isset($staff_data)) ? $staff_data['first_name'] : '' ?>" minlength="2" maxlength="50" required>
            <label class="form__label" for="last_name">Last Name</label>
            <input type="text" name="last_name" id="last_name" class="form-control mb-3 form__inp" placeholder="Last Name"
                value="<?= (isset($staff_data)) ? $staff_data['last_name'] : '' ?>" minlength="2" maxlength="50" required>
            <label class="form__label" for="phone_number">Phone Number</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control mb-3 form__inp" placeholder="Phone Number"
                value="<?= (isset($staff_data)) ? $staff_data['phone_number'] : '' ?>" minlength="10" maxlength="15" required>
            <label class="form__label" for="address_staff">Address</label>
            <input type="text" name="address_staff" id="address_staff" class="form-control mb-3 form__inp" placeholder="Address"
                value="<?= (isset($staff_data)) ? $staff_data['address_staff'] : '' ?>" minlength="4" maxlength="100" required>

            <input type="submit" class="btn btn-dark w-100 form__btn" name="edit-staff" value="Edit Staff Member">
        </form>
    </div>
</div>

<?php
require_once './layout/footer.php';
?>
