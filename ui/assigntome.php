<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- DataTables CSS -->
        <link rel="stylesheet"
            href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

        <!-- DataTables JS -->
        <script
            src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            body{
                cursor:pointer;
            }
            table{
                text-align:center;
                width:100%;
                border-collapse:collapse;
                color:black;
                font-size:medium;
            }
            .headrow{
                color:white;
                font-weight:bold;
                background:linear-gradient(to right, rgb(28, 159, 28), rgb(1, 119, 159));
            }
            #task_table tbody tr:nth-child(even){
                background-color:rgb(224, 224, 253);
            }
            #task_table tbody tr:nth-child(odd){
                background-color:white;
            }
            td,th{
                text-align:center;
                border:1px solid white !important;
                padding: 5px;
            }
            .status_btn{
                display: inline-block;
                background-color:orange;
                padding:5px;
                color:white;
                text-decoration:none;
                border-radius:10px;
                margin-bottom:10px;
                transition: transform 0.1s ease, background-color 0.2s ease;
            }
            .status_btn_inactive{
                display: inline-block;
                background-color:orange;
                padding:5px;
                color:white;
                text-decoration:none;
                border-radius:10px;
                margin-bottom:10px;
            }
            .status_btn:hover{
                transform: scale(1.1);
                background-color: darkorange;
            }
            .cmp{
                background-color:rgb(255, 255, 0);
                color:white;
                padding:3px;
                margin:2px;
                border-radius:5px;
                margin-right:8px;
                transition: transform 0.5s;
            }
            .cmp:hover{
                transform: scale(1.2); 
            }
            .fwd{
                background-color:rgb(255, 255, 0);
                color:white;
                padding:3px;
                margin:2px;
                border-radius:5px;
                transition:transform 0.5s;
            }
            .fwd:hover{
                transform:scale(1.2);
            }
            .req{
                background-color:rgb(255, 255, 0);
                color:white;
                padding:3px;
                margin:2px;
                border-radius:5px;
                transition:transform 0.5s;
                margin-right:8px;
            }
            .req:hover{
                transform:scale(1.2);
            }
            .btns{
                margin-top:10px;
                display:none;
                justify-content:center;
            }
            .mod {
                display:none;
                height:auto;
                position: fixed;
                width: 400px;
                z-index: 9999 !important; /* higher so it stays above other elements */
                border-radius: 10px;
                border: 1px solid white;
                top: 30px;
                left: 50%;
                transform: translateX(-50%); /* centers horizontally */
                background-color: white; /* optional: makes content visible */
                color: black;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3); 
                 
            }

            .topdiv{
                border-top-right-radius: 10px;
                border-top-left-radius: 10px;
                background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
                color: white;
                padding: 15px 20px;
                border-bottom: none;
            }
            .close{
                float:right;
                color:black;
                right:2px;
                border-radius:50%;
                background-color:white;
                font-size:large;
            }
            .fwd_mod_btn{
                position:relative;
                border-color:white;
                color:black;
                background-color: #4E65FF;;
                padding:10px;
                left:75%;
                border-radius:10px;
            }

        </style>
    </head>
    <body>
        <div class="tab_task" id="tab_task">
            <table id="task_table">
                <thead>
                    <tr class="headrow">
                        <th>S.No</th>
                        <th>Assigned_by</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Assigned_date</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                     <tr>
                        <td>1</td>
                        <td>Faculty</td>
                        <td>Complete the hw</td>
                        <td>hhkjbhjnk</td>
                        <td>owefbe</td>
                        <td>nowehfwe</td>
                        <td>Pending</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Faculty</td>
                        <td>Complete the hw</td>
                        <td>hhkjbhjnk</td>
                        <td>owefbe</td>
                        <td>nowehfwe</td>
                        <td>Pending</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class='mod' id='fwdmod'>
            <div class="topdiv">Forward To
                <span class="close"
                    onclick="$('#fwdmod').hide()">&times;</span>
            </div>
            <div style="padding:20px;">
                <form id="fwdform">
                    <label for="dept">Choose the department</label>
                    <select name='dept' id="dept" required>
                        <option disabled selected value>--Select
                            department--</option>
                        <option value="CSE">Computer Science and
                            Engineering</option>
                        <option value="IT">Information and Technology</option>
                        <option value="AIDS">Artificial Intelligence and Data
                            Science</option>
                        <option value="AIML">Artificial Intellignece and Machine
                            Learning</option>
                        <option value="MECH">Mechanical Engineering</option>
                        <option value="CIVIL">Civil Engineering</option>
                        <option value="EEE">Electricals and Electronics
                            Engineering</option>
                        <option value="ECE">Electricals and Communication
                            Engineering</option>
                        <option value="Cybersecurity">Electricals and
                            Communication
                            Engineering</option>
                        <option value="VLSI">Very Large Scale Industry</option>
                        <option value="CSBS">Computer Science and Business
                            System</option>
                    </select>
                    <br><br>
                    <label for="fac_name">Choose faculty</label><br>
                    <select style="width:362px;" name="fac_name" id="fac_name" required>
                        <option disabled selected val>--Select
                            Faculty--</option>
                    </select>
                    <br><br>
                    <button class="fwd_mod_btn">Forward</button>
                </form>
            </div>
        </div>

        <div class='mod' id='reqmod'>
            <div class="topdiv">Request for Extension
                <span class="close"
                    onclick="$('#reqmod').hide()">&times;</span>
            </div>
            <div style="padding:20px;">
                <form id="reqform">
                    <label for="deadline">Select deadline</label><br>
                    <input type="date" style="width:350px;" name="deadline"
                        id="deadline">
                    <br><br>
                    <label for='reason'>Reason</label><br>
                    <input type="text" style="width:350px; height:70px;"
                        name="reason" id='reason' required>
                    <br><br>
                    <button class="fwd_mod_btn">Request</button>
                </form>
            </div>
        </div>

    </body>

    <script>

