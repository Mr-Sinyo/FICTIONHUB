<?php
require '../includes/db.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user']['user_id'];
$book_id = $_GET['book_id'] ?? null;

if (!$book_id) {
    die("ID buku tidak ditemukan.");
}

try {
    // Cek apakah user sudah meminjam buku ini dan belum dikembalikan
    $stmt = $pdo->prepare("SELECT * FROM borrowing WHERE user_id = ? AND book_id = ? AND returned = 0");
    $stmt->execute([$user_id, $book_id]);

    if ($stmt->fetch()) {
        die("Kamu sudah meminjam buku ini.");
    }

    // Tambahkan peminjaman baru
    $insertStmt = $pdo->prepare("INSERT INTO borrowing (user_id, book_id, borrow_date, returned) VALUES (?, ?, NOW(), 0)");
    $insertStmt->execute([$user_id, $book_id]);

    // Tandai buku sebagai tidak tersedia
    $updateStmt = $pdo->prepare("UPDATE book SET available = 0 WHERE book_id = ?");
    $updateStmt->execute([$book_id]);

    // Redirect ke halaman peminjaman saya
    header("Location: ../my_borrowings.php");
    exit;

} catch (PDOException $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}
