
<?php
session_start();
if (!($_SESSION['admin'] ?? false)) exit("Unauthorized");

$file = "../data/links.json";
$data = json_decode(file_get_contents($file), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url']);
    $alias = trim($_POST['alias']);

    if (isset($data[$alias])) {
        $error = "Alias already exists.";
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $alias)) {
        $error = "Invalid alias. Use only letters, numbers, dash or underscore.";
    } else {
        $data[$alias] = [
            "url" => $url,
            "created_at" => date("c"),
            "visits" => 0
        ];
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        header("Location: dashboard.php");
        exit;
    }
}

if (isset($_GET['delete'])) {
    unset($data[$_GET['delete']]);
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
    <h1>Admin Panel</h1>
    <?php if (isset($error)): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="url" name="url" placeholder="https://example.com" required>
      <input type="text" name="alias" placeholder="custom-alias" required>
      <button type="submit">Shorten</button>
    </form>
    <ul>
      <?php foreach($data as $key => $info): ?>
        <li>
          <?=$key?> → <?=$info['url']?> (<?=$info['visits']?> visits)
          <a href="?delete=<?=$key?>" onclick="return confirm('Hapus?')">Hapus</a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</body>
</html>
