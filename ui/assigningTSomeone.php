<html>

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style/assigningTsomeone.css">
</head>

<body>
    <div class="add">

        <button type="button" class="btn btn-primary" id="AssignBtn">AssignTask</button>
    </div>

    <div>
        <div class="modal" tabindex="-1" id="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Modal body text goes here.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        let assign = document.getElementById("AssignBtn");
        let modal = new bootstrap.Modal(document.getElementById("modal"));
        assign.onclick = function () {
            modal.show();
        }
    </script>
</body>

</html>