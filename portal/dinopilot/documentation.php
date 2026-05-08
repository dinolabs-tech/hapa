<!DOCTYPE html>
<html lang="en">

<body class="bg-white text-slate-900 dark:bg-slate-950 dark:text-slate-100">

    <!-- Main Content -->
    <main class="lg:ml-72 pt-14 lg:pt-0">
        <div class="max-w-4xl mx-auto px-6 py-12 lg:pr-56">

            <!-- Introduction Section -->
            <section id="introduction" class="mb-16">
                <h1 class="text-3xl font-bold mb-4">Introduction</h1>
                <p class="text-lg text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Welcome to the EduHive Documentation. This guide provides comprehensive instructions on how to effectively use and manage the various features within the EduHive School Management System. Whether you are an administrator or teacher, this documentation will help you navigate the platform and utilize its functionalities to enhance the educational experience.
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                    <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-900 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-1">Complete Documentation</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Every feature explained with step-by-step instructions</p>
                    </div>
                    <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-900 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-1">Up To Date</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Always matching the latest version of EduHive</p>
                    </div>
                    <div class="p-5 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-blue-200 dark:hover:border-blue-900 transition-colors">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-3">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-1">Quick Navigation</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Search and find exactly what you need instantly</p>
                    </div>
                </div>
            </section>

            <!-- Enroll Students -->
            <section id="enroll-students" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Enroll a Student</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    This feature is designed for school administrators or admission officers to seamlessly add new students to the EduHive system. Think of it as the digital registration desk for new students, ensuring all their initial information is captured accurately from the start of their academic journey.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Enrollment Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the main dashboard, look for the sidebar menu on the left.</li>
                                <li>Under <strong>Admission</strong>, click to <strong>Students</strong>, and finally select <strong>Enroll</strong>.</li>
                                <li>This action will take you to the Register Students page, which is where you'll find the student enrollment form.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Fill in Student Details</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Register Students page, you will see a form with various fields.</li>
                                <li><strong>Personal Information:</strong> Enter the student's full name, date of birth, gender, and current address.</li>
                                <li><strong>Contact Information:</strong> Provide emergency contact details, including parent/guardian names, phone numbers, and email addresses.</li>
                                <li><strong>Academic Background:</strong> Input any relevant previous school records or academic history.</li>
                            </ul>
                            <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important Note:</strong> Accuracy is paramount here! Double-check all entries, especially names and dates, as this information will be used across all other modules (attendance, results, etc.) throughout the student's time at the school.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Submit the Enrollment Form</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>After carefully filling out all the required fields, locate and click the "Submit" button at the bottom of the form.</li>
                                <li>Upon successful submission, the student's record will be officially saved in the EduHive database. They are now a registered student and can be managed through other features like attendance, results, and parent communication.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modify Students -->
            <section id="modify-students" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Modify a Student's Record</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    This feature allows authorized staff (like administrators or admission officers) to update or correct any existing information for a student already enrolled in the system. This is incredibly useful for keeping student records current, whether it's a change in address, phone number, class assignment, or any other personal detail that evolves over time.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Modify Student Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Admission > Students > Modify</strong>.</li>
                                <li>This will direct you to the Modify Students page, which is where you can search for and edit student records.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Search for the Student</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Modify Students page, you'll find a search bar or filter options.</li>
                                <li>Enter the student's ID, full name, or other identifying information to quickly locate their record.</li>
                            </ul>
                            <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important Note:</strong> If you're unsure of the exact spelling, try using partial names or filtering by class to narrow down your search.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Update Student Information</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Once the correct student's record appears, their current details will be displayed in editable form fields.</li>
                                <li>Carefully make the necessary changes. For example, update their contact number, change their assigned class, or correct a spelling error in their name.</li>
                            </ul>
                              <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important Note:</strong> Always ensure that any modifications are accurate and reflect the most current information. Incorrect data can lead to issues in other parts of the system.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Save the Changes</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>After making all desired updates, click the "Update" button.</li>
                                <li>The system will then save these changes, and the student's record in the database will be immediately updated with the new information.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- View Student Profile -->
            <section id="view-profile" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to View a Student's Profile</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "View Student Profile" feature offers a complete, 360-degree view of a student's journey within the EduHive system. It consolidates all their personal, academic, attendance, and financial information into one easy-to-access location.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the View Profile Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, select <strong>Admission > Students > View Profile</strong>.</li>
                                <li>This action will take you to the View Students page.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Search for the Student</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the View Students page, you'll find a search field.</li>
                                <li>Enter the student's name or their unique student ID to find the specific profile.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Review the Comprehensive Profile</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Once located, the system will display a detailed profile including Personal Details, Academic Records, Attendance History, and Financial Status.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Filter Students -->
            <section id="filter-students" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Filter Students</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Filter Students" tool is a powerful feature that allows you to quickly narrow down and find specific groups of students based on class and arm.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Filter Students Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, select <strong>Admission > Students > Filter Students</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Apply Desired Filters</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Select a specific grade or class (e.g., "YEAER 7", "JSS 1")</li>
                                <li>Choose the arm (e.g., "A, B, C...")</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mark Attendance -->
            <section id="mark-attendance" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Mark Attendance</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Mark Attendance" feature is crucial for teachers to accurately record student presence or absence in the school.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Mark Attendance Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Attendance > Mark Attendance</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Select Class, Session, and Term</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Select the specific Class, Arm, and relevant date.</li>
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                    <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important:</strong> Ensure these selections are correct to avoid marking attendance for the wrong class or day.</p>
                                </div>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Mark Each Student's Status</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Mark each student as Present or Absent</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Save the Attendance Record</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Click the "Save" button to store the attendance records.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Print Attendance Summary -->
            <section id="print-summary" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Print Attendance Summary</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Generate a concise overview of attendance records for a specific class, arm, academic session, and term.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to Print Attendance Summary</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Attendance > Print Attendance Summary</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Select Desired Criteria</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Use dropdowns to select Class and Arm</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Generate and Print</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Click "Print" button to generate the summary</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Print Attendance Sheet -->
            <section id="print-sheet" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Print Attendance Sheet</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Generate printable attendance sheets for manual record keeping during class.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to Print Attendance Sheet</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Attendance > Print Attendance Sheet</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Select Class and Arm</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Select relevant Class and Arm</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Generate and Print</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Click "Print Detailed Sheet" button</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Upload Results -->
            <section id="upload-results" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Upload Results</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Submit student academic performance data into the EduHive system in bulk using Excel templates.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to Upload Results Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Results > Upload</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Prepare Result Data Using Template</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Download result template by selecting class and arm</li>
                                <li>Fill template correctly with student IDs and scores</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Upload Result File</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Select Subject, Class, and Arm</li>
                                <li>Choose prepared file and click Upload</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modify Results -->
            <section id="modify-results" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Modify Results</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Make corrections or updates to student results that have already been uploaded.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to Modify Results Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Results > Modify</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Search for the Student/Result</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Use search box with student name or ID</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Edit the Existing Results</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Make necessary changes to scores and grades</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Save the Updated Results</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Click "Update" button to save changes</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Delete Results -->
            <section id="delete-results" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Delete Results</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Remove student academic results from the system. This action should be performed with caution.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to Delete Results Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Results > Delete</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Identify Results for Deletion</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Filter by Class, Arm, Term and Academic Session</li>
                                <div class="mt-3 p-3 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900 rounded-lg">
                                    <p class="text-sm text-red-800 dark:text-red-300"><strong>Warning:</strong> Deleting results is usually irreversible. Double-check your selection carefully.</p>
                                </div>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Confirm and Execute Deletion</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Click Delete button and confirm when prompted</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Class Teacher Comments -->
            <section id="teacher-comments" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Add Class Teacher's Comments</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Provide personalized qualitative feedback on student performance for a specific academic term.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to Class Teacher's Comments Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Results > Class Teacher's Comments</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Select the Student and Academic Period</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Select Class, Arm, Academic Session and Term</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Compose the Comment</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Type detailed feedback including academic progress, behavior and recommendations</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Save the Comment</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Click Submit button to save comments</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Principal Comments -->
            <section id="principal-comments" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Add Principal's Comments</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Provide overarching qualitative assessment of student performance as final review.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to Principal's Comments Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Results > Principal's Comments</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Select the Student and Academic Period</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Select Class, Arm, Academic Session and Term</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Compose the Principal's Comment</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Type formal feedback focusing on overall development and school values</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Save the Comment</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Click Submit button to save comments</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Download Student Result -->
            <section id="download-result" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Download Student's Result</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Generate and download a student's individual result slip in PDF format.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to Download Student's Result Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Results > Download Student's result</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Generate and Download the Result</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Enter Student ID and click Submit</li>
                                <li>Result will be automatically downloaded as PDF</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- View Uploaded Results -->
            <section id="view-results" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to View Uploaded Results</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Review all academic results that have been submitted into the EduHive system.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to View Uploaded Results Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Results > View Uploaded Results</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Filter and Search Results (Optional)</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Use filters for Class, Arm, Term and Subject</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Review and Verify Results</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Review displayed results table for accuracy</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

             <!-- Download Mastersheet -->
            <section id="download_mastersheet" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Download Mastersheet</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Download comprehensive spreadsheet containing all student results for a selected academic period
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Download Mastersheet Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Results > Download Mastersheet</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Select Academic Period and Class (Optional):</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Mastersheet page, you will need to specify the criteria for the mastersheet.</li>
                                <li>Select the desired Class, Arm, Term and Academic Session from the filter options.</li>
                            </ul>
                              <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important Note:</strong> Carefully choose your academic period to ensure the mastersheet contains the correct set of results.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Generate and Download the Mastersheet:</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>After making your selections, click the "Download" button</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            
             <!-- Revoke Results -->
            <section id="revoke_results" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Revoke Student Results</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    This is a critical administrative function, typically restricted to Administrators, that allows for the reversal of approved student results. 
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Revoke Student Results Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Results >Revoke Students Results.</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Identify Results for Revocation:</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Revoke page, you will find options to search for or filter student results.</li>
                                <li>You will need to search for the Student (by name, ID, class or arm) for which you intend to revoke results.</li>
                            </ul>
                              <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important Note:</strong>This step requires absolute precision. 
                                    Double-check all criteria to ensure you are targeting the correct student's results. Revoking the wrong results can lead to significant administrative issues.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Initiate and Confirm Revocation</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Once the specific results are identified, there will be an option to initiate the revocation.</li>
                                <li>Carefully read the confirmation message and, if you are absolutely certain, click "Confirm" or "Yes" to proceed with the revocation.</li>
                            </ul>
                             <div class="mt-3 p-3 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900 rounded-lg"><p class="text-sm text-red-800 dark:text-red-300"><strong>Warning:</strong> Once revoked, these results cannot be seen by the student, alumni, or parents except if the revocation is removed. Always ensure proper authorization and backup procedures are in place before performing this action.</p></div>
                        </div>
                    </div>
                </div>
            </section>

             <!-- Results Maintenance -->
            <section id="results_maintenance" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to run Results Maintenance</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Designed to perform various upkeep tasks on the student results database. This can include optimizing data, cleaning up inconsistencies, 
                    running integrity checks to ensure the reliability and performance of the results management system.
                </p>

                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Results Maintenance Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > Results > Results Maintenance.</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Run All Maintenance Tasks</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Maintenance page, you will see a single button labeled "Run Maintenance".</li>
                                <li>Click this button to automatically scan for errors and inconsistencies in the results data, including wrong calculations, incorrect grading, ranking and positioning, remarks, and more</li>
                                <li>The system will fix all detected issues with a single action, streamlining the maintenance process.</li>
                            </ul>
                              <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important Note:</strong>This step requires absolute precision. 
                                    Double-check all criteria to ensure you are targeting the correct student's results. Revoking the wrong results can lead to significant administrative issues.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Initiate and Confirm Revocation</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Once the specific results are identified, there will be an option to initiate the revocation.</li>
                                <li>Carefully read the confirmation message and, if you are absolutely certain, click "Confirm" or "Yes" to proceed with the revocation.</li>
                            </ul>
                             <div class="mt-3 p-3 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900 rounded-lg"><p class="text-sm text-red-800 dark:text-red-300"><strong>Warning:</strong> Once revoked, these results cannot be seen by the student, alumni, or parents except if the revocation is removed. Always ensure proper authorization and backup procedures are in place before performing this action.</p></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- View Assignments -->
            <section id="view-assignments" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to View Assignments</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "View Assignments" feature provides a centralized hub for both teachers and students to access and manage all academic assignments. For teachers, it's a way to monitor distributed tasks, while for students, it's their primary portal to see what's due, access assignment materials, and track their progress. This feature ensures transparency and easy access to all assignment-related information.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the View Assignments Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, both teachers and students can navigate to <strong>E-Learning Resources > Assignments > View</strong>.</li>
                                <li>This action will take you to the View Upload Assignments page, which lists all assignments relevant to the logged-in user.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Review the Assignment List</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
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
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Interact with Assignments</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
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
                        </div>
                    </div>
                </div>
            </section>

            <!-- Upload Notes -->
            <section id="upload-notes" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Upload Notes</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Upload Notes" feature empowers teachers to share supplementary learning materials, lecture summaries, or important study guides directly with their students. This ensures that students have easy access to all necessary resources, enhancing their understanding and preparation for lessons and exams. It's a vital tool for enriching the e-learning experience.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Upload Notes Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > E-Learning Resources > Notes > Upload</strong>.</li>
                                <li>This will take you to the Upload Notes page, which is the dedicated interface for uploading educational notes.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Fill in Notes Details</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Upload Notes page, simply select the <strong>Class</strong> and <strong>Subject</strong> for the notes.</li>
                                <li>Next, choose the notes file to upload from your computer.</li>
                                <li>Once selected, click the "Submit" button to upload the notes.</li>
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                    <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important for Users:</strong> Make sure you select the correct class and subject before submitting.</p>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Upload the Notes</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>After clicking "Submit," the system will process the file and make the notes available to the selected students.</li>
                                <li><strong>Confirmation:</strong> A success message will confirm that the notes have been uploaded and are now accessible to students.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- View Notes -->
            <section id="view-notes" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to View Notes</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "View Notes" feature serves as a central repository where students and teachers can access all uploaded educational notes. This ensures that all learning materials are organized and readily available, supporting student revision and teacher resource management. It's an essential component for a well-structured e-learning environment.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the View Notes Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, both teachers and students can navigate to <strong>E-Learning Resources > Notes > View</strong>.</li>
                                <li>This action will take you to the View Upload Notes page, which displays a list of all available notes.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Review the Assignment List</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
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
                        </div>
                    </div>
                </div>
            </section>

            <!-- Upload Curriculum -->
            <section id="upload-curriculum" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Upload Curriculum</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Upload Curriculum" feature allows educators and administrators to centralize and distribute official curriculum documents, syllabi, and lesson plans. This ensures that all teaching staff and students have access to the most current and approved educational frameworks, promoting consistency in instruction and clarity in learning objectives. It's a foundational tool for academic planning and resource sharing.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Upload Curriculum Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > E-Learning Resources > Curriculum > Upload</strong>.</li>
                                <li>This will take you to the Upload Curriculum page, which is the interface for submitting curriculum materials.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Fill in Curriculum Details</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Upload Curriculum page, simply select the <strong>Class</strong> and <strong>Subject</strong> for the curriculum.</li>
                                <li>Next, choose the curriculum file to upload from your computer.</li>
                                <li>Once selected, click the "Submit" button to upload the curriculum.</li>
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                    <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important for Users:</strong> Make sure you select the correct class and subject before submitting.</p>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- View Curriculum -->
            <section id="view-curriculum" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to View Curriculum</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "View Curriculum" feature provides a centralized and easily accessible platform for teachers, students, and administrators to review all uploaded curriculum documents. This ensures that everyone involved in the educational process is aligned with the current academic standards, learning objectives, and course structures. It's a critical tool for academic transparency and effective educational planning.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the View Curriculum Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, navigate to <strong>E-Learning Resources > Curriculum > View</strong>.</li>
                                <li>This action will take you to the View Upload Curriculum page, which displays a comprehensive list of all available curriculum materials.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Review the Curriculum List</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the View Upload Curriculum page, you will see a list of uploaded curriculum documents. Each entry typically displays:
                                    <ul>
                                        <li><strong>Subject:</strong> The subject for which the curriculum was uploaded (e.g., "Mathematics").</li>
                                        <li><strong>Class:</strong> The class assigned to the curriculum (e.g., "JSS 2").</li>
                                        <li><strong>Filename:</strong> The name of the uploaded curriculum file (e.g., "math_curriculum_2024.pdf").</li>
                                        <li><strong>Actions:</strong> For students, options may include "Download" or "View Details." For teachers/admins, options may include "Edit" or "Delete."</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Add Questions -->
            <section id="add-questions" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Add Questions</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Add Questions" feature is designed for teachers to manually create and input individual questions into the Computer-Based Test (CBT) question bank. This allows for the creation of custom questions, ensuring that assessments are tailored to specific lesson plans, learning objectives, and student needs. It's a fundamental tool for building a robust and flexible CBT system.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Add Questions Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > CBT > Add Questions</strong>.</li>
                                <li>This will take you to the Add Question page, which is the interface for creating new questions.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Fill in Question Details</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Add Question page, you will find a form to enter the details of your question. This typically includes:
                                    <ul>
                                        <li><strong>Question Text:</strong> Type the full question clearly and concisely.</li>
                                        <li><strong>Options:</strong> Provide multiple-choice options (e.g., A, B, C, D). Ensure there is at least one correct answer.</li>
                                        <li><strong>Correct Answer:</strong> Select or specify the correct option among the choices.</li>
                                        <li><strong>Subject:</strong> Categorize the question by subject for better organization and retrieval.</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Submit the Question</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>After carefully filling in all the required details, click the "Submit" or "Add Question" button.</li>
                                <li>The system will then save the question to the CBT question bank, making it available for inclusion in future computer-based tests.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Upload Questions -->
            <section id="upload-questions" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Upload Questions (in Bulk)</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Upload Questions (in Bulk)" feature is an efficient tool for teachers to add a large number of questions to the Computer-Based Test (CBT) question bank simultaneously. Instead of manually entering each question, this feature allows you to prepare all your questions in a structured format (CSV) and upload them in one go. This is particularly useful for creating extensive question banks for major exams or multiple topics.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Bulk Upload Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > CBT > Upload Questions</strong>.</li>
                                <li>This will take you to the Question Add page, which is specifically designed for bulk question uploads.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Download the Provided Template</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Question Add page, you will find an option to "Download Excel Template" or "Download CSV Template."</li>
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                    <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important for Users:</strong> Always use this official template. It ensures that your questions are formatted correctly, with designated columns for the question text, multiple-choice options, and the correct answer.<br>
                                    <strong>Note:</strong> The correct answer should be specified as a numeric index. For example, if you have Option A, Option B, Option C, Option D and the correct answer is Option C, then the value for the correct answer field should be <code>3</code>. Using a different format will lead to upload errors.</p>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Prepare Your Questions in the Template</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Open the downloaded template file.</li>
                                <li>Carefully fill in your questions, ensuring each question has its text, all possible options (e.g., Option A, Option B, Option C, Option D), and a clear indication of the correct answer index as explained above.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Upload the Completed Template File</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Once you have filled the template with all your questions, save the file.</li>
                                <li>Return to the Question Add page and use the "Choose File" or "Browse" button to select your prepared template file from your computer.</li>
                                <li>Click the "Upload" or "Submit" button to initiate the bulk upload process.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">5</div>
                        <div>
                            <h4 class="font-semibold mb-2">Initiating the Exam (Crucial Next Step)</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li><strong>Initiate Exam:</strong> After uploading your questions, click the "Initiate Exam" button on the Question Add page. This step prepares the CBT module for exam mode, ensuring the uploaded questions are ready for student access.
                                    <ul>
                                        <li>This action finalizes the exam setup and makes the exam available for scheduling.</li>
                                        <li>Once initiated, proceed to "Set Exam Time/Date" to schedule when students can take the exam.</li>
                                        <li><strong>Note:</strong> Failing to initiate the exam will prevent students from accessing the uploaded questions during the scheduled exam period.</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modify Questions -->
            <section id="modify-questions" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Modify Questions</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Modify Questions" feature allows teachers to edit or update existing questions within the Computer-Based Test (CBT) question bank. This is essential for correcting errors, refining question wording, updating options, or changing the correct answer to ensure the accuracy and relevance of assessment materials. Maintaining an up-to-date question bank is crucial for effective CBTs.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Modify Questions Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > CBT > Modify Questions</strong>.</li>
                                <li>This will take you to the Adquest page, which is the interface for editing questions.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Search for the Question to Modify</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Adquest page, you will typically find a filter option.</li>
                                <li>Use the available filter options to narrow down your search by <strong>Class</strong>, <strong>Arm</strong>, <strong>Term</strong>, <strong>Academic Session</strong>, and <strong>Subject</strong>.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Make Necessary Changes</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Once the question is displayed, its text, options, and correct answer will be presented in editable fields.</li>
                                <li>Carefully make your desired changes. This could include:
                                    <ul>
                                        <li>Rewording the question for clarity.</li>
                                        <li>Adding, removing, or editing multiple-choice options.</li>
                                        <li>Changing the designated correct answer.</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Save Your Changes</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>After making all desired updates, click the "Update" or "Save Changes" button on the form.</li>
                                <li>The system will then save these modifications, and the question in the CBT question bank will be immediately updated.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Check CBT Results -->
            <section id="check-cbt-results" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Check CBT Results</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Check CBT Results" feature provides teachers and administrators with the ability to review the performance of students on Computer-Based Tests. This allows for quick assessment of student understanding, identification of areas where students might be struggling, and overall evaluation of the effectiveness of the CBTs. It's a crucial step in the feedback and grading process.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Check CBT Results Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > CBT > Check Results</strong>.</li>
                                <li>This will take you to the Checkcbt page, which is the central hub for viewing CBT outcomes.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Search and Download Results</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>The page provides a search field where you can enter a student's ID to view their individual CBT result.</li>
                                <li>You can also download the entire result of all students who participated in the selected CBT exam session.</li>
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                    <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Note for Users:</strong> The CBT Module is disposable—when you click "Initiate Exam," all previous results are cleared and the module prepares for a new exam session. Always download the entire result before using the "Initiate Exam" button again.</p>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Set Exam Time -->
            <section id="set-exam-time" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Set Exam Time/Date</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Set Exam Time/Date" feature is a critical administrative function within the CBT management system. It allows teachers and administrators to precisely schedule when a Computer-Based Test will become available to students. This ensures that exams are administered fairly, preventing early access and allowing for synchronized testing across all students.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Set Exam Time/Date Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > CBT > Set Exam Time/Date</strong>.</li>
                                <li>This will take you to the Settime page, which is the control panel for scheduling CBTs.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Choose Class, Arm, Term, Session, and Set Exam Time</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Settime page, you will find dropdown menus to select the <strong>Class</strong>, <strong>Arm</strong>, <strong>Term</strong>, and <strong>Academic Session</strong> for which you want to schedule the exam.</li>
                                <li>Next, select the <strong>Exam Date</strong> from the calendar control.</li>
                                <li>Enter the <strong>Exam Time</strong> in minutes (e.g., enter <code>60</code> for a 1-hour exam).</li>
                                <li>After filling all fields, click the "Save" button to schedule the exam for the selected class and arm.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Register Tuckshop User -->
            <section id="register-tuckshop-user" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Register a Tuckshop User</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Register Tuckshop User" feature allows administrators or authorized personnel to create new user accounts specifically for managing tuckshop operations. This ensures that only designated staff members have access to the Point of Sale (POS) system, inventory management, and other tuckshop-related functionalities, enhancing security and accountability.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the User Registration Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>TuckShop > Tuck Shop > Register</strong>.</li>
                                <li>This will take you to the Regtuck page, which is the interface for creating new tuckshop user accounts.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Enter Student ID and Fetch Details Automatically</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Regtuck page, enter the <strong>Student ID</strong> in the designated field.</li>
                                <li>The system will automatically retrieve the student's details from the database, including Student Name, Current Session and Class.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Enter Recharge Amount</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>In the <strong>Balance</strong> field, enter the amount the student is recharging their account with.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Register Student</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>After confirming all details and entering the recharge amount, click the "Register Student" button.</li>
                                <li>The system will create the tuckshop account for the student and update their balance accordingly.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tuckshop POS -->
            <section id="tuckshop-pos" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Make Tuckshop Sales (POS)</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Sales (POS)" feature is the core of the tuckshop operations, allowing staff to efficiently process sales of items to students or other customers. This Point of Sale (POS) system is designed to be intuitive, ensuring quick transactions and accurate record-keeping. Think of it as a digital cash register tailored for the school tuckshop environment.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the POS Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the main dashboard, locate the sidebar menu on the left.</li>
                                <li>Click on <strong>TuckShop</strong>, then navigate to <strong>Tuck Shop</strong>, and finally select <strong>POS</strong>.</li>
                                <li>This action will take you to the Selling Point page, which is your primary interface for making sales.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Search for Student</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>At the top of the Selling Point page, use the search field to enter the Student ID or name.</li>
                                <li>If the student is registered in the tuckshop database, their details will be displayed, including their available balance.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Select Products for Purchase</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Use the product search field to find items in the inventory and add them to the cart.</li>
                                <li>Each selected product will appear in the cart, where you can specify the quantity for each item.</li>
                                <li>The system will automatically calculate the subtotal for each item and the total cart balance.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Checkout and Payment</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>When ready to checkout, the system compares the student's available balance with the cart total.</li>
                                <li><strong>If the balance is sufficient:</strong> The cart total is deducted from the student's balance, the transaction is completed, inventory is updated, and a receipt is generated.</li>
                                <li><strong>If the balance is insufficient:</strong> The transaction is flagged and cannot proceed. The user must either recharge the student's account or reduce the cart total by removing items.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tuckshop Inventory -->
            <section id="tuckshop-inventory" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Manage Tuckshop Inventory</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Tuckshop Inventory Management" feature is essential for keeping track of all products available for sale in the tuckshop. It allows authorized staff to add new items, update existing product details (like price or stock levels), and remove products that are no longer sold. Effective inventory management ensures that popular items are always in stock and helps prevent waste.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Inventory Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>TuckShop > Tuck Shop > Inventory</strong>.</li>
                                <li>This will take you to the Inventory page, which is your central hub for all product management.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">To Add a New Product</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Inventory page you will be presented with a form to enter details for the new item:</li>
                                <li>
                                    <ul>
                                        <li><strong>Product Name:</strong> A clear and descriptive name (e.g., "Apple Juice Box," "Chocolate Bar").</li>
                                        <li><strong>Location:</strong> The Product location in the store/shop.</li>
                                        <li><strong>Unit Price:</strong> The Purchase price of the product.</li>
                                        <li><strong>Sell Price:</strong> The selling price of the product.</li>
                                        <li><strong>Quantity:</strong> The quantity of the product currently available.</li>
                                        <li><strong>Reorder Level:</strong> The minimum stock quantity at which you should reorder the product to avoid running out.</li>
                                    </ul>
                                </li>
                                <li>After filling in all required details, click "Save Product." The new item will then be added to your inventory.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">To Update an Existing Product</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Inventory page, you will see a list of all current products.</li>
                                <li>Locate the product you wish to modify. Click the "Edit" button next to each product entry.</li>
                                <li>The product's current details will be displayed in an editable form. Make the necessary changes (e.g., update the price, adjust the stock level after a new delivery, correct a product name).</li>
                                <li>After making your modifications, click "Update Product."</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tuckshop Suppliers -->
            <section id="tuckshop-suppliers" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Manage Tuckshop Suppliers</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Manage Tuckshop Suppliers" feature is crucial for maintaining a well-stocked tuckshop by effectively managing relationships and information with product suppliers. This module allows authorized personnel to add new suppliers, update their contact details, and remove suppliers who are no longer in use. Accurate supplier information ensures smooth procurement and inventory replenishment.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Suppliers Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>TuckShop > Tuck Shop > Suppliers</strong>.</li>
                                <li>This will take you to the Supplier page, which is the central interface for all supplier management.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">To Add a New Supplier</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Supplier page you will be provided a form to fill in details for the new supplier:</li>
                                <li>
                                    <ul>
                                        <li><strong>Supplier/Business Name:</strong> The official name of the supplier company.</li>
                                        <li><strong>Phone Number:</strong> The supplier's contact phone number.</li>
                                        <li><strong>Email Address:</strong> The supplier's email for communication.</li>
                                        <li><strong>Address:</strong> The physical address of the supplier.</li>
                                    </ul>
                                </li>
                                <li>After entering the information, click "Save" The new supplier will then be added to your records.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tuckshop Dashboard -->
            <section id="tuckshop-dashboard" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to View Tuckshop Dashboard</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Tuckshop Dashboard" feature provides an at-a-glance overview of the tuckshop's operational performance and key metrics. This dashboard is designed to give managers and administrators quick insights into sales trends, inventory status, and overall financial health, enabling informed decision-making and efficient management of tuckshop activities.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Tuckshop Dashboard Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>TuckShop > Tuck Shop > Dashboard</strong>.</li>
                                <li>This will take you to the Tuckdashboard page, which is the central display for tuckshop analytics.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Review Key Metrics and Overview</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Tuckdashboard page, you will find various widgets and charts displaying important metrics, including:
                                    <ul>
                                        <li><strong>Total Registered Students:</strong> The total number of students registered for tuckshop services.</li>
                                        <li><strong>Total Students Balance:</strong> The combined balance of all student tuckshop accounts.</li>
                                        <li><strong>Low Balance:</strong> Number of students whose account balance is below the defined threshold.</li>
                                        <li><strong>Total Sales:</strong> The total value of all sales made through the tuckshop.</li>
                                        <li><strong>Total Transactions:</strong> The total number of sales transactions processed.</li>
                                        <li><strong>Inventory Quantity:</strong> The total quantity of all products currently in stock.</li>
                                        <li><strong>Out of Stock Products:</strong> Number of products that have zero quantity in inventory.</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tuckshop Transactions -->
            <section id="tuckshop-transactions" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to View and Manage Tuckshop Transactions</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Tuckshop Transactions" feature provides a detailed log of every sale made through the Point of Sale (POS) system. This comprehensive transaction history is crucial for financial reconciliation, auditing, resolving students' queries, and analyzing sales patterns. It offers a transparent and searchable record of all tuckshop activities.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Transactions Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>TuckShop > Tuck Shop > Transactions</strong>.</li>
                                <li>This will take you to the Transactions page, which is the central repository for all sales records.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">View the List of All Sales</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>The Transactions page displays a complete list of all sales made through the POS system, including:
                                    <ul>
                                        <li>Date and time of transaction</li>
                                        <li>Student details</li>
                                        <li>Items purchased</li>
                                        <li>Total amount</li>
                                        <li>Transaction status</li>
                                    </ul>
                                </li>
                                <li>You can filter transactions by date range, student ID, or product category to find specific records easily.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Class Schedule -->
            <section id="class-schedule" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Use the Class Schedule</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Class Schedule" feature is a vital tool for administrators and teachers to organize and manage the school's academic timetable. It allows for the creation, editing, and viewing of class schedules across different grades, subjects, and academic sessions. An effective class schedule ensures optimal utilization of resources (classrooms, teachers) and provides clarity for students and parents regarding their daily academic structure.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Class Schedule Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Administrator > Class Schedule</strong>.</li>
                                <li>This will take you to the Timetable page, which is the central interface for managing all class schedules.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Create a New Class Schedule</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Timetable page, you will typically find an option to "Create New Schedule".</li>
                                <li>You will then need to input details such as:</li>
                                <li>
                                    <ul>
                                        <li><strong>Day:</strong> Select the day of the week for the class (e.g., "Monday").</li>
                                        <li><strong>Class:</strong> Select the class (e.g., "JSS 1").</li>
                                        <li><strong>Arm:</strong> Select the arm (e.g., "A, B, C...").</li>
                                        <li><strong>Subject:</strong> Select the subject being taught.</li>
                                        <li><strong>Start Time:</strong> Specify the start time for the class.</li>
                                        <li><strong>End Time:</strong> Specify the end time for the class.</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Academic Calendar -->
            <section id="academic-calendar" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Use the Academic Calendar</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Academic Calendar" feature is a comprehensive tool designed to help school administrators and staff effectively plan, manage, and communicate all important dates and events throughout the academic year. This includes scheduling exams, marking holidays, planning school activities, and noting parent-teacher conferences.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Academic Calendar Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Administrator > Calendar</strong>.</li>
                                <li>This will take you to the Calendar page, which is your central hub for managing all school events.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Manage Subjects -->
            <section id="manage-subjects" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Manage Subjects</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Manage Subjects" feature is a fundamental administrative tool that allows school staff to define, organize, and maintain the list of academic subjects offered within the EduHive system. This ensures that the curriculum is accurately reflected, enabling proper assignment of subjects to classes for use in result processing.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Manage Subjects Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Administrator > Subjects</strong>.</li>
                                <li>This will take you to the Subjects page, which is the central interface for all subject-related operations.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Configure System Settings -->
            <section id="configure-system-settings" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Configure System Settings</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "System Settings" feature is a critical administrative module that allows Administrators to configure and customize various system-wide parameters of the EduHive application. This includes general application settings, academic year configurations, security parameters, and other crucial administrative controls.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the System Settings Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Administrator > Settings</strong>.</li>
                                <li>This will take you to the Admin page, which is the central control panel for all system configurations.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- User Control -->
            <section id="user-control" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Use User Control</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "User Control" feature is a powerful administrative module that allows Administrators to manage all user accounts and their associated roles within the EduHive system. This includes creating new user accounts, assigning specific roles (such as Teacher, Admission Officer, Bursary Staff, or Tuck Shop officer) and modifying existing user details.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the User Control Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Administrator > User Control</strong>.</li>
                                <li>This will take you to the Usercontrol page, which is the central interface for managing all user accounts.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Send Notice to Parents -->
            <section id="send-notice" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Send Notice to Parents</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Send Notice to Parents" feature is a vital communication tool that allows administrators and authorized staff to disseminate important announcements, updates, or urgent messages directly to parents. This ensures timely and consistent communication, keeping parents informed about school events, policy changes, student progress, or any other relevant information.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Send Notice Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Administrator > Send Notice to Parents</strong>.</li>
                                <li>This will take you to the Send Notice page, which is the interface for composing and sending notices.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Alumni List -->
            <section id="alumni-list" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Manage Alumni List</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Alumni List" feature provides a dedicated section for managing records of former students who have graduated or left the institution. This is an invaluable resource for maintaining connections with alumni, organizing alumni events, tracking their achievements, and fostering a strong school community beyond graduation.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Alumni List Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Administrator > Alumni List</strong>.</li>
                                <li>This will take you to the Alumni List page, which is the central repository for all alumni records.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Manage Alumni Testimonials -->
            <section id="manage-alumni-testimonials" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Manage Alumni Testimonials</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Manage Alumni Testimonials" feature provides a robust system for collecting, storing, and displaying testimonials for former students. This is an invaluable tool for showcasing student success, building institutional reputation, and inspiring current and prospective students.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Alumni List Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Administrator > Alumni List</strong>.</li>
                                <li>This will take you to the Alumni List page, where you can manage alumni records and testimonials.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Discussion Threads Management -->
            <!-- Discussion Assignments -->
            <section id="discussion-threads-management" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">Discussion Threads Management</h2>

                <section id="view_threads" class="mb-12">
                    <h3 class="text-xl font-semibold mb-3">How to View Discussion Threads</h3>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                        The "View Discussion Threads" feature provides a centralized platform for users to browse and access all active discussion forums or threads within the EduHive system. This is essential for fostering communication, collaboration, and knowledge sharing among students, teachers, and administrators. It allows users to stay updated on ongoing discussions and participate in relevant conversations.
                    </p>

                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                            <div>
                                <h4 class="font-semibold mb-2">Navigate to the View Discussion Threads Page</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>From the sidebar menu, go to <strong>Administrator > Discussion Threads > Threads</strong>.</li>
                                    <li>This will take you to the Threads page, which is the central hub for viewing all discussion threads.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                            <div>
                                <h4 class="font-semibold mb-2">Review the List of Active Threads</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>On the Threads page, you will see a comprehensive list of all active discussion threads. Each entry typically includes:
                                        <ul>
                                            <li><strong>Thread Title/Subject:</strong> A clear title indicating the topic of discussion.</li>
                                            <li><strong>Creator:</strong> The user who initiated the thread.</li>
                                            <li><strong>Date Created:</strong> The date and time when the thread was originally started.</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                            <div>
                                <h4 class="font-semibold mb-2">Access and Participate in Threads</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>Click on the title of any thread to open it and view all messages and replies within that discussion.</li>
                                    <li>From within an open thread, you can read messages, compose replies, and engage with other participants.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="create-thread" class="mb-12">
                    <h3 class="text-xl font-semibold mb-3">How to Create a Discussion Thread</h3>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                        The "Create Discussion Thread" feature allows users (teachers, administrators, or even students) to initiate new discussion topics within the EduHive system. This is the starting point for any new conversation, question, or collaborative project, enabling users to share ideas, seek help, or discuss academic subjects in an organized forum.
                    </p>

                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                            <div>
                                <h4 class="font-semibold mb-2">Navigate to the Create Thread Page</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>From the sidebar menu, go to <strong>Administrator > Discussion Threads > Create Thread</strong>.</li>
                                    <li>This will take you to the Create_thread page, which is the interface for starting new discussions.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                            <div>
                                <h4 class="font-semibold mb-2">Fill in Thread Details</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>On the Create_thread page, you will find fields to define your new discussion thread:
                                        <ul>
                                            <li><strong>Subject/Title:</strong> A clear and descriptive title that summarizes the topic of your discussion.</li>
                                            <li><strong>Content/Initial Post:</strong> Type your initial message, question, or prompt for the discussion.</li>
                                        </ul>
                                    </li>
                                    <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                        <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important:</strong> A well-defined subject and clear initial post will encourage more relevant and productive discussions.</p>
                                    </div>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                            <div>
                                <h4 class="font-semibold mb-2">Submit to Create the Thread</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>After composing your initial post and filling in all necessary details, click the "Post".</li>
                                    <li>The system will then publish your new discussion thread, making it visible to the designated audience.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="read_message" class="mb-12">
                    <h3 class="text-xl font-semibold mb-3">How to Read a Message in a Thread</h3>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                        The "Read Message in a Thread" feature allows users to access and review the full content of any discussion thread within the EduHive system. This is crucial for following conversations, understanding context, and preparing to contribute to ongoing discussions.
                    </p>

                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                            <div>
                                <h4 class="font-semibold mb-2">Navigate to the View Discussion Threads Page</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>From the sidebar menu, go to <strong>Administrator > Discussion Threads > Threads</strong>.</li>
                                    <li>This will take you to the Threads page, which lists all active discussion threads.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                            <div>
                                <h4 class="font-semibold mb-2">Select and Open a Thread</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>On the Threads page, you will see a list of discussion threads. Each thread will have a title or subject.</li>
                                    <li>Click on the <strong>title of the specific thread</strong> you wish to read.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                            <div>
                                <h4 class="font-semibold mb-2">View All Messages and Replies</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>Clicking on a thread will take you to the View_thread page.</li>
                                    <li>On this page, you will see the initial post of the thread, followed by all subsequent replies, usually displayed in chronological order.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="reply_message" class="mb-12">
                    <h3 class="text-xl font-semibold mb-3">How to Reply to a Message in a Thread</h3>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                        The "Reply to a Message in a Thread" feature enables users to actively participate in ongoing discussions by posting their responses, questions, or additional information. This fosters interactive learning and collaborative problem-solving.
                    </p>

                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                            <div>
                                <h4 class="font-semibold mb-2">Access an Existing Discussion Thread</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>First, navigate to the <strong>View Discussion Threads</strong> page (Threads) and click on the title of the thread you wish to reply to.</li>
                                    <li>This will open the thread on the View_thread page, where you can see all existing messages.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                            <div>
                                <h4 class="font-semibold mb-2">Locate the Reply Option</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>While viewing the thread on the View_thread page, scroll to the bottom or look for a designated text input area.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                            <div>
                                <h4 class="font-semibold mb-2">Compose Your Reply</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>Type your response, question, or comment. Ensure your reply is relevant to the discussion and contributes constructively.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                            <div>
                                <h4 class="font-semibold mb-2">Submit Your Reply</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>After composing your message, click the "Post" button.</li>
                                    <li>The system will then add your reply to the discussion thread, making it visible to all other participants.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="edit_thread" class="mb-12">
                    <h3 class="text-xl font-semibold mb-3">How to Edit a Discussion Thread</h3>
                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                        The "Edit Discussion Thread" feature allows the creator of a thread (or an administrator with appropriate permissions) to modify the title or initial post of an existing discussion. This is useful for correcting errors, updating information, or refining the focus of a discussion.
                    </p>

                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                            <div>
                                <h4 class="font-semibold mb-2">Access the Discussion Thread</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>First, navigate to the <strong>View Discussion Threads</strong> page (Threads) and click on the title of the thread you wish to edit.</li>
                                    <li>This will open the thread on the Thread page.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                            <div>
                                <h4 class="font-semibold mb-2">Locate the Edit Option</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>While viewing the thread, look for an "Edit" button or icon, typically associated with the initial post or the thread settings.</li>
                                    <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900 rounded-lg">
                                        <p class="text-sm text-blue-800 dark:text-blue-300"><strong>Note:</strong> This option is usually only visible to the thread creator or administrators.</p>
                                    </div>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                            <div>
                                <h4 class="font-semibold mb-2">Make Desired Changes</h4>
                                <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                    <li>Clicking the "Edit" option will take you to the Edit Thread page.</li>
                                    <li>Here, you will find editable fields for the thread's subject/title and its initial content.</li>
                                    <li>Carefully make your desired modifications.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="flex-shrink-0
                <h2 class="text-2xl font-bold mb-4">How to Upload Assignments</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Upload Assignments" feature allows teachers to create and distribute academic assignments to their students digitally. This streamlines the assignment process, making it easier for students to access tasks and for teachers to manage submissions and grading. It's a core component of the e-learning resources, facilitating interactive learning and assessment.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Upload Assignments Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Teacher > E-Learning Resources > Assignments > Upload</strong>.</li>
                                <li>This will take you to the Upload Assignments page, which is the interface for creating and uploading new assignments.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Fill in Assignment Details</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Upload Assignments page, simply select the <strong>Class</strong> and <strong>Subject</strong> for the assignment.</li>
                                <li>Next, choose the assignment file to upload from your computer.</li>
                                <li>Once selected, click the "Submit" button to upload the assignment.</li>
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                    <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important for Users:</strong> Make sure you select the correct class and subject before submitting.</p>
                                </div>
                            </ul>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Upload the Assignment</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>After clicking "Submit," the system will process the file and make the assignment available to the selected students.</li>
                                <li><strong>Confirmation:</strong> A success message will confirm that the assignment has been uploaded and is now accessible to students.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Parents Management -->
            <section id="parents-management" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">Parents Management</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    Parent account management system allows administrators or authorized staff to create new user accounts for parents within the EduHive system. This is a crucial step for enabling parent access to student information (like results and bursary), facilitating communication, and allowing them to participate in the school community.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Register Parents Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Administrator > Parents > Register Parents</strong>.</li>
                                <li>This will take you to the Register Parent page, which is the interface for creating new parent accounts.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Create Message -->
            <section id="create-message" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Create and Send an Email (Internal Message)</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Create and Send an Email" feature (often referred to as an internal messaging system) allows users within the EduHive platform to compose and send private messages to other registered users. This facilitates direct and secure communication between students, teachers, administrators, and parents, without needing to use external email clients.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Create Message Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Profile & Messaging > Create Message</strong>.</li>
                                <li>This will take you to the Create Message page, which is the interface for composing new internal messages.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Select Recipient(s)</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Create Message page, you will find a field labeled "To" or "Recipient."</li>
                                <li>You can typically search for and select a registered user (e.g., a specific teacher, an administrator, or a student) from a list.</li>
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                    <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important:</strong> Ensure you select the correct recipient to avoid sending private messages to unintended individuals.</p>
                                </div>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Enter Subject and Compose Message</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li><strong>Subject:</strong> Provide a clear and concise subject line that summarizes the content of your message (e.g., "Question about Homework," "Meeting Request," "Feedback on Project").</li>
                                <li><strong>Message Content:</strong> Type the full body of your message in the designated text area. Be clear, respectful, and provide all necessary details.</li>
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                    <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Important:</strong> Always proofread your message for clarity, grammar, and accuracy before sending. Misinformation can cause confusion.</p>
                                </div>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Send the Message</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>After composing your message and selecting the recipient(s), click the "Send Message" button.</li>
                                <li>The system will then deliver your message to the recipient(s)' EduHive inbox.</li>
                                <li><strong>Confirmation:</strong> A success message will usually appear, confirming that your message has been successfully sent.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Inbox -->
            <section id="inbox" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Use the Inbox</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Inbox" feature serves as the central receiving area for all internal messages sent to a user within the EduHive system. It functions like a personal mailbox, allowing users to view, read, and manage all incoming communications from other students, teachers, administrators, or parents.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to Your Inbox</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the navbar menu, go to <strong>Inbox</strong>.</li>
                                <li>This will take you to the Inbox page, which displays a list of all messages you have received.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Review Incoming Messages</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Inbox page, you will see a list of messages, typically ordered by date (newest first). Each entry usually includes:
                                    <ul>
                                        <li><strong>Sender:</strong> The name of the user who sent the message.</li>
                                        <li><strong>Subject:</strong> The subject line of the message.</li>
                                        <li><strong>Date/Time:</strong> When the message was received.</li>
                                        <li><strong>Status:</strong> Often indicates if the message is "Unread" or "Read."</li>
                                    </ul>
                                </li>
                                <li><strong>Tip:</strong> Pay attention to the sender and subject line to prioritize which messages to read first.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Read a Message</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>To view the full content of a message, simply click on its <strong>subject line</strong> or the sender's name.</li>
                                <li>This will open the message, displaying the full text. The message's status will typically change from "Unread" to "Read."</li>
                                <li>From within the opened message, you may also find options to "Reply" to the sender.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sent Messages -->
            <section id="sent-messages" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to View Sent Messages</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Sent Messages" feature provides users with a record of all internal messages they have composed and sent to other users within the EduHive system. This acts as a personal outbox, allowing users to review past communications, confirm delivery, or retrieve information they have previously shared.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Sent Messages Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the navbar menu, go to <strong>Sent Messages</strong>.</li>
                                <li>This will take you to the Sent Message page, which displays a list of all messages you have sent.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Review Your Sent Communications</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the Sent Message page, you will see a chronological list of all messages you have sent. Each entry typically includes:
                                    <ul>
                                        <li><strong>Recipient(s):</strong> The user(s) to whom the message was sent.</li>
                                        <li><strong>Subject:</strong> The subject line of your sent message.</li>
                                        <li><strong>Date/Time:</strong> When the message was sent.</li>
                                        <li><strong>Status:</strong> This indicates if the message has been "Read" by the recipient.</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">View Full Message Content</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>To review the full content of a sent message, simply click on its <strong>subject line</strong>.</li>
                                <li>This will open the message, displaying the full text and any attachments you included.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

              <!-- Profile Page -->
            <section id="profile-page" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Use the Profile Page</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Profile Page" feature provides each user (students, teachers, administrators, and parents) with a personalized section to view and manage their personal information, update security settings, and access account-specific functionalities. It serves as a central hub for individual user data and preferences within the EduHive system.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to Your Profile Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the navbar menu (on your top right), look for an option like <strong>"Profile"</strong> or your name/avatar.</li>
                                <li>Clicking this will take you to your specific profile page.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Review and Update Personal Details</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On your profile page, you will typically see various sections displaying your personal information. This might include:
                                    <ul>
                                        <li><strong>Basic Information:</strong> Name, date of birth, gender, contact details (email, phone number).</li>
                                        <li><strong>Address:</strong> Your current residential address.</li>
                                    </ul>
                                </li>
                                <li>You can directly modify fields to update your information.</li>
                                <div class="mt-3 p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900 rounded-lg">
                                    <p class="text-sm text-amber-800 dark:text-amber-300"><strong>Tip:</strong> Keep your contact information up-to-date so the school can reach you with important announcements or in emergencies.</p>
                                </div>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Manage Account-Specific Settings</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Beyond personal details, the profile page often includes options to manage security and preferences:
                                    <ul>
                                        <li><strong>Change Password:</strong> A critical security feature. Always use a strong, unique password and change it regularly.</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Save Your Changes</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>After making any updates to your personal details or account settings, locate and click the "Update Profile" button.</li>
                                <li>The system will then apply these modifications to your user account.</li>
                                <li><strong>Confirmation:</strong> A success message will usually appear, confirming that your profile has been successfully updated.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Extend License -->
            <section id="license" class="mb-16">
                <h2 class="text-2xl font-bold mb-4">How to Extend License</h2>
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                    The "Extend License" feature is a comprehensive tool that offers several advantages designed to enhance user experience and system reliability. It is designed to provide users with a smooth, secure and efficient way to manage their subscription renewals. By integrating automation, flexibility, and transparency, it ensures that users can continue accessing all features without interruption or administrative delays.
                </p>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">1</div>
                        <div>
                            <h4 class="font-semibold mb-2">Navigate to the Extend License Page</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>From the sidebar menu, go to <strong>Extend License</strong>.</li>
                                <li>This will take you to the Extend License page.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">2</div>
                        <div>
                            <h4 class="font-semibold mb-2">Purchase a New License</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>On the License Extension page, click the Purchase License Link, you will be redirected to the Dinolabs Portal:</li>
                                <li>
                                    <ul>
                                        <li><strong>Login to the Dinolabs Portal:</strong> If not already logged in, you will be prompted to sign in with your registered account credentials.</li>
                                        <li><strong>Select License Details:</strong> After logging in, click "Purchase License" on the sidebar menu. Choose your preferred expiry date for the new license</li>
                                        <li><strong>Make Payment:</strong> Once the expiry date is selected, click "Pay". You will be redirected to a secure third-party payment gateway, where you can complete your payment using any of the available methods.</li>
                                        <li><strong>Retrieve License Key:</strong> upon successful payment: your new license key will automatically download to your device. The same license key will also be displayed on the payment confirmation page, just below the button.</li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">3</div>
                        <div>
                            <h4 class="font-semibold mb-2">Activate Extended License</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Return to the License Extension page in your EDUHIVE application.</li>
                                <li>Paste the copied license into the provided field.</li>
                                <li>Click "Renew Now" to apply the new license.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm">4</div>
                        <div>
                            <h4 class="font-semibold mb-2">Confirmation</h4>
                            <ul class="text-slate-600 dark:text-slate-300 text-sm space-y-1 ml-2">
                                <li>Once verified the application will automatically extend your license expiry date, and you can continue using EDUHIVE without interruption.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

    </main>

   
</body>
</html>