<?php include('components/admin_logic.php');

// Handle AJAX request for initiating actions
if (isset($_POST['initiate'])) {
    $promote_id = $_POST['promote_id'];
    $promote_name = $_POST['promote_name'];
    $promote_class = $_POST['promote_class'];
    $promote_arm = $_POST['promote_arm'];
    $promote_term = $_POST['promote_term'];
    $promote_session = $_POST['promote_session'];
    $action = $_POST['action'];

    $comment = ($action === 'promote') ? 'Promoted' : (($action === 'trial') ? 'Promoted on Trial' : 'To Repeat');

    try {
        // Check if the student already exists in the promote table
        $check_sql = "SELECT id FROM promote WHERE id = ? AND term = ? AND csession = ?";
        $check_stmt = $conn->prepare($check_sql);
        if ($check_stmt) {
            $check_stmt->bind_param("sss", $promote_id, $promote_term, $promote_session);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                // Update the comment if the student already exists
                $update_sql = "UPDATE promote SET comment = ? WHERE id = ? AND term = ? AND csession = ?";
                $update_stmt = $conn->prepare($update_sql);
                if ($update_stmt) {
                    $update_stmt->bind_param("ssss", $comment, $promote_id, $promote_term, $promote_session);
                    $update_stmt->execute();
                    $update_stmt->close();
                } else {
                    throw new Exception("Error preparing update statement: " . $conn->error);
                }
            } else {
                // Insert a new row if the student doesn't exist
                $insert_sql = "INSERT INTO promote (id, name, comment, class, arm, term, csession) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                if ($insert_stmt) {
                    $insert_stmt->bind_param("sssssss", $promote_id, $promote_name, $comment, $promote_class, $promote_arm, $promote_term, $promote_session);
                    $insert_stmt->execute();
                    $insert_stmt->close();
                } else {
                    throw new Exception("Error preparing insert statement: " . $conn->error);
                }
            }
            $check_stmt->close();


            // Step 1: Update status for the highest class
            $stmt = $conn->prepare("UPDATE students SET status = ? WHERE class = ?");
            $status = 1;
            $class = 'SSS 3';
            $stmt->bind_param("is", $status, $class);
            $stmt->execute();
            $stmt->close();

            // Promote student to next class if action is promote or trial
            $promotionMapping = [
                'JSS 1' => 'JSS 2',
                'JSS 2' => 'JSS 3',
                'JSS 3' => 'SSS 1',
                'SSS 1' => 'SSS 2',
                'SSS 2' => 'SSS 3'
            ];
            $new_class = $promote_class;
            if ($action === 'promote' || $action === 'trial') {
                foreach ($promotionMapping as $fromClass => $toClass) {
                    if ($promote_class === $fromClass) {
                        $stmt = $conn->prepare("UPDATE students SET class = ? WHERE class = ? AND id = ?");
                        $stmt->bind_param("sss", $toClass, $fromClass, $promote_id);
                        $stmt->execute();
                        $stmt->close();
                        $new_class = $toClass;
                        break;
                    }
                }
            }

            // Send JSON response with updated student data
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Student processed successfully!',
                'student' => [
                    'id' => $promote_id,
                    'name' => $promote_name,
                    'class' => $new_class,
                    'arm' => $promote_arm,
                    'comment' => $comment
                ]
            ]);
        } else {
            throw new Exception("Error preparing check statement: " . $conn->error);
        }
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
    exit();
}

// Initialize arrays for classes, arms, term, and session
$classes = [];
$arms = [];
$term = [];
$session = [];

try {
    // Fetch classes
    $class_result = $conn->query("SELECT class FROM class");
    if ($class_result) {
        while ($row = $class_result->fetch_assoc()) {
            $classes[] = $row['class'];
        }
    } else {
        throw new Exception("Error fetching classes: " . $conn->error);
    }

    // Fetch arms
    $arm_result = $conn->query("SELECT arm FROM arm");
    if ($arm_result) {
        while ($row = $arm_result->fetch_assoc()) {
            $arms[] = $row['arm'];
        }
    } else {
        throw new Exception("Error fetching arms: " . $conn->error);
    }

    // Fetch term
    $term_result = $conn->query("SELECT cterm FROM currentterm WHERE id = 1");
    if ($term_result) {
        while ($row = $term_result->fetch_assoc()) {
            $term[] = $row['cterm'];
        }
    } else {
        throw new Exception("Error fetching term: " . $conn->error);
    }

    // Fetch session
    $session_result = $conn->query("SELECT csession FROM currentsession WHERE id = 1");
    if ($session_result) {
        while ($row = $session_result->fetch_assoc()) {
            $session[] = $row['csession'];
        }
    } else {
        throw new Exception("Error fetching session: " . $conn->error);
    }
} catch (Exception $e) {
    die("Database query failed: " . $e->getMessage());
}

