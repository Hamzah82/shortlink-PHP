
<?php
session_start();
if ($_POST['password'] ?? '' === 'admin123') {
    $_SESSION['admin'] = true;
    header('Location: dashboard.php');
    exit;
}
?>
<form method="POST">
  <input type="password" name="password" placeholder="Password">
  <button type="submit">Login</button>
</form>
