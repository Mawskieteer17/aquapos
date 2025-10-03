<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>AquaPOS Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">🐠 AquaPOS</a>
      <div class="d-flex">
        <a href="products.php" class="btn btn-light btn-sm me-2">Manage Products</a>
        <a href="pos.php" class="btn btn-light btn-sm me-2">Open POS</a>
        <a href="sales.php" class="btn btn-light btn-sm me-2">Sales Report</a>
        <a href="../controllers/logout.php" class="btn btn-danger btn-sm">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Content -->
  <div class="container mt-4">
    <div class="card shadow p-4">
      <h2>Welcome, <span class="text-primary"><?php echo $_SESSION['user']; ?></span> 👋</h2>
      <p class="text-muted">Manage your aquarium shop with ease.</p>
    </div>
  </div>

</body>
</html>
