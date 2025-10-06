<html>
    <head>
        <!-- DataTables CSS -->
        <link rel="stylesheet"
            href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

        <!-- DataTables JS -->
        <script
            src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <style>
            table{
                text-align:center;
                width:100%;
                border-collapse:collapse;
                color:black;
                font-size:medium;
            }
            .headrow{
                color:white;
                font-weight:bold;
                background:linear-gradient(to right, rgb(28, 159, 28), rgb(1, 119, 159));
            }
            tr:nth-child(even){
                background-color:rgb(224, 224, 253);
            }
            tr:nth-child(odd){
                background-color:white;
            }
            td,th{
                
                text-align:center;
                border:1px solid white !important;
                padding: 5px;

            }
        </style>
    </head>
    <body>
        <div class="tab_task" id="tab_task">
            <table id="task_table">
                <thead>
                    <tr class="headrow">
                        <th>S.No</th>
                        <th>Assigned_by</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Assigned_date</th>
                        <th>Deadline</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Faculty</td>
                        <td>Complete the hw</td>
                        <td>hhkjbhjnk</td>
                        <td>owefbe</td>
                        <td>nowehfwe</td>
                        <td>Pending</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Faculty</td>
                        <td>Complete the hw</td>
                        <td>hhkjbhjnk</td>
                        <td>owefbe</td>
                        <td>nowehfwe</td>
                        <td>Pending</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </body>
    <script>
$(document).ready(function() {
    $('#task_table').DataTable({
        "pageLength": 10,  // Show 10 entries by default
        "lengthMenu": [5, 10, 25, 50, 100], // dropdown options
        "searching": true,  // enable search box
        "ordering": true,   // enable sorting
        "info": true       // show “Showing X of Y entries”
    });
});
</script>

</html>