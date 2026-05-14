<?php
require_once 'includes/config.php';
$pageTitle = 'NYX - La noche que ilumina tus compras';
$db = getDB();

$search   = trim($_GET['q'] ?? '');
$category = (int)($_GET['cat'] ?? 0);
$cats     = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$sql = "SELECT p.*, c.name AS cat_name,
               COALESCE(AVG(r.rating),0) AS avg_rating,
               COUNT(r.id) AS review_count
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN reviews r ON r.product_id = p.id
        WHERE p.stock > 0";
$params = [];

if ($search) {
    $sql .= " AND (p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}
if ($category) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category;
}
$sql .= " GROUP BY p.id ORDER BY p.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

require_once 'includes/header.php';
?>
<div class="container">
<div class="page-content">

<div class="hero">
  <img src="assets/images/logo.png" alt="NYX" class="hero-logo">
  <h1>NYX</h1>
  <div class="hero-slogan">La noche que ilumina tus compras</div>
</div>

<form method="GET" class="search-bar" id="searchForm">
  <div style="flex:1;display:flex;gap:0.5rem;min-width:200px;position:relative">
    <input type="text" name="q" class="form-control" id="searchInput"
           placeholder="Buscar productos..."
           value="<?= e($search) ?>"
           autocomplete="off"
           oninput="autoComplete(this.value)"
           onkeydown="handleKey(event)">
    <button type="button" id="voiceBtn" title="Buscar por voz"
            style="background:rgba(200,216,240,0.05);border:1px solid rgba(200,216,240,0.14);border-radius:8px;padding:0 0.9rem;cursor:pointer;color:var(--muted);font-size:1.1rem;transition:all 0.2s;white-space:nowrap;display:none">
      🎙️
    </button>
    <!-- DROPDOWN AUTOCOMPLETADO -->
    <div id="autocompleteList" style="display:none;position:absolute;top:100%;left:0;right:0;background:rgba(6,6,18,0.97);border:1px solid rgba(200,216,240,0.15);border-radius:0 0 10px 10px;z-index:500;backdrop-filter:blur(12px);max-height:280px;overflow-y:auto;margin-top:2px"></div>
  </div>
  <select name="cat" class="form-control" style="max-width:180px" onchange="this.form.submit()">
    <option value="0">Todas las categorias</option>
    <?php foreach ($cats as $cat): ?>
      <option value="<?= $cat['id'] ?>" <?= $category==$cat['id']?'selected':'' ?>>
        <?= e($cat['name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <button type="submit" class="btn btn-primary">Buscar</button>
  <?php if ($search || $category): ?>
    <a href="index.php" class="btn btn-secondary">Limpiar</a>
  <?php endif; ?>
</form>

<script>
var acIndex = -1;

function autoComplete(val) {
  var list = document.getElementById('autocompleteList');
  if (val.length < 2) { list.style.display='none'; return; }

  fetch('search_ac.php?q=' + encodeURIComponent(val))
  .then(r => r.json())
  .then(data => {
    list.innerHTML = '';
    acIndex = -1;
    if (!data.length) { list.style.display='none'; return; }
    data.forEach(function(item, i) {
      var div = document.createElement('div');
      div.innerHTML =
  '<div style="padding:0.65rem 1rem;cursor:pointer;border-bottom:1px solid rgba(200,216,240,0.06);transition:background 0.15s;font-size:0.88rem;color:var(--moon)" '+
  'onmouseover="this.style.background=\'rgba(200,216,240,0.07)\'" '+
  'onmouseout="this.style.background=\'\'" '+
  'onclick="selectResult(\'' + item.name.replace(/'/g,"\\'") + '\')">' +
  item.name +
  '</div>';
      list.appendChild(div);
    });
    list.style.display = 'block';
  });
}

function selectResult(name) {
  document.getElementById('searchInput').value = name;
  document.getElementById('autocompleteList').style.display = 'none';
  document.getElementById('searchForm').submit();
}

function handleKey(e) {
  var items = document.querySelectorAll('#autocompleteList > div');
  if (e.key === 'ArrowDown') {
    acIndex = Math.min(acIndex+1, items.length-1);
  } else if (e.key === 'ArrowUp') {
    acIndex = Math.max(acIndex-1, 0);
  } else if (e.key === 'Escape') {
    document.getElementById('autocompleteList').style.display='none';
    return;
  } else { return; }
  items.forEach(function(el,i) {
    el.firstChild.style.background = i===acIndex ? 'rgba(200,216,240,0.1)' : '';
  });
  if (acIndex>=0 && items[acIndex]) {
    var name = items[acIndex].querySelector('div > div').textContent;
    document.getElementById('searchInput').value = name;
  }
}

document.addEventListener('click', function(e) {
  if (!e.target.closest('#autocompleteList') && !e.target.closest('#searchInput')) {
    document.getElementById('autocompleteList').style.display='none';
  }
});
</script>

<style>
#voiceBtn.listening {
  background: rgba(239,68,68,0.15) !important;
  border-color: var(--danger) !important;
  color: var(--danger) !important;
  animation: pulse-mic 1s ease-in-out infinite;
}
@keyframes pulse-mic {
  0%,100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.3); }
  50%      { box-shadow: 0 0 0 8px rgba(239,68,68,0); }
}
#voiceStatus {
  font-size:0.8rem;
  color:var(--muted);
  margin-bottom:1rem;
  min-height:1.2rem;
  text-align:center;
  transition: color 0.2s;
}
</style>

