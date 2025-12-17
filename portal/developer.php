<?php
include('components/superuser_logic.php');

// Check if the user is logged in and is a super user
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Superuser') {
    header("Location: login.php");
    exit();
}

// Handle log fetching
if (isset($_GET['action']) && $_GET['action'] === 'fetch_log' && isset($_GET['log_type'])) {
    $log_type = $_GET['log_type'];
    $log_file = '';

    if ($log_type === 'error_log') {
        $log_file = 'error_log.txt'; // Assuming error_log.txt is in the parent directory
    } elseif ($log_type === 'backup_log') {
        $log_file = 'backup.log'; // Assuming backup.log is in the backend directory
    } elseif ($log_type === 'error_log_no_ext') {
        $log_file = 'error_log'; // Assuming error_log (no extension) is in the current directory
    } elseif ($log_type === 'deploy_log') {
        $log_file = '../deploy.log'; // Assuming deploy.log is in the parent directory
    }

    if (!empty($log_file) && file_exists($log_file)) {
        echo file_get_contents($log_file);
    } else {
        echo "Log file not found or specified.";
    }
    exit(); // Exit after serving the log content
}

// Handle backup download
if (isset($_GET['action']) && $_GET['action'] === 'download_backup') {
    $file = 'backup_hapacoll_portal.sql'; // The name of the SQL backup file

    if (file_exists($file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    } else {
        echo "Backup file not found.";
    }
    exit(); // Exit after serving the file
}

// Handle clearing logs
if (isset($_GET['action']) && $_GET['action'] === 'clear_log' && isset($_GET['log_type'])) {
    $log_type = $_GET['log_type'];
    $log_file = '';

    if ($log_type === 'error_log') {
        $log_file = 'error_log.txt';
    } elseif ($log_type === 'backup_log') {
        $log_file = 'backup.log';
    } elseif ($log_type === 'error_log_no_ext') {
        $log_file = 'error_log';
    } elseif ($log_type === 'deploy_log') {
        $log_file = '../deploy.log';
    }

    if (!empty($log_file) && file_exists($log_file)) {
        if (file_put_contents($log_file, '') !== false) {
            echo "Log file cleared successfully.";
        } else {
            echo "Error clearing log file.";
        }
    } else {
        echo "Log file not found or specified.";
    }
    exit(); // Exit after clearing the log
}

// Handle dropping all tables
if (isset($_GET['action']) && $_GET['action'] === 'drop_all_tables') {
    include('db_connection.php'); // Assuming this file establishes $conn

    if ($conn) {
        try {
            // Disable foreign key checks
            $conn->query("SET FOREIGN_KEY_CHECKS = 0;");

            // Generate DROP TABLE statements for all tables
            $tables_query = "SELECT GROUP_CONCAT(CONCAT('`', table_name, '`')) FROM information_schema.tables WHERE table_schema = DATABASE();";
            $result = $conn->query($tables_query);
            $row = $result->fetch_row();
            $tables = $row[0];

            if ($tables) {
                // Prepare and execute the DROP statement
                $drop_statement = "DROP TABLE $tables;";
                $conn->query($drop_statement);
                echo "All tables dropped successfully.";
            } else {
                echo "No tables found to drop.";
            }

            // Re-enable foreign key checks
            $conn->query("SET FOREIGN_KEY_CHECKS = 1;");
        } catch (Exception $e) {
            echo "Error dropping tables: " . $e->getMessage();
        } finally {
            $conn->close();
        }
    } else {
        echo "Database connection failed.";
    }
    exit();
}

// Handle executing custom SQL commands
if (isset($_POST['action']) && $_POST['action'] === 'execute_sql' && isset($_POST['sql_command'])) {
    include('db_connection.php');

    if ($conn) {
        $sql_command = $_POST['sql_command'];
        $output = '';

        try {
            // Execute multi-query if multiple statements are present, otherwise single query
            if ($conn->multi_query($sql_command)) {
                $output .= "SQL executed successfully.\n";
                do {
                    if ($result = $conn->store_result()) {
                        if ($result->num_rows > 0) {
                            $output .= "Results:\n";
                            // Get column names
                            $fields = $result->fetch_fields();
                            $column_names = [];
                            foreach ($fields as $field) {
                                $column_names[] = $field->name;
                            }
                            $output .= implode("\t| ", $column_names) . "\n";
                            $output .= str_repeat("-\t", count($column_names)) . "\n";

                            // Fetch rows
                            while ($row = $result->fetch_assoc()) {
                                $row_values = [];
                                foreach ($column_names as $col_name) {
                                    $row_values[] = $row[$col_name];
                                }
                                $output .= implode("\t| ", $row_values) . "\n";
                            }
                        } else {
                            $output .= "No rows returned for this statement.\n";
                        }
                        $result->free();
                    }
                    if ($conn->more_results()) {
                        $output .= "\n--- Next Query Results ---\n\n";
                    }
                } while ($conn->next_result());
            } else {
                $output .= "Error executing SQL: " . $conn->error . "\n";
            }
        } catch (Exception $e) {
            $output .= "Error: " . $e->getMessage() . "\n";
        } finally {
            $conn->close();
        }
    } else {
        $output .= "Database connection failed.";
    }
    echo $output;
    exit();
}

// Handle executing custom git commands
if (isset($_POST['action']) && $_POST['action'] === 'execute_git' && isset($_POST['git_command'])) {
    $git_command = trim($_POST['git_command']);
    $output = '';

    if (!empty($git_command)) {
        // Change to project root directory (parent of portal)
        $project_root = dirname(__DIR__);
        chdir($project_root);

        // Execute the git command
        $full_command = 'git ' . $git_command . ' 2>&1'; // Redirect stderr to stdout
        exec($full_command, $output_lines, $return_var);

        if ($return_var === 0) {
            $output .= "Git command executed successfully.\n\n";
        } else {
            $output .= "Git command failed with exit code $return_var.\n\n";
        }

        $output .= "Output:\n" . implode("\n", $output_lines);
    } else {
        $output .= "No git command provided.";
    }

    echo $output;
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>
<!-- Includes the head section of the HTML document (meta tags, title, CSS links) -->

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include('adminnav.php'); ?>
        <!-- Includes the admin specific navigation sidebar -->
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <?php include('logo_header.php'); ?>
                    <!-- Includes the logo and header content -->
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <?php include('navbar.php'); ?>
                <!-- Includes the main navigation bar -->
                <!-- End Navbar -->
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block">
                        <div>
                            <h3 class="fw-bold mb-3">Developer</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Home</li>
                                <li class="breadcrumb-item active">Developer</li>
                            </ol>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h2>Developer Tools</h2>
                            <p>This page provides access to developer-specific tools and logs.</p>

                            <div class="card mt-4">
                                <div class="card-header">
                                    View Logs and Download Backup
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 d-flex flex-wrap gap-1">
                                            <button class="btn btn-primary" id="viewErrorLog">View error_log.txt</button>
                                            <button class="btn btn-warning" id="clearErrorLog">Clear error_log.txt</button>
                                            <button class="btn btn-info" id="viewBackupLog">View backup.log</button>
                                            <button class="btn btn-danger" id="clearBackupLog">Clear backup.log</button>
                                            <button class="btn btn-primary" id="viewErrorLogNoExt">View error_log</button>
                                            <button class="btn btn-warning" id="clearErrorLogNoExt">Clear error_log</button>
                                            <button class="btn btn-info" id="viewDeployLog">View deploy.log</button>
                                            <button class="btn btn-danger" id="clearDeployLog">Clear deploy.log</button>
                                            <a href="database_schema.php" class="btn btn-primary">Run DB Schema</a>
                                            <a href="developer.php?action=download_backup" class="btn btn-success">
                                                Download SQL Backup
                                            </a>
                                            <button class="btn btn-danger" id="dropAllTablesButton">Drop All Tables</button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div id="logContent" class="mt-3"
                                            style="white-space: pre-wrap; background-color: #f8f9fa; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: scroll;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    Execute Custom SQL Commands
                                </div>
                                <div class="card-body">
                                    <p class="text-danger"><strong>WARNING:</strong> Executing SQL commands directly can lead to data loss or corruption if not used carefully. Proceed with caution.</p>
                                    <div class="form-group">
                                        <label for="sqlCommandInput">SQL Command:</label>
                                        <textarea class="form-control" id="sqlCommandInput" rows="15" placeholder="Enter SQL command here..."></textarea>
                                    </div>
                                    <button class="btn btn-primary mt-2" id="executeSqlCommand">Execute SQL</button>
                                    <div id="sqlResultContent" class="mt-3"
                                        style="white-space: pre-wrap; background-color: #f8f9fa; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: scroll;">
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header">
                                    Execute Custom Git Commands
                                </div>
                                <div class="card-body">
                                    <p class="text-danger"><strong>WARNING:</strong> Executing git commands directly can modify your repository, potentially leading to data loss or corruption if not used carefully. Proceed with caution.</p>
                                    <p>git reset --hard HEAD <br>
                                        git pull</p>
                                    <div class="form-group">
                                        <label for="gitCommandInput">Git Command:</label>
                                        <textarea class="form-control" id="gitCommandInput" rows="5" placeholder="Enter git command here (without 'git' prefix)..."></textarea>
                                    </div>
                                    <button class="btn btn-primary mt-2" id="executeGitCommand">Execute Git Command</button>
                                    <div id="gitResultContent" class="mt-3"
                                        style="white-space: pre-wrap; background-color: #b5b7b8ff; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: scroll;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include('footer.php'); ?>
                <!-- Includes the footer section of the page -->
            </div>

            <!-- Custom template | don't include it in your project! -->
            <?php include('cust-color.php'); ?>
            <!-- Includes custom color settings or scripts -->
            <!-- End Custom template -->
        </div>
        <?php include('scripts.php'); ?>
        <!-- Includes general JavaScript scripts for the page -->

        <script>
            $(document).ready(function() {
                $('#viewErrorLog').click(function() {
                    $.ajax({
                        url: 'developer.php', // Call developer.php itself
                        type: 'GET',
                        data: {
                            action: 'fetch_log',
                            log_type: 'error_log'
                        },
                        success: function(response) {
                            $('#logContent').text(response);
                        },
                        error: function() {
                            $('#logContent').text('Error fetching error_log.txt');
                        }
                    });
                });

                $('#viewBackupLog').click(function() {
                    $.ajax({
                        url: 'developer.php', // Call developer.php itself
                        type: 'GET',
                        data: {
                            action: 'fetch_log',
                            log_type: 'backup_log'
                        },
                        success: function(response) {
                            $('#logContent').text(response);
                        },
                        error: function() {
                            $('#logContent').text('Error fetching backup.log');
                        }
                    });
                });

                $('#clearErrorLog').click(function() {
                    if (confirm(
                            'Are you sure you want to clear error_log.txt? This action cannot be undone.')) {
                        $.ajax({
                            url: 'developer.php',
                            type: 'GET',
                            data: {
                                action: 'clear_log',
                                log_type: 'error_log'
                            },
                            success: function(response) {
                                alert(response);
                                $('#logContent').text(''); // Clear displayed content
                            },
                            error: function() {
                                alert('Error clearing error_log.txt');
                            }
                        });
                    }
                });

                $('#clearBackupLog').click(function() {
                    if (confirm('Are you sure you want to clear backup.log? This action cannot be undone.')) {
                        $.ajax({
                            url: 'developer.php',
                            type: 'GET',
                            data: {
                                action: 'clear_log',
                                log_type: 'backup_log'
                            },
                            success: function(response) {
                                alert(response);
                                $('#logContent').text(''); // Clear displayed content
                            },
                            error: function() {
                                alert('Error clearing backup.log');
                            }
                        });
                    }
                });

                $('#viewErrorLogNoExt').click(function() {
                    $.ajax({
                        url: 'developer.php',
                        type: 'GET',
                        data: {
                            action: 'fetch_log',
                            log_type: 'error_log_no_ext'
                        },
                        success: function(response) {
                            $('#logContent').text(response);
                        },
                        error: function() {
                            $('#logContent').text('Error fetching error_log');
                        }
                    });
                });

                $('#clearErrorLogNoExt').click(function() {
                    if (confirm('Are you sure you want to clear error_log? This action cannot be undone.')) {
                        $.ajax({
                            url: 'developer.php',
                            type: 'GET',
                            data: {
                                action: 'clear_log',
                                log_type: 'error_log_no_ext'
                            },
                            success: function(response) {
                                alert(response);
                                $('#logContent').text(''); // Clear displayed content
                            },
                            error: function() {
                                alert('Error clearing error_log');
                            }
                        });
                    }
                });

                $('#viewDeployLog').click(function() {
                    $.ajax({
                        url: 'developer.php',
                        type: 'GET',
                        data: {
                            action: 'fetch_log',
                            log_type: 'deploy_log'
                        },
                        success: function(response) {
                            $('#logContent').text(response);
                        },
                        error: function() {
                            $('#logContent').text('Error fetching deploy.log');
                        }
                    });
                });

                $('#clearDeployLog').click(function() {
                    if (confirm('Are you sure you want to clear deploy.log? This action cannot be undone.')) {
                        $.ajax({
                            url: 'developer.php',
                            type: 'GET',
                            data: {
                                action: 'clear_log',
                                log_type: 'deploy_log'
                            },
                            success: function(response) {
                                alert(response);
                                $('#logContent').text(''); // Clear displayed content
                            },
                            error: function() {
                                alert('Error clearing deploy.log');
                            }
                        });
                    }
                });

                $('#dropAllTablesButton').click(function() {
                    if (confirm('WARNING: Are you absolutely sure you want to drop ALL tables? This action is irreversible and will result in complete data loss.')) {
                        $.ajax({
                            url: 'developer.php',
                            type: 'GET',
                            data: {
                                action: 'drop_all_tables'
                            },
                            success: function(response) {
                                alert(response);
                                $('#logContent').text(response);
                            },
                            error: function() {
                                alert('Error dropping tables.');
                            }
                        });
                    }
                });

                $('#executeSqlCommand').click(function() {
                    const sqlCommand = $('#sqlCommandInput').val();
                    if (sqlCommand.trim() === '') {
                        alert('Please enter an SQL command to execute.');
                        return;
                    }

                    if (confirm('WARNING: Are you sure you want to execute this SQL command? This action can be destructive and irreversible.')) {
                        $.ajax({
                            url: 'developer.php',
                            type: 'POST',
                            data: {
                                action: 'execute_sql',
                                sql_command: sqlCommand
                            },
                            success: function(response) {
                                $('#sqlResultContent').text(response);
                            },
                            error: function() {
                                $('#sqlResultContent').text('Error executing SQL command.');
                            }
                        });
                    }
                });

                $('#executeGitCommand').click(function() {
                    const gitCommand = $('#gitCommandInput').val();
                    if (gitCommand.trim() === '') {
                        alert('Please enter a git command to execute.');
                        return;
                    }

                    if (confirm('WARNING: Are you sure you want to execute this git command? This action can be destructive and irreversible.')) {
                        $.ajax({
                            url: 'developer.php',
                            type: 'POST',
                            data: {
                                action: 'execute_git',
                                git_command: gitCommand
                            },
                            success: function(response) {
                                $('#gitResultContent').text(response);
                            },
                            error: function() {
                                $('#gitResultContent').text('Error executing git command.');
                            }
                        });
                    }
                });
            });
        </script>
</body>

</html>