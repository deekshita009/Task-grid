<!-- Assign to Someone Tab Content -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign to Someone</title>

    <!-- ✅ Bootstrap & jQuery -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ✅ SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ✅ DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style/assigningTsomeone.css">

    <style>
        .gradient-header {
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
            color: white !important;
            text-align: center;
            font-size: 0.9em;
        }

        .modal-header {
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: #fff;
            border-bottom: none;
            padding: 20px 24px;
        }

        .modal-title {
            font-size: 1.35rem;
            font-weight: 500;
        }

        .modal-body {
            padding: 24px;
            background: #fff;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .modal-footer {
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
            border-top: none;
            background: #f7f7f9;
        }
    </style>
</head>

<body>
    <div id="assign-by-me" class="container mt-4">
        <div class="assignbutton">
            <button id="taskAddBtn" class="btn btn-primary mb-2"
                data-bs-toggle="modal"
                data-bs-target="#assigntomodal"
                style="background: linear-gradient(to right, #a3c81c, #6d9409, #166f06);">
                Assign Task
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="parentTable">
                <thead class="gradient-header text-black">
                    <tr>
                        <th>S.No</th>
                        <th>Assign_To</th>
                        <th>Task_Title</th>
                        <th>Task_Description</th>
                        <th>Assigned_Date</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dynamic rows will appear here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- ✅ Task Assignment Modal -->
    <div class="modal fade" id="assigntomodal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel">
                        <i class="fas fa-tasks me-2"></i>Assign Task
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm">
                        <div class="mb-3">
                            <label for="assignTo" class="form-label">
                                <i class="fas fa-user me-1"></i>Assign To
                            </label>
                            <select class="form-select" id="assignTo" required>
                                <option value="">-- Select Faculty --</option>
                                <option value="Faculty 1">Faculty 1</option>
                                <option value="Faculty 2">Faculty 2</option>
                                <option value="Faculty 3">Faculty 3</option>
                                <option value="Faculty 4">Faculty 4</option>
                                <option value="Faculty 5">Faculty 5</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="taskTitle" class="form-label">
                                <i class="fas fa-heading me-1"></i>Task Title
                            </label>
                            <input type="text" class="form-control" id="taskTitle" placeholder="Enter task title" required>
                        </div>
                        <div class="mb-3">
                            <label for="taskDesc" class="form-label">
                                <i class="fas fa-align-left me-1"></i>Task Description
                            </label>
                            <textarea class="form-control" id="taskDesc" rows="3" placeholder="Enter task description"
                                required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="deadline" class="form-label">
                                <i class="fas fa-calendar me-1"></i>Deadline
                            </label>
                            <input type="date" class="form-control" id="deadline" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check me-2"></i>Assign Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Script Section -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('assigntomodal');
            if (modal && modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }

            let table;
            let taskCounter = 1;

            // Initialize DataTable
            if ($.fn.DataTable.isDataTable('#parentTable')) {
                $('#parentTable').DataTable().destroy();
            }

            table = $('#parentTable').DataTable({
                language: { emptyTable: "No data available in table" },
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']]
            });

            // ✅ Handle form submission
            $('#taskForm').on('submit', function (e) {
                e.preventDefault();

                let assignTo = $('#assignTo').val();
                let title = $('#taskTitle').val();
                let desc = $('#taskDesc').val();
                let deadline = $('#deadline').val();
                let assignedDate = new Date().toLocaleDateString();
                let status = 'Pending';

                // Add new row
                table.row.add([
                    taskCounter++,
                    assignTo,
                    title,
                    desc,
                    assignedDate,
                    deadline,
                    `<span class="badge bg-warning">${status}</span>`
                ]).draw(false);

                // ✅ Properly hide modal and backdrop
                const modalElement = document.getElementById('assigntomodal');
                let bootstrapModal = bootstrap.Modal.getInstance(modalElement);
                if (!bootstrapModal) {
                    bootstrapModal = new bootstrap.Modal(modalElement);
                }
                bootstrapModal.hide();

                // Ensure backdrop is removed and page becomes active again
                setTimeout(() => {
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = 'auto';
                    document.body.style.paddingRight = '0';
                }, 400);

                // Reset form
                this.reset();

                // ✅ Show success alert
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Task Assigned Successfully!',
                        text: `Task "${title}" has been assigned to ${assignTo}`,
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                } else {
                    alert(`Task "${title}" assigned to ${assignTo} successfully!`);
                }
            });
        });
    </script>
</body>
</html>
