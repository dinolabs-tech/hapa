<?php
session_start();
// Database connection
include 'db_connection.php';
$did = $_GET['delid'];

// Use mysqli for database operations
mysqli_query($conn, "DELETE FROM question WHERE que_id = '$did'") or die(mysqli_error($cn));

echo '<script type="text/javascript">
alert("The selected question has successfully been deleted from the Database");
window.location="adquest.php";
</script>';

exit();
?>