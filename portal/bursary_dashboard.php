<?php
include('components/admin_logic.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');

// Fetch current term and session
$current_term = $mysqli->query("SELECT cterm FROM currentterm LIMIT 1")->fetch_assoc()['cterm'] ?? '1st Term';
$current_session = $mysqli->query("SELECT csession FROM currentsession LIMIT 1")->fetch_assoc()['csession'] ?? '2024/2025';

// Fetch financial summaries
$alerts = [];

// Total revenue (all payments)
$total_revenue = $mysqli->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE term = '$current_term' AND session = '$current_session'")->fetch_assoc()['total'] ?? 0;

// Outstanding balance (sum of all unpaid fees)
$outstanding_balance = $mysqli->query("SELECT COALESCE(SUM(sfi.amount - sfi.paid_amount), 0) as total FROM student_fee_items sfi JOIN student_fees sf ON sfi.student_fee_id = sf.id WHERE sf.status='active' AND sf.term = '$current_term' AND sf.session = '$current_session' AND sfi.amount > sfi.paid_amount")->fetch_assoc()['total'] ?? 0;

// Today's payments
$today = date('Y-m-d');
$todays_payments = $mysqli->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE DATE(payment_date) = '$today' AND term = '$current_term' AND session = '$current_session'")->fetch_assoc()['total'] ?? 0;

// Payment methods distribution
$payment_methods = [];
$result = $mysqli->query("SELECT payment_method, COUNT(*) as count, SUM(amount) as total FROM payments WHERE term = '$current_term' AND session = '$current_session' GROUP BY payment_method ORDER BY total DESC");
while ($row = $result->fetch_assoc()) {
    $payment_methods[] = $row;
}

// Recent transactions (last 10)
$recent_transactions = [];
$result = $mysqli->query("SELECT p.id, p.student_id, s.name, p.amount, p.payment_method, p.payment_date, p.receipt_number FROM payments p JOIN students s ON p.student_id = s.id WHERE p.term = '$current_term' AND p.session = '$current_session' ORDER BY p.payment_date DESC LIMIT 10");
while ($row = $result->fetch_assoc()) {
    $row['amount_display'] = money_format_naira($row['amount']);
    $recent_transactions[] = $row;
}

// Payment trends (last 7 days)
$payment_trends = [];
$result = $mysqli->query("SELECT DATE(payment_date) as date, SUM(amount) as total FROM payments WHERE payment_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND term = '$current_term' AND session = '$current_session' GROUP BY DATE(payment_date) ORDER BY date");
while ($row = $result->fetch_assoc()) {
    $payment_trends[] = $row;
}

