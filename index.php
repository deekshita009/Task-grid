<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOD') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$dept = $_SESSION['dept'];
?>

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

        .nav-tabs #dashboard-tab:hover,
        .nav-tabs #dashboard-tab:hover span,
        .nav-tabs #dashboard-tab:hover i {
            background-color: blue !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #dashboard-tab.active,
        .nav-tabs #dashboard-tab.active span,
        .nav-tabs #dashboard-tab.active i {
            background-color: blue !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #personal-tab:hover,
        .nav-tabs #personal-tab:hover span,
        .nav-tabs #personal-tab:hover i {
            background-color: green !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #personal-tab.active,
        .nav-tabs #personal-tab.active span,
        .nav-tabs #personal-tab.active i {
            background-color: green !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #assignedtome-tab:hover,
        .nav-tabs #assignedtome-tab:hover span,
        .nav-tabs #assignedtome-tab:hover i {
            background-color: violet !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #assignedtome-tab.active,
        .nav-tabs #assignedtome-tab.active span,
        .nav-tabs #assignedtome-tab.active i {
            background-color: violet !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #assigning-tab:hover,
        .nav-tabs #assigning-tab:hover span,
        .nav-tabs #assigning-tab:hover i {
            background-color: orange !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #assigning-tab.active,
        .nav-tabs #assigning-tab.active span,
        .nav-tabs #assigning-tab.active i {
            background-color: orange !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #meeting-tab:hover,
        .nav-tabs #meeting-tab:hover span,
        .nav-tabs #meeting-tab:hover i {
            background-color: #ea0e0eff !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #meeting-tab.active,
        .nav-tabs #meeting-tab.active span,
        .nav-tabs #meeting-tab.active i {
            background-color: #f01527ff !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #report-tab:hover,
        .nav-tabs #report-tab:hover span,
        .nav-tabs #report-tab:hover i {
            background-color: grey !important;
            color: white !important;
            letter-spacing: 0.5px;
        }

        .nav-tabs #report-tab.active,
        .nav-tabs #report-tab.active span,
        .nav-tabs #report-tab.active i {
            background-color: grey !important;
            color: white !important;
            letter-spacing: 0.5px;
        }
    </style>

</head>

<body>
    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="content">

        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>

        <!-- Topbar -->
        <?php include 'topbar.php'; ?>

        <!-- Breadcrumb -->
        <div class="breadcrumb-area custom-gradient">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page"></li>
                </ol>
            </nav>
        </div>

        <!-- Content Area -->
        <div class="container-fluid">
            <div class="custom-tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" href="#dashboard" role="tab"
                            aria-controls="dashboard" aria-selected="true">
                            <span class="hidden-xs-down" style="font-size: 0.9em; font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:blue;">
                                <i class="fas fa-chart-bar tab-icon"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab"
                            aria-controls="personal" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:green;">
                                <i class="fas fa-list-check tab-icon"></i> Personal ToDo
                            </span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="assignedtome-tab" data-bs-toggle="tab" href="#assignedtome" role="tab"
                            aria-controls="assignedtome" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:purple;">
                                <i class="fas fa-user-check tab-icon"></i> My Task
                            </span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="assigning-tab" data-bs-toggle="tab" href="#assigning" role="tab"
                            aria-controls="assigning" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:orange;">
                                <i class="fas fa-user-tag tab-icon"></i> Allocate Task
                            </span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="meeting-tab" data-bs-toggle="tab" href="#meeting" role="tab"
                            aria-controls="meeting" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:red;">
                                <i class="fas fa-handshake tab-icon"></i> Meeting
                            </span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="report-tab" data-bs-toggle="tab" href="#report" role="tab"
                            aria-controls="report" aria-selected="false">
                            <span class="hidden-xs-down" style="font-size: 0.9em;font-family: 'Poppins', 'Open Sans', sans-serif;
font-weight: 600;color:grey;">
                                <i class="fas fa-chart-line tab-icon"></i> Report and Analysis
                            </span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Dashboard -->
                    <div class="tab-pane fade show active" id="dashboard" role="tabpanel"
                        aria-labelledby="dashboard-tab">
                        <?php include "ui/dashboard.php"; ?>
                    </div>

                    <!-- Personal-->
                    <div class="tab-pane fade" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <?php include "ui/personal.php"; ?>
                    </div>

                    <!-- Assign by Me -->
                    <div class="tab-pane fade" id="assignedtome" role="tabpanel" aria-labelledby="assignedtome-tab">
                        <?php include "ui/assigntome.php"; ?>
                    </div>

                    <!-- Assign to Someone -->
                    <div class="tab-pane fade" id="assigning" role="tabpanel" aria-labelledby="assigning-tab">
                        <?php include "ui/assigningTSomeone.php"; ?>
                    </div>

                    <!-- Meeting -->
                    <div class="tab-pane fade" id="meeting" role="tabpanel" aria-labelledby="meeting-tab">
                        <?php include "ui/meeting.php"; ?>
                    </div>

                    <!-- Report -->
                    <div class="tab-pane fade" id="report" role="tabpanel" aria-labelledby="report-tab">
                        <?php include "ui/report.php"; ?>
                    </div>

                </div>
            </div>
        </div>


        <!-- Footer -->
        <?php include 'footer.php'; ?>
    </div>

    <script>
        const loaderContainer = document.getElementById('loaderContainer');

        function showLoader() {
            loaderContainer.classList.add('show');
        }

        function hideLoader() {
            loaderContainer.classList.remove('show');
        }

        //    automatic loader
        document.addEventListener('DOMContentLoaded', function () {
            const contentWrapper = document.getElementById('contentWrapper');
            let loadingTimeout;

            function hideLoaderLocal() {
                loaderContainer.classList.add('hide');
                if (contentWrapper) contentWrapper.classList.add('show');
            }

            function showError() {
                console.error('Page load took too long or encountered an error');
                // You can add custom error handling here
            }

            // Set a maximum loading time (10 seconds)
            loadingTimeout = setTimeout(showError, 10000);

            // Hide loader when everything is loaded
            window.onload = function () {
                clearTimeout(loadingTimeout);

                // Add a small delay to ensure smooth transition
                setTimeout(hideLoaderLocal, 500);
            };

            // Error handling
            window.onerror = function (msg, url, lineNo, columnNo, error) {
                clearTimeout(loadingTimeout);
                showError();
                return false;
            };
        });

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

        // Dynamic loading for Assign to Someone tab
        document.addEventListener('DOMContentLoaded', function () {
            const assigningTab = document.getElementById('assigning-tab');
            let contentLoaded = false;

            if (assigningTab) {
                assigningTab.addEventListener('shown.bs.tab', function () {
                    if (!contentLoaded) {
                        const contentDiv = document.getElementById('assigning-content');
                        contentDiv.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>';

                        fetch('ui/assigningTSomeone.php')
                            .then(response => response.text())
                            .then(data => {
                                contentDiv.innerHTML = data;
                                contentLoaded = true;
                            })
                            .catch(error => {
                                contentDiv.innerHTML = '<div class="alert alert-danger">Error loading content</div>';
                            });
                    }
                });
            }
        });
    </script>
</body>

</html>