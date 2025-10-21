<?php
session_start();
include('db_connect.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Blog</title>
</head>
<?php
include('components/head.php');

// Pagination settings
$posts_per_page = 8;
$page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $posts_per_page;

// Get filters
$search   = isset($_GET['search'])   ? trim($_GET['search'])   : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// Build WHERE clause
$where = [];
$params = [];
if ($search !== '') {
    $where[]  = "title LIKE ?";
    $params[] = "%{$search}%";
}
if ($category !== '') {
    $where[]  = "category_id = ?";
    $params[] = $category;
}
$where_clause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// MAIN POSTS QUERY
$sql = "SELECT * FROM blog_posts {$where_clause} ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);

// Combine params + pagination
$params_all = $params;
$params_all[] = $posts_per_page;
$params_all[] = $offset;

// Build types string: 's' for each filter + 'ii' for limit offset
$types = str_repeat('s', count($params)) . 'ii';
array_unshift($params_all, $types);

// Convert to references
$refs = array();
foreach ($params_all as $key => $value) {
    $refs[$key] = &$params_all[$key];
}

// Bind and execute
call_user_func_array([$stmt, 'bind_param'], $refs);
$stmt->execute();
$result = $stmt->get_result();

// Close statement (result is buffered)
$stmt->close();

// COUNT TOTAL POSTS FOR PAGINATION
$sql_count = "SELECT COUNT(*) AS total FROM blog_posts {$where_clause}";
$stmt2 = $conn->prepare($sql_count);
if ($params) {
    $types_count = str_repeat('s', count($params));
    $params_count = $params;
    array_unshift($params_count, $types_count);
    $refs2 = array();
    foreach ($params_count as $key => $value) {
        $refs2[$key] = &$params_count[$key];
    }
    call_user_func_array([$stmt2, 'bind_param'], $refs2);
}
$stmt2->execute();
$total = $stmt2->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total / $posts_per_page);
$stmt2->close();
?>

<body class="blog-page">

    <?php include('components/header.php'); ?>

    <main class="main">

        <!-- Page Title -->
        <div class="page-title dark-background position-relative" style="background-image: url(assets/img/pg-header.jpg);">
            <div class="container position-relative">
                <h1>Blog</h1>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="index.php">Home</a></li>
                        <li class="current">Blog</li>
                    </ol>
                    <br>
                    <!-- Search Form -->
                    <div class="mb-5 wow slideInUp" data-wow-delay="0.1s">
                        <form method="GET" action="blog.php" class="mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control p-3" placeholder="Search Posts..." name="search" value="<?php echo htmlspecialchars($search); ?>">
                                <button class="btn btn-primary px-4" type="submit"><i class="bi bi-search"></i></button>
                            </div>
                        </form>
                    </div>
                </nav>
            </div>
        </div><!-- End Page Title -->

        <!-- Blog Posts Section -->
        <section id="blog-posts" class="blog-posts section">
            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row gy-4">
                    <?php if ($result->num_rows): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                            // Fetch category name
                            $cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
                            $cat_stmt->bind_param('i', $row['category_id']);
                            $cat_stmt->execute();
                            $cat = $cat_stmt->get_result()->fetch_assoc();
                            $category_name = $cat['name'] ?? 'Uncategorized';
                            $cat_stmt->close();
                            ?>
                            <div class="col-lg-3" data-aos="fade-up" data-aos-delay="100">
                                <article class="h-100">
                                    <div class="value-card h-100">
                                        <div class="post-img">
                                            <?php if ($row['image_path']): ?>
                                                <img src="assets/img/blog/<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="img-fluid rounded-image" loading="lazy" style="width:100%; height: 250px; object-fit: cover;">
                                            <?php else: ?>
                                                <img src="img/default-image.jpg" alt="Default Image" class="img-fluid rounded-image" loading="lazy" style="width:100%; height: 250px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div class="category-top-left" style="width:100%;">
                                                <p class="post-category category-top-left">
                                                    <a href="blog.php?category=<?php echo $row['category_id']; ?>" style="color: white;">
                                                        <?php echo htmlspecialchars($category_name); ?>
                                                    </a>
                                                </p>
                                            </div>
                                            <div class="post-content">
                                                <h2 class="title post-title-padding">
                                                    <a href="post.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo htmlspecialchars($row['title']); ?></a>
                                                </h2>
                                                <div class="post-meta">
                                                    <time datetime="<?php echo date('Y-m-d', strtotime($row['created_at'])); ?>">
                                                        <i class="bi bi-calendar"></i> <?php echo date('d M, Y', strtotime($row['created_at'])); ?>
                                                    </time>
                                                    <!-- <span class="px-2">•</span>
                                            <span><i class="bi bi-chat-dots"></i> No Comments</span> -->
                                                </div>
                                                <?php
                                                $content = $row['content'];
                                                $words = explode(' ', $content);
                                                $excerpt = implode(' ', array_slice($words, 0, 40));
                                                echo '<p>' . nl2br($excerpt) . (count($words) > 40 ? '...' : '') . '</p>';
                                                ?>
                                                <a class="text-uppercase" href="post.php?id=<?php echo $row['id']; ?>" target="_blank">Read More <i class="bi bi-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div><!-- End post list item -->
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-lg-12">
                            <p>No posts found.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <!-- End Blog Posts Section -->

        <!-- Pagination Section -->
        <!-- <section id="pagination" class="pagination section">
      <div class="container">
        <nav class="d-flex justify-content-center" aria-label="Page navigation">
          <ul class="pagination">
            <li class="page-item disabled">
              <a class="page-link" href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </section> -->
        <!-- End Pagination Section -->
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="blog.php?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="blog.php?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="blog.php?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
        </div> <!-- /.col-lg-8 -->

    </main>

    <?php include('components/footer.php'); ?>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <?php include('components/scripts.php'); ?>

</body>

</html>