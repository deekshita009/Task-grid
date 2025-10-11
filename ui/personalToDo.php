<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Faculty Task Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #f4f6fa;
            font-family: "Poppins", sans-serif;
        }

        .day {
            width: 60px;
            height: 60px;
            margin: 5px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            cursor: pointer;
            background: #fff;
            border: 1px solid #ddd;
            transition: 0.2s;
        }

        .day:hover {
            background: #4e73df;
            color: #fff;
            transform: scale(1.05);
        }

        .day.today {
            background: #4e73df;
            color: #fff;
            font-weight: bold;
        }

        .day.selected {
            background: #6f42c1;
            color: white;
        }

        #calendarModal .modal-dialog {
            max-width: 900px;
            width: 90%;
            margin: 2% auto;
        }

        #calendarModal .modal-content {
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.3);
        }

        #calendarModal .modal-header {
            background: linear-gradient(90deg, #4e73df, #6f42c1);
            color: #fff;
        }

        #addTaskModal .modal-dialog,
        #editTaskModal .modal-dialog {
            max-width: 700px;
            width: 90%;
            margin: 2% auto;
        }

        #addTaskModal .modal-content,
        #editTaskModal .modal-content {
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.3);
        }

        #addTaskModal .modal-header,
        #editTaskModal .modal-header {
            background: linear-gradient(90deg, #4e73df, #6f42c1);
            color: #fff;
        }


        #calendar {
            width: 100%;
            max-width: 520px;
            margin: 0 auto;
        }

        .weekday-row {
            display: grid;
            grid-template-columns: repeat(7, 60px);
            gap: 10px;
            justify-content: center;
            margin-bottom: 8px;
            user-select: none;
        }

        .weekday-label {
            text-align: center;
            font-weight: 600;
            color: #555;
            font-size: 0.85rem;
        }

        .day-grid {
            display: grid;
            grid-template-columns: repeat(7, 60px);
            gap: 10px;
            justify-content: center;
        }

        #calendar .day {
            margin: 0;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-sizing: border-box;
        }

        #calendar .day.empty {
            visibility: hidden;
            background: transparent;
            border: none;
        }

        .day-number {
            font-size: 1rem;
        }

        #calendar .day.has-task::after {
            content: "";
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            position: absolute;
            bottom: 6px;
            right: 6px;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.05);
        }

        #calendar .day.today {
            outline: 2px solid rgba(78, 115, 223, 0.9);
        }

        #calendar .day.selected {
            box-shadow: inset 0 0 0 3px rgba(111, 66, 193, 0.12);
            background: #6f42c1 !important;
            color: #fff !important;
        }

        .modal-backdrop {
            z-index: 1050;
        }

        .modal {
            z-index: 1051;
        }
    </style>
</head>

