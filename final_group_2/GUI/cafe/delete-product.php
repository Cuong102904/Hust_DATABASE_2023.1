<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

if (isset($_GET['id'])) {
    $id = sanitize($_GET['id']);

    try {
        $pdo->beginTransaction();

        // Check if the product with the given ID exists
        $sql = 'SELECT * FROM product WHERE product.prod_id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Product exists, delete it
            $sql = 'DELETE FROM product WHERE product.prod_id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);

            $successfully = 'Product deleted successfully';
            $pdo->commit();
        } else {
            $error = 'Product not found';
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo 'Could not delete product: ' . $e->getMessage();
    }

    header('location: ./product.php');
    exit();
} else {
    echo '<script type="text/javascript>';
    echo 'alert("Failed to delete product")';
    echo '</script>';
    header('location: ./product.php');
    exit();
}
?>
