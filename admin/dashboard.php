<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: /admin/");
    exit;
}

if (!($_SESSION['admin'] ?? false)) {
    header("Location: /403.html");
    exit;
}

// Perlindungan CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$file = "../data/links.json";
$data = json_decode(file_get_contents($file), true) ?: [];

// Fungsi untuk memverifikasi token CSRF
function verify_csrf_token($token) {
    return isset($token) && hash_equals($_SESSION['csrf_token'], $token);
}

// Tambah atau update link
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        die('CSRF token validation failed.');
    }

    $url = trim($_POST['url']);
    $alias = trim($_POST['alias']);
    $original = $_POST['original'] ?? null;
    $password = trim($_POST['password']) ?: null;

    // Validate URL format
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $error = "Invalid URL format.";
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $alias)) {
        $error = "Invalid alias format.";
    } else {
        // Hash password if provided
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        } else {
            // If no new password is provided during update, retain the old one
            $hashedPassword = ($original && isset($data[$original]['password'])) ? $data[$original]['password'] : null;
        }

        if ($original && isset($data[$original])) {
            // UPDATE
            if ($alias !== $original && isset($data[$alias])) {
                $error = "Alias already exists.";
            } else {
                $updated = $data;
                unset($updated[$original]);
                $updated[$alias] = [
                    "url" => $url,
                    "created_at" => $data[$original]['created_at'],
                    "visits" => $data[$original]['visits'],
                    "password" => $hashedPassword
                ];
                if (file_put_contents($file, json_encode($updated, JSON_PRETTY_PRINT)) === false) {
                    $error = "Failed to save link. Check file permissions.";
                } else {
                    header("Location: /admin/dashboard.php");
                    exit;
                }
            }
        } else {
            // CREATE
            if (isset($data[$alias])) {
                $error = "Alias already exists.";
            } else {
                $data[$alias] = [
                    "url" => $url,
                    "created_at" => date("c"),
                    "visits" => 0,
                    "password" => $hashedPassword
                ];
                if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT)) === false) {
                    $error = "Failed to save link. Check file permissions.";
                } else {
                    header("Location: /admin/dashboard.php");
                    exit;
                }
            }
        }
    }
} else if (isset($_GET['delete'])) {
    if (!verify_csrf_token($_GET['csrf_token'])) {
        die('CSRF token validation failed.');
    }
    unset($data[$_GET['delete']]);
    if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT)) === false) {
        $error = "Failed to delete link. Check file permissions.";
    } else {
        header("Location: /admin/dashboard.php");
        exit;
    }
}

