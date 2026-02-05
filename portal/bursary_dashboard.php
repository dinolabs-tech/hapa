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
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">Search & Filter</h4>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="export-filtered-data">
                                            <i class="fas fa-download"></i> Export CSV
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm" id="export-all-data">
                                            <i class="fas fa-download"></i> Export All
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form id="search-form" class="row g-3">
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" id="search-student" placeholder="Search by student name or ID">
                                        </div>
                                        <div class="col-md-3">
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
                                        <div class="col-md-2">
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
        // Dashboard state management
        let currentFilters = {
            student: '',
            method: '',
            date_from: '',
            date_to: ''
        };
        
        let autoRefresh = false;
        let refreshInterval;
        let isUpdating = false;

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeDashboard();
            setupEventListeners();
        });

        function initializeDashboard() {
            // Initialize charts with current data
            initializeCharts();
            
            // Set current term and session
            document.getElementById('current-term').textContent = '<?= $current_term ?>';
            document.getElementById('current-session').textContent = '<?= $current_session ?>';
            
            // Initialize auto-refresh
            toggleAutoRefresh();
        }

        function setupEventListeners() {
            // Search form submission
            document.getElementById('search-form').addEventListener('submit', function(e) {
                e.preventDefault();
                applyFilters();
            });

            // Clear filters
            document.getElementById('clear-filters').addEventListener('click', function() {
                clearFilters();
            });

            // Real-time refresh
            document.getElementById('refresh-transactions').addEventListener('click', function() {
                updateDashboardData();
            });

            // Auto-refresh toggle
            document.getElementById('toggle-auto-refresh').addEventListener('click', toggleAutoRefresh);

            // Debounced search input
            const searchInput = document.getElementById('search-student');
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 2 || this.value.length === 0) {
                        currentFilters.student = this.value;
                        updateDashboardData();
                    }
                }, 500);
            });

            // Filter select changes
            document.getElementById('filter-method').addEventListener('change', function() {
                currentFilters.method = this.value;
                updateDashboardData();
            });

            // Date range changes
            document.getElementById('filter-date-from').addEventListener('change', function() {
                currentFilters.date_from = this.value;
                updateDashboardData();
            });

            document.getElementById('filter-date-to').addEventListener('change', function() {
                currentFilters.date_to = this.value;
                updateDashboardData();
            });

            // Export functionality
            document.getElementById('export-filtered-data').addEventListener('click', function() {
                exportFilteredData();
            });

            document.getElementById('export-all-data').addEventListener('click', function() {
                exportAllData();
            });
        }

        function applyFilters() {
            currentFilters.student = document.getElementById('search-student').value;
            currentFilters.method = document.getElementById('filter-method').value;
            currentFilters.date_from = document.getElementById('filter-date-from').value;
            currentFilters.date_to = document.getElementById('filter-date-to').value;
            
            updateDashboardData();
        }

        function clearFilters() {
            currentFilters = {
                student: '',
                method: '',
                date_from: '',
                date_to: ''
            };
            
            document.getElementById('search-form').reset();
            updateDashboardData();
        }

        async function updateDashboardData() {
            if (isUpdating) return;
            isUpdating = true;
            
            showLoadingState();
            
            try {
                const response = await fetch('api/transactions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'get_dashboard_data',
                        term: '<?= $current_term ?>',
                        session: '<?= $current_session ?>',
                        filters: currentFilters
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    updateFinancialSummary(data.financial_summary);
                    updatePaymentMethods(data.payment_methods);
                    updateRecentTransactions(data.recent_transactions);
                    updatePaymentTrends(data.payment_trends);
                    updateFeeCollectionStatus(data.fee_collection_status);
                    updateActiveFiltersIndicator();
                } else {
                    console.error('Error fetching dashboard data:', data.message);
                    showErrorMessage('Failed to update dashboard data');
                }
            } catch (error) {
                console.error('Error:', error);
                showErrorMessage('Network error while updating dashboard');
            } finally {
                hideLoadingState();
                isUpdating = false;
                updateLastUpdateTime();
            }
        }

        function updateFinancialSummary(summary) {
            // Update financial cards
            document.querySelector('.card.bg-primary .fw-bold').textContent = formatCurrency(summary.total_revenue);
            document.querySelector('.card.bg-warning .fw-bold').textContent = formatCurrency(summary.outstanding_balance);
            document.querySelector('.card.bg-info .fw-bold').textContent = formatCurrency(summary.todays_payments);
            document.querySelector('.card.bg-success .fw-bold').textContent = summary.payment_methods_count || 0;
        }

        function updatePaymentMethods(methods) {
            // Update payment methods chart
            const methodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
            const methodsData = {
                labels: methods.map(m => m.payment_method),
                datasets: [{
                    data: methods.map(m => m.total),
                    backgroundColor: [
                        '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#20c997'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            };
            
            if (window.methodsChart) {
                window.methodsChart.data = methodsData;
                window.methodsChart.update();
            }
            
            // Update payment methods list
            const methodsList = document.querySelector('.card-body .mt-3');
            if (methodsList) {
                methodsList.innerHTML = '';
                methods.forEach(method => {
                    const div = document.createElement('div');
                    div.className = 'd-flex justify-content-between mb-2';
                    div.innerHTML = `
                        <span>${method.payment_method}</span>
                        <span class="badge bg-primary">${method.count} transactions</span>
                    `;
                    methodsList.appendChild(div);
                });
            }
        }

        function updateRecentTransactions(transactions) {
            const tbody = document.getElementById('transaction-feed');
            tbody.innerHTML = '';
            
            transactions.forEach(transaction => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${formatTime(transaction.payment_date)}</td>
                    <td>${transaction.name}</td>
                    <td>${transaction.amount_display}</td>
                    <td><span class="badge bg-primary">${transaction.payment_method}</span></td>
                    <td>${transaction.receipt_number}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function updatePaymentTrends(trends) {
            const trendsCtx = document.getElementById('paymentTrendsChart').getContext('2d');
            const trendsData = {
                labels: trends.map(t => t.date),
                datasets: [{
                    label: 'Daily Payments (₦)',
                    data: trends.map(t => t.total),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            };
            
            if (window.trendsChart) {
                window.trendsChart.data = trendsData;
                window.trendsChart.update();
            }
        }

        function updateFeeCollectionStatus(status) {
            const tbody = document.querySelector('.card-body .table-responsive tbody');
            tbody.innerHTML = '';
            
            status.forEach(item => {
                const row = document.createElement('tr');
                const progressClass = item.collection_rate >= 80 ? 'bg-success' : (item.collection_rate >= 50 ? 'bg-warning' : 'bg-danger');
                row.innerHTML = `
                    <td>${item.class}</td>
                    <td>${item.collected_display}</td>
                    <td>${item.total_fee_display}</td>
                    <td>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar ${progressClass}" style="width: ${item.collection_rate}%"></div>
                        </div>
                        <small>${item.collection_rate.toFixed(1)}%</small>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function updateActiveFiltersIndicator() {
            const activeFilters = Object.values(currentFilters).filter(v => v !== '').length;
            const indicator = document.querySelector('.ms-md-auto .badge');
            if (activeFilters > 0) {
                indicator.textContent = `FILTERS ACTIVE (${activeFilters})`;
                indicator.classList.remove('bg-danger');
                indicator.classList.add('bg-info');
            } else {
                indicator.textContent = 'LIVE UPDATES';
                indicator.classList.remove('bg-info');
                indicator.classList.add('bg-danger');
            }
        }

        function initializeCharts() {
            // Initialize payment trends chart
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
            
            window.trendsChart = new Chart(trendsCtx, {
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

            // Initialize payment methods chart
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
            
            window.methodsChart = new Chart(methodsCtx, {
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
                refreshInterval = setInterval(() => {
                    if (!isUpdating) {
                        updateDashboardData();
                    }
                }, 30000); // Update every 30 seconds
            } else {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
                icon.classList.remove('fa-pause');
                icon.classList.add('fa-play');
                clearInterval(refreshInterval);
            }
        }

        function updateLastUpdateTime() {
            document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
        }

        function showLoadingState() {
            const feed = document.getElementById('transaction-feed');
            feed.style.opacity = '0.5';
            
            // Add loading spinner to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach(card => {
                const header = card.querySelector('.card-header');
                if (header) {
                    header.style.position = 'relative';
                    let spinner = header.querySelector('.loading-spinner');
                    if (!spinner) {
                        spinner = document.createElement('div');
                        spinner.className = 'loading-spinner';
                        spinner.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                        spinner.style.position = 'absolute';
                        spinner.style.right = '10px';
                        spinner.style.top = '50%';
                        spinner.style.transform = 'translateY(-50%)';
                        header.appendChild(spinner);
                    }
                    spinner.style.display = 'block';
                }
            });
        }

        function hideLoadingState() {
            const feed = document.getElementById('transaction-feed');
            feed.style.opacity = '1';
            
            // Hide loading spinners
            const spinners = document.querySelectorAll('.loading-spinner');
            spinners.forEach(spinner => {
                spinner.style.display = 'none';
            });
        }

        function showErrorMessage(message) {
            // Create error alert
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger alert-dismissible fade show';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const container = document.querySelector('.page-inner');
            container.insertBefore(alert, container.firstChild);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }

        function formatCurrency(amount) {
            return '₦' + (amount || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function formatTime(dateString) {
            return new Date(dateString).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        async function exportFilteredData() {
            try {
                const response = await fetch('api/transactions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'export_transactions',
                        term: '<?= $current_term ?>',
                        session: '<?= $current_session ?>',
                        format: 'csv',
                        student: currentFilters.student,
                        method: currentFilters.method,
                        date_from: currentFilters.date_from,
                        date_to: currentFilters.date_to
                    })
                });

                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `filtered_transactions_${new Date().toISOString().slice(0, 10)}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                    showSuccessMessage('Filtered data exported successfully');
                } else {
                    throw new Error('Export failed');
                }
            } catch (error) {
                console.error('Export error:', error);
                showErrorMessage('Failed to export filtered data');
            }
        }

        async function exportAllData() {
            try {
                const response = await fetch('api/transactions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'export_transactions',
                        term: '<?= $current_term ?>',
                        session: '<?= $current_session ?>',
                        format: 'csv'
                    })
                });

                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `all_transactions_${new Date().toISOString().slice(0, 10)}.csv`;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);
                    showSuccessMessage('All data exported successfully');
                } else {
                    throw new Error('Export failed');
                }
            } catch (error) {
                console.error('Export error:', error);
                showErrorMessage('Failed to export all data');
            }
        }

        function showSuccessMessage(message) {
            // Create success alert
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const container = document.querySelector('.page-inner');
            container.insertBefore(alert, container.firstChild);
            
            // Auto-dismiss after 3 seconds
            setTimeout(() => {
                alert.remove();
            }, 3000);
        }
    </script>
</body>
</html>