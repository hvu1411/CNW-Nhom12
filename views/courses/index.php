<?php
$tiêu_đề = "Danh sách khóa học - Hệ thống Quản lý Khóa học Online";
require_once 'views/layouts/header.php';

$selected_category = $_GET['category_id'] ?? '';
?>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --accent-pink: #ec4899;
    --accent-cyan: #06b6d4;
    --dark-bg: #0f172a;
    --card-bg: #1e293b;
}

.courses-hero {
    background: var(--primary-gradient);
    padding: 4rem 2rem;
    text-align: center;
    margin-bottom: 3rem;
    border-radius: 0 0 40px 40px;
    position: relative;
    overflow: hidden;
}

.courses-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 30%, rgba(236, 72, 153, 0.3) 0%, transparent 50%),
        radial-gradient(circle at 80% 70%, rgba(6, 182, 212, 0.3) 0%, transparent 50%);
    pointer-events: none;
}

.courses-hero h1 {
    font-size: 3.5rem;
    font-weight: 900;
    color: white;
    margin-bottom: 1rem;
    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    position: relative;
    z-index: 1;
    animation: fadeInDown 0.8s ease-out;
}

.courses-hero p {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.9);
    position: relative;
    z-index: 1;
}

.filter-section {
    max-width: 1400px;
    margin: 0 auto 3rem;
    padding: 0 2rem;
}

.filter-section h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: #fff;
    font-weight: 700;
}

.filter-select {
    width: 100%;
    max-width: 400px;
    padding: 1rem 1.5rem;
    font-size: 1rem;
    background: var(--card-bg);
    border: 2px solid rgba(102, 126, 234, 0.3);
    border-radius: 12px;
    color: #fff;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-select:hover, .filter-select:focus {
    border-color: var(--accent-pink);
    box-shadow: 0 0 20px rgba(236, 72, 153, 0.3);
    outline: none;
}

.filter-select option {
    background: var(--dark-bg);
    padding: 0.5rem;
}

.courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2.5rem;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem 4rem;
}

.course-card {
    background: var(--card-bg);
    border-radius: 20px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    animation: fadeInUp 0.6s ease-out backwards;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.course-card:nth-child(1) { animation-delay: 0.1s; }
.course-card:nth-child(2) { animation-delay: 0.15s; }
.course-card:nth-child(3) { animation-delay: 0.2s; }
.course-card:nth-child(4) { animation-delay: 0.25s; }
.course-card:nth-child(5) { animation-delay: 0.3s; }
.course-card:nth-child(6) { animation-delay: 0.35s; }

.course-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(102, 126, 234, 0.1));
    opacity: 0;
    transition: opacity 0.4s ease;
    z-index: 1;
    pointer-events: none;
}

.course-card:hover {
    transform: translateY(-15px) scale(1.02);
    box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
}

.course-card:hover::before {
    opacity: 1;
}

.course-image {
    width: 100%;
    height: 220px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.course-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.course-card:hover .course-image img {
    transform: scale(1.1);
}

.course-placeholder {
    font-size: 3rem;
    color: rgba(255, 255, 255, 0.8);
}

.course-info {
    padding: 1.75rem;
    position: relative;
    z-index: 2;
}

.course-info h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 1rem;
    line-height: 1.4;
    min-height: 4.2rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.course-meta {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    margin-bottom: 1rem;
    font-size: 0.95rem;
}

.course-meta-item {
    display: flex;
    align-items: center;
    color: rgba(255, 255, 255, 0.8);
}

.course-meta-item span {
    margin-left: 0.5rem;
}

.course-instructor {
    color: var(--accent-cyan);
    font-weight: 600;
}

.course-category {
    color: var(--accent-pink);
    font-weight: 600;
}

.level-badge {
    display: inline-block;
    padding: 0.4rem 0.9rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-top: 0.5rem;
}

.level-beginner {
    background: linear-gradient(135deg, #10b981, #059669);
    color: #fff;
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.3);
}

.level-intermediate {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #fff;
    box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
}

.level-advanced {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
}

.course-price {
    font-size: 1.75rem;
    font-weight: 900;
    background: linear-gradient(135deg, var(--accent-pink), var(--accent-cyan));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-top: 1rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: rgba(255, 255, 255, 0.7);
}

.empty-state p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .courses-hero h1 {
        font-size: 2.5rem;
    }
    
    .courses-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}
</style>

<div class="courses-hero">
    <h1>🎓 Khám Phá Khóa Học</h1>
    <p>Hơn <?php echo count($danh_sách_khóa_học ?? []); ?> khóa học chất lượng cao đang chờ bạn</p>
</div>

<div class="filter-section">
    <h3>🔍 Lọc theo danh mục</h3>
    <select onchange="window.location.href='index.php?controller=course&action=index&category_id='+this.value" class="filter-select">
        <option value="">📚 Tất cả danh mục</option>
        <?php if (!empty($danh_sách_danh_mục)): ?>
            <?php foreach ($danh_sách_danh_mục as $danh_mục): ?>
                <option value="<?= $danh_mục['id'] ?>" <?= ($selected_category == $danh_mục['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($danh_mục['name']) ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
</div>

<?php if (!empty($danh_sách_khóa_học)): ?>
    <div class="courses-grid">
        <?php foreach ($danh_sách_khóa_học as $khóa_học): 
            $course_id = $khóa_học['id'];
            $course_title = htmlspecialchars($khóa_học['title']);
            $course_image = htmlspecialchars($khóa_học['image'] ?? '');
            $course_instructor = htmlspecialchars($khóa_học['tên_giảng_viên']);
            $course_category = htmlspecialchars($khóa_học['tên_danh_mục']);
            $course_level = htmlspecialchars($khóa_học['level']);
            $course_price = number_format($khóa_học['price'], 0, ',', '.');
            
            $level_class = 'level-' . strtolower($course_level);
        ?>
            <div class="course-card" onclick="window.location.href='index.php?controller=course&action=detail&id=<?= $course_id ?>'">
                <div class="course-image">
                    <?php if (!empty($course_image)): ?>
                        <img src="assets/images/<?= $course_image ?>" alt="<?= $course_title ?>" loading="lazy">
                    <?php else: ?>
                        <div class="course-placeholder">📚</div>
                    <?php endif; ?>
                </div>
                <div class="course-info">
                    <h3><?= $course_title ?></h3>
                    <div class="course-meta">
                        <div class="course-meta-item">
                            <span>👨‍🏫</span>
                            <span class="course-instructor"><?= $course_instructor ?></span>
                        </div>
                        <div class="course-meta-item">
                            <span>📂</span>
                            <span class="course-category"><?= $course_category ?></span>
                        </div>
                    </div>
                    <span class="level-badge <?= $level_class ?>">
                        <?= $course_level ?>
                    </span>
                    <div class="course-price"><?= $course_price ?> VNĐ</div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <p>😔 Không tìm thấy khóa học nào</p>
    </div>
<?php endif; ?>

<script>
// Smooth scroll animation
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.course-card');
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Add click animation
            card.style.transform = 'scale(0.98)';
            setTimeout(() => {
                card.style.transform = '';
            }, 150);
        });
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>