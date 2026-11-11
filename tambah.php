
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$conn = new mysqli("localhost", "root", "", "api_pari");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "pesan" => "Koneksi database gagal",
        "error" => $conn->connect_error
    ]);
    exit;
}

$conn->set_charset("utf8mb4");

// Ambil data POST
$nama_produk = $_POST['nama_produk'] ?? '';
$harga_produk = $_POST['harga_produk'] ?? '';

// Fallback jika data dikirim dalam format URL encoded
if ($nama_produk === '' && $harga_produk === '') {
    $raw = file_get_contents("php://input");
    parse_str($raw, $data);

    $nama_produk = $data['nama_produk'] ?? '';
    $harga_produk = $data['harga_produk'] ?? '';
}

$nama_produk = trim($nama_produk);
$harga_produk = trim($harga_produk);

if ($nama_produk === '' || $harga_produk === '') {
    http_response_code(400);
    echo json_encode([
        "pesan" => "Data produk tidak boleh kosong",
        "debug" => [
            "post" => $_POST,
            "raw" => file_get_contents("php://input")
        ]
    ]);
    exit;
}

$stmt = $conn->prepare(
    "INSERT INTO tb_produk (nama_produk, harga_produk) VALUES (?, ?)"
);

if (!$stmt) {
    http_response_code(500);
    echo json_encode([
        "pesan" => "Prepare gagal",
        "error" => $conn->error
    ]);
    exit;
}

$stmt->bind_param("ss", $nama_produk, $harga_produk);

if ($stmt->execute()) {
    echo json_encode([
        "pesan" => "sukses",
        "id" => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "pesan" => "gagal",
        "error" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
