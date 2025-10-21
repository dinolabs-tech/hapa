<?php
// Database connection settings
include('db_connection.php');

session_start();
$student_id = isset($_SESSION['user_id']) ? trim($_SESSION['user_id']) : '';

// Array of random introduction paragraphs
$intros = array(
"I've reviewed your academic performance.",
    "Here's your performance summary.",
    "Analysis complete for your results.",
    "Reviewing your academic data yields the following.",
    "Great news! Here are your performance details.",
    "All set! Here's how you performed.",
    "I've compiled your academic report.",
    "Here are your academic results.",
    "Your performance overview is ready.",
    "I've just finished analyzing your results.",
    "Here's a quick look at your scores.",
    "I've calculated your performance metrics.",
    "Here's how you did this term.",
    "A summary of your academic performance is here.",
    "Take a look at your results.",
    "Here's the breakdown of your scores.",
    "I've pulled up your results.",
    "Academic insights are ready for you.",
    "Explore your academic performance.",
    "Here's the detailed academic report.",
    "Your academic record is now available.",
    "Here's your assessment summary.",
    "Your results have been reviewed.",
    "Academic analysis is complete for you.",
    "Here's the evaluation of your performance.",
    "Insights into your performance are here.",
    "I've summarized your academic data.",
    "A performance review is ready.",
    "I've assessed your scores.",
    "Here's the overview of your grades.",
    "I've processed your academic data.",
    "Summary of your grades is ready.",
    "I've inspected your academic performance.",
    "Here are the highlights of your scores.",
    "I've prepared your performance summary.",
    "Here is the concise report.",
    "I've reviewed and summarized your results.",
    "Here is the quick summary.",
    "I've completed the grading overview.",
    "Here's what you scored.",
    "I’ve compiled a brief report.",
    "Results have been gathered.",
    "I've finalized your performance data.",
    "Here are the findings.",
    "I've curated your academic report.",
    "Here's the performance insight.",
    "I've done a performance check.",
    "Here is your report.",
    "I've wrapped up the score analysis.",
    "Here is the assessment breakdown.",
    "I've collected your academic statistics.",
    "Here are your academic stats.",
    "I've aggregated your performance metrics.",
    "Here is the grading summary.",
    "I've extracted your results.",
    "Here is the detailed breakdown.",
    "I've generated your performance report.",
    "Here is your term performance.",
    "Review of your grades is complete.",
    "Analytics on your performance are here.",
    "Here are the performance analytics.",
    "I've assessed your academic standing.",
    "An academic summary is ready.",
    "I've prepped your score evaluation.",
    "Here is the academic feedback.",
    "I've compiled the grade overview.",
    "Here is the evaluation summary.",
    "I've summarized the scoring details.",
    "Here is the performance outline.",
    "I've delivered the academic recap.",
    "Here is the concise performance summary.",
    "I've outlined your academic results.",
    "Here is the quick performance overview.",
    "I've reported on your academic standing.",
    "Here is the sectional performance.",
    "I've posted your academic data.",
    "Here is the performance snapshot.",
    "I've checked your grades.",
    "Here is the grade summary.",
    "I've done a quick grade check.",
    "Here is the subject-wise report.",
    "I've fetched your latest grades.",
);

