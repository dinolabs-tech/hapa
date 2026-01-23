<?php

// Start the session to maintain user state
session_start();

// Check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
include 'db_connection.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Queries to retrieve the data
$total_sales_query = "SELECT SUM(amount) FROM transactiondetails WHERE description='Sales'";
$total_profit_query = "SELECT SUM(profit) FROM transactiondetails WHERE description='Sales'";
$inventory_qty_query = "SELECT COUNT(qty) FROM product";
$inventory_sum_query = "SELECT SUM(total) FROM product";
$total_transactions_query = "SELECT COUNT(rownumber) FROM transactiondetails";
$out_of_stock_query = "SELECT * FROM product WHERE qty=0";
$tuck_students_qty_query = "SELECT COUNT(regno) FROM tuck";
$tuck_balance_query = "SELECT SUM(vbalance) FROM tuck";
$tuck_low_balance_query = "SELECT COUNT(regno) FROM tuck WHERE vbalance = 0";

// New queries for comprehensive dashboard
$today_sales_query = "SELECT SUM(amount) FROM transactiondetails WHERE DATE(transactiondate) = CURDATE() AND description='Sales'";
$yesterday_sales_query = "SELECT SUM(amount) FROM transactiondetails WHERE DATE(transactiondate) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND description='Sales'";
$week_sales_query = "SELECT SUM(amount) FROM transactiondetails WHERE YEARWEEK(transactiondate) = YEARWEEK(CURDATE()) AND description='Sales'";
$month_sales_query = "SELECT SUM(amount) FROM transactiondetails WHERE MONTH(transactiondate) = MONTH(CURDATE()) AND YEAR(transactiondate) = YEAR(CURDATE()) AND description='Sales'";

$low_stock_query = "SELECT * FROM product WHERE qty <= reorder_level AND qty > 0 ORDER BY qty ASC LIMIT 5";
$recent_transactions_query = "SELECT transactionID, studentname, productname, amount, transactiondate, cashier FROM transactiondetails WHERE description='Sales' ORDER BY transactiondate DESC LIMIT 10";

$top_products_query = "SELECT productname, SUM(units) as total_qty, SUM(amount) as total_revenue FROM transactiondetails WHERE description='Sales' GROUP BY productname ORDER BY total_revenue DESC LIMIT 5";

$avg_transaction_query = "SELECT AVG(amount) FROM transactiondetails WHERE description='Sales'";

// Chart data queries
$sales_trend_query = "SELECT DATE(transactiondate) as date, SUM(amount) as total_sales FROM transactiondetails WHERE description='Sales' AND transactiondate >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) GROUP BY DATE(transactiondate) ORDER BY date";

$monthly_sales_query = "SELECT DATE_FORMAT(transactiondate, '%Y-%m') as month, SUM(amount) as total_sales FROM transactiondetails WHERE description='Sales' AND transactiondate >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY DATE_FORMAT(transactiondate, '%Y-%m') ORDER BY month";

$transaction_volume_query = "SELECT DATE(transactiondate) as date, COUNT(*) as transaction_count FROM transactiondetails WHERE description='Sales' AND transactiondate >= DATE_SUB(CURDATE(), INTERVAL 14 DAY) GROUP BY DATE(transactiondate) ORDER BY date";

$top_products_chart_query = "SELECT productname, SUM(amount) as total_revenue FROM transactiondetails WHERE description='Sales' GROUP BY productname ORDER BY total_revenue DESC LIMIT 10";

// Function to safely execute queries and handle errors
// Function to safely execute queries and handle errors
function executeQuery($conn, $sql) {
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Error preparing statement: " . $conn->error . " in " . __FILE__ . " on line " . __LINE__);
        return null;
    }
    if ($stmt->execute() === false) {
        error_log("Error executing statement: " . $stmt->error . " in " . __FILE__ . " on line " . __LINE__);
        return null;
    }

    $value = null; // Initialize $value to null
    $stmt->bind_result($value); // Bind the single result column to a variable
    $stmt->fetch(); // Fetch the result
    $stmt->close();
    return $value; // Return the fetched value directly
}

// Execute the queries
$total_sales = executeQuery($conn, $total_sales_query);
$total_profit = executeQuery($conn, $total_profit_query);
$inventory_qty = executeQuery($conn, $inventory_qty_query);
$inventory_sum = executeQuery($conn, $inventory_sum_query);
$total_transactions = executeQuery($conn, $total_transactions_query);
// Changed out_of_stock_query to COUNT(*) to return a single value
$out_of_stock_query = "SELECT COUNT(*) FROM product WHERE qty=0";
$out_of_stock = executeQuery($conn, $out_of_stock_query);
$total_tuck_students = executeQuery($conn, $tuck_students_qty_query);
$total_students_balance = executeQuery($conn, $tuck_balance_query);
$total_low_balance = executeQuery($conn, $tuck_low_balance_query);