<div id="voiceStatus"></div>

<script>
// Mostrar botón solo si el navegador soporta reconocimiento de voz
if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
  document.getElementById('voiceBtn').style.display = 'flex';
  document.getElementById('voiceBtn').style.alignItems = 'center';

  var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
  var recognition = new SpeechRecognition();
  recognition.lang = 'es-MX';
  recognition.continuous = false;
  recognition.interimResults = false;

  var voiceBtn    = document.getElementById('voiceBtn');
  var searchInput = document.getElementById('searchInput');
  var voiceStatus = document.getElementById('voiceStatus');
  var isListening = false;

  voiceBtn.addEventListener('click', function() {
    if (isListening) {
      recognition.stop();
    } else {
      recognition.start();
    }
  });

  recognition.onstart = function() {
    isListening = true;
    voiceBtn.classList.add('listening');
    voiceBtn.textContent = '🔴';
    voiceStatus.textContent = '🎙️ Escuchando... habla ahora';
    voiceStatus.style.color = 'var(--danger)';
  };

  recognition.onresult = function(event) {
    var transcript = event.results[0][0].transcript;
    searchInput.value = transcript;
    voiceStatus.textContent = '✦ Buscando: "' + transcript + '"';
    voiceStatus.style.color = 'var(--accent)';
    setTimeout(function() {
      document.getElementById('searchForm').submit();
    }, 600);
  };

  recognition.onerror = function(event) {
    voiceStatus.textContent = 'No se pudo escuchar. Intenta de nuevo.';
    voiceStatus.style.color = 'var(--danger)';
  };

  recognition.onend = function() {
    isListening = false;
    voiceBtn.classList.remove('listening');
    voiceBtn.textContent = '🎙️';
    if (!searchInput.value) {
      voiceStatus.textContent = '';
    }
  };
}
</script>



<?php if ($search || $category): ?>
  <p style="color:var(--muted);margin-bottom:1rem;font-size:0.9rem">
    <?= count($products) ?> resultado(s)
  </p>
<?php endif; ?>

<!-- HISTORIAL DE BUSQUEDAS -->
<div id="searchHistory" style="margin-bottom:1.5rem;display:none">
  <div style="display:flex;align-items:center;gap:0.8rem;margin-bottom:0.6rem">
    <span style="font-size:0.75rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.1em">🕐 Buscado recientemente</span>
    <button onclick="clearHistory()" style="background:none;border:none;color:var(--muted);font-size:0.72rem;cursor:pointer;text-decoration:underline">Limpiar</button>
  </div>
  <div id="historyTags" style="display:flex;gap:0.5rem;flex-wrap:wrap"></div>
