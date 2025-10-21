<?php
// Database connection settings
include('db_connect.php');

// Function to check if a table exists
function tableExists($conn, $table)
{
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    return $result->num_rows > 0;
}

// Array of table creation queries
$tables = [
  
    'categories' => "
        CREATE TABLE `categories` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(50) NOT NULL,
            `description` text DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    'blog_posts' => "
        CREATE TABLE `blog_posts` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `content` text NOT NULL,
            `author_id` int(11) NOT NULL,
            `category_id` int(11) NOT NULL,
            `image_path` varchar(255) DEFAULT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `author_id` (`author_id`),
            KEY `category_id` (`category_id`),
            CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `login` (`id`),
            CONSTRAINT `blog_posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",
    'comments' => "
        CREATE TABLE `comments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `post_id` int(11) NOT NULL,
            `name` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `content` text NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`),
            KEY `post_id` (`post_id`),
            CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `blog_posts` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    "     
   
];

// Create tables in the correct order to satisfy foreign key constraints
$creationOrder = [
   'categories', 'blog_posts', 'comments'
];

foreach ($creationOrder as $tableName) {
    if (!tableExists($conn, $tableName)) {
        if ($conn->query($tables[$tableName]) === TRUE) {
            // Table created successfully
        } else {
            error_log("Error creating table $tableName: " . $conn->error);
        }
    }
}


?>