// Execute new queries
$today_sales = executeQuery($conn, $today_sales_query);
$yesterday_sales = executeQuery($conn, $yesterday_sales_query);
$week_sales = executeQuery($conn, $week_sales_query);
$month_sales = executeQuery($conn, $month_sales_query);
$avg_transaction = executeQuery($conn, $avg_transaction_query);

// Handle potential nulls for new queries
if (!isset($today_sales)) $today_sales = 0;
if (!isset($yesterday_sales)) $yesterday_sales = 0;
if (!isset($week_sales)) $week_sales = 0;
if (!isset($month_sales)) $month_sales = 0;
if (!isset($avg_transaction)) $avg_transaction = 0;

// Fetch arrays for complex queries
$low_stock_items = [];
$result = $conn->query($low_stock_query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $low_stock_items[] = $row;
    }
    $result->close();
}

$recent_transactions = [];
$result = $conn->query($recent_transactions_query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recent_transactions[] = $row;
    }
    $result->close();
}

$top_products = [];
$result = $conn->query($top_products_query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $top_products[] = $row;
    }
    $result->close();
}

// Fetch chart data
$sales_trend_data = [];
$result = $conn->query($sales_trend_query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $sales_trend_data[] = $row;
    }
    $result->close();
}

$monthly_sales_data = [];
$result = $conn->query($monthly_sales_query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $monthly_sales_data[] = $row;
    }
    $result->close();
}

$transaction_volume_data = [];
$result = $conn->query($transaction_volume_query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $transaction_volume_data[] = $row;
    }
    $result->close();
}

$top_products_chart_data = [];
$result = $conn->query($top_products_chart_query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $top_products_chart_data[] = $row;
    }
    $result->close();
}

// Handle potential nulls from executeQuery
if (!isset($total_sales)) {
    $total_sales = 0;
}
if (!isset($total_profit)) {
    $total_profit = 0;
}
if (!isset($inventory_qty)) {
    $inventory_qty = 0;
}
if (!isset($inventory_sum)) {
    $inventory_sum = 0;
}
if (!isset($total_transactions)) {
    $total_transactions = 0;
}
if (!isset($out_of_stock)) {
    $out_of_stock = 0;
}
if (!isset($total_tuck_students)) {
    $total_tuck_students = 0;
}
if (!isset($total_students_balance)) {
    $total_students_balance = 0;
}
if (!isset($total_low_balance)) {
    $total_low_balance = 0;
}



// Fetch the logged-in Staff name
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT staffname FROM login WHERE id=?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$stmt->bind_result($student_name);
$stmt->fetch();
$stmt->close();

