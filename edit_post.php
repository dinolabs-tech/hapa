<?php
session_start();
if (!isset($_SESSION["staffname"])) {
    header("Location: portal/login.php");
    exit();
}

include("db_connect.php");

$post_id = $_GET["id"];

$sql = "SELECT * FROM blog_posts WHERE id = $post_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Post not found";
    exit();
}

$post = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">

<?php include('components/head.php'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
    <script>
    tinymce.init({
      selector: '#content',
      menubar: false,
      toolbar: 'undo redo | formatselect | bold italic underline superscript subscript | alignleft aligncenter alignright | bullist numlist outdent indent | table',
      plugins: 'lists',
      branding: false
    });
  </script>

<body>
      <!-- Navbar Start -->
    <?php include('components/header.php'); ?>
    <!-- Navbar End -->

    <main class="main">

        <!-- Page Title -->
        <div class="page-title dark-background" style="background-image: url(assets/img/education/showcase-1.webp);">
            <div class="container position-relative">
                <h1>Edit Post</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Edit Post</li>
                    </ol>
                </nav>
            </div>
        </div><!-- End Page Title -->


    <!-- Quote Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">

            <div class="col-lg-12">
                <div class="bg-primary rounded h-100 d-flex align-items-center p-5 wow zoomIn" data-wow-delay="0.9s">
                    <form action="update_post.php" method="post" enctype="multipart/form-data" class="w-100">
                        <input type="hidden" name="id" value="<?php echo $post_id; ?>">
                        <div class="row g-3">

                            <div class="col-12">
                                <label for="title" class="text-white">Title:</label>
                                <input type="text" class="form-control bg-light border-0" id="title" name="title"
                                    value="<?php echo htmlspecialchars($post["title"]); ?>"
                                    placeholder="Enter Post Title" style="height: 55px;" required>
                            </div>

                            <div class="col-12">
                                <label for="content" class="text-white">Content:</label>
                                <textarea class="form-control bg-light border-0" id="content" name="content"
                                    placeholder="Enter Post Content" style="height: 150px;"
                                    required><?php echo htmlspecialchars($post["content"]); ?></textarea>
                            </div>

                            <div class="col-12">
                                <label for="image" class="text-white">Image:</label>
                                <input type="file" class="form-control bg-light border-0" id="image" name="image"
                                    style="height: 55px;">
                                <?php if (!empty($post["image_path"])): ?>
                                    <div class="mt-2">
                                        <img src="assets/img/blog/<?php echo htmlspecialchars($post["image_path"]); ?>"
                                            alt="Blog Image" style="max-width: 100px;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-12">
                                <label for="category" class="text-white">Category:</label>
                                <select class="form-control form-select bg-light border-0" id="category" name="category"
                                    style="height: 55px;" required>
                                    <option value="">Select Category</option>
                                    <?php
                                    $sql_categories = "SELECT id, name FROM categories";
                                    $result_categories = $conn->query($sql_categories);
                                    if ($result_categories->num_rows > 0) {
                                        while ($row_category = $result_categories->fetch_assoc()) {
                                            $selected = ($post["category_id"] == $row_category["id"]) ? "selected" : "";
                                            echo "<option value='" . $row_category["id"] . "' $selected>" . htmlspecialchars($row_category["name"]) . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No categories available</option>";
                                    }
                                    ?>
                                </select>



                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-dark w-100 py-3">Update Post</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Quote End -->





    <!-- Footer Start -->
    <?php include('components/footer.php'); ?>
    <!-- Footer End -->

      <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

    <?php include('components/scripts.php'); ?>
</body>

</html>
