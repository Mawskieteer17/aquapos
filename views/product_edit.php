<?php
session_start();
require_once("../config/db.php");

if (!isset($_GET['id'])) {
  header("Location: products.php");
  exit();
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM products WHERE id = $id");

if ($result->num_rows == 0) {
  echo "Product not found.";
  exit();
}

$product = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2>✏️ Edit Product</h2>
      <a href="products.php" class="btn btn-secondary btn-sm">⬅ Back</a>
    </div>

    <div class="card shadow p-4">
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

        <button type="submit" class="btn btn-primary">Update Product</button>
      </form>
    </div>
  </div>

</body>
</html>
