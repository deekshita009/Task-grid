<?php
header("Content-Type: application/json");
$host = 'localhost';
$username = 'root';
$password = "";
$dbname = 'taskgrid';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Database Connection Failed']);
    exit();
}

$action=$_POST['action'] ?? '';

switch($action){
    case 'read':
        readUser($conn);
        break;
    }

function readUser($conn){
    $sql="SELECT principal_id ,schedule_date, start_time,end_time, event_title FROM Principal_Schedule";
    $result=$conn->query($sql);
    
    if (!$result) {
        echo json_encode(['success'=>false,'message'=>'Query failed: ' . $conn->error]);
        return;
    }
    
    $meeters=[];
    if($result->num_rows>0){
        while($row=$result->fetch_assoc()){
            $meeters[]=$row;
        }
        echo json_encode(['success'=>true,'meeters'=>$meeters]);
    }  else{
        // Add debugging info
        echo json_encode([
            'success'=>false,
            'message'=>'No users found',
            'debug' => [
                'query' => $sql,
                'rows' => $result->num_rows
            ]
        ]);
    }
    $conn->close();
}
        
?>