<?php
session_start();
$loginid = $_SESSION['user_id'];

$time = date("h:i:s");
$date = date("l, F j, Y");
$tdate = $time . '  ' . $date;

include("db_connection.php"); // make sure this sets $conn

extract($_POST);
extract($_GET);
extract($_SESSION);

if (isset($subid) && isset($testid)) {
    $_SESSION['sid'] = $subid;
    $_SESSION['tid'] = $testid;
}

// Insert result into the database
mysqli_query(
    $conn,
    "INSERT INTO mst_result(login, subject, test_date, score) 
     VALUES('$loginid', '$testid', '$tdate', '" . $_SESSION['trueans'] . "')"
) or die(mysqli_error($conn));
?>

<script type="text/javascript">
    window.location = 'result.php';
</script>
