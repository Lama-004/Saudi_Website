<?php
require_once 'includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: gallery.php");
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM places WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$place = $result->fetch_assoc();

if (!$place) {
    header("Location: gallery.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عن المملكة - <?= htmlspecialchars($place['name']) ?></title>
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

<div class="details-page">

    <?php if (!empty($place['main_image']) && file_exists($place['main_image'])): ?>
        <img
            src="<?= htmlspecialchars($place['main_image']) ?>"
            alt="<?= htmlspecialchars($place['name']) ?>"
            style="width:100%; max-height:450px; object-fit:cover; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.1); margin-bottom:30px;"
            onerror="this.style.display='none';"
        >
    <?php else: ?>
        <div style="width:100%; height:280px; background:linear-gradient(135deg,#1a6b3c,#145530); border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:30px;">
            <span style="font-size:5rem; opacity:0.5;"></span>
        </div>
    <?php endif; ?>

    <div class="details-header">
        <h1><?= htmlspecialchars($place['name']) ?></h1>
        <p><?= nl2br(htmlspecialchars($place['description'])) ?></p>
    </div>

    <div class="details-grid">

        <div class="details-card">
            <h3>معلومات سريعة</h3>
            <ul class="quick-info">
                <li>المنطقة: <?= htmlspecialchars($place['region']) ?></li>
                <li>التصنيف: <?= htmlspecialchars($place['classification']) ?></li>
                <?php if (!empty($place['location'])): ?>
                    <li>الموقع: <?= htmlspecialchars($place['location']) ?></li>
                <?php endif; ?>
                <?php if (!empty($place['features'])): ?>
                    <?php foreach (explode('-', $place['features']) as $feature): ?>
                        <?php if (trim($feature)): ?>
                            <li><?= htmlspecialchars(trim($feature)) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>

        <div class="details-card">
            <h3>أبرز المعالم</h3>
            <?php if (!empty($place['landmarks'])): ?>
                <ul class="landmarks-list">
                    <?php foreach (explode('-', $place['landmarks']) as $landmark): ?>
                        <?php if (trim($landmark)): ?>
                            <li><?= htmlspecialchars(trim($landmark)) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p style="color: var(--text-light); font-size:0.9rem;">لا توجد معالم مسجلة.</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($place['activities'])): ?>
        <div class="details-card">
            <h3>الأنشطة المتاحة</h3>
            <ul class="landmarks-list">
                <?php foreach (explode('-', $place['activities']) as $activity): ?>
                    <?php if (trim($activity)): ?>
                        <li style="list-style:none; padding:4px 0; color:var(--text-medium); font-size:0.9rem;">
                             <?= htmlspecialchars(trim($activity)) ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="details-card">
            <h3>معرض الصور</h3>
            <div class="gallery-photos">
                <?php
                $galleryImages = [
                    $place['gallery_image1'],
                    $place['gallery_image2'],
                    $place['gallery_image3'],
                ];
                $hasImages = false;
                foreach ($galleryImages as $img):
                    if (!empty($img)):
                        $hasImages = true;
                ?>
                    <?php if (file_exists($img)): ?>
                        <img
                            src="<?= htmlspecialchars($img) ?>"
                            alt="صورة من <?= htmlspecialchars($place['name']) ?>"
                            onclick="openImage('<?= htmlspecialchars($img) ?>')"
                            onerror="this.parentElement.innerHTML='<div class=\'photo-placeholder\'></div>'"
                        >
                    <?php else: ?>
                        <div class="photo-placeholder"></div>
                    <?php endif; ?>
                <?php
                    endif;
                endforeach;
                if (!$hasImages): ?>
                    <p style="color:var(--text-light); font-size:0.85rem; grid-column:1/-1;">لا توجد صور معرض.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <div style="text-align:center; margin-top:30px;">
        <a href="gallery.php" class="hero-btn" style="display:inline-block;">
            ← العودة إلى المعرض
        </a>
    </div>

</div>

<footer>
    <p>© عن المملكة— <span>جامعة الملك سعود</span></p>
</footer>
<script src="js/main.js"></script>
</body>
</html>