// Array of random suggestion paragraphs for low scores
$suggestions = array(
    'Deepen your knowledge of %s. Stay curious and keep improving!',
    '%s deserve more attention. Your dedication is inspiring—keep it up!',
    '%s could benefit from additional practice. Your efforts today shape tomorrow’s success!',
    'Deepen your knowledge of %s. Keep pushing forward—you’re making progress every day!',
    'Refine your approach to %s. Learning is a journey, and you’re on the right track!',
    'Zero in on %s. Every practice session brings you closer!',
    '%s could use some extra focus. Step by step, you’re making it happen!',
    'Refine your approach to %s. Great things take time—you’re doing great!',
    'Put more energy into %s. Keep setting goals and smashing them!',
    '%s could use some extra focus. Every practice session brings you closer!',
    'Sharpen your understanding of %s. Your efforts today shape tomorrow’s success!',
    'Put more energy into %s. You have the power to overcome these challenges!',
    '%s are an excellent area to grow in. Every effort counts—keep going!',
    '%s deserve more attention. Learning is a journey, and you’re on the right track!',
    'It’s time to strengthen your skills in %s. Focus and dedication will get you there!',
    'Sharpen your understanding of %s. Stay motivated and keep striving!',
    'Deepen your knowledge of %s. Your commitment will lead to success!',
    '%s need some extra practice. Persistence wins the race—don’t stop now!',
    '%s are an excellent area to grow in. Step by step, you’re making it happen!',
    '%s deserve more attention. Every effort counts—keep going!',
    'Refine your approach to %s. Trust the process and keep working hard!',
    'Sharpen your understanding of %s. You’re building your skills one step at a time!',
    '%s could benefit from additional practice. Stay motivated and keep striving!',
    'Put more energy into %s. Keep pushing forward—you’re making progress every day!',
    'It’s time to strengthen your skills in %s. Your dedication is inspiring—keep it up!',
    'It’s time to strengthen your skills in %s. Great things take time—you’re doing great!',
    'Sharpen your understanding of %s. Persistence wins the race—don’t stop now!',
    '%s could use some extra focus. Learning is a journey, and you’re on the right track!',
    '%s deserve more attention. Step by step, you’re making it happen!',
    'Put more energy into %s. Small steps every day lead to big improvements!',
    'Zero in on %s. Your hard work will pay off—stay focused!',
    '%s could benefit from additional practice. You have the power to overcome these challenges!',
    'Deepen your knowledge of %s. Stay motivated and keep striving!',
    'Refine your approach to %s. Your commitment will lead to success!',
    '%s need some extra practice. Every effort counts—keep going!',
    'It’s time to strengthen your skills in %s. You’re building your skills one step at a time!',
    '%s are an excellent area to grow in. Learning is a journey, and you’re on the right track!',
    'Zero in on %s. Your efforts today shape tomorrow’s success!',
    '%s could use some extra focus. Your dedication is inspiring—keep it up!',
    '%s deserve more attention. Believe in your progress—you’re getting stronger!',
    'Sharpen your understanding of %s. Trust the process and keep working hard!',
    '%s could benefit from additional practice. Keep setting goals and smashing them!',
    'Zero in on %s. Step by step, you’re making it happen!',
    'It’s time to strengthen your skills in %s. Stay curious and keep improving!',
    '%s could use some extra focus. Small steps every day lead to big improvements!',
    'Deepen your knowledge of %s. You’re growing in your abilities—well done!',
    'Put more energy into %s. Your efforts today shape tomorrow’s success!',
    'Refine your approach to %s. Keep pushing forward—you’re making progress every day!',
    'Deepen your knowledge of %s. Your hard work will pay off—stay focused!',
    '%s need some extra practice. Your commitment will lead to success!',
    'Sharpen your understanding of %s. Small steps every day lead to big improvements!',
    '%s are an excellent area to grow in. Keep setting goals and smashing them!',
    '%s deserve more attention. You have the power to overcome these challenges!',
    'Put more energy into %s. Step by step, you’re making it happen!',
    '%s could benefit from additional practice. You’re building your skills one step at a time!',
    'Zero in on %s. Stay motivated and keep striving!',
    '%s could use some extra focus. Great things take time—you’re doing great!',
    'Sharpen your understanding of %s. Keep pushing forward—you’re making progress every day!',
    'It’s time to strengthen your skills in %s. Your dedication is inspiring—keep it up!',
    'Deepen your knowledge of %s. Your commitment will lead to success!',
    '%s deserve more attention. Persistence wins the race—don’t stop now!',
    '%s need some extra practice. Stay curious and keep improving!',
    'Put more energy into %s. Your hard work will pay off—stay focused!',
    'Refine your approach to %s. Learning is a journey, and you’re on the right track!',
    'Deepen your knowledge of %s. Small steps every day lead to big improvements!',
    '%s could use some extra focus. Trust the process and keep working hard!',
    'Sharpen your understanding of %s. Your efforts today shape tomorrow’s success!',
    '%s present a great chance to level up. Your dedication is inspiring—keep it up!',
    'Refine your approach to %s. Great things take time—you’re doing great!',
    'It’s time to strengthen your skills in %s. Stay curious and keep improving!',
    'Deepen your knowledge of %s. You have the power to overcome these challenges!',
    '%s deserve more attention. Keep setting goals and smashing them!',
    '%s could benefit from additional practice. Stay motivated and keep striving!',
    'Zero in on %s. Your dedication is inspiring—keep it up!',
    'Sharpen your understanding of %s. You’re growing in your abilities—well done!',
    '%s need some extra practice. Your commitment will lead to success!',
    'Put more energy into %s. Believe in your progress—you’re getting stronger!',
    '%s could use some extra focus. Your efforts today shape tomorrow’s success!',
    'It’s time to strengthen your skills in %s. Persistence wins the race—don’t stop now!',
    'Deepen your knowledge of %s. Keep pushing forward—you’re making progress every day!',
    '%s might be tough now, but they won’t be forever. I believe in you—always have, always will.',
    '%s present a good opportunity for growth. Stay consistent and you\'ll see amazing results.',
    'You might want to revisit %s. Every challenge is a chance to grow—embrace it!',
    'You might want to revisit %s. With focus and determination, you can master anything.',
    '%s present a good opportunity for growth. Your potential is limitless—tap into it.',
    '%s need some extra work. I believe in you—always have, always will.',
    'You might want to revisit %s. It’s not about being perfect. It’s about getting better.',
    '%s can definitely improve with some effort. Success is built on effort. You’re closer than you think.',
    'You might want to revisit %s. The key is persistence. Don’t give up!',
    '%s can definitely improve with some effort. Practice, patience, and positivity—that\'s your formula.',
    'You might want to revisit %s. You\'ve got what it takes—just keep pushing forward!',
    'Refocusing on %s could take you to the next level. You\'ve got what it takes—just keep pushing forward!',
    '%s could use a little more attention. Don’t be afraid to ask for help—growth comes from learning.',
    'Let’s put some extra energy into %s. With focus and determination, you can master anything.',
    'Refocusing on %s could take you to the next level. The key is persistence. Don’t give up!',
    'Refocusing on %s could take you to the next level. Practice, patience, and positivity—that\'s your formula.',
    '%s could use a little more attention. Stay consistent and you\'ll see amazing results.',
    '%s could use a little more attention. Every challenge is a chance to grow—embrace it!',
    '%s could use a little more attention. Your potential is limitless—tap into it.',
    '%s could use a little more attention. You’re already on the right path. Keep it up!',
    '%s might be tough now, but they won’t be forever. The key is persistence. Don’t give up!',
    '%s might be tough now, but they won’t be forever. The more you try, the better you become.',
    '%s might be tough now, but they won’t be forever. You’re stronger and smarter than you know.',
    '%s might be tough now, but they won’t be forever. Small steps every day lead to big improvements.',
    '%s need some extra work. With focus and determination, you can master anything.',
    '%s need some extra work. Success is built on effort. You’re closer than you think.',
    '%s need some extra work. You’re already on the right path. Keep it up!',
    '%s need some extra work. You’ve got what it takes—just keep pushing forward!',
    '%s need some extra work. The more you try, the better you become.',
    'Let’s put some extra energy into %s. Every challenge is a chance to grow—embrace it!',
    'Let’s put some extra energy into %s. Practice, patience, and positivity—that\'s your formula.',
    'Let’s put some extra energy into %s. You’re stronger and smarter than you know.',
    'Let’s put some extra energy into %s. You’re learning. That’s already progress.',
    'It might be time to focus more on %s. Success is built on effort. You’re closer than you think.',
    'It might be time to focus more on %s. The more you try, the better you become.',
    'It might be time to focus more on %s. You’re capable of greatness—just keep going!',
    'It might be time to focus more on %s. Don’t be afraid to ask for help—growth comes from learning.',
    'It might be time to focus more on %s. Use this moment to rise higher.',
    '%s are areas to shine brighter in. Believe in your ability to improve—because I do!',
    '%s are areas to shine brighter in. Stay consistent and you\'ll see amazing results.',
    '%s are areas to shine brighter in. Practice, patience, and positivity—that\'s your formula.',
    '%s are areas to shine brighter in. You’re stronger and smarter than you know.',
    '%s are areas to shine brighter in. I believe in you—always have, always will.',
    '%s are areas to shine brighter in. Small steps every day lead to big improvements.',
    '%s are areas to shine brighter in. The key is persistence. Don’t give up!',
    'Refocusing on %s could take you to the next level. You’re capable of greatness—just keep going!',
    'Refocusing on %s could take you to the next level. You’re learning. That’s already progress.',
    'Refocusing on %s could take you to the next level. Every challenge is a chance to grow—embrace it!',
    'Refocusing on %s could take you to the next level. You’re already on the right path. Keep it up!',
    'Refocusing on %s could take you to the next level. Your potential is limitless—tap into it.',
    'You might want to revisit %s. Believe in your ability to improve—because I do!',
    'You might want to revisit %s. Stay consistent and you\'ll see amazing results.',
    'You might want to revisit %s. You’ve got what it takes—just keep pushing forward!',
    'You might want to revisit %s. You’re learning. That’s already progress.',
    'You might want to revisit %s. Challenge accepted? I know you\'ll win it.',
    'You might want to revisit %s. Use this moment to rise higher.',
    '%s can definitely improve with some effort. I believe in you—always have, always will.',
    '%s can definitely improve with some effort. You’re capable of greatness—just keep going!',
    '%s can definitely improve with some effort. The more you try, the better you become.',
    '%s can definitely improve with some effort. Don’t be afraid to ask for help—growth comes from learning.',
    '%s can definitely improve with some effort. You’re already on the right path. Keep it up!',
    '%s can definitely improve with some effort. Small steps every day lead to big improvements.',
    '%s could use a little more attention. Success is built on effort. You’re closer than you think.',
    '%s could use a little more attention. Stay consistent and you\'ll see amazing results.',
    '%s could use a little more attention. You’re stronger and smarter than you know.',
    '%s could use a little more attention. You’ve got what it takes—just keep pushing forward!',
    '%s could use a little more attention. Use this moment to rise higher.',
    '%s present a good opportunity for growth. Every challenge is a chance to grow—embrace it!',
    '%s present a good opportunity for growth. Challenge accepted? I know you\'ll win it.',
    '%s present a good opportunity for growth. You’re learning. That’s already progress.',
    '%s present a good opportunity for growth. You’re already on the right path. Keep it up!',
    '%s present a good opportunity for growth. You’ve got what it takes—just keep pushing forward!',
);

