<?php
session_start();
require_once("../config/db.php");
$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="card shadow p-4" style="max-width: 500px; margin: auto;">
      <h3 class="mb-3">✏ Edit Product</h3>
      <form method="POST" action="../controllers/product_edit.php">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" class="form-control" value="<?php echo $product['name']; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Category</label>
          <input type="text" name="category" class="form-control" value="<?php echo $product['category']; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Price</label>
          <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Stock</label>
          <input type="number" name="stock" class="form-control" value="<?php echo $product['stock']; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Update Product</button>
      </form>
    </div>
  </div>

</body>
</html>
