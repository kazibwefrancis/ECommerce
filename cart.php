<?php
// filepath: c:\xampp\htdocs\ECommerce\cart.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'includes/cart.php';

// Get cart items
$cartItems = CartManager::getCartItems();
$cartTotal = CartManager::getCartTotal();
$cartCount = CartManager::getCartCount();

// Handle removing items (non-AJAX fallback)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {
    $productId = intval($_POST['product_id'] ?? 0);
    CartManager::removeFromCart($productId);
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart - ECommerce</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
            margin: 0;
            padding-top: 30px;
            min-height: 100vh;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto 40px auto;
            padding: 0 20px;
        }
        .page-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        .page-header h1 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 2rem;
        }
        .page-header p {
            margin: 0;
            color: #7f8c8d;
        }
        .nav-links {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            gap: 15px;
        }
        .nav-links a {
            display: inline-block;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s, transform 0.2s;
        }
        .nav-links a:hover {
            background: #5a6268;
            transform: translateY(-3px);
        }
        .nav-links a.primary {
            background: #007bff;
        }
        .nav-links a.primary:hover {
            background: #0069d9;
        }
        .cart-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .cart-empty {
            padding: 50px 20px;
            text-align: center;
        }
        .cart-empty h2 {
            color: #6c757d;
            margin-bottom: 20px;
        }
        .cart-empty p {
            color: #7f8c8d;
            margin-bottom: 30px;
        }
        .cart-empty .empty-icon {
            font-size: 4rem;
            color: #e0e0e0;
            margin-bottom: 20px;
        }
        .cart-empty .shop-now-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: background 0.3s, transform 0.2s;
        }
        .cart-empty .shop-now-btn:hover {
            background: #218838;
            transform: translateY(-3px);
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }
        .cart-table th {
            text-align: left;
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            color: #2c3e50;
            font-weight: 600;
        }
        .cart-table td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        .cart-table tr:last-child td {
            border-bottom: none;
        }
        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .product-info img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }
        .product-name {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .product-price {
            color: #7f8c8d;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .quantity-control input {
            width: 50px;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin: 0 8px;
        }
        .quantity-control button {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 30px;
            height: 30px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .quantity-control button:hover {
            background: #e9ecef;
        }
        .item-total {
            font-weight: 500;
            color: #2c3e50;
        }
        .remove-btn {
            color: #dc3545;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            padding: 5px;
            transition: transform 0.2s;
        }
        .remove-btn:hover {
            transform: scale(1.2);
        }
        .cart-summary {
            background: #f8f9fa;
            padding: 20px;
            border-top: 1px solid #e0e0e0;
        }
        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            font-size: 18px;
        }
        .cart-total-label {
            font-weight: 500;
            color: #2c3e50;
        }
        .cart-total-value {
            font-weight: 600;
            color: #28a745;
        }
        .checkout-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
            text-align: center;
            text-decoration: none;
        }
        .checkout-btn:hover {
            background: #218838;
            transform: translateY(-3px);
        }
        .continue-shopping {
            margin-top: 15px;
            text-align: center;
        }
        .continue-shopping a {
            color: #6c757d;
            text-decoration: none;
        }
        .continue-shopping a:hover {
            text-decoration: underline;
        }
        .quantity-form {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media (max-width: 768px) {
            .cart-table th:nth-child(3), .cart-table td:nth-child(3) {
                display: none;
            }
            .product-info img {
                width: 60px;
                height: 60px;
            }
        }
        @media (max-width: 576px) {
            .cart-table {
                display: block;
            }
            .cart-table thead {
                display: none;
            }
            .cart-table tbody, .cart-table tr, .cart-table td {
                display: block;
                width: 100%;
            }
            .cart-table tr {
                margin-bottom: 15px;
                border-bottom: 1px solid #e0e0e0;
            }
            .cart-table td {
                text-align: right;
                padding: 10px;
                position: relative;
                border-bottom: none;
            }
            .cart-table td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                top: 10px;
                font-weight: 600;
                color: #2c3e50;
            }
            .product-info {
                justify-content: flex-end;
            }
            .quantity-control {
                justify-content: flex-end;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1>Your Shopping Cart</h1>
            <p>Review your items and proceed to checkout</p>
        </div>
        
        <div class="nav-links">
            <a href="products.php" class="primary"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
            <a href="screens/layouts/pages/userdashboard.php">Dashboard</a>
        </div>
        
        <div class="cart-container">
            <?php if (empty($cartItems)): ?>
                <div class="cart-empty">
                    <div class="empty-icon"><i class="fas fa-shopping-cart"></i></div>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added any products to your cart yet.</p>
                    <a href="products.php" class="shop-now-btn">Shop Now</a>
                </div>
            <?php else: ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Product</th>
                            <th style="width: 15%;">Price</th>
                            <th style="width: 20%;">Quantity</th>
                            <th style="width: 15%;">Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td data-label="Product">
                                    <div class="product-info">
                                        <img src="https://via.placeholder.com/80x80/007bff/white?text=<?php echo urlencode($item['name']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                        <div>
                                            <div class="product-name"><?php echo htmlspecialchars($item['name']); ?></div>
                                            <div class="product-price">$<?php echo number_format($item['price'], 2); ?> each</div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Price">$<?php echo number_format($item['price'], 2); ?></td>
                                <td data-label="Quantity">
                                    <div class="quantity-control">
                                        <button type="button" class="qty-btn minus" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo max(1, $item['quantity'] - 1); ?>)">-</button>
                                        <input type="number" min="1" value="<?php echo $item['quantity']; ?>" id="qty-<?php echo $item['id']; ?>" onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)">
                                        <button type="button" class="qty-btn plus" onclick="updateQuantity(<?php echo $item['id']; ?>, <?php echo $item['quantity'] + 1; ?>)">+</button>
                                    </div>
                                </td>
                                <td data-label="Total" class="item-total">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <button type="button" class="remove-btn" onclick="removeItem(<?php echo $item['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="cart-summary">
                    <div class="cart-total">
                        <span class="cart-total-label">Total (<?php echo $cartCount; ?> items):</span>
                        <span class="cart-total-value">$<?php echo number_format($cartTotal, 2); ?></span>
                    </div>
                    <a href="checkout.php" class="checkout-btn">
                        Proceed to Checkout <i class="fas fa-arrow-right"></i>
                    </a>
                    <div class="continue-shopping">
                        <a href="products.php">
                            <i class="fas fa-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function updateQuantity(productId, quantity) {
            quantity = parseInt(quantity);
            if (quantity < 1) quantity = 1;
            
            fetch('/ECommerce/cart_actions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update&product_id=${productId}&quantity=${quantity}&ajax=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to reflect the changes
                    location.reload();
                } else {
                    alert(data.message || 'Error updating quantity');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the cart');
            });
        }

        function removeItem(productId) {
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                fetch('/ECommerce/cart_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=remove&product_id=${productId}&ajax=1`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to reflect the changes
                        location.reload();
                    } else {
                        alert(data.message || 'Error removing item');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while removing the item');
                });
            }
        }
    </script>
</body>
</html>