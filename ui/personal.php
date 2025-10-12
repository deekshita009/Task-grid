<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "taskgrid";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
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
    background-color: #fff; /* ✅ plain white background */
    padding: 20px;
    min-height: 100vh;
}

/* Calendar container */
.calendar-container {
    max-width: 600px;
    margin: 20px auto;
    background-color: linear-gradient(to right, #cc8bbdff, #c732bdff);
    border-radius: 14px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.1);
    overflow: hidden;
}

/* 🌟 Colored header */
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
    background-color: rgba(255,255,255,0.2);
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
    background-color: rgba(255,255,255,0.4);
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
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid #ddd;
    background: #fff;
    position: relative;
    cursor: pointer;
    font-weight: 600;
    color: #333;
    transition: 0.2s;
    font-size: 0.8rem;
}

.day:hover {
    background: #4e73df;
    color: #fff;
    transform: scale(1.05);
}

.day.empty {
    background: transparent;
    border: none;
    cursor: default;
}

.day.selected {
    background: #6f42c1;
    color: white;
}

.priority-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    position: absolute;
    bottom: 6px;
    right: 6px;
}

.dot-high { background: #e74c3c; }
.dot-moderate { background: #f39c12; }
.dot-low { background: #2ecc71; }

/* Tasks container */
.tasks-container {
    max-width: 1000px;
    margin: 30px auto;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.1);
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

.modal { z-index: 99999 !important; }
.modal-backdrop { z-index: 99998 !important; }
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

<!-- Tasks -->
<div class="tasks-container">
    <div class="tasks-section">
        <h4 id="selectedDateTitle">
            <span><i class="fas fa-list-check me-2"></i>Tasks for Today</span>
            <button class="btn btn-success btn-sm" id="addTaskBtn"><i class="fas fa-plus"></i> Add Task</button>
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

<!-- Add/Edit Task Modal -->
<div class="modal fade" id="taskModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-primary text-white">
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

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function(){
    $('#taskModal').appendTo('body');
    const modal = new bootstrap.Modal('#taskModal');

    let selectedDate = new Date().toISOString().split('T')[0];
    let currentMonth = new Date().getMonth(), currentYear = new Date().getFullYear();

    const weekdays = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
    $("#weekdayRow").html(weekdays.map(d=>`<div>${d}</div>`).join(''));

    function getPriorityRank(p){ return p==="High"?3:p==="Moderate"?2:1; }

    function renderCalendar(){
        const first = new Date(currentYear,currentMonth,1);
        const last = new Date(currentYear,currentMonth+1,0);
        $("#calendarTitle").text(`${first.toLocaleString('default',{month:'long'})} ${currentYear}`);
        let html = '';
        for(let i=0;i<first.getDay();i++) html += '<div class="day empty"></div>';
        
        $.getJSON('backend/personal_api.php?action=fetch', function(tasks){
            for(let d=1; d<=last.getDate(); d++){
                const dateStr = `${currentYear}-${String(currentMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                const dayTasks = tasks.filter(t=>t.start_date===dateStr && t.status!=="Completed");
                let dotClass='';
                if(dayTasks.length){
                    const topPriority = dayTasks.sort((a,b)=>getPriorityRank(b.priority)-getPriorityRank(a.priority))[0].priority;
                    dotClass = topPriority==='High'?'dot-high':topPriority==='Moderate'?'dot-moderate':'dot-low';
                }
                html+=`<div class="day ${dateStr===selectedDate?'selected':''}" data-date="${dateStr}">
                        ${d}${dotClass?`<div class="priority-dot ${dotClass}"></div>`:''}
                      </div>`;
            }
            $("#dayGrid").html(html);
            $(".day").not(".empty").click(function(){
                selectedDate=$(this).data('date');
                renderCalendar(); loadTasks();
            });
        });
    }

    function loadTasks(){
        $("#selectedDateTitle span").text(`Tasks for ${selectedDate}`);
        $.getJSON('backend/personal_api.php?action=fetch', function(tasks){
            const tb=$("#tasksTableBody").empty();
            const filtered = tasks.filter(t=>t.start_date===selectedDate);
            if(!filtered.length) return tb.append(`<tr><td colspan=7 class="text-center text-muted">No tasks</td></tr>`);
            filtered.forEach(t=>{
                const completeBtn = t.status!=='Completed'?`<button class='btn btn-success btn-sm complete' data-id='${t.id}'>Complete</button>`:'';
                const deleteBtn = `<button class='btn btn-danger btn-sm delete' data-id='${t.id}'>Delete</button>`;
                tb.append(`<tr>
                    <td>${t.title}</td><td>${t.description}</td><td>${t.start_date}</td><td>${t.deadline}</td>
                    <td>${t.priority}</td><td>${t.status}</td>
                    <td>${completeBtn}
                        <button class='btn btn-warning btn-sm edit' data-task='${JSON.stringify(t)}'>Edit</button>
                        ${deleteBtn}
                    </td>
                </tr>`);
            });
        });
    }

    $("#prevMonth").click(()=>{currentMonth--; if(currentMonth<0){currentMonth=11; currentYear--;} renderCalendar();});
    $("#nextMonth").click(()=>{currentMonth++; if(currentMonth>11){currentMonth=0; currentYear++;} renderCalendar();});

    $("#addTaskBtn").click(()=>{
        $("#taskForm")[0].reset();
        $("#taskId").val('');
        $("#statusField").hide();
        $("#taskStartDate").val(selectedDate);
        $("#taskDeadline").val(selectedDate);
        $(".modal-title").text("Add Task");
        modal.show();
    });

    $(document).on("click",".edit",function(){
        const t=JSON.parse($(this).data('task'));
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

    $("#taskForm").submit(function(e){
        e.preventDefault();
        const data={
            id: $("#taskId").val(),
            title: $("#taskTitle").val(),
            description: $("#taskDescription").val(),
            startDate: $("#taskStartDate").val(),
            deadline: $("#taskDeadline").val(),
            priority: $("#taskPriority").val(),
            status: $("#taskStatus").val()
        };
        const action = data.id?'update':'add';
        $.ajax({
            url:'backend/personal_api.php?action='+action,
            type:'POST',
            data:data,
            success:function(res){
                modal.hide();
                renderCalendar(); loadTasks();
                Swal.fire('Success', res, 'success');
            }
        });
    });

    $(document).on("click",".complete",function(){
        const id=$(this).data('id');
        $.ajax({
            url:'backend/personal_api.php?action=update',
            type:'POST',
            data:{id:id,status:'Completed'},
            success:function(){
                renderCalendar();
                loadTasks();
                Swal.fire('Done','Task Completed','success');
            }
        });
    });

    $(document).on("click",".delete",function(){
        const id=$(this).data('id');
        Swal.fire({title:'Delete?',text:'This cannot be undone',icon:'warning',showCancelButton:true})
        .then(res=>{ if(res.isConfirmed){
            $.ajax({url:'backend/personal_api.php?action=delete',type:'POST',data:{id:id},success:function(){
                renderCalendar(); loadTasks();
                Swal.fire('Deleted','','success');
            }}); }});
    });

    renderCalendar(); loadTasks();
});
</script>

</body>
</html>
