<?php
session_start();

// Redirect ke dashboard kalau sudah login
if ($_SESSION['admin'] ?? false) {
    header('Location: /admin/dashboard.php');
    exit;
}

// Variabel error
$error = '';

// Proses login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    // Hash password yang aman. Ganti 'SangatRahasia@2025!' dengan password Anda sendiri.
    // Untuk membuat hash baru, gunakan: echo password_hash('password_baru_anda', PASSWORD_DEFAULT);
    $hashed_password = '$2y$12$3TrQe6oti4QDo7zpnIXE1eBnDPaxvdrV5BafQXYI7VU25XXUHx7Ti'; // Hash untuk 'Hamzah1089'

    if (password_verify($password, $hashed_password)) {
        session_regenerate_id(true); // Mencegah session fixation
        $_SESSION['admin'] = true;
        header('Location: /admin/dashboard.php');
        exit;
    } else {
        $error = 'Invalid password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="icon" href="../assets/favicon.svg" type="image/svg+xml">
</head>
<body>
  <div class="main-layout">
    <div class="container" style="max-width: 450px;">
        <h1 style="text-align: center;">Admin Login</h1>
        
        <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" style="margin-top: 2rem;">
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </form>
    </div>
  </div>
</body>
</html>
