<?php
require_once 'includes/config.php';
if (empty($_SESSION['cart'])) {
    echo '<div class="empty-state"><div class="icon">🌙</div><h3>Carrito vacio</h3><p>Agrega productos para comenzar</p></div>';
} else {
    foreach ($_SESSION['cart'] as $pid => $item) {
        echo '<div class="cart-item" id="ci-'.$pid.'">
          <div class="cart-item-img">🌙</div>
          <div class="cart-item-info">
            <div class="cart-item-name">'.htmlspecialchars($item['name'],ENT_QUOTES).'</div>
            <div class="cart-item-price">$'.number_format($item['price'],2).' c/u</div>
            <div class="cart-item-qty">
              <button class="qty-btn" onclick="updateCart('.$pid.',-1)">-</button>
              <span class="qty-val" id="qty-'.$pid.'">'.$item['qty'].'</span>
              <button class="qty-btn" onclick="updateCart('.$pid.',1)">+</button>
            </div>
          </div>
          <button class="cart-item-remove" onclick="removeCart('.$pid.')">x</button>
        </div>';
    }
}
