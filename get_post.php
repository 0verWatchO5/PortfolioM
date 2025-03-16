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

// Get post by ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT * FROM blog_posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        
        // Format content with paragraphs
        $content = nl2br(htmlspecialchars($post['content']));
        
        // Return post data as JSON
        echo json_encode([
            'success' => true,
            'title' => htmlspecialchars($post['title']),
            'content' => $content,
            'date' => date("F j, Y", strtotime($post['date']))
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Post not found']);
    }
    
    $stmt->close();
    $conn->close();
    exit();
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
?>