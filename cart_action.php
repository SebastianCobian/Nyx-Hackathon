<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false]); exit; }

$action = $_POST['action'] ?? '';
$pid    = (int)($_POST['product_id'] ?? 0);
$db     = getDB();
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

function cartStats() {
    $count = 0; $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['qty'];
        $total += $item['price'] * $item['qty'];
    }
    return ['count'=>$count, 'total'=>'$'.number_format($total,2)];
}

switch ($action) {
    case 'add':
        $qty  = max(1, (int)($_POST['qty'] ?? 1));
        $stmt = $db->prepare("SELECT id,name,price,stock FROM products WHERE id=?");
        $stmt->execute([$pid]);
        $p = $stmt->fetch();
        if (!$p || $p['stock'] <= 0) {
            echo json_encode(['success'=>false,'msg'=>'Sin stock disponible']);
            exit;
        }
        $enCarrito = isset($_SESSION['cart'][$pid]) ? $_SESSION['cart'][$pid]['qty'] : 0;
        $disponible = $p['stock'] - $enCarrito;
        if ($disponible <= 0) {
            echo json_encode(['success'=>false,'msg'=>'No hay mas stock disponible']);
            exit;
        }
        $agregar = min($qty, $disponible);
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid]['qty'] += $agregar;
        } else {
            $_SESSION['cart'][$pid] = ['name'=>$p['name'],'price'=>(float)$p['price'],'qty'=>$agregar];
        }
        echo json_encode(['success'=>true] + cartStats());
        break;

    case 'update':
        $delta = (int)($_POST['delta'] ?? 0);
        if (!isset($_SESSION['cart'][$pid])) { echo json_encode(['success'=>false]); exit; }
        // Si está sumando, verificar stock real
        if ($delta > 0) {
            $stmt = $db->prepare("SELECT stock FROM products WHERE id=?");
            $stmt->execute([$pid]);
            $p = $stmt->fetch();
            if ($p && $_SESSION['cart'][$pid]['qty'] >= $p['stock']) {
                echo json_encode(['success'=>true,'removed'=>false,'qty'=>$_SESSION['cart'][$pid]['qty']] + cartStats());
                exit;
            }
        }
        $_SESSION['cart'][$pid]['qty'] += $delta;
        if ($_SESSION['cart'][$pid]['qty'] <= 0) {
            unset($_SESSION['cart'][$pid]);
            echo json_encode(['success'=>true,'removed'=>true] + cartStats());
        } else {
            echo json_encode(['success'=>true,'removed'=>false,'qty'=>$_SESSION['cart'][$pid]['qty']] + cartStats());
        }
        break;

    case 'remove':
        unset($_SESSION['cart'][$pid]);
        echo json_encode(['success'=>true] + cartStats());
        break;

    default:
        echo json_encode(['success'=>false]);
}