</div>

<script>
// Guardar busqueda actual en historial
<?php if ($search): ?>
(function() {
  var term = '<?= e($search) ?>';
  var history = JSON.parse(sessionStorage.getItem('nyx_search_history') || '[]');
  history = history.filter(function(h) { return h !== term; });
  history.unshift(term);
  if (history.length > 6) history = history.slice(0, 6);
  sessionStorage.setItem('nyx_search_history', JSON.stringify(history));
})();
<?php endif; ?>

// Mostrar historial
(function() {
  var history = JSON.parse(sessionStorage.getItem('nyx_search_history') || '[]');
  if (history.length === 0) return;
  var container = document.getElementById('searchHistory');
  var tags      = document.getElementById('historyTags');
  container.style.display = 'block';
  history.forEach(function(term) {
    var tag = document.createElement('a');
    tag.href = 'index.php?q=' + encodeURIComponent(term);
    tag.textContent = term;
    tag.style.cssText = 'background:rgba(200,216,240,0.06);border:1px solid rgba(200,216,240,0.12);border-radius:20px;padding:0.3rem 0.8rem;font-size:0.82rem;color:var(--muted);transition:all 0.2s;text-decoration:none';
    tag.onmouseover = function() { this.style.borderColor='var(--accent)'; this.style.color='var(--accent)'; };
    tag.onmouseout  = function() { this.style.borderColor='rgba(200,216,240,0.12)'; this.style.color='var(--muted)'; };
    tags.appendChild(tag);
  });
})();

function clearHistory() {
  sessionStorage.removeItem('nyx_search_history');
  document.getElementById('searchHistory').style.display = 'none';
}
</script>

<?php if (empty($products)): ?>
  <div class="empty-state">
    <div class="icon">🌙</div>
    <h3>Sin resultados</h3>
    <p>Intenta con otro termino de busqueda.</p>
  </div>
<?php else: ?>
  <div class="grid-4">
    <?php foreach ($products as $p): ?>
      <div class="product-card">
        <div class="product-img">
          <?php if ($p['image'] && file_exists('assets/images/'.$p['image'])): ?>
            <img src="assets/images/<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>">
          <?php else: ?>
            🌙
          <?php endif; ?>
        </div>
        <div class="product-body">
          <?php if ($p['cat_name']): ?>
            <div class="product-cat"><?= e($p['cat_name']) ?></div>
          <?php endif; ?>
          <div class="product-name"><?= e($p['name']) ?></div>
          <div class="product-desc"><?= e(mb_strimwidth($p['description']??'',0,75,'…')) ?></div>
          <div class="stars">
            <?php for ($i=1;$i<=5;$i++): ?>
              <span class="star <?= $i<=round($p['avg_rating'])?'filled':'' ?>">&#9733;</span>
            <?php endfor; ?>
            <span style="font-size:0.75rem;color:var(--muted);margin-left:0.3rem">(<?= $p['review_count'] ?>)</span>
          </div>
          <div class="product-price"><?= precio($p['price']) ?></div>
          <?php if ($p['stock'] <= 3): ?>
            <div class="stock-alert">Ultimas <?= $p['stock'] ?> unidades!</div>
          <?php else: ?>
            <div class="product-stock"><?= $p['stock'] ?> disponibles</div>
          <?php endif; ?>
        </div>
        <div class="product-actions">
          <a href="product.php?id=<?= $p['id'] ?>" class="btn btn-secondary">Ver</a>
          <button class="btn btn-primary" data-product="<?= $p['id'] ?>"
                  onclick="addToCart(<?= $p['id'] ?>)">Agregar</button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

</div>
</div>
<?php require_once 'includes/footer.php'; ?>