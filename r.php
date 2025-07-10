<?php
$code = $_GET['c'] ?? '';
$data = json_decode(file_get_contents("data/links.json"), true);

if (isset($data[$code])) {
    $data[$code]['visits'] += 1;
    file_put_contents("data/links.json", json_encode($data, JSON_PRETTY_PRINT));
    
    $url = $data[$code]['url'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <meta name="robots" content="noindex" />
      <title>Redirecting...</title>
      <style>
        body {
          background: #0a0a0a;
          color: #e0e0e0;
          font-family: 'Segoe UI', sans-serif;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
          margin: 0;
          flex-direction: column;
          text-align: center;
        }
        h1 {
          font-size: 1.8rem;
          color: #90caf9;
        }
        .loading {
          font-size: 1.2rem;
          margin-top: 10px;
          animation: pulse 1s infinite;
        }
        .hint {
          font-size: 0.9rem;
          color: #888;
          margin-top: 20px;
        }
        @keyframes pulse {
          0%, 100% { opacity: 1; }
          50% { opacity: 0.5; }
        }
      </style>
      <script>
        const url = <?= json_encode($url) ?>;
        let countdown = 3;
        const interval = setInterval(() => {
          countdown--;
          document.getElementById('countdown').innerText = countdown;
          if (countdown <= 0) {
            clearInterval(interval);
            window.location.href = url;
          }
        }, 1000);
        setTimeout(() => { window.location.href = url; }, 4000); // Fallback
      </script>
    </head>
    <body>
      <h1>Initializing redirect sequence...</h1>
      <p class="loading">Destination located. Transmitting link in <span id="countdown">3</span> seconds.</p>
      <p class="hint">Do not refresh. This link is classified.</p>
    </body>
    </html>
    <?php
    exit;
} else {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <title>404 Not Found</title>
      <style>
        body {
          background: #121212;
          color: #e0e0e0;
          font-family: 'Segoe UI', sans-serif;
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100vh;
          margin: 0;
          flex-direction: column;
          text-align: center;
        }
        h1 {
          font-size: 5rem;
          color: #ff5252;
          margin: 0;
        }
        p {
          font-size: 1.2rem;
          margin: 10px 0 30px;
        }
        a {
          color: #90caf9;
          text-decoration: none;
          padding: 0.5rem 1rem;
          border: 1px solid #90caf9;
          border-radius: 5px;
          transition: all 0.2s;
        }
        a:hover {
          background: #90caf9;
          color: #121212;
        }
      </style>
    </head>
    <body>
      <h1>404</h1>
      <p>The shortlink you are looking for does not exist or has been removed.</p>
      <a href="/index.php">Back to Home</a>
    </body>
    </html>
    <?php
}