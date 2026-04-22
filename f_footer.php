<?php
// f_footer.php
?>
    <style>
        .footer-island {
            background: rgba(255, 255, 255, 0.7); 
            backdrop-filter: blur(30px) saturate(200%); 
            -webkit-backdrop-filter: blur(30px) saturate(200%);
            margin: 100px 5% 30px 5%;
            border-radius: 40px;
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            box-shadow: 
                0 40px 80px rgba(0,0,0,0.06),
                0 1px 1px rgba(255,255,255,1) inset;
            padding: 60px 40px;
            text-align: center;
            color: #64748b;
        }
        .footer-links a {
            color: #64748b;
            text-decoration: none;
            margin: 0 20px;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            transition: 0.4s cubic-bezier(0.19, 1, 0.22, 1);
            display: inline-block;
        }
        .footer-links a:hover {
            color: var(--primary, #01696f);
            transform: translateY(-3px);
        }
        .social-icons { display: flex; justify-content: center; gap: 20px; margin-bottom: 30px; }
        .social-icons a { 
            width: 45px; height: 45px; border-radius: 14px; background: #f1f5f9; 
            display: flex; align-items: center; justify-content: center; 
            color: #475569; transition: 0.3s; text-decoration: none;
        }
        .social-icons a:hover { background: var(--primary); color: white; transform: translateY(-5px); }
    </style>
    <footer class="footer-island">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div class="social-icons">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <h3 style="color: #0f172a; font-weight: 900; font-size: 28px; margin-bottom: 20px; letter-spacing: -1.5px;">
                <?php echo isset($site_name) ? htmlspecialchars($site_name) : 'MyTrip'; ?>
            </h3>
            <p style="margin-bottom: 40px; font-size: 16px; color: #64748b; max-width: 500px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                We are on a mission to redefine the way you experience the world. Join us for premium, handpicked adventures.
            </p>
            <div class="footer-links" style="margin-bottom: 40px;">
                <a href="index.php">Home</a>
                <a href="about.php">Our Story</a>
                <a href="blog.php">Travel Blog</a>
                <a href="budget_tool.php">Planner</a>
                <a href="support.php">Contact</a>
            </div>
            <div style="height: 1px; background: rgba(0,0,0,0.05); margin: 40px 0;"></div>
            <p style="font-size: 13px; font-weight: 600;">&copy; <?php echo date('Y'); ?> <span style="color: #0f172a;"><?php echo isset($site_name) ? htmlspecialchars($site_name) : 'MyTrip'; ?></span>. Crafted for dreamers.</p>
        </div>
    </footer>
</body>
</html>
