<?php
session_start();
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'] === true;

// Database connection
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "portfolio_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle admin login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin'] = true;
            // No redirect, just set the session
        } else {
            $loginError = "Invalid username or password";
        }
    } else {
        $loginError = "Invalid username or password";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Handle blog post submission
if (isset($_POST['submit_post']) && $isAdmin) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = date("Y-m-d H:i:s");
    
    $sql = "INSERT INTO blog_posts (title, content, date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $title, $content, $date);
    
    if ($stmt->execute()) {
        $postSuccess = "Post published successfully!";
    } else {
        $postError = "Error: " . $stmt->error;
    }
}

// Handle post editing
if (isset($_POST['edit_post']) && $isAdmin) {
    $post_id = $_POST['post_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    $sql = "UPDATE blog_posts SET title = ?, content = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $content, $post_id);
    
    if ($stmt->execute()) {
        $editSuccess = "Post updated successfully!";
    } else {
        $editError = "Error updating post: " . $stmt->error;
    }
}

// Get post for editing
$editingPost = null;
if (isset($_GET['edit']) && $isAdmin) {
    $post_id = $_GET['edit'];
    
    $sql = "SELECT * FROM blog_posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $editingPost = $result->fetch_assoc();
    }
}

// Handle post deletion
if (isset($_GET['delete']) && $isAdmin) {
    $id = $_GET['delete'];
    
    $sql = "DELETE FROM blog_posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $deleteSuccess = "Post deleted successfully";
    } else {
        $deleteError = "Error deleting post";
    }
}

// Fetch blog posts for admin panel
$sql = "SELECT * FROM blog_posts ORDER BY date DESC";
$result = $conn->query($sql);
$blogPosts = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $blogPosts[] = $row;
    }
}

// Fetch contact messages for admin panel
$sql = "SELECT * FROM contact_messages ORDER BY date DESC";
$result = $conn->query($sql);
$contactMessages = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $contactMessages[] = $row;
    }
}

// Handle message viewing
$viewingMessage = null;
if (isset($_GET['view_message']) && $isAdmin) {
    $message_id = $_GET['view_message'];
    
    $sql = "SELECT * FROM contact_messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $viewingMessage = $result->fetch_assoc();
        
        // Mark message as read
        if ($viewingMessage['is_read'] == 0) {
            $sql = "UPDATE contact_messages SET is_read = 1 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $message_id);
            $stmt->execute();
        }
    }
}

