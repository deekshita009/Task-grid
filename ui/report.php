<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reports & Analytics | HOD Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Poppins', sans-serif;
        }

        .summary-card {
            border-radius: 12px;
            padding: 20px;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .bg-total {
            background: linear-gradient(to right, #6a11cb, #2575fc);
        }

        .bg-completed {
            background: linear-gradient(to right, #00b09b, #96c93d);
        }

        .bg-pending {
            background: linear-gradient(to right, #ff9966, #ff5e62);
        }

        .bg-delayed {
            background: linear-gradient(to right, #e52d27, #b31217);
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }

        table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        th {
            background: linear-gradient(to right, rgb(28, 159, 28), rgb(1, 119, 159));
            color: white;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h3 class="mb-4 text-center fw-bold">📊 Department Reports & Analytics</h3>

        <!-- Filters -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <select class="form-select" id="facultyFilter">
                    <option value="">All Faculties</option>
                    <option>Dr. Meenakshi</option>
                    <option>Mr. Karthik</option>
                    <option>Ms. Priya</option>
                    <option>Mr. Aravind</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="month" class="form-control" id="monthFilter">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary w-100" id="applyFilter">Apply Filter</button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 text-center">
            <div class="col-md-3">
                <div class="summary-card bg-total">
                    <h5>Total Tasks</h5>
                    <h2 id="totalTasks">125</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card bg-completed">
                    <h5>Completed Tasks</h5>
                    <h2 id="completedTasks">92</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card bg-pending">
                    <h5>Pending Tasks</h5>
                    <h2 id="pendingTasks">21</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card bg-delayed">
                    <h5>Delayed Tasks</h5>
                    <h2 id="delayedTasks">12</h2>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="text-center mb-3 fw-semibold">Task Completion Rate by Faculty</h5>
                    <canvas id="completionChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="text-center mb-3 fw-semibold">Delay Analysis</h5>
                    <canvas id="delayChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="chart-container">
                    <h5 class="text-center mb-3 fw-semibold">Monthly Task Completion Trend</h5>
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Insights Section -->
        <div class="alert alert-info mt-4">
            <strong>Insight:</strong> Top Performer — <b>Ms. Priya</b> with 98% completion rate. Department overall performance is <b>88%</b>.
        </div>

        <!-- Faculty Demerits Table -->
        <div class="mt-4">
            <h5 class="fw-bold mb-3">⚠ Faculty Demerit Summary</h5>
            <div class="table-responsive">
                <table id="demeritTable" class="table table-bordered table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th>Faculty Name</th>
                            <th>Pending Tasks</th>
                            <th>Delayed Submissions</th>
                            <th>Missed Deadlines</th>
                            <th>Total Demerit Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dr. R. Meenakshi</td>
                            <td>2</td>
                            <td>1</td>
                            <td>0</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <td>Mr. A. Karthik</td>
                            <td>4</td>
                            <td>2</td>
                            <td>1</td>
                            <td>7</td>
                        </tr>
                        <tr>
                            <td>Ms. S. Priya</td>
                            <td>1</td>
                            <td>0</td>
                            <td>0</td>
                            <td>1</td>
                        </tr>
                        <tr>
                            <td>Mr. P. Aravind</td>
                            <td>3</td>
                            <td>1</td>
                            <td>1</td>
                            <td>5</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="d-flex gap-2 mt-3 mb-5">
            <button class="btn btn-danger">Export PDF</button>
            <button class="btn btn-success">Export Excel</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable for demerits
            $('#demeritTable').DataTable({
                pageLength: 5,
                lengthChange: false
            });

            // Completion Chart
            new Chart(document.getElementById("completionChart"), {
                type: "bar",
                data: {
                    labels: ["Dr. Meenakshi", "A. Karthik", "S. Priya", "P. Aravind"],
                    datasets: [{
                        label: "Completion %",
                        data: [95, 80, 98, 85],
                        backgroundColor: ["#6a11cb", "#2575fc", "#00b09b", "#ff9966"]
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true, max: 100 } }
                }
            });

            // Delay Chart
            new Chart(document.getElementById("delayChart"), {
                type: "pie",
                data: {
                    labels: ["On Time", "Slight Delay", "Major Delay"],
                    datasets: [{
                        data: [70, 20, 10],
                        backgroundColor: ["#00b09b", "#ffcc00", "#e52d27"]
                    }]
                },
                options: { responsive: true }
            });

            // Trend Chart
            new Chart(document.getElementById("trendChart"), {
                type: "line",
                data: {
                    labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep"],
                    datasets: [{
                        label: "Completion Rate %",
                        data: [75, 80, 85, 88, 92, 95],
                        borderColor: "#4facfe",
                        backgroundColor: "rgba(79,172,254,0.2)",
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true, max: 100 } }
                }
            });

            // Apply Filter button (placeholder for future integration)
            $('#applyFilter').click(function () {
                alert('Filter applied! Integrate with backend to update charts and table.');
            });
        });
    </script>
</body>

</html>