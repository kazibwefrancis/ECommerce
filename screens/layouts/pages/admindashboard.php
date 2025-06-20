<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../../../screens/adminlogin.php');
    exit;
}
include '../../../logic/database_conn.php';

// Handle add item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO items (name, description, price) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $name, $desc, $price); // s = string, s = string, d = double (decimal)
    if (!$stmt->execute()) {
        echo "<div style='color:red;'>Error: " . $stmt->error . "</div>";
    }
    $stmt->close();
}

// Handle remove item
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    $conn->query("DELETE FROM items WHERE id=$id");
}

// Fetch items
$result = $conn->query("SELECT * FROM items");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        .container { width: 80%; margin: auto; }
        .item-card {
            border: 1px solid #ccc; border-radius: 8px; padding: 16px; margin: 16px 0;
            display: flex; justify-content: space-between; align-items: center;
            background: #f9f9f9;
        }
        .item-info { flex: 1; }
        .remove-btn { background: #e74c3c; color: #fff; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
        .add-form { margin-bottom: 32px; }
        .add-form input, .add-form textarea { margin: 4px 0; width: 100%; padding: 8px; }
        .add-form button { background: #27ae60; color: #fff; border: none; padding: 10px 16px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
<div class="container">
    <h2>Admin Dashboard</h2>
    <form class="add-form" method="post">
        <input type="text" name="name" placeholder="Item Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="number" name="price" placeholder="Price" step="0.01" required>
        <button type="submit" name="add_item">Add Item</button>
    </form>
    <h3>Items List</h3>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="item-card">
            <div class="item-info">
                <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                <?php echo htmlspecialchars($row['description']); ?><br>
                <em>Price: $<?php echo number_format($row['price'], 2); ?></em>
            </div>
            <form method="get" style="margin:0;">
                <button class="remove-btn" name="remove" value="<?php echo $row['id']; ?>" onclick="return confirm('Remove this item?')">Remove</button>
            </form>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>