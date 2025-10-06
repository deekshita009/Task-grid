<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Task Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #e0f2fe 0%, #ddd6fe 100%);
            min-height: 100vh;
            padding: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2rem;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #6b7280;
        }

        .tab-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .tab-button {
            width: 100%;
            padding: 1.5rem;
            background: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            border-radius: 0.5rem;
            transition: background 0.3s;
        }

        .tab-button:hover {
            background: #f9fafb;
        }

        .tab-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .tab-left svg {
            color: #2563eb;
        }

        .tab-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
        }

        .tab-hint {
            font-size: 0.875rem;
            color: #6b7280;
        }

        /* Modal/Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }

        .overlay.active {
            display: flex;
        }

        /* Calendar */
        .calendar {
            background: white;
            border-radius: 0.5rem;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .calendar-header h3 {
            font-size: 1.25rem;
            color: #1f2937;
        }

        .close-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            font-size: 1.5rem;
        }

        .close-btn:hover {
            color: #1f2937;
        }

        .calendar-weekdays {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .weekday {
            text-align: center;
            font-size: 0.875rem;
            font-weight: 600;
            color: #6b7280;
            padding: 0.5rem;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .calendar-day {
            padding: 0.5rem;
            text-align: center;
            border: none;
            background: white;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .calendar-day:hover {
            background: #dbeafe;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .calendar-day.today {
            background: #2563eb;
            color: white;
        }

        .calendar-day.today:hover {
            background: #1d4ed8;
        }

        .calendar-day.has-tasks::after {
            content: '';
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: #f97316;
            border-radius: 50%;
        }

        /* Task Section */
        .task-section {
            display: none;
            padding: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .task-section.active {
            display: block;
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .task-header h2 {
            font-size: 1.25rem;
            color: #1f2937;
        }

        .add-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: #2563eb;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .add-btn:hover {
            background: #1d4ed8;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 1rem;
            text-align: left;
        }

        th {
            background: #f3f4f6;
            font-weight: 600;
            color: #374151;
        }

        tr:hover {
            background: #f9fafb;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .edit-btn, .delete-btn {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.3s;
        }

        .edit-btn {
            background: #16a34a;
            color: white;
        }

        .edit-btn:hover {
            background: #15803d;
        }

        .delete-btn {
            background: #dc2626;
            color: white;
        }

        .delete-btn:hover {
            background: #b91c1c;
        }

        .no-tasks {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }

        /* Modal Form */
        .modal {
            background: white;
            border-radius: 0.5rem;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-header h3 {
            font-size: 1.25rem;
            color: #1f2937;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-buttons {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        .submit-btn, .cancel-btn {
            flex: 1;
            padding: 0.5rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .submit-btn {
            background: #2563eb;
            color: white;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        .cancel-btn {
            background: #d1d5db;
            color: #374151;
        }

        .cancel-btn:hover {
            background: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Faculty Task Management</h1>
            <p>Organize and manage your personal tasks efficiently</p>
        </div>

        <div class="tab-container">
            <button class="tab-button" onclick="toggleCalendar()">
                <div class="tab-left">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <span class="tab-title">Personal To-Do</span>
                </div>
                <span class="tab-hint" id="tabHint">Click to open calendar</span>
            </button>

            <div class="task-section" id="taskSection">
                <div class="task-header">
                    <h2 id="taskDateTitle">Tasks for Selected Date</h2>
                    <button class="add-btn" onclick="openModal()">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Add Task
                    </button>
                </div>

                <table id="taskTable">
                    <thead>
                        <tr>
                            <th>Task Title</th>
                            <th>Description</th>
                            <th>Start Date</th>
                            <th>Due Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="taskTableBody">
                        <tr>
                            <td colspan="5" class="no-tasks">No tasks for this date. Click "Add Task" to create one.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Calendar Overlay -->
    <div class="overlay" id="calendarOverlay">
        <div class="calendar">
            <div class="calendar-header">
                <h3 id="calendarMonth"></h3>
                <button class="close-btn" onclick="toggleCalendar()">&times;</button>
            </div>
            <div class="calendar-weekdays">
                <div class="weekday">Sun</div>
                <div class="weekday">Mon</div>
                <div class="weekday">Tue</div>
                <div class="weekday">Wed</div>
                <div class="weekday">Thu</div>
                <div class="weekday">Fri</div>
                <div class="weekday">Sat</div>
            </div>
            <div class="calendar-days" id="calendarDays"></div>
        </div>
    </div>

    <!-- Modal Overlay -->
    <div class="overlay" id="modalOverlay">
        <div class="modal">
            <div class="modal-header">
                <h3 id="modalTitle">Add New Task</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <div class="form-group">
                <label>Task Title</label>
                <input type="text" id="taskTitle" placeholder="Enter task title">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea id="taskDescription" placeholder="Enter task description"></textarea>
            </div>
            <div class="form-group">
                <label>Start Date</label>
                <input type="date" id="taskStartDate">
            </div>
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" id="taskDueDate">
            </div>
            <div class="form-buttons">
                <button class="submit-btn" onclick="submitTask()">
                    <span id="submitBtnText">Add Task</span>
                </button>
                <button class="cancel-btn" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
    let tasks = {};
    let selectedDate = null;
    let editingTaskId = null;
    const today = new Date();

    // Save tasks in localStorage
    function saveTasks() {
        localStorage.setItem('tasks', JSON.stringify(tasks));
    }

    // Load tasks from localStorage
    function loadTasks() {
        const saved = localStorage.getItem('tasks');
        if (saved) {
            tasks = JSON.parse(saved);
        }
    }

    function initCalendar() {
        const month = today.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        document.getElementById('calendarMonth').textContent = month;
        renderCalendar();
    }

    function renderCalendar() {
        const year = today.getFullYear();
        const month = today.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const calendarDays = document.getElementById('calendarDays');
        calendarDays.innerHTML = '';

        for (let i = 0; i < firstDay; i++) {
            const emptyDiv = document.createElement('div');
            calendarDays.appendChild(emptyDiv);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const button = document.createElement('button');
            button.className = 'calendar-day';
            button.textContent = day;

            const date = new Date(year, month, day);
            const dateStr = formatDate(date);

            if (dateStr === formatDate(today)) {
                button.classList.add('today');
            }

            if (tasks[dateStr] && tasks[dateStr].length > 0) {
                button.classList.add('has-tasks');
            }

            button.onclick = () => selectDate(dateStr);
            calendarDays.appendChild(button);
        }
    }

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function formatDisplayDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
    }

    function toggleCalendar() {
        const overlay = document.getElementById('calendarOverlay');
        overlay.classList.toggle('active');
        document.getElementById('tabHint').textContent = overlay.classList.contains('active') ? 'Close Calendar' : 'Click to open calendar';
    }

    function selectDate(dateStr) {
        selectedDate = dateStr;
        document.getElementById('taskDateTitle').textContent = 'Tasks for ' + formatDisplayDate(dateStr);
        document.getElementById('taskSection').classList.add('active');
        toggleCalendar();
        renderTasks();
    }

    function renderTasks() {
        const tbody = document.getElementById('taskTableBody');
        tbody.innerHTML = '';

        if (!tasks[selectedDate] || tasks[selectedDate].length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="no-tasks">No tasks for this date. Click "Add Task" to create one.</td></tr>';
            return;
        }

        tasks[selectedDate].forEach(task => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${task.title}</td>
                <td>${task.description}</td>
                <td>${formatDisplayDate(task.startDate)}</td>
                <td>${formatDisplayDate(task.dueDate)}</td>
                <td>
                    <div class="action-buttons">
                        <button class="edit-btn" onclick="editTask('${task.id}')">Edit</button>
                        <button class="delete-btn" onclick="deleteTask('${task.id}')">Delete</button>
                    </div>
                </td>`;
            tbody.appendChild(tr);
        });
    }

    function openModal() {
        if (!selectedDate) {
            alert('Please select a date first!');
            return;
        }

        editingTaskId = null;
        document.getElementById('modalTitle').textContent = 'Add New Task';
        document.getElementById('submitBtnText').textContent = 'Add Task';
        document.getElementById('taskTitle').value = '';
        document.getElementById('taskDescription').value = '';
        document.getElementById('taskStartDate').value = selectedDate;
        document.getElementById('taskDueDate').value = selectedDate;
        document.getElementById('modalOverlay').classList.add('active');
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('active');
    }

    function editTask(taskId) {
        const task = tasks[selectedDate].find(t => t.id === taskId);
        if (!task) return;

        editingTaskId = taskId;
        document.getElementById('modalTitle').textContent = 'Edit Task';
        document.getElementById('submitBtnText').textContent = 'Update Task';
        document.getElementById('taskTitle').value = task.title;
        document.getElementById('taskDescription').value = task.description;
        document.getElementById('taskStartDate').value = task.startDate;
        document.getElementById('taskDueDate').value = task.dueDate;
        document.getElementById('modalOverlay').classList.add('active');
    }

    function deleteTask(taskId) {
        if (!confirm('Are you sure you want to delete this task?')) return;
        tasks[selectedDate] = tasks[selectedDate].filter(t => t.id !== taskId);
        saveTasks();  // ✅ Save changes
        renderTasks();
        renderCalendar();
    }

    function submitTask() {
        const title = document.getElementById('taskTitle').value.trim();
        const description = document.getElementById('taskDescription').value.trim();
        const startDate = document.getElementById('taskStartDate').value;
        const dueDate = document.getElementById('taskDueDate').value;

        if (!title || !description || !startDate || !dueDate) {
            alert('Please fill in all fields!');
            return;
        }

        if (editingTaskId) {
            const taskIndex = tasks[selectedDate].findIndex(t => t.id === editingTaskId);
            tasks[selectedDate][taskIndex] = { id: editingTaskId, title, description, startDate, dueDate };
        } else {
            const newTask = { id: Date.now().toString(), title, description, startDate, dueDate };
            if (!tasks[selectedDate]) tasks[selectedDate] = [];
            tasks[selectedDate].push(newTask);
        }

        saveTasks(); // ✅ Save after adding or editing
        closeModal();
        renderTasks();
        renderCalendar();
    }

    window.onload = function() {
        loadTasks();
        initCalendar();
    };
</script>

</body>
</html>