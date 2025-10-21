<?php


// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: ..\login.php");
    exit();
}

include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            padding-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5>Welcome back, <?php echo htmlspecialchars($_SESSION['staffname']); ?>!</h5>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-12">
                <!-- Introduction -->
                <div class="card">
                    <div class="card-header">EDUHIVE Bursary Management: User Guide</div>
                    <div class="card-body">
                        <p>This guide covers the three core workflows in EDUHIVE&rsquo;s Bursary Management module:</p>
                        <ol>
                            <li>Register Students</li>
                            <li>Register School Fees</li>
                            <li>Assign Fees to Students</li>
                        </ol>
                        <p>Each section includes step-by-step instructions, field descriptions, and tips to ensure
                            smooth operation.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-12">
                <!-- Step 1 -->
                <div class="card">
                    <div class="card-header">Step 1: Register Students</div>
                    <div class="card-body">
                        <p>Before assigning any fees, each student must exist in the system.</p>
                        <ol>
                            <li>Navigate to <strong>Register Students</strong>.</li>
                            <li>Click the <strong>New</strong> button in the top-right corner.</li>
                            <li>Fill in the student details form:
                                <ul>
                                    <li><strong>Student ID</strong>: Unique alphanumeric code (e.g.,
                                        <code>STU2025-001</code>).</li>
                                </ul>
                            </li>
                            <li>Click <strong>Save</strong>. The student will now appear in the student list.</li>
                        </ol>
                        <p><strong>Validation:</strong> Student ID must be unique.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-12">
                <!-- Step 2 -->
                <div class="card">
                    <div class="card-header">Step 2: Register School Fees</div>
                    <div class="card-body">
                        <p>Define the fee types and structures that can be assigned.</p>
                        <ol>
                            <li>Navigate to <strong>School Fees</strong>.</li>
                            <li>Click the <strong>New Entry</strong> button.</li>
                            <li>Complete the fee form:</li>
                            <!-- Add form field descriptions here if needed -->
                            <li>Click <strong>Save</strong> to add the fee category.</li>
                        </ol>
                        <p><strong>Tip:</strong> Use clear, consistent naming for fee codes to avoid confusion when
                            assigning fees.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-12">
                <!-- Step 3 -->
                <div class="card">
                    <div class="card-header">Step 3: Assign Fees to Students</div>
                    <div class="card-body">
                        <p>Bind fee categories to individual students or groups.</p>
                        <ol>
                            <li>Navigate to <strong>Student Fees</strong>.</li>
                            <li>Click the <strong>New</strong> button.</li>
                            <li>Select the student.</li>
                            <li>Select the fee type from the list.</li>
                            <li>Click <strong>Save</strong>. Assigned fees will appear in the bursary ledger and on the
                                student&rsquo;s dashboard.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-12">
                <!-- Additional Operations -->
                <div class="card">
                    <div class="card-header">Additional Operations</div>
                    <div class="card-body">
                        <h6>View/Print Fee Statements</h6>
                        <ul>
                            <li>Go to <strong>Payments &gt; Payments Report</strong>.</li>
                            <li>Filter by month.</li>
                            <li>Click <strong>Print</strong> to view or <strong>Print/Export to PDF</strong>.</li>
                        </ul>

                        <h6>Record Payments (New Payment)</h6>
                        <ul>
                            <li>Navigate to <strong>Payments &gt; Payments</strong>.</li>
                            <li>Click the <strong>New</strong> button.</li>
                            <li>Select the student.</li>
                            <li>Insert the deposited <strong>Amount</strong>.</li>
                            <li>Enter <strong>Payment Remarks</strong>.</li>
                            <li>Click <strong>Save</strong>. Assigned fees will appear in the bursary ledger and on the
                                student&rsquo;s dashboard.</li>
                        </ul>

                        <h6>Update Payments (Existing Payment)</h6>
                        <ul>
                            <li>Navigate to <strong>Payments &gt; Payments</strong>.</li>
                            <li>Search for a student and click the <strong>Edit icon</strong> on the student&rsquo;s
                                record.</li>
                            <li>Update the <strong>Amount Paid</strong> and <strong>Remarks</strong>.</li>
                            <li>Click <strong>Save</strong> to update the student&rsquo;s outstanding balance.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<p></p>

    <script>
        $(document).ready(function () {
            $('#manage-records').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'ajax.php?action=save_track',
                    data: new FormData(this),
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    success: function (resp) {
                        let response = JSON.parse(resp);
                        if (response.status == 1) {
                            alert("Data successfully saved");
                            setTimeout(() => location.reload(), 800);
                        }
                    }
                });
            });

            $('#tracking_id').keypress(function (e) {
                if (e.which == 13) {
                    get_person();
                }
            });

            $('#check').click(get_person);

            function get_person() {
                $.ajax({
                    url: 'ajax.php?action=get_pdetails',
                    method: "POST",
                    data: { tracking_id: $('#tracking_id').val() },
                    success: function (resp) {
                        let response = JSON.parse(resp);
                        if (response.status == 1) {
                            $('#name').text(response.name);
                            $('#address').text(response.address);
                            $('[name="person_id"]').val(response.id);
                            $('#details').show();
                        } else {
                            alert("Unknown tracking ID.");
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>