// Array of random commendation paragraphs for high scores
$commendations = array(
    'Excellent work in %s! Keep shining!',
    'Outstanding performance in %s—well done!',
    'Great job on %s! Your hard work paid off!',
    'Impressive score in %s. Keep up the momentum!',
    'You excelled in %s! Fantastic effort!',
    'Remarkable achievement in %s. Proud of you!',
    'Superb performance in %s! Stay on this path!',
    'Bravo on your success in %s! Continue aiming high!',
    'Splendid work in %s! Your dedication shows!',
    'Fantastic results in %s—you nailed it!',
    'Exceptional effort in %s! You’re a star!',
    'Wonderful achievement in %s! Keep it going!',
    'Terrific job in %s! Your effort paid off!',
    'Magnificent performance in %s—kudos to you!',
    'Remarkable mastery of %s! Keep progressing!',
    'Phenomenal results in %s! You rock!',
    'Sensational work in %s! Very impressive!',
    'Top-tier performance in %s! Congratulations!',
    'Stellar job in %s! Your hard work shines!',
    'First-class effort in %s! True excellence!',
    'Splendid mastery of %s—way to go!',
    'Marvelous work in %s! You should be proud!',
    'Peerless achievement in %s! Outstanding!',
    'Elite performance in %s! Keep excelling!',
    'Remarkable focus on %s—well executed!',
    'Brilliant results in %s! Hats off to you!',
    'Incredible job in %s! You make it look easy!',
    'A+ effort in %s! Truly commendable!',
    'Distinguished performance in %s! Well done!',
    'Superior work in %s! Keep raising the bar!',
    'Championship-level performance in %s!',
    'Unbeatable results in %s! Keep it up!',
    'Admirable diligence in %s—impressive!',
    'Clever execution of %s! Excellent!',
    'High-caliber performance in %s!',
    'Elevated work in %s—fantastic!',
    'Triumphant performance in %s!',
    'Gold-standard effort in %s!',
    'Prime performance in %s—great job!',
    'Astonishing results in %s! Well done!',
    'Top performance in %s! Very proud!',
    'Commanding work in %s—excellent!',
    'Majestic achievement in %s!',
    'Top-notch execution of %s—bravo!',
    'Acutely impressive work in %s!',
    'Peak performance in %s! Keep climbing!',
    'Superlative job in %s! Congratulations!',
    'Immaculate results in %s! Nice work!',
    'Exemplary performance in %s!',
    'Distinguished work in %s—cheers!',
    'Supreme effort in %s! Well earned!',
    'First-rate achievement in %s!',
    'Premier performance in %s—magnificent!',
    'High-impact work in %s! Outstanding!',
    'Remarkably strong in %s!',
    'Consummate execution of %s!',
    'Unmatched performance in %s!',
    'Extraordinary work in %s! Bravo!',
    'Bravura performance in %s—impressive!',
    'Masterful work in %s!',
    'Exceptional command of %s!',
    'Commanding results in %s!',
    'Potent performance in %s!',
    'Vivid excellence in %s!',
    'Rousing achievement in %s!',
    'Dazzling work in %s!',
    'Eminent performance in %s!',
    'Iconic results in %s!',
    'Heroic effort in %s—kudos!',
    'Triumphant results in %s!',
    'Majestic work in %s!',
    'Radiant performance in %s!',
    'Vigorous achievement in %s!',
    'Paramount work in %s!',
    'Groundbreaking performance in %s!',
    'Apex achievement in %s!',
    'Resounding success in %s!',
    'Champion-level work in %s!',
    'Perfect execution of %s!',
    'Pin-point accuracy in %s!',
    'Flawless performance in %s!',
    'Polished work in %s!',
    'Sublime achievement in %s!',
    'Peerless success in %s!',
    'Refined performance in %s!',
    'Distinctive achievement in %s!',
    'Unrivaled results in %s!',
    'Ultimate performance in %s!',
    'Supercharged work in %s!',
    'Record-breaking results in %s!',
    'Legendary performance in %s!',
    'Prodigious work in %s!',
    'Unparalleled achievement in %s!'
);


