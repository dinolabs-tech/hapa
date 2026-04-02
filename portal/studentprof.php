<?php
include('components/admin_logic.php');

// MODIFY STUDENTS =============================
// Handle form submission for updating student record and image
if (isset($_POST['update'])) {
    // Collect student information from form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob_from_form = $_POST['dob'];
    $dob = !empty($dob_from_form) ? date('d/m/Y', strtotime($dob_from_form)) : '';
    $placeob = $_POST['placeob'];
    $address = $_POST['address'];
    $religion = $_POST['religion'];
    $state = $_POST['state'];
    $lga = $_POST['lga'];
    $class = $_POST['class'];
    $arm = $_POST['arm'];
    $session_val = $_POST['session'];
    $term = $_POST['term'];
    $schoolname = $_POST['schoolname'];
    $schooladdress = $_POST['schooladdress'];
    $hobbies = $_POST['hobbies'];
    $lastclass = isset($_POST['lastclass']) ? $_POST['lastclass'] : ''; // Line 25
    $sickle = $_POST['sickle'];
    $challenge = $_POST['challenge'];
    $emergency = $_POST['emergency'];
    $familydoc = $_POST['familydoc'];
    $docaddress = $_POST['docaddress'];
    $docmobile = $_POST['docmobile'];
    $polio = $_POST['polio'];
    $tuberculosis = $_POST['tuberculosis'];
    $measles = $_POST['measles'];
    $tetanus = $_POST['tetanus'];
    $whooping = $_POST['whooping'];
    $gname = $_POST['gname'];
    $mobile = $_POST['mobile'];
    $goccupation = $_POST['goccupation'];
    $gaddress = $_POST['gaddress'];
    $grelationship = $_POST['grelationship'];
    $hostel = $_POST['hostel'];
    $bloodtype = isset($_POST['bloodtype']) ? $_POST['bloodtype'] : ''; // Line 43
    $bloodgroup = isset($_POST['bloodgroup']) ? $_POST['bloodgroup'] : ''; // Line 44
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $password = $_POST['password'];

    // Update student record
    $sql = "UPDATE students SET 
                name='$name', 
                gender='$gender', 
                dob='$dob',
                placeob='$placeob', 
                address='$address', 
                religion='$religion', 
                state='$state', 
                lga='$lga', 
                class='$class', 
                arm='$arm', 
                session='$session_val', 
                term='$term', 
                schoolname='$schoolname', 
                schooladdress='$schooladdress', 
                hobbies='$hobbies', 
                lastclass='$lastclass', 
                sickle='$sickle', 
                challenge='$challenge', 
                emergency='$emergency', 
                familydoc='$familydoc', 
                docaddress='$docaddress', 
                docmobile='$docmobile', 
                polio='$polio', 
                tuberculosis='$tuberculosis', 
                measles='$measles', 
                tetanus='$tetanus', 
                whooping='$whooping', 
                gname='$gname', 
                mobile='$mobile', 
                goccupation='$goccupation', 
                gaddress='$gaddress', 
                grelationship='$grelationship', 
                hostel='$hostel', 
                bloodtype='$bloodtype', 
                bloodgroup='$bloodgroup', 
                height='$height', 
                weight='$weight', 
                password='$password'
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        // Process student image if a new file is provided
        if (isset($_FILES["formFile"]) && $_FILES["formFile"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $targetDir = "studentimg/"; // Upload folder
            $studentID = trim($id);

            if (empty($studentID)) {
                $message = "Student ID is required for image upload.";
            } else {
                // Sanitize Student ID for filename
                $sanitizedID = str_replace("/", "_", $studentID);
                $fileExtension = strtolower(pathinfo($_FILES["formFile"]["name"], PATHINFO_EXTENSION));
                $fileSize = $_FILES["formFile"]["size"];
                $allowedTypes = ["jpg", "jpeg"];
                $targetFile = $targetDir . $sanitizedID . "." . $fileExtension; // Final file path

                // Validate file size (500KB limit) and file type
                if ($fileSize > 500 * 1024) {
                    $message = "File size must be less than 500KB.";
                } elseif (!in_array($fileExtension, $allowedTypes)) {
                    $message = "Only JPG/JPEG files are allowed.";
                } else {
                    // Create directory if it does not exist
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($_FILES["formFile"]["tmp_name"], $targetFile)) {
                        $message = "Image uploaded successfully as " . htmlspecialchars($sanitizedID) . "." . $fileExtension;
                    } else {
                        $message = "Error uploading the image.";
                    }
                }
            }
        }
        // Redirect back to refresh the page (you can pass $message via session or GET if needed)
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Handle deletion of a student record
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM students WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

// Search query for student based on ID or name
$searchQuery = "";
if (isset($_POST['search'])) {
    $searchTerm = $_POST['searchTerm'];
    $searchQuery = "WHERE name LIKE '%$searchTerm%' OR id LIKE '%$searchTerm%'";
}

// Fetch student records
$sql = "SELECT * FROM students $searchQuery";
$result = $conn->query($sql);

// Convert result set into an array so it can be looped over safely later
$students = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Fetch student details for editing if an ID is passed in the URL
$studentDetails = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $studentSql = "SELECT * FROM students WHERE id='$id'";
    $studentResult = $conn->query($studentSql);
    if ($studentResult->num_rows > 0) {
        $studentDetails = $studentResult->fetch_assoc();
    }
}

$sql_class = "SELECT DISTINCT class from class";
$classresult = $conn->query($sql_class);
if (($classresult) && ($classresult->num_rows > 0)) {
    $classes = [];
    while ($row = $classresult->fetch_assoc()) {
        $classes[] = $row['class'];
    }
} else {
    $classes = [];
}

$sql_arm = "SELECT DISTINCT arm from arm";
$armresult = $conn->query($sql_arm);
if (($armresult) && ($armresult->num_rows > 0)) {
    $arms = [];
    while ($row = $armresult->fetch_assoc()) {
        $arms[] = $row['arm'];
    }
} else {
    $arms = [];
}

// Close database connection
$nigerian_states = [
    "Abia",
    "Adamawa",
    "Akwa Ibom",
    "Anambra",
    "Bauchi",
    "Bayelsa",
    "Benue",
    "Borno",
    "Cross River",
    "Delta",
    "Ebonyi",
    "Edo",
    "Ekiti",
    "Enugu",
    "FCT - Abuja",
    "Gombe",
    "Imo",
    "Jigawa",
    "Kaduna",
    "Kano",
    "Katsina",
    "Kebbi",
    "Kogi",
    "Kwara",
    "Lagos",
    "Nasarawa",
    "Niger",
    "Ogun",
    "Ondo",
    "Osun",
    "Oyo",
    "Plateau",
    "Rivers",
    "Sokoto",
    "Taraba",
    "Yobe",
    "Zamfara"
];
$conn->close();
?>