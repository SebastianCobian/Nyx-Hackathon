<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'nyx');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SITE_NAME', 'NYX');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
                DB_USER, DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            die("<div style='font-family:sans-serif;padding:2rem;color:red'>
                <h2>Error de conexion a la base de datos</h2>
                <p>".$e->getMessage()."</p>
                <p>Verifica <b>includes/config.php</b></p>
            </div>");
        }
    }
    return $pdo;
}

function isLoggedIn() { return isset($_SESSION['user_id']); }
function isAdmin() { return isset($_SESSION['role']) && $_SESSION['role'] === 'admin'; }
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php?redirect='.urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}
function requireAdmin() {
    if (!isAdmin()) { header('Location: ../index.php'); exit; }
}
function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }
function precio($n) { return '$'.number_format($n, 2); }
function setFlash($type, $msg) { $_SESSION['flash'] = ['type'=>$type,'msg'=>$msg]; }
function getFlash() {
    if (isset($_SESSION['flash'])) { $f=$_SESSION['flash']; unset($_SESSION['flash']); return $f; }
    return null;
}

session_start();
