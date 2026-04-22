<?php
session_start();
include 'db.php';
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

    .blog-hero {
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
    .blog-hero img {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        opacity: 0.65;
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

    .blog-container { max-width: 1400px; margin: 100px auto; padding: 0 5%; }
    
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 40px;
    }
    
    .blog-card {
        background: white;
        border-radius: 40px;
        overflow: hidden;
        border: 1.5px solid #f1f5f9;
        transition: 0.4s cubic-bezier(0.19, 1, 0.22, 1);
        display: flex;
        flex-direction: column;
    }
    .blog-card:hover { transform: translateY(-15px); box-shadow: 0 30px 60px rgba(0,0,0,0.06); border-color: var(--primary); }
    
    .blog-img { height: 280px; overflow: hidden; position: relative; }
    .blog-img img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
    .blog-card:hover .blog-img img { transform: scale(1.08); }
    
    .blog-content { padding: 40px; flex-grow: 1; display: flex; flex-direction: column; }
    .blog-date { font-size: 13px; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 15px; }
    .blog-title { font-size: 26px; font-weight: 800; color: var(--text-dark); margin: 0 0 15px 0; line-height: 1.3; letter-spacing: -0.5px; }
    .blog-excerpt { color: var(--text-light); line-height: 1.8; margin-bottom: 30px; flex-grow: 1; font-size: 16px; }
    
    .read-more {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 800;
        color: var(--primary);
        text-decoration: none;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: 0.3s;
    }
    .read-more i { transition: 0.3s; }
    .read-more:hover i { transform: translateX(5px); }

    @media (max-width: 900px) {
        .blog-grid { grid-template-columns: 1fr; }
        .blog-hero { height: 40vh; margin: 10px; border-radius: 20px; }
    }
</style>

<div class="blog-hero">
    <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?q=80&w=2000" alt="Blog Hero">
    <div class="hero-text">
        <h1>Travel Tales.</h1>
        <p>Stories from the road, expert guides, and inspiration for your next great journey.</p>
    </div>
</div>

<div class="blog-container">
    <div class="blog-grid">
        <?php
        $res = $conn->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
        if($res->num_rows > 0):
            while($post = $res->fetch_assoc()):
        ?>
            <div class="blog-card">
                <div class="blog-img">
                    <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                </div>
                <div class="blog-content">
                    <div class="blog-date"><?php echo date('M d, Y', strtotime($post['created_at'])); ?></div>
                    <h2 class="blog-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p class="blog-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                    <div>
                        <a href="blog_article.php?id=<?php echo $post['id']; ?>" class="read-more">Read Article <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        <?php endwhile; else: ?>
            <p style="text-align:center; grid-column: 1/-1; padding: 50px;">No articles found yet. Check back soon!</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'f_footer.php'; ?>
