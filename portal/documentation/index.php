<?php
session_start();
?>
<!doctype html>
<html class="no-js " lang="en">


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="EduHive Documentaation.">
    <title>EduHive Documentation</title>
    <link rel="icon" href="logo-dark.ico" type="image/x-icon"> <!-- Favicon-->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/jvectormap/jquery-jvectormap-2.0.3.min.css" />
    <link rel="stylesheet" href="assets/plugins/charts-c3/plugin.css" />

    <link rel="stylesheet" href="assets/plugins/morrisjs/morris.min.css" />
    <!-- Custom Css -->
    <link rel="stylesheet" href="assets/css/style.min.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

    <style>
        html {
            scroll-behavior: smooth;
        }

        /* Back to Top Button Styles */
        #backToTopBtn {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Fixed position */
            bottom: 20px;
            /* Place the button at the bottom of the page */
            right: 30px;
            /* Place the button at the right of the page */
            z-index: 99;
            /* Make sure it does not overlap */
            border: none;
            /* Remove borders */
            outline: none;
            /* Remove outline */
            background-color: #007bff;
            /* Set a background color */
            color: white;
            /* Text color */
            cursor: pointer;
            /* Add a mouse pointer on hover */
            padding: 5px;
            /* Some padding */
            border-radius: 10px;
            /* Rounded corners */
            font-size: 14px;
            /* Increase font size */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Add a subtle shadow */
            transition: background-color 0.3s, opacity 0.3s;
            /* Smooth transition for hover effects */
            height: 40px;
            /* Set a fixed height */
            width: 40px;
            /* Set a fixed width */
        }

        #backToTopBtn:hover {
            background-color: #0056b3;
            /* Darker background on hover */
            opacity: 0.9;
        }

        li.open a {
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>

<body class="theme-blush">

    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <div class="navbar-brand">
            <button class="btn-menu ls-toggle-btn" type="button"><i class="zmdi zmdi-menu"></i></button>
        </div>
        <div class="menu">
            <ul class="list">
                <?php if ($_SESSION['role'] == 'Superuser') { ?>
                    <li class="open"><a href="../superdashboard.php"><i class="zmdi zmdi-home"></i><span>Back to Dashboard</span></a></li>
                <?php } elseif ($_SESSION['role'] == 'Administrator') { ?>
                    <li class="open"><a href="../dashboard.php"><i class="zmdi zmdi-home"></i><span>Back to Dashboard</span></a></li>
                <?php } elseif ($_SESSION['role'] == 'Teacher') { ?>
                    <li class="open"><a href="../dashboard.php"><i class="zmdi zmdi-home"></i><span>Back to Dashboard</span></a></li>
                <?php } elseif ($_SESSION['role'] == 'Admission') { ?>
                    <li class="open"><a href="../dashboard.php"><i class="zmdi zmdi-home"></i><span>Back to Dashboard</span></a></li>
                <?php } elseif ($_SESSION['role'] == 'Tuckshop') { ?>
                    <li class="open"><a href="../tuckdashboard.php"><i class="zmdi zmdi-home"></i><span>Back to Dashboard</span></a></li>
                <?php } ?>
                <li class="open"><a href="#collapseEnroll"><i class="zmdi zmdi-account-add"></i><span>Enroll Students</span></a></li>
                <li class="open"><a href="#modify"><i class="zmdi zmdi-edit"></i><span>Modify Students</span></a></li>
                <li class="open"><a href="#view"><i class="zmdi zmdi-eye"></i><span>View Student Profile</span></a></li>
                <li class="open"><a href="#filter"><i class="zmdi zmdi-filter-list"></i><span>Filter Students</span></a></li>
                <li class="open"><a href="#mark_attendance"><i class="zmdi zmdi-check-square"></i><span>Mark Attendance</span></a></li>
                <li class="open"><a href="#print_summary"><i class="zmdi zmdi-print"></i><span>Print Attendance Summary</span></a></li>
                <li class="open"><a href="#print_sheet"><i class="zmdi zmdi-print"></i><span>Print Attendance Sheet</span></a></li>
                <li class="open"><a href="#upload_results"><i class="zmdi zmdi-upload"></i><span>Upload Results</span></a></li>
                <li class="open"><a href="#modify_results"><i class="zmdi zmdi-edit"></i><span>Modify Results</span></a></li>
                <li class="open"><a href="#delete_results"><i class="zmdi zmdi-delete"></i><span>Delete Results</span></a></li>
                <li class="open"><a href="#class_teacher_comments"><i class="zmdi zmdi-comment-text"></i><span>Class Teacher's Comments</span></a></li>
                <li class="open"><a href="#principal_comments"><i class="zmdi zmdi-comment-text-alt"></i><span>Principal's Comments</span></a></li>
                <li class="open"><a href="#download_student_result"><i class="zmdi zmdi-download"></i><span>Download Student's Result</span></a></li>
                <li class="open"><a href="#view_uploaded_results"><i class="zmdi zmdi-eye"></i><span>View Uploaded Results</span></a></li>
                <li class="open"><a href="#download_mastersheet"><i class="zmdi zmdi-collection-pdf"></i><span>Download Mastersheet</span></a></li>
                <li class="open"><a href="#revoke_results"><i class="zmdi zmdi-block"></i><span>Revoke Student Results</span></a></li>
                <li class="open"><a href="#results_maintenance"><i class="zmdi zmdi-settings"></i><span>Results Maintenance</span></a></li>
                <li class="open"><a href="#upload_assignments"><i class="zmdi zmdi-assignment"></i><span>Upload Assignments</span></a></li>
                <li class="open"><a href="#view_assignments"><i class="zmdi zmdi-assignment-o"></i><span>View Assignments</span></a></li>
                <li class="open"><a href="#upload_notes"><i class="zmdi zmdi-file-text"></i><span>Upload Notes</span></a></li>
                <li class="open"><a href="#view_notes"><i class="zmdi zmdi-file"></i><span>View Notes</span></a></li>
                <li class="open"><a href="#upload_curriculum"><i class="zmdi zmdi-book"></i><span>Upload Curriculum</span></a></li>
                <li class="open"><a href="#view_curriculum"><i class="zmdi zmdi-book-image"></i><span>View Curriculum</span></a></li>
                <li class="open"><a href="#add_questions"><i class="zmdi zmdi-plus-circle"></i><span>Add Questions</span></a></li>
                <li class="open"><a href="#upload_questions"><i class="zmdi zmdi-upload"></i><span>Upload Questions (Bulk)</span></a></li>
                <li class="open"><a href="#modify_questions"><i class="zmdi zmdi-edit"></i><span>Modify Questions</span></a></li>
                <li class="open"><a href="#check_cbt_results"><i class="zmdi zmdi-check-circle"></i><span>Check CBT Results</span></a></li>
                <li class="open"><a href="#set_exam_time"><i class="zmdi zmdi-time"></i><span>Set Exam Time/Date</span></a></li>
                <li class="open"><a href="#register_tuckshop_user"><i class="zmdi zmdi-account-add"></i><span>Register Tuckshop User</span></a></li>
                <li class="open"><a href="#tuckshop_pos"><i class="zmdi zmdi-shopping-cart"></i><span>Tuckshop POS</span></a></li>
                <li class="open"><a href="#tuckshop_inventory"><i class="zmdi zmdi-storage"></i><span>Tuckshop Inventory</span></a></li>
                <li class="open"><a href="#tuckshop_suppliers"><i class="zmdi zmdi-truck"></i><span>Tuckshop Suppliers</span></a></li>
                <li class="open"><a href="#tuckshop_dashboard"><i class="zmdi zmdi-view-dashboard"></i><span>Tuckshop Dashboard</span></a></li>
                <li class="open"><a href="#tuckshop_transactions"><i class="zmdi zmdi-money"></i><span>Tuckshop Transactions</span></a></li>
                <li class="open"><a href="#class_schedule"><i class="zmdi zmdi-calendar-alt"></i><span>Class Schedule</span></a></li>
                <li class="open"><a href="#academic_calendar"><i class="zmdi zmdi-calendar"></i><span>Academic Calendar</span></a></li>
                <li class="open"><a href="#manage_subjects"><i class="zmdi zmdi-book"></i><span>Manage Subjects</span></a></li>
                <li class="open"><a href="#collapseSystemSettings" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseSystemSettings"><i class="zmdi zmdi-settings-square"></i><span>System Settings</span></a></li>
                <li class="open"><a href="#user_control"><i class="zmdi zmdi-accounts-alt"></i><span>User Control</span></a></li>
                <li class="open"><a href="#send_notice"><i class="zmdi zmdi-notifications-active"></i><span>Send Notice to Parents</span></a></li>
                <li class="open"><a href="#alumni_list"><i class="zmdi zmdi-graduation-cap"></i><span>Alumni List</span></a></li>
                <li class="open"><a href="#view_threads"><i class="zmdi zmdi-comments"></i><span>View Discussion Threads</span></a></li>
                <li class="open"><a href="#create_thread"><i class="zmdi zmdi-comment"></i><span>Create Discussion Thread</span></a></li>
                <li class="open"><a href="#read_message"><i class="zmdi zmdi-email-open"></i><span>Read Message</span></a></li>
                <li class="open"><a href="#reply_message"><i class="zmdi zmdi-mail-reply"></i><span>Reply Message</span></a></li>
                <li class="open"><a href="#edit_thread"><i class="zmdi zmdi-edit"></i><span>Edit Discussion Thread</span></a></li>
                <li class="open"><a href="#delete_thread"><i class="zmdi zmdi-delete"></i><span>Delete Discussion Thread</span></a></li>
                <li class="open"><a href="#register_parents"><i class="zmdi zmdi-account-add"></i><span>Register Parents</span></a></li>
                <li class="open"><a href="#delete_parents"><i class="zmdi zmdi-delete"></i><span>Delete Parents</span></a></li>
                <li class="open"><a href="#assign_students_to_parents"><i class="zmdi zmdi-accounts-add"></i><span>Assign Students to Parents</span></a></li>
                <li class="open"><a href="#unassign_students_from_parents"><i class="zmdi zmdi-accounts-add"></i><span>Unassign Students from Parents</span></a></li>
                <li class="open"><a href="#profile_page"><i class="zmdi zmdi-account"></i><span>Profile Page</span></a></li>
                <li class="open"><a href="#create_message"><i class="zmdi zmdi-email"></i><span>Create Email</span></a></li>
                <li class="open"><a href="#inbox"><i class="zmdi zmdi-inbox"></i><span>Inbox</span></a></li>
                <li class="open"><a href="#sent_messages"><i class="zmdi zmdi-mail-send"></i><span>Sent Messages</span></a></li>
            </ul>
        </div>
    </aside>


    <!-- Main Content -->

    <section class="content">
        <div class="">

            <!-- INTRODUCTION -->
            <div class="block-header">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h2>EduHive Documentation</h2>
                        <p class="lead mt-2">Welcome to the EduHive Documentation. This guide provides comprehensive instructions on how to effectively use and manage the various features within the EduHive School Management System. Whether you are an administrator or teacher, this documentation will help you navigate the platform and utilize its functionalities to enhance the educational experience.</p>

                        <button class="btn btn-primary btn-icon mobile_menu d-lg-none d-md-none" type="button"><i class="zmdi zmdi-sort-amount-desc"></i></button>
                    </div>
                </div>
            </div>
            <!-- INTRODUCTION ENDS HERE -->

            <div class="container-fluid">

                <div class="row clearfix">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="header ms-4">
                                <h2><strong>Student</strong> Management</h2>
                            </div>
                            <div class="body">
                                <div class="row">
                                    <!-- ENROLLMENT -->
                                    <div class="col-lg-12" id="enroll">



                                        <div class="accordion" id="studentManagementAccordion">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingEnroll">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEnroll" aria-expanded="false" aria-controls="collapseEnroll">
                                                        How to Enroll a Student
                                                    </button>
                                                </h2>
                                                <div id="collapseEnroll" class="accordion-collapse collapse" aria-labelledby="headingEnroll" data-bs-parent="#studentManagementAccordion">
                                                    <div class="accordion-body">
                                                        <p>This feature is designed for school administrators or admission officers to seamlessly add new students to the EduHive system. Think of it as the digital registration desk for new students, ensuring all their initial information is captured accurately from the start of their academic journey.</p>
                                                        <ol>
                                                            <li><strong>Navigate to the Enrollment Page:</strong>
                                                                <ul>
                                                                    <li>From the main dashboard, look for the sidebar menu on the left.</li>
                                                                    <li>Under <strong>Admission</strong>, click to <strong>Students</strong>, and finally select <strong>Enroll</strong>.</li>
                                                                    <li>This action will take you to the Register Students page, which is where you'll find the student enrollment form.</li>
                                                                </ul>
                                                            </li>
                                                            <li><strong>Fill in Student Details:</strong>
                                                                <ul>
                                                                    <li>On the Register Students page, you will see a form with various fields.</li>
                                                                    <li><strong>Personal Information:</strong> Enter the student's full name, date of birth, gender, and current address.</li>
                                                                    <li><strong>Contact Information:</strong> Provide emergency contact details, including parent/guardian names, phone numbers, and email addresses.</li>
                                                                    <li><strong>Academic Background:</strong> Input any relevant previous school records or academic history.</li>
                                                                    <li><strong>Important Note for Users:</strong> Accuracy is paramount here! Double-check all entries, especially names and dates, as this information will be used across all other modules (attendance, results, etc.) throughout the student's time at the school.</li>
                                                                </ul>
                                                            </li>
                                                            <li><strong>Submit the Enrollment Form:</strong>
                                                                <ul>
                                                                    <li>After carefully filling out all the required fields, locate and click the "Submit" button at the bottom of the form.</li>
                                                                    <li>Upon successful submission, the student's record will be officially saved in the EduHive database. They are now a registered student and can be managed through other features like attendance, results, and parent communication.</li>
                                                                </ul>
                                                            </li>
                                                        </ol>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingModify">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseModify" aria-expanded="false" aria-controls="collapseModify">
                                                        How to Modify a Student's Record
                                                    </button>
                                                </h2>
                                                <div id="collapseModify" class="accordion-collapse collapse" aria-labelledby="headingModify" data-bs-parent="#studentManagementAccordion">
                                                    <div class="accordion-body">
                                                        <div id="modify">
                                                            <p>This feature allows authorized staff (like administrators or admission officers) to update or correct any existing information for a student already enrolled in the system. This is incredibly useful for keeping student records current, whether it's a change in address, phone number, class assignment, or any other personal detail that evolves over time.</p>
                                                            <ol>
                                                                <li><strong>Navigate to the Modify Student Page:</strong>
                                                                    <ul>
                                                                        <li>From the sidebar menu, go to <strong>Admission > Students > Modify</strong>.</li>
                                                                        <li>This will direct you to the Modify Students page, which is where you can search for and edit student records.</li>
                                                                    </ul>
                                                                </li>
                                                                <li><strong>Search for the Student:</strong>
                                                                    <ul>
                                                                        <li>On the Modify Students page, you'll find a search bar or filter options.</li>
                                                                        <li>Enter the student's ID, full name, or other identifying information to quickly locate their record.</li>
                                                                        <li><strong>Tip for Users:</strong> If you're unsure of the exact spelling, try using partial names or filtering by class to narrow down your search.</li>
                                                                    </ul>
                                                                </li>
                                                                <li><strong>Update Student Information:</strong>
                                                                    <ul>
                                                                        <li>Once the correct student's record appears, their current details will be displayed in editable form fields.</li>
                                                                        <li>Carefully make the necessary changes. For example, update their contact number, change their assigned class, or correct a spelling error in their name.</li>
                                                                        <li><strong>Important Note:</strong> Always ensure that any modifications are accurate and reflect the most current information. Incorrect data can lead to issues in other parts of the system.</li>
                                                                    </ul>
                                                                </li>
                                                                <li><strong>Save the Changes:</strong>
                                                                    <ul>
                                                                        <li>After making all desired updates, click the "Update" button.</li>
                                                                        <li>The system will then save these changes, and the student's record in the database will be immediately updated with the new information.</li>
                                                                    </ul>
                                                                </li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingView">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseView" aria-expanded="false" aria-controls="collapseView">
                                                        How to View a Student's Profile
                                                    </button>
                                                </h2>
                                                <div id="collapseView" class="accordion-collapse collapse" aria-labelledby="headingView" data-bs-parent="#studentManagementAccordion">
                                                    <div class="accordion-body">
                                                        <div id="view">
                                                            <p>The "View Student Profile" feature offers a complete, 360-degree view of a student's journey within the EduHive system. It consolidates all their personal, academic, attendance, and financial information into one easy-to-access location. This is a vital tool for administrators, teachers, and even parents (with appropriate permissions) to quickly understand a student's status and history.</p>
                                                            <ol>
                                                                <li><strong>Navigate to the View Profile Page:</strong>
                                                                    <ul>
                                                                        <li>From the sidebar menu, select <strong>Admission > Students > View Profile</strong>.</li>
                                                                        <li>This action will take you to the View Students page, which is designed to display individual student profiles.</li>
                                                                    </ul>
                                                                </li>
                                                                <li><strong>Search for the Student:</strong>
                                                                    <ul>
                                                                        <li>On the View Students page, you'll find a search field.</li>
                                                                        <li>Enter the student's name or their unique student ID to find the specific profile you wish to view.</li>
                                                                        <li><strong>Tip for Users:</strong> Ensure you have the correct name or ID to get the most accurate search result.</li>
                                                                    </ul>
                                                                </li>
                                                                <li><strong>Review the Comprehensive Profile:</strong>
                                                                    <ul>
                                                                        <li>Once located, the system will display a detailed profile for the student. This profile typically includes:
                                                                            <ul>
                                                                                <li><strong>Personal Details:</strong> Name, date of birth, contact information, address.</li>
                                                                            </ul>
                                                                        </li>
                                                                        <li><strong>Benefit for Users:</strong> This page acts as a central information hub, eliminating the need to jump between different sections of the system to gather a complete picture of a student. It's perfect for quick information retrieval.</li>
                                                                    </ul>
                                                                </li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingFilter">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                                                        How to Filter Students
                                                    </button>
                                                </h2>
                                                <div id="collapseFilter" class="accordion-collapse collapse" aria-labelledby="headingFilter" data-bs-parent="#studentManagementAccordion">
                                                    <div class="accordion-body">
                                                        <div id="filter">
                                                            <p>The "Filter Students" tool is a powerful feature that allows you to quickly narrow down and find specific groups of students based on class and arm..</p>
                                                            <ol>
                                                                <li><strong>Navigate to the Filter Students Page:</strong>
                                                                    <ul>
                                                                        <li>From the sidebar menu, select <strong>Admission > Students > Filter Students</strong>.</li>
                                                                        <li>This will take you to the Filter Students page, where you'll find the filtering options.</li>
                                                                    </ul>
                                                                </li>
                                                                <li><strong>Apply Desired Filters:</strong>
                                                                    <ul>
                                                                        <li>On the Filter Students page, you will see dropdown menus. These are your filtering tools.</li>
                                                                        <li>You can apply filters to get very specific results. For example:
                                                                            <ul>
                                                                                <li><strong>Filter by Class:</strong> Select a specific grade or class (e.g., "Grade 5A").</li>
                                                                                <li><strong>Filter by Arm:</strong> Choose the arm (e.g., "A, B, C...").</li>
                                                                            </ul>
                                                                        </li>
                                                                    </ul>
                                                                </li>
                                                            </ol>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ENROLLMENT END HERE -->
                                </div>

                                <div class="body">
                                    <div class="row">
                                        <div class="card">
                                            <div class="header ms-4">
                                                <h2><strong>Attendance</strong> Management</h2>
                                            </div>
                                            <div class="body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="accordion" id="attendanceManagementAccordion">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingMarkAttendance">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMarkAttendance" aria-expanded="false" aria-controls="collapseMarkAttendance">
                                                                        How to Mark Attendance
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseMarkAttendance" class="accordion-collapse collapse" aria-labelledby="headingMarkAttendance" data-bs-parent="#attendanceManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="mark_attendance">
                                                                            <p>The "Mark Attendance" feature is crucial for teachers to accurately record student presence or absence in the school. Consistent attendance tracking helps monitor student engagement, identify potential issues early, and provides valuable data for reports.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Mark Attendance Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Attendance > Mark Attendance</strong>.</li>
                                                                                        <li>This will take you to the Mark Attendance page, which is where you will record attendance.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Select Class, Session, and Term:</strong>
                                                                                    <ul>
                                                                                        <li>On the Mark Attendance page, you will typically find dropdown menus.</li>
                                                                                        <li>First, select the specific <strong>Class</strong> (e.g., "JSS 1") for which you are marking attendance.</li>
                                                                                        <li>Next, choose the <strong>Arm</strong> (e.g., "A, B, C...").</li>
                                                                                        <li>Finally, select the relevant <strong>date</strong> (e.g., "2024-02-15").</li>
                                                                                        <li><strong>Important for Users:</strong> Ensure these selections are correct to avoid marking attendance for the wrong class or day.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Mark Each Student's Status:</strong>
                                                                                    <ul>
                                                                                        <li>Once the class, session, and term are selected, a list of students in that class will appear.</li>
                                                                                        <li>For each student, you will have options to mark their attendance status:
                                                                                            <ul>
                                                                                                <li><strong>Present:</strong> The student is in school.</li>
                                                                                                <li><strong>Absent:</strong> The student is not in school.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Save the Attendance Record:</strong>
                                                                                    <ul>
                                                                                        <li>After marking the status for all students, locate and click the "Save" button.</li>
                                                                                        <li>This action saves the attendance record for that specific class, arm, and date into the EduHive database.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingPrintSummary">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrintSummary" aria-expanded="false" aria-controls="collapsePrintSummary">
                                                                        How to Print Attendance Summary
                                                                    </button>
                                                                </h2>
                                                                <div id="collapsePrintSummary" class="accordion-collapse collapse" aria-labelledby="headingPrintSummary" data-bs-parent="#attendanceManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="print_summary">
                                                                            <p>The "Print Attendance Summary" feature allows teachers and administrators to generate a concise overview of attendance records for a specific class, arm, academic session, and term. This summary is invaluable for tracking overall attendance trends, identifying students with frequent absences, and for reporting purposes.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Print Attendance Summary Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Attendance > Print Attendance Summary</strong>.</li>
                                                                                        <li>This will take you to the Print Attendance Summary page.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Select Desired Criteria:</strong>
                                                                                    <ul>
                                                                                        <li>On the Print Attendance Summary page, you will need to specify the criteria for the summary.</li>
                                                                                        <li>Use the dropdowns to select the <strong>Class</strong> (e.g., "JSS 1"), the <strong>Arm</strong> (e.g., "A").</li>
                                                                                        <li><strong>Important for Users:</strong> Make sure your selections accurately reflect the period and group for which you need the summary.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Generate and Print the Summary:</strong>
                                                                                    <ul>
                                                                                        <li>After making your selections, click the "Print" button.</li>
                                                                                        <li>The system will then generate a printable summary document. This document typically includes:
                                                                                            <ul>
                                                                                                <li>A list of students in the selected class.</li>
                                                                                                <li>The total number of days school opened, their total number of days present, absent for the specified class and arm in the current term/session.</li>

                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>You can then use your browser's print function to print this summary or save it as a PDF.</li>
                                                                                        <li><strong>Benefit for Users:</strong> This summary provides a quick and easy way to get a bird's-eye view of attendance without having to manually calculate totals from daily records.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingPrintSheet">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrintSheet" aria-expanded="false" aria-controls="collapsePrintSheet">
                                                                        How to Print Attendance Sheet
                                                                    </button>
                                                                </h2>
                                                                <div id="collapsePrintSheet" class="accordion-collapse collapse" aria-labelledby="headingPrintSheet" data-bs-parent="#attendanceManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="print_sheet">
                                                                            <p>The "Print Attendance Sheet" feature allows teachers to generate students attendance status by class. This is particularly useful for getting a general overview of the class attendance for the day. It provides a structured document for manual record-keeping during class.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Print Attendance Sheet Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Attendance > Print Attendance Sheet</strong>.</li>
                                                                                        <li>This will take you to the Print Attendance Sheet page.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Select Class, and Arm:</strong>
                                                                                    <ul>
                                                                                        <li>On the Print Attendance Sheet page, you will find dropdown menus to specify the details for the sheet.</li>
                                                                                        <li>Select the relevant <strong>Class</strong> (e.g., "JSS 1"), and the <strong>Arm</strong> (e.g., "B").</li>
                                                                                        <li><strong>Important for Users:</strong> These selections ensure that the generated sheet is tailored to the correct class and selected date.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Generate and Print the Attendance Sheet:</strong>
                                                                                    <ul>
                                                                                        <li>After making your selections, click the "Print Detailed Sheet" button.</li>
                                                                                        <li>The system will then generate a class attendance sheet for the selected day. This sheet typically includes:
                                                                                            <ul>
                                                                                                <li>The selected class, and arm details at the top.</li>
                                                                                                <li>A list of student names.</li>
                                                                                                <li>Columns for marked daily attendance (Present or Absent) over a period.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>You can then use your browser's print function to print this sheet.</li>
                                                                                        <li><strong>Benefit for Users:</strong> This tool simplifies the creation of organized physical attendance records, making it easy to maintain a backup or use in environments without immediate digital access.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="header ms-4">
                                                <h2><strong>Results</strong> Management</h2>
                                            </div>
                                            <div class="body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="accordion" id="resultsManagementAccordion">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingUploadResults">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUploadResults" aria-expanded="false" aria-controls="collapseUploadResults">
                                                                        How to Upload Results
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseUploadResults" class="accordion-collapse collapse" aria-labelledby="headingUploadResults" data-bs-parent="#resultsManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="upload_results">
                                                                            <p>The "Upload Results" feature is designed for teachers to submit student academic performance data into the EduHive system in bulk. This is a critical step in the academic cycle, ensuring that grades and scores are accurately recorded and made available for reporting and student profiles.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Upload Results Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Results > Upload</strong>.</li>
                                                                                        <li>This will take you to the Upload Results page, which is dedicated to the results submission process.</li>
                                                                                        <li>You can download a result template by selecting the class and arm and click on "Download Score Template", which is dedicated to the results submission process.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Prepare Your Result Data (Using a Template):</strong>
                                                                                    <ul>
                                                                                        <li>EduHive typically requires result data to be in a specific format, often provided via an Excel or CSV template.</li>
                                                                                        <li><strong>Important for Users:</strong> Before uploading, ensure your data is correctly entered into the provided template. This usually means:
                                                                                            <ul>
                                                                                                <li>Each student's ID is correct.</li>
                                                                                                <li>Scores for each subject are accurately recorded.</li>
                                                                                                <li>The academic session and term are clearly specified.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>If a template is available for download on the page, make sure to use it to prevent upload errors.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Upload the Result File:</strong>
                                                                                    <ul>
                                                                                        <li>On the Upload Results page, you will find an option to "Choose File" or "Browse" to select your prepared result file from your computer.</li>
                                                                                        <li><strong>Select Subject, Class, and Arm:</strong>
                                                                                            <ul>
                                                                                                <li>On the Upload Results page, you will find dropdown menus or selection fields.</li>
                                                                                                <li>First, select the <strong>Subject</strong> for which you are uploading results (e.g., "Mathematics").</li>
                                                                                                <li>Next, choose the <strong>Class</strong> (e.g., "JSS 1") and <strong>Arm</strong> (e.g., "A, B, C...").</li>
                                                                                                <li><strong>Tip for Users:</strong> Double-check your selections to ensure results are uploaded for the correct subject, class, and arm.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>After selecting the file, click the "Upload" or "Submit" button.</li>
                                                                                        <li>The system will then process the file, validate the data, and import the results into the database.</li>
                                                                                        <li><strong>Confirmation:</strong> A success message or a report of any errors will be displayed after the upload is complete. Review this carefully to ensure all results were processed correctly.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingModifyResults">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseModifyResults" aria-expanded="false" aria-controls="collapseModifyResults">
                                                                        How to Modify Results
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseModifyResults" class="accordion-collapse collapse" aria-labelledby="headingModifyResults" data-bs-parent="#resultsManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="modify_results">
                                                                            <p>The "Modify Results" feature allows authorized personnel, typically teachers or administrators, to make corrections or updates to student results that have already been uploaded. This is essential for rectifying errors, adjusting scores, or incorporating late submissions to ensure the accuracy of academic records.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Modify Results Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Results > Modify</strong>.</li>
                                                                                        <li>This will take you to the Modify Result page, where you can access and edit existing student results.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Search for the Student/Result:</strong>
                                                                                    <ul>
                                                                                        <li>On the Modify Result page, you will find a search box where you can search using a student name or ID.</li>
                                                                                        <li>Locate the specific result record you need to modify.</li>
                                                                                        <li><strong>Tip for Users:</strong> Be as specific as possible in your search to quickly find the correct record.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Edit the Existing Results:</strong>
                                                                                    <ul>
                                                                                        <li>Once the student's result record is displayed, you will see the current scores and grades in editable fields.</li>
                                                                                        <li>Carefully make the necessary changes to the scores, grades, or any other relevant data.</li>
                                                                                        <li><strong>Important Note:</strong> Any changes made here directly impact the student's academic record. Always verify the accuracy of your modifications before saving.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Save the Updated Results:</strong>
                                                                                    <ul>
                                                                                        <li>After making all desired edits, click the "Update" button on the form.</li>
                                                                                        <li>The system will then save these changes, and the student's result in the database will be immediately updated with the new information.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingDeleteResults">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDeleteResults" aria-expanded="false" aria-controls="collapseDeleteResults">
                                                                        How to Delete Results
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseDeleteResults" class="accordion-collapse collapse" aria-labelledby="headingDeleteResults" data-bs-parent="#resultsManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="delete_results">
                                                                            <p>The "Delete Results" feature allows authorized users, typically administrators or teachers with specific permissions, to remove student academic results from the system. This action should be performed with caution, as deleting results is usually irreversible and can impact academic records. It's generally used for correcting erroneous uploads or managing outdated data.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Delete Results Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Results > Delete</strong>.</li>
                                                                                        <li>This will take you to the Delete Result  page, which is where you can initiate the deletion process.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Identify Results for Deletion:</strong>
                                                                                    <ul>
                                                                                        <li>On the Delete Result page, you will find options to filter results.</li>
                                                                                        <li>Use the available filters to specify <strong>Class</strong>, <strong>Arm</strong>, <strong>Term</strong>, and <strong>Academic Session</strong> to display the relevant results.</li>
                                                                                        <li>Once the filtered records are displayed, you can select and delete your preferred result(s) from the list.</li>
                                                                                        <li><strong>Important for Users:</strong> Double-check your selection carefully. Deleting the wrong results can cause significant data loss.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Confirm and Execute Deletion:</strong>
                                                                                    <ul>
                                                                                        <li>Once you have identified the results to be deleted, there will typically be a "Delete" button or a similar action.</li>
                                                                                        <li>The system will usually prompt you with a confirmation message (e.g., "Are you sure you want to delete these results?"). This is a safety measure to prevent accidental deletions.</li>
                                                                                        <li>Click "Yes" to proceed with the deletion.</li>
                                                                                        <li><strong>Warning:</strong> Once confirmed, the selected student results will be permanently removed from the EduHive database. Ensure you have any necessary backups or approvals before proceeding.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingClassTeacherComments">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClassTeacherComments" aria-expanded="false" aria-controls="collapseClassTeacherComments">
                                                                        How to Add Class Teacher's Comments
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseClassTeacherComments" class="accordion-collapse collapse" aria-labelledby="headingClassTeacherComments" data-bs-parent="#resultsManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="class_teacher_comments">
                                                                            <p>The "Class Teacher's Comments" feature allows class teachers to provide personalized qualitative feedback on student performance for a specific academic term. These comments are a vital part of a student's report card, offering insights beyond just grades and helping parents understand their child's progress, strengths, and areas for improvement in a holistic manner.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Class Teacher's Comments Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Results > Class Teacher's Comments</strong>.</li>
                                                                                        <li>This will take you to the Class Teacher Comment page, which is where you will input your feedback.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Select the Student and Academic Period:</strong>
                                                                                    <ul>
                                                                                        <li>On the Class Teacher Comment page, you will find options for both bulk and individual uploads.</li>
                                                                                        <li><strong>For Bulk Uploads:</strong>
                                                                                            <ul>
                                                                                                <li>First, select the <strong>Class</strong> and <strong>Arm</strong> to download the template.</li>
                                                                                                <li>The template will include the student IDs and names; you only need to fill in the other required records correctly.</li>
                                                                                                <li>After completing the template, upload it back to the system to save comments for multiple students at once.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>For Individual Uploads:</strong>
                                                                                            <ul>
                                                                                                <li>Fill the form for a single student and save your entry to the database.</li>
                                                                                                <li>Ensure the <strong>Student ID</strong> and <strong>Name</strong> are correct before submitting.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>Next, choose the relevant <strong>Academic Session</strong> (e.g., "2024/2025") and <strong>Term</strong> (e.g., "First Term").</li>
                                                                                        <li><strong>Important for Users:</strong> Ensure you select the correct student and academic period to ensure your comments are attached to the right report card.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Compose the Comment:</strong>
                                                                                    <ul>
                                                                                        <li>You will find a text area or input field labeled "Comment" or "Teacher's Remarks."</li>
                                                                                        <li>Type your detailed feedback here. Consider including observations on the student's academic progress, behavior, participation in class, social skills, and any specific recommendations for improvement or areas of excellence.</li>
                                                                                        <li><strong>Tip for Users:</strong> Aim for constructive, specific, and encouraging comments. Avoid vague statements. For example, instead of "Good student," try " [Student's Name] consistently demonstrates a strong understanding of mathematical concepts and actively participates in group discussions."</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Save the Comment:</strong>
                                                                                    <ul>
                                                                                        <li>After carefully composing your comment, locate and click the "Submit" or "Save Comment" button.</li>
                                                                                        <li>The system will then save your comment, linking it to the selected student's result for that specific term.</li>
                                                                                        <li><strong>Confirmation:</strong> A success message will usually appear, confirming that the comment has been saved.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingPrincipalComments">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrincipalComments" aria-expanded="false" aria-controls="collapsePrincipalComments">
                                                                        How to Add Principal's Comments
                                                                    </button>
                                                                </h2>
                                                                <div id="collapsePrincipalComments" class="accordion-collapse collapse" aria-labelledby="headingPrincipalComments" data-bs-parent="#resultsManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="principal_comments">
                                                                            <p>The "Principal's Comments" feature allows the school principal or head of school to provide an overarching qualitative assessment of student performance, often serving as a final review and endorsement of the student's academic and personal development. These comments typically appear on the final report card and carry significant weight, offering a high-level perspective on the student's overall progress and potential.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Principal's Comments Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Results > Principal's Comments</strong>.</li>
                                                                                        <li>This will take you to the Principal Comment page, where the principal can input their feedback.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Select the Student and Academic Period:</strong>
                                                                                    <ul>
                                                                                        <li>On the Principal Comment page, you will find options for both bulk and individual uploads.</li>
                                                                                        <li><strong>For Bulk Uploads:</strong>
                                                                                            <ul>
                                                                                                <li>First, select the <strong>Class</strong> and <strong>Arm</strong> to download the template.</li>
                                                                                                <li>The template will include the student IDs and names; you only need to fill in the other required records correctly.</li>
                                                                                                <li>After completing the template, upload it back to the system to save comments for multiple students at once.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>For Individual Uploads:</strong>
                                                                                            <ul>
                                                                                                <li>Fill the form for a single student and save your entry to the database.</li>
                                                                                                <li>Ensure the <strong>Student ID</strong> and <strong>Name</strong> are correct before submitting.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>Next, choose the relevant <strong>Academic Session</strong> (e.g., "2024/2025") and <strong>Term</strong> (e.g., "First Term").</li>
                                                                                        <li><strong>Important for Users:</strong> Ensure you select the correct student and academic period to ensure your comments are attached to the right report card.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Compose the Principal's Comment:</strong>
                                                                                    <ul>
                                                                                        <li>You will find a text area or input field labeled "Comment" or "Principal's Remarks."</li>
                                                                                        <li>Type your detailed feedback here. Consider including observations on the student's academic progress, behavior, participation in school activities, leadership qualities, and any specific recommendations for improvement or areas of excellence.</li>
                                                                                        <li><strong>Tip for Users:</strong> Aim for constructive, specific, and encouraging comments. Principal's comments are usually more formal and strategic, focusing on broader aspects of student development and school values.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Save the Comment:</strong>
                                                                                    <ul>
                                                                                        <li>After the comment has been composed, click the "Submit" or "Save Comment" button.</li>
                                                                                        <li>The system will save this comment, linking it to the student's report card for the selected term.</li>
                                                                                        <li><strong>Confirmation:</strong> A confirmation message will typically be displayed upon successful saving.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingDownloadStudentResult">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDownloadStudentResult" aria-expanded="false" aria-controls="collapseDownloadStudentResult">
                                                                        How to Download Student's Result
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseDownloadStudentResult" class="accordion-collapse collapse" aria-labelledby="headingDownloadStudentResult" data-bs-parent="#resultsManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="download_student_result">
                                                                            <p>The "Download Student's Result" feature provides a convenient way for authorized users (teachers and administrators) to generate and download a student's individual result slip. This is essential for record-keeping, sharing with parents, or for students who need a physical copy of their academic performance.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Download Student's Result Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Results > Download Student's result</strong>.</li>
                                                                                        <li>This will take you to the Individual Result page, which is designed for generating individual student results.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Generate and Download the Result:</strong>
                                                                                    <ul>
                                                                                        <li>On the Individual Result page, you will typically find fields to specify the student ID.</li>
                                                                                        <li>Enter the correct <strong>Student ID</strong> in the provided field and click on <strong>Submit</strong>. The student's result will be automatically downloaded to your device in PDF format.</li>
                                                                                        <li><strong>Important for Users:</strong> Ensure the student ID is accurate to retrieve the correct report card.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingViewUploadedResults">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseViewUploadedResults" aria-expanded="false" aria-controls="collapseViewUploadedResults">
                                                                        How to View Uploaded Results
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseViewUploadedResults" class="accordion-collapse collapse" aria-labelledby="headingViewUploadedResults" data-bs-parent="#resultsManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="view_uploaded_results">
                                                                            <p>The "View Uploaded Results" feature allows teachers and administrators to review all academic results that have been submitted into the EduHive system. This provides a comprehensive overview of student performance across different classes, subjects, and academic periods, enabling easy monitoring and verification of data.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the View Uploaded Results Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Results > View Uploaded Results</strong>.</li>
                                                                                        <li>This will take you to the View Uploaded Result page, which displays a consolidated list of all uploaded results.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Filter and Search Results (Optional):</strong>
                                                                                    <ul>
                                                                                        <li>On the View Uploaded Result page, you will find options to filter the results.</li>
                                                                                        <li>You can typically filter by <strong>Class, Arm</strong>, <strong>Term</strong>, <strong>Subject</strong>.</li>
                                                                                        <li><strong>Tip for Users:</strong> Use these filters to quickly narrow down the vast amount of data to focus on the specific results you need to review. For example, to see all results for "JSS 1" in "Second Term," apply those filters.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Review and Verify Results:</strong>
                                                                                    <ul>
                                                                                        <li>The page will display a table or list of results based on your selections. This typically includes student names, subjects, scores, grades, and the academic period.</li>
                                                                                        <li>Carefully review the displayed results to ensure accuracy and consistency. This is an opportunity to spot any discrepancies before report cards are finalized.</li>
                                                                                        <li><strong>Benefit for Users:</strong> This central viewing area helps in quality control, allowing for quick checks and cross-referencing of student performance data.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingDownloadMastersheet">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDownloadMastersheet" aria-expanded="false" aria-controls="collapseDownloadMastersheet">
                                                                        How to Download Mastersheet
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseDownloadMastersheet" class="accordion-collapse collapse" aria-labelledby="headingDownloadMastersheet" data-bs-parent="#resultsManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="download_mastersheet">
                                                                            <p>The "Download Mastersheet" feature is a powerful tool for administrators and teachers to generate a comprehensive spreadsheet containing all student results for a selected academic period. This mastersheet is invaluable for in-depth analysis, external reporting, archiving, or for use in other data management systems. It provides a consolidated view of all academic performance data.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Download Mastersheet Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Results > Download Mastersheet</strong>.</li>
                                                                                        <li>This will take you to the Mastersheet page, which is dedicated to generating this comprehensive report.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Select Academic Period and Class (Optional):</strong>
                                                                                    <ul>
                                                                                        <li>On the Mastersheet page, you will need to specify the criteria for the mastersheet.</li>
                                                                                        <li>Select the desired <strong>Class, Arm, Term and Academic Session</strong> from the filter options.</li>
                                                                                        <li><strong>Important for Users:</strong> Carefully choose your academic period to ensure the mastersheet contains the correct set of results.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Generate and Download the Mastersheet:</strong>
                                                                                    <ul>
                                                                                        <li>After making your selections, click the "Download" button.</li>
                                                                                        <li>The system will then compile all the relevant student results into a spreadsheet format (PDF). This file will typically include:
                                                                                            <ul>
                                                                                                <li>Student Names.</li>
                                                                                                <li>Scores and grades for all subjects.</li>
                                                                                                <li>Total scores, averages, and other calculated metrics.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>Your browser will then prompt you to save the generated file to your computer.</li>
                                                                                        <li><strong>Benefit for Users:</strong> This feature provides a single, organized file with all academic data, making it easy to perform further analysis.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingRevokeResults">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRevokeResults" aria-expanded="false" aria-controls="collapseRevokeResults">
                                                                        How to Revoke Student Results
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseRevokeResults" class="accordion-collapse collapse" aria-labelledby="headingRevokeResults" data-bs-parent="#resultsManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="revoke_results">
                                                                            <p>The "Revoke Student Results" feature is a critical administrative function, typically restricted to Administrators, that allows for the reversal of approved student results. This action is usually taken in cases of data entry errors, policy changes, or when results need to be re-evaluated. It's a powerful tool that should be used with extreme caution due to its impact on official academic records.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Revoke Student Results Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Results > Revoke Students Results</strong>.</li>
                                                                                        <li>This will take you to the Revoke page, which is the interface for managing result revocations.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Identify Results for Revocation:</strong>
                                                                                    <ul>
                                                                                        <li>On the Revoke page, you will find options to search for or filter student results.</li>
                                                                                        <li>You will need to search for the <strong>Student</strong> (by name, ID, class or arm) for which you intend to revoke results.</li>
                                                                                        <li><strong>Important for Users:</strong> This step requires absolute precision. Double-check all criteria to ensure you are targeting the correct student's results. Revoking the wrong results can lead to significant administrative issues.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Initiate and Confirm Revocation:</strong>
                                                                                    <ul>
                                                                                        <li>Once the specific results are identified, there will be an option to initiate the revocation.</li>
                                                                                        <li>Carefully read the confirmation message and, if you are absolutely certain, click "Confirm" or "Yes" to proceed with the revocation.</li>
                                                                                        <li><strong>Warning:</strong> Once revoked, these results cannot be seen by the student, alumni, or parents except if the revocation is removed. Always ensure proper authorization and backup procedures are in place before performing this action.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingResultsMaintenance">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseResultsMaintenance" aria-expanded="false" aria-controls="collapseResultsMaintenance">
                                                                        How to Run Results Maintenance
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseResultsMaintenance" class="accordion-collapse collapse" aria-labelledby="headingResultsMaintenance" data-bs-parent="#resultsManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="results_maintenance">
                                                                            <p>The "Results Maintenance" feature is an administrative tool, typically accessible only to Administrators, designed to perform various upkeep tasks on the student results database. This can include optimizing data, cleaning up inconsistencies, archiving old results, or running integrity checks to ensure the reliability and performance of the results management system. Regular maintenance helps keep the system running smoothly and accurately.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Results Maintenance Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > Results > Results Maintenance</strong>.</li>
                                                                                        <li>This will take you to the Maintenance page, which is the control panel for results maintenance operations.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Run All Maintenance Tasks:</strong>
                                                                                    <ul>
                                                                                        <li>On the Maintenance page, you will see a single button labeled "Run Maintenance".</li>
                                                                                        <li>Clicking this button will automatically scan for errors and inconsistencies in the results data, including wrong calculations, incorrect grading, ranking and positioning, remarks, and more.</li>
                                                                                        <li>The system will fix all detected issues with a single action, streamlining the maintenance process.</li>
                                                                                        <li><strong>Important for Users:</strong> Always ensure you have a recent backup of your database before running this maintenance task, as it will make changes to your data.</li>
                                                                                        <li><strong>Confirmation:</strong> After completion, a message will indicate whether the maintenance was successful or if any issues remain.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="header ms-4">
                                                <h2><strong>E-Learning</strong> Resources</h2>
                                            </div>
                                            <div class="body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="accordion" id="eLearningResourcesAccordion">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingUploadAssignments">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUploadAssignments" aria-expanded="false" aria-controls="collapseUploadAssignments">
                                                                        How to Upload Assignments
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseUploadAssignments" class="accordion-collapse collapse" aria-labelledby="headingUploadAssignments" data-bs-parent="#eLearningResourcesAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="upload_assignments">
                                                                            <p>The "Upload Assignments" feature allows teachers to create and distribute academic assignments to their students digitally. This streamlines the assignment process, making it easier for students to access tasks and for teachers to manage submissions and grading. It's a core component of the e-learning resources, facilitating interactive learning and assessment.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Upload Assignments Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > E-Learning Resources > Assignments > Upload</strong>.</li>
                                                                                        <li>This will take you to the Upload Assignments page, which is the interface for creating and uploading new assignments.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Fill in Assignment Details:</strong>
                                                                                    <ul>
                                                                                        <li>On the Upload Assignments page, simply select the <strong>Class</strong> and <strong>Subject</strong> for the assignment.</li>
                                                                                        <li>Next, choose the assignment file to upload from your computer.</li>
                                                                                        <li>Once selected, click the "Submit" button to upload the assignment.</li>
                                                                                        <li><strong>Important for Users:</strong> Make sure you select the correct class and subject before submitting.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Upload the Assignment:</strong>
                                                                                    <ul>
                                                                                        <li>After clicking "Submit," the system will process the file and make the assignment available to the selected students.</li>
                                                                                        <li><strong>Confirmation:</strong> A success message will confirm that the assignment has been uploaded and is now accessible to students.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingViewAssignments">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseViewAssignments" aria-expanded="false" aria-controls="collapseViewAssignments">
                                                                        How to View Assignments
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseViewAssignments" class="accordion-collapse collapse" aria-labelledby="headingViewAssignments" data-bs-parent="#eLearningResourcesAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="view_assignments">
                                                                            <p>The "View Assignments" feature provides a centralized hub for both teachers and students to access and manage all academic assignments. For teachers, it's a way to monitor distributed tasks, while for students, it's their primary portal to see what's due, access assignment materials, and track their progress. This feature ensures transparency and easy access to all assignment-related information.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the View Assignments Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, both teachers and students can navigate to <strong>E-Learning Resources > Assignments > View</strong>.</li>
                                                                                        <li>This action will take you to the View Upload Assignments page, which lists all assignments relevant to the logged-in user.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Review the Assignment List:</strong>
                                                                                    <ul>
                                                                                        <li>On the View Upload Assignments page, you will see a list of uploaded assignments. Each entry typically displays:
                                                                                            <ul>
                                                                                                <li><strong>Subject:</strong> The subject for which the assignment was uploaded (e.g., "Mathematics").</li>
                                                                                                <li><strong>Class:</strong> The class assigned to the assignment (e.g., "JSS 2").</li>
                                                                                                <li><strong>Filename:</strong> The name of the uploaded assignment file (e.g., "math_homework_ch5.pdf").</li>
                                                                                                <li><strong>Actions:</strong> For students, options may include "Download" or "View Details." For teachers, options may include "Edit" or "Delete."</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>Tip for Users:</strong> Use the subject and class columns to quickly locate assignments relevant to you. Click the filename to download the assignment file.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Interact with Assignments:</strong>
                                                                                    <ul>
                                                                                        <li><strong>For Students:</strong>
                                                                                            <ul>
                                                                                                <li><strong>Download:</strong> Click the filename to download the assignment file to your device.</li>
                                                                                                <li><strong>View Details:</strong> Access more information about the assignment if available.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>For Teachers:</strong>
                                                                                            <ul>
                                                                                                <li><strong>Edit/Delete:</strong> Update assignment details or remove an assignment if needed.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits
                                                                                        </ul>
                                                                                </li>
                                                                                </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Organization:</strong> Keeps all assignments in one accessible place, reducing clutter and confusion.</li>
                                                                                        <li><strong>Accessibility:</strong> Students can access assignments anytime, anywhere, fostering flexible learning.</li>
                                                                                        <li><strong>Efficiency:</strong> Streamlines the distribution, submission, and management of academic tasks for both educators and learners.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingUploadNotes">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUploadNotes" aria-expanded="false" aria-controls="collapseUploadNotes">
                                                                        How to Upload Notes
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseUploadNotes" class="accordion-collapse collapse" aria-labelledby="headingUploadNotes" data-bs-parent="#eLearningResourcesAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="upload_notes">
                                                                            <p>The "Upload Notes" feature empowers teachers to share supplementary learning materials, lecture summaries, or important study guides directly with their students. This ensures that students have easy access to all necessary resources, enhancing their understanding and preparation for lessons and exams. It's a vital tool for enriching the e-learning experience.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Upload Notes Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > E-Learning Resources > Notes > Upload</strong>.</li>
                                                                                        <li>This will take you to the Upload Notes page, which is the dedicated interface for uploading educational notes.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Fill in Notes Details:</strong>
                                                                                    <ul>
                                                                                        <li>On the Upload Notes page, simply select the <strong>Class</strong> and <strong>Subject</strong> for the notes.</li>
                                                                                        <li>Next, choose the notes file to upload from your computer.</li>
                                                                                        <li>Once selected, click the "Submit" button to upload the notes.</li>
                                                                                        <li><strong>Important for Users:</strong> Make sure you select the correct class and subject before submitting.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Upload the Notes:</strong>
                                                                                    <ul>
                                                                                        <li>After clicking "Submit," the system will process the file and make the notes available to the selected students.</li>
                                                                                        <li><strong>Confirmation:</strong> A success message will confirm that the notes have been uploaded and are now accessible to students.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingViewNotes">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseViewNotes" aria-expanded="false" aria-controls="collapseViewNotes">
                                                                        How to View Notes
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseViewNotes" class="accordion-collapse collapse" aria-labelledby="headingViewNotes" data-bs-parent="#eLearningResourcesAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="view_notes">
                                                                            <p>The "View Notes" feature serves as a central repository where students and teachers can access all uploaded educational notes. This ensures that all learning materials are organized and readily available, supporting student revision and teacher resource management. It's an essential component for a well-structured e-learning environment.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the View Notes Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, both teachers and students can navigate to <strong>E-Learning Resources > Notes > View</strong>.</li>
                                                                                        <li>This action will take you to the View Upload Notes page, which displays a list of all available notes.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Review the Assignment List:</strong>
                                                                                    <ul>
                                                                                        <li>On the View Upload Notes page, you will see a list of uploaded notes. Each entry typically displays:
                                                                                            <ul>
                                                                                                <li><strong>Subject:</strong> The subject for which the notes were uploaded (e.g., "Mathematics").</li>
                                                                                                <li><strong>Class:</strong> The class assigned to the notes (e.g., "JSS 2").</li>
                                                                                                <li><strong>Filename:</strong> The name of the uploaded notes file (e.g., "math_notes_ch5.pdf").</li>
                                                                                                <li><strong>Actions:</strong> For students, options may include "Download" or "View Details." For teachers, options may include "Edit" or "Delete."</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>Tip for Users:</strong> Use the subject and class columns to quickly locate notes relevant to you. Click the filename to download the notes file.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Interact with Notes:</strong>
                                                                                    <ul>
                                                                                        <li><strong>For Students:</strong>
                                                                                            <ul>
                                                                                                <li><strong>Download:</strong> Click the filename to download the notes file to your device.</li>
                                                                                                <li><strong>View Details:</strong> Access more information about the notes if available.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>For Teachers:</strong>
                                                                                            <ul>
                                                                                                <li><strong>Edit/Delete:</strong> Update notes details or remove notes if needed.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Organization:</strong> Keeps all notes in one accessible place, reducing clutter and confusion.</li>
                                                                                        <li><strong>Accessibility:</strong> Students can access notes anytime, anywhere, fostering flexible learning.</li>
                                                                                        <li><strong>Efficiency:</strong> Streamlines the distribution, submission, and management of academic resources for both educators and learners.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingUploadCurriculum">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUploadCurriculum" aria-expanded="false" aria-controls="collapseUploadCurriculum">
                                                                        How to Upload Curriculum
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseUploadCurriculum" class="accordion-collapse collapse" aria-labelledby="headingUploadCurriculum" data-bs-parent="#eLearningResourcesAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="upload_curriculum">
                                                                            <p>The "Upload Curriculum" feature allows educators and administrators to centralize and distribute official curriculum documents, syllabi, and lesson plans. This ensures that all teaching staff and students have access to the most current and approved educational frameworks, promoting consistency in instruction and clarity in learning objectives. It's a foundational tool for academic planning and resource sharing.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Upload Curriculum Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > E-Learning Resources > Curriculum > Upload</strong>.</li>
                                                                                        <li>This will take you to the Upload Curriculum page, which is the interface for submitting curriculum materials.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Fill in Curriculum Details:</strong>
                                                                                    <ul>
                                                                                        <li>On the Upload Curriculum page, simply select the <strong>Class</strong> and <strong>Subject</strong> for the curriculum.</li>
                                                                                        <li>Next, choose the curriculum file to upload from your computer.</li>
                                                                                        <li>Once selected, click the "Submit" button to upload the curriculum.</li>
                                                                                        <li><strong>Important for Users:</strong> Make sure you select the correct class and subject before submitting.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Upload the Curriculum:</strong>
                                                                                    <ul>
                                                                                        <li>After clicking "Submit," the system will process the file and make the curriculum available to the selected students and teachers.</li>
                                                                                        <li><strong>Confirmation:</strong> A success message will confirm that the curriculum has been uploaded and is now accessible.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingViewCurriculum">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseViewCurriculum" aria-expanded="false" aria-controls="collapseViewCurriculum">
                                                                        How to View Curriculum
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseViewCurriculum" class="accordion-collapse collapse" aria-labelledby="headingViewCurriculum" data-bs-parent="#eLearningResourcesAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="view_curriculum">
                                                                            <p>The "View Curriculum" feature provides a centralized and easily accessible platform for teachers, students, and administrators to review all uploaded curriculum documents. This ensures that everyone involved in the educational process is aligned with the current academic standards, learning objectives, and course structures. It's a critical tool for academic transparency and effective educational planning.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the View Curriculum Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, navigate to <strong>E-Learning Resources > Curriculum > View</strong>.</li>
                                                                                        <li>This action will take you to the View Upload Curriculum page, which displays a comprehensive list of all available curriculum materials.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Review the Curriculum List:</strong>
                                                                                    <ul>
                                    
                                                                                        <li>On the View Upload Curriculum page, you will see a list of uploaded curriculum documents. Each entry typically displays:
                                                                                            <ul>
                                                                                                <li><strong>Subject:</strong> The subject for which the curriculum was uploaded (e.g., "Mathematics").</li>
                                                                                                <li><strong>Class:</strong> The class assigned to the curriculum (e.g., "JSS 2").</li>
                                                                                                <li><strong>Filename:</strong> The name of the uploaded curriculum file (e.g., "math_curriculum_2024.pdf").</li>
                                                                                                <li><strong>Actions:</strong> For students, options may include "Download" or "View Details." For teachers/admins, options may include "Edit" or "Delete."</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>Tip for Users:</strong> Use the subject and class columns to quickly locate curriculum relevant to you. Click the filename to download the curriculum file.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Interact with Curriculum Documents:</strong>
                                                                                    <ul>
                                                                                        <li><strong>For Students:</strong>
                                                                                            <ul>
                                                                                                <li><strong>Download:</strong> Click the filename to download the curriculum file to your device.</li>
                                                                                                <li><strong>View Details:</strong> Access more information about the curriculum if available.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>For Teachers/Admins:</strong>
                                                                                            <ul>
                                                                                                <li><strong>Edit/Delete:</strong> Update curriculum details or remove a curriculum if needed.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Organization:</strong> Keeps all curriculum documents in one accessible place, reducing clutter and confusion.</li>
                                                                                        <li><strong>Accessibility:</strong> Students and teachers can access curriculum anytime, anywhere, fostering flexible planning and learning.</li>
                                                                                        <li><strong>Efficiency:</strong> Streamlines the distribution, review, and management of curriculum resources for both educators and learners.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="header ms-4">
                                                <h2><strong>CBT (Computer Based Test)</strong> Management</h2>
                                            </div>
                                            <div class="body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="accordion" id="cbtManagementAccordion">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingAddQuestions">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAddQuestions" aria-expanded="false" aria-controls="collapseAddQuestions">
                                                                        How to Add Questions
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseAddQuestions" class="accordion-collapse collapse" aria-labelledby="headingAddQuestions" data-bs-parent="#cbtManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="add_questions">
                                                                            <p>The "Add Questions" feature is designed for teachers to manually create and input individual questions into the Computer-Based Test (CBT) question bank. This allows for the creation of custom questions, ensuring that assessments are tailored to specific lesson plans, learning objectives, and student needs. It's a fundamental tool for building a robust and flexible CBT system.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Add Questions Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > CBT > Add Questions</strong>.</li>
                                                                                        <li>This will take you to the Add Question page, which is the interface for creating new questions.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Fill in Question Details:</strong>
                                                                                    <ul>
                                                                                        <li>On the Add Question page, you will find a form to enter the details of your question. This typically includes:
                                                                                            <ul>
                                                                                                <li><strong>Question Text:</strong> Type the full question clearly and concisely.</li>
                                                                                                <li><strong>Options:</strong> Provide multiple-choice options (e.g., A, B, C, D). Ensure there is at least one correct answer.</li>
                                                                                                <li><strong>Correct Answer:</strong> Select or specify the correct option among the choices.</li>
                                                                                                <li><strong>Subject:</strong> Categorize the question by subject for better organization and retrieval.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>Important for Users:</strong> Double-check the correct answer selection to avoid errors in grading. Ensure options are distinct and plausible to effectively test student understanding.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Submit the Question:</strong>
                                                                                    <ul>
                                                                                        <li>After carefully filling in all the required details, click the "Submit" or "Add Question" button.</li>
                                                                                        <li>The system will then save the question to the CBT question bank, making it available for inclusion in future computer-based tests.</li>
                                                                                        <li><strong>Confirmation:</strong> A success message will be displayed, confirming that the question has been successfully added.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Customization:</strong> Allows teachers to create questions specific to their teaching content and style.</li>
                                                                                        <li><strong>Flexibility:</strong> Supports the creation of diverse question types and difficulty levels.</li>
                                                                                        <li><strong>Quality Control:</strong> Enables teachers to personally vet and ensure the accuracy of each question.</li>
                                                                                        <li><strong>Building Question Bank:</strong> Contributes to a growing repository of questions that can be reused and combined for various assessments.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingUploadQuestions">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUploadQuestions" aria-expanded="false" aria-controls="collapseUploadQuestions">
                                                                        How to Upload Questions (in Bulk)
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseUploadQuestions" class="accordion-collapse collapse" aria-labelledby="headingUploadQuestions" data-bs-parent="#cbtManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="upload_questions">
                                                                            <p>The "Upload Questions (in Bulk)" feature is an efficient tool for teachers to add a large number of questions to the Computer-Based Test (CBT) question bank simultaneously. Instead of manually entering each question, this feature allows you to prepare all your questions in a structured format (CSV) and upload them in one go. This is particularly useful for creating extensive question banks for major exams or multiple topics.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Bulk Upload Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > CBT > Upload Questions</strong>.</li>
                                                                                        <li>This will take you to the Question Add page, which is specifically designed for bulk question uploads.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Download the Provided Template:</strong>
                                                                                    <ul>
                                                                                        <li>On the Question Add page, you will find an option to "Download Excel Template" or "Download CSV Template."</li>
                                                                                        <li><strong>Important for Users:</strong> Always use this official template. It ensures that your questions are formatted correctly, with designated columns for the question text, multiple-choice options, and the correct answer. <br>
                                                                                            <strong>Note:</strong> The correct answer should be specified as a numeric index. For example, if you have Option A, Option B, Option C, Option D and the correct answer is Option C, then the value for the correct answer field should be <code>3</code>. Using a different format will lead to upload errors.
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Prepare Your Questions in the Template:</strong>
                                                                                    <ul>
                                                                                        <li>Open the downloaded template file.</li>
                                                                                        <li>Carefully fill in your questions, ensuring each question has its text, all possible options (e.g., Option A, Option B, Option C, Option D), and a clear indication of the correct answer index as explained above.</li>
                                                                                        <li><strong>Tip for Users:</strong> Pay close attention to spelling, grammar, and the accuracy of the correct answer. Inconsistent formatting or incorrect answers in the template will directly affect the quality of your CBT.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Upload the Completed Template File:</strong>
                                                                                    <ul>
                                                                                        <li>Once you have filled the template with all your questions, save the file.</li>
                                                                                        <li>Return to the Question Add page and use the "Choose File" or "Browse" button to select your prepared template file from your computer.</li>
                                                                                        <li>Click the "Upload" or "Submit" button to initiate the bulk upload process.</li>
                                                                                        <li><strong>Confirmation:</strong> The system will process the file and typically display a success message, or a report detailing any errors encountered during the upload (e.g., malformed questions, missing answers). Review this report carefully.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Initiating the Exam (Crucial Next Step):</strong>
                                                                                    <ul>
                                                                                        <li>
                                                                                            <strong>Initiate Exam:</strong> After uploading your questions, click the "Initiate Exam" button on the Question Add page. This step prepares the CBT module for exam mode, ensuring the uploaded questions are ready for student access.
                                                                                            <ul>
                                                                                                <li>This action finalizes the exam setup and makes the exam available for scheduling.</li>
                                                                                                <li>Once initiated, proceed to "Set Exam Time/Date" to schedule when students can take the exam.</li>
                                                                                                <li><strong>Note:</strong> Failing to initiate the exam will prevent students from accessing the uploaded questions during the scheduled exam period. In addition, the "Initiate Exam" feature would store the students exam result in the database, for future reference (students can check their CBT scores for previous terms and sessions)</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                    </ul>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Time-Saving:</strong> Drastically reduces the time and effort required to populate the question bank.</li>
                                                                                        <li><strong>Efficiency:</strong> Allows for quick deployment of new assessments.</li>
                                                                                        <li><strong>Scalability:</strong> Ideal for schools or teachers who need to manage a large volume of questions across many subjects and classes.</li>
                                                                                        <li><strong>Consistency:</strong> Helps maintain a uniform structure for questions when using a template.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingModifyQuestions">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseModifyQuestions" aria-expanded="false" aria-controls="collapseModifyQuestions">
                                                                        How to Modify Questions
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseModifyQuestions" class="accordion-collapse collapse" aria-labelledby="headingModifyQuestions" data-bs-parent="#cbtManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="modify_questions">
                                                                            <p>The "Modify Questions" feature allows teachers to edit or update existing questions within the Computer-Based Test (CBT) question bank. This is essential for correcting errors, refining question wording, updating options, or changing the correct answer to ensure the accuracy and relevance of assessment materials. Maintaining an up-to-date question bank is crucial for effective CBTs.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Modify Questions Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > CBT > Modify Questions</strong>.</li>
                                                                                        <li>This will take you to the Adquest page, which is the interface for editing questions.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Search for the Question to Modify:</strong>
                                                                                    <ul>
                                                                                        <li>On the Adquest page, you will typically find a filter option.</li>
                                                                                        <li>Use the available filter options to narrow down your search by <strong>Class</strong>, <strong>Arm</strong>, <strong>Term</strong>, <strong>Academic Session</strong>, and <strong>Subject</strong>. This helps you quickly locate the specific question you wish to edit.</li>
                                                                                        <li><strong>Tip for Users:</strong> Combining filters makes it easier to find the correct question, especially when the question bank is large.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Make Necessary Changes:</strong>
                                                                                    <ul>
                                                                                        <li>Once the question is displayed, its text, options, and correct answer will be presented in editable fields.</li>
                                                                                        <li>Carefully make your desired changes. This could include:
                                                                                            <ul>
                                                                                                <li>Rewording the question for clarity.</li>
                                                                                                <li>Adding, removing, or editing multiple-choice options.</li>
                                                                                                <li>Changing the designated correct answer.</li>
                                                                                                <li>Updating the subject or topic categorization.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>Important Note:</strong> Always verify the accuracy of your modifications, especially the correct answer, before saving. Incorrect questions can invalidate test results.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Save Your Changes:</strong>
                                                                                    <ul>
                                                                                        <li>After making all desired updates, click the "Update" or "Save Changes" button on the form.</li>
                                                                                        <li>The system will then save these modifications, and the question in the CBT question bank will be immediately updated.</li>
                                                                                        <li><strong>Confirmation:</strong> A success message will usually appear, confirming that your changes have been successfully applied.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Accuracy:</strong> Ensures that all questions in the CBT bank are correct and free from errors.</li>
                                                                                        <li><strong>Relevance:</strong> Allows teachers to update questions to reflect current curriculum changes or new teaching methodologies.</li>
                                                                                        <li><strong>Flexibility:</strong> Provides the ability to adapt assessment content without having to delete and re-create questions.</li>
                                                                                        <li><strong>Quality Assurance:</strong> Supports ongoing improvement of the CBT system by enabling continuous refinement of assessment items.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingCheckCBTResults">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCheckCBTResults" aria-expanded="false" aria-controls="collapseCheckCBTResults">
                                                                        How to Check CBT Results
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseCheckCBTResults" class="accordion-collapse collapse" aria-labelledby="headingCheckCBTResults" data-bs-parent="#cbtManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="check_cbt_results">
                                                                            <p>The "Check CBT Results" feature provides teachers and administrators with the ability to review the performance of students on Computer-Based Tests. This allows for quick assessment of student understanding, identification of areas where students might be struggling, and overall evaluation of the effectiveness of the CBTs. It's a crucial step in the feedback and grading process.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Check CBT Results Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > CBT > Check Results</strong>.</li>
                                                                                        <li>This will take you to the Checkcbt page, which is the central hub for viewing CBT outcomes.</li>
                                                                                    </ul>
                                                                                </li>

                                                                                <li><strong>Search and Download Results:</strong>
                                                                                    <ul>
                                                                                        <li>The page provides a search field where you can enter a student's ID to view their individual CBT result.</li>
                                                                                        <li>You can also download the entire result of all students who participated in the selected CBT exam session.</li>
                                                                                        <li><strong>Note for Users:</strong> The CBT Module is disposable—when you click "Initiate Exam," all previous results are cleared and the module prepares for a new exam session. Always download the entire result before using the "Initiate Exam" button again, as this action treats the next exam as a new entity and previous results will be lost.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Review Student Scores and Performance:</strong>
                                                                                    <ul>
                                                                                        <li>Once the exam is selected, the system will display a detailed breakdown of student results. This typically includes:
                                                                                            <ul>
                                                                                                <li>A list of students who took the exam.</li>
                                                                                                <li>Their individual scores or grades.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>Benefit for Users:</strong> This page offers immediate insights into how students performed, helping teachers to quickly identify learning gaps and plan follow-up instruction.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Utilize Results for Feedback and Analysis:</strong>
                                                                                    <ul>
                                                                                        <li>The displayed results can be used to provide targeted feedback to students, inform parents about academic progress, or analyze the effectiveness of teaching methods.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingSetExamTime">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSetExamTime" aria-expanded="false" aria-controls="collapseSetExamTime">
                                                                        How to Set Exam Time/Date
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseSetExamTime" class="accordion-collapse collapse" aria-labelledby="headingSetExamTime" data-bs-parent="#cbtManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="set_exam_time">
                                                                            <p>The "Set Exam Time/Date" feature is a critical administrative function within the CBT management system. It allows teachers and administrators to precisely schedule when a Computer-Based Test will become available to students. This ensures that exams are administered fairly, preventing early access and allowing for synchronized testing across all students. This step is essential after questions have been added or uploaded to the question bank.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Set Exam Time/Date Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Teacher > CBT > Set Exam Time/Date</strong>.</li>
                                                                                        <li>This will take you to the Settime page, which is the control panel for scheduling CBTs.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Choose Class, Arm, Term, Session, and Set Exam Time:</strong>
                                                                                    <ul>
                                                                                        <li>On the Settime page, you will find dropdown menus to select the <strong>Class</strong>, <strong>Arm</strong>, <strong>Term</strong>, and <strong>Academic Session</strong> for which you want to schedule the exam.</li>
                                                                                        <li>Next, select the <strong>Exam Date</strong> from the calendar control.</li>
                                                                                        <li>Enter the <strong>Exam Time</strong> in minutes (e.g., enter <code>60</code> for a 1-hour exam).</li>
                                                                                        <li>After filling all fields, click the "Save" button to schedule the exam for the selected class and arm.</li>
                                                                                        <li><strong>Important for Users:</strong> Double-check all selections and the entered time to ensure the exam is scheduled correctly for the intended group and duration.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Customization:</strong> Allows precise scheduling for different classes and arms.</li>
                                                                                        <li><strong>Fairness:</strong> Ensures all students in the selected group have equal access to the exam at the same time.</li>
                                                                                        <li><strong>Control:</strong> Teachers and administrators can set exact exam dates and durations.</li>
                                                                                        <li><strong>Organization:</strong> Simplifies management of multiple CBTs by clearly defining their schedules.</li>
                                                                                        <li><strong>Security:</strong> Prevents unauthorized early access and ensures exam integrity.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="header ms-4">
                                                <h2><strong>Tuckshop</strong> Management</h2>
                                            </div>
                                            <div class="body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="accordion" id="tuckshopManagementAccordion">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingRegisterTuckshopUser">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRegisterTuckshopUser" aria-expanded="false" aria-controls="collapseRegisterTuckshopUser">
                                                                        How to Register a Tuckshop User
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseRegisterTuckshopUser" class="accordion-collapse collapse" aria-labelledby="headingRegisterTuckshopUser" data-bs-parent="#tuckshopManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="register_tuckshop_user">
                                                                            <p>The "Register Tuckshop User" feature allows administrators or authorized personnel to create new user accounts specifically for managing tuckshop operations. This ensures that only designated staff members have access to the Point of Sale (POS) system, inventory management, and other tuckshop-related functionalities, enhancing security and accountability.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the User Registration Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>TuckShop > Tuck Shop > Register</strong>.</li>
                                                                                        <li>This will take you to the Regtuck page, which is the interface for creating new tuckshop user accounts.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Enter Student ID and Fetch Details Automatically:</strong>
                                                                                    <ul>
                                                                                        <li>On the Regtuck page, enter the <strong>Student ID</strong> in the designated field.</li>
                                                                                        <li>The system will automatically retrieve the student's details from the database, including:
                                                                                            <ul>
                                                                                                <li><strong>Student Name</strong></li>
                                                                                                <li><strong>Current Session</strong></li>
                                                                                                <li><strong>Class</strong></li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>These details will be displayed on the form for confirmation.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Enter Recharge Amount:</strong>
                                                                                    <ul>
                                                                                        <li>In the <strong>Balance</strong> field, enter the amount the student is recharging their account with.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Register Student:</strong>
                                                                                    <ul>
                                                                                        <li>After confirming all details and entering the recharge amount, click the "Register Student" button.</li>
                                                                                        <li>The system will create the tuckshop account for the student and update their balance accordingly.</li>
                                                                                        <li><strong>Confirmation:</strong> A success message will appear, confirming the registration and balance update.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Automation:</strong> Reduces manual entry and errors by fetching student details automatically.</li>
                                                                                        <li><strong>Efficiency:</strong> Streamlines the process of registering students and managing their tuckshop balances.</li>
                                                                                        <li><strong>Accuracy:</strong> Ensures student information is always up-to-date and correct.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingTuckshopPOS">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTuckshopPOS" aria-expanded="false" aria-controls="collapseTuckshopPOS">
                                                                        How to Make Tuckshop Sales (POS)
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseTuckshopPOS" class="accordion-collapse collapse" aria-labelledby="headingTuckshopPOS" data-bs-parent="#tuckshopManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="tuckshop_pos">
                                                                            <p>The "Sales (POS)" feature is the core of the tuckshop operations, allowing staff to efficiently process sales of items to students or other customers. This Point of Sale (POS) system is designed to be intuitive, ensuring quick transactions and accurate record-keeping. Think of it as a digital cash register tailored for the school tuckshop environment.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the POS Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the main dashboard, locate the sidebar menu on the left.</li>
                                                                                        <li>Click on <strong>TuckShop</strong>, then navigate to <strong>Tuck Shop</strong>, and finally select <strong>POS</strong>.</li>
                                                                                        <li>This action will take you to the Selling Point page, which is your primary interface for making sales.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Search for Student:</strong>
                                                                                    <ul>
                                                                                        <li>At the top of the Selling Point page, use the search field to enter the Student ID or name.</li>
                                                                                        <li>If the student is registered in the tuckshop database, their details will be displayed, including their available balance.</li>
                                                                                        <li><strong>Tip for Users:</strong> Always verify the student's identity and balance before proceeding with the sale.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Select Products for Purchase:</strong>
                                                                                    <ul>
                                                                                        <li>Use the product search field to find items in the inventory and add them to the cart.</li>
                                                                                        <li>Each selected product will appear in the cart, where you can specify the quantity for each item.</li>
                                                                                        <li>The system will automatically calculate the subtotal for each item and the total cart balance.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Checkout and Payment:</strong>
                                                                                    <ul>
                                                                                        <li>When ready to checkout, the system compares the student's available balance with the cart total.</li>
                                                                                        <li>
                                                                                            <strong>If the balance is sufficient:</strong> The cart total is deducted from the student's balance, the transaction is completed, inventory is updated, and a receipt is generated.
                                                                                        </li>
                                                                                        <li>
                                                                                            <strong>If the balance is insufficient:</strong> The transaction is flagged and cannot proceed. The user must either recharge the student's account or reduce the cart total by removing items.
                                                                                        </li>
                                                                                        <li><strong>Important for Users:</strong> The system will not allow checkout until the student has enough funds to cover the purchase.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Accurate Balance Management:</strong> Ensures students cannot overspend and all transactions are properly logged.</li>
                                                                                        <li><strong>Security:</strong> Prevents unauthorized purchases and maintains financial integrity.</li>
                                                                                        <li><strong>Efficiency:</strong> Streamlines the sales process and automates inventory and balance updates.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingTuckshopInventory">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTuckshopInventory" aria-expanded="false" aria-controls="collapseTuckshopInventory">
                                                                        How to Manage Tuckshop Inventory (Add, Update, Delete Products)
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseTuckshopInventory" class="accordion-collapse collapse" aria-labelledby="headingTuckshopInventory" data-bs-parent="#tuckshopManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="tuckshop_inventory">
                                                                            <p>The "Tuckshop Inventory Management" feature is essential for keeping track of all products available for sale in the tuckshop. It allows authorized staff to add new items, update existing product details (like price or stock levels), and remove products that are no longer sold. Effective inventory management ensures that popular items are always in stock and helps prevent waste.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Inventory Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>TuckShop > Tuck Shop > Inventory</strong>.</li>
                                                                                        <li>This will take you to the Inventory page, which is your central hub for all product management.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>To Add a New Product:</strong>
                                                                                    <ul>
                                                                                        <li>On the Inventory page You will be presented with a form to enter details for the new item:</li>
                                                                                        <li>
                                                                                            <ul>
                                                                                                <li><strong>Product Name:</strong> A clear and descriptive name (e.g., "Apple Juice Box," "Chocolate Bar").</li>
                                                                                                <li><strong>Location:</strong> The Product location in the store/shop.</li>
                                                                                                <li><strong>Unit Price:</strong> The Purchase price of the product.</li>
                                                                                                <li><strong>Sell Price:</strong> The selling price of the product.</li>
                                                                                                <li><strong>Quantity:</strong> The quantity of the product currently available.</li>
                                                                                                <li><strong>Description (Optional):</strong> Any additional details about the product.</li>
                                                                                                <li><strong>Reorder Level:</strong> The minimum stock quantity at which you should reorder the product to avoid running out.</li>
                                                                                                <li><strong>Reorder Quantity:</strong> The quantity to order when the stock reaches the reorder level.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>After filling in all required details, click "Save Product." The new item will then be added to your inventory.</li>
                                                                                        <li><strong>Important for Users:</strong> Ensure accurate pricing and initial stock levels to avoid financial discrepancies and stock errors.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>To Update an Existing Product:</strong>
                                                                                    <ul>
                                                                                        <li>On the Inventory page, you will see a list of all current products.</li>
                                                                                        <li>Locate the product you wish to modify. There will typically be an "Edit" button or icon next to each product entry. Click it.</li>
                                                                                        <li>The product's current details will be displayed in an editable form. Make the necessary changes (e.g., update the price, adjust the stock level after a new delivery, correct a product name).</li>
                                                                                        <li>After making your modifications, click "Update Product." The system will then update the product's record in the database.</li>
                                                                                        <li><strong>Tip for Users:</strong> Regularly update stock levels after receiving new supplies or after significant sales periods to maintain accurate inventory counts.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>To Delete a Product:</strong>
                                                                                    <ul>
                                                                                        <li>On the Inventory page, find the product you want to remove from the inventory list.</li>
                                                                                        <li>Click the "Delete" button or icon associated with that product.</li>
                                                                                        <li>The system will usually ask for a confirmation (e.g., "Are you sure you want to delete this product?"). This is a safety measure to prevent accidental deletions.</li>
                                                                                        <li>Confirm the deletion to permanently remove the product from your inventory.</li>
                                                                                        <li><strong>Warning:</strong> Deleting a product is usually irreversible. Ensure you have proper authorization and consider the implications before deleting.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Stock Control:</strong> Provides real-time visibility into product availability, preventing stockouts or overstocking.</li>
                                                                                        <li><strong>Accuracy:</strong> Ensures that product information (prices, descriptions) is always current.</li>
                                                                                        <li><strong>Efficiency:</strong> Streamlines the process of managing a diverse range of tuckshop items.</li>
                                                                                        <li><strong>Financial Tracking:</strong> Accurate inventory data is crucial for financial reporting and profit analysis.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingTuckshopSuppliers">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTuckshopSuppliers" aria-expanded="false" aria-controls="collapseTuckshopSuppliers">
                                                                        How to Manage Tuckshop Suppliers
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseTuckshopSuppliers" class="accordion-collapse collapse" aria-labelledby="headingTuckshopSuppliers" data-bs-parent="#tuckshopManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="tuckshop_suppliers">
                                                                            <p>The "Manage Tuckshop Suppliers" feature is crucial for maintaining a well-stocked tuckshop by effectively managing relationships and information with product suppliers. This module allows authorized personnel to add new suppliers, update their contact details, and remove suppliers who are no longer in use. Accurate supplier information ensures smooth procurement and inventory replenishment.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Suppliers Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>TuckShop > Tuck Shop > Suppliers</strong>.</li>
                                                                                        <li>This will take you to the Supplier page, which is the central interface for all supplier management.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>To Add a New Supplier:</strong>
                                                                                    <ul>
                                                                                        <li>On the Supplier page You will be provided a form to fill in details for the new supplier:.</li>
                                                                                        <li>
                                                                                            <ul>
                                                                                                <li><strong>Supplier/Business Name:</strong> The official name of the supplier company.</li>
                                                                                                <li><strong>Phone Number:</strong> The supplier's contact phone number.</li>
                                                                                                <li><strong>Email Address:</strong> The supplier's email for communication.</li>
                                                                                                <li><strong>Address:</strong> The physical address of the supplier.</li>
                                                                                                <li><strong>Products Supplied (Optional):</strong> A brief note on what products they provide.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>After entering the information, click "Save" The new supplier will then be added to your records.</li>
                                                                                        <li><strong>Important for Users:</strong> Accurate contact information is vital for placing orders and resolving any supply chain issues.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>To Modify Existing Supplier Details:</strong>
                                                                                    <ul>
                                                                                        <li>On the Supplier page, you will see a list of all registered suppliers.</li>
                                                                                        <li>Locate the supplier whose details you wish to update. There will typically be an "Edit" button or icon next to their entry. Click it.</li>
                                                                                        <li>The supplier's current information will be displayed in an editable form. Make any necessary changes (e.g., update a phone number, change a contact person).</li>
                                                                                        <li>After making your modifications, click "Update" The system will update the supplier's record.</li>
                                                                                        <li><strong>Tip for Users:</strong> Regularly review supplier information to ensure it is current, especially after any changes in their business operations.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>To Remove a Supplier:</strong>
                                                                                    <ul>
                                                                                        <li>On the Supplier page, find the supplier you want to remove from your records.</li>
                                                                                        <li>Click the "Delete" button or icon associated with that supplier.</li>
                                                                                        <li>The system will usually ask for a confirmation (e.g., "Are you sure you want to remove this supplier?"). Confirm the action to permanently delete the supplier's record.</li>
                                                                                        <li><strong>Warning:</strong> Ensure all outstanding orders are resolved before deletion.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Organized Procurement:</strong> Centralizes all supplier information, making it easy to manage purchasing.</li>
                                                                                        <li><strong>Improved Communication:</strong> Ensures quick access to correct contact details for efficient communication.</li>
                                                                                        <li><strong>Supply Chain Efficiency:</strong> Helps in maintaining a reliable network of suppliers for tuckshop products.</li>
                                                                                        <li><strong>Record-Keeping:</strong> Provides a clear record of all suppliers the tuckshop works with.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingTuckshopDashboard">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTuckshopDashboard" aria-expanded="false" aria-controls="collapseTuckshopDashboard">
                                                                        How to View Tuckshop Dashboard
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseTuckshopDashboard" class="accordion-collapse collapse" aria-labelledby="headingTuckshopDashboard" data-bs-parent="#tuckshopManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="tuckshop_dashboard">
                                                                            <p>The "Tuckshop Dashboard" feature provides an at-a-glance overview of the tuckshop's operational performance and key metrics. This dashboard is designed to give managers and administrators quick insights into sales trends, inventory status, and overall financial health, enabling informed decision-making and efficient management of tuckshop activities.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Tuckshop Dashboard Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>TuckShop > Tuck Shop > Dashboard</strong>.</li>
                                                                                        <li>This will take you to the Tuckdashboard page, which is the central display for tuckshop analytics.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Review Key Metrics and Overview:</strong>
                                                                                    <ul>
                                                                                        <li>On the Tuckdashboard page, you will find various widgets and charts displaying important metrics, including:
                                                                                            <ul>
                                                                                                <li><strong>Total Registered Students:</strong> The total number of students registered for tuckshop services.</li>
                                                                                                <li><strong>Total Students Balance:</strong> The combined balance of all student tuckshop accounts.</li>
                                                                                                <li><strong>Low Balance:</strong> Number of students whose account balance is below the defined threshold.</li>
                                                                                                <li><strong>Total Sales:</strong> The total value of all sales made through the tuckshop.</li>
                                                                                                <li><strong>Total Transactions:</strong> The total number of sales transactions processed.</li>
                                                                                                <li><strong>Inventory Quantity:</strong> The total quantity of all products currently in stock.</li>
                                                                                                <li><strong>Inventory Sum:</strong> The total monetary value of all inventory items.</li>
                                                                                                <li><strong>Out of Stock Products:</strong> Number of products that have zero quantity in inventory.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>Benefit for Users:</strong> This dashboard provides real-time, actionable insights into tuckshop operations, enabling managers to make informed decisions, optimize inventory, monitor sales trends, and quickly address issues such as low balances or out-of-stock products.</li>
                                                                                    </ul>
                                                                                </li>

                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Real-time Monitoring:</strong> Provides up-to-date information on tuckshop operations.</li>
                                                                                        <li><strong>Data-Driven Decisions:</strong> Empowers managers to make informed choices about inventory and pricing.</li>
                                                                                        <li><strong>Efficiency:</strong> Reduces the need for manual data compilation and analysis.</li>
                                                                                        <li><strong>Transparency:</strong> Offers a clear and concise overview of tuckshop performance to all authorized stakeholders.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingTuckshopTransactions">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTuckshopTransactions" aria-expanded="false" aria-controls="collapseTuckshopTransactions">
                                                                        How to View and Manage Tuckshop Transactions
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseTuckshopTransactions" class="accordion-collapse collapse" aria-labelledby="headingTuckshopTransactions" data-bs-parent="#tuckshopManagementAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="tuckshop_transactions">
                                                                            <p>The "Tuckshop Transactions" feature provides a detailed log of every sale made through the Point of Sale (POS) system. This comprehensive transaction history is crucial for financial reconciliation, auditing, resolving students' queries, and analyzing sales patterns. It offers a transparent and searchable record of all tuckshop activities.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Transactions Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>TuckShop > Tuck Shop > Transactions</strong>.</li>
                                                                                        <li>This will take you to the Transactions page, which is the central repository for all sales records.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>View the List of All Sales:</strong>
                                                                                    <ul>
                                                                                        <li>On the Transactions page, you will see a chronological list of all completed sales transactions. Each entry typically includes:
                                                                                            <ul>
                                                                                                <li><strong>Transaction ID:</strong> The unique transaction identifier.</li>
                                                                                                <li><strong>Student ID:</strong> The ID of the student who made the purchase.</li>
                                                                                                <li><strong>Student Name:</strong> The name of the student.</li>
                                                                                                <li><strong>Product:</strong> The name of the product sold.</li>
                                                                                                <li><strong>Description:</strong> Details about the product.</li>
                                                                                                <li><strong>Units:</strong> Quantity of product purchased.</li>
                                                                                                <li><strong>Amount:</strong> Total amount for the transaction.</li>
                                                                                                <li><strong>Date:</strong> Date and time of the transaction.</li>
                                                                                                <li><strong>Cashier:</strong> The tuckshop user who processed the sale.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li><strong>Benefit for Users:</strong> This detailed list provides a clear overview of all sales activities, making it easy to track individual transactions.</li>
                                                                                    </ul>
                                                                                </li>

                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Financial Accountability:</strong> Provides a complete audit trail for all sales, simplifying financial reporting and reconciliation.</li>
                                                                                        <li><strong>Customer Service:</strong> Quickly resolve student or parent queries regarding purchases.</li>
                                                                                        <li><strong>Sales Analysis:</strong> Offers data for understanding popular products, peak sales times, and overall revenue performance.</li>
                                                                                        <li><strong>Error Correction:</strong> Helps in identifying any discrepancies in sales records.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="header ms-4">
                                                <h2><strong>General</strong> Administration</h2>
                                            </div>
                                            <div class="body">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="accordion" id="generalAdministrationAccordion">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingClassSchedule">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClassSchedule" aria-expanded="false" aria-controls="collapseClassSchedule">
                                                                        How to Use the Class Schedule
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseClassSchedule" class="accordion-collapse collapse" aria-labelledby="headingClassSchedule" data-bs-parent="#generalAdministrationAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="class_schedule">
                                                                            <p>The "Class Schedule" feature is a vital tool for administrators and teachers to organize and manage the school's academic timetable. It allows for the creation, editing, and viewing of class schedules across different grades, subjects, and academic sessions. An effective class schedule ensures optimal utilization of resources (classrooms, teachers) and provides clarity for students and parents regarding their daily academic structure.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Class Schedule Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Administrator > Class Schedule</strong>.</li>
                                                                                        <li>This will take you to the Timetable page, which is the central interface for managing all class schedules.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Create a New Class Schedule:</strong>
                                                                                    <ul>
                                                                                        <li>On the Timetable page, you will typically find an option to "Create New Schedule".</li>
                                                                                        <li>You will then need to input details suchs as:
                                                                                            <ul>
                                                                                                <li><strong>Day:</strong> Select the day of the week for the class (e.g., "Monday").</li>
                                                                                                <li><strong>Class:</strong> Select the class (e.g., "JSS 1").</li>
                                                                                                <li><strong>Arm:</strong> Select the arm (e.g., "A, B, C...").</li>
                                                                                                <li><strong>Subject:</strong> Select the subject being taught.</li>
                                                                                                <li><strong>Start Time:</strong> Specify the start time for the class.</li>
                                                                                                <li><strong>End Time:</strong> Specify the end time for the class.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>After entering all details, click "Save".</li>
                                                                                        <li><strong>Important for Users:</strong> Careful planning is required to avoid conflicts (e.g., two classes scheduled in the same room at the same time, or a teacher assigned to two different classes simultaneously).</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Edit an Existing Class Schedule:</strong>
                                                                                    <ul>
                                                                                        <li>To modify a schedule, locate the specific class entry on the Timetable page.</li>
                                                                                        <li>There will usually be an "Edit" button or icon next to each schedule entry. Click it.</li>
                                                                                        <li>Make the necessary changes (e.g., change a time slot, assign a different teacher, update a classroom).</li>
                                                                                        <li>Click "Update" to apply your modifications.</li>
                                                                                    </ul>
                                                                                </li>

                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Organization:</strong> Provides a structured and clear overview of all academic activities.</li>
                                                                                        <li><strong>Resource Optimization:</strong> Helps in efficiently allocating classrooms and teacher time.</li>
                                                                                        <li><strong>Conflict Prevention:</strong> Designed to minimize scheduling conflicts.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingAcademicCalendar">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAcademicCalendar" aria-expanded="false" aria-controls="collapseAcademicCalendar">
                                                                        How to Use the Academic Calendar
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseAcademicCalendar" class="accordion-collapse collapse" aria-labelledby="headingAcademicCalendar" data-bs-parent="#generalAdministrationAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="academic_calendar">
                                                                            <p>The "Academic Calendar" feature is a comprehensive tool designed to help school administrators and staff effectively plan, manage, and communicate all important dates and events throughout the academic year. This includes scheduling exams, marking holidays, planning school activities, and noting parent-teacher conferences. A well-maintained academic calendar ensures that all stakeholders—students, parents, and staff—are aware of upcoming events and deadlines, promoting better organization and communication within the school community.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Academic Calendar Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Administrator > Calendar</strong>.</li>
                                                                                        <li>This will take you to the Calendar page, which is your central hub for managing all school events.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Add a New Event to the Calendar:</strong>
                                                                                    <ul>
                                                                                        <li>On the Calendar page, you will then be prompted to enter details for the new event:</li>
                                                                                        <li>
                                                                                            <ul>
                                                                                                <li><strong>Date:</strong> Specify the date of the event. For multi-day events, provide both start and end dates.</li>
                                                                                                <li><strong>Title:</strong> Enter a clear and concise name for the event (e.g., "Mid-Term Exams," "Public Holiday," "Annual Sports Day").</li>
                                                                                                <li><strong>Description (Optional):</strong> Add any additional details or notes about the event.</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>After filling in all the required details, click "Save Event" or "Add." The event will then appear on the calendar.</li>
                                                                                        <li><strong>Important for Users:</strong> Double-check the dates and event title to ensure accuracy. This information is critical for school-wide planning and communication.</li>
                                                                                    </ul>
                                                                                </li>

                                                                                <li><strong>Edit or Delete Existing Events:</strong>
                                                                                    <ul>
                                                                                        <li>To modify an event, click on it within the calendar. An "Edit" option will typically appear.</li>
                                                                                        <li>Make the necessary changes (e.g., adjust dates, update description, change title) and click "Save Changes."</li>
                                                                                        <li>To delete an event, click on it and select the "Delete" option. The system will usually ask for confirmation before permanent removal.</li>
                                                                                        <li><strong>Warning:</strong> Deleting an event is usually irreversible. Ensure you have proper authorization and confirm the correct event before proceeding.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Centralized Planning:</strong> All school events are managed from a single, accessible location.</li>
                                                                                        <li><strong>Improved Communication:</strong> Provides a clear schedule for students, parents, and staff, reducing confusion.</li>
                                                                                        <li><strong>Efficient Resource Allocation:</strong> Helps in planning the use of school facilities and personnel.</li>
                                                                                        <li><strong>Timely Reminders:</strong> Ensures important deadlines and activities are not missed.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingManageSubjects">
                                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseManageSubjects" aria-expanded="false" aria-controls="collapseManageSubjects">
                                                                        How to Manage Subjects
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseManageSubjects" class="accordion-collapse collapse" aria-labelledby="headingManageSubjects" data-bs-parent="#generalAdministrationAccordion">
                                                                    <div class="accordion-body">
                                                                        <div id="manage_subjects">
                                                                            <p>The "Manage Subjects" feature is a fundamental administrative tool that allows school staff to define, organize, and maintain the list of academic subjects offered within the EduHive system. This ensures that the curriculum is accurately reflected, enabling proper assignment of subjects to classes for use in result processing. Effective subject management is crucial for the structural integrity of the academic system.</p>
                                                                            <ol>
                                                                                <li><strong>Navigate to the Manage Subjects Page:</strong>
                                                                                    <ul>
                                                                                        <li>From the sidebar menu, go to <strong>Administrator > Subjects</strong>.</li>
                                                                                        <li>This will take you to the Subjects page, which is the central interface for all subject-related operations.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Add a New Subject:</strong>
                                                                                    <ul>
                                                                                        <li>On the Subjects page, look for an option (e.g., a button) to "Add New Subject" or "Create Subject."</li>
                                                                                        <li>You will typically be prompted to enter details for the new subject:
                                                                                            <ul>
                                                                                                <li><strong>Subject Name:</strong> The full name of the subject (e.g., "Mathematics," "English Language," "Integrated Science").</li>
                                                                                            </ul>
                                                                                        </li>
                                                                                        <li>After entering the required information, click "Save Subject" or "Add." The new subject will then be added to the school's official list.</li>
                                                                                        <li><strong>Important for Users:</strong> Ensure the subject name is clear and consistent with school standards.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Modify an Existing Subject:</strong>
                                                                                    <ul>
                                                                                        <li>On the Subjects page, you will see a list of all currently defined subjects.</li>
                                                                                        <li>Locate the subject you wish to modify. There will typically be an "Edit" button or icon next to each subject entry. Click it.</li>
                                                                                        <li>The subject's current details will be displayed in editable fields. Make any necessary changes (e.g., correct a spelling error, update the description, change the subject code).</li>
                                                                                        <li>After making your modifications, click "Update" or "Save Changes." The system will then update the subject's record.</li>
                                                                                        <li><strong>Tip for Users:</strong> Any changes made here will reflect across the system where this subject is used (e.g., class schedules, result uploads).</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Remove a Subject:</strong>
                                                                                    <ul>
                                                                                        <li>On the Subjects page, find the subject you want to remove from the school's offerings.</li>
                                                                                        <li>Click the "Delete" button or icon associated with that subject.</li>
                                                                                        <li>The system will usually ask for a confirmation (e.g., "Are you sure you want to delete this subject?"). This is a safety measure.</li>
                                                                                        <li>Confirm the deletion to permanently remove the subject from the system.</li>
                                                                                        <li><strong>Warning:</strong> Deleting a subject can impact historical data (e.g., past results linked to this subject). Ensure proper archiving or data migration procedures are followed if necessary.</li>
                                                                                    </ul>
                                                                                </li>
                                                                                <li><strong>Benefits of this Feature:</strong>
                                                                                    <ul>
                                                                                        <li><strong>Curriculum Accuracy:</strong> Ensures the system accurately reflects the school's academic offerings.</li>
                                                                                        <li><strong>Streamlined Operations:</strong> Simplifies the process of assigning subjects to classes and teachers.</li>
                                                                                        <li><strong>Data Consistency:</strong> Maintains uniform subject information across all modules.</li>
                                                                                        <li><strong>Flexibility:</strong> Allows the school to easily adapt its subject offerings as curriculum needs evolve.</li>
                                                                                    </ul>
                                                                                </li>
                                                                            </ol>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion" id="generalAdministrationAccordion">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingSystemSettings">
                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSystemSettings" aria-expanded="false" aria-controls="collapseSystemSettings">
                                                                            How to Configure System Settings
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseSystemSettings" class="accordion-collapse collapse" aria-labelledby="headingSystemSettings" data-bs-parent="#generalAdministrationAccordion">
                                                                        <div class="accordion-body">
                                                                            <div id="system_settings">
                                                                                <p>The "System Settings" feature is a critical administrative module that allows Administrators to configure and customize various system-wide parameters of the EduHive application. This includes general application settings, academic year configurations, security parameters, and other crucial administrative controls. Proper configuration ensures the system operates according to the school's policies and academic structure, providing a tailored and efficient user experience.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the System Settings Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Administrator > Settings</strong>.</li>
                                                                                            <li>This will take you to the Admin page, which is the central control panel for all system configurations.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Configure Academic Settings:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Set Current Term:</strong> Use the dropdown menu to select the current academic term (e.g., "First Term," "Second Term," "Third Term"). After making your selection, click the "Update" button to save the change.</li>
                                                                                            <li><strong>Set Current Session:</strong> Enter the current academic session in the format <code>XXXX/XXXX</code> (e.g., "2023/2024") in the provided field. Click "Update" to apply the new session.</li>
                                                                                            <li><strong>Set Next Term Start Date:</strong> Select the date the next term begins using the date picker. This helps in scheduling and calendar management.</li>
                                                                                            <li><strong>Promote Students:</strong> Use the "Promote Students" option to advance students to the next academic class and arm at the end of a session or term. This ensures student records are updated for the new academic period.</li>
                                                                                            <li><strong>Manage Academic Arms and Classes:</strong> Configure or update the available academic arms (e.g., "A, B, C") and classes (e.g., "JSS 1," "SSS 2") to match your school's structure.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Customization:</strong> Allows the school to tailor the EduHive system to its unique academic structure and policies.</li>
                                                                                            <li><strong>Operational Efficiency:</strong> Streamlines administrative processes such as term/session updates and student promotions.</li>
                                                                                            <li><strong>Accuracy:</strong> Ensures all system-wide settings are current, reducing errors in academic records and reporting.</li>
                                                                                            <li><strong>Control:</strong> Provides administrators with centralized authority over critical system parameters.</li>
                                                                                            <li><strong>Scalability:</strong> Easily adapts to changes in school size, structure, or academic calendar.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingUserControl">
                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUserControl" aria-expanded="false" aria-controls="collapseUserControl">
                                                                            How to Use User Control
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseUserControl" class="accordion-collapse collapse" aria-labelledby="headingUserControl" data-bs-parent="#generalAdministrationAccordion">
                                                                        <div class="accordion-body">
                                                                            <div id="user_control">
                                                                                <p>The "User Control" feature is a powerful administrative module that allows Administrators to manage all user accounts and their associated roles within the EduHive system. This includes creating new user accounts, assigning specific roles (such as Teacher, Admission Officer, Bursary Staff, or Tuck Shop officer) and modifying existing user details. Robust user control ensures system security, proper access levels, and accountability for all actions performed within the platform.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the User Control Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Administrator > User Control</strong>.</li>
                                                                                            <li>This will take you to the Usercontrol page, which is the central interface for managing all user accounts.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Create a New User Account:</strong>
                                                                                        <ul>
                                                                                            <li>On the Usercontrol page, You will be prompted to enter details for the new user:</li>
                                                                                            <li>
                                                                                                <ul>
                                                                                                    <li><strong>Staff Name:</strong> The full name of the staff member.</li>
                                                                                                    <li><strong>Username:</strong> A unique identifier for the user.</li>
                                                                                                    <li><strong>Password:</strong> A secure password for login.</li>
                                                                                                    <li><strong>Role:</strong> Select the appropriate role for the user (e.g., "Teacher," "Admission," "Bursary," "Parent," "Administrator"). This role defines their permissions and access rights within the system.</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                            <li>After filling in all required details and assigning a role, click "Submit" or "Create Account." The new user account will then be active.</li>
                                                                                            <li><strong>Important for Users:</strong> Carefully consider the principle of least privilege—assign users only the roles and permissions absolutely necessary for their job function to enhance security.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Modify Existing User Details:</strong>
                                                                                        <ul>
                                                                                            <li>On the Usercontrol page, you will see a list of all registered users.</li>
                                                                                            <li>Locate the user whose details you wish to modify. There will typically be an "Edit" button or icon next to each user entry. Click it.</li>
                                                                                            <li>The user's current information will be displayed in editable fields. You can update their name, contact details, or even change their assigned role if their responsibilities shift.</li>
                                                                                            <li>After making your modifications, click "Update". The system will then update the user's record.</li>
                                                                                            <li><strong>Tip for Users:</strong> Always verify changes with the user or relevant department before updating critical information like roles or contact details.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Delete User Accounts:</strong>
                                                                                        <ul>
                                                                                            <li>To manage a user's access, locate their account on the Usercontrol page.</li>
                                                                                            <li>You will find button to "Delete" the account.
                                                                                                <ul>
                                                                                                    <li><strong>Delete:</strong> This permanently removes the user account and potentially associated data. This action is usually irreversible.</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                            <li>The system will typically ask for a confirmation before deletion.</li>
                                                                                            <li><strong>Warning:</strong> Exercise extreme caution when deleting accounts, as it can lead to data loss and impact historical records.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Security:</strong> Controls who can access the system and what actions they can perform.</li>
                                                                                            <li><strong>Compliance:</strong> Helps in adhering to data privacy and access control regulations.</li>
                                                                                            <li><strong>Operational Efficiency:</strong> Streamlines the onboarding and offboarding of staff and students.</li>
                                                                                            <li><strong>Accountability:</strong> Ensures that all system activities are traceable to specific users and roles.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingSendNotice">
                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSendNotice" aria-expanded="false" aria-controls="collapseSendNotice">
                                                                            How to Send Notice to Parents
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseSendNotice" class="accordion-collapse collapse" aria-labelledby="headingSendNotice" data-bs-parent="#generalAdministrationAccordion">
                                                                        <div class="accordion-body">
                                                                            <div id="send_notice">
                                                                                <p>The "Send Notice to Parents" feature is a vital communication tool that allows administrators and authorized staff to disseminate important announcements, updates, or urgent messages directly to parents. This ensures timely and consistent communication, keeping parents informed about school events, policy changes, student progress, or any other relevant information.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the Send Notice Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Administrator > Send Notice to Parents</strong>.</li>
                                                                                            <li>This will take you to the Send Notice page, which is the interface for composing and sending notices.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Compose Your Message:</strong>
                                                                                        <ul>
                                                                                            <li>On the Send Notice page, you will find fields to compose your notice:
                                                                                                <ul>
                                                                                                    <li><strong>Title:</strong> Enter a clear and concise title for the notice (e.g., "Parent-Teacher Conference," "School Holiday").</li>
                                                                                                    <li><strong>Message:</strong> Type the full content of your notice. Make sure it is clear and provides all necessary information.</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                            <li><strong>Important for Users:</strong> Always proofread your message for clarity, grammar, and accuracy before sending. Misinformation can cause confusion.</li>
                                                                                        </ul>
                                                                                    </li>

                                                                                    <li><strong>Send the Notice:</strong>
                                                                                        <ul>
                                                                                            <li>After composing your message and selecting the target audience, click the "Send Notice" button.</li>
                                                                                            <li>The system will then send the notice to the selected parents. Notices will be displayed on the parent dashboard system.</li>
                                                                                            <li><strong>Confirmation:</strong> A success message will usually appear, confirming that the notice has been successfully sent.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Efficient Communication:</strong> Reaches a large number of parents quickly and simultaneously.</li>
                                                                                            <li><strong>Consistency:</strong> Ensures all parents receive the same official information.</li>
                                                                                            <li><strong>Record-Keeping:</strong> Creates a digital record of all communications sent to parents.</li>
                                                                                            <li><strong>Engagement:</strong> Keeps parents actively involved and informed about their child's education and school activities.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingAlumniList">
                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAlumniList" aria-expanded="false" aria-controls="collapseAlumniList">
                                                                            How to Manage Alumni List
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseAlumniList" class="accordion-collapse collapse" aria-labelledby="headingAlumniList" data-bs-parent="#generalAdministrationAccordion">
                                                                        <div class="accordion-body">
                                                                            <div id="alumni_list">
                                                                                <p>The "Alumni List" feature provides a dedicated section for managing records of former students who have graduated or left the institution. This is an invaluable resource for maintaining connections with alumni, organizing alumni events, tracking their achievements, and fostering a strong school community beyond graduation. It serves as a historical database of past students.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the Alumni List Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Administrator > Alumni List</strong>.</li>
                                                                                            <li>This will take you to the Alumni List page, which is the central repository for all alumni records.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>View Alumni Records:</strong>
                                                                                        <ul>
                                                                                            <li>On the Alumni List page, you will see a comprehensive list of former students. Each entry typically includes:
                                                                                                <ul>
                                                                                                    <li><strong>Student Name:</strong> The full name of the alumnus.</li>
                                                                                                    <li><strong>Gender:</strong> The gender of the alumnus.</li>
                                                                                                    <li><strong>Mobile:</strong> The mobile phone number of the alumnus.</li>
                                                                                                    <li><strong>Email:</strong> The email address of the alumnus.</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                            <li><strong>Benefit for Users:</strong> This page offers a quick way to access information about former students, which can be useful for outreach or verification purposes.</li>
                                                                                        </ul>
                                                                                    </li>

                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Community Building:</strong> Facilitates maintaining a strong connection with former students.</li>
                                                                                            <li><strong>Networking Opportunities:</strong> Can be used to connect current students with successful alumni.</li>
                                                                                            <li><strong>Fundraising:</strong> Provides a database for alumni outreach and support initiatives.</li>
                                                                                            <li><strong>Historical Data:</strong> Serves as a valuable archive of the school's past student body.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingDiscussionThreadsManagement">
                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDiscussionThreadsManagement" aria-expanded="false" aria-controls="collapseDiscussionThreadsManagement">
                                                                            Discussion Threads Management
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseDiscussionThreadsManagement" class="accordion-collapse collapse" aria-labelledby="headingDiscussionThreadsManagement" data-bs-parent="#generalAdministrationAccordion">
                                                                        <div class="accordion-body">
                                                                            <div id="view_threads">
                                                                                <h5>How to View Discussion Threads</h5>
                                                                                <p>The "View Discussion Threads" feature provides a centralized platform for users to browse and access all active discussion forums or threads within the EduHive system. This is essential for fostering communication, collaboration, and knowledge sharing among students, teachers, and administrators. It allows users to stay updated on ongoing discussions and participate in relevant conversations.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the View Discussion Threads Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Administrator > Discussion Threads > Threads</strong>.</li>
                                                                                            <li>This will take you to the Threads page, which is the central hub for viewing all discussion threads.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Review the List of Active Threads:</strong>
                                                                                        <ul>
                                                                                            <li>On the Threads page, you will see a comprehensive list of all active discussion threads. Each entry typically includes:
                                                                                                <ul>
                                                                                                    <li><strong>Thread Title/Subject:</strong> A clear title indicating the topic of discussion.</li>
                                                                                                    <li><strong>Creator:</strong> The user who initiated the thread.</li>
                                                                                                    <li><strong>Date Created:</strong> The date and time when the thread was originally started.</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                            <li><strong>Benefit for Users:</strong> This list helps you quickly identify discussions that are relevant to your interests or responsibilities.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Access and Participate in Threads:</strong>
                                                                                        <ul>
                                                                                            <li>Click on the title of any thread to open it and view all messages and replies within that discussion.</li>
                                                                                            <li>From within an open thread, you can read messages, compose replies, and engage with other participants.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Centralized Communication:</strong> Provides a single platform for organized discussions.</li>
                                                                                            <li><strong>Knowledge Sharing:</strong> Facilitates the exchange of ideas and information among users.</li>
                                                                                            <li><strong>Community Building:</strong> Fosters a sense of community and collaboration within the school.</li>
                                                                                            <li><strong>Transparency:</strong> Allows all authorized users to see ongoing discussions.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                            <div id="create_thread">
                                                                                <h5>How to Create a Discussion Thread</h5>
                                                                                <p>The "Create Discussion Thread" feature allows users (teachers, administrators, or even students) to initiate new discussion topics within the EduHive system. This is the starting point for any new conversation, question, or collaborative project, enabling users to share ideas, seek help, or discuss academic subjects in an organized forum.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the Create Thread Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Administrator > Discussion Threads > Create Thread</strong>.</li>
                                                                                            <li>This will take you to the Create_thread page, which is the interface for starting new discussions.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Fill in Thread Details:</strong>
                                                                                        <ul>
                                                                                            <li>On the Create_thread page, you will find fields to define your new discussion thread:
                                                                                                <ul>
                                                                                                    <li><strong>Subject/Title:</strong> A clear and descriptive title that summarizes the topic of your discussion (e.g., "Questions about Photosynthesis," "Planning for the School Fair").</li>
                                                                                                    <li><strong>Content/Initial Post:</strong> Type your initial message, question, or prompt for the discussion. Provide enough detail to clearly articulate the purpose of the thread.</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                            <li><strong>Important for Users:</strong> A well-defined subject and clear initial post will encourage more relevant and productive discussions.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Submit to Create the Thread:</strong>
                                                                                        <ul>
                                                                                            <li>After composing your initial post and filling in all necessary details, click the "Post".</li>
                                                                                            <li>The system will then publish your new discussion thread, making it visible to the designated audience.</li>
                                                                                            <li><strong>Confirmation:</strong> A success message will usually appear, confirming that your thread has been successfully created.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Initiates Discussion:</strong> Provides a structured way to start new conversations.</li>
                                                                                            <li><strong>Organized Communication:</strong> Keeps discussions focused on specific topics.</li>
                                                                                            <li><strong>Collaboration:</strong> Encourages interaction and idea exchange among users.</li>
                                                                                            <li><strong>Knowledge Repository:</strong> Builds a searchable archive of discussions that can be referenced later.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                            <div id="read_message">
                                                                                <h5>How to Read a Message in a Thread</h5>
                                                                                <p>The "Read Message in a Thread" feature allows users to access and review the full content of any discussion thread within the EduHive system. This is crucial for following conversations, understanding context, and preparing to contribute to ongoing discussions. It provides a clear view of all posts and replies within a specific topic.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the View Discussion Threads Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Administrator > Discussion Threads > Threads</strong>.</li>
                                                                                            <li>This will take you to the Threads page, which lists all active discussion threads.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Select and Open a Thread:</strong>
                                                                                        <ul>
                                                                                            <li>On the Threads page, you will see a list of discussion threads. Each thread will have a title or subject.</li>
                                                                                            <li>Click on the <strong>title of the specific thread</strong> you wish to read.</li>
                                                                                            <li><strong>Important for Users:</strong> Choose a thread that interests you or is relevant to your class/role to start.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>View All Messages and Replies:</strong>
                                                                                        <ul>
                                                                                            <li>Clicking on a thread will take you to the View_thread page.</li>
                                                                                            <li>On this page, you will see the initial post of the thread, followed by all subsequent replies, usually displayed in chronological order.</li>
                                                                                            <li>You can scroll through the messages to read the entire conversation.</li>
                                                                                            <li><strong>Benefit for Users:</strong> This page provides the complete context of the discussion, allowing you to catch up on what has been said and understand the flow of ideas.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Full Context:</strong> Provides a comprehensive view of all communications within a topic.</li>
                                                                                            <li><strong>Information Retrieval:</strong> Allows users to easily find and review past discussions.</li>
                                                                                            <li><strong>Preparation for Participation:</strong> Helps users understand the conversation before contributing their own thoughts.</li>
                                                                                            <li><strong>Learning Resource:</strong> Discussions can serve as valuable learning resources, especially for students reviewing topics.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                            <div id="reply_message">
                                                                                <h5>How to Reply to a Message in a Thread</h5>
                                                                                <p>The "Reply to a Message in a Thread" feature enables users to actively participate in ongoing discussions by posting their responses, questions, or additional information. This fosters interactive learning and collaborative problem-solving, allowing for a dynamic exchange of ideas within the EduHive discussion forums.</p>
                                                                                <ol>
                                                                                    <li><strong>Access an Existing Discussion Thread:</strong>
                                                                                        <ul>
                                                                                            <li>First, navigate to the <strong>View Discussion Threads</strong> page (Threads) and click on the title of the thread you wish to reply to.</li>
                                                                                            <li>This will open the thread on the View_thread page, where you can see all existing messages.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Locate the Reply Option:</strong>
                                                                                        <ul>
                                                                                            <li>While viewing the thread on the View_thread page, scroll to the bottom or look for a designated text input area.</li>
                                                                                            <li><strong>Important for Users:</strong> The exact location might vary slightly based on devices, but it's usually clearly marked to encourage interaction.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Compose Your Reply:</strong>
                                                                                        <ul>
                                                                                            <li>Type your response, question, or comment. Ensure your reply is relevant to the discussion and contributes constructively.</li>
                                                                                            <li><strong>Tip for Users:</strong> Be clear and concise. If you are responding to a specific point, you might quote the original message for clarity.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Submit Your Reply:</strong>
                                                                                        <ul>
                                                                                            <li>After composing your message, click the "Post" button.</li>
                                                                                            <li>The system will then add your reply to the discussion thread, making it visible to all other participants.</li>
                                                                                            <li><strong>Confirmation:</strong> A success message will usually appear, confirming that your reply has been successfully posted.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Active Participation:</strong> Enables users to engage directly with discussion topics.</li>
                                                                                            <li><strong>Collaborative Learning:</strong> Facilitates the exchange of ideas and peer-to-peer support.</li>
                                                                                            <li><strong>Feedback Mechanism:</strong> Allows teachers to provide feedback and students to ask questions.</li>
                                                                                            <li><strong>Dynamic Content:</strong> Keeps discussions fresh and evolving with new contributions.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                            <div id="edit_thread">
                                                                                <h5>How to Edit a Discussion Thread</h5>
                                                                                <p>The "Edit Discussion Thread" feature allows the creator of a thread (or an administrator with appropriate permissions) to modify the title or initial post of an existing discussion. This is useful for correcting errors, updating information, or refining the focus of a discussion to ensure clarity and accuracy for all participants.</p>
                                                                                <ol>
                                                                                    <li><strong>Access the Discussion Thread:</strong>
                                                                                        <ul>
                                                                                            <li>First, navigate to the <strong>View Discussion Threads</strong> page (Threads) and click on the title of the thread you wish to edit.</li>
                                                                                            <li>This will open the thread on the Thread page.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Locate the Edit Option:</strong>
                                                                                        <ul>
                                                                                            <li>While viewing the thread, look for an "Edit" button or icon, typically associated with the initial post or the thread settings. This option is usually only visible to the thread creator or administrators.</li>
                                                                                            <li><strong>Important for Users:</strong> If you don't see an "Edit" option, it means you do not have the necessary permissions to modify that specific thread.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Make Desired Changes:</strong>
                                                                                        <ul>
                                                                                            <li>Clicking the "Edit" option will take you to the Edit Thread page.</li>
                                                                                            <li>Here, you will find editable fields for the thread's subject/title and its initial content.</li>
                                                                                            <li>Carefully make your desired modifications. This could include rewording the title, adding more details to the initial post, or correcting any mistakes.</li>
                                                                                            <li><strong>Tip for Users:</strong> Ensure your edits maintain the integrity of the discussion and are communicated clearly if they significantly alter the thread's original purpose.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Save Your Changes:</strong>
                                                                                        <ul>
                                                                                            <li>After making all necessary modifications, click the "Update" button.</li>
                                                                                            <li>The system will then save these changes, and the discussion thread will be immediately updated with the new information.</li>
                                                                                            <li><strong>Confirmation:</strong> A success message will usually appear, confirming that your changes have been successfully applied.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Accuracy:</strong> Allows for correction of errors in thread titles or initial posts.</li>
                                                                                            <li><strong>Clarity:</strong> Helps in refining the focus of a discussion for better engagement.</li>
                                                                                            <li><strong>Flexibility:</strong> Provides the ability to adapt thread content as discussions evolve or new information becomes available.</li>
                                                                                            <li><strong>Quality Control:</strong> Ensures that discussion forums remain well-organized and relevant.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                            <div id="delete_thread">
                                                                                <h5>How to Delete a Discussion Thread</h5>
                                                                                <p>The "Delete Discussion Thread" feature allows authorized users (typically the thread creator or administrators) to permanently remove an entire discussion thread from the EduHive system. This action should be performed with extreme caution, as it is usually irreversible and will remove all associated messages and replies. It's generally used for removing irrelevant, inappropriate, or outdated discussions.</p>
                                                                                <ol>
                                                                                    <li><strong>Access the Discussion Thread:</strong>
                                                                                        <ul>
                                                                                            <li>First, navigate to the <strong>View Discussion Threads</strong> page (Threads) and locate the thread you wish to delete.</li>
                                                                                            <li>You might need to open the thread (on View Thread) to find the delete option, or it might be available directly from the list of threads.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Locate the Delete Option:</strong>
                                                                                        <ul>
                                                                                            <li>Look for a "Delete" button or icon associated with the discussion thread. This option is typically only visible to users with deletion permissions.</li>
                                                                                            <li><strong>Important for Users:</strong> If you don't see a "Delete" option, you do not have the necessary permissions.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Confirm and Execute Deletion:</strong>
                                                                                        <ul>
                                                                                            <li>Clicking the "Delete" option will typically trigger a strong warning or confirmation prompt (e.g., "Are you sure you want to delete this thread? This action is irreversible and will remove all messages within it.").</li>
                                                                                            <li>Carefully read the confirmation message. If you are absolutely certain, click "Confirm" or "Yes" to proceed with the deletion. This action utilizes the Delete Thread functionality in the backend.</li>
                                                                                            <li><strong>Warning:</strong> Once confirmed, the entire discussion thread, including all its messages and replies, will be permanently removed from the EduHive database. Ensure you have any necessary approvals or backups before performing this action.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature (when used judiciously):</strong>
                                                                                        <ul>
                                                                                            <li><strong>Content Moderation:</strong> Helps in removing inappropriate or off-topic discussions.</li>
                                                                                            <li><strong>System Cleanup:</strong> Allows for the removal of outdated or irrelevant threads, keeping the forum organized.</li>
                                                                                            <li><strong>Data Management:</strong> Contributes to maintaining a clean and relevant discussion board.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingParentManagement">
                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseParentManagement" aria-expanded="false" aria-controls="collapseParentManagement">
                                                                            Parent Management
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseParentManagement" class="accordion-collapse collapse" aria-labelledby="headingParentManagement" data-bs-parent="#generalAdministrationAccordion">
                                                                        <div class="accordion-body">
                                                                            <div id="register_parents">
                                                                                <h5>How to Register Parents</h5>
                                                                                <p>The "Register Parents" feature allows administrators or authorized staff to create new user accounts for parents within the EduHive system. This is a crucial step for enabling parent access to student information (like results and bursary), facilitating communication, and allowing them to participate in the school community. Each parent account is a gateway for them to stay informed and engaged.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the Register Parents Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Administrator > Parents > Register Parents</strong>.</li>
                                                                                            <li>This will take you to the Register Parent page, which is the interface for creating new parent accounts.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Fill in Parent Details and Create Login Credentials:</strong>
                                                                                        <ul>
                                                                                            <li>On the Register Parent page, you will find a form to enter the new parent's information. This typically includes:
                                                                                                <ul>
                                                                                                    <li><strong>Full Name:</strong> Automatically fetched from the database after searching for the parent (cannot be edited).</li>
                                                                                                    <li><strong>Mobile:</strong> Automatically fetched from the database (cannot be edited).</li>
                                                                                                    <li><strong>Username:</strong> Enter a unique username for the parent account.</li>
                                                                                                    <li><strong>Password:</strong> Enter a secure password for login.</li>
                                                                                                    <li><strong>Note:</strong> The parent registration form appears below the table after searching for the parent and clicking the "Register" button next to their record.</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                            <li><strong>Important for Users:</strong> Advise parents to keep their password secure.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Submit to Create the Parent Account:</strong>
                                                                                        <ul>
                                                                                            <li>After filling in all the required details, click the "Submit" or "Create Account" button.</li>
                                                                                            <li>The system will then create the new parent account, making it active and ready for use.</li>
                                                                                            <li><strong>Confirmation:</strong> A success message will usually appear, confirming that the parent account has been successfully created. The next crucial step is to assign students to this parent.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Parent Engagement:</strong> Provides parents with direct access to school information and their child's progress.</li>
                                                                                            <li><strong>Streamlined Communication:</strong> Facilitates official communication between the school and parents.</li>
                                                                                            <li><strong>Accountability:</strong> Ensures secure and individualized access for each parent.</li>
                                                                                            <li><strong>Data Management:</strong> Integrates parent information into the overall school management system.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                            <div id="delete_parents">
                                                                                <h5>How to Delete Parents</h5>
                                                                                <p>The "Delete Parents" feature allows authorized administrators to permanently remove parent accounts from the EduHive system. This action should be performed with extreme caution, as it is usually irreversible and will remove the parent's access and potentially their historical communication records. It's typically used when a parent no longer has children enrolled in the school or in cases of data cleanup.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the Delete Parents Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Administrator > Parents > Delete Parents</strong>.</li>
                                                                                            <li>This will take you to the Delete Parent page, which is the interface for managing parent account deletions.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Search for the Parent Account to Delete:</strong>
                                                                                        <ul>
                                                                                            <li>On the Delete Parent page, you will typically find a search bar or filter options.</li>
                                                                                            <li>Enter the parent's name, email address, or other identifying information to locate the specific account you wish to remove.</li>
                                                                                            <li><strong>Important for Users:</strong> Double-check your selection carefully. Deleting the wrong parent account can lead to significant administrative issues and loss of access for legitimate parents.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Confirm and Execute Deletion:</strong>
                                                                                        <ul>
                                                                                            <li>Once the specific parent account is identified, there will be a "Delete" button.</li>
                                                                                            <li>The system will almost certainly present a strong warning or confirmation prompt (e.g., "Are you sure you want to delete this parent account? This action is irreversible."). This is a crucial safety measure.</li>
                                                                                            <li>Carefully read the confirmation message and, if you are absolutely certain, click or "Yes" to proceed with the deletion.</li>
                                                                                            <li><strong>Warning:</strong> Once confirmed, the selected parent account and its associated data will be permanently removed from the EduHive database. Ensure you have any necessary approvals or backups before performing this action. Consider deactivating an account instead of deleting it if there's a possibility of future re-enrollment or if historical data needs to be preserved.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature (when used judiciously):</strong>
                                                                                        <ul>
                                                                                            <li><strong>Data Hygiene:</strong> Helps in removing outdated or irrelevant parent accounts from the system.</li>
                                                                                            <li><strong>Security:</strong> Ensures that only active and authorized parents have access to the platform.</li>
                                                                                            <li><strong>Compliance:</strong> Assists in adhering to data retention and privacy policies.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                            <div id="assign_students_to_parents">
                                                                                <h5>How to Assign Students to Parents</h5>
                                                                                <p>The "Assign Students to Parents" feature is a critical function that links student records to their respective parent accounts within the EduHive system. This linkage is essential for parents to gain access to their child's academic progress, attendance records, and school communications. It establishes the digital connection that empowers parents to stay informed and involved in their child's education.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the Assign Students Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Administrator > Parents > Assign Students</strong>.</li>
                                                                                            <li>This will take you to the Assign Students page, which is the interface for creating these crucial links.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Select the Parent and Student(s):</strong>
                                                                                        <ul>
                                                                                            <li>On the Assign Students page, you will typically find two main selection areas:
                                                                                                <ul>
                                                                                                    <li><strong>Select Parent:</strong> Use a dropdown menu or search bar to find and select the parent account you wish to link.</li>
                                                                                                    <li><strong>Select Student(s):</strong> Then, choose the child or children who need to be associated with this parent. You might be able to select multiple students if the parent has more than one child in the school.</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                            <li><strong>Important for Users:</strong> Accuracy is paramount here. Ensure you are linking the correct student(s) to the correct parent account to prevent unauthorized access to sensitive student data.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Establish the Assignment:</strong>
                                                                                        <ul>
                                                                                            <li>After selecting both the parent and the student(s), click the "Assign" button.</li>
                                                                                            <li>The system will then establish the connection in the database, granting the selected parent access to the linked student's information.</li>
                                                                                            <li><strong>Confirmation:</strong> A success message will usually appear, confirming that the student(s) have been successfully assigned to the parent.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Secure Access:</strong> Ensures parents can only view information for their own children.</li>
                                                                                            <li><strong>Enhanced Communication:</strong> Allows targeted communication to parents about their specific child.</li>
                                                                                            <li><strong>Parental Involvement:</strong> Empowers parents with real-time access to their child's academic journey.</li>
                                                                                            <li><strong>Data Integrity:</strong> Maintains accurate relationships between students and their guardians within the system.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                            <div id="unassign_students_from_parents">
                                                                                <h5>How to Unassign Students from Parents</h5>
                                                                                <p>The "Unassign Students from Parents" feature allows authorized administrators to remove the linkage between a student's record and a parent's account. This action is necessary when a student leaves the school, a parent's guardianship changes, or in cases where an incorrect assignment was made. It ensures that parent access to student information is accurately maintained and revoked when no longer appropriate.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the Unassign Students Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Administrator > Parents > Unassign Students</strong>.</li>
                                                                                            <li>This will take you to the Unassign Students page, which is the interface for managing these linkages.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Select the Parent and Student to Unassign:</strong>
                                                                                        <ul>
                                                                                            <li>On the Unassign Students page, you will typically find selection fields:
                                                                                                <ul>
                                                                                                    <li><strong>Search using the student name or parent name:</strong> Choose the parent account from which you want to unassign a student.</li>

                                                                                                </ul>
                                                                                            </li>
                                                                                            <li><strong>Important for Users:</strong> Carefully verify your selections to ensure you are unassigning the correct student from the correct parent. Incorrect unassignment could lead to a parent losing access to their child's legitimate information or, conversely, retaining access when they shouldn't.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Confirm and Execute Unassignment:</strong>
                                                                                        <ul>
                                                                                            <li>After selecting both the parent and the student, click the "Unassign" button.</li>
                                                                                            <li>The system will usually prompt you with a confirmation message (e.g., "Are you sure you want to unassign this student from this parent?"). This is a safety measure.</li>
                                                                                            <li>Carefully read the confirmation message and, if you are absolutely certain, click "Confirm" or "Yes" to proceed with the unassignment.</li>
                                                                                            <li><strong>Warning:</strong> Once confirmed, the link between the student and parent will be broken, and the parent will no longer have access to that student's information through their EduHive account.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Data Security:</strong> Ensures that access to student information is always current and authorized.</li>
                                                                                            <li><strong>Privacy Compliance:</strong> Helps in adhering to data privacy regulations by revoking access when no longer needed.</li>
                                                                                            <li><strong>System Accuracy:</strong> Maintains accurate relationships between students and their guardians.</li>
                                                                                            <li><strong>Flexibility:</strong> Allows for adjustments to parent-student linkages as family or enrollment circumstances change.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="headingProfileMessagingManagement">
                                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProfileMessagingManagement" aria-expanded="false" aria-controls="collapseProfileMessagingManagement">
                                                                            Profile & Messaging Management
                                                                        </button>
                                                                    </h2>
                                                                    <div id="collapseProfileMessagingManagement" class="accordion-collapse collapse" aria-labelledby="headingProfileMessagingManagement" data-bs-parent="#generalAdministrationAccordion">
                                                                        <div class="accordion-body">
                                                                            <div id="profile_page">
                                                                                <h5>How to Use the Profile Page</h5>
                                                                                <p>The "Profile Page" feature provides each user (students, teachers, administrators, and parents) with a personalized section to view and manage their personal information, update security settings, and access account-specific functionalities. It serves as a central hub for individual user data and preferences within the EduHive system.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to Your Profile Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the navbar menu (on your top right), look for an option like <strong>"Profile"</strong> or your name/avatar.</li>
                                                                                            <li>Clicking this will take you to your specific profile page.
                                                                                            </li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Review and Update Personal Details:</strong>
                                                                                        <ul>
                                                                                            <li>On your profile page, you will typically see various sections displaying your personal information. This might include:
                                                                                                <ul>
                                                                                                    <li><strong>Basic Information:</strong> Name, date of birth, gender, contact details (email, phone number).</li>
                                                                                                    <li><strong>Address:</strong> Your current residential address.</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                            <li>You can directly modify fields to update your information.</li>
                                                                                            <li><strong>Tip for Users:</strong> Keep your contact information up-to-date so the school can reach you with important announcements or in emergencies.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Manage Account-Specific Settings:</strong>
                                                                                        <ul>
                                                                                            <li>Beyond personal details, the profile page often includes options to manage security and preferences:
                                                                                                <ul>
                                                                                                    <li><strong>Change Password:</strong> A critical security feature. Always use a strong, unique password and change it regularly.</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Save Your Changes:</strong>
                                                                                        <ul>
                                                                                            <li>After making any updates to your personal details or account settings, locate and click the "Update Profile" button.</li>
                                                                                            <li>The system will then apply these modifications to your user account.</li>
                                                                                            <li><strong>Confirmation:</strong> A success message will usually appear, confirming that your profile has been successfully updated.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Personalization:</strong> Allows users to maintain their own accurate and current information.</li>
                                                                                            <li><strong>Security:</strong> Provides tools for users to manage their login credentials and protect their accounts.</li>
                                                                                            <li><strong>Control:</strong> Empowers users to customize their experience and communication preferences.</li>
                                                                                            <li><strong>Efficiency:</strong> Centralizes individual user management, reducing administrative overhead.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                            <div id="create_message">
                                                                                <h5>How to Create and Send an Email (Internal Message)</h5>
                                                                                <p>The "Create and Send an Email" feature (often referred to as an internal messaging system) allows users within the EduHive platform to compose and send private messages to other registered users. This facilitates direct and secure communication between students, teachers, administrators, and parents, without needing to use external email clients. It's ideal for personal inquiries, sharing sensitive information, or direct feedback.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the Create Message Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the sidebar menu, go to <strong>Profile & Messaging > Create Message</strong>.</li>
                                                                                            <li>This will take you to the Create Message page, which is the interface for composing new internal messages.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Select Recipient(s):</strong>
                                                                                        <ul>
                                                                                            <li>On the Create Message page, you will find a field labeled "To" or "Recipient."</li>
                                                                                            <li>You can typically search for and select a registered user (e.g., a specific teacher, an administrator, or a student) from a list.</li>
                                                                                            <li><strong>Important for Users:</strong> Ensure you select the correct recipient to avoid sending private messages to unintended individuals.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Enter Subject and Compose Message:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Subject:</strong> Provide a clear and concise subject line that summarizes the content of your message (e.g., "Question about Homework," "Meeting Request," "Feedback on Project").</li>
                                                                                            <li><strong>Message Content:</strong> Type the full body of your message in the designated text area. Be clear, respectful, and provide all necessary details.</li>
                                                                                            <li><strong>Important for Users:</strong> Always proofread your message for clarity, grammar, and accuracy before sending. Misinformation can cause confusion.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Send the Message:</strong>
                                                                                        <ul>
                                                                                            <li>After composing your message and selecting the recipient(s), click the "Send Message" button.</li>
                                                                                            <li>The system will then deliver your message to the recipient(s)' EduHive inbox.</li>
                                                                                            <li><strong>Confirmation:</strong> A success message will usually appear, confirming that your message has been successfully sent.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Direct Communication:</strong> Enables private, one-on-one or group messaging within the platform.</li>
                                                                                            <li><strong>Security and Privacy:</strong> Keeps sensitive communications within the secure EduHive environment.</li>
                                                                                            <li><strong>Record-Keeping:</strong> Creates a digital trail of all internal communications.</li>
                                                                                            <li><strong>Efficiency:</strong> Streamlines communication without relying on external email systems.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                            <div id="inbox">
                                                                                <h5>How to Use the Inbox</h5>
                                                                                <p>The "Inbox" feature serves as the central receiving area for all internal messages sent to a user within the EduHive system. It functions like a personal mailbox, allowing users to view, read, and manage all incoming communications from other students, teachers, administrators, or parents. Regularly checking the inbox ensures that users stay informed about important updates and personal messages.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to Your Inbox:</strong>
                                                                                        <ul>
                                                                                            <li>From the navbar menu, go to <strong>Inbox</strong>.</li>
                                                                                            <li>This will take you to the Inbox page, which displays a list of all messages you have received.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Review Incoming Messages:</strong>
                                                                                        <ul>
                                                                                            <li>On the Inbox page, you will see a list of messages, typically ordered by date (newest first). Each entry usually includes:
                                                                                                <ul>
                                                                                                    <li><strong>Sender:</strong> The name of the user who sent the message.</li>
                                                                                                    <li><strong>Subject:</strong> The subject line of the message.</li>
                                                                                                    <li><strong>Date/Time:</strong> When the message was received.</li>
                                                                                                    <li><strong>Status:</strong> Often indicates if the message is "Unread" or "Read."</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                            <li><strong>Tip for Users:</strong> Pay attention to the sender and subject line to prioritize which messages to read first.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Read a Message:</strong>
                                                                                        <ul>
                                                                                            <li>To view the full content of a message, simply click on its <strong>subject line</strong> or the sender's name.</li>
                                                                                            <li>This will open the message, displaying the full text. The message's status will typically change from "Unread" to "Read."</li>
                                                                                            <li>From within the opened message, you may also find options to "Reply" to the sender.</li>
                                                                                        </ul>
                                                                                    </li>

                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Centralized Communication:</strong> All incoming internal messages are in one easy-to-access location.</li>
                                                                                            <li><strong>Timely Information:</strong> Ensures users receive important updates and personal communications promptly.</li>
                                                                                            <li><strong>Organization:</strong> Helps users manage their communications effectively.</li>
                                                                                            <li><strong>Record-Keeping:</strong> Provides a history of received messages for future reference.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                            <div id="sent_messages">
                                                                                <h5>How to View Sent Messages</h5>
                                                                                <p>The "Sent Messages" feature provides users with a record of all internal messages they have composed and sent to other users within the EduHive system. This acts as a personal outbox, allowing users to review past communications, confirm delivery, or retrieve information they have previously shared. It's an important tool for maintaining a complete communication history.</p>
                                                                                <ol>
                                                                                    <li><strong>Navigate to the Sent Messages Page:</strong>
                                                                                        <ul>
                                                                                            <li>From the navbar menu, go to <strong>Sent Messages</strong>.</li>
                                                                                            <li>This will take you to the Sent Message page, which displays a list of all messages you have sent.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Review Your Sent Communications:</strong>
                                                                                        <ul>
                                                                                            <li>On the Sent Message page, you will see a chronological list of all messages you have sent. Each entry typically includes:
                                                                                                <ul>
                                                                                                    <li><strong>Recipient(s):</strong> The user(s) to whom the message was sent.</li>
                                                                                                    <li><strong>Subject:</strong> The subject line of your sent message.</li>
                                                                                                    <li><strong>Date/Time:</strong> When the message was sent.</li>
                                                                                                    <li><strong>Status:</strong> This indicates if the message has been "Read" by the recipient.</li>
                                                                                                </ul>
                                                                                            </li>
                                                                                            <li><strong>Benefit for Users:</strong> This page is useful for confirming that a message was indeed sent, or for quickly recalling the content of a past communication.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>View Full Message Content:</strong>
                                                                                        <ul>
                                                                                            <li>To review the full content of a sent message, simply click on its <strong>subject line</strong>.</li>
                                                                                            <li>This will open the message, displaying the full text and any attachments you included.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                    <li><strong>Benefits of this Feature:</strong>
                                                                                        <ul>
                                                                                            <li><strong>Record-Keeping:</strong> Provides a comprehensive history of all your outgoing internal communications.</li>
                                                                                            <li><strong>Verification:</strong> Allows you to confirm that messages were sent successfully.</li>
                                                                                            <li><strong>Reference:</strong> Enables easy retrieval of information or instructions you have previously shared.</li>
                                                                                            <li><strong>Accountability:</strong> Helps in tracking your communication activities within the system.</li>
                                                                                        </ul>
                                                                                    </li>
                                                                                </ol>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>


    <!-- Jquery Core Js -->
    <script src="assets/bundles/libscripts.bundle.js"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) -->
    <script src="assets/bundles/vendorscripts.bundle.js"></script> <!-- slimscroll, waves Scripts Plugin Js -->

    <script src="assets/bundles/jvectormap.bundle.js"></script> <!-- JVectorMap Plugin Js -->
    <script src="assets/bundles/sparkline.bundle.js"></script> <!-- Sparkline Plugin Js -->
    <script src="assets/bundles/c3.bundle.js"></script>

    <script src="assets/bundles/mainscripts.bundle.js"></script>
    <script src="assets/js/pages/index.js"></script>
    <script src="assets/bootstrap/js/bootstrap.bundle.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#leftsidebar .menu .list a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        // Find the parent accordion-collapse element
                        const accordionCollapse = targetElement.closest('.accordion-collapse');
                        if (accordionCollapse) {
                            const bsCollapse = new bootstrap.Collapse(accordionCollapse, {
                                toggle: false
                            });
                            bsCollapse.show();

                            // Scroll to the target element after a short delay to allow accordion to open
                            setTimeout(() => {
                                targetElement.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }, 300); // Adjust delay as needed
                        } else {
                            // If not inside an accordion, just scroll
                            targetElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }
                });
            });

            // Back to Top Button functionality
            var mybutton = document.getElementById("backToTopBtn");

            // When the user scrolls down 20px from the top of the document, show the button
            window.onscroll = function() {
                scrollFunction()
            };

            function scrollFunction() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    mybutton.style.display = "block";
                } else {
                    mybutton.style.display = "none";
                }
            }

            // When the user clicks on the button, scroll to the top of the document
            mybutton.addEventListener('click', function() {
                document.body.scrollTop = 0; // For Safari
                document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
            });
        });
    </script>
    <button onclick="topFunction()" id="backToTopBtn" title="Go to top" class="btn btn-primary btn-sm btn-circle"><i class="zmdi zmdi-chevron-up"></i></button>
</body>


</html>