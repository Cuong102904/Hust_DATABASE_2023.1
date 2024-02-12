<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trigger Functions</title>
</head>
<body>

<?php
require_once 'functions.php';


try {
    $pdo = new PDO("pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $result = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['check_stock_trigger'])) {
            $result = executeCheckStockTrigger($pdo);
        } elseif (isset($_POST['update_stock_trigger'])) {
            $result = executeUpdateStockTrigger($pdo);
        }
        // Add other conditions for buttons here...
    }
} catch (PDOException $e) {
    $result = "Error: " . $e->getMessage();
}

$pdo = null; // Close the connection
?>

<!-- Buttons to trigger functions -->
<form method="post">
    <button type="submit" name="check_stock_trigger">Execute check_stock_trigger</button>
    <button type="submit" name="update_stock_trigger">Execute update_stock_trigger</button>
    <!-- Add other buttons for functions here... -->
</form>

<!-- Display results -->
<div>
    <h2>Results:</h2>
    <p><?php echo $result; ?></p>
</div>

</body>
</html>
