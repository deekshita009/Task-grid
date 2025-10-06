<!-- Assign to Someone Tab Content -->
<link rel="stylesheet" href="style/assigningTsomeone.css">
<style>
    .gradient-header {
        --bs-table-bg: transparent;
        --bs-table-color: white;
        background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
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
<div id="assign-by-me" class="container mt-4">
    <div class="assignbutton">
        <button id="taskAddBtn" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#assigntomodal"
            style="background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
">
            Assign Task
        </button>
    </div>

    <!-- assignToTable.php-->
    <?php include "tables/assignToTable.php"?>
</div>

<!-- Move modal outside the tab content container for full-screen popup -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Move the modal to body root to ensure it appears as full-screen popup
        const modal = document.getElementById('assigntomodal');
        if (modal && !document.body.contains(modal)) {
            document.body.appendChild(modal);
        }
    });
</script>



<!-- Task Assignment Modal - Positioned for full-screen popup -->
<div class="modal fade" id="assigntomodal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
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

<!-- Tab Content JavaScript for Assign to Someone -->
<script>
    (function () {
        'use strict';

        let table;
        let taskCounter = 1;
        let initialized = false;

        function initializeAssignToSomeone() {
            if (initialized) return;

            // Move modal to body root for full-screen popup
            const modal = document.getElementById('assigntomodal');
            if (modal && modal.parentElement !== document.body) {
                document.body.appendChild(modal);
            }

            // Initialize DataTable
            if ($.fn.DataTable.isDataTable('#parentTable')) {
                $('#parentTable').DataTable().destroy();
            }

            table = $('#parentTable').DataTable({
                language: {
                    emptyTable: "No data available in table"
                },
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']] // Latest tasks first
            });

            // Handle form submission
            $('#taskForm').off('submit').on('submit', function (e) {
                e.preventDefault();

                let assignTo = $('#assignTo').val();
                let title = $('#taskTitle').val();
                let desc = $('#taskDesc').val();
                let deadline = $('#deadline').val();
                let assignedDate = new Date().toLocaleDateString();
                let status = 'Pending';

                // Add to table
                table.row.add([
                    taskCounter++,
                    assignTo,
                    title,
                    desc,
                    assignedDate,
                    deadline,
                    `<span class="badge bg-warning">${status}</span>`
                ]).draw(false);

                // Hide modal and reset form
                const modalElement = document.getElementById('assigntomodal');
                const bootstrapModal = bootstrap.Modal.getInstance(modalElement);
                if (bootstrapModal) {
                    bootstrapModal.hide();
                }

                this.reset();

                // Show success message
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

            initialized = true;
        }

        // Initialize when tab is shown
        $(document).on('shown.bs.tab', 'a[href="#assigning"]', function () {
            setTimeout(initializeAssignToSomeone, 200);
        });

        // Initialize immediately if already on this tab
        $(document).ready(function () {
            if ($('#assigning').hasClass('show active') || window.location.hash === '#assigning') {
                setTimeout(initializeAssignToSomeone, 100);
            }
        });

    })();
</script>