<?php
$servername = "localhost";
$username = "root";
$password = "Fresno5328";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully\r\n";
$sql = "SELECT * from data";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	echo $row["date"]." ";
    }
} else {
    echo "0 results";
}
/*if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()){
		echo $row["data"];
	}
} */

$conn->close();
?>