<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Assign By Me</title>
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-5/bootstrap-5.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .gradient-header {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
            text-align: center;
            font-size: 0.9em;
        }

        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: #fff;
            border-bottom: none;
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.35rem;
            font-weight: 500;
        }

        .close {
            color: #fff;
            opacity: 1;
            font-size: 1.5rem;
            border: none;
            background: transparent;
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
        <button id="taskAddBtn" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#assigntomodal"
            style="background: linear-gradient(to right, #a3c81c, #6d9409, #166f06); border: none;">
            Assign Task
        </button>
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

    <!-- Task Assignment Mosdal -->
    <div class="modal fade" id="assigntomodal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel">Assign Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm">
                        <div class="mb-3">
                            <label for="assignTo" class="form-label">Assign To</label>
                            <select class="form-select" id="assignTo" required>
                                <option value="">-- Select Faculty --</option>
                                <!-- Add faculty options below -->
                                <option value="Faculty 1">Faculty 1</option>
                                <option value="Faculty 2">Faculty 2</option>
                                <option value="Faculty 3">Faculty 3</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="taskTitle" class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="taskTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="taskDesc" class="form-label">Task Description</label>
                            <textarea class="form-control" id="taskDesc" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="deadline" class="form-label">Deadline</label>
                            <input type="date" class="form-control" id="deadline" required>
                        </div>
                        <button type="submit" class="btn btn-success">Assign Task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Required JS libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        $(document).ready(function () {
            let table = $('#parentTable').DataTable({
                language: {
                    emptyTable: "No data available in table"
                }
            });
            let taskCounter = 1;

            $('#taskForm').submit(function (e) {
                e.preventDefault();

                let assignTo = $('#assignTo').val();
                let title = $('#taskTitle').val();
                let desc = $('#taskDesc').val();
                let deadline = $('#deadline').val();
                let assignedDate = new Date().toLocaleDateString();
                let status = 'Pending';

                table.row.add([
                    taskCounter++,
                    assignTo,
                    title,
                    desc,
                    assignedDate,
                    deadline,
                    status
                ]).draw(false);

                $('#assigntomodal').modal('hide');
                this.reset();

                Swal.fire({
                    icon: 'success',
                    title: 'Task Assigned',
                    text: `Task "${title}" assigned to ${assignTo}!`,
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });
        $(document).ready(function () {
            $('#parentTable').DataTable();
        });

    </script>
</body>

</html>