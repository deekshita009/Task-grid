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

        .bg-in-progress {
            background: linear-gradient(to right, #9366fc, #d7adff);
        }

        .bg-overdue {
            background: linear-gradient(to right, #f7706b, #eb151c);
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-top: 20px;
        }

        #demeritTable {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

       .demerit  {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
           color: inherit;
           text-align:centre;
           font-size:0.9em;
           font-weight:600;
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
                    <h2 id="totalTasks">0</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card bg-completed">
                    <h5>Completed Tasks</h5>
                    <h2 id="completedTasks">0</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card bg-pending">
                    <h5>Pending Tasks</h5>
                    <h2 id="pendingTasks">0</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card bg-in-progress">
                    <h5>In-Progress</h5>
                    <h2 id="in-progressTasks">0</h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-card bg-overdue">
                    <h5>Overdue</h5>
                    <h2 id="overdueTasks">0</h2>
                </div>
            </div>
        </div>

        <!-- Faculty Demerits Table -->
        <div class="mt-4">
            <h5 class="fw-bold mb-3">⚠ Faculty Demerit Summary</h5>
            <div class="table-responsive">
                <table id="demeritTable" class="table table-bordered table-striped table-hover">
                    <thead  >
                        <tr class="demerit">
                            <th>Faculty Name</th>
                            <th>Pending Tasks</th>
                            <th>Delayed Submissions</th>
                            <th>Missed Deadlines</th>
                            <th>Total Demerit Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Charts -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="chart-container">
                    <h5 class="text-center mb-3 fw-semibold">Monthly Task Completion Trend</h5>
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="chart-container">
                    <h5 class="text-center mb-3 fw-semibold">Task Completion Rate by Faculty</h5>
                    <canvas id="completionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Insights -->
        <div class="alert alert-info mt-4">
            <strong>Insight:</strong> Top Performer — <b id="topPerformer">-</b> with <b id="topCompletion">0%</b> completion rate.
            Department overall performance is <b id="overallPerformance">0%</b>.
        </div>

        <!-- Export Buttons -->
        <div class="d-flex gap-2 mt-3 mb-5">
            <button class="btn btn-danger">Export PDF</button>
            <button class="btn btn-success">Export Excel</button>
        </div>
    </div>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            let completionChartObj, delayChartObj, trendChartObj;
            const demeritTable = $('#demeritTable').DataTable({
                pageLength: 5,
                lengthChange: false
            });
        
            function loadReportData(faculty = '', month = '') {
                $.ajax({
                    url: 'db/reportbackend.php',
                    type: 'GET',
                    data: { faculty: faculty, month: month },
                    dataType: 'json',
                    success: function (data) {
                        console.log("Backend Data:", data);
        
                        if (!data || !data.summary) {
                            alert("No data received from backend.");
                            return;
                        }
        
                        const s = data.summary || {};
        
                        // Summary Cards
                        $('#totalTasks').text(s.total_tasks ?? 0);
                        $('#completedTasks').text(s.completed ?? 0);
                        $('#pendingTasks').text(s.pending ?? 0);
                        $('#in-progressTasks').text(s.in_progress ?? 0);
                        $('#overdueTasks').text(s.overdue ?? 0);
        
                        // Overall Performance
                        const completed = Number(s.completed ?? 0);
                        const total = Number(s.total_tasks ?? 0);
                        const overallPerf = total > 0 ? ((completed / total) * 100).toFixed(2) : '0.00';
                        $('#overallPerformance').text(overallPerf + '%');
        
                        // Faculty Completion Chart
                        const facultyArr = Array.isArray(data.faculty) ? data.faculty : [];
                        const facultyNames = facultyArr.map(f => f.faculty_name || 'Unknown');
                        const completionRates = facultyArr.map(f => Number(f.completion_percentage ?? 0));
        
                        let topPerformerName = '-';
                        let topRate = 0;
                        if (completionRates.length > 0) {
                            const maxVal = Math.max(...completionRates);
                            const idx = completionRates.indexOf(maxVal);
                            topPerformerName = facultyNames[idx] || '-';
                            topRate = isFinite(maxVal) ? maxVal : 0;
                        }
                        $('#topPerformer').text(topPerformerName);
                        $('#topCompletion').text(topRate + '%');
        
                        if (completionChartObj) completionChartObj.destroy();
                        const ctxComp = document.getElementById('completionChart');
                        completionChartObj = new Chart(ctxComp, {
                            type: 'bar',
                            data: {
                                labels: facultyNames,
                                datasets: [{
                                    label: 'Completion %',
                                    data: completionRates,
                                    backgroundColor: facultyNames.map((_, i) => ['#6a11cb', '#2575fc', '#00b09b', '#ff9966'][i % 4])
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: { y: { beginAtZero: true, max: 100 } }
                            }
                        });
        
                
        
                        // Trend Chart (Past 6 Months)
                        const trendArr = Array.isArray(data.trend) && data.trend.length ? data.trend : [];
                        const Labels = trendArr.map(item => item.month);
                        const Values = trendArr.map(item => parseInt(item.completed_count));
        
                        if (trendChartObj) trendChartObj.destroy();
                        const ctxTrend = document.getElementById('trendChart');
                        trendChartObj = new Chart(ctxTrend, {
                            type: 'line',
                            data: {
                                labels: Labels,
                                datasets: [{
                                    label: 'Completed Tasks',
                                    data: Values,
                                    borderColor: '#2575fc',
                                    backgroundColor: 'rgba(37,117,252,0.3)',
                                    fill: true,
                                    tension: 0.3,
                                    pointRadius: 5
                                }]
                            },
                            options: {
                                responsive: true,
                                scales: {
                                    y: { beginAtZero: true, title: { display: true, text: 'Completed Tasks' } },
                                    x: { title: { display: true, text: 'Month' } }
                                }
                            }
                        });
        
                        // Demerits Table
                        demeritTable.clear();
                        const demerits = Array.isArray(data.demerits) ? data.demerits : [];
                        if (demerits.length > 0) {
                            demerits.forEach(d => {
                                demeritTable.row.add([
                                    d.faculty_name ?? '-',
                                    d.pending_tasks ?? 0,
                                    d.delayed_submissions ?? 0,
                                    d.missed_deadlines ?? 0,
                                    d.total_demerit_points ?? 0
                                ]);
                            });
                        } else {
                            demeritTable.row.add(['No data', '-', '-', '-', '-']);
                        }
                        demeritTable.draw();
                    },
                    error: function (xhr, status, error) {
                        console.error('❌ AJAX Error:', error);
                        console.log(xhr.responseText);
                        alert("Failed to fetch data from backend.");
                    }
                });
            }
        
            // Initial Load
            loadReportData();
        
            // Filter button
            $('#applyFilter').click(function () {
                const faculty = $('#facultyFilter').val();
                const month = $('#monthFilter').val();
                loadReportData(faculty, month);
            });
        });
        </script>
        
</body>
</html>