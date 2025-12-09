<?php
// process_result.php
include('components/admin_logic.php'); // Replace with your DB connection

$message = "";
$term = "";
$session = "";
$previousterm = "";

// Fetch current term
$currentTermResult = $conn->query("SELECT cterm FROM currentterm WHERE id = 1");
if ($currentTermResult && $row = $currentTermResult->fetch_assoc()) {
    $term = $row['cterm'];
}

// Fetch current session
$currentSessionResult = $conn->query("SELECT csession FROM currentsession WHERE id = 1");
if ($currentSessionResult && $row = $currentSessionResult->fetch_assoc()) {
    $session = $row['csession'];
}

// Determine previous term
if ($term === "2nd Term") {
    $previousterm = "1st Term";
} elseif ($term === "3rd Term") {
    $previousterm = "2nd Term";
}

if (isset($_POST['run_process'])) {
    try {

          // Step 1: Calculate average considering lastcum
        $conn->query("UPDATE mastersheet SET total = ca1 + ca2 + exam  WHERE term = '$term' AND csession = '$session'");


        // Step 2: Copy total to average where lastcum = 0
        $conn->query("UPDATE mastersheet SET average = total WHERE lastcum = 0");

        // Step 3: If previous term exists, copy last term average to lastcum
        if (!empty($previousterm)) {
            $sql = "
                UPDATE mastersheet AS second_term
                JOIN mastersheet AS first_term ON
                    second_term.id = first_term.id AND
                    second_term.name = first_term.name AND
                    second_term.subject = first_term.subject AND
                    second_term.class = first_term.class AND
                    second_term.arm = first_term.arm AND
                    second_term.csession = first_term.csession
                SET second_term.lastcum = first_term.average
                WHERE first_term.term = '$previousterm' AND
                      second_term.term = '$term' AND
                      second_term.csession = '$session'";
            $conn->query($sql);
        }

        // Step 4: Calculate average considering lastcum
        $conn->query("UPDATE mastersheet SET average = CASE WHEN lastcum = 0 THEN total ELSE (total + lastcum) / 2 END WHERE term = '$term' AND csession = '$session'");

        // Step 5: Grade assignment
        $conn->query("UPDATE mastersheet SET grade = 
            CASE 
                WHEN average >= 75 THEN 'A'
                WHEN average >= 65 THEN 'B'
                WHEN average >= 50 THEN 'C'
                WHEN average >= 45 THEN 'D'
                WHEN average >= 40 THEN 'E'
                ELSE 'F'
            END");

        // Step 6: Remark assignment
        $conn->query("UPDATE mastersheet SET remark = 
            CASE 
                WHEN average >= 75 THEN 'EXCELLENT'
                WHEN average >= 65 THEN 'VERY GOOD'
                WHEN average >= 50 THEN 'GOOD'
                WHEN average >= 45 THEN 'FAIR'
                WHEN average >= 40 THEN 'POOR'
                ELSE 'VERY POOR'
            END");

        // Step 7: Position ranking
        $conn->query("SET @rank := 0"); // Not strictly needed with RANK()
        $sql = "
            UPDATE mastersheet m
            JOIN (
                SELECT id, subject, class, term, name,
                       RANK() OVER (PARTITION BY subject, class, term ORDER BY average DESC) AS position
                FROM mastersheet
                WHERE term = '$term' AND csession = '$session'
            ) ranks
            ON m.id = ranks.id AND m.subject = ranks.subject AND m.class = ranks.class AND m.term = ranks.term AND m.name = ranks.name
            SET m.position = ranks.position";
        $conn->query($sql);

        $message = "<div style='color: green;'>Result processing for <b>$term</b> - <b>$session</b> completed successfully.</div>";
    } catch (Exception $e) {
        $message = "<div style='color: red;'>Error: " . $e->getMessage() . "</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include('adminnav.php'); ?>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <?php include('logo_header.php'); ?>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <?php include('navbar.php'); ?>
                <!-- End Navbar -->
            </div>

            <div class="container">
                <div class="page-inner">
                    <div
                        class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block">
                        <div>
                            <h3 class="fw-bold mb-3">Maintenance</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Home</li>
                                <li class="breadcrumb-item active">Maintenance</li>
                            </ol>
                        </div>

                    </div>


                    <div class="row justify-content-center">

                        <div class="col-md-4">
                            <div class="card card-round">
                              
                                <div class="card-body pb-0">
                                    <div class="mb-4 mt-2">

                                        <form method="POST">
                                            <input type="hidden" name="term" value="<?= htmlspecialchars($term) ?>">
                                            <input type="hidden" name="session" value="<?= htmlspecialchars($session) ?>">
                                            <button class="w-100 btn btn-warning pt-3 pb-3 rounded-3" type="submit" name="run_process">Run Result Maintenance</button>
                                        </form>
                                        <div class="message"><?= $message ?></div>

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>

            </script>
            <?php include('footer.php'); ?>
        </div>

        <!-- Custom template | don't include it in your project! -->
        <?php include('cust-color.php'); ?>
        <!-- End Custom template -->
    </div>
    <?php include('scripts.php'); ?>



</body>

</html>