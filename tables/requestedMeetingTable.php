<div class="tab-pane fade" id="requested" role="tabpanel">
  <table id="requestedMeetingsTable" class="display table table-striped" style="width:100%">
    <thead>
      <tr class="header-gradient">
        <th>Requested To</th>
        <th>Staff</th>
        <th>Purpose</th>
        <th>Preferred Date & Time</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody id="requestedMeetingsBody">
      <!-- Data will be populated here via JavaScript -->
    </tbody>
  </table>
</div>

<script>
$(document).ready(function() {
    const requestedDT = $('#requestedMeetingsTable').DataTable({
        pageLength: 5,
        language: { emptyTable: "No meeting requests" },
        order: [[3, 'desc']] // order by date & time
    });

    window.loadRequestedMeetings = function() {
        console.log('Loading requested meetings...');
        $.ajax({
            url: 'backend/requestMeeting.php',
            method: 'GET',
            data: { fetchRequests: 1 },
            dataType: 'json',
            success: function(response) {
                console.log('Received requested meetings response:', response);
                requestedDT.clear();

                if (response.success && response.data && response.data.length > 0) {
                    response.data.forEach(request => {
                        const badgeClass =
                            request.status === 'Pending' ? 'bg-warning text-dark' :
                            request.status === 'Approved' ? 'bg-success' : 'bg-danger';

                        requestedDT.row.add([
                            escapeHtml(request.requested_to),
                            escapeHtml(request.users),
                            escapeHtml(request.purpose),
                            escapeHtml(request.request_time),
                            `<span class="badge ${badgeClass}">${escapeHtml(request.status)}</span>`
                        ]);
                    });
                }

                requestedDT.draw();
            },
            error: function(xhr, status, error) {
                console.error('Error loading meetings:', error);
            }
        });
    }

    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    loadRequestedMeetings();
    setInterval(loadRequestedMeetings, 30000);
});
</script>