// Edit mode
$edit = null;
if (isset($_GET['edit']) && isset($data[$_GET['edit']])) {
    $edit = [
        "alias" => $_GET['edit'],
        "url" => $data[$_GET['edit']]['url'],
        "password" => $data[$_GET['edit']]['password'] ?? ''
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - URL Shortener</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="icon" href="../assets/favicon.svg" type="image/svg+xml">
</head>
<body>
  <div class="main-layout">
    <header class="site-header">
        <div class="logo"><a href="dashboard.php">🔗 LinkManager</a></div>
        <a href="?logout=1" class="btn btn-secondary">Logout</a>
    </header>

    <div class="container">
        <h1>Dashboard</h1>

        <?php if (isset($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <div class="form-section">
            <h2><?= $edit ? '✏️ Edit Link' : '✨ Create New Link' ?></h2>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <div class="form-grid">
                    <input type="url" name="url" placeholder="Enter target URL" value="<?= htmlspecialchars($edit['url'] ?? '') ?>" required>
                    <input type="text" name="alias" placeholder="Custom alias" value="<?= htmlspecialchars($edit['alias'] ?? '') ?>" required>
                    <input type="text" name="password" placeholder="Optional password" value="<?= htmlspecialchars($edit['password'] ?? '') ?>">
                </div>
                <div class="form-actions">
                    <?php if ($edit): ?>
                    <input type="hidden" name="original" value="<?= htmlspecialchars($edit['alias']) ?>">
                    <button type="submit" class="btn btn-primary">Update Link</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel Edit</a>
                    <?php else: ?>
                    <button type="submit" class="btn btn-primary">Create Link</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <h2>📋 Existing Links</h2>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="th-alias">Alias</th>
                        <th class="th-url">Destination URL</th>
                        <th class="th-visits">Visits</th>
                        <th class="th-status">Status</th>
                        <th class="th-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem;">No links created yet.</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($data as $key => $info): ?>
                    <tr>
                        <td data-label="Alias"><strong><a href="/<?= htmlspecialchars($key) ?>" target="_blank"><?= htmlspecialchars($key) ?></a></strong></td>
                        <td data-label="Destination URL" class="col-url"><a href="<?= htmlspecialchars($info['url']) ?>" target="_blank" title="<?= htmlspecialchars($info['url']) ?>"><?= htmlspecialchars(substr($info['url'], 0, 50)) . (strlen($info['url']) > 50 ? '...' : '') ?></a></td>
                        <td data-label="Visits"><?= $info['visits'] ?></td>
                        <td data-label="Status"><?= isset($info['password']) && $info['password'] ? "🔐 Protected" : "Public" ?></td>
                        <td data-label="Actions" class="actions">
                            <button class="btn-icon copy-btn" data-alias="<?= htmlspecialchars($key) ?>" title="Copy Shortlink">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            </button>
                            <a href="?edit=<?= urlencode($key) ?>" class="btn-icon" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h3"></path><path d="M18.4 2.6a2.17 2.17 0 0 1 3 3L7.4 20.6 2 22l1.4-5.4L18.4 2.6z"></path></svg>
                            </a>
                            <a href="?delete=<?= urlencode($key) ?>&csrf_token=<?= urlencode($csrf_token) ?>" class="btn-icon" title="Delete" onclick="return confirm('Are you sure you want to delete this link?')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                            </a>
                            <button class="btn-icon qr-btn" data-alias="<?= htmlspecialchars($key) ?>" title="Show QR Code">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h6v6H3zM15 3h6v6h-6zM3 15h6v6H3zM15 15h6v6h-6z"/></svg>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

  <!-- QR Code Modal -->
  <div id="qr-modal" class="modal-overlay">
    <div class="modal-content">
      <span class="modal-close">&times;</span>
      <h2>QR Code for: <span id="qr-alias-display"></span></h2>
      <div id="qr-code-container"></div>
      <a id="qr-download-btn" class="btn btn-primary" download="qr-code.png">Download PNG</a>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('qr-modal');
        const closeBtn = modal.querySelector('.modal-close');
        const qrCodeContainer = document.getElementById('qr-code-container');
        const downloadBtn = document.getElementById('qr-download-btn');
        const aliasDisplay = document.getElementById('qr-alias-display');
        const copySuccessPopup = document.getElementById('copy-success-popup');

        document.querySelectorAll('.qr-btn').forEach(button => {
            button.addEventListener('click', () => {
                const alias = button.dataset.alias;
                const currentUrl = window.location.origin + '/' + alias;
                const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=256x256&data=${encodeURIComponent(currentUrl)}`;

                aliasDisplay.textContent = alias;
                qrCodeContainer.innerHTML = `<img src="${qrApiUrl}" alt="QR Code for ${alias}">`;
                downloadBtn.href = qrApiUrl;
                downloadBtn.download = `qr-code-${alias}.png`;
                
                modal.classList.add('visible');
            });
        });

        const closeModal = () => {
            modal.classList.remove('visible');
        };

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('visible')) {
                closeModal();
            }
        });

        // Copy to clipboard
        document.querySelectorAll('.copy-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                const alias = e.currentTarget.dataset.alias;
                const shortlink = window.location.origin + '/' + alias;

                const textArea = document.createElement('textarea');
                textArea.value = shortlink;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    // Show popup
                    copySuccessPopup.classList.add('show');
                    setTimeout(() => {
                        copySuccessPopup.classList.remove('show');
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy: ', err);
                    alert('Failed to copy link.');
                }
                document.body.removeChild(textArea);
            });
        });
    });
  </script>

</body>
</html>

<!-- Copy Success Popup -->
<div id="copy-success-popup" class="copy-popup">Link Copied!</div>