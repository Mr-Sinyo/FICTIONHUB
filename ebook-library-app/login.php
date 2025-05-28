<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Query to find user by username
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    // Debug: Check if user is fetched correctly
    // var_dump($user); // Uncomment this for debugging to see user data

    if ($user) {
        // Debug: Check the hashed password in the database and verify it with input
        // echo "Database Password Hash: " . $user['password']; // Debugging line

        if (password_verify($password, $user['password'])) {
            // Save user data in session
            $_SESSION['user'] = $user;
            // Redirect to homepage after successful login
            header("Location: index.php");
            exit;
        } else {
            // Password is incorrect
            $error = "Username atau Password salah.";
        }
    } else {
        // Username not found
        $error = "Username atau Password salah.";
    }
}
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm p-4">
      <h2 class="mb-4 text-center">🔐 Login</h2>

      <?php if (isset($_GET['registered'])): ?>
          <div class="alert alert-success">Berhasil daftar! Silakan login.</div>
      <?php endif; ?>

      <?php if ($error): ?>
          <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST">
          <div class="mb-3">
              <label class="form-label">Email</label>
              <input name="username" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-success w-100">Login</button>
      </form>
    </div>
  </div>
</div>

<?php require 'includes/footer.php'; ?>
