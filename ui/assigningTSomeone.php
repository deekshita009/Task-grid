<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Assign to Someone</title>

        <!-- ✅ Bootstrap & jQuery -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- ✅ SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- ✅ DataTables -->
        <link rel="stylesheet"
            href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <script
            src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <!-- ✅ Font Awesome -->
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
        /* ---------- AssignTo Table Style ---------- */
        .assignto {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
            color: inherit;
            text-align: center;
            font-size: 0.9em;
            font-weight: 600;
        }

        /* ---------- Modal Styling ---------- */
        /* Fix z-index & modal stacking */
        .modal-backdrop {
            z-index: 2000 !important;
            background-color: rgba(0, 0, 0, 0.55) !important;
            backdrop-filter: blur(3px);
        }

        .modal {
            z-index: 2100 !important;
        }

        /* ---------- Modal Header ---------- */
        .modal-header {
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: #fff;
            border-bottom: none;
            padding: 20px 24px;
        }

        /* ---------- Modal Title ---------- */
        .modal-title {
            font-size: 1.35rem;
            font-weight: 500;
        }

        /* ---------- Modal Body ---------- */
        .modal-body {
            padding: 24px;
            background: #fff;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        /* ---------- Modal Footer ---------- */
        .modal-footer {
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
            border-top: none;
            background: #f7f7f9;
        }

        /* ---------- Buttons inside Modals ---------- */
        .modal-footer .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 8px 18px;
        }

        /* ---------- SweetAlert (for safety) ---------- */
        .swal2-container {
            z-index: 2000 !important;
        }

        /* ---------- Optional: Smooth open animation ---------- */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translateY(-30px);
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }

        .add {
            display: flex;
            flex-direction: row;
            justify-content: end;
            align-items: center;
            margin-right: 70px;
        }

        .buttonclass {
            display: flex;
        }

        /* Button styling to match your first sample */
        .btn-primary {
            border-radius: 8px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: white;
            border: none;
        }
    </style>
    </head>

    <body>
        <div id="assign-by-me" class="container mt-4">

            <div class="assignbutton"
                style="display:flex;flex-direction:column;justify-content:flex-end;align-items:end;text-align:end;">
                <button id="taskAddBtn" class="btn btn-primary mb-2"
                    style="display:flex;flex-direction:column;align-items:end;text-align:end;">
                    Assign Task
                </button>
            </div>

            <!-- Assigned Tasks Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover"
                    id="parentTable">
                    <thead>
                        <tr class="assignto">
                            <th>S.No</th>
                            <th>Assign_To</th>
                            <th>Task_Title</th>
                            <th>Task_Description</th>
                            <th>Assigned_Date</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="assignTo">
                        <!-- Dynamic rows will appear here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ✅ Task Assignment Modal -->
        <div class="modal fade" id="assigntomodal" tabindex="-1"
            aria-labelledby="assignModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="assignModalLabel">
                            <i class="fas fa-tasks me-2"></i>Assign Task
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="taskForm">
                            <div class="mb-3">
                                <label for="assignToDepartment"
                                    class="form-label">
                                    <i class="fas fa-user me-1"></i>Select
                                    Department
                                </label>
                                <select class="form-select"
                                    id="assignToDepartment" required>
                                    <option value>-- Select department
                                        --</option>
                                    <option value="CSE">CSE</option>
                                    <option value="EEE">EEE</option>
                                    <option value="ECE">ECE</option>
                                    <option value="IT">IT</option>
                                    <option value="MECH">MECH</option>
                                    <option value="CIVIL">CIVIL</option>
                                    <option value="AI">AI</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="assignToFaculty" class="form-label">
                                    <i class="fas fa-user me-1"></i>Assign To
                                    FACULTY
                                </label>
                                <select class="form-select" id="assignToFaculty"
                                    multiple required>
                                    <option value>-- Select Faculty --</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="some" class="form-label">
                                    <i class="fas fa-heading me-1"></i>Task
                                    Title
                                </label>
                                <input type="text" class="form-control"
                                    id="some" placeholder="Enter task title"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="taskDesc" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Task
                                    Description
                                </label>
                                <textarea class="form-control" id="taskDesc"
                                    rows="3"
                                    placeholder="Enter task description"
                                    required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="startdate" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Start
                                    Date
                                </label>
                                <input type="date" class="form-control"
                                    id="startdate" required>
                            </div>

                            <div class="mb-3">
                                <label for="deadline" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Deadline
                                </label>
                                <input type="date" class="form-control"
                                    id="taskdl" required>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check me-2"></i>Assign Task
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- ✅ Edit Task Modal -->
        <div class="modal fade" id="editmodal" tabindex="-1"
            aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">
                            <i class="fas fa-tasks me-2"></i>Edit Task
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="EditForm">
                            <input type="hidden" id="editid" name="editid">
                            <div class="mb-3">
                                <label for="editDepartment" class="form-label">
                                    <i class="fas fa-user me-1"></i>Select
                                    Department
                                </label>
                                <select class="form-select" id="editDepartment"
                                    required>
                                    <option value>-- Select department
                                        --</option>
                                    <option value="CSE">CSE</option>
                                    <option value="EEE">EEE</option>
                                    <option value="ECE">ECE</option>
                                    <option value="IT">IT</option>
                                    <option value="MECH">MECH</option>
                                    <option value="CIVIL">CIVIL</option>
                                    <option value="AI">AI</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="editFaculty" class="form-label">
                                    <i class="fas fa-user me-1"></i>Assign To
                                    FACULTY
                                </label>
                                <select class="form-select" id="editFaculty"
                                    multiple required>
                                    <option value>-- Select Faculty --</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="editsome" class="form-label">
                                    <i class="fas fa-heading me-1"></i>Task
                                    Title
                                </label>
                                <input type="text" class="form-control"
                                    id="editsome" placeholder="Enter task title"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="edittaskDesc" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Task
                                    Description
                                </label>
                                <textarea class="form-control" id="edittaskDesc"
                                    rows="3"
                                    placeholder="Enter task description"
                                    required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="editstartdate" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Start
                                    Date
                                </label>
                                <input type="date" class="form-control"
                                    id="editstartdate" required>
                            </div>

                            <div class="mb-3">
                                <label for="editdeadline" class="form-label">
                                    <i class="fas fa-calendar me-1"></i>Deadline
                                </label>
                                <input type="date" class="form-control"
                                    id="editdeadline" required>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check me-2"></i>Edit Task
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- ✅ Deadline Request Action Modal -->
        <div class="modal fade" id="deadlineRequestModal" tabindex="-1"
            aria-labelledby="deadlineRequestModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="deadlineRequestModalLabel">
                            <i class="fas fa-clock me-2"></i>Deadline Extension
                            Request
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form id="deadlineRequestForm">
                            <input type="hidden" id="requestTaskId">
                            <input type="hidden" id="TAId">

                            <div class="mb-4">
                                <label for="requestDeadline" class="form-label">
                                    <i
                                        class="fas fa-calendar-day me-1"></i>Requested
                                    Deadline
                                </label>
                                <input type="date" class="form-control"
                                    id="requestDeadline" required>
                                <div class="form-text">Selected Deadline</div>
                            </div>

                            <div class="mb-4">
                                <label for="requestReason" class="form-label">
                                    <i
                                        class="fas fa-comment-alt me-1"></i>Reason
                                    for Extension
                                </label>
                                <textarea class="form-control"
                                    id="requestReason" rows="4"
                                    placeholder="Enter the reason provided for deadline extension..."
                                    required></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger"
                                    id="rejectBtn">
                                    <i class="fas fa-times me-2"></i>Reject
                                </button>
                                <button type="button" class="btn btn-primary"
                                    id="approveBtn">
                                    <i class="fas fa-check me-2"></i>Approve
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- Submission proof-->
        <div class="modal fade" id="ViewSubmissionModal" tabindex="-1"
            aria-labelledby="ViewSubmissionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="ViewSubmissionModalLabel">
                            <i class="fas fa-eye me-2"></i>View Task Submission
                        </h5>
                        <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Hidden IDs -->
                        <input type="hidden" id="view_ti">
                        <input type="hidden" id="view_tai">

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i
                                    class="fas fa-align-left me-1"></i>Description
                                of Work Completed:
                            </label>
                            <p id="view_explanation"
                                class="form-control-plaintext border p-2 rounded bg-light">
                                <!-- Fetched explanation will appear here -->
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-file-image me-1"></i>Proof of
                                Completion:
                            </label>
                            <div id="view_proof"
                                class="border p-2 rounded bg-light text-center">
                                <!-- Proof (image/link/file) will appear here -->
                                <span class="text-muted">No proof
                                    uploaded</span>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger"
                            id="rejsub">
                            <i class="fas fa-times me-2"></i>Reject
                        </button>
                        <button type="button" class="btn btn-primary"
                            id="appsub">
                            <i class="fas fa-check me-2"></i>Approve
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- ✅ Scripts -->
        <script>
        let tableAssign;

        $(document).ready(function () {
            // Initialize DataTable once
            tableAssign = $('#parentTable').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                searching: true,
                ordering: false,
                info: true
            });

            loadAssignedTasks(tableAssign);

            // Show assign task modal using the same approach as your first sample
            $('#taskAddBtn').on('click', function () {
                const modalEl = $('#assigntomodal')[0];
                document.body.appendChild(modalEl); // ensure it's in <body>
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });

            // Assign task modal submission
            $('#taskForm').on('submit', function (e) {
                e.preventDefault();
                console.log("Form submitted");
                $.post('db/database.php', {
                    action: 'assigntasks',
                    title: $('#some').val(),
                    dept: $('#assignToDepartment').val(),
                    fac: $('#assignToFaculty').val(),
                    desc: $('#taskDesc').val(),
                    date: $('#startdate').val(),
                    deadline: $('#taskdl').val()
                }, function (res) {
                    if (res.response === 'success') {
                        Swal.fire({
                            title: "Task assigned",
                            text: "Task assigned successfully",
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: res.message || "Unknown error occurred.",
                            icon: "error"
                        });
                    }

                    $('#assigntomodal').modal('hide');
                    loadAssignedTasks(tableAssign);
                }, "json");
            });
        });

        // Function to load assigned tasks
        function loadAssignedTasks(table) {
            $.post('db/database.php', { action: 'readAssignedTasks' }, function (res) {
                table.clear();

                res.forEach((r, i) => {
                    let statusHtml = '';
                    let actionHtml = '';

                    // Status color
                    if (r.status.toLowerCase() === 'completed') {
                        statusHtml = `<span class='status_btn_inactive' style='background-color:green; color:white; padding:4px 12px; border-radius:20px; font-size:0.8em;'>${r.status}</span>`;
                    }
                    else if (r.status.toLowerCase() === 'overdue') {
                        statusHtml = `<span class='status_btn_inactive' style='background-color:red; color:white; padding:4px 12px; border-radius:20px; font-size:0.8em;'>${r.status}</span>`;
                    }
                    else if (r.status.toLowerCase() === 'assigned') {
                        statusHtml = `<span class='status_btn_inactive' style='background-color:blue; color:white; padding:4px 12px; border-radius:20px; font-size:0.8em;'>${r.status}</span>`;
                    }
                    else {
                        statusHtml = `<span class='status_btn_inactive' style='background-color:orange; color:white; padding:4px 12px; border-radius:20px; font-size:0.8em;'>${r.status}</span>`;
                    }

                    // Action column — if deadline extension requested, show button instead of text
                    if (r.action && r.action.toLowerCase() === 'deadline extension requested') {
                        actionHtml = `
                            <button class='btn btn-sm btn-warning deadline-request-btn' 
                                    onclick='showDeadlineRequestModal(${r.task_assignment_id})'
                                    data-task-id="${r.task_assignment_id}">
                                <i class="fa-solid fa-clock me-1"></i>Review Request
                            </button>`;
                    }
                    else if (r.action && r.action.toLowerCase() === 'submitted') {
                        actionHtml = `
                            <button class='btn btn-sm btn-success deadline-request-btn' 
                                    onclick='showSubmissionModal(${r.task_assignment_id})'
                                    data-task-id="${r.task_assignment_id}">
                                <i class="fa-solid fa-clock me-1"></i>Review Submission
                            </button>
                        `;
                    }
                    else {
                        actionHtml = r.action || ''; // keep normal text if no special case
                    }

                    // Add Edit/Delete buttons if NOT completed
                    if (r.status.toLowerCase() == 'assigned') {
                        statusHtml += `
                            <button class='btn btn-sm btn-primary ms-2 editBtn' 
                                    onclick='edittask(${r.task_assignment_id})'>
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class='btn btn-sm btn-danger ms-1 deleteBtn' 
                                    onclick='deletetask(${r.task_assignment_id})'>
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        `;
                    }

                    // Add row to DataTable
                    table.row.add([
                        i + 1,
                        r.assigned_to,
                        r.task_title,
                        r.task_description,
                        r.start_date,
                        r.deadline,
                        statusHtml,
                        actionHtml
                    ]);
                });

                table.draw();
            }, 'json');
        }

        // Modal dropdown setup
        $('#assignToDepartment').on('change', function () {
            if ($('#assignToDepartment').val() != '') {
                $.post('db/database.php', { action: 'filterfaculty', dept: $('#assignToDepartment').val() }, function (res) {
                    const facultyname = $('#assignToFaculty');
                    facultyname.find('option:not(:first)').remove();
                    res.forEach(r => {
                        facultyname.append(`<option value="${r.name}">${r.name}</option>`);
                    });
                }, "json");
            }
        });

        $('#editDepartment').on('change', function () {
            if ($('#editDepartment').val() != '') {
                $.post('db/database.php', { action: 'filterfaculty', dept: $('#editDepartment').val() }, function (res) {
                    const facultyname = $('#editFaculty');
                    facultyname.find('option:not(:first)').remove();
                    res.forEach(r => {
                        facultyname.append(`<option value="${r.name}">${r.name}</option>`);
                    });
                }, "json");
            }
        });

        // Function to show deadline request modal
        function showDeadlineRequestModal(taskId) {
            const modalEl = $('#deadlineRequestModal')[0];
            document.body.appendChild(modalEl);

            $.post('db/database.php', { action: 'getdlreq', tid: taskId }, function (res) {
                if (res) {
                    $('#requestTaskId').val(res.dr_id);
                    $('#requestDeadline').val(res.requested_deadline);
                    $('#requestReason').val(res.Reason);
                }

                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }, "json").fail(function (xhr, status, error) {
                console.error("Error fetching deadline request data:", error);
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });
        }

        // Approve button handler
        $('#approveBtn').on('click', function () {
            const drId = $('#requestTaskId').val();
            $('#deadlineRequestModal').modal('hide');
            Swal.fire({
                title: "Approve Request?",
                text: "Are you sure you want to approve this deadline extension?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Approve",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('db/database.php', {
                        action: 'approvereqdl',
                        drId: drId,
                        extndl : $("#requestDeadline").val()
                    }, function (res) {
                        if (res.response === 'success') {
                            Swal.fire({
                                title: "Approved!",
                                text: "Deadline extension approved successfully",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: res.message || "Failed to approve request",
                                icon: "error"
                            });
                        }
                    }, "json").fail(function (xhr, status, error) {
                        Swal.fire({
                            title: "Error",
                            text: "Request failed: " + error,
                            icon: "error"
                        });
                    });
                }
            });
            loadAssignedTasks(tableAssign);
        });

        // Reject button handler - FIXED
        $('#rejectBtn').on('click', function () {
            const drId = $('#requestTaskId').val();
            $('#deadlineRequestModal').modal('hide');
            Swal.fire({
                title: "Reject Request?",
                text: "Are you sure you want to reject this deadline extension?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Reject",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#dc3545"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('db/database.php', {
                        action: 'rejectreqdl',
                        drId: drId // Using drId consistently
                    }, function (res) {
                        if (res.response === 'success') {
                            Swal.fire({
                                title: "Rejected!",
                                text: "Deadline extension rejected",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: res.message || "Failed to reject request",
                                icon: "error"
                            });
                        }
                    }, "json").fail(function (xhr, status, error) {
                        Swal.fire({
                            title: "Error",
                            text: "Request failed: " + error,
                            icon: "error"
                        });
                    });
                }
            });
            loadAssignedTasks(tableAssign);
        });

        function showSubmissionModal(taskID) {
            const modalEl = $('#ViewSubmissionModal')[0];
            document.body.appendChild(modalEl);

            $.post('db/database.php', { action: 'getsub', tId: taskID }, function (res) {
                if (res) {
                    $('#view_tai').val(taskID);
                    $('#view_explanation').html(res.explanation);
                    if (res.proof && res.proof.trim() !== '') {
                        const fileUrl = res.proof.trim();
                        const fileExt = fileUrl.split('.').pop().toLowerCase();

                        let proofHTML = '';

                        // Handle file types
                        if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(fileExt)) {
                            // Show image preview
                            proofHTML = `
                        <a href="${fileUrl}" target="_blank">
                            <img src="${fileUrl}" alt="Proof Image"
                                class="img-fluid rounded shadow-sm" style="max-height:300px;">
                        </a>
                        <div class="mt-2">
                            <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                                View Full Image
                            </a>
                        </div>
                    `;
                        } else if (fileExt === 'pdf') {
                            // Show PDF inline
                            proofHTML = `
                        <iframe src="${fileUrl}" width="100%" height="400px" 
                            class="border rounded"></iframe>
                        <div class="mt-2">
                            <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-outline-primary">
                                Open PDF in new tab
                            </a>
                        </div>
                    `;
                        } else {
                            // All other file types: show download/view link
                            proofHTML = `
                        <div class="p-3">
                            <i class="fas fa-file me-2"></i>
                            <span>File uploaded:</span>
                            <a href="${fileUrl}" target="_blank" class="ms-2 text-decoration-none">
                                Open / Download File
                            </a>
                        </div>
                    `;
                        }

                        $('#view_proof').html(proofHTML);
                    } else {
                        $('#view_proof').html('<span class="text-muted">No proof uploaded</span>');
                    }
                }
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }, "json").fail(function (xhr, status, error) {
                console.error("Error fetching task submission data:", error);
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });
        }

        $('#appsub').on('click', function () {
            
            const tai = $('#view_tai').val();
            $('#ViewSubmissionModal').modal('hide');


            Swal.fire({
                title: "Approve Submission?",
                text: "Are you sure you want to approve this submission?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, Approve",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('db/database.php', {
                        action: 'approvesub',
                        tai: tai,
                    }, function (res) {
                        if (res.response === 'success') {
                            Swal.fire({
                                title: "Approved!",
                                text: "Submission approved successfully",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#ViewSubmissionModal').modal('hide');
                            loadAssignedTasks(tableAssign);
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: res.message || "Failed to approve request",
                                icon: "error"
                            });
                        }
                    }, "json").fail(function (xhr, status, error) {
                        Swal.fire({
                            title: "Error",
                            text: "Request failed: " + error,
                            icon: "error"
                        });
                    });
                }
            });
            loadAssignedTasks(tableAssign);
        });

        // Reject button handler - FIXED
        $('#rejsub').on('click', function () {
            const TaI= $('#view_tai').val();
            $('#ViewSubmissionModal').modal('hide');
            
            Swal.fire({
                title: "Reject Request?",
                text: "Are you sure you want to reject this submission?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Reject",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#dc3545"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('db/database.php', {
                        action: 'rejectsub',
                        drId: drId // Using drId consistently
                    }, function (res) {
                        if (res.response === 'success') {
                            Swal.fire({
                                title: "Rejected!",
                                text: "Submission of completed task rejected",
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                title: "Error",
                                text: res.message || "Failed to reject request",
                                icon: "error"
                            });
                        }
                    }, "json").fail(function (xhr, status, error) {
                        Swal.fire({
                            title: "Error",
                            text: "Request failed: " + error,
                            icon: "error"
                        });
                    });
                }
            });
            loadAssignedTasks(tableAssign);
        });


        // Close modal on escape key or backdrop click
        $('#deadlineRequestModal').on('hidden.bs.modal', function () {
            $('#deadlineRequestForm')[0].reset();
            $('#requestTaskId').val('');
        });

        // Delete task function
        function deletetask(taskAssId) {
            Swal.fire({
                title: 'Delete task',
                text: 'Do you really want to delete this task?',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'No',
                confirmButtonText: 'Yes, I want to delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('db/database.php', { action: 'deletetask', taskAsid: taskAssId }, function (res) {
                        Swal.fire({
                            title: 'Delete task',
                            text: 'Task deleted successfully',
                            icon: 'success'
                        })
                    }, "json");
                }
            });
            loadAssignedTasks(tableAssign);
        }

        // Open edit modal
        function edittask(id) {
            const modalEl = $('#editmodal')[0];
            document.body.appendChild(modalEl);

            $.post('db/database.php', { action: 'fetchrow', id: id }, function (res) {
                $("#editid").val(res.task_id);
                $("#editDepartment").val(res.ddept),
                    $("#editFaculty").val(res.name),
                    $("#editsome").val(res.task_title);
                $("#edittaskDesc").val(res.task_description);
                $("#editstartdate").val(res.start_date);
                $("#editdeadline").val(res.deadline);
            }, "json");

            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }

        // Edit task function
        $("#EditForm").on("submit", function (e) {
            e.preventDefault();
            $.post("db/database.php", {
                action: "editTask",
                id: $("#editid").val(),
                department: $("#editDepartment").val(),
                faculty: $("#editFaculty").val(),
                title: $("#editsome").val(),
                description: $("#edittaskDesc").val(),
                startdate: $("#editstartdate").val(),
                deadline: $("#editdeadline").val()
            }, function (res) {
                Swal.fire({
                    title: 'Update task',
                    text: 'Task updated successfully',
                    icon: 'success'
                })
                $('#editmodal').modal('hide');
                loadAssignedTasks(tableAssign);
            }, "json");
        });
    </script>
    </body>

</html>