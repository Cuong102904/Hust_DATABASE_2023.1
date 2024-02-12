<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

if (isset($_GET['id'])) {
    $staff_id = sanitize($_GET['id']);

    try {
        $pdo->beginTransaction();

        // Check if the staff with the given ID exists
        $sql = 'SELECT * FROM staff WHERE staff.staff_id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$staff_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Staff exists, delete it
            $sql = 'DELETE FROM staff WHERE staff.staff_id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$staff_id]);

            $successfully = 'Staff deleted successfully';
            $pdo->commit();
        } else {
            $error = 'Staff not found';
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo 'Could not delete staff: ' . $e->getMessage();
    }

    header('location: ./staff.php');
    exit();
} else {
    echo '<script type="text/javascript>';
    echo 'alert("Failed to delete staff")';
    echo '</script>';
    header('location: ./staff.php');
    exit();
}
?>
