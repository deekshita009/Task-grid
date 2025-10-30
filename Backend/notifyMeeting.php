<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST['email'] ?? '';
    $title = $_POST['title'] ?? '';
    $datetime = $_POST['datetime'] ?? '';
    $staff = $_POST['staff'] ?? '';

    if (empty($email) || empty($title) || empty($datetime) || empty($staff)) {
        echo json_encode(["success"=>false,"message"=>"❌ Missing parameters"]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success"=>false,"message"=>"❌ Invalid email"]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'vijayavarshini070@gmail.com';
        $mail->Password = 'pjmz xgli wwbc yswu';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('vijayavarshini070@gmail.com', 'Meeting Reminder');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "📅 Meeting Notification - $title";
        $mail->Body = "
            <h2>Meeting Notification</h2>
            <p>Dear <b>$staff</b>,</p>
            <p>You have a scheduled meeting.</p>
            <ul>
                <li><b>Title:</b> $title</li>
                <li><b>Date & Time:</b> $datetime</li>
            </ul>
            <p>Please attend on time.</p>
            <hr><p>-- Head of the Department</p>
        ";
        $mail->AltBody = "Meeting: $title at $datetime with $staff";

        $mail->send();
        echo json_encode(["success"=>true,"message"=>"✅ Email sent successfully"]);

    } catch (Exception $e) {
        echo json_encode(["success"=>false,"message"=>"❌ Mailer Error: {$mail->ErrorInfo}"]);
    }

} else {
    echo json_encode(["success"=>false,"message"=>"❌ Invalid request"]);
}
?>
