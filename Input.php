<?php
 
$hostname = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "stoksensor"; 

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) { 
	die("Connection failed: " . mysqli_connect_error()); 
} 

echo "Database connection is OK<br>"; 

if(isset($_POST["Berat"]) && isset($_POST["Jarak"])) {

	$Berat = $_POST["Berat"];
	$Jarak = $_POST["Jarak"];

	$sql = "INSERT INTO sensor (Berat, Jarak) VALUES (".$Berat.", ".$Jarak.")"; 

	if (mysqli_query($conn, $sql)) { 
		echo "\nNew record created successfully"; 
	} else { 
		echo "Error: " . $sql . "<br>" . mysqli_error($conn); 
	}
}

?>