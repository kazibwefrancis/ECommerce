<?php
// filepath: c:\xampp\htdocs\ECommerce\includes\cart.php
// Use __DIR__ to create a reliable path to the database connection file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../logic/database_conn.php';

class CartManager {
    
    public static function addToCart($productId, $quantity = 1, $price = 0, $name = '', $image = '') {
        global $conn;
        
        // Check stock availability
        $sql = "SELECT quantity FROM items WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        
        if (!$product) {
            throw new Exception("Product not found");
        }
        
        if ($product['quantity'] < $quantity) {
            throw new Exception("Not enough stock available. Only {$product['quantity']} items left.");
        }
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // If item already exists, update quantity
        if (isset($_SESSION['cart'][$productId])) {
            $newQuantity = $_SESSION['cart'][$productId]['quantity'] + $quantity;
            
            // Check if total quantity exceeds stock
            if ($newQuantity > $product['quantity']) {
                throw new Exception("Cannot add more items. Only {$product['quantity']} items available.");
            }
            
            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'id' => $productId,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $image
            ];
        }
        
        self::updateCartTotal();
    }
    
    public static function removeFromCart($productId) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            self::updateCartTotal();
        }
    }
    
    public static function updateQuantity($productId, $quantity) {
        global $conn;
        
        if (isset($_SESSION['cart'][$productId])) {
            if ($quantity <= 0) {
                self::removeFromCart($productId);
            } else {
                // Check stock availability
                $sql = "SELECT quantity FROM items WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $productId);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
                
                if ($product && $quantity > $product['quantity']) {
                    throw new Exception("Not enough stock available. Only {$product['quantity']} items left.");
                }
                
                $_SESSION['cart'][$productId]['quantity'] = $quantity;
                self::updateCartTotal();
            }
        }
    }
    
    public static function getCartItems() {
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }
    
    public static function getCartCount() {
        $count = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += $item['quantity'];
            }
        }
        return $count;
    }
    
    public static function getCartTotal() {
        return isset($_SESSION['cart_total']) ? $_SESSION['cart_total'] : 0;
    }
    
    private static function updateCartTotal() {
        $total = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        }
        $_SESSION['cart_total'] = $total;
    }
    
    public static function clearCart() {
        $_SESSION['cart'] = [];
        $_SESSION['cart_total'] = 0;
    }
    
    // Function to process order and update stock
    public static function processOrder() {
        global $conn;
        
        if (empty($_SESSION['cart'])) {
            throw new Exception("Cart is empty");
        }
        
        // Update stock quantities
        foreach ($_SESSION['cart'] as $item) {
            $sql = "UPDATE items SET quantity = quantity - ? WHERE id = ? AND quantity >= ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $item['quantity'], $item['id'], $item['quantity']);
            
            if (!$stmt->execute() || $stmt->affected_rows === 0) {
                throw new Exception("Failed to update stock for " . $item['name']);
            }
        }
        
        // Clear cart after successful order
        self::clearCart();
        return true;
    }
}
?>