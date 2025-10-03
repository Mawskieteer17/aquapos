<?php
require_once("../config/db.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM products WHERE id = $id";

    if ($conn->query($sql)) {
        header("Location: ../views/products.php?deleted=1");
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    header("Location: ../views/products.php");
}
