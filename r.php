<?php
session_start();
$code = $_GET['c'] ?? '';

$json_content = @file_get_contents("data/links.json");
if ($json_content === false) {
    http_response_code(500);
    die("Error: Could not read link data. Check file permissions.");
}
$data = json_decode($json_content, true);

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    die("Error: Invalid link data format.");
}

$data = $data ?: [];

if (!isset($data[$code])) {
    http_response_code(404);
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>404 — Link Not Found</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="icon" href="/assets/favicon.svg" type="image/svg+xml">
</head>
<body>
  <div class="main-layout">
    <div class="container message-box">
        <h1 style="color: var(--danger-primary);">404 — Not Found</h1>
        <p>The requested shortlink either never existed or has been permanently removed from our system.</p>
        <a href="/" class="btn btn-primary" style="margin-top: 1rem;">Return to Homepage</a>
    </div>
  </div>
</body>
</html>
<?php
    exit;
}

$link = $data[$code];
$requirePassword = isset($link['password']) && $link['password'] !== '';

if ($requirePassword && !password_verify(($_POST['password'] ?? ''), $link['password'])) {
    $error = ($_SERVER['REQUEST_METHOD'] === 'POST') ? "Access denied. Incorrect key." : null;
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>🔒 Authorization Required</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="icon" href="/assets/favicon.svg" type="image/svg+xml">
</head>
<body>
  <div class="main-layout">
    <div class="container message-box" style="max-width: 450px;">
        <h1 style="text-align: center;">🔐 Encrypted Gateway</h1>
        <p>This link is password protected. Please enter the access key to proceed.</p>
        
        <?php if (isset($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST" style="margin-top: 1.5rem;">
            <input type="password" name="password" placeholder="Enter access key..." required>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Authenticate</button>
        </form>
    </div>
  </div>
</body>
</html>
<?php
    exit;
}

// Valid password or no password needed
$data[$code]['visits'] += 1;
if (file_put_contents("data/links.json", json_encode($data, JSON_PRETTY_PRINT)) === false) {
    // Log the error, but don't stop the redirect as it's a background operation
    error_log("Error writing to links.json for code: " . $code);
}
$url = htmlspecialchars($link['url'], ENT_QUOTES);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="robots" content="noindex" />
  <meta http-equiv="refresh" content="3;url=<?= $url ?>">
  <title>Redirecting...</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="icon" href="/assets/favicon.svg" type="image/svg+xml">
  <style>
    @keyframes pulse {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }
    .loading {
      font-size: 1.2rem;
      margin-top: 10px;
      animation: pulse 1.5s infinite;
    }
  </style>
</head>
<body>
  <div class="main-layout">
    <div class="container message-box">
        <h1>Redirecting</h1>
        <p class="loading">Please wait, you are being redirected...</p>
        <p class="hint">If you are not redirected automatically in 3 seconds, <a href="<?= $url ?>">click here</a>.</p>
    </div>
  </div>
</body>
</html>
