<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/header.php';

$book_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT book_id, title, author, publisher, year, genre, file_path, cover_image, available, synopsis FROM book WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();
?>

<div class="container my-5">
  <div class="row">
    <!-- Cover -->
    <div class="col-md-4 mb-4">
      <?php if (!empty($book['cover_image'])): ?>
        <img src="/ebook-library-app/<?= $book['cover_image'] ?>" alt="Cover" class="img-fluid rounded shadow-sm">
      <?php else: ?>
        <div class="bg-secondary text-white text-center p-5 rounded">Tidak Ada Cover</div>
      <?php endif; ?>
    </div>

    <!-- Info Buku -->
    <div class="col-md-8">
      <h2 class="text-primary"><?= htmlspecialchars($book['title']) ?></h2>
      <p><strong>Penulis:</strong> <?= htmlspecialchars($book['author']) ?></p>
      <p><strong>Penerbit:</strong> <?= htmlspecialchars($book['publisher']) ?> (<?= $book['year'] ?>)</p>
      <p><strong>Genre:</strong> <?= htmlspecialchars($book['genre']) ?></p>
      <p><strong>Status:</strong>
        <?= $book['available'] ? '<span class="badge bg-success">Tersedia</span>' : '<span class="badge bg-secondary">Dipinjam</span>' ?>
      </p>

      <!-- Tombol Aksi -->
      <div class="mt-3 d-flex gap-2">
        <?php if ($book['available']): ?>
          <a href="../borrow/borrow.php?book_id=<?= $book_id ?>" class="btn btn-success">📥 Pinjam</a>
        <?php endif; ?>

        <?php if (!empty($book['file_path'])): ?>
          <a href="../ebook.php?book_id=<?= $book_id ?>" class="btn btn-secondary" target="_blank">📖 Baca</a>
        <?php endif; ?>

        <a href="../index.php" class="btn btn-outline-primary">← Kembali</a>
      </div>
    </div>
  </div>

  <!-- Sinopsis -->
  <div class="card mt-4">
    <div class="card-header">📘 Sinopsis</div>
    <div class="card-body">
      <p class="card-text">
        <?= !empty($book['synopsis']) 
            ? nl2br(htmlspecialchars($book['synopsis'])) 
            : '<em>(Tidak ada sinopsis untuk buku ini)</em>' ?>
      </p>
    </div>
  </div>
</div>

<?php require '../includes/footer.php'; ?>
