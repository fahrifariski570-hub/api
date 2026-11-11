<?php
//header api
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,OPTIONS");
header("Access-Control-Allow-Headers:X-Requested-With,Content-Type");
header("Content-Type:application/json; charset=UTF-8");

//request options
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    http_response_code(200);
    exit;
}

//database connection
$conn=new mysqli('localhost', 'root', '', 'api_pari');

//check database connection
if ($conn->connect_error) {
    echo json_encode([
        'status' => false,
        'pesan' => 'Koneksi database gagal',
        'error' => $conn->connect_error
    ]);
    exit;
}

//get username and password
$username = $_POST['username']??'';
$password = $_POST['password']??'';

//search user from table user
$stmt = $conn->prepare(
    "SELECT * FROM tb_user WHERE username = ?"
);

//mwngambil pencarian username
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if($user=$result->fetch_assoc()){
    //password checking
    if (md5($password) === $user['password']){
        echo json_encode([
            "status" => true,
            "pesan" => "Login berhasil",
            "data" => [
                "id" => $user['id'],
                "username" => $user['username'],
                "nama" => $user['nama']
            ]
        ]);
    } else {
        echo json_encode([
            "status" => false,
            "pesan" => "Password salah"
        ]);
    }
} else{
        echo json_encode([
            "status" => false,
            "pesan" => "Username tidak ditemukan"
        ]);
    }
    $stmt->close();
    $conn->close();

?>