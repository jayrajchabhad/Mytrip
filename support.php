<?php
session_start();
include 'db.php';

$success_msg = '';
$error_msg = '';

if(isset($_POST['submit_support'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
    
    if(mysqli_query($conn, $sql)) {
        $success_msg = "Your message has been sent successfully. We will get back to you soon!";
    } else {
        $error_msg = "Oops! Something went wrong. Please try again later.";
    }
}

include 'f_header.php';
?>

<style>
    :root {
        --primary: #01696f;
        --soft-bg: #f8fafc;
        --text-dark: #0f172a;
        --text-light: #64748b;
    }
    body { background-color: white; }
    
    .support-hero {
        position: relative;
        height: 50vh;
        min-height: 400px;
        margin: 20px 5%;
        border-radius: 40px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: #000;
    }
    .support-hero img {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0.6;
        z-index: 1;
    }
    .hero-text {
        position: relative;
        z-index: 2;
        padding: 0 30px;
        color: white;
    }
    .hero-text h1 { font-size: clamp(40px, 6vw, 64px); font-weight: 900; letter-spacing: -2px; margin-bottom: 20px; }
    .hero-text p { font-size: clamp(16px, 1.5vw, 20px); opacity: 0.9; max-width: 600px; margin: 0 auto; font-weight: 600; }

    .support-container {
        display: grid;
        grid-template-columns: 1.1fr 1fr;
        gap: 80px;
        max-width: 1300px;
        margin: 100px auto;
        padding: 0 5%;
    }

    /* FAQ Section */
    .faq-section h2 { font-size: 36px; font-weight: 900; color: var(--text-dark); margin-bottom: 40px; letter-spacing: -1.5px; }
    .faq-item {
        background: #f8fafc;
        border: 1.5px solid #f1f5f9;
        border-radius: 24px;
        margin-bottom: 20px;
        overflow: hidden;
        transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .faq-item:hover { border-color: var(--primary); background: white; box-shadow: 0 15px 30px rgba(0,0,0,0.04); }
    .faq-question {
        padding: 25px 30px;
        font-weight: 800;
        color: var(--text-dark);
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 17px;
    }
    .faq-question i { 
        width: 32px; height: 32px; border-radius: 10px; background: white;
        display: flex; align-items: center; justify-content: center;
        color: var(--primary); transition: 0.3s; font-size: 14px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .faq-answer {
        padding: 0 30px 30px;
        color: var(--text-light);
        line-height: 1.8;
        display: none;
        font-size: 16px;
    }
    .faq-item.active .faq-answer { display: block; }
    .faq-item.active .faq-question i { transform: rotate(180deg); background: var(--primary); color: white; }

    /* Contact Form */
    .contact-card {
        background: white;
        padding: 50px;
        border-radius: 40px;
        box-shadow: 0 40px 80px rgba(0,0,0,0.06);
        border: 1.5px solid #f1f5f9;
    }
    .contact-card h2 { font-size: 32px; font-weight: 900; color: var(--text-dark); margin-bottom: 15px; letter-spacing: -1.5px; }
    .contact-card p { color: var(--text-light); margin-bottom: 40px; font-weight: 600; }
    
    .form-group { margin-bottom: 25px; }
    .form-label { display: block; font-weight: 800; color: var(--text-dark); margin-bottom: 10px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-control {
        width: 100%;
        padding: 18px 20px;
        border: 2px solid #f8fafc;
        border-radius: 20px;
        font-family: inherit;
        font-size: 15px;
        font-weight: 600;
        transition: 0.3s;
        background: #f8fafc;
        box-sizing: border-box;
    }
    .form-control:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 4px rgba(1, 105, 111, 0.05); }
    
    .submit-btn {
        width: 100%;
        background: linear-gradient(135deg, var(--primary) 0%, #014f54 100%);
        color: white;
        padding: 20px;
        border: none;
        border-radius: 20px;
        font-size: 17px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 10px;
        box-shadow: 0 15px 30px rgba(1, 105, 111, 0.2);
    }
    .submit-btn:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(1, 105, 111, 0.3); }

    .alert { padding: 20px; border-radius: 20px; margin-bottom: 30px; font-weight: 700; font-size: 15px; border: none; }
    .alert-success { background: #eef7f2; color: #1e5c37; }
    .alert-error { background: #fef2f2; color: #991b1b; }

    @media (max-width: 1000px) {
        .support-container { grid-template-columns: 1fr; gap: 60px; }
        .support-hero { height: 40vh; }
    }
</style>

<div class="support-hero">
    <img src="https://images.unsplash.com/photo-1534536281715-e28d76689b4d?q=80&w=2000" alt="Support Background">
    <div class="hero-text">
        <h1>How can we help?</h1>
        <p>Our dedicated support team is here to ensure your journey is seamless and unforgettable.</p>
    </div>
</div>

<div class="support-container">
    <div class="faq-section">
        <h2>Frequently Asked Questions</h2>
        
        <div class="faq-item">
            <div class="faq-question">How do I cancel my booking? <i><i class="fas fa-chevron-down"></i></i></div>
            <div class="faq-answer">You can cancel your booking by logging into your account, going to "My Trips", and selecting the cancellation option. Please note that cancellation fees may apply based on the proximity to the travel date.</div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">Are flights included in the packages? <i><i class="fas fa-chevron-down"></i></i></div>
            <div class="faq-answer">Most of our premium packages include round-trip flights from major cities. Check the specific inclusions and travel mode on the package details page.</div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">What is your refund policy? <i><i class="fas fa-chevron-down"></i></i></div>
            <div class="faq-answer">Refunds are processed within 7-10 business days. A full refund is available if cancelled 30 days prior to departure. Partial refunds apply for later cancellations.</div>
        </div>
        
        <div class="faq-item">
            <div class="faq-question">Can I customize a package? <i><i class="fas fa-chevron-down"></i></i></div>
            <div class="faq-answer">Absolutely! We offer bespoke customizations for group bookings. Reach out via the form with your requirements and our curators will build a custom itinerary for you.</div>
        </div>
    </div>

    <div class="contact-form-section">
        <div class="contact-card">
            <h2>Send us a Message</h2>
            <p>Our experts typically respond within 4 business hours.</p>
            
            <?php if($success_msg): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success_msg; ?></div>
            <?php endif; ?>
            
            <?php if($error_msg): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="John Doe" value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="john@example.com" value="<?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Subject</label>
                    <select name="subject" class="form-control" required>
                        <option value="" disabled selected>Select a topic</option>
                        <option value="Booking Inquiry">Booking Inquiry</option>
                        <option value="Cancellation/Refund">Cancellation or Refund</option>
                        <option value="Custom Package">Custom Package</option>
                        <option value="Technical Issue">Technical Issue</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-control" rows="5" required placeholder="How can we help you?"></textarea>
                </div>
                
                <button type="submit" name="submit_support" class="submit-btn">Send Message</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('.faq-question').click(function(){
        $(this).parent('.faq-item').toggleClass('active');
        $(this).parent('.faq-item').siblings().removeClass('active');
    });
});
</script>

<?php include 'f_footer.php'; ?>
