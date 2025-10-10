<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Conflict-Free Meeting Module</title>

    <!-- ✅ Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- ✅ Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- ✅ DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        /* Scoped under .my-meeting-module to avoid conflicts */
        .my-meeting-module {
            --light-bg: #f8f9fc;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            background: var(--light-bg);
            padding: 20px;
        }

        .my-meeting-module .btn {
            border-radius: 8px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: white;
            border: none;
        }

        .my-meeting-module .modal-header {
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: #fff;
            border-bottom: none;
            padding: 20px 24px;
        }

        .my-meeting-module .modal-title {
            font-size: 1.35rem;
            font-weight: 500;
        }

        .my-meeting-module .modal-body {
            padding: 24px;
            background: #fff;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .my-meeting-module .modal-footer {
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px;
            border-top: none;
            background: #f7f7f9;
        }

        .my-meeting-module table.dataTable thead {
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;
            color: white !important;
            text-align: center;
        }

        .my-meeting-module .table-responsive {
            margin-bottom: 40px;
        }

        /* Fix z-index & modal stacking */
        .modal-backdrop {
            z-index: 2000 !important;
            background-color: rgba(0, 0, 0, 0.55) !important;
            backdrop-filter: blur(3px);
        }

        .modal {
            z-index: 2100 !important;
        }

        .thcolor {
            background: linear-gradient(135deg, #4CAF50, #2196F3);
        }
    </style>
</head>

<body>
    <div class="container-fluid my-meeting-module">
        <!-- Buttons aligned to right -->
        <div class="d-flex justify-content-end mb-4 gap-3">
            <button id="myOpenAddBtn" class="btn d-flex align-items-center gap-2">
                <i class="fas fa-calendar-plus"></i> Add Meeting
            </button>
            <button id="myOpenRequestBtn" class="btn d-flex align-items-center gap-2">
                <i class="fas fa-user-tie"></i> Request Meeting
            </button>
        </div>

        <!-- Table -->
        <?php include "ui/meetingTable.php" ?>

        <!-- ✅ Add Meeting Modal -->
        <div class="modal fade" id="myAddMeetingModal" tabindex="-1" aria-labelledby="myAddMeetingModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myAddMeetingModalLabel">Schedule Staff Meeting</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="myAddMeetingForm">
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
                            <button type="submit" class="btn btn-primary">Schedule Meeting</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ✅ Request Meeting Modal -->
        <div class="modal fade" id="myRequestMeetingModal" tabindex="-1" aria-labelledby="myRequestMeetingModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myRequestMeetingModalLabel">Request Principal Meeting</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form id="myRequestMeetingForm">
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
                            <button type="submit" class="btn btn-primary">Submit Request</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- ✅ Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ✅ DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- ✅ SweetAlert2 for nicer alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            const myTable = $('#myMeetingsTable').DataTable({
                responsive: true,
                pageLength: 5,
                language: { emptyTable: "No meetings scheduled" },
            });
            
            // ✅ Show Add Meeting modal
            $('#myOpenAddBtn').on('click', function () {
                const modalEl = $('#myAddMeetingModal')[0];
                document.body.appendChild(modalEl); // ensure it's in <body>
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });

            // ✅ Show Request Meeting modal
            $('#myOpenRequestBtn').on('click', function () {
                const modalEl = $('#myRequestMeetingModal')[0];
                document.body.appendChild(modalEl);
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            });

            // ✅ Add Meeting Form Submit
            $('#myAddMeetingForm').on('submit', function (e) {
                e.preventDefault();
                const title = $(this).find('[name="title"]').val();
                const staff = $(this).find('[name="staff"]').val();
                const datetime = $(this).find('[name="datetime"]').val();

                myTable.row.add([
                    title,
                    'Staff (' + staff + ')',
                    datetime ? new Date(datetime).toLocaleString() : '-',
                    '<span class="text-success"><i class="fas fa-check-circle"></i> Scheduled</span>',
                    '<button class="btn btn-warning btn-sm btn-notify"><i class="fas fa-bell"></i> Notify</button>'
                ]).draw(false);

                this.reset();
                bootstrap.Modal.getInstance($('#myAddMeetingModal')[0]).hide();
            });

            // ✅ Request Meeting Form Submit (connected to backend)
            $('#myRequestMeetingForm').on('submit', function (e) {
                e.preventDefault();
                const formData = $(this).serialize();

                $.ajax({
                    url: 'meetBackend/requestMeeting.php', // Adjust path based on folder
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        response = response.trim();
                        if (response === "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Request Sent!',
                                text: 'Your meeting request has been submitted.',
                                timer: 1800,
                                showConfirmButton: false
                            });

                            const datetime = $('[name="datetime"]').val();

                            // Add to table instantly
                            myTable.row.add([
                                'Meeting Request', // Since title cannot be stored
                                'Principal',
                                datetime ? new Date(datetime).toLocaleString() : '-',
                                '<span class="text-warning"><i class="fas fa-hourglass-half"></i> Pending</span>',
                                '<span class="text-secondary">Awaiting approval</span>'
                            ]).draw(false);

                            $('#myRequestMeetingForm')[0].reset();
                            bootstrap.Modal.getInstance($('#myRequestMeetingModal')[0]).hide();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                html: 'Something went wrong.<br>' + response
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Could not connect to the server.'
                        });
                    }
                });
            });



            // ✅ Delegated handler for Notify button (uses SweetAlert2)
            $(document).on('click', '.btn-notify', function (e) {
                e.preventDefault();
                const $btn = $(this);
                Swal.fire({
                    title: 'Send notification?',
                    text: 'This will notify selected staff about the meeting.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, notify',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Simulate an action (AJAX can be added here)
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Notified successfully',
                            showConfirmButton: false,
                            timer: 1600
                        });
                        // update button state
                        $btn.removeClass('btn-warning').addClass('btn-success').html('<i class="fas fa-bell"></i> Notified').prop('disabled', true);
                    }
                });
            });
        });
    </script>
</body>

</html>