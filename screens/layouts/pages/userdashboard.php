<?php
include '../../../logic/database_conn.php';

// Fetch items
$result = $conn->query("SELECT * FROM items");
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <style>
        .container { width: 80%; margin: auto; }
        .items-grid {
            display: flex; flex-wrap: wrap; gap: 24px;
        }
        .item-card {
            background: #fff; border: 1px solid #ddd; border-radius: 8px;
            padding: 20px; width: 250px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex; flex-direction: column; align-items: flex-start;
        }
        .item-card strong { font-size: 1.2em; }
        .item-card em { color: #27ae60; margin-top: 8px; }
        .add-cart-btn {
            margin-top: 12px; background: #4CAF50; color: #fff; border: none;
            padding: 8px 16px; border-radius: 4px; cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Welcome to the Shop!</h2>
    <div class="items-grid">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="item-card">
                <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                <div><?php echo htmlspecialchars($row['description']); ?></div>
                <em>$<?php echo number_format($row['price'], 2); ?></em>
                <button class="add-cart-btn" onclick="alert('Added to cart!')">Add to Cart</button>
            </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>