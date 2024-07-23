<?php
require 'function.php';

// Ambil data waktu dan berat dari database
$berat_query = "SELECT Berat FROM sensor ORDER by Id asc";
$brt = mysqli_query($conn, $berat_query);
if(!$brt) {
    die ("query gagal: " . mysqli_error($conn));
}

// Inisialisasi array untuk menyimpan data yang akan digunakan di dalam grafik
$data_berat = [];

$jarak_query = "SELECT Jarak FROM sensor ORDER by Id asc";
$jrk = mysqli_query($conn, $jarak_query);
if(!$jrk) {
    die ("query gagal: " . mysqli_error($conn));
}
// Inisialisasi array untuk menyimpan data yang akan digunakan di dalam grafik
$data_jarak = [];

$waktu_query = "SELECT waktu FROM sensor ORDER by Id asc";
$wak = mysqli_query($conn, $waktu_query);
if(!$wak) {
    die ("query gagal: " . mysqli_error($conn));
}

$data_waktu = [];

// Looping untuk mengambil data dari hasil query
while ($berat = mysqli_fetch_array($brt)) {
    $data_berat[] = $berat['Berat'];
}

while ($dwaktu = mysqli_fetch_array($wak)) {
    $data_waktu[] = $dwaktu['waktu'];
}

while ($djarak = mysqli_fetch_array($jrk)) {
    $data_jarak[] = $djarak['Jarak'];
}


$response = [
    'Berat' => $data_berat,
    'Jarak' => $data_jarak,
    'waktu' => $data_waktu
];

echo json_encode($response);
?>

