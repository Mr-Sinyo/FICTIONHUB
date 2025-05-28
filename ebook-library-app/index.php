<?php
require 'includes/db.php';
require 'includes/header.php';

$isLoggedIn = isset($_SESSION['user']);
$isAdmin = $isLoggedIn && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';

$q = $_GET['q'] ?? '';
$genre = $_GET['genre'] ?? '';
$status = $_GET['status'] ?? '';

$sql = "SELECT * FROM book WHERE 1";
$params = [];

if (!empty($q)) {
    $sql .= " AND (title LIKE ? OR author LIKE ?)";
    $params[] = "%$q%";
    $params[] = "%$q%";
}

if (!empty($genre)) {
    $sql .= " AND genre = ?";
    $params[] = $genre;
}

if ($status !== '') {
    $sql .= " AND available = ?";
    $params[] = $status;
}

$sql .= " ORDER BY title";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();
?>

<!-- Filter Form -->
<form method="GET" class="row g-3 align-items-end mb-4">
    <div class="col-md-4">
        <label for="q" class="form-label">🔍 Cari Judul / Penulis</label>
        <input type="text" class="form-control" id="q" name="q" placeholder="Contoh: Sherlock" value="<?= htmlspecialchars($q) ?>">
    </div>
    <div class="col-md-3">
        <label for="genre" class="form-label">📚 Genre</label>
        <select class="form-select" id="genre" name="genre">
            <option value="">Semua Genre</option>
            <option value="Romance" <?= $genre === 'Romance' ? 'selected' : '' ?>>Romance</option>
            <option value="Misteri" <?= $genre === 'Misteri' ? 'selected' : '' ?>>Misteri</option>
            <option value="Petualangan" <?= $genre === 'Petualangan' ? 'selected' : '' ?>>Petualangan</option>
            <option value="Business & Economics" <?= $genre === 'Business & Economics' ? 'selected' : '' ?>>Business & Economics</option>
        </select>
    </div>
    <div class="col-md-3">
        <label for="status" class="form-label">📦 Status Buku</label>
        <select class="form-select" id="status" name="status">
            <option value="">Semua</option>
            <option value="1" <?= $status === '1' ? 'selected' : '' ?>>Tersedia</option>
            <option value="0" <?= $status === '0' ? 'selected' : '' ?>>Dipinjam</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<!-- Hasil Pencarian -->
<?php if ($q || $genre): ?>
    <p class="text-muted">
        Menampilkan hasil untuk 
        <?= $q ? "pencarian '<strong>" . htmlspecialchars($q) . "</strong>'" : '' ?>
        <?= $genre ? "dengan genre <strong>" . htmlspecialchars($genre) . "</strong>" : '' ?>
        (<a href="index.php">Reset</a>)
    </p>
<?php endif; ?>

<h2 class="mb-4"><i class="bi bi-book-half"></i> Daftar Buku</h2>

<?php foreach ($books as $book): ?>
    <div class="card mb-4 shadow-sm book-hover">
        <div class="row g-0">
            <div class="col-md-3 d-flex align-items-stretch">
                <?php if (!empty($book['cover_image'])): ?>
                    <img src="/ebook-library-app/<?= $book['cover_image'] ?>" class="img-fluid rounded-start w-100" style="height: 100%; object-fit: cover;" alt="<?= htmlspecialchars($book['title']) ?>">
                <?php else: ?>
                    <div class="bg-secondary text-white text-center p-5">No Cover</div>
                <?php endif; ?>
            </div>
            <div class="col-md-9">
                <div class="card-body">
                    <h5 class="card-title text-primary">
                        <i class="bi bi-journal-bookmark"></i>
                        <?= htmlspecialchars($book['title']) ?>
                    </h5>
                    <p class="card-subtitle mb-1 text-muted">by <em><?= htmlspecialchars($book['author']) ?></em></p>
                    <p class="mb-1"><strong><i class="bi bi-tags"></i> Genre:</strong> <?= htmlspecialchars($book['genre']) ?></p>
                    <p class="mb-2"><?= !empty($book['synopsis']) ? substr(strip_tags($book['synopsis']), 0, 300) . '…' : '<em>(Tidak ada sinopsis)</em>' ?></p>
                    <div class="d-flex gap-2 align-items-center mt-2">
                        <?php if ($book['available']): ?>
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Tersedia</span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><i class="bi bi-x-circle"></i> Dipinjam</span>
                        <?php endif; ?>

                        <a href="books/view.php?id=<?= $book['book_id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Lihat
                        </a>

                        <?php if ($book['available']): ?>
                            <a href="borrow/borrow.php?book_id=<?= $book['book_id'] ?>" class="btn btn-sm btn-success">
                                <i class="bi bi-box-arrow-in-down"></i> Pinjam
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($book['file_path'])): ?>
                            <?php if ($isLoggedIn): ?>
                                <a href="ebook.php?book_id=<?= $book['book_id'] ?>" class="btn btn-sm btn-secondary">Baca</a>
                            <?php else: ?>
                                <span class="btn btn-sm btn-secondary disabled">Login untuk Baca</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php require 'includes/footer.php'; ?>
