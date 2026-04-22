<?php
session_start();
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

    .about-hero {
        position: relative;
        height: 70vh;
        min-height: 500px;
        margin: 20px 5%;
        border-radius: 40px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: #000;
    }
    .about-hero img {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0.55;
        z-index: 1;
    }
    .hero-text {
        position: relative;
        z-index: 2;
        max-width: 800px;
        padding: 0 30px;
        color: white;
    }
    .hero-text h1 {
        font-size: clamp(40px, 7vw, 72px);
        font-weight: 900;
        margin-bottom: 25px;
        letter-spacing: -3px;
        line-height: 1.1;
    }
    .hero-text p {
        font-size: clamp(16px, 2vw, 22px);
        opacity: 0.9;
        font-weight: 600;
        max-width: 650px;
        margin: 0 auto;
    }

    .about-container { max-width: 1300px; margin: 100px auto; padding: 0 5%; }

    .story-section {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 80px;
        align-items: center;
        margin-bottom: 120px;
    }
    .story-text h2 { 
        font-size: clamp(32px, 4vw, 48px); 
        font-weight: 900; 
        color: var(--text-dark); 
        margin-bottom: 30px; 
        line-height: 1.2; 
        letter-spacing: -1.5px;
    }
    .story-text p { font-size: 18px; color: var(--text-light); line-height: 1.8; margin-bottom: 25px; }
    
    .story-img {
        position: relative;
        border-radius: 40px;
        overflow: hidden;
        box-shadow: 0 40px 80px rgba(0,0,0,0.1);
    }
    .story-img img { width: 100%; display: block; transition: 0.5s; }
    .story-img:hover img { transform: scale(1.05); }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-bottom: 120px;
    }
    .stat-card {
        background: #f8fafc;
        padding: 60px 40px;
        border-radius: 40px;
        text-align: center;
        border: 1.5px solid #f1f5f9;
        transition: 0.3s;
    }
    .stat-card:hover { transform: translateY(-10px); background: white; border-color: var(--primary); box-shadow: 0 20px 40px rgba(1, 105, 111, 0.05); }
    .stat-card h3 { font-size: 56px; font-weight: 900; color: var(--primary); margin: 0 0 10px 0; letter-spacing: -2px; }
    .stat-card p { font-size: 14px; font-weight: 800; color: var(--text-light); text-transform: uppercase; letter-spacing: 1.5px; }

    .team-header { text-align: center; margin-bottom: 60px; }
    .team-header h2 { font-size: 42px; font-weight: 900; color: var(--text-dark); letter-spacing: -1.5px; }
    
    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }
    .team-card {
        background: white;
        padding: 50px 30px;
        border-radius: 40px;
        border: 1.5px solid #f1f5f9;
        text-align: center;
        transition: 0.4s cubic-bezier(0.19, 1, 0.22, 1);
    }
    .team-card:hover { 
        transform: translateY(-15px); 
        box-shadow: 0 30px 60px rgba(0,0,0,0.06); 
        border-color: var(--primary);
    }
    .team-avatar {
        width: 140px; height: 140px;
        border-radius: 28px;
        margin: 0 auto 30px auto;
        overflow: hidden;
        transform: rotate(-5deg);
        transition: 0.3s;
    }
    .team-card:hover .team-avatar { transform: rotate(0deg) scale(1.1); }
    .team-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .team-card h4 { font-size: 24px; font-weight: 800; color: var(--text-dark); margin: 0 0 8px 0; }
    .team-card p { font-size: 15px; color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }

    @media (max-width: 1000px) {
        .story-section { grid-template-columns: 1fr; gap: 40px; }
        .stats-grid { grid-template-columns: 1fr; }
        .about-hero { height: 50vh; }
        .team-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="about-hero">
    <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?q=80&w=2000" alt="Travel Landscape">
    <div class="hero-text">
        <h1>Redefining Travel.</h1>
        <p>We believe the journey is just as important as the destination. Discover the world with unparalleled premium experiences.</p>
    </div>
</div>

<div class="about-container">
    <div class="story-section">
        <div class="story-text">
            <h2>Our Journey Began With a Simple Idea.</h2>
            <p>Founded in 2020, MyTrip was born out of a passion for exploration and a desire to make premium travel accessible. We realized that planning the perfect getaway was often stressful and time-consuming.</p>
            <p>So, we set out to build a platform that curates handpicked experiences, offering a seamless booking process from start to finish. Whether you're seeking a serene escape or an adrenaline-pumping bike trip, we ensure every detail is taken care of.</p>
        </div>
        <div class="story-img">
            <img src="https://images.unsplash.com/photo-1527631746610-bca00a040d60?q=80&w=800" alt="Our Story">
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>10K+</h3>
            <p>Happy Travelers</p>
        </div>
        <div class="stat-card">
            <h3>50+</h3>
            <p>Curated Destinations</p>
        </div>
        <div class="stat-card">
            <h3>4.9</h3>
            <p>Average Rating</p>
        </div>
    </div>

    <div class="team-header">
        <h2>Meet the Dream Makers</h2>
        <p style="color: var(--text-light); font-size: 18px; max-width: 600px; margin: 20px auto 0;">The experts behind your next unforgettable adventure.</p>
    </div>

    <div class="team-grid">
        <div class="team-card">
            <div class="team-avatar"><img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300" alt="CEO"></div>
            <h4>Sarah Jenkins</h4>
            <p>Founder & CEO</p>
        </div>
        <div class="team-card">
            <div class="team-avatar" style="transform: rotate(5deg);"><img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=300" alt="Director"></div>
            <h4>David Chen</h4>
            <p>Operations Director</p>
        </div>
        <div class="team-card">
            <div class="team-avatar" style="transform: rotate(-3deg);"><img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=300" alt="Guide"></div>
            <h4>Elena Rodriguez</h4>
            <p>Experience Curator</p>
        </div>
    </div>
</div>
<?php include 'f_footer.php'; ?>