// Initialize variables for search results
$students = [];
$selected_class = '';
$selected_arm = '';

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['initiate'])) {
    $selected_class = $_POST['class'] ?? '';
    $selected_arm = $_POST['arm'] ?? '';
    $selected_term = $_POST['cterm'] ?? '';
    $selected_session = $_POST['csession'] ?? '';

    if ($selected_class && $selected_arm) {
        try {
            $stmt = $conn->prepare("SELECT id, name, class, arm, subject, average FROM mastersheet WHERE class = ? AND arm = ? AND term = ? AND csession = ?");
            if ($stmt) {
                $stmt->bind_param("ssss", $selected_class, $selected_arm, $selected_term, $selected_session);
                $stmt->execute();
                $stmt->bind_result($id, $name, $class, $arm, $subject, $average);
                $student_data = [];
                while ($stmt->fetch()) {
                    $subject_score = htmlspecialchars($subject, ENT_QUOTES) . ' - ' . htmlspecialchars($average, ENT_QUOTES);
                    if (!isset($student_data[$id])) {
                        $student_data[$id] = [
                            'id' => $id,
                            'name' => $name,
                            'class' => $class,
                            'arm' => $arm,
                            'subjects' => [],
                        ];
                    }
                    $student_data[$id]['subjects'][] = $subject_score;
                }
                $stmt->close();
                $students = array_values($student_data);
            } else {
                throw new Exception("Error preparing statement: " . $conn->error);
            }
        } catch (Exception $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>

<body>
    <div class="wrapper">
        <?php include('adminnav.php'); ?>
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
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4 d-none d-lg-block">
                        <div>
                            <h3 class="fw-bold mb-3">Promote Students</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item active">Home</li>
                                <li class="breadcrumb-item active">Settings</li>
                                <li class="breadcrumb-item active">Promote Students</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-round">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">Filter</div>
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="text-start mt-3" id="notification" style="display: none;"></div>
                                    <div class="mb-4 mt-2">
                                        <form method="post" id="filterForm">
                                            <select name="class" id="class" class="form-control form-select mb-3" required>
                                                <option value="" disabled selected>Select Class</option>
                                                <?php foreach ($classes as $class): ?>
                                                    <option value="<?= htmlspecialchars($class, ENT_QUOTES) ?>" <?= $selected_class === $class ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($class, ENT_QUOTES) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <select name="arm" id="arm" class="form-control form-select mb-3" required>
                                                <option value="" disabled selected>Select Arm</option>
                                                <?php foreach ($arms as $arm): ?>
                                                    <option value="<?= htmlspecialchars($arm, ENT_QUOTES) ?>" <?= $selected_arm === $arm ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($arm, ENT_QUOTES) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <input type="hidden" name="cterm" value="<?= htmlspecialchars($term[0], ENT_QUOTES) ?>">
                                            <input type="hidden" name="csession" value="<?= htmlspecialchars($session[0], ENT_QUOTES) ?>">
                                            <div class="text-start mt-3">
                                                <button type="submit" class="btn btn-success">
                                                    <span class="btn-label">
                                                        <i class="fa fa-filter"></i>
                                                    </span>
                                                    Filter
                                                </button>
                                            </div>
                                        </form>
                                        <p></p>
                                        <div class="table-responsive">
                                            <?php if (!empty($students)): ?>
                                                <table id="studentTable" class="display table table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Name</th>
                                                            <th>Subject & Score</th>
                                                            <th>Class</th>
                                                            <th>Arm</th>
                                                            <th>Comment</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($students as $student): ?>
                                                            <tr data-student-id="<?= htmlspecialchars($student['id'], ENT_QUOTES) ?>">
                                                                <td><?= htmlspecialchars($student['id'], ENT_QUOTES) ?></td>
                                                                <td><?= htmlspecialchars($student['name'], ENT_QUOTES) ?></td>
                                                                <td>
                                                                    <?php
                                                                    $subject_string = implode("<br>", $student['subjects']);
                                                                    echo $subject_string;
                                                                    ?>
                                                                </td>
                                                                <td class="class"><?= htmlspecialchars($student['class'], ENT_QUOTES) ?></td>
                                                                <td class="arm"><?= htmlspecialchars($student['arm'], ENT_QUOTES) ?></td>
                                                                <td class="comment">
                                                                    <?php
                                                                    $student_id = htmlspecialchars($student['id'], ENT_QUOTES);
                                                                    $sql = "SELECT comment FROM promote WHERE id = ? AND term = ? AND csession = ?";
                                                                    $stmt = $conn->prepare($sql);
                                                                    $stmt->bind_param("sss", $student_id, $term[0], $session[0]);
                                                                    $stmt->execute();
                                                                    $comment_result = $stmt->get_result();
                                                                    if ($comment_result && $comment_result->num_rows > 0) {
                                                                        $comment_data = $comment_result->fetch_assoc();
                                                                        echo htmlspecialchars($comment_data['comment'], ENT_QUOTES);
                                                                    } else {
                                                                        echo "N/A";
                                                                    }
                                                                    $stmt->close();
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <form class="actionForm" method="POST">
                                                                        <input type="hidden" name="promote_id" value="<?= htmlspecialchars($student['id'], ENT_QUOTES) ?>">
                                                                        <input type="hidden" name="promote_name" value="<?= htmlspecialchars($student['name'], ENT_QUOTES) ?>">
                                                                        <input type="hidden" name="promote_class" value="<?= htmlspecialchars($student['class'], ENT_QUOTES) ?>">
                                                                        <input type="hidden" name="promote_arm" value="<?= htmlspecialchars($student['arm'], ENT_QUOTES) ?>">
                                                                        <input type="hidden" name="promote_term" value="<?= htmlspecialchars($term[0], ENT_QUOTES) ?>">
                                                                        <input type="hidden" name="promote_session" value="<?= htmlspecialchars($session[0], ENT_QUOTES) ?>">
                                                                        <select name="action" class="form-control form-select mb-3" required>
                                                                            <option value="" disabled selected>Select Action</option>
                                                                            <option value="promote">Promote</option>
                                                                            <option value="trial">Promote on Trial</option>
                                                                            <option value="repeat">Repeat</option>
                                                                        </select>
                                                                        <button type="submit" name="initiate" class="btn btn-primary initiateBtn">Initiate</button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <p class="no-records">No records found.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include('footer.php'); ?>
        </div>
        <?php include('cust-color.php'); ?>
    </div>
    <?php include('scripts.php'); ?>

    <script>
        // Handle form submission for individual initiate buttons
        document.querySelectorAll('.actionForm').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Prevent page reload
                const formData = new FormData(this);
                formData.append('initiate', 'true');

                // Store current filter values
                const filterForm = document.getElementById('filterForm');
                const filterFormData = new FormData(filterForm);

                // First, process the initiate action
                fetch('', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        const notificationDiv = document.getElementById('notification');
                        notificationDiv.style.display = 'block';
                        if (data.success) {
                            notificationDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                            // After successful initiate, refresh the table with current filter
                            fetch('', {
                                    method: 'POST',
                                    body: filterFormData
                                })
                                .then(response => response.text())
                                .then(html => {
                                    // Parse the response HTML and extract the table or no-records
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    const newTable = doc.querySelector('#studentTable');
                                    const noRecords = doc.querySelector('.no-records');
                                    const tableContainer = document.querySelector('.table-responsive');

                                    if (tableContainer) {
                                        if (newTable) {
                                            tableContainer.innerHTML = newTable.outerHTML;
                                            // Re-attach event listeners to new forms
                                            document.querySelectorAll('.actionForm').forEach(newForm => {
                                                newForm.addEventListener('submit', function(e) {
                                                    e.preventDefault();
                                                    const newFormData = new FormData(this);
                                                    newFormData.append('initiate', 'true');

                                                    fetch('', {
                                                            method: 'POST',
                                                            body: newFormData
                                                        })
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            const notificationDiv = document.getElementById('notification');
                                                            notificationDiv.style.display = 'block';
                                                            if (data.success) {
                                                                notificationDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                                                                // Refresh table again
                                                                fetch('', {
                                                                        method: 'POST',
                                                                        body: filterFormData
                                                                    })
                                                                    .then(response => response.text())
                                                                    .then(html => {
                                                                        const parser = new DOMParser();
                                                                        const doc = parser.parseFromString(html, 'text/html');
                                                                        const newTable = doc.querySelector('#studentTable');
                                                                        const noRecords = doc.querySelector('.no-records');
                                                                        const tableContainer = document.querySelector('.table-responsive');

                                                                        if (tableContainer) {
                                                                            if (newTable) {
                                                                                tableContainer.innerHTML = newTable.outerHTML;
                                                                                // Re-attach event listeners again
                                                                                document.querySelectorAll('.actionForm').forEach(form => {
                                                                                    form.addEventListener('submit', arguments.callee);
                                                                                });
                                                                            } else if (noRecords) {
                                                                                tableContainer.innerHTML = '<p class="no-records">No records found.</p>';
                                                                            }
                                                                        }
                                                                    })
                                                                    .catch(error => {
                                                                        console.error('Error refreshing table:', error);
                                                                        notificationDiv.innerHTML = `<div class="alert alert-danger">Error refreshing table: ${error.message}</div>`;
                                                                        setTimeout(() => {
                                                                            notificationDiv.style.display = 'none';
                                                                        }, 3000);
                                                                    });
                                                            } else {
                                                                notificationDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                                                            }
                                                            setTimeout(() => {
                                                                notificationDiv.style.display = 'none';
                                                            }, 3000);
                                                        })
                                                        .catch(error => {
                                                            console.error('Error:', error);
                                                            const notificationDiv = document.getElementById('notification');
                                                            notificationDiv.style.display = 'block';
                                                            notificationDiv.innerHTML = `<div class="alert alert-danger">An error occurred: ${error.message}</div>`;
                                                            setTimeout(() => {
                                                                notificationDiv.style.display = 'none';
                                                            }, 3000);
                                                        });
                                                });
                                            });
                                        } else if (noRecords) {
                                            tableContainer.innerHTML = '<p class="no-records">No records found.</p>';
                                        }
                                    }
                                })
                                .catch(error => {
                                    console.error('Error refreshing table:', error);
                                    notificationDiv.innerHTML = `<div class="alert alert-danger">Error refreshing table: ${error.message}</div>`;
                                    setTimeout(() => {
                                        notificationDiv.style.display = 'none';
                                    }, 3000);
                                });
                        } else {
                            notificationDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                            setTimeout(() => {
                                notificationDiv.style.display = 'none';
                            }, 3000);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        const notificationDiv = document.getElementById('notification');
                        notificationDiv.style.display = 'block';
                        notificationDiv.innerHTML = `<div class="alert alert-danger">An error occurred: ${error.message}</div>`;
                        setTimeout(() => {
                            notificationDiv.style.display = 'none';
                        }, 3000);
                    });
            });
        });

        // Handle filter form submission with AJAX
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent page reload
            const formData = new FormData(this);

            fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(html => {
                    // Parse the response HTML and extract the table or no-records
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.querySelector('#studentTable');
                    const noRecords = doc.querySelector('.no-records');
                    const tableContainer = document.querySelector('.table-responsive');

                    if (tableContainer) {
                        if (newTable) {
                            tableContainer.innerHTML = newTable.outerHTML;
                            // Re-attach event listeners to new forms
                            document.querySelectorAll('.actionForm').forEach(form => {
                                form.addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    const formData = new FormData(this);
                                    formData.append('initiate', 'true');

                                    const filterFormData = new FormData(document.getElementById('filterForm'));

                                    fetch('', {
                                            method: 'POST',
                                            body: formData
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            const notificationDiv = document.getElementById('notification');
                                            notificationDiv.style.display = 'block';
                                            if (data.success) {
                                                notificationDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                                                // Refresh table
                                                fetch('', {
                                                        method: 'POST',
                                                        body: filterFormData
                                                    })
                                                    .then(response => response.text())
                                                    .then(html => {
                                                        const parser = new DOMParser();
                                                        const doc = parser.parseFromString(html, 'text/html');
                                                        const newTable = doc.querySelector('#studentTable');
                                                        const noRecords = doc.querySelector('.no-records');
                                                        const tableContainer = document.querySelector('.table-responsive');

                                                        if (tableContainer) {
                                                            if (newTable) {
                                                                tableContainer.innerHTML = newTable.outerHTML;
                                                                // Re-attach event listeners
                                                                document.querySelectorAll('.actionForm').forEach(form => {
                                                                    form.addEventListener('submit', arguments.callee);
                                                                });
                                                            } else if (noRecords) {
                                                                tableContainer.innerHTML = '<p class="no-records">No records found.</p>';
                                                            }
                                                        }
                                                    })
                                                    .catch(error => {
                                                        console.error('Error refreshing table:', error);
                                                        notificationDiv.innerHTML = `<div class="alert alert-danger">Error refreshing table: ${error.message}</div>`;
                                                        setTimeout(() => {
                                                            notificationDiv.style.display = 'none';
                                                        }, 3000);
                                                    });
                                            } else {
                                                notificationDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                                            }
                                            setTimeout(() => {
                                                notificationDiv.style.display = 'none';
                                            }, 3000);
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            const notificationDiv = document.getElementById('notification');
                                            notificationDiv.style.display = 'block';
                                            notificationDiv.innerHTML = `<div class="alert alert-danger">An error occurred: ${error.message}</div>`;
                                            setTimeout(() => {
                                                notificationDiv.style.display = 'none';
                                            }, 3000);
                                        });
                                });
                            });
                        } else if (noRecords) {
                            tableContainer.innerHTML = '<p class="no-records">No records found.</p>';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const notificationDiv = document.getElementById('notification');
                    notificationDiv.style.display = 'block';
                    notificationDiv.innerHTML = `<div class="alert alert-danger">An error occurred: ${error.message}</div>`;
                    setTimeout(() => {
                        notificationDiv.style.display = 'none';
                    }, 3000);
                });
        });
    </script>
</body>

</html>
<?php
// Close database connection
$conn->close();
?>