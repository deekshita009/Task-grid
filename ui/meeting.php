<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Conflict-Free Meeting Module</title>

    <!-- Bootstrap + Icons + DataTables -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        #conflictFreeMeeting {
            background: #f8f9fc;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial;
            padding: 20px;
        }

        #conflictFreeMeeting .cfm-btn {
            border-radius: 8px;
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: white;
            border: none;
        }

        #conflictFreeMeeting table.dataTable thead tr {
            background: linear-gradient(135deg, #3e8e40ff, #0d7fddff) !important;
            color: white !important;
            text-align: center;
        }

        #conflictFreeMeeting table.dataTable thead th {
            background: transparent !important;
        }

        #conflictFreeMeeting .cfm-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: 0.3s ease;
            margin-bottom: 30px;
            padding: 15px;
        }

        #conflictFreeMeeting .cfm-modal-header {
            background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
            color: #fff;
            border-bottom: none;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            padding: 20px 24px;
        }

        #conflictFreeMeeting .modal {
            z-index: 2000 !important;
        }

        #conflictFreeMeeting .modal-backdrop {
            z-index: 1050 !important;
        }
    </style>
</head>

<body>
    <div id="conflictFreeMeeting" class="container-fluid">
        <!-- Buttons -->
        <div class="d-flex justify-content-end mb-4 gap-3">
            <button id="cfmOpenAddBtn" class="cfm-btn d-flex align-items-center gap-2">
                <i class="fas fa-calendar-plus"></i> Add Meeting
            </button>
            <button id="cfmOpenRequestBtn" class="cfm-btn d-flex align-items-center gap-2">
                <i class="fas fa-user-tie"></i> Request Meeting
            </button>
        </div>

        <!-- Tabs -->
        <div class="cfm-card">
            <ul class="nav nav-tabs mb-3" id="cfmMeetingTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="cfmScheduledTab" data-bs-toggle="tab"
                        data-bs-target="#cfmScheduled" type="button" role="tab">Scheduled Meetings</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="cfmRequestedTab" data-bs-toggle="tab" data-bs-target="#cfmRequested"
                        type="button" role="tab">Requested Meetings</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Scheduled Meetings Table -->
                <div class="tab-pane fade show active" id="cfmScheduled" role="tabpanel">
                    <table id="cfmScheduledMeetingsTable" class="display table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Staff</th>
                                <th>Date & Time</th>
                                <th>Location</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Requested Meetings Table -->
                <div class="tab-pane fade" id="cfmRequested" role="tabpanel">
                    <table id="cfmRequestedMeetingsTable" class="display table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Preferred Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Add Meeting Modal -->
        <div class="modal fade" id="cfmAddMeetingModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="cfm-modal-header modal-header">
                        <h5 class="modal-title">Schedule Staff Meeting</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="cfmAddMeetingForm">
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
                                <label class="form-label">Select department</label>
                                <br><br>
                                <select name="staff" id="cfmStaffDropdown" required>
                                    <option disabled selected value="">--Select
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
                                    <option value="Japanese">Japanese dept</option>
                                    <option value="IQAC">IQAC</option>
                                </select>
                                <br><br>
                                <label for="fac_name">Choose faculty</label><br>
                                <select style="width:362px;" name="fac_name" id="fac_name" required>
                                    <option value="">--Select
                                        Faculty--</option>
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

        <!-- Request Meeting Modal -->
        <div class="modal fade" id="cfmRequestMeetingModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="cfm-modal-header modal-header">
                        <h5 class="modal-title">Request Principal Meeting</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="cfmRequestMeetingForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Request To</label>
                                <select class="form-select" name="request_to" id="cfmPrincipalDropdown" required>
                                    <option disabled selected value="">Select</option>
                                    <option value="P001">Principal</option>

                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Purpose</label>
                                <textarea class="form-control" name="purpose" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Preferred Date & Time</label>
                                <input type="datetime-local" class="form-control" name="datetime" required>
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


        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>

            $(document).ready(function () {

                // Initialize DataTables
                const scheduledTable = $('#cfmScheduledMeetingsTable').DataTable({
                    pageLength: 5,
                    language: { emptyTable: "No scheduled meetings" }
                });
                const requestedTable = $('#cfmRequestedMeetingsTable').DataTable({
                    pageLength: 5,
                    language: { emptyTable: "No meeting requests" }
                });

                // Open Add Meeting Modal
                $('#cfmOpenAddBtn').on('click', function () {
                    $('#cfmAddMeetingModal').appendTo('body').modal('show');
                    $('.modal-title', '#cfmAddMeetingModal').text('Schedule Staff Meeting');
                    $('#cfmAddMeetingForm')[0].reset();
                    $('#cfmAddMeetingForm').removeData('edit-id');

                    // Populate staff dropdown

                });

                // Open Request Meeting Modal
                $('#cfmOpenRequestBtn').on('click', function () {
                    $('#cfmRequestMeetingModal').appendTo('body').modal('show');
                });

                // ✅ Load meetings with Notify + Edit buttons
                function loadMeetings() {
                    $.getJSON('Backend/addMeeting.php?fetchMeetings=1', function (meetings) {
                        scheduledTable.clear();
                        meetings.forEach((m, i) => {
                            const actions = `
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="cfm-btn btn-sm btn-warning cfm-notify-btn" 
                                        data-email="${m.email}"
                                        data-title="${m.title}" 
                                        data-datetime="${m.datetime}"
                                        data-staff="${m.fac_name}">
                                        <i class="fas fa-bell"></i> Notify
                                    </button>
                                    <button class="btn btn-sm btn-info btn-edit" data-id="${i}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </div>
                            `;
                            scheduledTable.row.add([
                                m.title,
                                m.fac_name,
                                m.datetime,
                                m.location,
                                actions
                            ]);
                        });
                        scheduledTable.draw();
                    });

                    // Requested Meetings
                    $.getJSON('Backend/requestMeeting.php?fetchRequests=1', function (response) {
                        requestedTable.clear();
                        if (response.success && response.data) {
                            response.data.forEach(req => {
                                requestedTable.row.add([
                                    req.purpose,
                                    req.datetime,
                                    req.status,

                                ]);
                            });
                        }
                        requestedTable.draw();
                    });
                }

                loadMeetings();

                // ✅ Add Meeting
                $(document).on('submit', '#cfmAddMeetingForm', function (e) {
                    e.preventDefault();
                    const editId = $(this).data('edit-id');
                    const url = editId
                        ? 'Backend/addMeeting.php?updateMeeting=1'
                        : 'Backend/addMeeting.php';

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: editId ? 'Meeting Updated' : 'Meeting Scheduled',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                $('#cfmAddMeetingModal').modal('hide');
                                $('#cfmAddMeetingForm')[0].reset();
                                loadMeetings();
                            } else {
                                Swal.fire({ 
                                    icon: 'error', 
                                    title: 'Error', 
                                    text: response.message || 'Failed to schedule meeting'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({ 
                                icon: 'error', 
                                title: 'Error', 
                                text: 'Failed to communicate with server'
                            });
                            console.error('Ajax error:', error);
                        }
                    });
                });

                // ✅ Request Meeting
                $(document).on('submit', '#cfmRequestMeetingForm', function (e) {
                    e.preventDefault();
                    console.log($(this).serialize());
                    $.post('Backend/requestMeeting.php', $(this).serialize(), function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Request Submitted',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            $('#cfmRequestMeetingModal').modal('hide');
                            $('#cfmRequestMeetingForm')[0].reset();
                            loadMeetings();
                        } else {
                            Swal.fire({ icon: 'error', title: 'Error', text: response.message });
                        }
                    }, 'json');
                });

                // ✅ Notify Button
                // Replace the existing notify button click handler
                $(document).on('click', '.cfm-notify-btn', function () {
                    const btn = $(this);
                    const email = btn.data('email');
                    const title = btn.data('title');
                    const datetime = btn.data('datetime');
                    const staff = btn.data('staff');
                
                    Swal.fire({
                        title: 'Notify staff?',
                        text: 'Send meeting notification via email.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, notify'
                    }).then(res => {
                        if (res.isConfirmed) {
                            $.ajax({
                                url: 'Backend/notifyMeeting.php',
                                type: 'POST',
                                data: {
                                    email: email,
                                    title: title,
                                    datetime: datetime,
                                    staff: staff
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            toast: true,
                                            icon: 'success',
                                            title: 'Email notification sent',
                                            timer: 1500,
                                            showConfirmButton: false
                                        });
                                        btn.removeClass('btn-warning')
                                            .addClass('btn-success')
                                            .html('<i class="fas fa-check"></i> Notified')
                                            .prop('disabled', true);
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: response.message || 'Failed to send notification'
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Failed to send notification'
                                    });
                                }
                            });
                        }
                    });
                });

                // ✅ Edit Button — opens modal with existing meeting info
                $(document).on('click', '.btn-edit', function () {
                    const row = $(this).closest('tr');
                    const data = scheduledTable.row(row).data();
                    if (!data) return;

                    $('#cfmAddMeetingModal').appendTo('body').modal('show');
                    $('.modal-title', '#cfmAddMeetingModal').text('Edit Meeting');

                    $('#cfmAddMeetingForm [name="title"]').val(data[0]);
                    $('#cfmAddMeetingForm [name="agenda"]').val('');
                    $('#cfmAddMeetingForm [name="staff"]').val('Everyone');
                    $('#cfmAddMeetingForm [name="datetime"]').val('');
                    $('#cfmAddMeetingForm [name="mode"]').val('Online');
                    $('#cfmAddMeetingForm [name="location"]').val(data[3]);
                    $('#cfmAddMeetingForm').data('edit-id', $(this).data('id'));
                });


                $('#cfmStaffDropdown').on('change', function () {
                    const dept = $(this).val();
                    if (!dept) return;

                    console.log("Selected dept:", dept);
                    $.post('Backend/addMeeting.php', { action: 'filterfaculty', dept }, function (res) {
                        console.log("Faculty list:", res);

                        // Re-select inside callback to avoid detached DOM issue
                        const facultyname = $('#cfmAddMeetingModal').find('#fac_name');
                        facultyname.empty().append(`<option value="">--Select Faculty--</option>`);

                        if (Array.isArray(res)) {
                            res.forEach(r => {
                                facultyname.append(`<option value="${r.name}">${r.name}</option>`);
                            });
                        }
                    }, "json");
                });

            });

        </script>
</body>

</html>