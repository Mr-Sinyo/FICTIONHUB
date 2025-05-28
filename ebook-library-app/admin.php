<?php
require 'includes/db.php';
require 'includes/header.php';
session_start();

// Hanya admin yang boleh mengakses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("<div class='alert alert-danger text-center mt-5'>❌ Unauthorized Access. Hanya Admin yang bisa mengakses halaman ini.</div>");
}

// Ambil statistik
$total_users = $pdo->query("SELECT COUNT(*) FROM user WHERE role = 'user'")->fetchColumn();
$total_books = $pdo->query("SELECT COUNT(*) FROM book")->fetchColumn();
$total_borrowings = $pdo->query("SELECT COUNT(*) FROM borrowing WHERE status = 'borrowed'")->fetchColumn();

// Daftar peminjaman aktif
$stmt = $pdo->query("
  SELECT B.title, U.email AS user_email, BR.borrow_date
  FROM borrowing AS BR
  JOIN book AS B ON BR.book_id = B.book_id
  JOIN user AS U ON BR.user_id = U.user_id
  WHERE BR.status = 'borrowed'
");

$active_borrows = $stmt->fetchAll();
?>

<div class="container mt-5">
  <h1 class="mb-4">🛠️ Dashboard Admin</h1>

  <!-- Statistik -->
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

  <!-- Daftar Peminjaman Aktif -->
  <div class="card shadow-sm p-4 mb-4">
    <h3>📚 Peminjaman Aktif</h3>
    <table class="table table-striped">
      <thead class="table-primary">
        <tr>
          <th>Judul Buku</th>
          <th>Email Peminjam</th>
          <th>Tanggal Pinjam</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($active_borrows) > 0): ?>
          <?php foreach ($active_borrows as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['user_email']) ?></td>
            <td><?= htmlspecialchars($row['borrow_date']) ?></td>
          </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="3" class="text-center text-muted">Tidak ada peminjaman aktif saat ini.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Link ke kelola buku -->
  <div class="mb-4">
    <a href="books/list.php" class="btn btn-primary">Kelola Buku</a>
    <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
  </div>
</div>

<?php require 'includes/footer.php'; ?>
