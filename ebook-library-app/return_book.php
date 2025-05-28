<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$borrowing_id = $_POST['borrowing_id'] ?? null;

if (!$borrowing_id) {
    die("ID peminjaman tidak ditemukan.");
}

// Ambil data pinjaman berdasarkan borrowing_id dan user_id
$stmt = $pdo->prepare("SELECT * FROM borrowing WHERE borrowing_id = ? AND user_id = ?");
$stmt->execute([$borrowing_id, $_SESSION['user']['user_id']]);
$borrowing = $stmt->fetch();

if (!$borrowing || $borrowing['returned']) {
    die("Data peminjaman tidak valid atau sudah dikembalikan.");
}

// Update status peminjaman menjadi sudah dikembalikan
$stmt = $pdo->prepare("UPDATE borrowing SET returned = 1 WHERE borrowing_id = ?");
$stmt->execute([$borrowing_id]);

// Update status buku menjadi tersedia
$stmt = $pdo->prepare("UPDATE book SET available = 1 WHERE book_id = ?");
$stmt->execute([$borrowing['book_id']]);

header("Location: my_borrowings.php"); // Mengarahkan kembali ke halaman peminjaman saya
exit;
?>
