<?php
session_start();
if (!isset($_SESSION["staffname"])) {
    header("Location: portal/login.php");
    exit();
}

include("db_connect.php");
?>


<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>

<body>

    <!-- Navbar Start -->
    <?php include('components/header.php'); ?>
    <!-- Navbar End -->

    <main class="main">

        <!-- Page Title -->
        <div class="page-title dark-background" style="background-image: url(assets/img/education/showcase-1.webp);">
            <div class="container position-relative">
                <h1>Create Post</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Create Post</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->

        <!-- Quote Start -->
        <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container py-5">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="bg-primary rounded p-5 wow zoomIn" data-wow-delay="0.9s">
                            <form action="save_post.php" method="post" enctype="multipart/form-data" class="row g-4" novalidate>

                                <div class="col-12">
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Post Title" required>
                                </div>

                                <div class="col-12">
                                    <textarea class="form-control" id="content" name="content" placeholder="Content" rows="6"></textarea>
                                </div>

                                <div class="col-12">
                                    <label for="image" class="form-label text-white">Upload Image</label>
                                    <input type="file" class="form-control" id="image" name="image" required>
                                </div>

                                <div class="col-12">
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <?php
                                        $sql_categories = "SELECT id, name FROM categories";
                                        $result_categories = $conn->query($sql_categories);
                                        if ($result_categories->num_rows > 0) {
                                            while ($row_category = $result_categories->fetch_assoc()) {
                                                echo "<option value='" . $row_category["id"] . "'>" . htmlspecialchars($row_category["name"]) . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>No categories available</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-light px-5 py-2">Save Post</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Quote End -->



        <script>
            tinymce.init({
                selector: '#content',
                menubar: false,
                toolbar: 'undo redo | formatselect | bold italic underline superscript subscript | alignleft aligncenter alignright | bullist numlist outdent indent | table',
                plugins: 'lists',
                branding: false
            });

            document.querySelector('form').addEventListener('submit', function(e) {
                if (tinymce.get('content').getContent({
                        format: 'text'
                    }).trim() === '') {
                    alert('Please enter some content.');
                    e.preventDefault();
                }
            });
        </script>

    </main>
    <!-- Footer Start -->
    <?php include('components/footer.php'); ?>
    <!-- Footer End -->

    <!-- Back to Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

    <?php include('components/scripts.php'); ?>
</body>

</html>