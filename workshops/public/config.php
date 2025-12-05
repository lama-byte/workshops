<?php
session_start();

function getPDO() {      //  connect with DB....
    static $pdo = null;

    if ($pdo === null) {
        $pdo = new PDO(
            "mysql:host=localhost;dbname=shop;charset=utf8",
            "root",
            "",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }
    return $pdo;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php?msg=login_required");
        exit;
    }
}


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];   // array: productId => quantity
}


function cart_add($id, $qty = 1) {
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 0;
    }
    $_SESSION['cart'][$id] += $qty;
}

function cart_update($id, $qty) {
    if ($qty <= 0) {
        unset($_SESSION['cart'][$id]);
    } else {
        $_SESSION['cart'][$id] = $qty;
    }
}

function cart_clear() {
    $_SESSION['cart'] = [];
}

function cart_subtotal() {
    $pdo = getPDO();
    $subtotal = 0;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $p = $stmt->fetch();
        if ($p) {
            $subtotal += $p['price'] * $qty;
        }
    }
    return $subtotal;
}

function cart_total_with_tax($subtotal, $tax = 0.15) {
    return $subtotal + ($subtotal * $tax);
}


function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

?>