// Close database connection
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<?php include('head.php');?>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
     <?php include('adminnav.php');?>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <?php include('logo_header.php');?>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
         <?php include('navbar.php');?>
          <!-- End Navbar -->
        </div>

        <div class="container">
          <div class="page-inner">
            <div
              class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
              <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item active">Tuck Shop</li>
                  <li class="breadcrumb-item active">Dashboard</li>
              </ol>
              </div>

            </div>

            <!-- Today's Performance -->
            <div class="row mb-4">
              <div class="col-md-3">
                <div class="card card-round card-primary">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Today's Sales</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title text-info">₦ <?php echo number_format($today_sales, 2); ?></p>
                      <small class="text-muted">
                        <?php
                        $change = $today_sales - $yesterday_sales;
                        $change_percent = $yesterday_sales > 0 ? (($change / $yesterday_sales) * 100) : 0;
                        $change_class = $change >= 0 ? 'text-success' : 'text-danger';
                        $change_icon = $change >= 0 ? '↑' : '↓';
                        echo "<span class='$change_class'>$change_icon " . abs($change_percent) . "%</span> vs yesterday";
                        ?>
                      </small>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card card-round card-success">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">This Week</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title text-success">₦ <?php echo number_format($week_sales, 2); ?></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card card-round card-primary">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">This Month</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title text-primary">₦ <?php echo number_format($month_sales, 2); ?></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card card-round card-warning">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Avg Transaction</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title text-warning">₦ <?php echo number_format($avg_transaction, 2); ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Main Statistics -->
            <div class="row mb-4">
              <div class="col-md-3">
                <div class="card card-round card-success curves-shadow">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Total Students Registered</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title"> <?php echo $total_tuck_students; ?> </p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card card-round card-primary skew-shadow">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Total Students Balance</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title">₦ <?php echo number_format($total_students_balance, 2); ?> </p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card card-round card-danger bubble-shadow">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Low Balance Students</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title"> <?php echo $total_low_balance; ?> </p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card card-round card-success curves-shadow">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Total Sales</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title">₦ <?php echo number_format($total_sales, 2); ?> </p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card card-round card-secondary skew-shadow">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Total Transactions</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title"> <?php echo $total_transactions; ?> </p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card card-round card-warning bubble-shadow">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Inventory Items</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title"><?php echo $inventory_qty; ?> Items</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card card-round card-primary curves-shadow">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Inventory Value</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title">₦ <?php echo number_format($inventory_sum, 2); ?></p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <div class="card card-round card-danger bubble-shadow">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Out of Stock</div>
                    </div>
                  </div>
                  <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                      <p class="card-title"><?php echo $out_of_stock; ?> Products</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Recent Transactions and Inventory Alerts -->
            <div class="row mb-4">
              <!-- Recent Transactions -->
              <div class="col-md-8">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Recent Transactions</div>
                      <div class="card-tools">
                        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Student</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Cashier</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php if (!empty($recent_transactions)): ?>
                            <?php foreach ($recent_transactions as $transaction): ?>
                              <tr>
                                <td><?php echo htmlspecialchars($transaction['studentname']); ?></td>
                                <td><?php echo htmlspecialchars($transaction['productname']); ?></td>
                                <td>₦<?php echo number_format($transaction['amount'], 2); ?></td>
                                <td><?php echo date('M d, H:i', strtotime($transaction['transactiondate'])); ?></td>
                                <td><?php echo htmlspecialchars($transaction['cashier']); ?></td>
                              </tr>
                            <?php endforeach; ?>
                          <?php else: ?>
                            <tr>
                              <td colspan="5" class="text-center text-muted">No recent transactions</td>
                            </tr>
                          <?php endif; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Inventory Alerts -->
              <div class="col-md-4">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Inventory Alerts</div>
                    </div>
                  </div>
                  <div class="card-body">
                    <?php if (!empty($low_stock_items)): ?>
                      <?php foreach ($low_stock_items as $item): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                          <strong><?php echo htmlspecialchars($item['productname']); ?></strong><br>
                          <small>Only <?php echo $item['qty']; ?> left (Reorder at: <?php echo $item['reorder_level']; ?>)</small>
                          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <div class="text-center text-muted">
                        <i class="fas fa-check-circle text-success fa-2x"></i><br>
                        All inventory levels are good!
                      </div>
                    <?php endif; ?>

                    <?php if ($out_of_stock > 0): ?>
                      <div class="alert alert-danger" role="alert">
                        <strong><?php echo $out_of_stock; ?> products are out of stock!</strong>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>

                <!-- Top Products -->
                <div class="card card-round mt-3">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Top Selling Products</div>
                    </div>
                  </div>
                  <div class="card-body">
                    <?php if (!empty($top_products)): ?>
                      <?php foreach ($top_products as $index => $product): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                          <div>
                            <strong><?php echo htmlspecialchars($product['productname']); ?></strong><br>
                            <small class="text-muted"><?php echo $product['total_qty']; ?> units sold</small>
                          </div>
                          <div class="text-end">
                            <span class="badge bg-success">₦<?php echo number_format($product['total_revenue'], 2); ?></span>
                          </div>
                        </div>
                        <?php if ($index < count($top_products) - 1): ?>
                          <hr>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <div class="text-center text-muted">
                        No sales data available
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>

            <!-- Charts Section -->
            <div class="row mb-4">
              <!-- Sales Trend Chart -->
              <div class="col-md-6">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Sales Trend (Last 14 Days)</div>
                    </div>
                  </div>
                  <div class="card-body">
                    <canvas id="salesTrendChart" width="400" height="200"></canvas>
                  </div>
                </div>
              </div>

              <!-- Monthly Comparison Chart -->
              <div class="col-md-6">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Monthly Sales Comparison</div>
                    </div>
                  </div>
                  <div class="card-body">
                    <canvas id="monthlySalesChart" width="400" height="200"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mb-4">
              <!-- Product Performance Chart -->
              <div class="col-md-6">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Top Products Performance</div>
                    </div>
                  </div>
                  <div class="card-body">
                    <canvas id="productPerformanceChart" width="400" height="200"></canvas>
                  </div>
                </div>
              </div>

              <!-- Transaction Volume Chart -->
              <div class="col-md-6">
                <div class="card card-round">
                  <div class="card-header">
                    <div class="card-head-row">
                      <div class="card-title">Transaction Volume (Last 14 Days)</div>
                    </div>
                  </div>
                  <div class="card-body">
                    <canvas id="transactionVolumeChart" width="400" height="200"></canvas>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <?php include('footer.php');?>
      </div>

      <!-- Custom template | don't include it in your project! -->
      <?php include('cust-color.php');?>
      <!-- End Custom template -->
    </div>
   <?php include('scripts.php');?>

   <script>
   // Chart data preparation
   <?php
   // Sales Trend Chart Data
   $salesTrendLabels = [];
   $salesTrendValues = [];
   foreach ($sales_trend_data as $data) {
       $salesTrendLabels[] = date('M d', strtotime($data['date']));
       $salesTrendValues[] = (float)$data['total_sales'];
   }

   // Monthly Sales Chart Data
   $monthlyLabels = [];
   $monthlyValues = [];
   foreach ($monthly_sales_data as $data) {
       $monthlyLabels[] = date('M Y', strtotime($data['month'] . '-01'));
       $monthlyValues[] = (float)$data['total_sales'];
   }

   // Product Performance Chart Data
   $productLabels = [];
   $productValues = [];
   foreach ($top_products_chart_data as $data) {
       $productLabels[] = $data['productname'];
       $productValues[] = (float)$data['total_revenue'];
   }

   // Transaction Volume Chart Data
   $transactionLabels = [];
   $transactionValues = [];
   foreach ($transaction_volume_data as $data) {
       $transactionLabels[] = date('M d', strtotime($data['date']));
       $transactionValues[] = (int)$data['transaction_count'];
   }
   ?>

   // Sales Trend Chart
   const salesTrendCtx = document.getElementById('salesTrendChart').getContext('2d');
   new Chart(salesTrendCtx, {
       type: 'line',
       data: {
           labels: <?php echo json_encode($salesTrendLabels); ?>,
           datasets: [{
               label: 'Daily Sales (₦)',
               data: <?php echo json_encode($salesTrendValues); ?>,
               borderColor: 'rgb(75, 192, 192)',
               backgroundColor: 'rgba(75, 192, 192, 0.2)',
               tension: 0.1,
               fill: true
           }]
       },
       options: {
           responsive: true,
           plugins: {
               legend: {
                   position: 'top',
               },
               title: {
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

   // Monthly Sales Comparison Chart
   const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
   new Chart(monthlySalesCtx, {
       type: 'bar',
       data: {
           labels: <?php echo json_encode($monthlyLabels); ?>,
           datasets: [{
               label: 'Monthly Sales (₦)',
               data: <?php echo json_encode($monthlyValues); ?>,
               backgroundColor: 'rgba(54, 162, 235, 0.8)',
               borderColor: 'rgba(54, 162, 235, 1)',
               borderWidth: 1
           }]
       },
       options: {
           responsive: true,
           plugins: {
               legend: {
                   position: 'top',
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

   // Product Performance Chart
   const productPerformanceCtx = document.getElementById('productPerformanceChart').getContext('2d');
   new Chart(productPerformanceCtx, {
       type: 'bar',
       data: {
           labels: <?php echo json_encode($productLabels); ?>,
           datasets: [{
               label: 'Revenue (₦)',
               data: <?php echo json_encode($productValues); ?>,
               backgroundColor: [
                   'rgba(255, 99, 132, 0.8)',
                   'rgba(54, 162, 235, 0.8)',
                   'rgba(255, 205, 86, 0.8)',
                   'rgba(75, 192, 192, 0.8)',
                   'rgba(153, 102, 255, 0.8)',
                   'rgba(255, 159, 64, 0.8)',
                   'rgba(199, 199, 199, 0.8)',
                   'rgba(83, 102, 255, 0.8)',
                   'rgba(255, 99, 255, 0.8)',
                   'rgba(99, 255, 132, 0.8)'
               ],
               borderWidth: 1
           }]
       },
       options: {
           indexAxis: 'y',
           responsive: true,
           plugins: {
               legend: {
                   position: 'top',
               }
           },
           scales: {
               x: {
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

   // Transaction Volume Chart
   const transactionVolumeCtx = document.getElementById('transactionVolumeChart').getContext('2d');
   new Chart(transactionVolumeCtx, {
       type: 'line',
       data: {
           labels: <?php echo json_encode($transactionLabels); ?>,
           datasets: [{
               label: 'Transaction Count',
               data: <?php echo json_encode($transactionValues); ?>,
               borderColor: 'rgb(255, 99, 132)',
               backgroundColor: 'rgba(255, 99, 132, 0.2)',
               tension: 0.1,
               fill: true
           }]
       },
       options: {
           responsive: true,
           plugins: {
               legend: {
                   position: 'top',
               }
           },
           scales: {
               y: {
                   beginAtZero: true,
                   ticks: {
                       stepSize: 1
                   }
               }
           }
       }
   });
   </script>

  </body>
</html>
