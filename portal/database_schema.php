<?php
// Database connection settings
include('db_connection.php');

// Function to check if a table exists
function tableExists($conn, $table)
{
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    return $result->num_rows > 0;
}

// Function to check if a column exists in a table
function columnExists($conn, $table, $column)
{
    $result = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    return $result->num_rows > 0;
}

// Array of table creation queries
$tables = [
    // Table: admin
    "admin" => "
        CREATE TABLE IF NOT EXISTS `admin` (
            `id` varchar(111) NOT NULL,
            `fullname` varchar(111) NOT NULL,
            `subject` varchar(111) NOT NULL,
            `mobile` varchar(111) NOT NULL,
            `email` varchar(111) NOT NULL,
            `pass` varchar(111) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: arm
    "arm" => "
        CREATE TABLE IF NOT EXISTS `arm` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `arm` varchar(222) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: assignments
    "assignments" => "
        CREATE TABLE IF NOT EXISTS `assignments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `subject_name` varchar(111) NOT NULL,
            `class_name` varchar(111) NOT NULL,
            `file_name` varchar(111) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: bills
    "bills" => "
        CREATE TABLE IF NOT EXISTS `bills` (
            `productID` varchar(222) NOT NULL,
            `productname` varchar(222) NOT NULL,
            `qty` varchar(222) NOT NULL,
            `unitprice` varchar(222) NOT NULL,
            `totalamt` varchar(222) NOT NULL,
            `invoiceno` varchar(222) NOT NULL,
            `billdate` varchar(222) NOT NULL,
            `profit` varchar(222) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: bills1
    "bills1" => "
        CREATE TABLE IF NOT EXISTS `bills1` (
            `productID` varchar(222) NOT NULL,
            `productname` varchar(222) NOT NULL,
            `qty` varchar(222) NOT NULL,
            `unitprice` varchar(222) NOT NULL,
            `totalamt` varchar(222) NOT NULL,
            `invoiceno` varchar(222) NOT NULL,
            `billdate` varchar(222) NOT NULL,
            `profit` varchar(222) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: bills2
    "bills2" => "
        CREATE TABLE IF NOT EXISTS `bills2` (
            `productID` varchar(222) NOT NULL,
            `productname` varchar(222) NOT NULL,
            `qty` varchar(222) NOT NULL,
            `unitprice` varchar(222) NOT NULL,
            `totalamt` varchar(222) NOT NULL,
            `invoiceno` varchar(222) NOT NULL,
            `billdate` varchar(222) NOT NULL,
            `profit` varchar(222) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: bills3
    "bills3" => "
        CREATE TABLE IF NOT EXISTS `bills3` (
            `productID` varchar(222) NOT NULL,
            `productname` varchar(222) NOT NULL,
            `qty` varchar(222) NOT NULL,
            `unitprice` varchar(222) NOT NULL,
            `totalamt` varchar(222) NOT NULL,
            `invoiceno` varchar(222) NOT NULL,
            `billdate` varchar(222) NOT NULL,
            `profit` varchar(222) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: bursary
    "bursary" => "
        CREATE TABLE IF NOT EXISTS `bursary` (
            `id` varchar(111) NOT NULL,
            `name` varchar(111) NOT NULL,
            `gender` varchar(111) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `session` varchar(111) NOT NULL,
            `term` varchar(111) NOT NULL,
            `hostel` varchar(111) NOT NULL,
            `fee` varchar(111) NOT NULL,
            `paid` varchar(111) NOT NULL,
            `outstanding` varchar(111) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: calendar
    "calendar" => "
        CREATE TABLE IF NOT EXISTS `calendar` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `date` varchar(222) NOT NULL,
            `title` varchar(222) NOT NULL,
            `description` varchar(1000) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: capacity
    "capacity" => "
        CREATE TABLE IF NOT EXISTS `capacity` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `volume` varchar(111) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: cbt_score
    "cbt_score" => "
        CREATE TABLE IF NOT EXISTS `cbt_score` (
            `id` VARCHAR(30) NOT NULL PRIMARY KEY,
            `login` VARCHAR(100) NOT NULL,
            `subject` VARCHAR(100) NOT NULL,
            `class` VARCHAR(50) NOT NULL,
            `arm` VARCHAR(20) NOT NULL,
            `term` VARCHAR(20) NOT NULL,
            `session` VARCHAR(20) NOT NULL,
            `test_date` VARCHAR(255) NOT NULL,
            `score` VARCHAR(255) NOT NULL,
            UNIQUE KEY `unique_exam` (`login`, `subject`, `class`, `arm`, `term`, `session`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: cbtadmin
    "cbtadmin" => "
        CREATE TABLE IF NOT EXISTS `cbtadmin` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `term` varchar(111) NOT NULL,
            `session` varchar(111) NOT NULL,
            `testdate` varchar(111) NOT NULL,
            `testtime` int(11) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: class
    "class" => "
        CREATE TABLE IF NOT EXISTS `class` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `class` varchar(222) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: classcomments
    "classcomments" => "
      CREATE TABLE IF NOT EXISTS `classcomments` (
    `id` varchar(111) NOT NULL,
    `name` varchar(111) NOT NULL,
    `comment` varchar(111) NOT NULL,
    `schlopen` int(11) NOT NULL,
    `dayspresent` int(11) NOT NULL,
    `daysabsent` int(11) NOT NULL,
    `attentiveness` varchar(111) NOT NULL,
    `neatness` varchar(111) NOT NULL,
    `politeness` varchar(111) NOT NULL,
    `selfcontrol` varchar(111) NOT NULL,
    `punctuality` varchar(111) NOT NULL,
    `relationship` varchar(111) NOT NULL,
    `handwriting` varchar(111) NOT NULL,
    `music` varchar(111) NOT NULL,
    `club` varchar(111) NOT NULL,
    `sport` varchar(222) NOT NULL,
    `class` varchar(111) NOT NULL,
    `arm` varchar(111) NOT NULL,
    `term` varchar(111) NOT NULL,
    `csession` varchar(111) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
",

    // Table: classteacher
    "classteacher" => "
        CREATE TABLE IF NOT EXISTS `classteacher` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `class` varchar(222) NOT NULL,
            `name` varchar(222) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: courses
    "courses" => "
        CREATE TABLE IF NOT EXISTS `courses` (
            `id` int(30) NOT NULL AUTO_INCREMENT,
            `course` varchar(100) NOT NULL,
            `description` text NOT NULL,
            `level` varchar(150) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `hostel` varchar(111) NOT NULL,
            `total_amount` float NOT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: currency
    "currency" => "
        CREATE TABLE IF NOT EXISTS `currency` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `rate` varchar(10) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: currentsession
    "currentsession" => "
        CREATE TABLE IF NOT EXISTS `currentsession` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `csession` varchar(222) NOT NULL,
            `regyr` varchar(222) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: currentterm
    "currentterm" => "
        CREATE TABLE IF NOT EXISTS `currentterm` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `cterm` varchar(11) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: curriculum
    "curriculum" => "
        CREATE TABLE IF NOT EXISTS `curriculum` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `subject_name` varchar(111) NOT NULL,
            `class_name` varchar(111) NOT NULL,
            `file_name` varchar(111) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: fee
    "fee" => "
        CREATE TABLE IF NOT EXISTS `fee` (
            `id` varchar(111) NOT NULL,
            `class` varchar(111) NOT NULL,
            `service` varchar(111) NOT NULL,
            `price` int(11) NOT NULL,
            `hostel` varchar(111) NOT NULL,
            `session` varchar(111) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: fees
    "fees" => "
        CREATE TABLE IF NOT EXISTS `fees` (
            `id` int(30) NOT NULL AUTO_INCREMENT,
            `course_id` int(30) NOT NULL,
            `description` varchar(200) NOT NULL,
            `amount` float NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: firstcum
    "firstcum" => "
        CREATE TABLE IF NOT EXISTS `firstcum` (
            `id` varchar(111) NOT NULL,
            `name` varchar(111) NOT NULL,
            `ca1` varchar(111) NOT NULL,
            `ca2` varchar(111) NOT NULL,
            `exam` varchar(111) NOT NULL,
            `lastcum` varchar(111) NOT NULL,
            `average` varchar(111) NOT NULL,
            `subject` varchar(111) NOT NULL,
            `csession` varchar(111) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `term` varchar(111) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: license
    "license" => "
        CREATE TABLE IF NOT EXISTS `license` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `license` varchar(111) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: login
    "login" => "
        CREATE TABLE IF NOT EXISTS `login` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `staffname` varchar(222) NOT NULL,
            `username` varchar(222) NOT NULL,
            `password` varchar(222) NOT NULL,
            `role` varchar(222) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: mail
    "mail" => "
        CREATE TABLE IF NOT EXISTS `mail` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `subject` varchar(255) NOT NULL,
            `message` text NOT NULL,
            `from_user` varchar(255) NOT NULL,
            `to_user` varchar(255) NOT NULL,
            `status` tinyint(4) NOT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: mastersheet
    "mastersheet" => "
        CREATE TABLE IF NOT EXISTS `mastersheet` (
            `id` varchar(111) NOT NULL,
            `name` varchar(222) NOT NULL,
            `ca1` varchar(11) NOT NULL,
            `ca2` varchar(11) NOT NULL,
            `exam` varchar(11) NOT NULL,
            `lastcum` int(11) NOT NULL,
            `total` varchar(11) NOT NULL,
            `average` varchar(111) NOT NULL,
            `grade` varchar(222) NOT NULL,
            `subject` varchar(222) NOT NULL,
            `csession` varchar(222) NOT NULL,
            `class` varchar(222) NOT NULL,
            `arm` varchar(222) NOT NULL,
            `term` varchar(111) NOT NULL,
            `remark` varchar(111) NOT NULL,
            `position` varchar(222) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ",

    // Table: messages
    "messages" => "
        CREATE TABLE IF NOT EXISTS `messages` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `subject` varchar(111) NOT NULL,
            `message` varchar(111) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: message_list
    "message_list" => "
        CREATE TABLE IF NOT EXISTS `message_list` (
            `id` int(30) NOT NULL AUTO_INCREMENT,
            `conversation_id` int(30) NOT NULL,
            `from_user` int(30) NOT NULL,
            `to_user` int(30) NOT NULL,
            `message` text NOT NULL,
            `status` tinyint(1) NOT NULL DEFAULT 0,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `conversation_id` (`conversation_id`),
            KEY `from_user` (`from_user`),
            KEY `to_user` (`to_user`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: monitoring
    "monitoring" => "
        CREATE TABLE IF NOT EXISTS `monitoring` (
            `id` varchar(111) NOT NULL,
            `name` varchar(111) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `term` varchar(111) NOT NULL,
            `session` varchar(111) NOT NULL,
            `narration` varchar(111) NOT NULL,
            `fee` varchar(111) NOT NULL,
            `amount` varchar(111) NOT NULL,
            `paid` varchar(111) NOT NULL,
            `outstanding` varchar(111) NOT NULL,
            `transdate` varchar(111) NOT NULL,
            `staff` varchar(111) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: mst_question
    "mst_question" => "
        CREATE TABLE IF NOT EXISTS `mst_question` (
            `que_id` int(11) NOT NULL,
            `test_id` int(11) DEFAULT NULL,
            `que_desc` varchar(2000) DEFAULT NULL,
            `ans1` varchar(75) DEFAULT NULL,
            `ans2` varchar(75) DEFAULT NULL,
            `ans3` varchar(75) DEFAULT NULL,
            `ans4` varchar(75) DEFAULT NULL,
            `true_ans` int(11) DEFAULT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: mst_result
    "mst_result" => "
        CREATE TABLE IF NOT EXISTS `mst_result` (
            `login` varchar(20) DEFAULT NULL,
            `subject` varchar(111) DEFAULT NULL,
            `test_date` varchar(111) DEFAULT NULL,
            `score` int(11) DEFAULT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: mst_useranswer
    "mst_useranswer" => "
        CREATE TABLE IF NOT EXISTS `mst_useranswer` (
            `sess_id` varchar(80) DEFAULT NULL,
            `subject` varchar(111) DEFAULT NULL,
            `que_des` varchar(200) DEFAULT NULL,
            `ans1` varchar(50) DEFAULT NULL,
            `ans2` varchar(50) DEFAULT NULL,
            `ans3` varchar(50) DEFAULT NULL,
            `ans4` varchar(50) DEFAULT NULL,
            `true_ans` int(11) DEFAULT NULL,
            `your_ans` int(11) DEFAULT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: nextterm
    "nextterm" => "
        CREATE TABLE IF NOT EXISTS `nextterm` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `term` varchar(255) NOT NULL,
            `session` varchar(255) NOT NULL,
            `Next` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",
    // Table: notices
    "notices" => "
        CREATE TABLE IF NOT EXISTS `notices` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `message` TEXT NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: notes
    "notes" => "
        CREATE TABLE IF NOT EXISTS `notes` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `subject_name` varchar(111) NOT NULL,
            `class_name` varchar(111) NOT NULL,
            `file_name` varchar(111) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: parent
    "parent" => "
        CREATE TABLE IF NOT EXISTS `parent` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(222) NOT NULL,
            `mobile` varchar(222) NOT NULL,
            `email` varchar(222) NOT NULL,
            `student_id` varchar(222) NOT NULL,
            `username` varchar(222) NOT NULL,
            `password` varchar(222) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: payments
    "payments" => "
        CREATE TABLE IF NOT EXISTS `payments` (
            `id` int(30) NOT NULL AUTO_INCREMENT,
            `ef_id` varchar(30) NOT NULL,
            `amount` float NOT NULL,
            `remarks` text NOT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: prebursary
    "prebursary" => "
        CREATE TABLE IF NOT EXISTS `prebursary` (
            `id` varchar(111) NOT NULL,
            `name` varchar(111) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `term` varchar(111) NOT NULL,
            `gender` varchar(111) NOT NULL,
            `session` varchar(111) NOT NULL,
            `date` varchar(111) NOT NULL,
            `depositor` varchar(111) NOT NULL,
            `mobile` varchar(111) NOT NULL,
            `amount` varchar(111) NOT NULL,
            `narration` varchar(111) NOT NULL,
            `status` int(111) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: principalcomments
    "principalcomments" => "
        CREATE TABLE IF NOT EXISTS `principalcomments` (
            `id` varchar(111) NOT NULL,
            `name` varchar(222) NOT NULL,
            `comment` varchar(222) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `term` varchar(111) NOT NULL,
            `csession` varchar(111) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: Promote
    "promote" => "
       CREATE TABLE IF NOT EXISTS `promote` (
            `id` varchar(111) NOT NULL,
            `name` varchar(222) NOT NULL,
            `comment` varchar(222) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `term` varchar(111) NOT NULL,
            `csession` varchar(111) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: product
    "product" => "
        CREATE TABLE IF NOT EXISTS `product` (
            `productid` int(11) NOT NULL AUTO_INCREMENT,
            `productname` varchar(222) NOT NULL,
            `location` varchar(222) NOT NULL,
            `unitprice` varchar(222) NOT NULL,
            `sellprice` int(11) NOT NULL,
            `qty` varchar(222) NOT NULL,
            `total` varchar(222) NOT NULL,
            `description` varchar(222) NOT NULL,
            `reorder_level` varchar(222) NOT NULL,
            `reorder_qty` varchar(222) NOT NULL,
            `profit` varchar(1000) NOT NULL,
            PRIMARY KEY (`productid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: ques
    "ques" => "
        CREATE TABLE IF NOT EXISTS `ques` (
            `id` int(11) NOT NULL,
            `question` varchar(1111) NOT NULL,
            `opt1` varchar(111) NOT NULL,
            `opt2` varchar(111) NOT NULL,
            `opt3` varchar(111) NOT NULL,
            `opt4` varchar(111) NOT NULL,
            `answer` varchar(111) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `session` varchar(111) NOT NULL,
            `term` varchar(111) NOT NULL,
            `subject` varchar(111) NOT NULL,
            `photo` blob NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: question
    "question" => "
        CREATE TABLE IF NOT EXISTS `question` (
            `que_id` int(11) NOT NULL AUTO_INCREMENT,
            `subject` varchar(111) NOT NULL,
            `que_desc` varchar(2000) NOT NULL,
            `ans1` varchar(75) NOT NULL,
            `ans2` varchar(75) NOT NULL,
            `ans3` varchar(75) NOT NULL,
            `ans4` varchar(75) NOT NULL,
            `true_ans` varchar(1) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `term` varchar(111) NOT NULL,
            `session` varchar(111) NOT NULL,
            PRIMARY KEY (`que_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: receipt
    "receipt" => "
        CREATE TABLE IF NOT EXISTS `receipt` (
            `rproduct` varchar(111) NOT NULL,
            `rqty` varchar(111) NOT NULL,
            `rprice` varchar(111) NOT NULL,
            `rtotal` varchar(111) NOT NULL,
            `invoice` varchar(111) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: regno
    "regno" => "
        CREATE TABLE IF NOT EXISTS `regno` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `sindex` varchar(222) NOT NULL,
            `sno` varchar(222) NOT NULL,
            `syear` varchar(222) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: result
    "result" => "
        CREATE TABLE IF NOT EXISTS `result` (
            `id` varchar(111) NOT NULL,
            `name` varchar(111) NOT NULL,
            `subject` varchar(111) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `term` varchar(111) NOT NULL,
            `session` varchar(111) NOT NULL,
            `totalques` int(11) NOT NULL,
            `attemptedques` int(11) NOT NULL,
            `rightanswers` int(11) NOT NULL,
            `marksobtained` int(11) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: secondcum
    "secondcum" => "
        CREATE TABLE IF NOT EXISTS `secondcum` (
            `id` varchar(111) NOT NULL,
            `name` varchar(111) NOT NULL,
            `ca1` varchar(111) NOT NULL,
            `ca2` varchar(111) NOT NULL,
            `exam` varchar(111) NOT NULL,
            `lastcum` varchar(111) NOT NULL,
            `average` varchar(111) NOT NULL,
            `subject` varchar(111) NOT NULL,
            `csession` varchar(111) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `term` varchar(111) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: student
    "student" => "
        CREATE TABLE IF NOT EXISTS `student` (
            `id` int(30) NOT NULL AUTO_INCREMENT,
            `id_no` varchar(100) NOT NULL,
            `name` text NOT NULL,
            `contact` varchar(100) NOT NULL,
            `address` text NOT NULL,
            `email` varchar(200) NOT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: students
    "students" => "
        CREATE TABLE IF NOT EXISTS `students` (
            `id` varchar(222) NOT NULL,
            `name` varchar(222) NOT NULL,
            `gender` varchar(222) NOT NULL,
            `dob` varchar(222) NOT NULL,
            `placeob` varchar(222) NOT NULL,
            `address` varchar(222) NOT NULL,
            `studentmobile` varchar(222) NOT NULL,
            `email` varchar(222) NOT NULL,
            `religion` varchar(222) NOT NULL,
            `state` varchar(222) NOT NULL,
            `lga` varchar(222) NOT NULL,
            `class` varchar(222) NOT NULL,
            `arm` varchar(222) NOT NULL,
            `session` varchar(222) NOT NULL,
            `term` varchar(222) NOT NULL,
            `schoolname` varchar(222) NOT NULL,
            `schooladdress` varchar(222) NOT NULL,
            `hobbies` varchar(222) NOT NULL,
            `lastclass` varchar(222) NOT NULL,
            `sickle` varchar(222) NOT NULL,
            `challenge` varchar(222) NOT NULL,
            `emergency` varchar(222) NOT NULL,
            `familydoc` varchar(222) NOT NULL,
            `docaddress` varchar(222) NOT NULL,
            `docmobile` varchar(222) NOT NULL,
            `polio` varchar(222) NOT NULL,
            `tuberculosis` varchar(222) NOT NULL,
            `measles` varchar(222) NOT NULL,
            `tetanus` varchar(222) NOT NULL,
            `whooping` varchar(222) NOT NULL,
            `gname` varchar(222) NOT NULL,
            `mobile` varchar(222) NOT NULL,
            `goccupation` varchar(222) NOT NULL,
            `gaddress` varchar(222) NOT NULL,
            `grelationship` varchar(222) NOT NULL,
            `hostel` varchar(111) NOT NULL,
            `bloodtype` varchar(111) NOT NULL,
            `bloodgroup` varchar(111) NOT NULL,
            `height` varchar(111) NOT NULL,
            `weight` varchar(111) NOT NULL,
            `photo` blob NOT NULL,
            `status` int(11) NOT NULL,
            `password` varchar(222) NOT NULL,
            `result` int(11) NOT NULL COMMENT '0 = allow\r\n1 = revoke',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: student_ef_list
    "student_ef_list" => "
        CREATE TABLE IF NOT EXISTS `student_ef_list` (
            `id` int(30) NOT NULL AUTO_INCREMENT,
            `student_id` varchar(30) NOT NULL,
            `ef_no` varchar(200) NOT NULL,
            `course_id` int(30) NOT NULL,
            `total_fee` float NOT NULL,
            `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: sub
    "sub" => "
        CREATE TABLE IF NOT EXISTS `sub` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `expdate` varchar(111) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: subject
    "subject" => "
        CREATE TABLE IF NOT EXISTS `subject` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `subject` varchar(222) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: suppliers
    "suppliers" => "
        CREATE TABLE IF NOT EXISTS `suppliers` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `product` varchar(222) NOT NULL,
            `companyname` varchar(222) NOT NULL,
            `phone` varchar(222) NOT NULL,
            `address` varchar(222) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: tblbooknumber
    "tblbooknumber" => "
        CREATE TABLE IF NOT EXISTS `tblbooknumber` (
            `ID` int(11) NOT NULL AUTO_INCREMENT,
            `BOOKTITLE` varchar(255) NOT NULL,
            `QTY` int(11) NOT NULL,
            `Desc` varchar(90) NOT NULL,
            `Author` varchar(90) NOT NULL,
            `PublishDate` date NOT NULL,
            `Publisher` varchar(90) NOT NULL,
            PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: tblbooks
    "tblbooks" => "
        CREATE TABLE IF NOT EXISTS `tblbooks` (
            `BookID` int(11) NOT NULL AUTO_INCREMENT,
            `AccessionNo` varchar(90) NOT NULL,
            `BookTitle` varchar(125) NOT NULL,
            `BookDesc` varchar(255) NOT NULL,
            `Author` varchar(125) NOT NULL,
            `PublishDate` date NOT NULL,
            `BookPublisher` varchar(125) NOT NULL,
            `CategoryId` int(11) NOT NULL,
            `BookPrice` double NOT NULL,
            `BookQuantity` int(11) NOT NULL,
            `Status` varchar(30) NOT NULL,
            `BookType` varchar(90) NOT NULL,
            `DeweyDecimal` varchar(90) NOT NULL,
            `OverAllQty` int(11) NOT NULL,
            `Donate` tinyint(1) NOT NULL,
            `Remark` varchar(90) NOT NULL,
            PRIMARY KEY (`BookID`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: tblborrow
    "tblborrow" => "
        CREATE TABLE IF NOT EXISTS `tblborrow` (
            `BorrowId` int(11) NOT NULL AUTO_INCREMENT,
            `AccessionNo` varchar(90) NOT NULL,
            `NoCopies` int(11) NOT NULL,
            `DateBorrowed` datetime NOT NULL,
            `Purpose` varchar(90) NOT NULL,
            `Status` varchar(30) NOT NULL,
            `DueDate` datetime NOT NULL,
            `BorrowerId` int(11) NOT NULL,
            `Due` tinyint(1) NOT NULL,
            `Remarks` varchar(90) NOT NULL,
            PRIMARY KEY (`BorrowId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: tblborrower
    "tblborrower" => "
        CREATE TABLE IF NOT EXISTS `tblborrower` (
            `IDNO` int(11) NOT NULL AUTO_INCREMENT,
            `BorrowerId` varchar(90) NOT NULL,
            `Firstname` varchar(125) NOT NULL,
            `Lastname` varchar(125) NOT NULL,
            `MiddleName` varchar(125) NOT NULL,
            `Address` varchar(255) NOT NULL,
            `Sex` varchar(11) NOT NULL,
            `ContactNo` varchar(125) NOT NULL,
            `CourseYear` varchar(125) NOT NULL,
            `BorrowerPhoto` varchar(255) NOT NULL,
            `BorrowerType` varchar(35) NOT NULL,
            `Stats` varchar(36) NOT NULL,
            `IMGBLOB` blob NOT NULL,
            PRIMARY KEY (`IDNO`),
            UNIQUE KEY `BorrowerId` (`BorrowerId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: tblcategory
    "tblcategory" => "
        CREATE TABLE IF NOT EXISTS `tblcategory` (
            `CategoryId` int(11) NOT NULL AUTO_INCREMENT,
            `Category` varchar(125) NOT NULL,
            `DDecimal` varchar(90) NOT NULL,
            PRIMARY KEY (`CategoryId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: tbllogs
    "tbllogs" => "
        CREATE TABLE IF NOT EXISTS `tbllogs` (
            `LogId` int(11) NOT NULL AUTO_INCREMENT,
            `UserId` int(11) NOT NULL,
            `LogDate` datetime NOT NULL,
            `LogMode` varchar(30) NOT NULL,
            PRIMARY KEY (`LogId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: tblpayment
    "tblpayment" => "
        CREATE TABLE IF NOT EXISTS `tblpayment` (
            `PaymentId` int(11) NOT NULL AUTO_INCREMENT,
            `BorrowId` int(11) NOT NULL,
            `Payment` double NOT NULL,
            `Change` double NOT NULL,
            `DatePayed` date NOT NULL,
            `BorrowerId` int(11) NOT NULL,
            `Remarks` varchar(125) NOT NULL,
            PRIMARY KEY (`PaymentId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: tblreturn
    "tblreturn" => "
        CREATE TABLE IF NOT EXISTS `tblreturn` (
            `ReturnId` int(11) NOT NULL AUTO_INCREMENT,
            `BorrowId` int(11) NOT NULL,
            `NoCopies` int(11) NOT NULL,
            `DateReturned` datetime NOT NULL,
            `Remarks` varchar(125) NOT NULL,
            PRIMARY KEY (`ReturnId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: tbluser
    "tbluser" => "
        CREATE TABLE IF NOT EXISTS `tbluser` (
            `UserId` int(11) NOT NULL AUTO_INCREMENT,
            `Fullname` varchar(124) NOT NULL,
            `User_name` varchar(125) NOT NULL,
            `Pass` varchar(125) NOT NULL,
            `UserRole` varchar(125) NOT NULL,
            `Status` varchar(11) NOT NULL,
            PRIMARY KEY (`UserId`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: tb_chat
    "tb_chat" => "
        CREATE TABLE IF NOT EXISTS `tb_chat` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `chat` longtext NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: tb_pm
    "tb_pm" => "
        CREATE TABLE IF NOT EXISTS `tb_pm` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(200) NOT NULL,
            `fromuser` varchar(200) NOT NULL,
            `subject` varchar(300) NOT NULL,
            `message` text NOT NULL,
            `isread` int(11) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: tb_users
    "tb_users" => "
        CREATE TABLE IF NOT EXISTS `tb_users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `staffname` varchar(200) NOT NULL,
            `username` varchar(200) NOT NULL,
            `password` varchar(200) NOT NULL,
            `mobile` varchar(50) NOT NULL,
            `email` varchar(200) NOT NULL,
            `dp` blob NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: teachers
    "teachers" => "
    CREATE TABLE IF NOT EXISTS `teachers` (
        `staffid` INT NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(222) NOT NULL,
        `subject` VARCHAR(222) NOT NULL,
        `mobile` VARCHAR(222) NOT NULL,
        `address` VARCHAR(222) NOT NULL,
        PRIMARY KEY (`staffid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
",

    // Table: testimonial
    "testimonial" => "
        CREATE TABLE IF NOT EXISTS `testimonial` (
            `id` varchar(222) NOT NULL,
            `name` varchar(222) NOT NULL,
            `subject` varchar(222) NOT NULL,
            `class` varchar(222) NOT NULL,
            `arm` varchar(222) NOT NULL,
            `remark` varchar(222) NOT NULL,
            `session` varchar(222) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: threads
    "threads" => "
        CREATE TABLE IF NOT EXISTS `threads` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `content` text NOT NULL,
            `author` varchar(255) NOT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: timer
    "timer" => "
        CREATE TABLE IF NOT EXISTS `timer` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `studentid` varchar(50) NOT NULL,
            `timer` varchar(100) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: timetable
    "timetable" => "
        CREATE TABLE IF NOT EXISTS `timetable` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `day` varchar(111) NOT NULL,
            `class` varchar(111) NOT NULL,
            `arm` varchar(111) NOT NULL,
            `subject` varchar(111) NOT NULL,
            `starttime` time NOT NULL,
            `endtime` time NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: transactiondetails
    "transactiondetails" => "
        CREATE TABLE IF NOT EXISTS `transactiondetails` (
            `transactionID` varchar(222) NOT NULL,
            `studentname` varchar(200) NOT NULL,
            `productname` varchar(222) NOT NULL,
            `description` varchar(222) NOT NULL,
            `units` varchar(222) NOT NULL,
            `amount` varchar(222) NOT NULL,
            `transactiondate` varchar(222) NOT NULL,
            `profit` varchar(1000) NOT NULL,
            `cashier` varchar(100) NOT NULL,
            `rownumber` int(11) NOT NULL AUTO_INCREMENT,
            PRIMARY KEY (`rownumber`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: tuck
    "tuck" => "
        CREATE TABLE IF NOT EXISTS `tuck` (
            `regno` varchar(222) NOT NULL,
            `studentname` varchar(222) NOT NULL,
            `sex` varchar(222) NOT NULL,
            `studentclass` varchar(222) NOT NULL,
            `csession` varchar(222) NOT NULL,
            `vbalance` varchar(222) NOT NULL,
            `photo` blob NOT NULL,
            `passcode` int(11) NOT NULL,
            PRIMARY KEY (`regno`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: users
    "users" => "
        CREATE TABLE IF NOT EXISTS `users` (
            `id` varchar(30) NOT NULL,
            `name` text NOT NULL,
            `username` varchar(200) NOT NULL,
            `password` text NOT NULL,
            `type` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1=Admin,2=Staff, 3= subscriber',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    ",

    // Table: posts (must be after threads due to foreign key)
    "posts" => "
        CREATE TABLE IF NOT EXISTS `posts` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `thread_id` int(11) NOT NULL,
            `content` text NOT NULL,
            `author` varchar(255) NOT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            KEY `thread_id` (`thread_id`),
            CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`thread_id`) REFERENCES `threads` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
    ",

    // Table: parent_student
    "parent_student" => "
        CREATE TABLE IF NOT EXISTS `parent_student` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `parent_id` int(11) NOT NULL,
            `student_id` varchar(222) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `parent_id` (`parent_id`),
            KEY `student_id` (`student_id`),
            CONSTRAINT `parent_student_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `parent` (`id`),
            CONSTRAINT `parent_student_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
    "
];

// Create tables
foreach ($tables as $tableName => $query) {
    if (!tableExists($conn, $tableName)) {
        if ($conn->query($query) === TRUE) {
            // Table created successfully
        } else {
            error_log("Error creating table $tableName: " . $conn->error);
        }
    }
}

if (tableExists($conn, 'mst_useranswer')) {
    if (!columnExists($conn, 'mst_useranswer', 'que_id')) {

        $sql = "ALTER TABLE `mst_useranswer`
                ADD COLUMN `que_id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY";

        if ($conn->query($sql) === TRUE) {
            // Success
        } else {
            error_log("Error adding auto increment que_id: " . $conn->error);
        }
    }
}

// Insert initial data
if (tableExists($conn, 'capacity')) {
    $result = $conn->query("SELECT COUNT(*) as count FROM `capacity`");
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $conn->query("INSERT INTO `capacity` (`id`, `volume`) VALUES (1, '50')");
    }
}

if (tableExists($conn, 'currentsession')) {
    $result = $conn->query("SELECT COUNT(*) as count FROM `currentsession`");
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $conn->query("INSERT INTO `currentsession` (`id`, `csession`, `regyr`) VALUES (1, '2024/2025', '25')");
    }
}

if (tableExists($conn, 'currentterm')) {
    $result = $conn->query("SELECT COUNT(*) as count FROM `currentterm`");
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $conn->query("INSERT INTO `currentterm` (`id`, `cterm`) VALUES (1, '1st Term')");
    }
}

if (tableExists($conn, 'nextterm')) {
    $result = $conn->query("SELECT COUNT(*) as count FROM `nextterm`");
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $conn->query("INSERT INTO `nextterm` (`id`, `term`, `session`, `Next`) VALUES (1, '1st Term', '2024/2025', '10-04-2025')");
    }
}

if (tableExists($conn, 'sub')) {
    $result = $conn->query("SELECT COUNT(*) as count FROM `sub`");
    $row = $result->fetch_assoc();
    if ($row['count'] == 0) {
        $conn->query("INSERT INTO `sub` (`id`, `expdate`) VALUES (1, '12/19/2024')");
    }
}
