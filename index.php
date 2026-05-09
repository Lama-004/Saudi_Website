<?php
require_once 'includes/db.php';

$result = $conn->query("SELECT COUNT(*) as total FROM places");
$total = $result->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> عن المملكة - الصفحة الرئيسية</title>
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

<section class="hero-section">
    <div class="hero-content">
        <div class="hero-text">
            <h1>المملكة العربية السعودية</h1>
            <p>
               اكتشف مناطق المملكة كلها، من شمالها لجنوبها ومن شرقها لغربها، وتعرّف على تاريخها وأصالتها وتراثها الغني.
            </p>
            <a href="gallery.php" class="hero-btn">تعرف على وجهتك</a>
        </div>
        <div class="hero-welcome">
            <img src="images/saudi.jpg" alt="">
        </div>
    </div>
</section>

<div class="info-cards">
    <div class="info-card">
        <div class="icon">
            <img src="images/mission.png" alt="">

        </div>
        <h3>الهدف</h3>
        <p>   منصة رقمية تهدف إلى تقديم تجربة تعريفية حديثة عن مناطق المملكة العربية السعودية،
            تجمع بين المعلومات الموثوقة والعرض البصري الجذاب.
        </p>
    </div>
    <div class="info-card">
        <div class="icon">
             <img src="images/location.png" alt="">
        </div>
        <h3>المناطق</h3>
        <p>
            استكشاف تفاعلي لمناطق المملكة بطريقة سهلة ومنظمة، مع عرض الصور والمعلومات الأساسية لكل وجهة.
            يضم الموقع حالياً <strong><?= $total ?></strong> منطقة وموقعاً سياحياً.
        </p>
    </div>
    <div class="info-card">
        <div class="icon">             
            <img src="images/search.png" alt="">

        </div>
        <h3>التفاصيل</h3>
        <p>صفحات لكل موقع تعرض نبذة مختصرة، صوراً، ومعلومات ثقافية تعكس هوية المكان.</p>
    </div>
</div>

<div class="section-title">
    <h2>لماذا اكتشف السعودية؟</h2>
    <div class="title-line"></div>
    <p>موقعك الأول للتعرف على جمال وتنوع المملكة العربية السعودية</p>
</div>

<div class="info-cards" style="padding-top: 0;">
    <div class="info-card">
        <div class="icon"></div>
        <h3>تراث عريق</h3>
        <p>تعرف على المواقع الأثرية والتاريخية المدرجة ضمن قائمة التراث العالمي لليونسكو.</p>
    </div>
    <div class="info-card">
        <div class="icon"></div>
        <h3>طبيعة متنوعة</h3>
        <p>من جبال عسير الخضراء إلى رمال الربع الخالي وشواطئ البحر الأحمر.</p>
    </div>
    <div class="info-card">
        <div class="icon"></div>
        <h3>روحانية لا مثيل لها</h3>
        <p>استكشف أقدس بقاع الأرض في مكة المكرمة والمدينة المنورة.</p>
    </div>
</div>

<div class="section-title">
    <h2>هوية اكتشف السعودية</h2>
    <div class="title-line"></div>
    <p>ألوان موقعنا مستوحاة من جمال المملكة العربية السعودية</p>
</div>

<div class="identity-section">
    <div class="identity-card identity-purple">
        <div class="identity-icon">
             <img src="images/lavender.jpg" alt="خزامى نجد">
        </div>
        <h3>البنفسجي</h3>
        <p class="identity-name">خزامى نجد</p>
        <p class="identity-desc">
            مستوحى من أزهار الخزامى التي تتفتح في برية نجد،
            يرمز إلى العمق الحضاري والأصالة العريقة للمملكة.
        </p>
        <div class="identity-color-bar" style="background: linear-gradient(to left, #4b2e83, #9f86c0);"></div>
    </div>

    <div class="identity-card identity-yellow">
        <div class="identity-icon">
            <img src="images/golden-sand.jpg" alt="رمال ذهبية">
        </div>       
        <h3>الذهبي</h3>
        <p class="identity-name">رمال الصحراء الذهبية</p>
        <p class="identity-desc">
            مستوحى من رمال الربع الخالي والصحراء الذهبية،
            يرمز إلى الثروة والكرم والتاريخ الممتد عبر الأجيال.
        </p>
        <div class="identity-color-bar" style="background: linear-gradient(to left, #c8a84b, #f0d080);"></div>
    </div>
</div>

<footer>
    <p>© عن المملكة— <span>جامعة الملك سعود</span></p>
</footer>

<script src="js/main.js"></script>
</body>
</html>
