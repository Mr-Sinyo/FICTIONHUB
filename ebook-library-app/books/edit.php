<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/header.php';

$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';

$book_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM book WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];
    $file_path = $_POST['file_path'];
    $synopsis = $isAdmin && isset($_POST['synopsis']) ? $_POST['synopsis'] : '';

    $stmt = $pdo->prepare("UPDATE book SET title=?, author=?, publisher=?, year=?, genre=?, file_path=?, synopsis=? WHERE book_id=?");
    $stmt->execute([$title, $author, $publisher, $year, $genre, $file_path, $synopsis, $book_id]);

    header("Location: list.php");
    exit;
}
?>

<div class="container mt-5">
    <h2 class="mb-4">✏️ Edit Buku</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($book['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Penulis</label>
            <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($book['author']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Penerbit</label>
            <input type="text" name="publisher" class="form-control" value="<?= htmlspecialchars($book['publisher']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Tahun Terbit</label>
            <input type="number" name="year" class="form-control" value="<?= htmlspecialchars($book['year']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Genre</label>
            <input type="text" name="genre" class="form-control" value="<?= htmlspecialchars($book['genre']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">File eBook (path)</label>
            <input type="text" name="file_path" class="form-control" value="<?= htmlspecialchars($book['file_path']) ?>">
        </div>

        <?php if ($isAdmin): ?>
        <div class="mb-3">
            <label class="form-label">Sinopsis</label>
            <textarea name="synopsis" class="form-control" rows="5"><?= htmlspecialchars($book['synopsis']) ?></textarea>
        </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">💾 Simpan</button>
        <a href="list.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php require '../includes/footer.php'; ?>
