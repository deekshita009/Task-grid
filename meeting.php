<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MIC</title>
    <link rel="icon" type="image/png" sizes="32x32" href="image/icons/mkce_s.png">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-5/bootstrap-5.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
            --footer-height: 60px;
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --dark-bg: #1a1c23;
            --light-bg: #f8f9fc;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* General Styles with Enhanced Typography */

        /* Content Area Styles */
        .content {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            transition: all 0.3s ease;
            min-height: 100vh;
        }

        /* Content Navigation */
        .content-nav {
            background: linear-gradient(45deg, #4e73df, #1cc88a);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .content-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
            overflow-x: auto;
        }

        .content-nav li a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .content-nav li a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar.collapsed+.content {
            margin-left: var(--sidebar-collapsed-width);
        }

        .breadcrumb-area {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }



        /* Table Styles */



        .gradient-header {
            --bs-table-bg: transparent;
            --bs-table-color: white;
            background: linear-gradient(135deg, #4CAF50, #2196F3) !important;

            text-align: center;
            font-size: 0.9em;


        }


        td {
            text-align: left;
            font-size: 0.9em;
            vertical-align: middle;
            /* For vertical alignment */
        }






        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            .sidebar.mobile-show {
                transform: translateX(0);
            }

            .topbar {
                left: 0 !important;
            }

            .mobile-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            .mobile-overlay.show {
                display: block;
            }

            .content {
                margin-left: 0 !important;
            }

            .brand-logo {
                display: block;
            }

            .user-profile {
                margin-left: 0;
            }

            .sidebar .logo {
                justify-content: center;
            }

            .sidebar .menu-item span,
            .sidebar .has-submenu::after {
                display: block !important;
            }

            body.sidebar-open {
                overflow: hidden;
            }

            .footer {
                left: 0 !important;
            }

            .content-nav ul {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .content-nav ul::-webkit-scrollbar {
                height: 4px;
            }

            .content-nav ul::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 2px;
            }
        }

        .container-fluid {
            padding: 20px;
        }


        /* loader */
        .loader-container {
            position: fixed;
            left: var(--sidebar-width);
            right: 0;
            top: var(--topbar-height);
            bottom: var(--footer-height);
            background: rgba(255, 255, 255, 0.95);
            display: flex;
            /* Changed from 'none' to show by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: left 0.3s ease;
        }

        .sidebar.collapsed+.content .loader-container {
            left: var(--sidebar-collapsed-width);
        }

        @media (max-width: 768px) {
            .loader-container {
                left: 0;
            }
        }

        /* Hide loader when done */
        .loader-container.hide {
            display: none;
        }

        /* Loader Animation */
        .loader {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid var(--primary-color);
            border-right: 5px solid var(--success-color);
            border-bottom: 5px solid var(--primary-color);
            border-left: 5px solid var(--success-color);
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .breadcrumb-area {
            background-image: linear-gradient(to top, #fff1eb 0%, #ace0f9 100%);
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin: 20px;
            padding: 15px 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .breadcrumb-item a:hover {
            color: #224abe;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>
    <?php include 'topbar.php'; ?>

    <div class="content">
        <div class="container-fluid">
            <!-- Removed top tab navigation -->
            <!-- Meetings Section -->
            <div id="meetings-section" class="mt-4">
                <div class="d-flex justify-content-center mb-4 gap-3">
                    <button class="btn btn-success d-flex align-items-center gap-2" data-bs-toggle="modal"
                        data-bs-target="#addMeetingModal">
                        <i class="fas fa-calendar-plus"></i> Add Meeting
                    </button>
                    <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                        data-bs-target="#requestMeetingModal">
                        <i class="fas fa-user-tie"></i> Request Meeting
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle" id="meetingsTable">
                        <thead class="table-primary">
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic rows will be added here -->
                        </tbody>
                    </table>
                </div>
                <!-- Add Meeting Modal -->
                <div class="modal fade" id="addMeetingModal" tabindex="-1" aria-labelledby="addMeetingModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addMeetingModalLabel">Schedule Staff Meeting</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
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
                                        <select class="form-control" name="staff" required>
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
                                        <select class="form-control" name="mode">
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
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Schedule Meeting</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Request Meeting Modal -->
                <div class="modal fade" id="requestMeetingModal" tabindex="-1"
                    aria-labelledby="requestMeetingModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="requestMeetingModalLabel">Request Principal Meeting</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
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
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // Add Meeting
                document.getElementById('addMeetingForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const form = e.target;
                    const title = form.title.value;
                    const staff = form.staff.value;
                    const datetime = form.datetime.value;
                    // Add to table
                    const table = document.getElementById('meetingsTable').getElementsByTagName('tbody')[0];
                    const row = table.insertRow();
                    row.insertCell(0).innerText = title;
                    row.insertCell(1).innerText = 'Staff (' + staff + ')';
                    row.insertCell(2).innerText = new Date(datetime).toLocaleString();
                    row.insertCell(3).innerText = 'Scheduled';
                    // Notify Staffs button
                    const actionCell = row.insertCell(4);
                    const notifyBtn = document.createElement('button');
                    notifyBtn.className = 'btn btn-warning btn-sm d-flex align-items-center gap-1';
                    notifyBtn.innerHTML = '<i class="fas fa-bell"></i> Notify Staffs';
                    notifyBtn.onclick = function () { alert('Staffs notified!'); };
                    actionCell.appendChild(notifyBtn);
                    form.reset();
                    var modal = bootstrap.Modal.getInstance(do cument.getElementById('addMeetingModal'));
                    modal.hide();
                });
                // Request Meeting
                document.getElementById('requestMeetingForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const form = e.target;
                    const title = form.title.value;
                    const datetime = form.datetime.value;
                    // Add to table
                    const table = document.getElementById('meetingsTable').getElementsByTagName('tbody')[0];
                    const row = table.insertRow();
                    row.insertCell(0).innerText = title;
                    row.insertCell(1).innerText = 'Principal';
                    row.insertCell(2).innerText = new Date(datetime).toLocaleString();
                    // Status cell with approve/reject icons
                    const statusCell = row.insertCell(3);
                    statusCell.innerHTML = 'Pending ' +
                        '<button class="btn btn-success btn-sm ms-2" title="Approve"><i class="fas fa-check-circle"></i></button>' +
                        '<button class="btn btn-danger btn-sm ms-1" title="Reject"><i class="fas fa-times-circle"></i></button>';
                    // Action cell empty for request meeting
                    row.insertCell(4).innerHTML = '';
                    // Approve/Reject logic
                    const approveBtn = statusCell.querySelector('.btn-success');
                    const rejectBtn = statusCell.querySelector('.btn-danger');
                    approveBtn.onclick = function () {
                        statusCell.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Approved</span>';
                    };
                    rejectBtn.onclick = function () {
                        statusCell.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> Rejected</span>';
                    };
                    form.reset();
                    var modal = bootstrap.Modal.getInstance(document.getElementById('requestMeetingModal'));
                    modal.hide();
                });
                // Loader and sidebar logic
                function showError() {
                    console.error('Page load took too long or encountered an error');
                }
                let loadingTimeout = setTimeout(showError, 10000);
                function hideLoader() {
                    const loaderContainer = document.getElementById('loaderContainer');
                    if (loaderContainer) loaderContainer.classList.add('hide');
                }
                window.onload = function () {
                    clearTimeout(loadingTimeout);
                    setTimeout(hideLoader, 500);
                };
                window.onerror = function (msg, url, lineNo, columnNo, error) {
                    clearTimeout(loadingTimeout);
                    showError();
                    return false;
                };
                const hamburger = document.getElementById('hamburger');
                const sidebar = document.getElementById('sidebar');
                const body = document.body;
                const mobileOverlay = document.getElementById('mobileOverlay');
                function toggleSidebar() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.toggle('mobile-show');
                        mobileOverlay.classList.toggle('show');
                        body.classList.toggle('sidebar-open');
                    } else {
                        sidebar.classList.toggle('collapsed');
                    }
                }
                if (hamburger) hamburger.addEventListener('click', toggleSidebar);
                if (mobileOverlay) mobileOverlay.addEventListener('click', toggleSidebar);
                const userMenu = document.getElementById('userMenu');
                if (userMenu) {
                    const dropdownMenu = userMenu.querySelector('.dropdown-menu');
                    userMenu.addEventListener('click', (e) => {
                        e.stopPropagation();
                        dropdownMenu.classList.toggle('show');
                    });
                    document.addEventListener('click', () => {
                        dropdownMenu.classList.remove('show');
                    });
                }
                const menuItems = document.querySelectorAll('.has-submenu');
                menuItems.forEach(item => {
                    item.addEventListener('click', () => {
                        const submenu = item.nextElementSibling;
                        item.classList.toggle('active');
                        submenu.classList.toggle('active');
                    });
                });
                window.addEventListener('resize', () => {
                    if (window.innerWidth <= 768) {
                        if (sidebar) sidebar.classList.remove('collapsed');
                        if (sidebar) sidebar.classList.remove('mobile-show');
                        if (mobileOverlay) mobileOverlay.classList.remove('show');
                        body.classList.remove('sidebar-open');
                    } else {
                        if (sidebar) sidebar.style.transform = '';
                        if (mobileOverlay) mobileOverlay.classList.remove('show');
                        body.classList.remove('sidebar-open');
                    }
                });
            </script>

            <script>
                function showError() {
                    console.error('Page load took too long or encountered an error');
                    // You can add custom error handling here
                }

                // Set a maximum loading time (10 seconds)
                let loadingTimeout = setTimeout(showError, 10000);

                // Hide loader when everything is loaded
                window.onload = function () {
                    clearTimeout(loadingTimeout);
                    // Add a small delay to ensure smooth transition
                    setTimeout(hideLoader, 500);
                };

                // Error handling
                window.onerror = function (msg, url, lineNo, columnNo, error) {
                    clearTimeout(loadingTimeout);
                    showError();
                    return false;
                };

                // Toggle Sidebar
                const hamburger = document.getElementById('hamburger');
                const sidebar = document.getElementById('sidebar');
                const body = document.body;
                const mobileOverlay = document.getElementById('mobileOverlay');

                function toggleSidebar() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.toggle('mobile-show');
                        mobileOverlay.classList.toggle('show');
                        body.classList.toggle('sidebar-open');
                    } else {
                        sidebar.classList.toggle('collapsed');
                    }
                }
                hamburger.addEventListener('click', toggleSidebar);
                mobileOverlay.addEventListener('click', toggleSidebar);
                // Toggle User Menu
                const userMenu = document.getElementById('userMenu');
                const dropdownMenu = userMenu.querySelector('.dropdown-menu');

                userMenu.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', () => {
                    dropdownMenu.classList.remove('show');
                });

                // Toggle Submenu
                const menuItems = document.querySelectorAll('.has-submenu');
                menuItems.forEach(item => {
                    item.addEventListener('click', () => {
                        const submenu = item.nextElementSibling;
                        item.classList.toggle('active');
                        submenu.classList.toggle('active');
                    });
                });

                // Handle responsive behavior
                window.addEventListener('resize', () => {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('collapsed');
                        sidebar.classList.remove('mobile-show');
                        mobileOverlay.classList.remove('show');
                        body.classList.remove('sidebar-open');
                    } else {
                        sidebar.style.transform = '';
                        mobileOverlay.classList.remove('show');
                        body.classList.remove('sidebar-open');
                    }
                });
            </script>

            <script>
                // Add Meeting
                document.getElementById('addMeetingForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const form = e.target;
                    const title = form.title.value;
                    const staff = form.staff.value;
                    const datetime = form.datetime.value;
                    // Add to table
                    const table = document.getElementById('meetingsTable').getElementsByTagName('tbody')[0];
                    const row = table.insertRow();
                    row.insertCell(0).innerText = title;
                    row.insertCell(1).innerText = 'Staff (' + staff + ')';
                    row.insertCell(2).innerText = new Date(datetime).toLocaleString();
                    row.insertCell(3).innerText = 'Scheduled';
                    // Notify Staffs button
                    const actionCell = row.insertCell(4);
                    const notifyBtn = document.createElement('button');
                    notifyBtn.className = 'btn btn-warning btn-sm d-flex align-items-center gap-1';
                    notifyBtn.innerHTML = '<i class="fas fa-bell"></i> Notify Staffs';
                    notifyBtn.onclick = function () { alert('Staffs notified!'); };
                    actionCell.appendChild(notifyBtn);
                    form.reset();
                    var modal = bootstrap.Modal.getInstance(document.getElementById('addMeetingModal'));
                    modal.hide();
                });


                // ...existing code...
                document.getElementById('requestMeetingForm').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const form = e.target;
                    const title = form.title.value;
                    const datetime = form.datetime.value;
                    // Add to table
                    const table = document.getElementById('meetingsTable').getElementsByTagName('tbody')[0];
                    const row = table.insertRow();
                    row.insertCell(0).innerText = title;
                    row.insertCell(1).innerText = 'Principal';
                    row.insertCell(2).innerText = new Date(datetime).toLocaleString();
                    // Status cell: only Pending
                    const statusCell = row.insertCell(3);
                    statusCell.innerHTML = '<span class="text-warning"><i class="fas fa-hourglass-half"></i> Pending</span>';
                    // Action cell: waiting for approval
                    const actionCell = row.insertCell(4);
                    actionCell.innerHTML = '<span class="text-secondary">Waiting for approval</span>';
                    form.reset();
                    var modal = bootstrap.Modal.getInstance(document.getElementById('requestMeetingModal'));
                    modal.hide();
                });
                // ...existing code...

            </script>


</body>

</html>