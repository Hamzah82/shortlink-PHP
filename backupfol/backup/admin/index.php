<?php
session_start();
if ($_POST['password'] ?? '' === 'Hamzah1088') {
    $_SESSION['admin'] = true;
    header('Location: dashboard.php');
    exit;
}
?>
<form method="POST">
  <input type="password" name="password" placeholder="Password">
  <button type="submit">Login</button>
</form>
