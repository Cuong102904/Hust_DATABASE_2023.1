<?php
require_once 'config.php';
require_once 'connect.php';

function executeCheckStockTrigger(PDO $pdo) {
    try {
        // Simulate an INSERT statement that triggers the check_stock_trigger
        $pdo->exec("INSERT INTO orderline (order_id, product_id, quantity) VALUES (1, 1, 10)");

        return "check_stock_trigger executed successfully!";
    } catch (PDOException $e) {
        if ($e->getCode() == 'P0001') {
            // Handle the specific exception for out-of-stock situation
            return "Error: Product is out of stock!";
        } else {
            // Handle other exceptions
            return "Error: " . $e->getMessage();
        }
    }
}

// Other functions...




function executeUpdateStockTrigger($pdo) {
    $pdo->exec("INSERT INTO orderline (order_id, product_id, quantity) VALUES (2, 2, 100);");
}

// Add other functions here...
