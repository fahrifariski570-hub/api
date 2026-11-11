<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli(hostname:'localhost',username:'root',password:'',database:'api_pari');
if($conn->connect_error){
    echo json_encode(['pesan'=>'gagal',
    'error'=>$conn->connect_error]);
    exit;
}
$id_produk=$_POST['id_produk'];
$query=mysqli_query($conn,query:"delete from tb_produk where id_produk='$id_produk'");
if($query){
    echo json_encode([
        'pesan'=>'sukses'
    ]);
}else{
    echo json_encode([
        'pesan'=>'gagal'
    ]);
}
$conn->close();
?>