<body>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="text-primary fw-bold"><i class="fas fa-calendar-check me-2"></i>Personal To-Do</h3>
            <button id="calendarTabBtn" class="btn btn-primary"><i class="fas fa-calendar-alt me-1"></i>Calendar</button>
        </div>

        <div id="tasksSection">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 id="selectedDateTitle"><i class="fas fa-list-check me-2"></i>Tasks for Today</h4>
                <button id="addTaskBtn" class="btn btn-success btn-sm"><i class="fas fa-plus me-1"></i>Add Task</button>
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="gradient-header">
                        <th>Title</th>
                        <th>Description</th>
                        <th>Start Date</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tasksTableBody"></tbody>
            </table>
        </div>
    </div>

    <!-- Calendar Modal -->
    <div class="modal fade" id="calendarModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-calendar-alt me-2"></i>Calendar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button id="prevMonth" class="btn btn-primary btn-sm"><i class="fas fa-chevron-left"></i></button>
                        <h5 id="calendarTitle" class="text-dark m-0 fw-bold"></h5>
                        <button id="nextMonth" class="btn btn-primary btn-sm"><i class="fas fa-chevron-right"></i></button>
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-tasks me-2"></i>Add Task</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm">
                        <div class="mb-3">
                            <label class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="taskTitle" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="taskDescription" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="taskStartDate" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deadline</label>
                            <input type="date" class="form-control" id="taskDeadline" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Save Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade" id="editTaskModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Task</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editTaskForm">
                        <input type="hidden" id="editDate">
                        <input type="hidden" id="editIndex">
                        <div class="mb-3">
                            <label class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="editTaskTitle" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="editTaskDescription" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="editTaskStartDate" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deadline</label>
                            <input type="date" class="form-control" id="editTaskDeadline" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="editTaskStatus">
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const calendarModalEl = document.getElementById("calendarModal");
            const addTaskModalEl = document.getElementById("addTaskModal");
            const editTaskModalEl = document.getElementById("editTaskModal");
            const tasksKey = "facultyTasks";

            function getLocalDateStr(date) {
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, "0");
                const d = String(date.getDate()).padStart(2, "0");
                return `${y}-${m}-${d}`;
            }

            let today = new Date();
            let selectedDate = getLocalDateStr(today);
            let currentMonth = today.getMonth();
            let currentYear = today.getFullYear();

            function updateSelectedDateTitle(dateStr) {
                $("#selectedDateTitle").text("Tasks for " + new Date(dateStr + 'T00:00:00').toDateString());
            }

            // Open Calendar
            $("#calendarTabBtn").click(function() {
                document.body.appendChild(calendarModalEl);
                bootstrap.Modal.getOrCreateInstance(calendarModalEl).show();
                renderCalendar(currentMonth, currentYear);
            });

            // Previous Month
            $("#prevMonth").click(function() {
                currentMonth--;
                if (currentMonth < 0) {
                    currentMonth = 11;
                    currentYear--;
                }
                renderCalendar(currentMonth, currentYear);
            });

            // Next Month
            $("#nextMonth").click(function() {
                currentMonth++;
                if (currentMonth > 11) {
                    currentMonth = 0;
                    currentYear++;
                }
                renderCalendar(currentMonth, currentYear);
            });

            // Open Add Task Modal
            $("#addTaskBtn").click(() => {
                $("#taskForm")[0].reset();
                $("#taskStartDate").val(selectedDate);
                $("#taskDeadline").val(selectedDate);
                document.body.appendChild(addTaskModalEl);
                bootstrap.Modal.getOrCreateInstance(addTaskModalEl).show();
            });

            [calendarModalEl, addTaskModalEl, editTaskModalEl].forEach(el => {
                el.addEventListener('hidden.bs.modal', () => $('.modal-backdrop').remove());
            });

            function renderCalendar(month, year) {
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const monthName = firstDay.toLocaleString("default", {
                    month: "long"
                });
                $("#calendarTitle").text(`${monthName} ${year}`);
                const tasks = JSON.parse(localStorage.getItem(tasksKey)) || {};
                const weekdays = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
                let html = `<div class="weekday-row">${weekdays.map(d=>`<div class="weekday-label">${d}</div>`).join('')}</div>`;
                html += `<div class="day-grid">`;

                const startDayOfWeek = firstDay.getDay();
                for (let i = 0; i < startDayOfWeek; i++) html += `<div class="day empty"></div>`;

                for (let d = 1; d <= lastDay.getDate(); d++) {
                    const date = new Date(year, month, d);
                    const dateStr = getLocalDateStr(date);
                    const isToday = dateStr === getLocalDateStr(today);
                    const isSelected = dateStr === selectedDate;
                    const hasTasks = tasks[dateStr] && tasks[dateStr].length > 0;
                    html += `<div class="day ${isToday?'today':''} ${isSelected?'selected':''} ${hasTasks?'has-task':''}" data-date="${dateStr}"><div class="day-number">${d}</div></div>`;
                }

                const totalCells = startDayOfWeek + lastDay.getDate();
                const trailing = (7 - (totalCells % 7)) % 7;
                for (let i = 0; i < trailing; i++) html += `<div class="day empty"></div>`;

                html += `</div>`;
                $("#calendar").html(html);

                $("#calendar .day").not(".empty").off("click").on("click", function() {
                    selectedDate = $(this).data("date");
                    updateSelectedDateTitle(selectedDate);
                    loadTasks(selectedDate);
                    bootstrap.Modal.getInstance(calendarModalEl).hide();
                    setTimeout(() => $('.modal-backdrop').remove(), 100);
                });
            }

            function loadTasks(date) {
                const tasks = JSON.parse(localStorage.getItem(tasksKey)) || {};
                const list = tasks[date] || [];
                const tbody = $("#tasksTableBody");
                tbody.empty();
                if (list.length === 0) {
                    tbody.append(`<tr><td colspan="6" class="text-center text-muted">No tasks for this date.</td></tr>`);
                } else {
                    list.forEach((t, i) => {
                        tbody.append(`<tr>
              <td>${t.title}</td>
              <td>${t.description}</td>
              <td>${t.startDate}</td>
              <td>${t.deadline}</td>
              <td class="${t.status==='Completed'?'text-success fw-bold':''}">${t.status}</td>
              <td>
                ${t.status==='Pending'?`<button class="btn btn-sm btn-success completeTask" data-date="${date}" data-index="${i}" title="Mark Completed"><i class="fas fa-check"></i></button>`:''}
                ${t.status==='Pending'?`<button class="btn btn-sm btn-warning editTask" data-date="${date}" data-index="${i}" title="Edit Task"><i class="fas fa-edit"></i></button>`:''}
                <button class="btn btn-sm btn-danger deleteTask" data-date="${date}" data-index="${i}"><i class="fas fa-trash"></i></button>
              </td>
            </tr>`);
                    });
                }
            }

            // Add Task
            $("#taskForm").submit(function(e) {
                e.preventDefault();
                const t = {
                    title: $("#taskTitle").val(),
                    description: $("#taskDescription").val(),
                    startDate: $("#taskStartDate").val(),
                    deadline: $("#taskDeadline").val(),
                    status: "Pending"
                };
                const tasks = JSON.parse(localStorage.getItem(tasksKey)) || {};
                if (!tasks[t.startDate]) tasks[t.startDate] = [];
                tasks[t.startDate].push(t);
                localStorage.setItem(tasksKey, JSON.stringify(tasks));
                bootstrap.Modal.getInstance(addTaskModalEl).hide();
                setTimeout(() => $('.modal-backdrop').remove(), 100);
                if (t.startDate === selectedDate) loadTasks(selectedDate);
                Swal.fire("Added!", "Task added successfully.", "success");
            });

            // Complete/Edit/Delete handled same as before...
            $(document).on("click", ".completeTask", function() {
                const date = $(this).data("date");
                const index = $(this).data("index");
                const tasks = JSON.parse(localStorage.getItem(tasksKey)) || {};
                Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, mark as completed!'
                    })
                    .then(result => {
                        if (result.isConfirmed) {
                            tasks[date][index].status = "Completed";
                            localStorage.setItem(tasksKey, JSON.stringify(tasks));
                            loadTasks(selectedDate);
                            Swal.fire("Completed!", "Task marked as completed.", "success");
                        }
                    });
            });

            $(document).on("click", ".editTask", function() {
                const date = $(this).data("date");
                const index = $(this).data("index");
                const tasks = JSON.parse(localStorage.getItem(tasksKey)) || {};
                const t = tasks[date][index];
                $("#editDate").val(date);
                $("#editIndex").val(index);
                $("#editTaskTitle").val(t.title);
                $("#editTaskDescription").val(t.description);
                $("#editTaskStartDate").val(t.startDate);
                $("#editTaskDeadline").val(t.deadline);
                $("#editTaskStatus").val(t.status);
                document.body.appendChild(editTaskModalEl);
                bootstrap.Modal.getOrCreateInstance(editTaskModalEl).show();
            });

            $("#editTaskForm").submit(function(e) {
                e.preventDefault();
                const date = $("#editDate").val();
                const index = $("#editIndex").val();
                const tasks = JSON.parse(localStorage.getItem(tasksKey)) || {};
                tasks[date][index] = {
                    title: $("#editTaskTitle").val(),
                    description: $("#editTaskDescription").val(),
                    startDate: $("#editTaskStartDate").val(),
                    deadline: $("#editTaskDeadline").val(),
                    status: $("#editTaskStatus").val()
                };
                localStorage.setItem(tasksKey, JSON.stringify(tasks));
                bootstrap.Modal.getInstance(editTaskModalEl).hide();
                loadTasks(selectedDate);
                Swal.fire("Updated!", "Task updated successfully.", "success");
            });

            $(document).on("click", ".deleteTask", function() {
                const date = $(this).data("date");
                const index = $(this).data("index");
                const tasks = JSON.parse(localStorage.getItem(tasksKey)) || {};
                Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    })
                    .then(result => {
                        if (result.isConfirmed) {
                            tasks[date].splice(index, 1);
                            localStorage.setItem(tasksKey, JSON.stringify(tasks));
                            loadTasks(date);
                            Swal.fire("Deleted!", "Task deleted successfully.", "success");
                        }
                    });
            });
            updateSelectedDateTitle(selectedDate);
            loadTasks(selectedDate);
        });
    </script>
</body>
</html>