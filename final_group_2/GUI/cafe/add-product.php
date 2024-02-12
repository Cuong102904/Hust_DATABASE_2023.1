<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

if (isset($_POST['add-product'])) {
    $product_id = sanitize($_POST['product-id']);
    $product_name = sanitize($_POST['name']);
    $product_type = sanitize($_POST['productType']);
    $product_cost = sanitize($_POST['cost']);
    $product_stock = sanitize($_POST['stock']);
    $product_price = sanitize($_POST['price']); // Corrected the variable name

    if (strlen($product_name) < 4 || strlen($product_name) > 100) {
        $error = 'Product name must be more than 4 characters and less than 100 characters';
    }

    if (!isset($error)) {
        try {
            $pdo->beginTransaction();

            // Check if the product with the given name already exists
            $sql = 'SELECT * FROM product WHERE product.prod_id = ? OR product.prod_name = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$product_id, $product_name]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                // Product does not exist, insert a new one
                $sql = 'INSERT INTO product(prod_id, prod_name, prod_type, prod_cost, stock, price) VALUES (?, ?, ?, ?, ?, ?)';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$product_id, $product_name, $product_type, $product_cost, $product_stock, $product_price]);

                $successfully = 'New product added successfully';
                $pdo->commit();
            } else {
                $error = 'Product ID or name already exists';
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            die('Could not add product: ' . $e->getMessage());
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
                <h3>
                    Add Product
                </h3>
                <a href="./product.php" class="btn btn-danger lh-100">Back</a>
            </div>
            <label class="form__label" for="product-id">ID</label>
            <input type="text" name="product-id" id="product-id" class="form-control mb-3 form__inp" placeholder="Product ID"
                minlength="1" maxlength="100" required>
            <label class="form__label" for="product-name">Name</label>
            <input type="text" name="name" id="product-name" class="form-control mb-3 form__inp" placeholder="Product Name"
                minlength="4" maxlength="100" required>
            <label for="productType">Select Product Type:</label>
            <select name="productType" id="productType">
                <option value="coffee">Coffee</option>
                <option value="cake">Cake</option>
                <option value="juice">Juice</option>
                <option value="ice_cream">Ice Cream</option>
            </select>
            <label class="form__label" for="product-stock">Stock</label>
            <input type="text" name="stock" id="product-stock" class="form-control mb-3 form__inp" placeholder="Product Stock" minlength="1" maxlength="100" required>
            <label class="form__label" for="product-cost">Cost</label>
            <input type="text" name="cost" id="product-cost" class="form-control mb-3 form__inp" placeholder="Product Cost" minlength="4" maxlength="100" required>
            <label class="form__label" for="product-price">Price</label>
            <input type="text" name="price" id="product-price" class="form-control mb-3 form__inp" placeholder="Product Price" minlength="4" maxlength="100" required>

            <input type="submit" class="btn btn-dark w-100 form__btn" name="add-product" value="Add Product">
        </form>
    </div>
</div>

<?php
require_once './layout/footer.php';
?>
