<?php
session_start();
require_once("../config/db.php");
$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>📦 Products</h2>
      <div>
        <a href="dashboard.php" class="btn btn-secondary btn-sm">⬅ Back to Dashboard</a>
        <a href="product_add.php" class="btn btn-primary btn-sm">➕ Add Product</a>
        <a href="bulk_add_products.php" class="btn btn-success btn-sm">📑 Bulk Add Products</a>
      </div>
    </div>

    <div class="card shadow p-3">
      <table class="table table-hover table-bordered">
        <thead class="table-primary">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
          <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td>₱<?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td>
              <a href="product_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="../controllers/product_delete.php?id=<?php echo $row['id']; ?>" 
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('Are you sure you want to delete this product?');">
                 Delete
              </a>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
