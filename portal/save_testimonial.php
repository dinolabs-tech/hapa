<?php
session_start();
header('Content-Type: application/json');

include('db_connection.php');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect POST fields
    $student_id = $_POST['student_id']; // student_id is INT in DB, no htmlspecialchars for int
    $session = htmlspecialchars($_POST['session'], ENT_QUOTES, 'UTF-8');

    // Testimonial fields, aligning with DB schema
    $principal_comment = htmlspecialchars($_POST['principal_comment'], ENT_QUOTES, 'UTF-8');
    $subjects_offered = htmlspecialchars($_POST['subjects_offered'], ENT_QUOTES, 'UTF-8');
    $academic_ability = htmlspecialchars($_POST['academic_ability'], ENT_QUOTES, 'UTF-8');
    $prizes_won = htmlspecialchars($_POST['prizes_won'], ENT_QUOTES, 'UTF-8');
    $character_assessment = htmlspecialchars($_POST['character_assessment'], ENT_QUOTES, 'UTF-8');
    $leadership_position = htmlspecialchars($_POST['leadership_position'], ENT_QUOTES, 'UTF-8'); // Renamed to match DB
    $co_curricular = htmlspecialchars($_POST['co_curricular'], ENT_QUOTES, 'UTF-8'); // Renamed to match DB
    // $general_remarks = htmlspecialchars($_POST['general_remarks'], ENT_QUOTES, 'UTF-8');

    // Required fields check (removed 'term' as it's not in DB)
    if (
        empty($student_id) ||
        empty($session) ||
        empty($principal_comment)
    ) {
        $response['message'] = 'Missing required fields.';
        echo json_encode($response);
        exit();
    }

    // Insert or Update testimonial
    $sql = "
        INSERT INTO testimonial
        (
            student_id, session, principal_comment, subjects_offered,
            academic_ability, prizes_won, character_assessment, leadership_position,
            co_curricular
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            session = VALUES(session),
            principal_comment = VALUES(principal_comment),
            subjects_offered = VALUES(subjects_offered),
            academic_ability = VALUES(academic_ability),
            prizes_won = VALUES(prizes_won),
            character_assessment = VALUES(character_assessment),
            leadership_position = VALUES(leadership_position),
            co_curricular = VALUES(co_curricular)
    ";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            "sssssssss", // All 9 parameters are strings
            $student_id,
            $session,
            $principal_comment,
            $subjects_offered,
            $academic_ability,
            $prizes_won,
            $character_assessment,
            $leadership_position,
            $co_curricular
        );

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['student_id'] = $student_id;
        } else {
            $response['message'] = 'Database error: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = 'Failed to prepare statement: ' . $conn->error;
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
