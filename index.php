<!DOCTYPE html>
<html lang="en" class="light-mode">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mayuresh | Portfolio</title>
    <link rel="script" href="script.js">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
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
    
    // Fetch blog posts
    $sql = "SELECT * FROM blog_posts ORDER BY date DESC";
    $result = $conn->query($sql);
    $blogPosts = [];
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $blogPosts[] = $row;
        }
    }
    ?>

    <!-- Navigation -->
    <header>
        <div class="logo">
            <h1>Mayur<span>esh</span></h1>
        </div>
        <nav>
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#skills">Skills</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#blog">Blog</a></li>
                <li><a href="#contact">Contact</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="admin.php" class="btn-admin">Admin Panel</a></li>
                    <li><a href="admin.php?logout=1" class="btn-logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="admin.php" class="btn-login">Admin</a></li>
                <?php endif; ?>
                <li><button id="theme-toggle" class="theme-toggle">
                    <i class="fas fa-moon"></i>
                    <i class="fas fa-sun"></i>
                </button></li>
            </ul>
        </nav>
        <div class="nav-controls">
    <div class="menu-toggle">
        <i class="fas fa-bars"></i>
    </div>
    <button id="theme-toggle-mobile" class="theme-toggle">
        <i class="fas fa-moon"></i>
        <i class="fas fa-sun"></i>
    </button>
