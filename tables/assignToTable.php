<link rel="stylesheet" href="style/assigningTsomeone.css">
<style>
    .buttonclass {
        display: flex;
        gap: 5px;
    }
</style>
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
        <tbody id="assignTo">
            <!-- Dynamic rows will appear here -->
        </tbody>
    </table>
</div>

<script>
    let users = [];
    let loaduser = () => {
        $.post('db/database.php', { action: 'read' }, function (response) {
            if (response.success) {
                users = response.data;
                displayuser();
            }
        }, 'json')
    }
    let displayuser = () => {
        let tablebody = $('#assignTo').empty();
        if (users.length == 0) {
            tablebody.append(`<tr><td colspan="7" class="text-center text-muted">No data has Found</td></tr>`);
        }
        users.forEach(user => {
            tablebody.append(
                `
            <tr>
            <td>${user.task_id}</td>
            <td>${user.assigned_by}</td>
            <td>${user.task_title}</td>
            <td>${user.task_description}</td>
            <td>${user.start_date}</td>
            <td>${user.deadline}</td>
            
            <td class="buttonclass"><button type="button" class="btn btn-warning btn-sm">${user.status}</button><button type="button" class="btn btn-danger btn-sm" onclick="deleteUser('${user.task_id}')">Delete</button></td>
            
            
            </tr>`)
        })
    }

    let deleteUser = (id) => {
        $.post('db/database.php', { action: 'delete', id: id }, function (response) {
            if (response.success) {
                alert('deleted Successfully');
                loaduser();
            } else {
                alert('not deleted');
            }
        }, 'json');
    }
</script>