<?php
require '../includes/db.php';
require '../includes/header.php';
session_start();

$isLoggedIn = isset($_SESSION['user']);
$user_id = $_SESSION['user']['user_id'] ?? null;

// ✅ Handle review submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && $isLoggedIn) {
    $book_id = $_POST['book_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $pdo->prepare("INSERT INTO Review (book_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, CURDATE())");
    $stmt->execute([$book_id, $user_id, $rating, $comment]);
}

$books = $pdo->query("SELECT * FROM Book")->fetchAll();
?>

<h1 class="mb-4">📚 Daftar Buku</h1>

<div class="row row-cols-1 row-cols-md-2 g-4">
<?php foreach ($books as $book): ?>
  <?php
    $reviewsStmt = $pdo->prepare("SELECT R.rating, R.comment, U.username FROM Review R JOIN User U ON R.user_id = U.user_id WHERE book_id = ?");
    $reviewsStmt->execute([$book['book_id']]);
    $reviews = $reviewsStmt->fetchAll();
  ?>
  <div class="col">
    <div class="card h-100 shadow-sm">
      <?php if ($book['cover_image']): ?>
        <img src="/ebook-library-app/<?= $book['cover_image'] ?>" class="card-img-top" alt="<?= $book['title'] ?>" style="height: 200px; object-fit: cover;">
      <?php endif; ?>
      <div class="card-body">
        <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
        <p><strong><?= $book['author'] ?></strong><br>Genre: <?= $book['genre'] ?></p>

        <h6 class="mt-3">💬 Ulasan:</h6>
        <?php if ($reviews): ?>
          <?php foreach ($reviews as $review): ?>
            <div class="border-bottom mb-2 pb-1">
              <strong><?= htmlspecialchars($review['username']) ?></strong>
              <span class="badge bg-warning text-dark">⭐ <?= $review['rating'] ?>/5</span>
              <p class="mb-0"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-muted">Belum ada ulasan.</p>
        <?php endif; ?>

        <?php if ($isLoggedIn): ?>
          <form method="POST" class="mt-3 border-top pt-3">
            <input type="hidden" name="book_id" value="<?= $book['book_id'] ?>">
            <div class="mb-2">
              <label class="form-label">Rating:</label>
              <select name="rating" class="form-select form-select-sm" required>
                <option value="">Pilih rating</option>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                  <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label">Komentar:</label>
              <textarea name="comment" class="form-control form-control-sm" rows="2" required></textarea>
            </div>
            <button type="submit" class="btn btn-sm btn-outline-success">Kirim Ulasan</button>
          </form>
        <?php else: ?>
          <div class="alert alert-warning mt-3">Login untuk menulis ulasan.</div>
        <?php endif; ?>
      </div>
      <div class="card-footer d-flex justify-content-between align-items-center">
        <a href="../books/view.php?id=<?= $book['book_id'] ?>" class="btn btn-sm btn-outline-primary">Lihat</a>
        <?php if ($book['available']): ?>
          <a href="../borrow/borrow.php?book_id=<?= $book['book_id'] ?>" class="btn btn-sm btn-success">Pinjam</a>
        <?php else: ?>
          <span class="badge bg-secondary">Dipinjam</span>
        <?php endif; ?>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>

<?php require '../includes/footer.php'; ?>
