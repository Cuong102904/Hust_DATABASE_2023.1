<?php
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected product type from the form
    $selectedProductType = isset($_POST['productType']) ? $_POST['productType'] : '';

    // Validate or sanitize the input if needed

    // Now, you can save $selectedProductType to the database in the 'product_type' column.
    // Replace the database connection code and SQL query with your actual implementation.
    try {
        $pdo = new PDO("pgsql:host=localhost;port=5432;dbname=khanh", 'postgres', 'khanh');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'INSERT INTO products(product-name, product-url, product-type, product-cost, product-stock, product-price) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$selectedProductType]);

        echo 'Data successfully inserted into the database.';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>
