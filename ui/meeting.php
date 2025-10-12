<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Conflict-Free Meeting Module</title>

  <!-- ✅ Bootstrap + Icons + DataTables -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

  <style>
    body {
      background: #f8f9fc;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    }

    .btn {
      border-radius: 8px;
      background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
      color: white;
      border: none;
    }

    table.dataTable thead tr {
      background: linear-gradient(135deg, #3e8e40ff, #0d7fddff) !important;
      color: white !important;
      text-align: center;
    }

    table.dataTable thead th {
      background: transparent !important;
    }

    .meeting-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: 0.3s ease;
      margin-bottom: 30px;
      padding: 15px;
    }

    .modal-header {
      background: linear-gradient(to right, rgba(106, 17, 203, 0.9), rgba(37, 117, 252, 0.9));
      color: #fff;
      border-bottom: none;
      border-top-left-radius: 16px;
      border-top-right-radius: 16px;
      padding: 20px 24px;
    }

    .modal {
      z-index: 2000 !important;
    }

    .modal-backdrop {
      z-index: 1050 !important;
    }
  </style>
</head>

<body>
  <div class="container-fluid py-4">

    <!-- ✅ Request Meeting Button -->
    <div class="d-flex justify-content-end mb-4 gap-3">
      <button id="myOpenRequestBtn" class="btn d-flex align-items-center gap-2" style="color:white;">
        <i class="fas fa-user-tie"></i> Request Meeting
      </button>
    </div>

    <!-- ✅ Tabs for Meetings -->
    <div class="meeting-card">
      <ul class="nav nav-tabs mb-3" id="meetingTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="scheduled-tab" data-bs-toggle="tab" data-bs-target="#scheduled"
            type="button" role="tab">Scheduled Meetings</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="requested-tab" data-bs-toggle="tab" data-bs-target="#requested" type="button"
            role="tab">Requested Meetings</button>
        </li>
      </ul>

      <div class="tab-content">
        <!-- ✅ Scheduled Meetings Table -->
        <?php include "tables/scheduledMeetingTable.php"; ?>

        <!-- ✅ Requested Meetings Table -->
        <?php include "tables/requestedMeetingTable.php"; ?>
      </div>
    </div>
  </div>

  <!-- ✅ Request Meeting Modal -->
  <div class="modal fade" id="myRequestMeetingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Request Principal Meeting</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <!-- ✅ Request Meeting Form -->
        <form id="requestMeetingForm">
          <div class="mb-3">
            <label class="form-label">Purpose</label>
            <input type="text" id="purpose" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Preferred Date & Time</label>
            <input type="datetime-local" id="request_time" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary">Request Meeting</button>
        </form>
      </div>
    </div>
  </div>

  <!-- ✅ Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(document).ready(function() {
      // ✅ Initialize Scheduled Meetings Table
      const scheduledTable = $('#scheduledMeetingsTable').DataTable({
        pageLength: 5,
      });

      // ✅ Open Modal
      $('#myOpenRequestBtn').on('click', function() {
        $('#myRequestMeetingModal').appendTo('body').modal('show');
      });

      // ✅ Load Scheduled Meetings
      function loadScheduledMeetings() {
        $.getJSON('../backend/scheduledMeeting.php?fetchMeetings=1', function(meetings) {
          scheduledTable.clear();
          meetings.forEach(m => {
            scheduledTable.row.add([
              m.title,
              m.staff,
              m.datetime,
              m.location
            ]);
          });
          scheduledTable.draw();
        }).fail(function(xhr, status, err) {
          console.error('Failed to load scheduled meetings:', err);
        });
      }
      loadScheduledMeetings();

      // ✅ Request Meeting Form Submission (Replaced Section)
      document.getElementById('requestMeetingForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = {
          request_by: 'STAFF001',     // later from session
          requested_to: 'HOD001',     // later dynamic or dropdown
          purpose: document.getElementById('purpose').value.trim(),
          request_time: document.getElementById('request_time').value
        };

        try {
          const res = await fetch('backend/requestMeeting.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
          });

          const data = await res.json();
          console.log('Server Response:', data);

          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Meeting Requested',
              timer: 1500,
              showConfirmButton: false
            });

            $('#myRequestMeetingModal').modal('hide');
            document.getElementById('requestMeetingForm').reset();

            if (typeof window.loadRequestedMeetings === 'function') {
              window.loadRequestedMeetings();
            }
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.message || 'Failed to submit meeting request.'
            });
          }

        } catch (err) {
          console.error('Fetch error:', err);
          Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'Failed to submit request: ' + err.message
          });
        }
      });
    });
  </script>
</body>

</html>
