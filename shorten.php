<?php
$file = "data/links.json";
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = trim($_POST['url']);
    $alias = trim($_POST['alias'] ?? '');

    // Kalau alias kosong, buat random
    if ($alias === '') {
        do {
            $alias = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);
        } while (isset($data[$alias]));
    } else {
        // Kalau alias ada, tolak (biar cuma admin yang bisa pakai alias)
        exit("Alias hanya bisa dipakai oleh admin.");
    }

    // Tambah ke data
    $data[$alias] = [
        "url" => $url,
        "created_at" => date("c"),
        "visits" => 0
    ];

    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    header("Location: index.php?success=$alias");
    exit;
}
?>