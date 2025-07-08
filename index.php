
<?php
$data = json_decode(file_get_contents("data/links.json"), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Shortlink</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="container">
    <h1>Shorten your link</h1>
    <form method="POST" action="admin/dashboard.php">
      <input type="url" name="url" placeholder="https://example.com" required>
      <input type="text" name="alias" placeholder="custom-alias (admin only)" required>
      <button type="submit">Shorten</button>
    </form>
    <h2>Latest links</h2>
    <ul>
      <?php foreach(array_reverse($data) as $key => $info): ?>
        <li><a href="r.php?c=<?=$key?>" target="_blank"><?=$key?></a> → <?=$info['url']?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</body>
</html>
