<?php
require 'includes/db.php';
require 'includes/auth.php';

$isLoggedIn = isset($_SESSION['user']);
$book_id = $_GET['book_id'] ?? null;
$user_id = $_SESSION['user']['user_id'] ?? null;
$book = null;
$error = null;

// Cek login
if (!$isLoggedIn) {
    $error = "⛔ Anda harus login untuk membaca buku.";
} elseif (!$book_id) {
    $error = "❌ Buku tidak ditemukan.";
} else {
    // Cek apakah user telah meminjam buku ini
    $stmt = $pdo->prepare("SELECT * FROM borrowing WHERE user_id = ? AND book_id = ? AND status = 'borrowed'");
    $stmt->execute([$user_id, $book_id]);
    $hasAccess = $stmt->fetch();

    if (!$hasAccess) {
        $error = "🚫 Anda belum meminjam buku ini.";
    } else {
        // Ambil info eBook
        $stmt = $pdo->prepare("SELECT title, file_path FROM book WHERE book_id = ?");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch();

        if (!$book || empty($book['file_path'])) {
            $error = "📄 File eBook tidak tersedia.";
        } else {
            // Redirect ke file eBook jika tidak error
            header("Location: " . $book['file_path']);
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Baca Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm p-4">
        <h2 class="mb-3"><i class="bi bi-book"></i> Akses eBook</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary mt-3">
            ← Kembali ke Daftar Buku
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
</body>
</html>
