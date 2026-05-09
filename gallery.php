<?php
require_once 'includes/db.php';

$result = $conn->query("SELECT * FROM places ORDER BY id ASC");
$places = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> عن المملكة - مناطق المملكة </title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-inner">
        <a href="index.php" class="navbar-brand"> <span>عن المملكة</span></a>
        <ul class="nav-links">
            <li><a href="index.php" class="active">الرئيسية</a></li>
            <li><a href="gallery.php">مناطق المملكة </a></li>
            <li><a href="admin/login.php"> صفحة المشرف</a></li>
            <li>
                <button class="night-toggle" id="night-toggle-btn">
                الوضع الليلي
                </button>
            </li>
        </ul>
    </div>
</nav>

<div class="section-title">
    <h2>مناطق  المملكة</h2>
    <div class="title-line"></div>
    <p>ابحث أو صنّف ثم اضغط على أي منطقة للانتقال إلى صفحة التفاصيل.</p>
</div>

<div class="gallery-page">
    <div class="gallery-controls">
        <input
            type="text"
            id="search-input"
            class="search-box"
            placeholder="ابحث عن منطقة أو مدينة..."
        >
        <select id="filter-select" class="filter-select">
            <option value="all">كل المناطق</option>
            <option value="وسطى">وسطى</option>
            <option value="غربية">غربية</option>
            <option value="شرقية">شرقية</option>
            <option value="جنوبية">جنوبية</option>
            <option value="شمالية">شمالية</option>
        </select>
        <span class="results-count" id="results-count">عدد النتائج: <?= count($places) ?></span>
    </div>

    <div class="gallery-grid" id="gallery-grid">
        <?php if (empty($places)): ?>
            <div class="no-results">لا توجد مناطق مسجلة حتى الآن.</div>
        <?php else: ?>
            <?php foreach ($places as $place): ?>
                <a
                    href="details.php?id=<?= $place['id'] ?>"
                    class="place-card"
                    data-name="<?= htmlspecialchars($place['name']) ?>"
                    data-region="<?= htmlspecialchars($place['region']) ?>"
                    data-classification="<?= htmlspecialchars($place['classification']) ?>"
                    data-desc="<?= htmlspecialchars(mb_substr($place['description'], 0, 100)) ?>"
                >
                    <?php if (!empty($place['main_image']) && file_exists($place['main_image'])): ?>
                        <img
                            src="<?= htmlspecialchars($place['main_image']) ?>"
                            alt="<?= htmlspecialchars($place['name']) ?>"
                            class="place-card-img"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >
                        <div class="place-card-img-placeholder" style="display:none;"></div>
                    <?php else: ?>
                        <div class="place-card-img-placeholder"></div>
                    <?php endif; ?>

                    <div class="place-card-body">
                        <span class="place-card-tag"><?= htmlspecialchars($place['classification']) ?></span>
                        <div class="place-card-name"><?= htmlspecialchars($place['name']) ?></div>
                        <div class="place-card-desc"><?= htmlspecialchars($place['description']) ?></div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="no-results" id="no-results" style="display:none;">
            لا توجد نتائج تطابق بحثك. جرّب كلمة مختلفة.
        </div>
    </div>
</div>


<footer>
    <p>© عن المملكة— <span>جامعة الملك سعود</span></p>
</footer>

<script src="js/main.js"></script>
</body>
</html>