// Handle message deletion
if (isset($_GET['delete_message']) && $isAdmin) {
    $id = $_GET['delete_message'];
    
    $sql = "DELETE FROM contact_messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $deleteMessageSuccess = "Message deleted successfully";
    } else {
        $deleteMessageError = "Error deleting message";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="light-mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Cybersecurity Portfolio</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="admin-header">
        <div class="logo">
            <h1>Mayur<span>esh</span></h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Back to Website</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="?logout=1" class="btn-logout">Logout</a></li>
                <?php endif; ?>
                <li><button id="theme-toggle" class="theme-toggle">
                    <i class="fas fa-moon"></i>
                    <i class="fas fa-sun"></i>
                </button></li>
            </ul>
        </nav>
        <button id="theme-toggle-mobile" class="theme-toggle">
            <i class="fas fa-moon"></i>
            <i class="fas fa-sun"></i>
        </button>
    </header>

    <div class="admin-container">
        <?php if (!$isAdmin): ?>
            <!-- Login Form -->
            <section class="admin-login-section">
                <div class="container">
                    <h2 class="section-title">Admin Login</h2>
                    <div class="admin-login">
                        <form action="" method="POST">
                            <?php if (isset($loginError)): ?>
                                <div class="error-message"><?php echo $loginError; ?></div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" id="password" name="password" required>
                            </div>
                            <button type="submit" name="login" class="btn">Login</button>
                        </form>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <!-- Admin Dashboard -->
            <section class="admin-dashboard-section">
                <div class="container">
                    <h2 class="section-title">Admin Dashboard</h2>
                    
                    <!-- Success/Error Messages -->
                    <?php if (isset($postSuccess)): ?>
                        <div class="success-message"><?php echo $postSuccess; ?></div>
                    <?php endif; ?>
                    <?php if (isset($postError)): ?>
                        <div class="error-message"><?php echo $postError; ?></div>
                    <?php endif; ?>
                    <?php if (isset($editSuccess)): ?>
                        <div class="success-message"><?php echo $editSuccess; ?></div>
                    <?php endif; ?>
                    <?php if (isset($editError)): ?>
                        <div class="error-message"><?php echo $editError; ?></div>
                    <?php endif; ?>
                    <?php if (isset($deleteSuccess)): ?>
                        <div class="success-message"><?php echo $deleteSuccess; ?></div>
                    <?php endif; ?>
                    <?php if (isset($deleteError)): ?>
                        <div class="error-message"><?php echo $deleteError; ?></div>
                    <?php endif; ?>
                    <?php if (isset($deleteMessageSuccess)): ?>
                        <div class="success-message"><?php echo $deleteMessageSuccess; ?></div>
                    <?php endif; ?>
                    <?php if (isset($deleteMessageError)): ?>
                        <div class="error-message"><?php echo $deleteMessageError; ?></div>
                    <?php endif; ?>
                    
                    <!-- Admin Tabs -->
                    <div class="admin-tabs">
                        <button class="tab-btn <?php echo (!isset($_GET['view_message']) && !isset($_GET['edit'])) ? 'active' : ''; ?>" data-tab="blog-tab">Blog Posts</button>
                        <button class="tab-btn <?php echo isset($_GET['view_message']) ? 'active' : ''; ?>" data-tab="messages-tab">Contact Messages</button>
                        <?php if (isset($_GET['edit'])): ?>
                            <button class="tab-btn active" data-tab="edit-post-tab">Edit Post</button>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Blog Posts Tab -->
                    <div class="tab-content <?php echo (!isset($_GET['view_message']) && !isset($_GET['edit'])) ? 'active' : ''; ?>" id="blog-tab">
                        <div class="admin-section">
                            <h3>Add New Blog Post</h3>
                            <form action="" method="POST" class="admin-form">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" id="title" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea id="content" name="content" rows="10" required></textarea>
                                </div>
                                <button type="submit" name="submit_post" class="btn">Publish Post</button>
                            </form>
                        </div>
                        
                        <div class="admin-section">
                            <h3>Manage Blog Posts</h3>
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($blogPosts)): ?>
                                        <tr>
                                            <td colspan="3">No posts available</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($blogPosts as $post): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                                <td><?php echo date("F j, Y", strtotime($post['date'])); ?></td>
                                                <td>
                                                    <a href="?edit=<?php echo $post['id']; ?>" class="btn-edit">Edit</a>
                                                    <a href="?delete=<?php echo $post['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Edit Post Tab -->
                    <?php if (isset($_GET['edit']) && $editingPost): ?>
                        <div class="tab-content active" id="edit-post-tab">
                            <div class="admin-section">
                                <h3>Edit Blog Post</h3>
                                <form action="" method="POST" class="admin-form">
                                    <input type="hidden" name="post_id" value="<?php echo $editingPost['id']; ?>">
                                    <div class="form-group">
                                        <label for="edit-title">Title</label>
                                        <input type="text" id="edit-title" name="title" value="<?php echo htmlspecialchars($editingPost['title']); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit-content">Content</label>
                                        <textarea id="edit-content" name="content" rows="15" required><?php echo htmlspecialchars($editingPost['content']); ?></textarea>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" name="edit_post" class="btn">Update Post</button>
                                        <a href="admin.php" class="btn btn-outline">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Contact Messages Tab -->
                    <div class="tab-content <?php echo isset($_GET['view_message']) ? 'active' : ''; ?>" id="messages-tab">
                        <?php if (isset($_GET['view_message']) && $viewingMessage): ?>
                            <!-- View Message -->
                            <div class="admin-section">
                                <h3>Message Details</h3>
                                <div class="message-details">
                                    <div class="message-meta">
                                        <div class="message-meta-item">
                                            <span class="message-meta-label">From</span>
                                            <span><?php echo htmlspecialchars($viewingMessage['name']); ?></span>
                                        </div>
                                        <div class="message-meta-item">
                                            <span class="message-meta-label">Email</span>
                                            <span><?php echo htmlspecialchars($viewingMessage['email']); ?></span>
                                        </div>
                                        <div class="message-meta-item">
                                            <span class="message-meta-label">Subject</span>
                                            <span><?php echo htmlspecialchars($viewingMessage['subject']); ?></span>
                                        </div>
                                        <div class="message-meta-item">
                                            <span class="message-meta-label">Date</span>
                                            <span><?php echo date("F j, Y, g:i a", strtotime($viewingMessage['date'])); ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="message-content">
                                        <?php echo nl2br(htmlspecialchars($viewingMessage['message'])); ?>
                                    </div>
                                    
                                    <div class="message-actions">
                                        <a href="mailto:<?php echo htmlspecialchars($viewingMessage['email']); ?>?subject=Re: <?php echo htmlspecialchars($viewingMessage['subject']); ?>" class="btn btn-reply">Reply</a>
                                        <a href="?delete_message=<?php echo $viewingMessage['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>
                                        <a href="admin.php" class="btn btn-outline">Back to Messages</a>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Message List -->
                            <div class="admin-section">
                                <h3>Contact Messages</h3>
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Subject</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($contactMessages)): ?>
                                            <tr>
                                                <td colspan="5">No messages available</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($contactMessages as $message): ?>
                                                <tr class="<?php echo $message['is_read'] ? '' : 'unread'; ?>">
                                                    <td><?php echo htmlspecialchars($message['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($message['email']); ?></td>
                                                    <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                                    <td><?php echo date("F j, Y", strtotime($message['date'])); ?></td>
                                                    <td>
                                                        <a href="?view_message=<?php echo $message['id']; ?>" class="btn-view">View</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </div>

    <footer class="admin-footer">
        <div class="container">
            <p>&copy; 2025 Mayuresh. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Tab functionality
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active class from all buttons and contents
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked button and corresponding content
                btn.classList.add('active');
                const tabId = btn.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
        
        // Theme toggle
        const themeToggle = document.getElementById('theme-toggle');
        const themeToggleMobile = document.getElementById('theme-toggle-mobile');
        const html = document.documentElement;
        
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            html.className = savedTheme;
        }
        
        function toggleTheme() {
            if (html.classList.contains('light-mode')) {
                html.classList.remove('light-mode');
                html.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark-mode');
            } else {
                html.classList.remove('dark-mode');
                html.classList.add('light-mode');
                localStorage.setItem('theme', 'light-mode');
            }
        }
        
        themeToggle.addEventListener('click', toggleTheme);
        if (themeToggleMobile) {
            themeToggleMobile.addEventListener('click', toggleTheme);
        }
    </script>
</body>
</html>