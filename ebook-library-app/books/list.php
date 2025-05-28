<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/header.php';

$stmt = $pdo->query("SELECT * FROM Book");
$books = $stmt->fetchAll();
?>

<h2 class="mb-4">📚 Daftar Buku</h2>
<a href="upload.php" class="btn btn-success mb-3">➕ Tambah Buku</a>

<table class="table table-bordered table-striped align-middle">
    <thead class="table-primary">
        <tr>
            <th>Cover</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($books as $book): ?>
        <tr>
            <td style="width: 80px;">
                <?php if (!empty($book['cover_image'])): ?>
                    <img src="/ebook-library-app/<?= $book['cover_image'] ?>" alt="Cover" style="width:60px; height:auto;">
                <?php else: ?>
                    <span class="text-muted">-</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($book['title']) ?></td>
            <td><?= htmlspecialchars($book['author']) ?></td>
            <td>
                <a href="edit.php?id=<?= $book['book_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                <a href="delete.php?id=<?= $book['book_id'] ?>" class="btn btn-sm btn-danger"
                   onclick="return confirm('Hapus buku ini?')">Hapus</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require '../includes/footer.php'; ?>
