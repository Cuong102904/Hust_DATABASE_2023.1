<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    try {
        $sql = 'SELECT * FROM product WHERE prod_id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$product_id]);
        $product_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product_data) {
            $error = 'ID of product does not exist';
        }
    } catch (PDOException $e) {
        die('Get data from table product failed: ' . $e->getMessage());
    }
}

if (isset($_POST['edit-product'])) {
    $product_id = sanitize($_POST['id']);
    $product_name = sanitize($_POST['name']);
    $product_type = sanitize($_POST['type']);
    $product_cost = sanitize($_POST['cost']);
    $product_stock = sanitize($_POST['stock']);
    $product_price = sanitize($_POST['price']);

    if (strlen($product_name) < 4 || strlen($product_name) > 200) {
        $error = 'Product name must be between 4 and 200 characters';
    }

    if (empty($error)) {
        try {
            $pdo->beginTransaction();

            $sql = 'UPDATE product SET prod_name = ?, prod_type = ?, prod_cost = ?, stock = ?, price = ? WHERE prod_id = ?';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$product_name, $product_type, $product_cost, $product_stock, $product_price, $product_id]);

            $pdo->commit();
            header('location: ./product.php');
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Editing product failed: ' . $e->getMessage();
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
                <h3>Edit Product</h3>
                <a href="./product.php" class="btn btn-danger lh-100">Back</a>
            </div>
            <?php if (isset($product_data)): ?>
                <input type="text" name="id" hidden value="<?= $product_data['prod_id'] ?>">
            <?php endif; ?>
            <label class="form__label" for="product-name">Name</label>
            <input type="text" name="name" id="product-name" class="form-control mb-3 form__inp" placeholder="Product name"
                value="<?= (isset($product_data)) ? $product_data['prod_name'] : '' ?>" minlength="4" maxlength="200" required>
            <label class="form__label" for="product-type">Type</label>
            <input type="text" name="type" id="product-type" class="form-control mb-3 form__inp" placeholder="Product type"
                value="<?= (isset($product_data)) ? $product_data['prod_type'] : '' ?>" minlength="4" maxlength="200" required>
            <label class="form__label" for="product-cost">Cost</label>
            <input type="text" name="cost" id="product-cost" class="form-control mb-3 form__inp" placeholder="Product cost"
                value="<?= (isset($product_data)) ? $product_data['prod_cost'] : '' ?>" required>
            <label class="form__label" for="product-stock">Stock</label>
            <input type="text" name="stock" id="product-stock" class="form-control mb-3 form__inp" placeholder="Product stock"
                value="<?= (isset($product_data)) ? $product_data['stock'] : '' ?>" required>
            <label class="form__label" for="product-price">Price</label>
            <input type="text" name="price" id="product-price" class="form-control mb-3 form__inp" placeholder="Product price"
                value="<?= (isset($product_data)) ? $product_data['price'] : '' ?>" required>
            <input type="submit" class="btn btn-dark w-100 form__btn" name="edit-product" value="Edit Product">
        </form>
    </div>
</div>

<?php
require_once './layout/footer.php';
?>
