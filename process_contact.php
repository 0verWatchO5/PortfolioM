<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "portfolio_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process contact form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $date = date("Y-m-d H:i:s");

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        header("Location: index.php?contact_error=All fields are required#contact");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?contact_error=Invalid email format#contact");
        exit();
    }

    // Insert into database
    $sql = "INSERT INTO contact_messages (name, email, subject, message, date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $subject, $message, $date);

    if ($stmt->execute()) {
        // Send email notification
        $to = "mayureshchaubal57@gmail.com";
        $email_subject = "New Contact Form Submission: " . $subject;
        $email_body = "You have received a new message from your website contact form.\n\n";
        $email_body .= "Name: " . $name . "\n";
        $email_body .= "Email: " . $email . "\n";
        $email_body .= "Subject: " . $subject . "\n";
        $email_body .= "Message:\n" . $message . "\n";
        
        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        
        mail($to, $email_subject, $email_body, $headers);
        
        header("Location: index.php?contact_success=Message sent successfully#contact");
    } else {
        header("Location: index.php?contact_error=Error sending message#contact");
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>