<?php

require_once "../config/database.php";
require_once "../includes/csrf.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$token = $_POST["csrf_token"] ?? "";

if (!verify_csrf_token($token)) {
    die("CSRF token tidak valid.");
}

$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare(
    "DELETE FROM products WHERE id = ?"
);

$stmt->execute([$id]);

header("Location: index.php");
exit;