<?php
require_once 'includes/config.php';
header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');
if (strlen($q) < 2) { echo '[]'; exit; }

$db = getDB();
$stmt = $db->prepare("
    SELECT p.name, p.price, c.name AS category
    FROM products p
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.stock > 0 AND p.name LIKE ?
    ORDER BY p.name ASC
    LIMIT 6
");
$stmt->execute(["%$q%"]);
$results = $stmt->fetchAll();

$out = [];
foreach ($results as $r) {
    $out[] = [
        'name'     => $r['name'],
        'price'    => number_format($r['price'], 2),
        'category' => $r['category'] ?? ''
    ];
}

echo json_encode($out);
