<?php
session_start();
include '../../../logic/database_conn.php';

// Use the registered user's name from session
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

// Fetch items
$result = $conn->query("SELECT * FROM items");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shop - User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
            margin: 0;
            min-height: 100vh;
        }
        .navbar {
            background: #222;
            color: #fff;
            padding: 0 40px;
            display: flex;
            align-items: center;
            height: 60px;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        }
        .navbar .shop-name {
            font-size: 1.7em;
            font-weight: bold;
            letter-spacing: 2px;
            color: #4CAF50;
        }
        .navbar .menu {
            display: flex;
            gap: 30px;
        }
        .navbar .menu a {
            color: #fff;
            text-decoration: none;
            font-size: 1em;
            padding: 8px 0;
            position: relative;
            transition: color 0.2s;
        }
        .navbar .menu a:hover {
            color: #4CAF50;
            border-bottom: 2px solid #4CAF50;
        }
        .navbar .right {
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .username {
            font-size: 1em;
            color: #fff;
            background: #4CAF50;
            padding: 6px 18px;
            border-radius: 20px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(76,175,80,0.08);
            letter-spacing: 1px;
        }
        .container {
            width: 95%;
            max-width: 1200px;
            margin: 30px auto 0 auto;
        }
        .welcome-section {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(76,175,80,0.08);
            padding: 36px 32px 28px 32px;
            margin-bottom: 36px;
            display: flex;
            align-items: center;
            gap: 32px;
            animation: fadeIn 1s;
        }
        .welcome-icon {
            font-size: 3em;
            color: #4CAF50;
            background: #e8f5e9;
            border-radius: 50%;
            padding: 18px;
            margin-right: 18px;
            box-shadow: 0 2px 8px rgba(76,175,80,0.07);
        }
        .welcome-text {
            flex: 1;
        }
        .welcome-text h2 {
            margin: 0 0 10px 0;
            font-size: 2.1em;
            color: #222;
            font-weight: 700;
        }
        .welcome-text p {
            margin: 0;
            color: #555;
            font-size: 1.15em;
            letter-spacing: 0.2px;
        }
        .items-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
            justify-content: flex-start;
        }
        .item-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 14px;
            padding: 24px 20px 20px 20px;
            width: 260px;
            box-shadow: 0 2px 12px rgba(76,175,80,0.07);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            transition: box-shadow 0.2s, transform 0.2s;
            position: relative;
        }
        .item-card:hover {
            box-shadow: 0 8px 24px rgba(76,175,80,0.13);
            transform: translateY(-4px) scale(1.02);
        }
        .item-card strong {
            font-size: 1.18em;
            color: #222;
            margin-bottom: 6px;
        }
        .item-card .desc {
            color: #666;
            font-size: 1em;
            margin-bottom: 10px;
            min-height: 48px;
        }
        .item-card em {
            color: #27ae60;
            margin-top: 8px;
            font-size: 1.1em;
            font-style: normal;
            font-weight: 500;
        }
        .add-cart-btn {
            margin-top: 16px;
            background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(76,175,80,0.08);
            transition: background 0.2s, transform 0.2s;
        }
        .add-cart-btn:hover {
            background: linear-gradient(90deg, #11998e 0%, #38ef7d 100%);
            transform: scale(1.05);
        }
        .logout-btn {
            margin-left: 18px;
            color: #fff;
            background: #e74c3c;
            padding: 6px 16px;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }
        .logout-btn:hover {
            background: #c0392b;
        }
        @media (max-width: 900px) {
            .items-grid { flex-direction: column; align-items: center; }
            .container { width: 99%; }
            .welcome-section { flex-direction: column; text-align: center; gap: 18px; }
            .welcome-icon { margin: 0 auto 12px auto; }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px);}
            to { opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <!-- This is the new, functional cart widget. It's already linked to cart.php -->
    <?php include __DIR__ . '/../../../includes/cart_widget.php'; ?>

    <div class="navbar">
        <div class="shop-name">Rodney's Shop</div>
        <div class="menu">
            <a href="#">Home</a>
            <a href="../../../products.php">Shop</a>
            <a href="#">About</a>
            <a href="#">Contact</a>
        </div>
        <div class="right">
            <!-- The old cart icon with the popup has been removed from here -->
            <span class="username">
                <?php echo htmlspecialchars($username); ?>
            </span>
            <a href="../../logout.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="welcome-section">
            <div class="welcome-icon">
                <i class="fa fa-handshake"></i>
            </div>
            <div class="welcome-text">
                <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
                <p>
                    We're excited to have you at <span style="color:#4CAF50;font-weight:600;">Rodney's Shop</span>.<br>
                    Discover amazing products and add your favorites to the cart. Happy shopping!
                </p>
            </div>
        </div>
        <div class="items-grid">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="item-card">
                    <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                    <div class="desc"><?php echo htmlspecialchars($row['description']); ?></div>
                    <div class="price">$<?php echo number_format($row['price'], 2); ?></div>
                    <div class="stock"><?php echo htmlspecialchars($row['quantity']); ?> in stock</div>
                    
                    <form class="add-to-cart-form" onsubmit="return addToCartAjax(this);">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
                        <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="ajax" value="1">
                        
                        <button type="submit" class="add-cart-btn">
                            <i class="fa fa-cart-plus"></i> Add to Cart
                        </button>
                        <div class="success-message"></div>
                        <div class="error-message"></div>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        // This script sends an AJAX request to your cart_actions.php
        function addToCartAjax(form) {
            const formData = new FormData(form);
            const button = form.querySelector('.add-cart-btn');
            const successMsg = form.querySelector('.success-message');
            const errorMsg = form.querySelector('.error-message');

            successMsg.style.display = 'none';
            errorMsg.style.display = 'none';
            button.disabled = true;
            button.innerHTML = 'Adding...';

            fetch('../../../cart_actions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartWidget(); // This function is in cart_widget.php
                    successMsg.textContent = 'Added to cart!';
                    successMsg.style.display = 'block';
                    setTimeout(() => { successMsg.style.display = 'none'; }, 3000);
                } else {
                    errorMsg.textContent = data.message || 'Could not add item.';
                    errorMsg.style.display = 'block';
                    setTimeout(() => { errorMsg.style.display = 'none'; }, 4000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMsg.textContent = 'An error occurred.';
                errorMsg.style.display = 'block';
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML = '<i class="fa fa-cart-plus"></i> Add to Cart';
            });

            return false; 
        }

        // IMPORTANT: Also, make sure to delete any old JavaScript functions 
        // like showCart(), getCart(), setCart(), etc. from this script tag.
    </script>
</body>
</html>