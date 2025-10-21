<?php
session_start();
if (!isset($_SESSION["staffname"])) {
    header("Location: portal/login.php");
    exit();
}
include("db_connect.php");

$sql = "SELECT * FROM categories";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>
<title>Manage Categories</title>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<body>
       <!-- Navbar Start -->
    <?php include('components/header.php'); ?>
    <!-- Navbar End -->

    <main class="main">

        <!-- Page Title -->
        <div class="page-title dark-background" style="background-image: url(assets/img/education/showcase-1.webp);">
            <div class="container position-relative">
                <h1>Manage Categories</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Manage Categories</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->

    <!-- Content Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
                <div class="col-12 mb-4">
                    <a href="create_category.php" class="btn btn-primary w-100 py-3">Create New Category</a>
                </div>
            </div>

            <div class="row g-5">
                <div class="col-lg-12 wow slideInUp" data-wow-delay="0.3s">
                    <div class="table-responsive">
                        <table id="categoryTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                                        echo "<td>" . htmlspecialchars($row["description"]) . "</td>";
                                        echo "<td>";
                                        echo "<a href='edit_category.php?id=" . $row["id"] . "' class='btn btn-sm btn-primary'>Edit</a> ";
                                        echo "<a href='delete_category.php?id=" . $row["id"] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this category?\")'>Delete</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Content End -->

    <!-- Footer Start -->
    <?php include('components/footer.php'); ?>
    <!-- Footer End -->
  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>
      
    <?php include('components/scripts.php'); ?>

    <!-- jQuery (needed for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#categoryTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50],
                "language": {
                    "search": "Search Categories:"
                }
            });
        });
    </script>

</body>

</html>