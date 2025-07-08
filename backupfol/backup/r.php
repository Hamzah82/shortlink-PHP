<?php
$code = $_GET['c'] ?? '';
$data = json_decode(file_get_contents("data/links.json"), true);

if (isset($data[$code])) {
    $data[$code]['visits'] += 1;
    file_put_contents("data/links.json", json_encode($data, JSON_PRETTY_PRINT));
    header("Location: " . $data[$code]['url']);
    exit;
} else {
    echo "404 Not Found";
}
