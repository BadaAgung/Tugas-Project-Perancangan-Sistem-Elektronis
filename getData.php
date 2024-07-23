<?php
// Include file koneksi
require 'connect.php';

// Query SQL untuk mengambil data dari tabel sensor
$sql = "SELECT * FROM sensor";

// Eksekusi query
$result = $conn->query($sql);

// Cek apakah query berhasil
if (!$result) {
    die("Query gagal: ". $conn->error);
}

// Ambil data dari hasil query
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Tutup koneksi
$conn->close();

// Output data dalam format yang sesuai untuk tabel HTML
echo json_encode($data);
?>