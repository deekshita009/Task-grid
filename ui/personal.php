<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "college_erp";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOD') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$dept = $_SESSION['dept'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Task Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #fff;
            padding: 20px;
            min-height: 100vh;
        }

        .calendar-container {
            max-width: 1000px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 14px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .calendar-header {
            background: linear-gradient(135deg, #4e73df, #36b9cc);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }

        .calendar-header h3 {
            font-weight: bold;
            font-size: 1.2rem;
            margin: 0;
        }

        .calendar-header button {
            background-color: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            border-radius: 6px;
            width: 35px;
            height: 35px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .calendar-header button:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }

        .weekday-row,
        .day-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            padding: 10px 15px;
        }

        .weekday-row div {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
            font-size: 0.85rem;
        }

        .day {
            width: 100%;
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            position: relative;
            cursor: pointer;
            font-weight: 600;
            color: #333;
            background: #fff;
            transition: 0.2s;
            font-size: 0.9rem;
        }

        .day.empty {
            background: transparent;
            border: none;
            cursor: default;
        }

        .day:hover {
            transform: scale(1.05);
            border-color: #4e73df;
        }

        .day.selected {
            border: 3px solid #6f42c1;
        }

        .priority-high {
            background-color: red !important;
            color: white;
        }

        .priority-moderate {
            background-color: yellow !important;
        }

        .priority-low {
            background-color: green !important;
            color: white;
        }

        .task-dots {
            position: absolute;
            bottom: 6px;
            display: flex;
            justify-content: center;
            gap: 3px;
        }

        .task-dots span {
            width: 8px;
            height: 8px;
            background-color: black;
            border-radius: 50%;
        }

        .today {
            border: 3px solid #007bff;
            background-color: #cce5ff !important;
        }

        .tasks-container {
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            padding: 25px;
        }

        .tasks-section h4 {
            font-weight: bold;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table thead {
            background: linear-gradient(135deg, #4caf50, #2196f3);
            color: white;
        }

        .modal {
            z-index: 99999 !important;
        }

        .modal-backdrop {
            z-index: 99998 !important;
        }

        /* Disabled Add Task Button */
        #addTaskBtn:disabled {
            background-color: #ccc !important;
            border: none;
            cursor: not-allowed;
        }
    </style>
</head>

<body>

    <!-- Calendar -->
    <div class="calendar-container">
        <div class="calendar-header">
            <button id="prevMonth"><i class="fa fa-chevron-left"></i></button>
            <h3 id="calendarTitle"></h3>
            <button id="nextMonth"><i class="fa fa-chevron-right"></i></button>
        </div>
        <div class="weekday-row" id="weekdayRow"></div>
        <div class="day-grid" id="dayGrid"></div>
    </div>

    <!-- Tasks Table -->
    <div class="tasks-container">
        <div class="tasks-section">
            <h4 id="selectedDateTitle">
                <span><i class="fas fa-list-check me-2"></i>Tasks for Today</span>
                <button class="btn btn-primary btn-sm" id="addTaskBtn"><i class="fas fa-plus"></i> Add Task</button>
            </h4>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr class="gradient-header">
                            <th>Title</th>
                            <th>Description</th>
                            <th>Start</th>
                            <th>Deadline</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tasksTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg">
                <div class="modal-header text-white bg-primary">
                    <h5 class="modal-title">Task</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm">
                        <input type="hidden" id="taskId">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input id="taskTitle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="taskDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Start</label>
                                <input type="date" id="taskStartDate" class="form-control" required>
                            </div>
                            <div class="col">
                                <label class="form-label">Deadline</label>
                                <input type="date" id="taskDeadline" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select id="taskPriority" class="form-select">
                                <option>High</option>
                                <option>Moderate</option>
                                <option>Low</option>
                            </select>
                        </div>
                        <div class="mb-3" id="statusField" style="display:none;">
                            <label class="form-label">Status</label>
                            <select id="taskStatus" class="form-select">
                                <option>Pending</option>
                                <option>In Progress</option>
                                <option>Completed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dateTasksModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Tasks on Selected Date</h5>
                    <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Deadline</th>
                            </tr>
                        </thead>
                        <tbody id="dateTasksBody">
                            <tr>
                                <td colspan="5" class="text-center text-muted">No tasks found</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            $('#taskModal').appendTo('body');
            $('#dateTasksModal').appendTo('body');

            const modal = new bootstrap.Modal('#taskModal');
            const dateModal = new bootstrap.Modal('#dateTasksModal');

            let selectedDate = new Date().toISOString().split('T')[0];
            let currentMonth = new Date().getMonth(),
                currentYear = new Date().getFullYear();

            const weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
            $("#weekdayRow").html(weekdays.map(d => `<div>${d}</div>`).join(''));

            function getPriorityRank(p) {
                return p === "High" ? 3 : p === "Moderate" ? 2 : 1;
            }

            // ---------------- RENDER CALENDAR ----------------
            function renderCalendar() {
                const first = new Date(currentYear, currentMonth, 1);
                const last = new Date(currentYear, currentMonth + 1, 0);
                $("#calendarTitle").text(`${first.toLocaleString('default', { month: 'long' })} ${currentYear}`);
                let html = '';

                for (let i = 0; i < first.getDay(); i++) html += '<div class="day empty"></div>';

                $.getJSON('db/hod_personal_api.php?action=fetch', function(tasks) {
                    for (let d = 1; d <= last.getDate(); d++) {
                        const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
                        const dayTasks = tasks.filter(t => t.start_date === dateStr);
                        const isToday = dateStr === new Date().toISOString().split('T')[0];

                        let priorityClass = '';
                        if (dayTasks.length) {
                            // Filter only tasks that are NOT completed
                            const activeTasks = dayTasks.filter(t => t.status !== 'Completed');

                            if (activeTasks.length) {
                                const topPriority = activeTasks.sort((a, b) => getPriorityRank(b.priority) - getPriorityRank(a.priority))[0].priority;
                                priorityClass = topPriority === 'High' ? 'priority-high' :
                                    topPriority === 'Moderate' ? 'priority-moderate' :
                                    'priority-low';
                            } else {
                                // All tasks are completed -> no color
                                priorityClass = '';
                                let dots = '';
                            }
                        }


                        let dots = dayTasks.length ? '<div class="task-dots">' + '<span></span>'.repeat(dayTasks.length) + '</div>' : '';

                        html += `<div class="day ${priorityClass} ${isToday ? 'today' : ''} ${dateStr === selectedDate ? 'selected' : ''}" 
                        data-date="${dateStr}" data-tasks='${encodeURIComponent(JSON.stringify(dayTasks))}'>
                    <div>${d}</div>
                    ${dots}
                </div>`;
                    }

                    $("#dayGrid").html(html);

                    $(".day").not(".empty").click(function() {
                        selectedDate = $(this).data('date');
                        renderCalendar();
                        loadTasks();

                        // Disable Add Task if selected date < today
                        const today = new Date().toISOString().split('T')[0];
                        if (selectedDate < today) {
                            $("#addTaskBtn").prop("disabled", true);
                        } else {
                            $("#addTaskBtn").prop("disabled", false);
                        }

                        // Show popup modal for that date
                        const dayTasks = JSON.parse(decodeURIComponent($(this).data('tasks')));
                        const body = $("#dateTasksBody").empty();

                        if (dayTasks.length) {
                            dayTasks.forEach(t => {
                                body.append(`<tr>
                            <td>${t.title}</td>
                            <td>${t.description}</td>
                            <td>${t.priority}</td>
                            <td>${t.status}</td>
                            <td>${t.deadline}</td>
                        </tr>`);
                            });
                        } else {
                            body.append(`<tr><td colspan="5" class="text-center text-muted">No tasks on this date</td></tr>`);
                        }

                        $(".modal-title", "#dateTasksModal").text(`Tasks on ${selectedDate}`);
                        dateModal.show();
                    });
                });
            }

            // ---------------- LOAD TASKS TABLE ----------------
            function loadTasks() {
                $("#selectedDateTitle span").text(`Tasks for ${selectedDate}`);

                $.getJSON('db/hod_personal_api.php?action=fetch', function(tasks) {
                    const tb = $("#tasksTableBody").empty();
                    const filtered = tasks.filter(t => t.start_date === selectedDate);

                    // Disable Add Task if past date
                    const today = new Date().toISOString().split('T')[0];
                    if (selectedDate < today) {
                        $("#addTaskBtn").prop("disabled", true);
                    } else {
                        $("#addTaskBtn").prop("disabled", false);
                    }

                    if (!filtered.length) {
                        return tb.append(`<tr><td colspan="7" class="text-center text-muted">No tasks</td></tr>`);
                    }

                    filtered.forEach(t => {
                        const rowClass = t.status === "Completed" ? "table-success" : "";
                        let actions = `<button class='btn btn-danger btn-sm delete' data-id='${t.id}'>Delete</button>`;

                        if (t.status !== "Completed") {
                            actions = `
                        <button class='btn btn-success btn-sm complete' data-id='${t.id}'>Complete</button>
                        <button class='btn btn-warning btn-sm edit' data-task='${encodeURIComponent(JSON.stringify(t))}'>Edit</button>
                        ${actions}`;
                        }

                        tb.append(`<tr class="${rowClass}">
                    <td>${t.title}</td>
                    <td>${t.description}</td>
                    <td>${t.start_date}</td>
                    <td>${t.deadline}</td>
                    <td>${t.priority}</td>
                    <td>${t.status}</td>
                    <td>${actions}</td>
                </tr>`);
                    });
                });
            }

            // ---------------- MONTH NAVIGATION ----------------
            $("#prevMonth").click(() => {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar();
            });

            $("#nextMonth").click(() => {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar();
            });

            // ---------------- ADD TASK ----------------
            $("#addTaskBtn").click(() => {
                $("#taskForm")[0].reset();
                $("#taskId").val('');
                $("#statusField").hide();
                $("#taskStartDate").val(selectedDate);
                $("#taskDeadline").val(selectedDate);
                $(".modal-title").text("Add Task");
                modal.show();
            });

            // ---------------- EDIT TASK ----------------
            $(document).on("click", ".edit", function() {
                const t = JSON.parse(decodeURIComponent($(this).data('task')));
                $("#taskId").val(t.id);
                $("#taskTitle").val(t.title);
                $("#taskDescription").val(t.description);
                $("#taskStartDate").val(t.start_date);
                $("#taskDeadline").val(t.deadline);
                $("#taskPriority").val(t.priority);
                $("#taskStatus").val(t.status);
                $("#statusField").show();
                $(".modal-title").text("Edit Task");
                modal.show();
            });

            // ---------------- SAVE TASK (Add / Update) ----------------
            $("#taskForm").submit(function(e) {
                e.preventDefault();
                const data = {
                    id: $("#taskId").val(),
                    title: $("#taskTitle").val(),
                    description: $("#taskDescription").val(),
                    start_date: $("#taskStartDate").val(),
                    deadline: $("#taskDeadline").val(),
                    priority: $("#taskPriority").val(),
                    status: $("#taskStatus").val()
                };
                const action = data.id ? 'update' : 'insert';

                $.ajax({
                    url: 'db/hod_personal_api.php?action=' + action,
                    method: data.id ? 'POST' : 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    success: function() {
                        modal.hide();
                        renderCalendar();
                        loadTasks();
                        Swal.fire('Success', 'Task saved successfully!', 'success');
                    }
                });
            });
            // ---------------- COMPLETE TASK ----------------
            $(document).on("click", ".complete", function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Mark Complete?',
                    text: 'Do you want to mark this task as completed?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then(res => {
                    if (res.isConfirmed) {
                        $.post('db/hod_personal_api.php?action=complete', {
                            id: id
                        }, function() {
                            // Reload UI after completion
                            renderCalendar();
                            loadTasks();

                            Swal.fire('Completed', 'Task marked as complete!', 'success');

                            // After reload, remove color/dots from that date if all tasks are completed
                            $.getJSON('db/hod_personal_api.php?action=fetch', function(tasks) {
                                // Find date of completed task
                                const completedTask = tasks.find(t => t.id == id);
                                if (completedTask) {
                                    const dateStr = completedTask.start_date;
                                    const dayTasks = tasks.filter(t => t.start_date === dateStr);
                                    const pendingTasks = dayTasks.filter(t => t.status !== "Completed");

                                    const cell = $(`.day[data-date="${dateStr}"]`);
                                    if (!pendingTasks.length) {
                                        cell.removeClass("priority-high priority-moderate priority-low");
                                        cell.find(".task-dots").remove();
                                    }
                                }
                            });
                        });
                    }
                });
            });

            // ---------------- DELETE TASK ----------------
            $(document).on("click", ".delete", function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Delete Task?',
                    text: 'This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete'
                }).then(res => {
                    if (res.isConfirmed) {
                        $.post('db/hod_personal_api.php?action=delete', {
                            id: id
                        }, function() {
                            renderCalendar();
                            loadTasks();

                            // After deletion, clean up day if no tasks remain
                            $.getJSON('db/hod_personal_api.php?action=fetch', function(tasks) {
                                const deletedTask = tasks.find(t => t.id == id);
                                if (deletedTask) {
                                    const dateStr = deletedTask.start_date;
                                    const remaining = tasks.filter(t => t.start_date === dateStr);
                                    const cell = $(`.day[data-date="${dateStr}"]`);
                                    if (!remaining.length) {
                                        cell.removeClass("priority-high priority-moderate priority-low");
                                        cell.find(".task-dots").remove();
                                    }
                                }
                            });

                            Swal.fire('Deleted', 'Task removed.', 'success');
                        });
                    }
                });
            });

            // ---------------- INITIAL LOAD ----------------
            renderCalendar();
            loadTasks();

        });
    </script>

</body>

</html>