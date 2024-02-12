<?php
require_once 'utils.php';
require_once 'config.php';
require_once 'connect.php';

try {
    $sql = 'SELECT * FROM product'; // Changed to 'product' from 'products'
    $stmt = $pdo->query($sql);

    if ($stmt) {
        $product_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        die('Error getting data from khanh');
    }
} catch (PDOException $e) {
    die('Retrieving data from database failed: ' . $e->getMessage());
}

require_once './layout/header.php';
?>

<div class="container-fluid px-0">
    <div class="bg-white admin-wraps">
        <div id="admin__nav" class="">
            <ul class="list-unstyled text-capitalize">
                <!-- ... your navigation links ... -->
            </ul>
        </div>
    </div>
    <div class="admin__views">
        <div class="row admin__view-page">
            <div class="col-md-12 mt-4">
                <div class="p-3 bg-white rounded">
                <ul class="list-unstyled text-capitalize">
                <li class="mb-1 admin__nav-item">
                    <a href="./orders.php" class="p-2 rounded admin__nav-item-link">Order</a>
                </li>
                <li class="mb-1 admin__nav-item">
                    <a href="./product.php" class="p-2 rounded admin__nav-item-link">Product</a>
                </li>
                <li class="mb-1 admin__nav-item">
                    <a href="./customer.php" class="p-2 rounded admin__nav-item-link">Customer</a>
                </li>
                <li class="mb-1 admin__nav-item">
                    <a href="./staff.php" class="p-2 rounded admin__nav-item-link admin__nav-item-link--active">Staff</a>
                </li>

            </ul>
                    <div class="d-flex align-items-center justify-content-between">
                        <h3>Product List</h3>
                        <div>
                            <a href="./add-product.php" class="btn btn-primary">Add Product</a>
                        </div>
                    </div>
                    <table class="table table-responsive">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Cost</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Functions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($product_data as $product) : ?>
                                <tr>
                                    <td><?= $product['prod_id'] ?></td>
                                    <td><?= $product['prod_name'] ?></td>
                                    <td><?= $product['prod_type'] ?></td>
                                    <td><?= $product['prod_cost'] ?></td>
                                    <td><?= $product['stock'] ?></td>
                                    <td><?= $product['price'] ?></td>
                                    <td>
                                        <a class="btn btn-warning me-2" href="./edit-product.php?id=<?= $product['prod_id'] ?>">Edit</a>
                                        <a onclick="deleteProduct(event, <?= $product['prod_id'] ?>)" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once './layout/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.27/sweetalert2.min.js" integrity="sha512-mJQ9oQHzLM2zXe1cwiHmnMddNrmjv1YlaKZe1rM4J7q8JTnNn9UgeJVBV9jyV/lVGdXymVx6odhgwNZjQD8AqA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.27/sweetalert2.css" integrity="sha512-eRBMRR/qeSlGYAb6a7UVwNFgXHRXa62u20w4veTi9suM2AkgkJpjcU5J8UVcoRCw0MS096e3n716Qe9Bf14EyQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<script>
    function deleteProduct(event, product_id) {
        event.preventDefault();

        Swal.fire({
            title: 'Do you really want to delete this item?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'delete-product.php?id=' + product_id;
                console.log('Delete function triggered');
            }
        });
    }
</script>
