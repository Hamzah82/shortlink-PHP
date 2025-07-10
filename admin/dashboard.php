<?php
session_start();
if (!($_SESSION['admin'] ?? false)) exit("Unauthorized");

$file = "../data/links.json";
$data = json_decode(file_get_contents($file), true);

// Proses tambah / update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url']);
    $alias = trim($_POST['alias']);
    $original = $_POST['original'] ?? null;

    if ($original && isset($data[$original])) {
        // Update alias atau URL
        if ($alias !== $original && isset($data[$alias])) {
            $error = "Alias already exists.";
        } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $alias)) {
            $error = "Invalid alias.";
        } else {
            $newData = $data;
            unset($newData[$original]);
            $newData[$alias] = [
                "url" => $url,
                "created_at" => $data[$original]['created_at'],
                "visits" => $data[$original]['visits']
            ];
            file_put_contents($file, json_encode($newData, JSON_PRETTY_PRINT));
            header("Location: dashboard.php");
            exit;
        }
    } else {
        // Tambah baru
        if (isset($data[$alias])) {
            $error = "Alias already exists.";
        } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $alias)) {
            $error = "Invalid alias.";
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
}

// Delete
if (isset($_GET['delete'])) {
    unset($data[$_GET['delete']]);
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    header("Location: dashboard.php");
    exit;
}

// Data edit
$edit = null;
if (isset($_GET['edit']) && isset($data[$_GET['edit']])) {
    $edit = [
        "alias" => $_GET['edit'],
        "url" => $data[$_GET['edit']]['url']
    ];
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
      <input type="url" name="url" placeholder="https://example.com" value="<?= $edit['url'] ?? '' ?>" required>
      <input type="text" name="alias" placeholder="custom-alias" value="<?= $edit['alias'] ?? '' ?>" required>
      <?php if ($edit): ?>
        <input type="hidden" name="original" value="<?= $edit['alias'] ?>">
        <button type="submit">Update</button>
        <a href="dashboard.php">Cancel</a>
      <?php else: ?>
        <button type="submit">Shorten</button>
      <?php endif; ?>
    </form>

    <ul>
      <?php foreach($data as $key => $info): ?>
        <li>
          <?= $key ?> → <?= $info['url'] ?> (<?= $info['visits'] ?> visits)
          <a href="?edit=<?= $key ?>">Edit</a> |
          <a href="?delete=<?= $key ?>" onclick="return confirm('Hapus?')">Delete</a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</body>
</html>