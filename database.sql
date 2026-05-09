-- =============================================
-- قاعدة بيانات موقع اكتشف السعودية
-- =============================================

CREATE DATABASE IF NOT EXISTS saudi_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE saudi_website;

-- جدول المستخدمين (المشرف)
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- جدول المناطق والأماكن
CREATE TABLE IF NOT EXISTS places (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    region VARCHAR(100) NOT NULL,
    classification ENUM('وسطى', 'غربية', 'شرقية', 'جنوبية', 'شمالية') NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(200),
    features TEXT,
    activities TEXT,
    landmarks TEXT,
    main_image VARCHAR(255),
    gallery_image1 VARCHAR(255),
    gallery_image2 VARCHAR(255),
    gallery_image3 VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- إدراج بيانات المشرف الافتراضي (كلمة المرور: admin123)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- إدراج بيانات تجريبية للمناطق
INSERT INTO places (name, region, classification, description, location, features, activities, landmarks, main_image, gallery_image1, gallery_image2, gallery_image3) VALUES

('الرياض', 'الرياض', 'وسطى',
'الرياض هي عاصمة المملكة العربية السعودية ومركزها السياسي والاقتصادي. تتميز بمزيج رائع من التراث العريق والحداثة المتطورة، وتجمع بين أحياء تاريخية عريقة وناطحات سحاب شامخة.',
'المنطقة الوسطى',
'أكبر مدينة في المملكة - مركز الأعمال والاقتصاد - العاصمة السياسية',
'زيارة الأحياء التاريخية - التسوق في المراكز التجارية الكبرى - الاستمتاع بالحدائق العامة',
'برج المملكة - قصر المصمك - حديقة الحيوانات',
'images/riyadh_main.jpg', 'images/riyadh1.jpg', 'images/riyadh2.jpg', 'images/riyadh3.jpg'),

('مكة المكرمة', 'مكة المكرمة', 'غربية',
'مكة المكرمة هي أقدس بقاع الأرض لدى المسلمين، وفيها المسجد الحرام والكعبة المشرفة. تستقبل الملايين من الحجاج والمعتمرين من شتى أنحاء العالم على مدار العام.',
'المنطقة الغربية - الحجاز',
'وجهة دينية عالمية - تستضيف أكبر تجمع بشري في العالم خلال موسم الحج',
'أداء فريضة الحج والعمرة - زيارة المواقع الدينية المقدسة - التأمل والعبادة',
'المسجد الحرام - الكعبة المشرفة - جبل عرفات - منى',
'images/makkah_main.jpg', 'images/makkah1.jpg', 'images/makkah2.jpg', 'images/makkah3.jpg'),

('العلا', 'العلا', 'غربية',
'العلا مدينة أثرية ساحرة تضم مواقع تراثية عالمية فريدة من نوعها. تشتهر بتكويناتها الصخرية الرائعة والمدائن الصالح التي أُدرجت ضمن قائمة التراث العالمي لليونسكو.',
'شمال غرب المملكة',
'موقع تراث عالمي - تكوينات صخرية فريدة - آثار نبطية نادرة',
'جولات أثرية - التصوير الفوتوغرافي - ركوب الخيل - مهرجانات الفنون',
'مدائن صالح - قصر العلا - العيون الأثرية',
'images/alula_main.jpg', 'images/alula1.jpg', 'images/alula2.jpg', 'images/alula3.jpg'),

('الخبر', 'الخبر', 'شرقية',
'الخبر مدينة ساحلية حديثة تطل على الخليج العربي في المنطقة الشرقية. تشتهر بكورنيشها الجميل ومراكزها التجارية الكبرى وبيئتها الحضرية المتطورة.',
'المنطقة الشرقية - ساحل الخليج',
'واجهة بحرية حديثة - مركز تجاري وترفيهي - بيئة متعددة الثقافات',
'التنزه على الكورنيش - صيد الأسماك - زيارة المولات - المطاعم البحرية',
'كورنيش الخبر - جزيرة أم النعسان - برج الخبر',
'images/khobar_main.jpg', 'images/khobar1.jpg', 'images/khobar2.jpg', 'images/khobar3.jpg'),

('أبها', 'أبها', 'جنوبية',
'أبها عاصمة منطقة عسير تقع على أعلى نقطة في سلسلة جبال الحجاز. تشتهر بمناخها المعتدل وطبيعتها الجبلية الخضراء الخلابة وتراثها الشعبي العريق.',
'المنطقة الجنوبية - جبال عسير',
'عاصمة منطقة عسير - أعلى مدن المملكة فوق سطح البحر - مناخ معتدل طوال العام',
'تسلق الجبال - زيارة قرى التراث - الاستمتاع بالطبيعة - الاستحمام بالضباب',
'تلفريك أبها - منتزه عسير الوطني - قرية رجال ألمع',
'images/abha_main.jpg', 'images/abha1.jpg', 'images/abha2.jpg', 'images/abha3.jpg'),

('تبوك', 'تبوك', 'شمالية',
'تبوك مدينة تاريخية تقع في شمال غرب المملكة العربية السعودية. تتميز بموقعها الاستراتيجي وتاريخها العريق الممتد منذ آلاف السنين، فضلاً عن طبيعتها المتنوعة.',
'شمال غرب المملكة',
'موقع تاريخي استراتيجي - بوابة المملكة الشمالية - طبيعة متنوعة',
'زيارة المواقع الأثرية - الغوص في البحر الأحمر - استكشاف الصحراء',
'قلعة تبوك - وادي دومة - شرما البحرية',
'images/tabuk_main.jpg', 'images/tabuk1.jpg', 'images/tabuk2.jpg', 'images/tabuk3.jpg');
