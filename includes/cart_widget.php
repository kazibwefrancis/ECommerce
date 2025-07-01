<?php
// filepath: c:\xampp\htdocs\ECommerce\includes\cart_widget.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/cart.php';

$cartCount = CartManager::getCartCount();
$cartTotal = CartManager::getCartTotal();
?>

<div id="cart-widget" style="position: fixed; top: 20px; right: 20px; background: white; padding: 15px 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1100; min-width: 200px; transition: all 0.3s ease; cursor: pointer;">
    <a href="/ECommerce/cart.php" style="text-decoration: none; color: #333; display: block;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 28px;">🛒</span>
            <div>
                <div style="font-weight: 600; font-size: 16px; color: #2c3e50;">
                    Cart (<span id="cart-count"><?php echo $cartCount; ?></span>)
                </div>
                <div style="color: #28a745; font-size: 14px; font-weight: 500;">
                    $<span id="cart-total"><?php echo number_format($cartTotal, 2); ?></span>
                </div>
            </div>
        </div>
    </a>
</div>

<script>
function updateCartWidget() {
    fetch('/ECommerce/cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_cart_info&ajax=1'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cart-count').textContent = data.cart_count;
            document.getElementById('cart-total').textContent = parseFloat(data.cart_total).toFixed(2);
        }
    })
    .catch(error => console.error('Error updating cart widget:', error));
}

document.addEventListener('DOMContentLoaded', updateCartWidget);
setInterval(updateCartWidget, 10000);
</script>