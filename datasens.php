<?php
// Include file koneksi
require 'function.php';

// Mengambil data dari tabel sensor
$sql = "SELECT Id, Berat, jarak, waktu FROM sensor";
$result = $conn->query($sql);

// Cek jika ada hasil
if ($result->num_rows > 0) {
    // Buat array untuk menyimpan data
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    // Konversi data ke format JSON
    $json_data = json_encode($data);
    echo $json_data;
} else {
    echo "Tidak ada data";
}
?>