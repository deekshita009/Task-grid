<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>MIC - Styled Like Friend</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        :root {
            --light-bg: #f8f9fc;
        }

        html,
        body {
            height: 100%;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: var(--light-bg);
            overflow-x: hidden;
        }

        .container-fluid {
            padding: 40px 24px;
        }

        .btn {
            border-radius: 8px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: white;
            border: none;
        }

        /* Modal styling like friend’s UI */
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

        /* Gradient table headers */
        #meetingsTable thead,
        #parentTable thead {
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
            color: white !important;
            text-align: center;
        }

        /* Table responsiveness */
        .table-responsive {
            margin-bottom: 40px;
        }

        /* z-index fixes for modals */
        .modal-backdrop {
            z-index: 2000 !important;
            background-color: rgba(0, 0, 0, 0.55) !important;
            backdrop-filter: blur(3px);
        }

        .modal {
            position: fixed !important;
            z-index: 2100 !important;
        }

        .modal-dialog {
            transform: translateY(-6px);
            transition: transform .18s ease, opacity .18s ease;
        }

        .modal.show .modal-dialog {
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <div class="container-fluid app-content">
        <!-- Buttons aligned to right -->
        <div class="d-flex justify-content-end mb-4 gap-3">
            <button id="openAddBtn" class="btn d-flex align-items-center gap-2">
                <i class="fas fa-calendar-plus"></i> Add Meeting
            </button>
            <button id="openRequestBtn" class="btn d-flex align-items-center gap-2">
                <i class="fas fa-user-tie"></i> Request Meeting
            </button>
        </div>

        <?php include "tables/meetingTable.php"; ?>

    </div>

    <!-- Add Meeting Modal -->
    <div class="modal fade" id="addMeetingModal" tabindex="-1" aria-labelledby="addMeetingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMeetingModalLabel">Schedule Staff Meeting</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="addMeetingForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Meeting Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Agenda</label>
                            <textarea class="form-control" name="agenda" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Staff</label>
                            <select class="form-select" name="staff" required>
                                <option value="Everyone">Everyone</option>
                                <option value="John">John</option>
                                <option value="Priya">Priya</option>
                                <option value="Rahul">Rahul</option>
                                <option value="Meena">Meena</option>
                                <option value="David">David</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date & Time</label>
                            <input type="datetime-local" class="form-control" name="datetime" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mode</label>
                            <select class="form-select" name="mode">
                                <option>Online</option>
                                <option>Offline</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location / Link</label>
                            <input type="text" class="form-control" name="location" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn">Schedule Meeting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Request Meeting Modal -->
    <div class="modal fade" id="requestMeetingModal" tabindex="-1" aria-labelledby="requestMeetingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestMeetingModalLabel">Request Principal Meeting</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="requestMeetingForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Meeting Title</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Purpose</label>
                            <textarea class="form-control" name="purpose" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Preferred Date & Time</label>
                            <input type="datetime-local" class="form-control" name="datetime" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Additional Notes</label>
                            <textarea class="form-control" name="notes"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            // Move modals to body
            ['#addMeetingModal', '#requestMeetingModal'].forEach(id => {
                const modalEl = $(id)[0];
                if (modalEl) document.body.appendChild(modalEl);
            });

            // Initialize DataTables for both tables
            $('#meetingsTable').DataTable({
                responsive: true,
                pageLength: 5,
                language: { emptyTable: "No meetings scheduled" },
            });
            $('#parentTable').DataTable({
                responsive: true,
                pageLength: 5,
                language: { emptyTable: "No tasks assigned" },
            });

            // Show modals programmatically
            $('#openAddBtn').on('click', function () {
                $('.modal-backdrop').remove();
                bootstrap.Modal.getOrCreateInstance($('#addMeetingModal')[0]).show();
            });

            $('#openRequestBtn').on('click', function () {
                $('.modal-backdrop').remove();
                bootstrap.Modal.getOrCreateInstance($('#requestMeetingModal')[0]).show();
            });

            // Form submit handling for Add Meeting
            $('#addMeetingForm').on('submit', function (e) {
                e.preventDefault();
                const title = $(this).find('[name="title"]').val();
                const staff = $(this).find('[name="staff"]').val();
                const datetime = $(this).find('[name="datetime"]').val();

                $('#meetingsTable').DataTable().row.add([
                    title,
                    'Staff (' + staff + ')',
                    datetime ? new Date(datetime).toLocaleString() : '-',
                    '<span class="text-success"><i class="fas fa-check-circle"></i> Scheduled</span>',
                    '<button class="btn btn-warning btn-sm"><i class="fas fa-bell"></i> Notify</button>'
                ]).draw(false);

                this.reset();
                bootstrap.Modal.getInstance($('#addMeetingModal')[0]).hide();
            });

            // Form submit handling for Request Meeting
            $('#requestMeetingForm').on('submit', function (e) {
                e.preventDefault();
                const title = $(this).find('[name="title"]').val();
                const datetime = $(this).find('[name="datetime"]').val();

                $('#meetingsTable').DataTable().row.add([
                    title,
                    'Principal',
                    datetime ? new Date(datetime).toLocaleString() : '-',
                    '<span class="text-warning"><i class="fas fa-hourglass-half"></i> Pending</span>',
                    '<span class="text-secondary">Awaiting approval</span>'
                ]).draw(false);

                this.reset();
                bootstrap.Modal.getInstance($('#requestMeetingModal')[0]).hide();
            });
        });
    </script>
</body>

</html>