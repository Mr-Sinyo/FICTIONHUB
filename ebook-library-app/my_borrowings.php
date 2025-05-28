<?php
require 'includes/db.php';
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['user_id'];

// Ambil data peminjaman user
$sql = "SELECT br.*, b.title, b.author, b.cover_image, b.file_path
        FROM borrowing br
        JOIN book b ON br.book_id = b.book_id
        WHERE br.user_id = ?
        ORDER BY br.borrow_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$borrowings = $stmt->fetchAll();
?>

<?php include 'includes/header.php'; ?>

<h3 class="mb-4"><i class="bi bi-clock-history me-2"></i>Peminjaman Saya</h3>

<?php if (empty($borrowings)): ?>
    <p>Kamu belum meminjam buku apa pun.</p>
<?php else: ?>
    <?php foreach ($borrowings as $borrow): ?>
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                    <?php if ($borrow['cover_image'] && file_exists('uploads/' . $borrow['cover_image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($borrow['cover_image']) ?>" class="img-fluid p-2" alt="Cover Buku">
                    <?php else: ?>
                        <i class="bi bi-book display-1 text-secondary"></i>
                    <?php endif; ?>
                </div>
                <div class="col-md-10">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($borrow['title']) ?></h5>
                        <p class="card-text"><em>by <?= htmlspecialchars($borrow['author']) ?></em></p>
                        <p class="card-text"><strong>Tanggal Pinjam:</strong> <?= date('d M Y H:i', strtotime($borrow['borrow_date'])) ?></p>
                        <p class="card-text">
                            <strong>Status:</strong>
                            <?php if ($borrow['returned']): ?>
                                <span class="badge bg-secondary">Dikembalikan</span>
                            <?php else: ?>
                                <span class="badge bg-success">Sedang Dipinjam</span>
                                <!-- Form untuk mengembalikan buku -->
                                <form method="POST" action="return_book.php">
                                    <input type="hidden" name="borrowing_id" value="<?= $borrow['borrowing_id'] ?>">
                                    <button type="submit" class="btn btn-primary">Kembalikan Buku</button>
                                </form>

                                <!-- Tombol untuk membaca buku jika ada file_path -->
                                <?php if (!empty($borrow['file_path'])): ?>
                                    <?php if ($_SESSION['user']): ?>
                                        <a href="ebook.php?book_id=<?= $borrow['book_id'] ?>" class="btn btn-success">Baca Buku</a>
                                    <?php else: ?>
                                        <span class="btn btn-sm btn-secondary disabled">Login untuk Baca</span>
                                    <?php endif; ?>
                                <?php endif; ?>

                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
