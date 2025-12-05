<?php
session_start();

function getPDO() {      //  connect with DB....
    static $pdo = null;

    if ($pdo === null) {
        $pdo = new PDO(
            "mysql:host=localhost;dbname=workshopsystem;charset=utf8",
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


function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}
?>