// Basic chatbot logic (retrieve data from the 'mastersheet' table and analyze student performance)
if (!empty($student_id)) {
    // Get the current term dynamically
    $term_sql = "SELECT cterm FROM currentterm LIMIT 1";
    $term_result = $conn->query($term_sql);

    if (!$term_result) {
        die("Error fetching current term: " . $conn->error);
    }

    $current_term = '';
    if ($term_result->num_rows > 0) {
        $row = $term_result->fetch_assoc();
        $current_term = $conn->real_escape_string($row['cterm']);
    } else {
        die("No current term defined in currentterm table.");
    }

    $sql = "
        SELECT subject, ca1, ca2, exam, average, name
        FROM mastersheet
        WHERE term = '$current_term'
          AND id   = '" . $conn->real_escape_string($student_id) . "'
    ";
    $result = $conn->query($sql);

    if (!$result) {
        $response = "Error: " . mysqli_error($conn);
    } elseif ($result->num_rows > 0) {
        // Fetch all subject scores
        $subject_scores = array();
        $total_scores = 0;
        $num_subjects = 0;
        $name = '';

        while ($row = $result->fetch_assoc()) {
            $subject = $row['subject'];
            $ca1     = (float) $row['ca1'];
            $ca2     = (float) $row['ca2'];
            $exam    = (float) $row['exam'];
            $total   = (float) $row['average'];
            $name    = htmlspecialchars($row['name']);

            // Calculate total score if it's not already available
            if (empty($total)) {
                $total = $ca1 + $ca2 + $exam;
            }

            $subject_scores[$subject] = $total;
            $total_scores += $total;
            $num_subjects++;
        }

        // Calculate overall average score
        $overall_average = ($num_subjects > 0) ? round(($total_scores / $num_subjects), 2) : 0;

        // Identify subjects with scores below 60 and above 80
        $low_scores  = array();
        $high_scores = array();
        foreach ($subject_scores as $subject => $score) {
            if ($score < 50) {
                $low_scores[$subject] = $score;
            } elseif ($score > 80) {
                $high_scores[$subject] = $score;
            }
        }

        // Pick a random introduction and inject name and ID
        $intro = $intros[array_rand($intros)];
        $response = $intro . " Your overall average score is " . $overall_average . ". ";

        // Add improvement suggestions for low scores
        if (!empty($low_scores)) {
            $subjects_string = implode(", ", array_keys($low_scores));
            $random_template = $suggestions[array_rand($suggestions)];
            $response .= sprintf($random_template, $subjects_string) . " ";
        } else {
            $response .= "Great job, " . $name . "! I didn't identify any specific areas for improvement. ";
        }

        // Add commendations for high scores
        if (!empty($high_scores)) {
            $high_subjects = implode(", ", array_keys($high_scores));
            $commend_template = $commendations[array_rand($commendations)];
            $response .= sprintf($commend_template, $high_subjects);
        }
    } else {
        $response = "I'm sorry, I couldn't find any data for student ID: " . htmlspecialchars($student_id) . " in the mastersheet table. Please double-check the ID and try again.";
    }
} else {
    $response = "To analyze a student's performance, please provide their Student ID.";
}

echo $response;

$conn->close();
?>
