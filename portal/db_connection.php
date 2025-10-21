<?php
// $servername = "localhost";
// $username = "hapacoll_root";
// $password = "foxtrot2november";
// $dbname = "hapacoll_portal";
// $conn = new mysqli($servername, $username, $password, $dbname);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "portal";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
