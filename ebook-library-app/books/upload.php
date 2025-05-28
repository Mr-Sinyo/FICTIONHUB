<?php
require '../includes/db.php';
require '../includes/auth.php';
require '../includes/header.php';


$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
$synopsis = '';

if ($isAdmin && isset($_POST['synopsis'])) {
  $synopsis = $_POST['synopsis'];
}
$stmt = $pdo->prepare("INSERT INTO book (title, author, publisher, year, genre, file_path, cover_image, synopsis)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)");



// ✅ Fungsi resize image
function resizeImage($source, $destination, $new_width) {
    list($width, $height, $type) = getimagesize($source);
    $new_height = ($new_width / $width) * $height;

    switch ($type) {
        case IMAGETYPE_JPEG:
            $src_img = imagecreatefromjpeg($source);
            break;
        case IMAGETYPE_PNG:
            $src_img = imagecreatefrompng($source);
            break;
        case IMAGETYPE_GIF:
            $src_img = imagecreatefromgif($source);
            break;
        default:
            return false;
    }

    $dst_img = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($dst_img, $destination, 85);
            break;
        case IMAGETYPE_PNG:
            imagepng($dst_img, $destination);
            break;
        case IMAGETYPE_GIF:
            imagegif($dst_img, $destination);
            break;
    }

    imagedestroy($src_img);
    imagedestroy($dst_img);
    return true;
}

if ($_SESSION['user']['role'] !== 'admin') die('Unauthorized');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];
    $file_path = $_POST['file_path'];
    $cover_image = null;

    // ✅ Upload cover image (jika ada)
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
        $target_dir = "../uploads/covers/";
        $file_name = time() . "_" . basename($_FILES['cover']['name']);
        $target_file = $target_dir . $file_name;

        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            // Simpan original, lalu resize
            move_uploaded_file($_FILES['cover']['tmp_name'], $target_file);
            resizeImage($target_file, $target_file, 300); // Resize to max width 300px
            $cover_image = "uploads/covers/" . $file_name; // Simpan path relatif
        }
    }

    // ✅ Insert ke database
    $stmt = $pdo->prepare("INSERT INTO Book (title, author, publisher, year, genre, file_path, cover_image)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $author, $publisher, $year, $genre, $file_path, $cover_image]);

    header("Location: list.php?uploaded=1");
    exit;
}
?>

<h2 class="mb-4">📚 Tambah Buku</h2>

<form method="POST" enctype="multipart/form-data" class="card card-body shadow-sm p-4">
    <div class="mb-3">
        <label class="form-label">Judul</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Penulis</label>
        <input type="text" name="author" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Penerbit</label>
        <input type="text" name="publisher" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Tahun Terbit</label>
        <input type="number" name="year" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Genre</label>
        <input type="text" name="genre" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Path File eBook (URL atau path)</label>
        <input type="text" name="file_path" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Upload Cover Image</label>
        <input type="file" name="cover" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Tambah Buku</button>
    <?php if ($isAdmin): ?>
  <div class="mb-3">
    <label for="synopsis" class="form-label">Sinopsis</label>
    <textarea name="synopsis" class="form-control" rows="5" placeholder="Tuliskan sinopsis buku"><?= isset($book['synopsis']) ? htmlspecialchars($book['synopsis']) : '' ?></textarea>
  </div>
  <?php if ($isAdmin): ?>
  <div class="mb-3">
    <label for="synopsis" class="form-label">Sinopsis</label>
    <textarea name="synopsis" class="form-control" rows="5" placeholder="Tuliskan sinopsis buku..."></textarea>
  </div>
<?php endif; ?>

<?php endif; ?>
</form>

<?php require '../includes/footer.php'; ?>
