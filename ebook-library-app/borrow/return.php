<?php
require '../includes/db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user']['user_id'];
$book_id = $_GET['book_id'] ?? null;

if (!$book_id) {
    die("ID buku tidak ditemukan.");
}

$stmt = $pdo->prepare("UPDATE borrowings SET returned = 1, return_date = NOW() WHERE user_id = ? AND book_id = ? AND returned = 0");
$stmt->execute([$user_id, $book_id]);

$pdo->prepare("UPDATE book SET available = 1 WHERE book_id = ?")->execute([$book_id]);

header("Location: ../my_borrowings.php");