</div>

    </header>

    <!-- Home Section -->
    <section id="home" class="section">
        <div class="container">
            <div class="home-content">
                <h1>MAYURESH CHAUBAL</h1>
                <h2>Protecting Digital Assets & Securing Networks</h2>
                <p>Specialized in Penetration Testing, Vulnerability Assessment, and Information Security</p>
                <a href="#contact" class="btn">Get In Touch</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section">
        <div class="container">
            <h2 class="section-title">About Me</h2>
            <div class="about-content">
                <div class="about-img">
                    <img src="RP2.jpg" alt="Profile Picture">
                </div>
                <div class="about-text">
                    <h3>Mayuresh Chaubal</h3>
                    <p>Cybersecurity student passionate about penetration testing, threat analysis, 
                        and ethical hacking. Hands-on experience with vulnerability assessments, 
                        security monitoring, and CTF challenges. Skilled in identifying and mitigating 
                        cyber threats through real-world simulations and proactive defense strategies.</p>
                    <div class="about-info">
                        <div class="info-item">
                            <span>Experience:</span>
                            <p>3 Months</p>
                        </div>
                        <div class="info-item">
                            <span>Specialization:</span>
                            <p>Penetration Testing, Information Security</p>
                        </div>
                        <div class="info-item">
                            <span>Email:</span>
                            <p>mayureshchaubal57@gmail.com</p>
                        </div>
                        <div class="info-item">
                            <span>Location:</span>
                            <p>Kandivali, Mumbai</p>
                        </div>
                    </div>
                    <a href="Mayuresh_Chaubal_Resume.pdf" class="btn" download>Download CV</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="section">
        <div class="container">
            <h2 class="section-title">My Skills</h2>
            <div class="skills-content">
                <div class="skill-category">
                    <h3>Technical Skills</h3>
                    <div class="skill-item">
                        <div class="skill-name">Network Security</div>
                        <div class="skill-bar">
                            <div class="skill-level" style="width: 95%"></div>
                        </div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Penetration Testing</div>
                        <div class="skill-bar">
                            <div class="skill-level" style="width: 90%"></div>
                        </div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Vulnerability Assessment</div>
                        <div class="skill-bar">
                            <div class="skill-level" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Security Architecture</div>
                        <div class="skill-bar">
                            <div class="skill-level" style="width: 80%"></div>
                        </div>
                    </div>
                </div>
                <div class="skill-category">
                    <h3>Tools & Technologies</h3>
                    <div class="skill-item">
                        <div class="skill-name">Kali Linux</div>
                        <div class="skill-bar">
                            <div class="skill-level" style="width: 95%"></div>
                        </div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Wireshark</div>
                        <div class="skill-bar">
                            <div class="skill-level" style="width: 90%"></div>
                        </div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Metasploit</div>
                        <div class="skill-bar">
                            <div class="skill-level" style="width: 85%"></div>
                        </div>
                    </div>
                    <div class="skill-item">
                        <div class="skill-name">Nmap</div>
                        <div class="skill-bar">
                            <div class="skill-level" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
                <div class="skill-category">
                    <h3>Certifications</h3>
                    <ul class="certification-list">
                        <li><i class="fas fa-certificate"></i> Vulnerability Assessment & Penetration Testing (VAPT)</li>
                        <li><i class="fas fa-certificate"></i> Certified Ethical Hacker (CEH)</li>
                        <!-- <li><i class="fas fa-certificate"></i> CompTIA Security+</li>
                        <li><i class="fas fa-certificate"></i> Offensive Security Certified Professional (OSCP)</li> -->
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="section">
        <div class="container">
            <h2 class="section-title">Recent Projects</h2>
            <div class="projects-grid">
                <div class="project-card">
                    <div class="project-img">
                        <img src="Project1.png" alt="Project 1">
                    </div>
                    <div class="project-info">
                    <h3>Testing on Metasploitable VM</h3>
                        <p>VMPerformed a penetration test on Metasploitable, identifying andexploiting 
                            vulnerabilities using Nmap, Metasploit, and other tools.Conducted reconnaissance, 
                            privilege escalation, and post-exploitation analysis in a controlled environment.</p>
                        <div class="project-tags">
                            <span>Vulnerability Assessment</span>
                            <span>Penetration Testing</span>
                        </div>
                    </div>
                </div>
                <div class="project-card">
                    <div class="project-img">
                        <img src="Project2.png" alt="Project 2">
                    </div>
                    <div class="project-info">
                    <h3>Web App PenTest on OWASP Juice Shop</h3>
                        <p>Conducted a black box penetration test on OWASP 
                            Juice Shop, identifying and mitigating security flaws 
                            using Burp Suite & Wireshark.</p>
                        <div class="project-tags">
                            <span>Vulnerability Assessment</span>
                            <span>Penetration Testing</span>
                            <span>Web Application Security</span>
                        </div>
                    </div>
                </div>
                <div class="project-card">
                    <div class="project-img">
                        <img src="Project3.png" alt="Project 3">
                    </div>
                    <div class="project-info">
                    <h3>Web App PenTest on Acunetix</h3>
                        <p>Conducted a black box penetration test to evaluate the security posture of a web application.
                            Used Nmap, Burp Suite, and Wireshark for network analysis, identifying security gaps and 
                            recommending remediation strategies.</p>
                        <div class="project-tags">
                            <span>Vulnerability Assessment</span>
                            <span>Penetration Testing</span>
                            <span>Web Application Security</span>
                        </div>
                    </div>
                </div>
                <div class="project-card">
                    <div class="project-img">
                        <img src="project4.jpg" alt="Project 4">
                    </div>
                    <div class="project-info">
                        <h3>Coming Soon</h3>
                        <p>Coming Soon!</p>
                        <div class="project-tags">
                            <span>Security Training</span>
                            <!-- <span>Social Engineering</span> -->
                        </div>
                    </div>
                </div>
                <div class="project-card">
                    <div class="project-img">
                        <img src="project5.jpg" alt="Project 5">
                    </div>
                    <div class="project-info">
                        <h3>Coming Soon</h3>
                        <p>Coming Soon!</p>
                        <div class="project-tags">
                            <span>Coming Soon</span>
                            <!-- <span>Social Engineering</span> -->
                        </div>
                    </div>
                </div>
                <div class="project-card">
                    <div class="project-img">
                        <img src="project6.jpg" alt="Project 6">
                    </div>
                    <div class="project-info">
                        <h3>Coming Soon</h3>
                        <p>Coming Soon!</p>
                        <div class="project-tags">
                            <span>Coming Soon</span>
                            <!-- <span>Social Engineering</span> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Section -->
    <section id="blog" class="section">
        <div class="container">
            <h2 class="section-title">Blog</h2>
            <div class="blog-grid">
                <?php if (empty($blogPosts)): ?>
                    <p class="no-posts">No blog posts available yet.</p>
                <?php else: ?>
                    <?php foreach ($blogPosts as $post): ?>
                        <div class="blog-card">
                            <div class="blog-info">
                                <span class="blog-date"><?php echo date("F j, Y", strtotime($post['date'])); ?></span>
                                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                                <p><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 150) . '...')); ?></p>
                                <button class="read-more" data-id="<?php echo $post['id']; ?>">Read More</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Blog Post Modal -->
            <div id="blogModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <div id="modalContent"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="section">
        <div class="container">
            <h2 class="section-title">Contact Me</h2>
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h3>Location</h3>
                            <p>Kandivali, Mumbai</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h3>Email</h3>
                            <p>mayureshchaubal57@gmail.com</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h3>Phone</h3>
                            <p>+91 7021524797</p>
                        </div>
                    </div>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="contact-form">
                    <?php if (isset($_GET['contact_success'])): ?>
                        <div class="success-message"><?php echo htmlspecialchars($_GET['contact_success']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_GET['contact_error'])): ?>
                        <div class="error-message"><?php echo htmlspecialchars($_GET['contact_error']); ?></div>
                    <?php endif; ?>
                    <form action="process_contact.php" method="POST">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Your Name" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="subject" placeholder="Subject" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Your Message" required></textarea>
                        </div>
                        <button type="submit" class="btn">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <h2>Mayur<span>esh</span></h2>
                    <p>Protecting Digital Assets & Securing Networks</p>
                </div>
                <div class="footer-links">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#skills">Skills</a></li>
                        <li><a href="#projects">Projects</a></li>
                        <li><a href="#blog">Blog</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-social">
                    <h3>Connect With Me</h3>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Mayuresh Chaubal. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>