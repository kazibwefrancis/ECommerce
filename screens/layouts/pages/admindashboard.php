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
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO items (name, description, price, quantity) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $name, $desc, $price, $quantity); // s = string, s = string, d = double (decimal), i = integer
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
<div id="sidebar" style="position:fixed;top:0;left:-250px;width:250px;height:100%;background:#222;color:#fff;transition:left 0.3s;z-index:1000;padding:30px 10px;">
    <h2 id="sidebarMessage">This is my side bar</h2>
</div>
<button id="sidebarToggle" style="position:fixed;top:20px;left:20px;z-index:1100;padding:10px 15px;border:none;background:#27ae60;color:#fff;border-radius:4px;cursor:pointer;">
    ☰
</button>
<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebarMessage = document.getElementById('sidebarMessage');
    let sidebarOpen = false;

    toggleBtn.onclick = function () {
        sidebarOpen = !sidebarOpen;
        sidebar.style.left = sidebarOpen ? '0' : '-250px';
        if (sidebarOpen) {
            sidebarMessage.textContent = "Hello, this is my sidebar";
        }
    };

    document.addEventListener('click', function (e) {
        if (sidebarOpen && !sidebar.contains(e.target) && e.target !== toggleBtn) {
            sidebar.style.left = '-250px';
            sidebarOpen = false;
        }
    });
</script>
<div class="container">
    <h2>Admin Dashboard</h2>
    <form class="add-form" method="post">
        <input type="text" name="name" placeholder="Item Name" required>
        <textarea name="description" placeholder="Description" required></textarea>
        <input type="number" name="price" placeholder="Price" step="0.01" required>
        <div class="form-group">
            <label for="quantity">Product Quantity:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="0" required>
            <small class="form-text text-muted">Enter the number of items in stock</small>
        </div>
        <button type="submit" name="add_item">Add Item</button>
    </form>
    <h3>Items List</h3>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="item-card">
            <div class="item-info">
                <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                <?php echo htmlspecialchars($row['description']); ?><br>
                <em>Price: $<?php echo number_format($row['price'], 2); ?></em><br>
                <em>Quantity: <?php echo intval($row['quantity']); ?></em>
            </div>
            <form method="get" style="margin:0;">
                <button class="remove-btn" name="remove" value="<?php echo $row['id']; ?>" onclick="return confirm('Remove this item?')">Remove</button>
            </form>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>