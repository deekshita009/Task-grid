<link rel="stylesheet" href="style/assigningTsomeone.css">
<style>
    .buttonclass {
        display: flex;
        gap: 5px;
    }
</style>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover" id="parentTable">
        <thead>
            <tr class="assignto">
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
            <td>${user.name}</td>
            <td>${user.task_title}</td>
            <td>${user.task_description}</td>
            <td>${user.start_date}</td>
            <td>${user.deadline}</td>
            
            <td class="buttonclass"><button type="button" class="btn btn-warning btn-sm">${user.status}</button>
            <button type="button" class="btn btn-danger btn-sm" onclick="deleteUser('${user.task_id}')">Delete</button>
            <button type="button" class="btn btn-info btn-sm" onclick="edituser('${user.task_id}')">Edit</button>
            </td>
            
            
            </tr>`)
        })
    }
    //Edit assignment module
    let edituser = (id) => {
        let user = users.find(u => u.task_id == id);
        if (user) {
            $('#some').val(user.task_title);
            $('#taskDesc').val(user.task_description);
            $('#startdate').val(user.start_date);
            $('#deadline').val(user.deadline);
            $('#assigntomodal').modal('show');

            // Store the task_id for update
            window.editingTaskId = id;

            // Store the current assigned_by name for update
            window.currentAssignedBy = user.assigned_by;
        }
    }

    let deleteUser = (id) => {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('db/database.php', { action: 'delete', id: id }, function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: "Deleted!",
                            text: "Task has been deleted successfully.",
                            icon: "success"
                        });
                        loaduser();
                    }
                    else {
                        Swal.fire({
                            title: "Error!",
                            text: "Failed to delete the task.",
                            icon: "error"
                        });
                        alert(response.message);
                    }
                }, 'json');
            }
        });
    }
</script>