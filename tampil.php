<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

$koneksi = new mysqli('localhost', 'root', '', 'api_pari');

if ($koneksi->connect_error) {
    http_response_code(500);
    echo json_encode(['pesan' => 'Koneksi database gagal']);
    exit;
}

$query = mysqli_query($koneksi, "SELECT * FROM tb_produk");

$data = [];

while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

echo json_encode($data);
?>
    