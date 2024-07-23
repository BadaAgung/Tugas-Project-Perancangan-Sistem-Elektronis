<?php
session_start(); // Mulai session di awal
require 'function.php';
require 'cek.php';

// Cek apakah pengguna sudah login, jika tidak arahkan ke halaman login
if (!isset($_SESSION['log'])) {
    header('location:login.php');
    exit;
}

$query = "SELECT * FROM sensor ORDER BY Id DESC"; // Fetch last 100 records
$result = $conn->query($query);
$sensorData = array();

while($row = $result->fetch_assoc()) {
    $sensorData[] = $row;
}

$data_berat = "";
$data_jarak = "";
$data_waktu = array();

while($row = mysqli_fetch_assoc($result)){
    $data_berat .= $row['Berat'] . ',';
    $data_jarak .= $row['Jarak'] . ',';
    $data_waktu[] = $row['waktu'];
}

// Hilangkan koma terakhir
$data_berat = rtrim($data_berat, ',');
$data_jarak = rtrim($data_jarak, ',');
$data_waktu_json = json_encode($data_waktu);

// Tutup koneksi database

mysqli_close($conn);                          


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Tugas Besar - PSE</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php">Bada Agung dan Ismail</a>
            </form>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Dashboard</div>
                            <a class="nav-link" href="charts.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Charts
                            </a>
                            <a class="nav-link" href="tables.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Tables
                            </a>
                            <a class="nav-link" href="logout.php">
                                Logout
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Kelompok 9
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Data sensor</h1>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Berat</th>
                                            <th>Jarak</th>
                                            <th>waktu</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                    // Looping untuk menampilkan data sensor dalam tabel
                                        foreach ($sensorData as $row) {
                                            echo "<tr>";
                                            echo "<td>" . $row['Id'] . "</td>";
                                            echo "<td>" . $row['Berat'] . "</td>";
                                            echo "<td>" . $row['Jarak'] . "</td>";
                                            echo "<td>" . $row['waktu'] . "</td>";
                                            echo "</tr>";
                                    }
                                    ?>
                    
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>

    
    
    <script>
        // document.addEventListener("DOMContentLoaded", function () {
            // Menggunakan AJAX untuk mengambil data dari data.php

            // const getData = async () => {
            //     const data = await fetch('http://localhost/stoksensor/index.php');
            //     const _data = await data.json();
            //     console.log(_data);
            // }

            // getData()

            // fetch('http://localhost/stoksensor/index.php')
            //     .then(response => response.json())
            //     .then(data => {
                    // Memuat data yang diambil ke dalam grafik
                    // var ctxArea = document.getElementById('myAreaChart').getContext('2d');
                    // var myAreaChart = new Chart(ctxArea, {
                    //     type: 'line',
                    //     data: {
                    //         labels: data.Waktu,
                    //         datasets: [{
                    //             label: 'Berat',
                    //             data: ,
                    //             backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    //             borderColor: 'rgba(54, 162, 235, 1)',
                    //             borderWidth: 1,
                    //             fill: true,
                    //         }]
                    //     },
                    //     options: {
                    //         responsive: true,
                    //         maintainAspectRatio: false,
                    //         scales: {
                    //             xAxes: [{
                    //                 type: 'time',
                    //                 time: {
                    //                     unit: 'minute' // Sesuaikan dengan skala waktu yang Anda inginkan
                    //                 },
                    //                 scaleLabel: {
                    //                     display: true,
                    //                     labelString: 'Waktu'
                    //                 }
                    //             }],
                    //             yAxes: [{
                    //                 scaleLabel: {
                    //                     display: true,
                    //                     labelString: 'Berat'
                    //                 }
                    //             }]
                    //         }
                    //     }
                    // });

            //         // Grafik Bar Chart
            //         // var ctxBar = document.getElementById('myBarChart').getContext('2d');
            //         // var myBarChart = new Chart(ctxBar, {
            //         //     type: 'bar',
            //         //     data: {
            //         //         labels: data.Waktu,
            //         //         datasets: [{
            //         //             label: 'Jarak',
            //         //             data: data.Jarak,
            //         //             backgroundColor: 'rgba(255, 99, 132, 0.2)',
            //         //             borderColor: 'rgba(255, 99, 132, 1)',
            //         //             borderWidth: 1
            //         //         }]
            //         //     },
            //         //     options: {
            //         //         responsive: true,
            //         //         maintainAspectRatio: false,
            //         //         scales: {
            //         //             xAxes: [{
            //         //                 type: 'time',
            //         //                 time: {
            //         //                     unit: 'minute' // Sesuaikan dengan skala waktu yang Anda inginkan
            //         //                 },
            //         //                 scaleLabel: {
            //         //                     display: true,
            //         //                     labelString: 'Waktu'
            //         //                 }
            //         //             }],
            //         //             yAxes: [{
            //         //                 scaleLabel: {
            //         //                     display: true,
            //         //                     labelString: 'Jarak'
            //         //                 }
            //         //             }]
            //         //         }
            //         //     }
            //         // });
            //     })
            //     .catch(error => {
            //         console.error('Error fetching data:', error);
            //     });
        // });
    </script>
    </body>
</html>

<?php 

require 'function.php';
require 'cek.php';

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

mysqli_close($conn);                          

$response = [
    'berat' => $data_berat,
    'jarak' => $data_jarak,
    'waktu' => $data_waktu
];

$json = json_encode($response);
// var_dump($json);
echo "<script>

let data = $json
const berat = data.berat.map((it) => {
    return Number(it)
});

const waktu = data.waktu.map((it) => {
    const date = new Date(it);
    const formattedDate = date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
    return formattedDate;
});

const jarak = data.jarak.map((it) => {
    return Number(it)
});


var ctxArea = document.getElementById('myAreaChart').getContext('2d');
console.log(berat)
console.log(data.waktu)

var myAreaChart = new Chart(ctxArea, {
                        type: 'line',
                        data: {
                            labels: waktu,
                            datasets: [{
                                label: 'Berat',
                                data: berat,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                                fill: true,
                            }]
                        },
                    })

var ctxBar = document.getElementById('myBarChart').getContext('2d');
var myBarChart = new Chart(ctxBar, {
        type: 'line',
        data: {
            labels: waktu,
            datasets: [{
                label: 'Jarak',
                data: jarak,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                fill: true,
            }]
        },
})

</script>
"
// var ctxArea = document.getElementById('myAreaChart').getContext('2d');
//                     var myAreaChart = new Chart(ctxArea, {
//                         type: 'line',
//                         data: {
//                             labels: data.Waktu,
//                             datasets: [{
//                                 label: 'Berat',
//                                 data: ,
//                                 backgroundColor: 'rgba(54, 162, 235, 0.2)',
//                                 borderColor: 'rgba(54, 162, 235, 1)',
//                                 borderWidth: 1,
//                                 fill: true,
//                             }]
//                         },
//                         options: {
//                             responsive: true,
//                             maintainAspectRatio: false,
//                             scales: {
//                                 xAxes: [{
//                                     type: 'time',
//                                     time: {
//                                         unit: 'minute' // Sesuaikan dengan skala waktu yang Anda inginkan
//                                     },
//                                     scaleLabel: {
//                                         display: true,
//                                         labelString: 'Waktu'
//                                     }
//                                 }],
//                                 yAxes: [{
//                                     scaleLabel: {
//                                         display: true,
//                                         labelString: 'Berat'
//                                     }
//                                 }]
//                             }
//                         }
//                     });

// ;</script>";
    

    ?>