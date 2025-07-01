<?php
// filepath: c:\xampp\htdocs\ECommerce\cart_actions.php
// Check if a session is already active before starting one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/includes/cart.php';

// Ensure we always return JSON
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        switch ($action) {
            case 'add':
                $productId = intval($_POST['product_id'] ?? 0);
                $quantity = intval($_POST['quantity'] ?? 1);
                $price = floatval($_POST['price'] ?? 0);
                $name = trim($_POST['name'] ?? '');
                $image = $_POST['image'] ?? ''; // Add image support

                if ($productId > 0 && $name !== '' && $price >= 0) {
                    CartManager::addToCart($productId, $quantity, $price, $name, $image);
                    $response = [
                        'success' => true,
                        'message' => 'Item added to cart!',
                        'cart_count' => CartManager::getCartCount(),
                        'cart_total' => CartManager::getCartTotal()
                    ];
                } else {
                    $response['message'] = 'Invalid product data provided.';
                }
                break;

            case 'update':
                $productId = intval($_POST['product_id'] ?? 0);
                $quantity = intval($_POST['quantity'] ?? 1);
                
                if ($productId > 0 && $quantity > 0) {
                    CartManager::updateQuantity($productId, $quantity);
                    $response = [
                        'success' => true,
                        'message' => 'Cart updated!',
                        'cart_count' => CartManager::getCartCount(),
                        'cart_total' => CartManager::getCartTotal()
                    ];
                } else {
                    $response['message'] = 'Invalid product data provided.';
                }
                break;
                
            case 'remove':
                $productId = intval($_POST['product_id'] ?? 0);
                
                if ($productId > 0) {
                    CartManager::removeFromCart($productId);
                    $response = [
                        'success' => true,
                        'message' => 'Item removed from cart!',
                        'cart_count' => CartManager::getCartCount(),
                        'cart_total' => CartManager::getCartTotal()
                    ];
                } else {
                    $response['message'] = 'Invalid product ID provided.';
                }
                break;

            case 'get_cart_info':
                 $response = [
                    'success' => true,
                    'cart_count' => CartManager::getCartCount(),
                    'cart_total' => CartManager::getCartTotal()
                ];
                break;

            default:
                $response['message'] = 'Unknown action.';
                break;
        }
    } catch (Exception $e) {
        // Return a specific error message from the cart logic (e.g., "Not enough stock")
        $response['message'] = $e->getMessage();
    }
}

// Echo the JSON response and exit
echo json_encode($response);
exit;