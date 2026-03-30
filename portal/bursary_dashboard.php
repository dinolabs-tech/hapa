<?php
include('components/admin_logic.php');
require_once('helpers/audit.php');
require_once('helpers/money.php');

// Fetch current term and session
$current_term = $mysqli->query("SELECT cterm FROM currentterm LIMIT 1")->fetch_assoc()['cterm'] ?? '1st Term';
$current_session = $mysqli->query("SELECT csession FROM currentsession LIMIT 1")->fetch_assoc()['csession'] ?? '2024/2025';

// Minimal initial data - just the essentials for page load
// Full data will be loaded via AJAX for better performance
$alerts = [];
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
        
        .live-indicator.disconnected {
            background-color: #dc3545;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.7; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .trend-up { color: #28a745; }
        .trend-down { color: #dc3545; }
        .trend-neutral { color: #6c757d; }
        
        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 4px;
            min-height: 1em;
            min-width: 3em;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        .card-value-loading {
            display: inline-block;
            width: 60%;
            height: 1.5em;
        }
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
                            <span class="live-indicator" id="live-indicator"></span>
                            <span class="badge bg-danger" id="connection-status">CONNECTING...</span>
                        </div>
                    </div>

                    <?php foreach ($alerts as [$type, $msg]): ?>
                        <div class="alert alert-<?= $type ?>"><?= htmlspecialchars($msg) ?></div>
                    <?php endforeach; ?>

                    <!-- Financial Overview Cards -->
                    <div class="row" id="financial-cards">
                        <div class="col-sm-6 col-md-3">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <h5 class="card-title">Total Revenue</h5>
                                            <h2 class="fw-bold"><span class="loading-skeleton card-value-loading"></span></h2>
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
                                            <h2 class="fw-bold"><span class="loading-skeleton card-value-loading"></span></h2>
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
                                            <h2 class="fw-bold"><span class="loading-skeleton card-value-loading"></span></h2>
                                            <small id="today-date"><?= date('F j, Y') ?></small>
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
                                            <h2 class="fw-bold"><span class="loading-skeleton card-value-loading"></span></h2>
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
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        <div class="loading-skeleton" style="height: 200px;"></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mt-2">
                                        <small class="text-muted">Last updated: <span id="last-update">--:--:--</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Trends Chart -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h4 class="card-title">Payment Trends (Last 12 Months)</h4>
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
                                    <div class="mt-3" id="payment-methods-list">
                                        <div class="loading-skeleton" style="height: 100px;"></div>
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
                                    <div class="table-responsive" id="fee-collection-container">
                                        <div class="loading-skeleton" style="height: 200px;"></div>
                                    </div>
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
    
    <script>
        // Dashboard state management
        let trendsChart = null;
        let methodsChart = null;
        let sseConnection = null;
        let reconnectAttempts = 0;
        const MAX_RECONNECT_ATTEMPTS = 5;

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeDashboard();
        });

        function initializeDashboard() {
            // Load initial data via AJAX
            loadDashboardData();
            
            // Initialize SSE for real-time updates
            initializeSSE();
            
            // Setup refresh button
            document.getElementById('refresh-transactions').addEventListener('click', function() {
                loadDashboardData(true);
            });
        }

        // Load dashboard data via AJAX
        async function loadDashboardData(forceRefresh = false) {
            try {
                const url = forceRefresh 
                    ? 'api/bursary/dashboard.php?refresh=true'
                    : 'api/bursary/dashboard.php';
                    
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-User-Id': '<?= $_SESSION['user_id'] ?? '' ?>'
                    }
                });

                const result = await response.json();
                
                if (result.status === 'success') {
                    updateDashboard(result.data);
                } else {
                    console.error('Error loading dashboard:', result.message);
                }
            } catch (error) {
                console.error('Network error:', error);
            }
        }

        // Update dashboard with new data
        function updateDashboard(data) {
            // Update financial cards
            updateFinancialCards(data.financial_summary);
            
            // Update transaction feed
            updateTransactionFeed(data.recent_transactions);
            
            // Update payment methods
            updatePaymentMethods(data.payment_methods);
            
            // Update fee collection status
            updateFeeCollectionStatus(data.fee_collection_status);
            
            // Update charts
            updateCharts(data.payment_methods, data.payment_trends);
            
            // Update last update time
            document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
        }

        function updateFinancialCards(summary) {
            const cards = document.querySelectorAll('#financial-cards .card');
            if (cards.length >= 4) {
                // Total Revenue
                cards[0].querySelector('.fw-bold').textContent = formatCurrency(summary.total_revenue);
                // Outstanding Balance
                cards[1].querySelector('.fw-bold').textContent = formatCurrency(summary.outstanding_balance);
                // Today's Payments
                cards[2].querySelector('.fw-bold').textContent = formatCurrency(summary.todays_payments);
                // Payment Methods
                cards[3].querySelector('.fw-bold').textContent = summary.payment_methods_count || 0;
            }
        }

        function updateTransactionFeed(transactions) {
            const tbody = document.getElementById('transaction-feed');
            tbody.innerHTML = '';
            
            if (transactions.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No recent transactions</td></tr>';
                return;
            }
            
            transactions.forEach(transaction => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${formatTime(transaction.payment_date)}</td>
                    <td>${escapeHtml(transaction.name)}</td>
                    <td>${formatCurrency(transaction.amount)}</td>
                    <td><span class="badge bg-primary">${escapeHtml(transaction.payment_method)}</span></td>
                    <td>${escapeHtml(transaction.receipt_number)}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function updatePaymentMethods(methods) {
            const container = document.getElementById('payment-methods-list');
            container.innerHTML = '';
            
            methods.forEach(method => {
                const div = document.createElement('div');
                div.className = 'd-flex justify-content-between mb-2';
                div.innerHTML = `
                    <span>${escapeHtml(method.payment_method)}</span>
                    <span class="badge bg-primary">${method.count} transactions</span>
                `;
                container.appendChild(div);
            });
        }

        function updateFeeCollectionStatus(status) {
            const container = document.getElementById('fee-collection-container');
            container.innerHTML = `
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
                        ${status.map(item => `
                            <tr>
                                <td>${escapeHtml(item.class)}</td>
                                <td>${item.collected_display}</td>
                                <td>${item.total_fee_display}</td>
                                <td>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar ${item.collection_rate >= 80 ? 'bg-success' : (item.collection_rate >= 50 ? 'bg-warning' : 'bg-danger')}" 
                                             style="width: ${item.collection_rate}%"></div>
                                    </div>
                                    <small>${item.collection_rate.toFixed(1)}%</small>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }

        function updateCharts(methods, trends) {
            // Update or create trends chart
            const trendsCtx = document.getElementById('paymentTrendsChart').getContext('2d');
            
            if (trendsChart) {
                trendsChart.data.labels = trends.map(t => t.date);
                trendsChart.data.datasets[0].data = trends.map(t => t.total);
                trendsChart.update();
            } else {
                trendsChart = new Chart(trendsCtx, {
                    type: 'line',
                    data: {
                        labels: trends.map(t => t.date),
                        datasets: [{
                            label: 'Monthly Payments (₦)',
                            data: trends.map(t => t.total),
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
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
            }

            // Update or create methods chart
            const methodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
            
            if (methodsChart) {
                methodsChart.data.labels = methods.map(m => m.payment_method);
                methodsChart.data.datasets[0].data = methods.map(m => m.total);
                methodsChart.update();
            } else {
                methodsChart = new Chart(methodsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: methods.map(m => m.payment_method),
                        datasets: [{
                            data: methods.map(m => m.total),
                            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1', '#20c997'],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }
        }

        // SSE Connection Management
        function initializeSSE() {
            if (typeof(EventSource) === 'undefined') {
                console.log('SSE not supported, falling back to polling');
                startPolling();
                return;
            }

            connectSSE();
        }

        function connectSSE() {
            const url = 'api/bursary/sse.php?term=<?= $current_term ?>&session=<?= $current_session ?>';
            
            sseConnection = new EventSource(url);
            
            sseConnection.onopen = function() {
                reconnectAttempts = 0;
                updateConnectionStatus(true);
            };
            
            sseConnection.addEventListener('connected', function(e) {
                console.log('SSE connected:', JSON.parse(e.data));
            });
            
            sseConnection.addEventListener('initial_data', function(e) {
                const data = JSON.parse(e.data);
                updateDashboard(data);
            });
            
            sseConnection.addEventListener('new_payments', function(e) {
                const data = JSON.parse(e.data);
                console.log('New payments received:', data);
                
                // Update financial summary
                updateFinancialCards(data.stats);
                
                // Prepend new transactions to feed
                const tbody = document.getElementById('transaction-feed');
                data.payments.forEach(payment => {
                    const row = document.createElement('tr');
                    row.className = 'table-success';
                    row.innerHTML = `
                        <td>${formatTime(payment.payment_date)}</td>
                        <td>${escapeHtml(payment.name)}</td>
                        <td>${formatCurrency(payment.amount)}</td>
                        <td><span class="badge bg-primary">${escapeHtml(payment.payment_method)}</span></td>
                        <td>${escapeHtml(payment.receipt_number)}</td>
                    `;
                    tbody.insertBefore(row, tbody.firstChild);
                    
                    // Remove highlight after 3 seconds
                    setTimeout(() => row.classList.remove('table-success'), 3000);
                });
                
                document.getElementById('last-update').textContent = new Date().toLocaleTimeString();
            });
            
            sseConnection.addEventListener('timeout', function(e) {
                console.log('SSE timeout, reconnecting...');
                sseConnection.close();
                connectSSE();
            });
            
            sseConnection.onerror = function() {
                sseConnection.close();
                updateConnectionStatus(false);
                
                reconnectAttempts++;
                if (reconnectAttempts < MAX_RECONNECT_ATTEMPTS) {
                    setTimeout(connectSSE, 3000 * reconnectAttempts);
                } else {
                    console.log('Max reconnect attempts reached, switching to polling');
                    startPolling();
                }
            };
        }

        function startPolling() {
            // Fallback to 30-second polling if SSE fails
            setInterval(() => {
                loadDashboardData();
            }, 30000);
        }

        function updateConnectionStatus(connected) {
            const indicator = document.getElementById('live-indicator');
            const status = document.getElementById('connection-status');
            
            if (connected) {
                indicator.classList.remove('disconnected');
                status.textContent = 'LIVE UPDATES';
                status.classList.remove('bg-secondary');
                status.classList.add('bg-danger');
            } else {
                indicator.classList.add('disconnected');
                status.textContent = 'RECONNECTING...';
                status.classList.remove('bg-danger');
                status.classList.add('bg-secondary');
            }
        }

        // Utility functions
        function formatCurrency(amount) {
            return '₦' + (amount || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function formatTime(dateString) {
            return new Date(dateString).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>