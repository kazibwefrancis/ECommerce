<?php
// filepath: c:\xampp\htdocs\ECommerce\products.php
require_once 'includes/cart.php';
require_once 'logic/database_conn.php';

// Fetch products from database
$sql = "SELECT * FROM items WHERE quantity > 0 ORDER BY id DESC";
$result = $conn->query($sql);
$products = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'quantity' => $row['quantity'],
            'image' => 'https://via.placeholder.com/400x400/007bff/white?text=' . urlencode($row['name']),
            'description' => $row['description']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - ECommerce</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background-color: #f8f9fa;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .products-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); 
            gap: 30px; 
        }
        
        .product-card { 
            background: white; 
            border-radius: 12px; 
            padding: 25px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        
        .product-image { 
            width: 100%; 
            height: 250px;
            object-fit: cover;
            border-radius: 8px; 
            margin-bottom: 20px;
        }
        
        .product-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .product-price {
            font-size: 1.5rem;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .product-stock {
            font-size: 0.9rem;
            margin-bottom: 15px;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
        }
        
        .stock-high { 
            background: #d4edda; 
            color: #155724; 
        }
        
        .stock-medium { 
            background: #fff3cd; 
            color: #856404; 
        }
        
        .stock-low { 
            background: #f8d7da; 
            color: #721c24; 
        }
        
        .product-description {
            color: #6c757d;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .add-to-cart-form { 
            margin-top: 20px; 
        }
        
        .quantity-selector { 
            margin: 15px 0; 
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .quantity-selector label {
            font-weight: 500;
            color: #495057;
        }
        
        .quantity-selector input { 
            padding: 8px 12px; 
            width: 80px; 
            border: 2px solid #dee2e6;
            border-radius: 6px;
            text-align: center;
        }
        
        .add-to-cart-btn { 
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white; 
            border: none; 
            padding: 12px 24px; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 1rem;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .add-to-cart-btn:hover { 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }
        
        .add-to-cart-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }
        
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px 15px;
            border-radius: 6px;
            margin-top: 10px;
            display: none;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 6px;
            margin-top: 10px;
            display: none;
        }
        
        .nav-links {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .nav-links a {
            background: #6c757d;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            margin: 0 10px;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .nav-links a:hover {
            background: #5a6268;
        }
        
        .admin-link {
            background: #28a745 !important;
        }
        
        .admin-link:hover {
            background: #218838 !important;
        }
        
        .no-products {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .no-products h3 {
            color: #6c757d;
            margin-bottom: 15px;
        }
        
        .no-products p {
            color: #6c757d;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'includes/cart_widget.php'; ?>
    
    <div class="container">
        <div class="header">
            <h1>🛍️ Our Products</h1>
            <p>Choose from our selection of high-quality products</p>
        </div>
        
        <div class="nav-links">
            <a href="cart.php">View Cart</a>
            <a href="screens/layouts/pages/admindashboard.php" class="admin-link">Admin Dashboard</a>
            <a href="register.html">Home</a>
        </div>
        
        <?php if (empty($products)): ?>
            <div class="no-products">
                <h3>No products available</h3>
                <p>The admin hasn't added any products yet.</p>
                <a href="screens/layouts/pages/admindashboard.php" class="admin-link" style="display: inline-block; padding: 12px 24px; text-decoration: none; border-radius: 8px;">
                    Go to Admin Dashboard
                </a>
            </div>
        <?php else: ?>
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                        
                        <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                        <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                        
                        <div class="product-stock">
                            <?php
                            $stock = $product['quantity'];
                            if ($stock > 10) {
                                echo "<span class='stock-high'>✓ In Stock ({$stock} available)</span>";
                            } elseif ($stock > 3) {
                                echo "<span class='stock-medium'>⚠ Limited Stock ({$stock} left)</span>";
                            } else {
                                echo "<span class='stock-low'>⚠ Low Stock ({$stock} left)</span>";
                            }
                            ?>
                        </div>
                        
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        
                        <form method="POST" action="cart_actions.php" class="add-to-cart-form" onsubmit="return addToCartAjax(this)">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($product['name']); ?>">
                            <input type="hidden" name="image" value="<?php echo htmlspecialchars($product['image']); ?>">
                            <input type="hidden" name="max_quantity" value="<?php echo $product['quantity']; ?>">
                            <input type="hidden" name="ajax" value="1">
                            
                            <div class="quantity-selector">
                                <label>Quantity:</label>
                                <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['quantity']; ?>">
                            </div>
                            
                            <button type="submit" class="add-to-cart-btn">🛒 Add to Cart</button>
                            <div class="success-message"></div>
                            <div class="error-message"></div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function addToCartAjax(form) {
            const formData = new FormData(form);
            const button = form.querySelector('.add-to-cart-btn');
            const successMessage = form.querySelector('.success-message');
            const errorMessage = form.querySelector('.error-message');
            
            // Hide previous messages
            successMessage.style.display = 'none';
            errorMessage.style.display = 'none';
            
            button.disabled = true;
            button.textContent = 'Adding...';
            
            fetch('cart_actions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartWidget();
                    successMessage.textContent = data.message;
                    successMessage.style.display = 'block';
                    form.querySelector('input[name="quantity"]').value = 1;
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 3000);
                } else {
                    errorMessage.textContent = data.message;
                    errorMessage.style.display = 'block';
                    setTimeout(() => {
                        errorMessage.style.display = 'none';
                    }, 5000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMessage.textContent = 'Error adding item to cart. Please try again.';
                errorMessage.style.display = 'block';
            })
            .finally(() => {
                button.disabled = false;
                button.textContent = '🛒 Add to Cart';
            });
            
            return false;
        }
    </script>
</body>
</html>