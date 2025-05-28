<?php
require '../includes/db.php';
require '../includes/auth.php';

if ($_SESSION['user']['role'] !== 'admin') die('❌ Akses ditolak.');

if (!isset($_GET['id']) || empty($_GET['id'])) die("❌ ID tidak ditemukan.");

$book_id = $_GET['id'];

// 1. Hapus dulu semua data peminjaman yang berkaitan dengan buku ini
$stmt1 = $pdo->prepare("DELETE FROM Borrowing WHERE book_id = ?");
$stmt1->execute([$book_id]);

// 2. Baru hapus bukunya
$stmt2 = $pdo->prepare("DELETE FROM Book WHERE book_id = ?");
$stmt2->execute([$book_id]);

header("Location: list.php?deleted=1");
exit;
