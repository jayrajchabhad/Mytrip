<?php
session_start();
include 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$res = $conn->query("SELECT * FROM blog_posts WHERE id = $id");

if($res->num_rows == 0) {
    include 'f_header.php';
    echo "<div style='padding:100px; text-align:center;'><h2>Article not found!</h2><a href='blog.php'>Back to Blog</a></div>";
    include 'f_footer.php';
    exit;
}

$post = $res->fetch_assoc();
include 'f_header.php';
?>

<style>
    :root {
        --primary: #01696f;
        --soft-bg: #f8fafc;
        --text-dark: #0f172a;
        --text-light: #475569;
    }
    body { background-color: var(--soft-bg); }

    .article-hero {
        position: relative;
        height: 50vh;
        min-height: 400px;
        border-radius: 0 0 60px 60px;
        overflow: hidden;
        margin-top: -20px;
        display: flex;
        align-items: flex-end;
        padding: 60px 5%;
    }
    .article-hero img {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        z-index: -2;
    }
    .article-hero .overlay {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, rgba(15,23,42,0) 0%, rgba(15,23,42,0.9) 100%);
        z-index: -1;
    }
    
    .hero-content {
        max-width: 1000px;
        margin: 0 auto;
        width: 100%;
    }
    .article-date {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 20px;
        border: 1px solid rgba(255,255,255,0.3);
    }
    .article-hero h1 {
        font-size: 48px;
        font-weight: 900;
        color: white;
        margin: 0;
        line-height: 1.2;
    }

    .article-container {
        max-width: 900px;
        margin: -40px auto 80px auto;
        background: white;
        padding: 60px;
        border-radius: 40px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.05);
        position: relative;
        z-index: 10;
        border: 1px solid #f1f5f9;
    }

    .article-content {
        font-size: 18px;
        line-height: 1.8;
        color: var(--text-light);
    }
    .article-content p { margin-bottom: 25px; }
    
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: var(--primary);
        text-decoration: none;
        font-weight: 700;
        margin-top: 40px;
        padding: 12px 24px;
        background: #e0f2f1;
        border-radius: 16px;
        transition: 0.3s;
    }
    .back-btn:hover { background: var(--primary); color: white; transform: translateY(-2px); }

    @media (max-width: 768px) {
        .article-hero h1 { font-size: 32px; }
        .article-container { padding: 40px 30px; }
    }
</style>

<div class="article-hero">
    <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
    <div class="overlay"></div>
    <div class="hero-content">
        <div class="article-date"><?php echo date('F j, Y', strtotime($post['created_at'])); ?></div>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    </div>
</div>

<div class="article-container">
    <div class="article-content">
        <?php echo nl2br(htmlspecialchars($post['content'])); ?>
    </div>
    <a href="blog.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Articles</a>
</div>

<?php include 'f_footer.php'; ?>
