<?php 
$servername = "localhost";
$username = "root";
$password = "";
$database = "college_erp";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOD') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];
$dept = $_SESSION['dept'];
?>


<html>

<head>
    <link rel="stylesheet" href="style/dashboard.css">
    
</head>

<body>

<div style="display: flex; gap: 20px;">
    <p class="para" id="box1" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <i class="fal fa-solid fa-check fa-2xl" style="color: #f7f7f7; margin-bottom:20px;"></i> 
        <span class="text-beat" style="color: #f1f2f3; font-weight: bold; font-size: 1.5rem;">Completed Task</span>
        <span id="completed-count" style="color: #f1f2f3; font-size: 2rem; font-weight: bold;">0</span>
    </p>
    <p class="para" style="background-color:#FF941A; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <i class="fa-solid fa-hourglass-half fa-2xl" style="color: #f1f2f3; margin-bottom:20px;"></i>
        <span class="text-beat" style="color: #f1f2f3; font-weight: bold; font-size: 1.5rem;">Task Pending</span>
        <span id="pending-count" style="color: #f1f2f3; font-size: 2rem; font-weight: bold;">0</span>
    </p>
    <p class="para" style="background-color:#E02C47; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
        <i class="fa-solid fa-credit-card fa-2xl" style="color: #f2f2f2; margin-bottom:20px;"></i>
        <span class="text-beat" style="color: #f1f2f3; font-weight: bold; font-size: 1.5rem;">Demerit</span>
        <span id="demerit-count" style="color: #f1f2f3; font-size: 2rem; font-weight: bold;">0</span>
    </p>
</div>

<script>
fetch('db/dashboard_db/api.php')
    .then(response => response.json())
    .then(data => {
        if (!data.error) {
            document.getElementById('completed-count').textContent = data.completed;
            document.getElementById('pending-count').textContent = data.pending;
            document.getElementById('demerit-count').textContent = data.demerit;
        } else {
            console.error(data.error);
        }
    })
    .catch(err => console.error('Fetch error:', err));
</script>

</body>
</html>