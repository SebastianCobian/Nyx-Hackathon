function toggleCart() {
  document.getElementById('cartOverlay').classList.toggle('open');
  document.getElementById('cartSidebar').classList.toggle('open');
}

function addToCart(productId) {
  fetch('cart_action.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'action=add&product_id=' + productId + '&qty=1'
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      document.getElementById('cartCount').textContent = data.count;
      document.getElementById('cartTotal').textContent = data.total;
      var btn = document.querySelector('[data-product="' + productId + '"]');
      if (btn) {
        var orig = btn.textContent;
        btn.textContent = 'Agregado!';
        btn.style.background = 'var(--success)';
        setTimeout(function() {
          btn.textContent = orig;
          btn.style.background = '';
        }, 1200);
      }
      fetch('cart_fragment.php').then(r => r.text()).then(html => {
        document.getElementById('cartItems').innerHTML = html;
      });
      toggleCart();
    }
  }).catch(console.error);
}

function updateCart(productId, delta) {
  fetch('cart_action.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'action=update&product_id=' + productId + '&delta=' + delta
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      if (data.removed) {
        var el = document.getElementById('ci-' + productId);
        if (el) el.remove();
      } else {
        var q = document.getElementById('qty-' + productId);
        if (q) q.textContent = data.qty;
      }
      document.getElementById('cartCount').textContent = data.count;
      document.getElementById('cartTotal').textContent = data.total;
    }
  });
}

function removeCart(productId) {
  fetch('cart_action.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'action=remove&product_id=' + productId
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      var el = document.getElementById('ci-' + productId);
      if (el) el.remove();
      document.getElementById('cartCount').textContent = data.count;
      document.getElementById('cartTotal').textContent = data.total;
      if (data.count == 0) {
        document.getElementById('cartItems').innerHTML =
          '<div class="empty-state"><div class="icon">🌙</div><h3>Carrito vacio</h3><p>Agrega productos para comenzar</p></div>';
      }
    }
  });
}
