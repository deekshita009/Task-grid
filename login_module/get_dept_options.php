<?php
include("../shared/db_connect.php");

if (isset($_POST['faculty_id'])) {
    $faculty_id = $_POST['faculty_id'];

    $stmt = $conn->prepare("SELECT dept, ddept FROM Users WHERE user_id = ? AND role = 'FACULTY'");
    $stmt->bind_param("s", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = ['status' => 404, 'data' => []];

    if ($result->num_rows > 0) {
        $res_data = [];
        while ($row = $result->fetch_assoc()) {
            if ($row['dept']) $res_data[] = $row['dept'];
            if ($row['ddept']) $res_data[] = $row['ddept'];
        }
        $response = ['status' => 200, 'data' => array_unique($res_data)];
    }

    echo json_encode($response);
}
?>
