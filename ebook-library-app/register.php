<?php
session_start();
require 'includes/db.php';
require 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];  // Corrected to retrieve the email field
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists in the database
    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $existing_user = $stmt->fetch();

    if ($existing_user) {
        // Email already exists
        echo "<p class='alert alert-danger'>Email is already registered.</p>";
    } else {
        // Insert new user if email is not already in use
        $stmt = $pdo->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");

        // Redirect to login page
        header("Location: login.php");
        exit();
    }
}
?>

<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm p-4">
      <h2 class="mb-4 text-center">📝 Registrasi</h2>
      <form method="POST">
          <div class="mb-3">
              <label class="form-label">Username</label>
              <input name="username" class="form-control" required>
          </div>
          <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" required> <!-- Corrected name to email -->
          </div>
          <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Daftar</button>
      </form>
    </div>
  </div>
</div>

<?php require 'includes/footer.php'; ?>

