<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/header.php';

if ($_SESSION['user']['role'] !== 'admin') {
    die("❌ Unauthorized Access.");
}

// Hitung total data
$total_users = $pdo->query("SELECT COUNT(*) FROM User WHERE role = 'user'")->fetchColumn();
$total_books = $pdo->query("SELECT COUNT(*) FROM Book")->fetchColumn();
$total_borrowings = $pdo->query("SELECT COUNT(*) FROM Borrowing WHERE status = 'borrowed'")->fetchColumn();

// Ambil daftar peminjaman aktif
$stmt = $pdo->query("
  SELECT B.title, U.username, BR.borrow_date
  FROM borrowing AS BR
  JOIN book AS B ON BR.book_id = B.book_id
  JOIN user AS U ON BR.user_id = U.user_id
  WHERE BR.status = 'borrowed'
");

$active_borrows = $stmt->fetchAll();
?>

<h1 class="mb-4">🛠️ Dashboard Admin</h1>

<div class="row text-center mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h4><?= $total_users ?></h4>
            <p class="text-muted">Total Pengguna</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h4><?= $total_books ?></h4>
            <p class="text-muted">Total Buku</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm p-3">
            <h4><?= $total_borrowings ?></h4>
            <p class="text-muted">Buku Dipinjam</p>
        </div>
    </div>
</div>

<div class="card shadow-sm p-4 mb-4">
    <h3>📚 Peminjaman Aktif</h3>
    <table class="table table-striped">
        <thead class="table-primary">
            <tr>
                <th>Buku</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($active_borrows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['borrow_date'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="mb-4">
    <a href="../books/list.php" class="btn btn-primary">Kelola Buku</a>
</div>

<?php require '../includes/footer.php'; ?>
