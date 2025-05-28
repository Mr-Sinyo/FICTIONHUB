<?php
require 'includes/db.php';
session_start();

if (!isset($_GET['book_id'])) {
    die("Book ID tidak ditemukan.");
}

$book_id = $_GET['book_id'];

// Ambil data buku
$stmt = $pdo->prepare("SELECT * FROM book WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if (!$book) {
    die("Buku tidak ditemukan.");
}

?>

<?php include 'includes/header.php'; ?>

<h3><?= htmlspecialchars($book['title']) ?></h3>
<p><em>by <?= htmlspecialchars($book['author']) ?></em></p>


<?php include 'includes/footer.php'; ?>