let table;
$(document).ready(function() {
    // Initialize DataTable ONCE
    table = $('#task_table').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        searching: true,
        ordering: false,
        info: true
    });

    loadtasks(table);
});

function loadtasks(table) {
    $.post('backend/api.php', { action: 'readtasks' }, function(res) {

        table.clear();
        
        res.forEach((r, i) => { // taken as list of lists,i is index and r is a row
            let html = '';
            if(r.status.toLowerCase() == 'completed'){
                html = `<a class='status_btn_inactive'  style='background-color:green;color:white;'>${r.status}</a>`;
            }
            else if(r.status == 'Overdue'){
                html = `<a class='status_btn_inactive' style='background-color:red;color:white;' onclick="showbtns(this)">${r.status}</a>`;
            }
            else if(r.status == 'In Progress'){
                html = `<a class='status_btn' style='background-color:orange;color:white;' onclick="showbtns(this)">${r.status}</a>
                <div class="btns">
                    <button onclick="editstatus(${r.task_assignment_id})" class="cmp" title="Mark as Completed">✅</button>
                    <button class="req" onclick="reqdl(${r.task_assignment_id})" title='deadline extension request'>🙋‍♂️</button>
                    <button class="fwd" onclick='fwd(${r.task_id})' title = 'Forward task'>➡</button>
                </div>`;
            }
            else{
                html = `<a class='status_btn' onclick="showbtns(this)">${r.status}</a>
                <div class="btns">
                    <button class="cmp" onclick="editstatus(${r.task_assignment_id})" title="Mark as Completed">✅</button>
                    <button class="req" onclick="reqdl(${r.task_assignment_id})" title='deadline extension request'>🙋‍♂️</button>
                    <button class="fwd" onclick='fwd(${r.task_id})' title='Forward task'>➡</button>
                </div>`;
            }
            table.row.add([
                i + 1,
                r.assigned_by,
                r.task_title,
                r.task_description,
                r.start_date,
                r.deadline,
                html
            ]);
        });
        table.draw();
    }, 'json');
}

function reqdl(id){
    $('#reqmod').show();
    $('#reqform').data('taskid', id);
}

function fwd(id){
    $('#fwdmod').show();
    $('#fwdform').data('taskid', id);
}

$('#dept').on('change', function(){
    if($('#dept').val()!=''){
    $.post('backend/api.php', {action:'filterfaculty', dept:$('#dept').val()}, function(res){
        const facultyname = $('#fac_name');
        facultyname.find('option:not(:first)').remove();
        res.forEach(r=>{
            facultyname.append(`<option value="${r.name}">${r.name}</option>`);
        });
    },"json");
    }
});

$('#fwdform').on('submit', function(e) {
    e.preventDefault();

    const taskid = $(this).data('taskid');
    const faculty = $('#fac_name').val();

    $.post('backend/api.php', { action: 'forward', taskId: taskid, name: faculty }, function(res) {
        console.log(res); 

        if (res.response === 'success') {
            Swal.fire({
                title: "Task Forwarded",
                text: "Task forwarded to " + faculty, 
                icon: "success"
            });
        } else {
            Swal.fire({
                title: "Error",
                text: res.message || "Unknown error occurred.",
                icon: "error"
            });
        }

        $('#fwdmod').hide();
    }, 'json')
    .fail(function(xhr, status, error) { 
        console.error("AJAX error:", status, error);
        Swal.fire({
            title: "Request Failed",
            text: "Could not reach the server or invalid response.",
            icon: "error"
        });
    });
});


function showbtns(el){
    document.querySelectorAll('.btns').forEach(m => m.style.display = 'none');
    const btns = el.nextElementSibling; 
    btns.style.display='flex';
}

$(document).on('click', function(e){
    if (!$(e.target).closest('.status_btn','.btns').length ) {
        $('.btns').hide();
    }
});

function editstatus(id){   
    Swal.fire({
        title:"Are you sure",
        text:"You want to change the status to completed?",
        icon:"question",
        showCancelButton:true,
        confirmButtonText: "Yes, Change it",
        cancelButtonText: "Cancel"
    }).then((result)=>{
        if(result.isConfirmed){
            $.post('backend/api.php', {action:'update_status', status:'Completed', task_assignment_id: id}, function(res){
                Swal.fire("Done!", "status changed to completed", "success");
                loadtasks(table);
            },'json');
        }
    });
    $("#btns").hide();
}
</script>
</html>