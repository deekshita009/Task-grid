<div class="tab-pane fade show active" id="scheduled" role="tabpanel">
  <table id="scheduledMeetingsTable" class="display table table-striped" style="width:100%">
    <thead>
      <tr class="header-gradient">
        <th>Assigned by</th>
        <th>title</th>
        <th>date</th>
        <th>start time</th>
        <th>end time</th>
      </tr>
    </thead>
    <tbody id="scheduledMeetingsBody">
      <!-- Data will be populated here via JavaScript -->

    </tbody>
  </table>
</div>
<script>
  let meeters = [];
  $(document).ready(function() {
    $.post('backend/scheduledMeeting.php', {
      action: 'read'
    }, function(data) {
      if (data.success) {
        meeters = data.meeters;
        readingmeeters();
      } else {
        console.error('Failed to fetch meeters:', data.message);
        $('#scheduledMeetingsBody').html('<tr><td colspan="5" class="text-center">No scheduled meetings found.</td></tr>');
      }
    }, 'json');
  });

  let readingmeeters = () => {
    if (meeters.length === 0) {
      $('#scheduledMeetingsBody').html('<tr><td colspan="5" class="text-center">No scheduled meetings found.</td></tr>');
    } else {
      $('#scheduledMeetingsBody').empty(); // Clear existing content first
      meeters.forEach(user => {
        $('#scheduledMeetingsBody').append(`
          <tr>
            <td>${user.principal_id}</td>
            <td>${user.event_title}</td>
            <td>${user.schedule_date}</td>
            <td>${user.start_time}</td>
            <td>${user.end_time}</td>
          </tr>
        `);
      });
    }
  }
  
</script>