// Fee collection status by class
$fee_collection_status = [];
$result = $mysqli->query("SELECT s.class, COUNT(DISTINCT s.id) as student_count, COALESCE(SUM(sfi.paid_amount), 0) as collected, COALESCE(SUM(sfi.amount), 0) as total_fee FROM students s LEFT JOIN student_fees sf ON s.id = sf.student_id AND sf.status='active' AND sf.term = '$current_term' AND sf.session = '$current_session' LEFT JOIN student_fee_items sfi ON sf.id = sfi.student_fee_id GROUP BY s.class ORDER BY s.class");
while ($row = $result->fetch_assoc()) {
    $row['collection_rate'] = $row['total_fee'] > 0 ? ($row['collected'] / $row['total_fee']) * 100 : 0;
    $row['collected_display'] = money_format_naira($row['collected']);
    $row['total_fee_display'] = money_format_naira($row['total_fee']);
    $fee_collection_status[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php'); ?>

<head>
    <style>
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .live-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #28a745;
            border-radius: 50%;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .trend-up { color: #28a745; }
        .trend-down { color: #dc3545; }
        .trend-neutral { color: #6c757d; }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include('adminnav.php'); ?>
        <!-- End Sidebar -->

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
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Bursary Dashboard</h3>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                                <li class="breadcrumb-item active">Bursary Dashboard</li>
                            </ol>
                        </div>
                        <div class="ms-md-auto py-2 py-md-0">
                            <span class="live-indicator"></span>
                            <span class="badge bg-danger">LIVE UPDATES</span>
                        </div>
                    </div>

                    <?php foreach ($alerts as [$type, $msg]): ?>
                        <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
                    <?php endforeach; ?>

                    <!-- Financial Overview Cards -->
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <h5 class="card-title">Total Revenue</h5>
                                            <h2 class="fw-bold"><?= money_format_naira($total_revenue) ?></h2>
                                            <small><?= $current_term ?> • <?= $current_session ?></small>
                                        </div>
                                        <div class="col-3">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-coins fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <h5 class="card-title">Outstanding Balance</h5>
                                            <h2 class="fw-bold"><?= money_format_naira($outstanding_balance) ?></h2>
                                            <small><?= $current_term ?> • <?= $current_session ?></small>
                                        </div>
                                        <div class="col-3">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="card bg-info text-white mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <h5 class="card-title">Today's Payments</h5>
                                            <h2 class="fw-bold"><?= money_format_naira($todays_payments) ?></h2>
                                            <small><?= date('F j, Y') ?></small>
                                        </div>
                                        <div class="col-3">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-3">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <h5 class="card-title">Payment Methods</h5>
                                            <h2 class="fw-bold"><?= count($payment_methods) ?></h2>
                                            <small>Active Methods</small>
                                        </div>
                                        <div class="col-3">
                                            <div class="icon-big text-center">
                                                <i class="fas fa-credit-card fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Dashboard Content -->
                    <div class="row">
                        <!-- Real-time Transaction Feed -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Real-time Transaction Feed</h4>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="refresh-transactions">
                                            <i class="fas fa-sync-alt"></i> Refresh
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="toggle-auto-refresh">
                                            <i class="fas fa-play"></i> Auto
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Time</th>
                                                    <th>Student</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Receipt</th>
                                                </tr>
                                            </thead>
                                            <tbody id="transaction-feed">
                                                <?php foreach ($recent_transactions as $transaction): ?>
                                                    <tr>
                                                        <td><?= date('H:i', strtotime($transaction['payment_date'])) ?></td>
                                                        <td><?= htmlspecialchars($transaction['name']) ?></td>
                                                        <td><?= $transaction['amount_display'] ?></td>
                                                        <td><span class="badge bg-primary"><?= htmlspecialchars($transaction['payment_method']) ?></span></td>
                                                        <td><?= htmlspecialchars($transaction['receipt_number']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mt-2">
                                        <small class="text-muted">Last updated: <span id="last-update"><?= date('H:i:s') ?></span></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Trends Chart -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-title">Payment Trends (Last 7 Days)</h4>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="paymentTrendsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions and Analytics -->
                    <div class="row">
                        <!-- Quick Actions -->
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-title">Quick Actions</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <a href="students_for_payments.php" class="btn btn-success rounded-5 w-100">
                                                <i class="fas fa-users me-2"></i>Outstanding Fees
                                            </a>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <a href="payments_list.php" class="btn btn-info rounded-5 w-100">
                                                <i class="fas fa-list me-2"></i>Payment List
                                            </a>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <a href="reports_transactions.php" class="btn btn-warning rounded-5 w-100">
                                                <i class="fas fa-chart-bar me-2"></i>Transaction Reports
                                            </a>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <a href="assign_fees.php" class="btn btn-secondary rounded-5 w-100">
                                                <i class="fas fa-money-bill-wave me-2"></i>Assign Fees
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods Distribution -->
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-title">Payment Methods Distribution</h4>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="paymentMethodsChart"></canvas>
                                    </div>
                                    <div class="mt-3">
                                        <?php foreach ($payment_methods as $method): ?>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span><?= htmlspecialchars($method['payment_method']) ?></span>
                                                <span class="badge bg-primary"><?= $method['count'] ?> transactions</span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fee Collection Status -->
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-title">Fee Collection Status by Class</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Class</th>
                                                    <th>Collected</th>
                                                    <th>Total</th>
                                                    <th>Rate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($fee_collection_status as $status): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($status['class']) ?></td>
                                                        <td><?= $status['collected_display'] ?></td>
                                                        <td><?= $status['total_fee_display'] ?></td>
                                                        <td>
                                                            <div class="progress" style="height: 10px;">
                                                                <div class="progress-bar <?= $status['collection_rate'] >= 80 ? 'bg-success' : ($status['collection_rate'] >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                                                                     style="width: <?= $status['collection_rate'] ?>%"></div>
                                                            </div>
                                                            <small><?= number_format($status['collection_rate'], 1) ?>%</small>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filter Section -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-title">Search & Filter</h4>
                                </div>
                                <div class="card-body">
                                    <form id="search-form" class="row g-3">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" id="search-student" placeholder="Search by student name or ID">
                                        </div>
                                        <div class="col-md-2">
                                            <select class="form-select form-control" id="filter-method">
                                                <option value="">All Payment Methods</option>
                                                <option value="cash">Cash</option>
                                                <option value="bank">Bank Transfer</option>
                                                <option value="pos">POS</option>
                                                <option value="refund">Refund</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" class="form-control" id="filter-date-from">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="date" class="form-control" id="filter-date-to">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary btn-icon btn-round">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary btn-icon btn-round" id="clear-filters">
                                                    <i class="fas fa-eraser"></i> 
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php include('footer.php'); ?>
        </div>

        <!-- Custom template | don't include it in your project! -->
        <?php include('cust-color.php'); ?>
        <!-- End Custom template -->
    </div>
    
    <?php include('scripts.php'); ?>

    <!-- Chart.js for visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Real-time updates -->
    <script src="helpers/realtime_updates.js"></script>
    
    <script>
        // Payment Trends Chart
        const trendsCtx = document.getElementById('paymentTrendsChart').getContext('2d');
        const trendsData = {
            labels: <?= json_encode(array_column($payment_trends, 'date')) ?>,
            datasets: [{
                label: 'Daily Payments (₦)',
                data: <?= json_encode(array_column($payment_trends, 'total')) ?>,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        };
        
        const trendsChart = new Chart(trendsCtx, {
            type: 'line',
            data: trendsData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₦' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Payment Methods Chart
        const methodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
        const methodsData = {
            labels: <?= json_encode(array_column($payment_methods, 'payment_method')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($payment_methods, 'total')) ?>,
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#20c997'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        };
        
        const methodsChart = new Chart(methodsCtx, {
            type: 'doughnut',
            data: methodsData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Real-time updates
        let autoRefresh = false;
        let refreshInterval;

        function updateLastUpdateTime() {
            document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
        }

        function refreshTransactions() {
            // Simulate real-time update (in production, this would be an AJAX call)
            updateLastUpdateTime();
            
            // Add a subtle animation to indicate refresh
            const feed = document.getElementById('transaction-feed');
            feed.style.opacity = '0.5';
            setTimeout(() => {
                feed.style.opacity = '1';
            }, 300);
        }

        function toggleAutoRefresh() {
            autoRefresh = !autoRefresh;
            const btn = document.getElementById('toggle-auto-refresh');
            const icon = btn.querySelector('i');
            
            if (autoRefresh) {
                btn.classList.remove('btn-outline-secondary');
                btn.classList.add('btn-success');
                icon.classList.remove('fa-play');
                icon.classList.add('fa-pause');
                refreshInterval = setInterval(refreshTransactions, 30000); // Update every 30 seconds
            } else {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
                icon.classList.remove('fa-pause');
                icon.classList.add('fa-play');
                clearInterval(refreshInterval);
            }
        }

        // Event listeners
        document.getElementById('refresh-transactions').addEventListener('click', refreshTransactions);
        document.getElementById('toggle-auto-refresh').addEventListener('click', toggleAutoRefresh);
        
        document.getElementById('search-form').addEventListener('submit', function(e) {
            e.preventDefault();
            // In production, this would trigger an AJAX search
            alert('Search functionality would be implemented here with real-time filtering.');
        });

        document.getElementById('clear-filters').addEventListener('click', function() {
            document.getElementById('search-form').reset();
        });

        document.getElementById('export-data').addEventListener('click', function() {
            // In production, this would export the filtered data
            alert('Export functionality would be implemented here.');
        });

        // Initialize auto-refresh
        toggleAutoRefresh();
    </script>
</body>
</html>