-- CI fixture for GitHub Actions. Generated from the staging copy of the production
-- database on 2026-10-31: full schema, public site content and Voyager configuration.
-- Personal data is NOT included: users user_roles contacts appointments password_resets personal_access_tokens failed_jobs are schema only,
-- and settings.site.google_analytics_tracking_id is left out. See tests/fixtures/README.md.
/*M!999999\- enable the sandbox mode */ 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `avatar` varchar(191) DEFAULT 'users/default.png',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `settings` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `user_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `user_roles_user_id_index` (`user_id`),
  KEY `user_roles_role_id_index` (`role_id`),
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `type_of_service` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`(191),`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

/*M!999999\- enable the sandbox mode */ 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;
DROP TABLE IF EXISTS `abouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `abouts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) DEFAULT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `text_en` text DEFAULT NULL,
  `text_ar` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `video` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `abouts` WRITE;
/*!40000 ALTER TABLE `abouts` DISABLE KEYS */;
INSERT INTO `abouts` VALUES (1,'know about Just Lite Center','تعرف على السنتر','You are welcome :)\r\n\r\nMy name is Layla Hanna, Owner of Just Lite Center \r\n\r\nI graduated from Notre Dame University – Louaize, Lebanon in 2015 majoring in Nutrition and Dietetics and In 2016 I obtained permission to practice the profession from the Ministry of Health and decided to establish my own business by establishing a private clinic in Lebanon, Tripoli, where I treat patients of all groups and ages, men, women and children to support their health status\r\nIn addition to the clinic, I started my career in providing online nutritional advice with the start of the Corona epidemic in 2020, and now I have clients from all over the world, I help them on how to follow a healthy lifestyle and reach their goal in a healthy and easy way\r\nAnd in the year 2022, I obtained a life coach certificate in addition to my profession, and I am sure that you will benefit more from mental and physical health safely with me.','أهلا و سهلا فيك \r\n\r\nاسمي ليلى حنا, صاحبة شركة \r\n تخصصت بعلم التغذية و تخرجت من جامعة سيدة اللويزة - لبنان عام 2015\r\nوفي سنة ٢٠١6، حصلت على اذن مزاولة المهنة من وزارة الصحة  وقررت تأسيس عملي الخاص من خلال تاسيس عيادة خاصة في لبنان طرابلس حيث أعالج مرضى من جميع الفئات والأعمار رجال ونساء وأطفال لدعم وضعهم الصحي\r\nالى جانب العيادة , بلشت مسيرتي بتتقديم استشارات غذائية أونلاين مع ابتداء وباء الكورونا عام 2020 والآن أصبح لدي عملاء من جميع أنحاء العالم، أساعدهم على كيفية اتباع نمط حياة صحي والوصول الى هدفهم بطريقة صحية وسهلة\r\nو في سنة 2022 حصلت على شهادة مدرب حياة الى جانب مهنتي و متاكدة انوا ح تستفيد اكتر صحة العقلية و الجسدة بامان معي',NULL,'https://www.youtube.com/watch?v=wmRWkKUtrNs','2022-02-07 18:46:00','2023-01-08 01:52:45');
/*!40000 ALTER TABLE `abouts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `bad_habits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `bad_habits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` text DEFAULT NULL,
  `title_ar` text DEFAULT NULL,
  `text_en` text DEFAULT NULL,
  `text_ar` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `bad_habits` WRITE;
/*!40000 ALTER TABLE `bad_habits` DISABLE KEYS */;
INSERT INTO `bad_habits` VALUES (1,'Too Many Liquid Calories','الكثير من السعرات الحرارية السائلة','It’s easy to forget that calories from soda, juices and other sugar-sweetened beverages count! Sip on calorie-free beverages like water, unsweetened teas and sparkling water in place of the high calorie drinks.','من السهل أن تنسى أن السعرات الحرارية من الصودا والعصائر وغيرها من المشروبات المحلاة بالسكر مهمة! ارتشف مشروبات خالية من السعرات الحرارية مثل الماء والشاي غير المحلى والماء الفوار بدلاً من المشروبات عالية السعرات الحرارية.','2022-02-23 23:49:53','2022-02-23 23:49:53');
INSERT INTO `bad_habits` VALUES (2,'Too Much Added Sugar','الكثير من السكر المضاف','Aside from the candies, cookies and soda that we eat , sugar is lurking in places you might not expect, like whole grain cereals, salad dressings and breads. Take inventory of the total sugar in your diet and find ways to cut back on those empty calories.','بصرف النظر عن الحلوى والبسكويت والصودا التي نأكلها ، فإن السكر يكمن في أماكن قد لا تتوقعها ، مثل الحبوب الكاملة وتوابل السلطة والخبز. قم بجرد إجمالي السكر في نظامك الغذائي وابحث عن طرق لتقليص تلك السعرات الحرارية الفارغة.','2022-02-23 23:51:23','2022-02-23 23:51:23');
INSERT INTO `bad_habits` VALUES (3,'Mindless Eating','الأكل الطائش','Instead of just eating when hungry, many of us grab food when we are bored, tired, stressed, happy, sad.','بدلاً من مجرد الأكل عند الجوع ، يتناول الكثير منا الطعام عندما نشعر بالملل والتعب والتوتر والسعادة والحزن','2022-02-23 23:52:14','2022-02-23 23:52:14');
INSERT INTO `bad_habits` VALUES (4,'Not Eating Enough Throughout The Day','عدم تناول ما يكفي من الطعام طوال اليوم','Less definitely is not always more! Not taking in enough calories throughout the day tanks energy levels, spikes hunger, and leads to overeating later on (when you are tired and ready to eat everything in sight).','أقل تأكيدًا ليس دائمًا أكثر! عدم تناول سعرات حرارية كافية على مدار اليوم يؤدي إلى زيادة مستويات الطاقة وزيادة الجوع ويؤدي إلى الإفراط في تناول الطعام في وقت لاحق (عندما تكون متعبًا ومستعدًا لأكل كل شيء في الأفق).','2022-02-23 23:53:16','2022-02-23 23:53:16');
INSERT INTO `bad_habits` VALUES (5,'Not Eating Together','لا نأكل معا','Along the same lines as eating mindlessly comes eating while distracted, over-scheduled and in multiple shifts. Turn off the TV (and yes, even the computer and cell phone) at meals and make time to eat as a family as much as possible.','على نفس المنوال مثل تناول الطعام بلا وعي ، يأتي تناول الطعام أثناء تشتيت الانتباه ، والإفراط في الجدول الزمني وفي نوبات متعددة. أغلق التليفزيون (ونعم ، حتى الكمبيوتر والهاتف الخلوي) في وجبات الطعام وخصص وقتًا لتناول الطعام كعائلة قدر الإمكان.','2022-02-23 23:54:37','2022-02-23 23:54:37');
INSERT INTO `bad_habits` VALUES (6,'Too Many Meals Away From Home','وجبات كثيرة جدًا بعيدًا عن المنزل','Restaurants and take-out will always mean super-sized portions, along with more calories and sodium. Make the effort to prepare meals at home most nights of the week and use our tips when you eat outside.','ستعني المطاعم والوجبات الجاهزة دائمًا حصصًا كبيرة الحجم ، إلى جانب المزيد من السعرات الحرارية والصوديوم. ابذل جهدًا لإعداد وجبات الطعام في المنزل معظم ليالي الأسبوع واستخدم نصائحنا .','2022-02-23 23:55:54','2022-02-23 23:55:54');
/*!40000 ALTER TABLE `bad_habits` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) DEFAULT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `text_en` text DEFAULT NULL,
  `text_ar` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `link` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'Welcome to the first step towards better health','أهلا و سهلا بك في الخطوة الاولى نحو صحة الافضل','feel confident with food and find your healthiest weight in this self-paced, supportive page','استعيد الثقة بنفسك و بجسمك و تعلم كيف توصل لوزنك الصحي من خلال هذه الصفحة','banners/January2023/qzbv28Vvv4975OiLcTnv.png','asfxfasf','2022-02-06 08:55:09','2023-01-07 15:21:11');
INSERT INTO `banners` VALUES (3,'Start a different health lifestyle','ابدأ نظام حياة صحي مختلف','I help you reach your goal without deprivation, restrictions or rules, teach you how to eat home cooking and your favorite meals without any deprivation, know how to enjoy eating restaurants and keep following your nutritional plan and achieving your goals','اساعدك في الوصول لهدفك من دون حرمان أو قيود أو قواعد\r\n و أعلمك كيفية تناول  طبخ البيت ووجباتك المفضلة  دون أية حرمان\r\n معرفة كيفية الاستمتاع بأكل المطاعم و المحافظة على إتباع خطتك الغذائية \r\n  وتحقيق أهدافك و التركيز على تغذية الجسم','banners/January2023/c0fuuAUzUEyRFKvC4anx.png',NULL,'2022-02-24 00:02:40','2023-01-05 18:24:22');
INSERT INTO `banners` VALUES (4,'Anti-diet','ضد الدايت و الحرمان','Over the past 7 years through my career, I\'ve helped more than 1000 people in Lebanon and 1 year ago starting my Online consultations\r\nhelping people in achieving their body (weight loss , gain, maintenance) and health goals\r\n\r\n- Understand their bodies nutritional need to become their own nutritionist \r\n-  Change their lifestyle and nutritional understanding to guarantee maintaining their achieved results\r\n-  my aim is educating people on how to apply a healthier lifestyle in an easy and simple and enjoyable way','.خلال مسيرتي المهنية التي تفوق السبع سنوات، قمت بمساعدة أكثر من 1000 شخص حول العالم في تحقيق جسم أحلامهم ,في خسارة أو زيادة الوزن و فهم إحتياجات جسمهم الغذائية ليصبحوا خبراء جسدهم \r\n .تغيير نمط حياتهم وفكرهم الغذائي لضمان المحافظة على النتائج المحققة مدى الحياة\r\nهدفي الاساسي من مسيرتي هي بتثقيف الأشخاص بأمور متعلقة بكيفية تطبيق الحياة الصحية بطريقة سهلة، واقعية وممتعة','banners/January2023/o6i8UwDQtY1oFJXzHNTE.png','link','2022-03-04 18:14:11','2023-01-06 20:49:27');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) DEFAULT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `text_en` text DEFAULT NULL,
  `text_ar` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_receipt` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (2,'Lentil Soup','شوربة العدس','For amounts:\r\n tablespoon olive oil\r\n 2 medium onions\r\n Garlic (I used about 8 cloves)\r\n coriander\r\n 2 liters of water\r\n 3 sweet potatoes, cut into cubes\r\n Boil (almost complete)\r\n Spices: 1 teaspoon salt, allspice, chili and sumac\r\n Acid on demandsu is a coffee-flavoured Italian dessert.','لمقادير:\r\n ملعقة كبيرة زيت زيتون\r\n ٢ بصل وسط\r\n ثوم (انا استعملت تقريبا ٨ حبات)\r\n كزبرة\r\n ٢ ليتر ماء\r\n ٣ حبات بطاطا مقطع مكعبات\r\n سلق ( تقريبا سلقة كاملة)\r\n بهارات: ١ ملعقة صغيرة ملح و بهار وحر و سماق\r\n حامض حسب الطلب','blogs/February2022/lP4VBxkr5O1hjzNvSpJ2.png','2022-02-11 18:09:00','2022-02-23 23:03:24',1);
INSERT INTO `blogs` VALUES (4,'Snickers','سنيكرز','This recipe is easy and delicious.\r\nJust 4 ingredients:\r\n- Dates\r\n- Raw almonds\r\n- Peanut Butter\r\n- Chocolate\r\nChop dates and almonds finely,add a layer on peanut butter.Them melt chocolate and dip them inside of it.Freeze and enjoy.','بس ب ٤ مكونات \r\n- تمر\r\n- لوز نيء\r\n- زبدة الفول السوداني\r\n- شوكولا','blogs/February2022/535MX0bZ9HcWfDnpq9P6.png','2022-02-11 18:10:00','2022-02-23 23:10:05',1);
INSERT INTO `blogs` VALUES (5,'Almond cookies with dates','كوكيز اللوز مع التمر','The components :1 cup almonds,12-15 dates or 1 cup of date paste,3 Tablespoons Peanut Butter,1 teaspoon vanilla,pinch of salt\r\nTo decorate:Melt chocolate with oil.Intructions:add all ingredients into a blender until smooth,then melt chocolate and oil together and decorate','المكونات :\r\n١ كوب لوز\r\n١٢-١٥ حبة تمر او ١ كوب معجون التمر\r\n٣ ملاعق كبار زبدة الفول السوداني\r\n١ ملعقة صغير فانيلا\r\nرشة ملح\r\nرشة شوكولا حب ( ما ضروري)\r\n\r\nللتنزيين:\r\nتدويب شوكولا مع ملعقة زيت صغيرة\r\nSprinkles chocolate','blogs/February2022/XkRlco5QpUGGPIsjdHsN.png','2022-02-11 18:10:00','2022-02-23 23:15:18',1);
INSERT INTO `blogs` VALUES (7,'Chocolate balls','كرات الشوكولا','the components:\r\n1/4 cup cocoa\r\n1/4 cup oat flour\r\n1/2 cup crushed almonds\r\n1 tablespoon of maca oil or coconut oil\r\n3 tablespoons milk\r\n2 tablespoons honey\r\nsome water\r\nTo decorate:Melt chocolate\r\nadd all ingredients to a blender,melt chocolate.make small chocolate balls and dip them in the melted chocolate.','المكونات:\r\n١/٤ كوب كاكاو\r\n١/٤ كوب طحين الشوفان\r\n١/٢ كوب لوز مطحون\r\n١ ملعقة كبيرة زيت مباتي او زيت جوز الهند\r\n٣ ملاعق كبيرة حليب\r\n٢ ملعقة عسل كبيرة\r\nشوية ماء\r\nللتزيين :\r\nتذويب شوكولا','blogs/February2022/GXeewunOqE6cmlzhuYzT.png','2022-02-11 18:11:00','2022-02-23 23:28:04',1);
INSERT INTO `blogs` VALUES (8,'Vegetable Soup','شوربة','Ingredients:\r\n2 potatoes\r\n2 onions\r\n6 garlic cloves\r\n4 zucchini\r\nCauliflower average 1 cup\r\nBroccoli 1 cup\r\nBeans average 1 cup\r\n4 carrots\r\n3 tablespoons of rice\r\nvermicelli noodles\r\n2 liters of water\r\n\r\nspices:\r\n1 teaspoon salt\r\n1/2 teaspoon white pepper\r\n1 teaspoon dry mint\r\n1 teaspoon paprika\r\nCut all ingredients into small pieces and boil them.','المقادير:\r\n٢ بطاطا\r\n٢ بصل\r\n٦ حبات ثوم\r\n٤ كوسا\r\nقرنبيط معدل ١ كوب\r\nبروكولي معدل ١ كوب\r\nلوبيا معدل ١ كوب\r\n٤ حبات جزر\r\n٣ ملاعق كبار رز\r\nكمشة شعيرية\r\n٢ ليتر ماء\r\n\r\nبهارات:\r\n١ ملعقة صغيرة ملح\r\n١/٢ ملعقة صغيرة بهار ابيض\r\n١ ملعقة صغيرة نعنع يابس\r\n١ ملعقة صغيرة بابريكا','blogs/February2022/NU5TBjYYjqsyojdZ1Axt.png','2022-02-11 18:11:00','2022-02-23 23:28:48',1);
INSERT INTO `blogs` VALUES (9,'Salad','سلطة','Ingredients:Iceberg,Tomatoes,Onions,Carrots,Green Bell Pepper,Red Bell Pepper,Yellow Bell Pepper,Fetta Cheese,Cranberry,cucumbers,radishes,walnuts and pumpkin seeds.\r\nSauce:Lemon Juice,Olive Oil,Salt,Black pepper,paprika and mustard.Cut ingredients into bite size pieces,add them into a bowl,mix the sauce','المكونات: جرجير ، سلطة آيسبرج ، طماطم ، بصل ، جزر ، فلفل أخضر ، فلفل أحمر ، فلفل أصفر ، جبنة فيتا ، توت بري ، خيار ، فجل ، جوز وبذور اليقطين.\r\nالصلصة: عصير ليمون ، زيت زيتون ، ملح ، فلفل أسود ، بابريكا وخردل.\r\nقطعي المكونات إلى قطع صغيرة الحجم وأضيفيها في وعاء (يمكنك إضافة وإزالة أي خضروات تريدها) ، ثم اخلطي مكونات الصلصة وأضيفيها إلى الوعاء.','blogs/February2022/0yzYHVOuhUfpxK6fsULP.png','2022-02-23 23:41:00','2022-02-23 23:44:23',1);
INSERT INTO `blogs` VALUES (11,'Strawberry salad','سلطة الفراولة','Ingredients:Lettuce,fetta cheese,cranberry,walnuts and strawberries.For the sauce add honey,olive oil and lemon juice.Add a pinch of salt and chilli to your liking and enjoy.','المقادير: خس ، جبنة فيتا ، توت بري ، جوز وفراولة ، ولصوص يضاف العسل وزيت الزيتون وعصير الليمون ، يضاف قليل من الملح والفلفل حسب الرغبة والاستمتاع.','blogs/February2022/pGJOjyEEcuNNT8b8U6Qu.png','2022-02-23 23:46:18','2022-02-23 23:46:18',1);
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `data_rows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_rows` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `data_type_id` int(10) unsigned NOT NULL,
  `field` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `browse` tinyint(1) NOT NULL DEFAULT 1,
  `read` tinyint(1) NOT NULL DEFAULT 1,
  `edit` tinyint(1) NOT NULL DEFAULT 1,
  `add` tinyint(1) NOT NULL DEFAULT 1,
  `delete` tinyint(1) NOT NULL DEFAULT 1,
  `details` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `data_rows_data_type_id_foreign` (`data_type_id`),
  CONSTRAINT `data_rows_data_type_id_foreign` FOREIGN KEY (`data_type_id`) REFERENCES `data_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `data_rows` WRITE;
/*!40000 ALTER TABLE `data_rows` DISABLE KEYS */;
INSERT INTO `data_rows` VALUES (1,1,'id','number','ID',1,0,0,0,0,0,NULL,1);
INSERT INTO `data_rows` VALUES (2,1,'name','text','Name',1,1,1,1,1,1,NULL,2);
INSERT INTO `data_rows` VALUES (3,1,'email','text','Email',1,1,1,1,1,1,NULL,3);
INSERT INTO `data_rows` VALUES (4,1,'password','password','Password',1,0,0,1,1,0,NULL,4);
INSERT INTO `data_rows` VALUES (5,1,'remember_token','text','Remember Token',0,0,0,0,0,0,NULL,5);
INSERT INTO `data_rows` VALUES (6,1,'created_at','timestamp','Created At',0,1,1,0,0,0,NULL,6);
INSERT INTO `data_rows` VALUES (7,1,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,7);
INSERT INTO `data_rows` VALUES (8,1,'avatar','image','Avatar',0,1,1,1,1,1,NULL,8);
INSERT INTO `data_rows` VALUES (9,1,'user_belongsto_role_relationship','relationship','Role',0,1,1,1,1,0,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsTo\",\"column\":\"role_id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"roles\",\"pivot\":0}',10);
INSERT INTO `data_rows` VALUES (10,1,'user_belongstomany_role_relationship','relationship','voyager::seeders.data_rows.roles',0,1,1,1,1,0,'{\"model\":\"TCG\\\\Voyager\\\\Models\\\\Role\",\"table\":\"roles\",\"type\":\"belongsToMany\",\"column\":\"id\",\"key\":\"id\",\"label\":\"display_name\",\"pivot_table\":\"user_roles\",\"pivot\":\"1\",\"taggable\":\"0\"}',11);
INSERT INTO `data_rows` VALUES (11,1,'settings','hidden','Settings',0,0,0,0,0,0,NULL,12);
INSERT INTO `data_rows` VALUES (12,2,'id','number','ID',1,0,0,0,0,0,NULL,1);
INSERT INTO `data_rows` VALUES (13,2,'name','text','Name',1,1,1,1,1,1,NULL,2);
INSERT INTO `data_rows` VALUES (14,2,'created_at','timestamp','Created At',0,0,0,0,0,0,NULL,3);
INSERT INTO `data_rows` VALUES (15,2,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,4);
INSERT INTO `data_rows` VALUES (16,3,'id','number','ID',1,0,0,0,0,0,NULL,1);
INSERT INTO `data_rows` VALUES (17,3,'name','text','Name',1,1,1,1,1,1,NULL,2);
INSERT INTO `data_rows` VALUES (18,3,'created_at','timestamp','Created At',0,0,0,0,0,0,NULL,3);
INSERT INTO `data_rows` VALUES (19,3,'updated_at','timestamp','Updated At',0,0,0,0,0,0,NULL,4);
INSERT INTO `data_rows` VALUES (20,3,'display_name','text','Display Name',1,1,1,1,1,1,NULL,5);
INSERT INTO `data_rows` VALUES (21,1,'role_id','text','Role',1,1,1,1,1,1,NULL,9);
INSERT INTO `data_rows` VALUES (22,4,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (23,4,'title_en','text','Title En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (24,4,'title_ar','text','Title Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (25,4,'text_en','text_area','Text En',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (26,4,'text_ar','text_area','Text Ar',0,1,1,1,1,1,'{}',5);
INSERT INTO `data_rows` VALUES (27,4,'image','image','Image (1020 x 860)',0,1,1,1,1,1,'{\"resize\":{\"width\":\"1020\",\"height\":\"860\"},\"quality\":\"100%\",\"upsize\":true,\"thumbnails\":[{\"name\":\"medium\",\"scale\":\"50%\"},{\"name\":\"small\",\"scale\":\"25%\"},{\"name\":\"cropped\",\"crop\":{\"width\":\"300\",\"height\":\"250\"}}]}',6);
INSERT INTO `data_rows` VALUES (28,4,'link','text','Link',0,1,1,1,1,1,'{}',7);
INSERT INTO `data_rows` VALUES (29,4,'created_at','timestamp','Created At',0,1,1,0,0,0,'{}',8);
INSERT INTO `data_rows` VALUES (30,4,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9);
INSERT INTO `data_rows` VALUES (31,5,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (32,5,'title_en','text','Title En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (33,5,'title_Ar','text','Title Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (34,5,'text_en','text_area','Text En',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (35,5,'text_ar','text_area','Text Ar',0,1,1,1,1,1,'{}',5);
INSERT INTO `data_rows` VALUES (37,5,'created_at','timestamp','Created At',0,1,1,0,0,0,'{}',7);
INSERT INTO `data_rows` VALUES (38,5,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',8);
INSERT INTO `data_rows` VALUES (39,5,'images','multiple_images','Images ( 1650 x 1095)',0,1,1,1,1,1,'[{\"resize\":{\"width\":\"1140\",\"height\":\"715\"},\"quality\":\"70%\",\"upsize\":true,\"thumbnails\":[{\"name\":\"medium\",\"scale\":\"50%\"},{\"name\":\"small\",\"scale\":\"25%\"},{\"name\":\"cropped\",\"crop\":{\"width\":\"300\",\"height\":\"250\"}}]}]',6);
INSERT INTO `data_rows` VALUES (40,6,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (41,6,'title_en','text','Title En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (42,6,'title_ar','text','Title Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (43,6,'text_en','text_area','Text En',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (44,6,'text_ar','text_area','Text Ar',0,1,1,1,1,1,'{}',5);
INSERT INTO `data_rows` VALUES (45,6,'image','image','Image ( 1650 x 1095 )',0,1,1,1,1,1,'{\"resize\":{\"width\":\"1140\",\"height\":\"700\"},\"quality\":\"70%\",\"upsize\":true,\"thumbnails\":[{\"name\":\"medium\",\"scale\":\"50%\"},{\"name\":\"small\",\"scale\":\"25%\"},{\"name\":\"cropped\",\"crop\":{\"width\":\"300\",\"height\":\"250\"}}]}',6);
INSERT INTO `data_rows` VALUES (46,6,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',7);
INSERT INTO `data_rows` VALUES (47,6,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',8);
INSERT INTO `data_rows` VALUES (48,7,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (49,7,'title_en','text','Title En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (50,7,'title_ar','text','Title Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (51,7,'text_en','text_area','Text En',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (52,7,'text_ar','text_area','Text Ar',0,1,1,1,1,1,'{}',5);
INSERT INTO `data_rows` VALUES (53,7,'image','image','Image',0,1,1,1,1,1,'{\"resize\":{\"width\":\"500\",\"height\":\"500\"},\"quality\":\"70%\",\"upsize\":true,\"thumbnails\":[{\"name\":\"medium\",\"scale\":\"50%\"},{\"name\":\"small\",\"scale\":\"25%\"},{\"name\":\"cropped\",\"crop\":{\"width\":\"300\",\"height\":\"250\"}}]}',6);
INSERT INTO `data_rows` VALUES (54,7,'created_at','timestamp','Created At',0,1,1,0,0,0,'{}',7);
INSERT INTO `data_rows` VALUES (55,7,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',8);
INSERT INTO `data_rows` VALUES (56,8,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (57,8,'name_en','text','Name En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (58,8,'name_ar','text','Name Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (59,8,'features_en','markdown_editor','Features En',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (60,8,'features_ar','markdown_editor','Features Ar',0,1,1,1,1,1,'{}',5);
INSERT INTO `data_rows` VALUES (61,8,'price','number','Price',0,1,1,1,1,1,'{}',6);
INSERT INTO `data_rows` VALUES (62,8,'image','image','Image',0,1,1,1,1,1,'{}',7);
INSERT INTO `data_rows` VALUES (63,8,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',8);
INSERT INTO `data_rows` VALUES (64,8,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9);
INSERT INTO `data_rows` VALUES (65,9,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (66,9,'title_en','text','Title En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (67,9,'title_ar','text','Title Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (68,9,'text_en','markdown_editor','Text En',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (69,9,'text_ar','markdown_editor','Text Ar',0,1,1,1,1,1,'{}',5);
INSERT INTO `data_rows` VALUES (70,9,'image','image','Image ( 1010px x 690px )',0,1,1,1,1,1,'{\"resize\":{\"width\":\"1010\",\"height\":\"690\"},\"quality\":\"70%\",\"upsize\":true,\"thumbnails\":[{\"name\":\"medium\",\"scale\":\"50%\"},{\"name\":\"small\",\"scale\":\"25%\"},{\"name\":\"cropped\",\"crop\":{\"width\":\"300\",\"height\":\"250\"}}]}',6);
INSERT INTO `data_rows` VALUES (71,9,'video','text','Video',0,1,1,1,1,1,'{}',7);
INSERT INTO `data_rows` VALUES (72,10,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (73,10,'title_en','text','Title En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (74,10,'title_ar','text','Title Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (75,10,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',4);
INSERT INTO `data_rows` VALUES (76,10,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',5);
INSERT INTO `data_rows` VALUES (77,13,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (78,13,'title_en','text','Title En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (79,13,'title_ar','text','Title Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (80,13,'text_en','text_area','Text En',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (81,13,'text_ar','text_area','Text Ar',0,1,1,1,1,1,'{}',5);
INSERT INTO `data_rows` VALUES (82,13,'image','image','Image (550 x 700)',0,1,1,1,1,1,'{}',6);
INSERT INTO `data_rows` VALUES (83,13,'day','select_dropdown','Day',0,1,1,1,1,1,'{\"default\":\"option1\",\"options\":{\"option1\":\"Monday\",\"option2\":\"Tuesday\",\"option3\":\"Wednesday\",\"option4\":\"Thursday\",\"option5\":\"Friday\",\"option6\":\"Saturday\",\"option7\":\"Sanday\"}}',7);
INSERT INTO `data_rows` VALUES (84,13,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',8);
INSERT INTO `data_rows` VALUES (85,13,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9);
INSERT INTO `data_rows` VALUES (86,6,'is_receipt','checkbox','Is Receipt',0,1,1,1,1,1,'{}',9);
INSERT INTO `data_rows` VALUES (87,9,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',8);
INSERT INTO `data_rows` VALUES (88,9,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9);
INSERT INTO `data_rows` VALUES (89,14,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (90,14,'name','text','Name',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (91,14,'email','text','Email',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (92,14,'phone','text','Phone',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (93,14,'appointment_date','date','Appointment Date',0,1,1,1,1,1,'{}',5);
INSERT INTO `data_rows` VALUES (94,14,'type_of_service','text','Type Of Service',0,1,1,1,1,1,'{}',6);
INSERT INTO `data_rows` VALUES (95,14,'message','text','Message',0,1,1,1,1,1,'{}',7);
INSERT INTO `data_rows` VALUES (96,14,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',8);
INSERT INTO `data_rows` VALUES (97,14,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',9);
INSERT INTO `data_rows` VALUES (98,13,'meel_type','select_dropdown','Meel Type',0,1,1,1,1,1,'{\"default\":\"option1\",\"options\":{\"option1\":\"Breakfast\",\"option2\":\"Pre Dinner\",\"option3\":\"Dinner\",\"option4\":\"Pre DLunch\",\"option5\":\"Lucnh\"}}',10);
INSERT INTO `data_rows` VALUES (99,15,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (100,15,'name','text','Name',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (101,15,'email','text','Email',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (102,15,'phone','text','Phone',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (103,15,'subject','text','Subject',0,1,1,1,1,1,'{}',5);
INSERT INTO `data_rows` VALUES (104,15,'message','text','Message',0,1,1,1,1,1,'{}',6);
INSERT INTO `data_rows` VALUES (105,15,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',7);
INSERT INTO `data_rows` VALUES (106,15,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',8);
INSERT INTO `data_rows` VALUES (107,16,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (108,16,'title_en','text','Title En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (109,16,'title_ar','text','Title Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (110,16,'text_en','markdown_editor','Text En',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (111,16,'text_ar','markdown_editor','Text Ar',0,1,1,1,1,1,'{}',5);
INSERT INTO `data_rows` VALUES (112,16,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',6);
INSERT INTO `data_rows` VALUES (113,16,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',7);
INSERT INTO `data_rows` VALUES (114,17,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (115,17,'title_en','text','Title En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (116,17,'title_ar','text','Title Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (117,17,'text_en','markdown_editor','Text En',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (118,17,'text_ar','markdown_editor','Text Ar',0,1,1,1,1,1,'{}',5);
INSERT INTO `data_rows` VALUES (119,17,'image','image','Image (1000 x 1000)',0,1,1,1,1,1,'{}',6);
INSERT INTO `data_rows` VALUES (120,17,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',7);
INSERT INTO `data_rows` VALUES (121,17,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',8);
INSERT INTO `data_rows` VALUES (122,18,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (123,18,'title_en','text','Title En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (124,18,'title_ar','text','Title Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (125,18,'image','image','Image',0,1,1,1,1,1,'{}',6);
INSERT INTO `data_rows` VALUES (126,19,'id','text','Id',1,0,0,0,0,0,'{}',1);
INSERT INTO `data_rows` VALUES (127,19,'list_en','text','List En',0,1,1,1,1,1,'{}',2);
INSERT INTO `data_rows` VALUES (128,19,'list_ar','text','List Ar',0,1,1,1,1,1,'{}',3);
INSERT INTO `data_rows` VALUES (129,19,'info_detail_belongsto_info_relationship','relationship','infos',0,1,1,1,1,1,'{\"model\":\"App\\\\Info\",\"table\":\"infos\",\"type\":\"belongsTo\",\"column\":\"info_id\",\"key\":\"id\",\"label\":\"title_en\",\"pivot_table\":\"abouts\",\"pivot\":\"0\",\"taggable\":\"0\"}',4);
INSERT INTO `data_rows` VALUES (130,18,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',5);
INSERT INTO `data_rows` VALUES (131,18,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',6);
INSERT INTO `data_rows` VALUES (132,19,'info_id','text','Info Id',0,1,1,1,1,1,'{}',4);
INSERT INTO `data_rows` VALUES (133,19,'created_at','timestamp','Created At',0,1,1,1,0,1,'{}',5);
INSERT INTO `data_rows` VALUES (134,19,'updated_at','timestamp','Updated At',0,0,0,0,0,0,'{}',6);
/*!40000 ALTER TABLE `data_rows` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `data_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `display_name_singular` varchar(191) NOT NULL,
  `display_name_plural` varchar(191) NOT NULL,
  `icon` varchar(191) DEFAULT NULL,
  `model_name` varchar(191) DEFAULT NULL,
  `policy_name` varchar(191) DEFAULT NULL,
  `controller` varchar(191) DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `generate_permissions` tinyint(1) NOT NULL DEFAULT 0,
  `server_side` tinyint(4) NOT NULL DEFAULT 0,
  `details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_types_name_unique` (`name`),
  UNIQUE KEY `data_types_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `data_types` WRITE;
/*!40000 ALTER TABLE `data_types` DISABLE KEYS */;
INSERT INTO `data_types` VALUES (1,'users','users','User','Users','voyager-person','TCG\\Voyager\\Models\\User','TCG\\Voyager\\Policies\\UserPolicy','TCG\\Voyager\\Http\\Controllers\\VoyagerUserController','',1,0,NULL,'2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `data_types` VALUES (2,'menus','menus','Menu','Menus','voyager-list','TCG\\Voyager\\Models\\Menu',NULL,'','',1,0,NULL,'2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `data_types` VALUES (3,'roles','roles','Role','Roles','voyager-lock','TCG\\Voyager\\Models\\Role',NULL,'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController','',1,0,NULL,'2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `data_types` VALUES (4,'banners','banners','Banner','Banners',NULL,'App\\Banner',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-02-06 08:51:16','2022-03-04 19:48:47');
INSERT INTO `data_types` VALUES (5,'services','services','Service','Services',NULL,'App\\Service',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-02-06 09:05:27','2022-02-10 11:29:22');
INSERT INTO `data_types` VALUES (6,'blogs','blogs','Blog','Blogs',NULL,'App\\Blog',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-02-06 09:21:18','2022-02-13 13:59:25');
INSERT INTO `data_types` VALUES (7,'testimonials','testimonials','Testimonial','Testimonials',NULL,'App\\Testimonial',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}','2022-02-06 09:26:15','2022-02-06 09:26:15');
INSERT INTO `data_types` VALUES (8,'packages','packages','Package','Packages',NULL,'App\\Package',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-02-06 09:46:01','2022-11-06 16:34:39');
INSERT INTO `data_types` VALUES (9,'abouts','abouts','About','Abouts',NULL,'App\\About',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-02-06 10:09:05','2022-02-13 13:43:23');
INSERT INTO `data_types` VALUES (10,'nutrition_special_features','nutrition-special-features','Nutrition Special Feature','Nutrition Special Features',NULL,'App\\NutritionSpecialFeature',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}','2022-02-06 10:10:38','2022-02-06 10:10:38');
INSERT INTO `data_types` VALUES (13,'weekly_diet_plans','weekly-diet-plans','Weekly Diet Plan','Weekly Diet Plans',NULL,'App\\WeeklyDietPlan',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-02-06 10:27:06','2022-02-13 13:44:15');
INSERT INTO `data_types` VALUES (14,'appointments','appointments','Appointment','Appointments',NULL,'App\\Appointment',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}','2022-02-09 06:38:11','2022-02-09 06:38:11');
INSERT INTO `data_types` VALUES (15,'contacts','contacts','Contact','Contacts',NULL,'App\\Contact',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}','2022-02-11 18:29:00','2022-02-11 18:29:00');
INSERT INTO `data_types` VALUES (16,'bad_habits','bad-habits','Bad Habit','Bad Habits',NULL,'App\\BadHabit',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null}','2022-02-23 23:11:50','2022-02-23 23:11:50');
INSERT INTO `data_types` VALUES (17,'results','results','Result','Results',NULL,'App\\Result',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-02-24 00:01:56','2022-11-01 12:08:10');
INSERT INTO `data_types` VALUES (18,'infos','infos','Info','Infos',NULL,'App\\Info',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-03-04 00:08:55','2022-03-04 00:39:03');
INSERT INTO `data_types` VALUES (19,'info_details','info-details','Info Detail','Info Details',NULL,'App\\InfoDetail',NULL,NULL,NULL,1,0,'{\"order_column\":null,\"order_display_column\":null,\"order_direction\":\"asc\",\"default_search_key\":null,\"scope\":null}','2022-03-04 00:19:19','2022-03-04 00:43:01');
/*!40000 ALTER TABLE `data_types` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `info_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `info_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `list_en` text DEFAULT NULL,
  `list_ar` text DEFAULT NULL,
  `info_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `info_details` WRITE;
/*!40000 ALTER TABLE `info_details` DISABLE KEYS */;
INSERT INTO `info_details` VALUES (3,'Improve your lifestyle step-by-step, to reach a healthy body.','تحسين أسلوب حياتك خطوة بخطوة، للوصول إلى جسم صحي.',6,'2022-03-04 03:41:03','2022-03-04 03:41:03');
INSERT INTO `info_details` VALUES (4,'Get rid of restrictive diets and food deprivation.','التخلص من الأنظمة القاسية والحرمان من الأكل.',6,'2022-03-04 03:41:25','2022-03-04 03:41:25');
INSERT INTO `info_details` VALUES (5,'Deal with hunger and persistent appetite.','علاج مشكلة الجوع والشهية المستمرة.',6,'2022-03-04 03:41:49','2022-03-04 03:41:49');
INSERT INTO `info_details` VALUES (6,'Eat in moderation, regardless the food type.','تناول الطعام باعتدال مهما كان نوعه من دون أي قيود أو حرمان.',6,'2022-03-04 03:42:10','2022-03-04 03:42:10');
INSERT INTO `info_details` VALUES (7,'Enjoy the food you love without feeling guilty.','الاستمتاع بالطعام الذي تحبه من دون الإحساس بالذنب.',6,'2022-03-04 03:42:30','2022-03-04 03:42:30');
INSERT INTO `info_details` VALUES (8,'Exercise properly to reach your goal.','ممارسة الرياضة بشكل صحيح للوصول الى هدفك.',6,'2022-03-04 03:42:52','2022-03-04 03:42:52');
INSERT INTO `info_details` VALUES (9,'Maintain your weight forever.','المحافظة على وزنك للأبد.',6,'2022-03-04 03:43:00','2022-03-04 03:45:49');
INSERT INTO `info_details` VALUES (10,'If you want to lose and maintain your weight.','إذا كنت تريد خسارة وزنك والمحافظة عليه.',7,'2022-03-04 03:43:00','2022-03-04 03:45:23');
INSERT INTO `info_details` VALUES (11,'If you want to gain weight in a healthy way.','إذا كنت ترغب في زيادة وزنك بطريقة صحية.',7,'2022-03-04 03:43:42','2022-03-04 03:43:42');
INSERT INTO `info_details` VALUES (12,'If you are pregnant or breastfeeding.','المرأة الحامل أو المرضعة.',7,'2022-03-04 03:44:02','2022-03-04 03:44:02');
INSERT INTO `info_details` VALUES (13,'Athletes who want to build their muscles and maintain high sport performance.','الرياضيون الذين يريدون بناء عضلاتهم والمحافظة على أداء رياضي عالٍ.',7,'2022-03-04 03:44:22','2022-03-04 03:44:22');
INSERT INTO `info_details` VALUES (14,'If you suffer from health conditions such as diabetes, high blood pressure, or cholesterol…','إذا كنت تعاني من المشاكل الصحية مثل السكر أو الكوليسترول أو الضغط أو غيرها…',7,'2022-03-04 03:44:46','2022-03-04 03:44:46');
INSERT INTO `info_details` VALUES (15,'If you have undergone a bariatric surgery to lose weight.','إذا كنت قد خضعت لإحدى عمليات السمنة لخسارة الوزن.',7,'2022-03-04 03:45:07','2022-03-04 03:45:07');
/*!40000 ALTER TABLE `info_details` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `infos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `infos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` text DEFAULT NULL,
  `title_ar` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `infos` WRITE;
/*!40000 ALTER TABLE `infos` DISABLE KEYS */;
INSERT INTO `infos` VALUES (6,'How do you benefit from a healthy lifestyle?','كيف تستفيد من اتّباع أسلوب حياة صحي؟',NULL,'2022-03-03 18:43:00','2022-03-04 03:39:41');
INSERT INTO `infos` VALUES (7,'Who benefits from this program?','من يستفيد من هذا النظام؟',NULL,'2022-03-04 03:40:12','2022-03-04 03:40:12');
INSERT INTO `infos` VALUES (9,'My Goal :The healthy lifestyle that I live is the same that I intend to send to all of my clients because I feel comfortable with my body and I eat from everything in moderation and I do exercise too. So welcome to my healthy world!','شغفي و هدفي الاساسي:  نمط حياة الصحي الذي أعيشه هو نفسه الذي أحاول نقله لكل الأشخاص حيث أنني أشعر بارتياح تام مع جسمي وأتناول من كل الأغذية باعتدال وأمارس التمارين الرياضية. فأهلاً وسهلاً بكم في عالمي الصحي',NULL,'2023-01-06 15:37:00','2023-01-06 15:46:23');
/*!40000 ALTER TABLE `infos` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `menu_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` int(10) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `target` varchar(255) NOT NULL DEFAULT '_self',
  `icon_class` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `parameters` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_id_foreign` (`menu_id`),
  CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `menu_items` WRITE;
/*!40000 ALTER TABLE `menu_items` DISABLE KEYS */;
INSERT INTO `menu_items` VALUES (1,1,'Dashboard','','_self','voyager-boat',NULL,NULL,1,'2022-02-06 08:36:49','2022-02-06 08:36:49','voyager.dashboard',NULL);
INSERT INTO `menu_items` VALUES (2,1,'Media','','_self','voyager-images',NULL,NULL,5,'2022-02-06 08:36:49','2022-02-06 08:36:49','voyager.media.index',NULL);
INSERT INTO `menu_items` VALUES (3,1,'Users','','_self','voyager-person',NULL,NULL,3,'2022-02-06 08:36:49','2022-02-06 08:36:49','voyager.users.index',NULL);
INSERT INTO `menu_items` VALUES (4,1,'Roles','','_self','voyager-lock',NULL,NULL,2,'2022-02-06 08:36:49','2022-02-06 08:36:49','voyager.roles.index',NULL);
INSERT INTO `menu_items` VALUES (5,1,'Tools','','_self','voyager-tools',NULL,NULL,9,'2022-02-06 08:36:49','2022-02-06 08:36:49',NULL,NULL);
INSERT INTO `menu_items` VALUES (6,1,'Menu Builder','','_self','voyager-list',NULL,5,10,'2022-02-06 08:36:49','2022-02-06 08:36:49','voyager.menus.index',NULL);
INSERT INTO `menu_items` VALUES (7,1,'Database','','_self','voyager-data',NULL,5,11,'2022-02-06 08:36:49','2022-02-06 08:36:49','voyager.database.index',NULL);
INSERT INTO `menu_items` VALUES (8,1,'Compass','','_self','voyager-compass',NULL,5,12,'2022-02-06 08:36:49','2022-02-06 08:36:49','voyager.compass.index',NULL);
INSERT INTO `menu_items` VALUES (9,1,'BREAD','','_self','voyager-bread',NULL,5,13,'2022-02-06 08:36:49','2022-02-06 08:36:49','voyager.bread.index',NULL);
INSERT INTO `menu_items` VALUES (10,1,'Settings','','_self','voyager-settings',NULL,NULL,14,'2022-02-06 08:36:49','2022-02-06 08:36:49','voyager.settings.index',NULL);
INSERT INTO `menu_items` VALUES (11,1,'Banners','','_self',NULL,NULL,NULL,15,'2022-02-06 08:51:16','2022-02-06 08:51:16','voyager.banners.index',NULL);
INSERT INTO `menu_items` VALUES (12,1,'Services','','_self',NULL,NULL,NULL,16,'2022-02-06 09:05:27','2022-02-06 09:05:27','voyager.services.index',NULL);
INSERT INTO `menu_items` VALUES (13,1,'Blogs','','_self',NULL,NULL,NULL,17,'2022-02-06 09:21:18','2022-02-06 09:21:18','voyager.blogs.index',NULL);
INSERT INTO `menu_items` VALUES (14,1,'Testimonials','','_self',NULL,NULL,NULL,18,'2022-02-06 09:26:15','2022-02-06 09:26:15','voyager.testimonials.index',NULL);
INSERT INTO `menu_items` VALUES (15,1,'Packages','','_self',NULL,NULL,NULL,19,'2022-02-06 09:46:01','2022-02-06 09:46:01','voyager.packages.index',NULL);
INSERT INTO `menu_items` VALUES (16,1,'Abouts','','_self',NULL,NULL,NULL,20,'2022-02-06 10:09:05','2022-02-06 10:09:05','voyager.abouts.index',NULL);
INSERT INTO `menu_items` VALUES (17,1,'Nutrition Special Features','','_self',NULL,NULL,NULL,21,'2022-02-06 10:10:38','2022-02-06 10:10:38','voyager.nutrition-special-features.index',NULL);
INSERT INTO `menu_items` VALUES (18,1,'Weekly Diet Plans','','_self',NULL,NULL,NULL,22,'2022-02-06 10:27:06','2022-02-06 10:27:06','voyager.weekly-diet-plans.index',NULL);
INSERT INTO `menu_items` VALUES (19,1,'Appointments','','_self',NULL,NULL,NULL,23,'2022-02-09 06:38:11','2022-02-09 06:38:11','voyager.appointments.index',NULL);
INSERT INTO `menu_items` VALUES (20,1,'Contacts','','_self',NULL,NULL,NULL,24,'2022-02-11 18:29:00','2022-02-11 18:29:00','voyager.contacts.index',NULL);
INSERT INTO `menu_items` VALUES (21,1,'Bad Habits','','_self',NULL,NULL,NULL,25,'2022-02-23 23:11:50','2022-02-23 23:11:50','voyager.bad-habits.index',NULL);
INSERT INTO `menu_items` VALUES (22,1,'Results','','_self',NULL,NULL,NULL,26,'2022-02-24 00:01:56','2022-02-24 00:01:56','voyager.results.index',NULL);
INSERT INTO `menu_items` VALUES (23,1,'Infos','','_self',NULL,NULL,NULL,27,'2022-03-04 00:08:55','2022-03-04 00:08:55','voyager.infos.index',NULL);
INSERT INTO `menu_items` VALUES (24,1,'Info Details','','_self',NULL,NULL,NULL,28,'2022-03-04 00:19:19','2022-03-04 00:19:19','voyager.info-details.index',NULL);
/*!40000 ALTER TABLE `menu_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'admin','2022-02-06 08:36:49','2022-02-06 08:36:49');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` VALUES (2,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` VALUES (3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` VALUES (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` VALUES (5,'2016_01_01_000000_add_voyager_user_fields',2);
INSERT INTO `migrations` VALUES (6,'2016_01_01_000000_create_data_types_table',2);
INSERT INTO `migrations` VALUES (7,'2016_05_19_173453_create_menu_table',2);
INSERT INTO `migrations` VALUES (8,'2016_10_21_190000_create_roles_table',2);
INSERT INTO `migrations` VALUES (9,'2016_10_21_190000_create_settings_table',2);
INSERT INTO `migrations` VALUES (10,'2016_11_30_135954_create_permission_table',2);
INSERT INTO `migrations` VALUES (11,'2016_11_30_141208_create_permission_role_table',2);
INSERT INTO `migrations` VALUES (12,'2016_12_26_201236_data_types__add__server_side',2);
INSERT INTO `migrations` VALUES (13,'2017_01_13_000000_add_route_to_menu_items_table',2);
INSERT INTO `migrations` VALUES (14,'2017_01_14_005015_create_translations_table',2);
INSERT INTO `migrations` VALUES (15,'2017_01_15_000000_make_table_name_nullable_in_permissions_table',2);
INSERT INTO `migrations` VALUES (16,'2017_03_06_000000_add_controller_to_data_types_table',2);
INSERT INTO `migrations` VALUES (17,'2017_04_21_000000_add_order_to_data_rows_table',2);
INSERT INTO `migrations` VALUES (18,'2017_07_05_210000_add_policyname_to_data_types_table',2);
INSERT INTO `migrations` VALUES (19,'2017_08_05_000000_add_group_to_settings_table',2);
INSERT INTO `migrations` VALUES (20,'2017_11_26_013050_add_user_role_relationship',2);
INSERT INTO `migrations` VALUES (21,'2017_11_26_015000_create_user_roles_table',2);
INSERT INTO `migrations` VALUES (22,'2018_03_11_000000_add_user_settings',2);
INSERT INTO `migrations` VALUES (23,'2018_03_14_000000_add_details_to_data_types_table',2);
INSERT INTO `migrations` VALUES (24,'2018_03_16_000000_make_settings_value_nullable',2);
INSERT INTO `migrations` VALUES (25,'2026_10_31_000000_add_expires_at_to_personal_access_tokens_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `nutrition_special_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `nutrition_special_features` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) DEFAULT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `nutrition_special_features` WRITE;
/*!40000 ALTER TABLE `nutrition_special_features` DISABLE KEYS */;
/*!40000 ALTER TABLE `nutrition_special_features` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `packages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name_en` varchar(255) DEFAULT NULL,
  `name_ar` varchar(255) DEFAULT NULL,
  `features_en` text DEFAULT NULL,
  `features_ar` text DEFAULT NULL,
  `price` float DEFAULT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES (6,'Monthly Package','باقة شهرية','<li><i class=\"bx bx-check\"></i> A diet tailored to your goal and not tied to specific meals</li> \r\n <li><i class=\"bx bx-check\"></i> You learn how to make your meals balanced, satisfying, and exemplary</li>\r\n<li><i class=\"bx bx-check\"></i>Tips and goals for a month</li>\r\n<li><i class=\"bx bx-check\"></i> Two calls a month via WhatsApp or Zoom</li>\r\n <li><i class=\"bx bx-check\"></i> Contact once a week via WhatsApp for questions and inquiries</li>','من خلال هذه الخدمة سوف يتم التواصل معك مرتين بالشهر و من خلال هذه الخدمة سوف تتعلم التغذية المتوازنة التي ستساعد بخسارة وزن بطريقة صحية و التركيز على تغذية الجسم\r\n<li><i class=\"bx bx-check\"></i>نظام غذائي مصمم خصيصا لهدفك و غير مرتبط بوجبات محددة  </li>\r\n<li><i class=\"bx bx-check\"></i> تتعلم كيفية تكوين وجباتك متوازنة و مرضية و مثال عن الطعام </li>\r\n<li><i class=\"bx bx-check\"></i> نصائح و اهداف لمدة شهر </li>\r\n<li><i class=\"bx bx-check\"></i> مكالمتين بالشهر عبر واتساب او زوم </li>\r\n<li><i class=\"bx bx-check\"></i>تواصل مرة في الاسبوع عبر الوانساب للاسئلة و الاستفسار </li>\r\n<li><i class=\"bx bx-check\"></i> تقييم النتيجة بعيدا عن هوس الميزان </li>',60,NULL,'2022-02-23 21:59:00','2024-12-20 14:22:02');
INSERT INTO `packages` VALUES (7,'Nutritional Advice','استشارة غذائية','Through this service, you get one consultation with Laila, and they talk about your health and nutritional status, and the duration of the consultation is 50-60 minutes\r\n\r\nWhat does this package provide?\r\n\r\n<li><i class=\"bx bx-check\"></i>A diet tailored to your goal and not tied to specific meals\r\n<li><i class=\"bx bx-check\"></i>Tips and goals for you\r\n<li><i class=\"bx bx-check\"></i>How to improve your relationship with food and your body\r\n<li><i class=\"bx bx-check\"></i>The best way to get rid of calorie counting and balance obsession','من خلال هذه الخدمة تحصل على استشارة واحدة مع ليلى ، ويتحدثون عن حالتك الصحية والغذائية ، ومدة الاستشارة 50-60 دقيقة\r\n\r\nماذا تقدم هذه الحدمة؟\r\n\r\n<li><i class=\"bx bx-check\"></i>نظام غذائي مصمم خصيصًا لهدفك وغير مرتبط بوجبات محددة\r\n<li><i class=\"bx bx-check\"></i>نصائح وأهداف لك\r\n<li><i class=\"bx bx-check\"></i>كيفية تحسين علاقتك بالطعام وجسمك\r\n<li><i class=\"bx bx-check\"></i>أفضل طريقة للتخلص من هوس عد السعرات الحرارية والتوازن',40,NULL,'2022-03-15 19:29:00','2024-12-20 14:19:27');
INSERT INTO `packages` VALUES (8,'Balanced Nutritional Program For 3 Months','برنامج التغذية المتوازنة مدته 3 اشهر','Welcome the year 2023 with a healthy life free of diet and restrictions\r\nThis program will take you on a new journey to achieve your goals without having to follow a 3-month paper\r\n\r\nIn this balanced program you will learn\r\n\r\n<li><i class=\"bx bx-check\"></i> Discover the skills you need to develop healthy habits that you can maintain for life\r\n<li><i class=\"bx bx-check\"></i> Learn how to follow a diet tailored to your goal, free from specific meals, and tailored to your lifestyle.\r\n<li><i class=\"bx bx-check\"></i> Learn how to create balanced, satisfying meals with example food\r\n<li><i class=\"bx bx-check\"></i> Feel comfortable with your body and break free from scale obsession.\r\n<li><i class=\"bx bx-check\"></i> Trust and listen to your body naturally\r\n<li><i class=\"bx bx-check\"></i> Get rid of the calorie mania\r\n<li><i class=\"bx bx-check\"></i> Feel completely confident in your food choices\r\n<li><i class=\"bx bx-check\"></i> Get motivation and support through WhatsApp Group\r\n<li><i class=\"bx bx-check\"></i> Set personal goals\r\nThis program includes 6 sessions via video / communication with the nutritionist and weekly follow-up via WhatsApp','استقبل سنة 2023 بحياة صحية خالية من الدايت و القيود \r\nهذا البرنامج سوف ياخذك الى رحلة جديدة لتحقيق ادافك من دون اللجوء لاتباع ورقة مدته 3 اشهر \r\n\r\nفي هذا البرناج المتوازن سوف تتعلم \r\n\r\n<li><i class=\"bx bx-check\"></i>  اكتشاف المهارات التي تحتاجها لتطوير عادات صحية و يمكنك الحفاظ عليها مدى الحياة \r\n<li><i class=\"bx bx-check\"></i> تتعلم كيفية اتباع نظام غذائي مخصص حسب هدفك و غير مرتبط بوجبات محددة و يلائم نمط حياتك\r\n<li><i class=\"bx bx-check\"></i> تتعلم كيفية تكوين وجباتك متوازنة و مرضية و مثال عن الطعام\r\n<li><i class=\"bx bx-check\"></i>تشعر براحة مع جسمك و تتخلص من هوس الميزان \r\n<li><i class=\"bx bx-check\"></i> الثقة و الاستماع لاشارات جسمك بشكل طبيعي \r\n<li><i class=\"bx bx-check\"></i> تتخلص من هوس السعرات الحرارية \r\n<li><i class=\"bx bx-check\"></i>  تشعر بالثقة التامة في اختياراتك الغذائية\r\n<li><i class=\"bx bx-check\"></i> الحصول على تحفيز و دعم يوميا عبر الواتساب \r\n	 \r\n		\r\n		 \r\n \r\n <li><i class=\"bx bx-check\"></i> يتضمن هذا البرنامج 6 جلسات عبر فيديو / اتصال مع اختصاصية التغذية و متابعة اسوعية عبر واتساب \r\n			من خلال هذا البرنامج شوف تصل لاهدافك الواقعية و الحرية الغذائية ما يعني تصبح اخصائي نفسك من دون وجود قواعد غذائية',160,NULL,'2022-03-15 19:29:00','2024-12-20 14:05:55');
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission_role` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_permission_id_index` (`permission_id`),
  KEY `permission_role_role_id_index` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `permission_role` WRITE;
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
INSERT INTO `permission_role` VALUES (1,1);
INSERT INTO `permission_role` VALUES (1,2);
INSERT INTO `permission_role` VALUES (2,1);
INSERT INTO `permission_role` VALUES (3,1);
INSERT INTO `permission_role` VALUES (4,1);
INSERT INTO `permission_role` VALUES (4,2);
INSERT INTO `permission_role` VALUES (5,1);
INSERT INTO `permission_role` VALUES (6,1);
INSERT INTO `permission_role` VALUES (7,1);
INSERT INTO `permission_role` VALUES (8,1);
INSERT INTO `permission_role` VALUES (9,1);
INSERT INTO `permission_role` VALUES (10,1);
INSERT INTO `permission_role` VALUES (11,1);
INSERT INTO `permission_role` VALUES (12,1);
INSERT INTO `permission_role` VALUES (13,1);
INSERT INTO `permission_role` VALUES (14,1);
INSERT INTO `permission_role` VALUES (15,1);
INSERT INTO `permission_role` VALUES (16,1);
INSERT INTO `permission_role` VALUES (16,2);
INSERT INTO `permission_role` VALUES (17,1);
INSERT INTO `permission_role` VALUES (17,2);
INSERT INTO `permission_role` VALUES (18,1);
INSERT INTO `permission_role` VALUES (18,2);
INSERT INTO `permission_role` VALUES (19,1);
INSERT INTO `permission_role` VALUES (19,2);
INSERT INTO `permission_role` VALUES (20,1);
INSERT INTO `permission_role` VALUES (20,2);
INSERT INTO `permission_role` VALUES (21,1);
INSERT INTO `permission_role` VALUES (22,1);
INSERT INTO `permission_role` VALUES (23,1);
INSERT INTO `permission_role` VALUES (24,1);
INSERT INTO `permission_role` VALUES (25,1);
INSERT INTO `permission_role` VALUES (26,1);
INSERT INTO `permission_role` VALUES (26,2);
INSERT INTO `permission_role` VALUES (27,1);
INSERT INTO `permission_role` VALUES (27,2);
INSERT INTO `permission_role` VALUES (28,1);
INSERT INTO `permission_role` VALUES (28,2);
INSERT INTO `permission_role` VALUES (29,1);
INSERT INTO `permission_role` VALUES (29,2);
INSERT INTO `permission_role` VALUES (30,1);
INSERT INTO `permission_role` VALUES (30,2);
INSERT INTO `permission_role` VALUES (31,1);
INSERT INTO `permission_role` VALUES (31,2);
INSERT INTO `permission_role` VALUES (32,1);
INSERT INTO `permission_role` VALUES (32,2);
INSERT INTO `permission_role` VALUES (33,1);
INSERT INTO `permission_role` VALUES (33,2);
INSERT INTO `permission_role` VALUES (34,1);
INSERT INTO `permission_role` VALUES (34,2);
INSERT INTO `permission_role` VALUES (35,1);
INSERT INTO `permission_role` VALUES (35,2);
INSERT INTO `permission_role` VALUES (36,1);
INSERT INTO `permission_role` VALUES (36,2);
INSERT INTO `permission_role` VALUES (37,1);
INSERT INTO `permission_role` VALUES (37,2);
INSERT INTO `permission_role` VALUES (38,1);
INSERT INTO `permission_role` VALUES (38,2);
INSERT INTO `permission_role` VALUES (39,1);
INSERT INTO `permission_role` VALUES (39,2);
INSERT INTO `permission_role` VALUES (40,1);
INSERT INTO `permission_role` VALUES (40,2);
INSERT INTO `permission_role` VALUES (41,1);
INSERT INTO `permission_role` VALUES (41,2);
INSERT INTO `permission_role` VALUES (42,1);
INSERT INTO `permission_role` VALUES (42,2);
INSERT INTO `permission_role` VALUES (43,1);
INSERT INTO `permission_role` VALUES (43,2);
INSERT INTO `permission_role` VALUES (44,1);
INSERT INTO `permission_role` VALUES (44,2);
INSERT INTO `permission_role` VALUES (45,1);
INSERT INTO `permission_role` VALUES (45,2);
INSERT INTO `permission_role` VALUES (46,1);
INSERT INTO `permission_role` VALUES (46,2);
INSERT INTO `permission_role` VALUES (47,1);
INSERT INTO `permission_role` VALUES (47,2);
INSERT INTO `permission_role` VALUES (48,1);
INSERT INTO `permission_role` VALUES (48,2);
INSERT INTO `permission_role` VALUES (49,1);
INSERT INTO `permission_role` VALUES (49,2);
INSERT INTO `permission_role` VALUES (50,1);
INSERT INTO `permission_role` VALUES (50,2);
INSERT INTO `permission_role` VALUES (51,1);
INSERT INTO `permission_role` VALUES (51,2);
INSERT INTO `permission_role` VALUES (52,1);
INSERT INTO `permission_role` VALUES (52,2);
INSERT INTO `permission_role` VALUES (53,1);
INSERT INTO `permission_role` VALUES (53,2);
INSERT INTO `permission_role` VALUES (54,1);
INSERT INTO `permission_role` VALUES (54,2);
INSERT INTO `permission_role` VALUES (55,1);
INSERT INTO `permission_role` VALUES (55,2);
INSERT INTO `permission_role` VALUES (56,1);
INSERT INTO `permission_role` VALUES (56,2);
INSERT INTO `permission_role` VALUES (57,1);
INSERT INTO `permission_role` VALUES (57,2);
INSERT INTO `permission_role` VALUES (58,1);
INSERT INTO `permission_role` VALUES (58,2);
INSERT INTO `permission_role` VALUES (59,1);
INSERT INTO `permission_role` VALUES (59,2);
INSERT INTO `permission_role` VALUES (60,1);
INSERT INTO `permission_role` VALUES (60,2);
INSERT INTO `permission_role` VALUES (61,1);
INSERT INTO `permission_role` VALUES (61,2);
INSERT INTO `permission_role` VALUES (62,1);
INSERT INTO `permission_role` VALUES (62,2);
INSERT INTO `permission_role` VALUES (63,1);
INSERT INTO `permission_role` VALUES (63,2);
INSERT INTO `permission_role` VALUES (64,1);
INSERT INTO `permission_role` VALUES (64,2);
INSERT INTO `permission_role` VALUES (65,1);
INSERT INTO `permission_role` VALUES (65,2);
INSERT INTO `permission_role` VALUES (66,1);
INSERT INTO `permission_role` VALUES (66,2);
INSERT INTO `permission_role` VALUES (67,1);
INSERT INTO `permission_role` VALUES (67,2);
INSERT INTO `permission_role` VALUES (68,1);
INSERT INTO `permission_role` VALUES (68,2);
INSERT INTO `permission_role` VALUES (69,1);
INSERT INTO `permission_role` VALUES (69,2);
INSERT INTO `permission_role` VALUES (70,1);
INSERT INTO `permission_role` VALUES (70,2);
INSERT INTO `permission_role` VALUES (71,1);
INSERT INTO `permission_role` VALUES (71,2);
INSERT INTO `permission_role` VALUES (72,1);
INSERT INTO `permission_role` VALUES (72,2);
INSERT INTO `permission_role` VALUES (73,1);
INSERT INTO `permission_role` VALUES (73,2);
INSERT INTO `permission_role` VALUES (74,1);
INSERT INTO `permission_role` VALUES (74,2);
INSERT INTO `permission_role` VALUES (75,1);
INSERT INTO `permission_role` VALUES (75,2);
INSERT INTO `permission_role` VALUES (76,1);
INSERT INTO `permission_role` VALUES (76,2);
INSERT INTO `permission_role` VALUES (77,1);
INSERT INTO `permission_role` VALUES (77,2);
INSERT INTO `permission_role` VALUES (78,1);
INSERT INTO `permission_role` VALUES (78,2);
INSERT INTO `permission_role` VALUES (79,1);
INSERT INTO `permission_role` VALUES (79,2);
INSERT INTO `permission_role` VALUES (80,1);
INSERT INTO `permission_role` VALUES (80,2);
INSERT INTO `permission_role` VALUES (81,1);
INSERT INTO `permission_role` VALUES (81,2);
INSERT INTO `permission_role` VALUES (82,1);
INSERT INTO `permission_role` VALUES (82,2);
INSERT INTO `permission_role` VALUES (83,1);
INSERT INTO `permission_role` VALUES (83,2);
INSERT INTO `permission_role` VALUES (84,1);
INSERT INTO `permission_role` VALUES (84,2);
INSERT INTO `permission_role` VALUES (85,1);
INSERT INTO `permission_role` VALUES (85,2);
INSERT INTO `permission_role` VALUES (86,1);
INSERT INTO `permission_role` VALUES (86,2);
INSERT INTO `permission_role` VALUES (87,1);
INSERT INTO `permission_role` VALUES (87,2);
INSERT INTO `permission_role` VALUES (88,1);
INSERT INTO `permission_role` VALUES (88,2);
INSERT INTO `permission_role` VALUES (89,1);
INSERT INTO `permission_role` VALUES (89,2);
INSERT INTO `permission_role` VALUES (90,1);
INSERT INTO `permission_role` VALUES (90,2);
INSERT INTO `permission_role` VALUES (91,1);
INSERT INTO `permission_role` VALUES (91,2);
INSERT INTO `permission_role` VALUES (92,1);
INSERT INTO `permission_role` VALUES (92,2);
INSERT INTO `permission_role` VALUES (93,1);
INSERT INTO `permission_role` VALUES (93,2);
INSERT INTO `permission_role` VALUES (94,1);
INSERT INTO `permission_role` VALUES (94,2);
INSERT INTO `permission_role` VALUES (95,1);
INSERT INTO `permission_role` VALUES (95,2);
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_key_index` (`key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'browse_admin',NULL,'2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (2,'browse_bread',NULL,'2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (3,'browse_database',NULL,'2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (4,'browse_media',NULL,'2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (5,'browse_compass',NULL,'2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (6,'browse_menus','menus','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (7,'read_menus','menus','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (8,'edit_menus','menus','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (9,'add_menus','menus','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (10,'delete_menus','menus','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (11,'browse_roles','roles','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (12,'read_roles','roles','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (13,'edit_roles','roles','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (14,'add_roles','roles','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (15,'delete_roles','roles','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (16,'browse_users','users','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (17,'read_users','users','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (18,'edit_users','users','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (19,'add_users','users','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (20,'delete_users','users','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (21,'browse_settings','settings','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (22,'read_settings','settings','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (23,'edit_settings','settings','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (24,'add_settings','settings','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (25,'delete_settings','settings','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `permissions` VALUES (26,'browse_banners','banners','2022-02-06 08:51:16','2022-02-06 08:51:16');
INSERT INTO `permissions` VALUES (27,'read_banners','banners','2022-02-06 08:51:16','2022-02-06 08:51:16');
INSERT INTO `permissions` VALUES (28,'edit_banners','banners','2022-02-06 08:51:16','2022-02-06 08:51:16');
INSERT INTO `permissions` VALUES (29,'add_banners','banners','2022-02-06 08:51:16','2022-02-06 08:51:16');
INSERT INTO `permissions` VALUES (30,'delete_banners','banners','2022-02-06 08:51:16','2022-02-06 08:51:16');
INSERT INTO `permissions` VALUES (31,'browse_services','services','2022-02-06 09:05:27','2022-02-06 09:05:27');
INSERT INTO `permissions` VALUES (32,'read_services','services','2022-02-06 09:05:27','2022-02-06 09:05:27');
INSERT INTO `permissions` VALUES (33,'edit_services','services','2022-02-06 09:05:27','2022-02-06 09:05:27');
INSERT INTO `permissions` VALUES (34,'add_services','services','2022-02-06 09:05:27','2022-02-06 09:05:27');
INSERT INTO `permissions` VALUES (35,'delete_services','services','2022-02-06 09:05:27','2022-02-06 09:05:27');
INSERT INTO `permissions` VALUES (36,'browse_blogs','blogs','2022-02-06 09:21:18','2022-02-06 09:21:18');
INSERT INTO `permissions` VALUES (37,'read_blogs','blogs','2022-02-06 09:21:18','2022-02-06 09:21:18');
INSERT INTO `permissions` VALUES (38,'edit_blogs','blogs','2022-02-06 09:21:18','2022-02-06 09:21:18');
INSERT INTO `permissions` VALUES (39,'add_blogs','blogs','2022-02-06 09:21:18','2022-02-06 09:21:18');
INSERT INTO `permissions` VALUES (40,'delete_blogs','blogs','2022-02-06 09:21:18','2022-02-06 09:21:18');
INSERT INTO `permissions` VALUES (41,'browse_testimonials','testimonials','2022-02-06 09:26:15','2022-02-06 09:26:15');
INSERT INTO `permissions` VALUES (42,'read_testimonials','testimonials','2022-02-06 09:26:15','2022-02-06 09:26:15');
INSERT INTO `permissions` VALUES (43,'edit_testimonials','testimonials','2022-02-06 09:26:15','2022-02-06 09:26:15');
INSERT INTO `permissions` VALUES (44,'add_testimonials','testimonials','2022-02-06 09:26:15','2022-02-06 09:26:15');
INSERT INTO `permissions` VALUES (45,'delete_testimonials','testimonials','2022-02-06 09:26:15','2022-02-06 09:26:15');
INSERT INTO `permissions` VALUES (46,'browse_packages','packages','2022-02-06 09:46:01','2022-02-06 09:46:01');
INSERT INTO `permissions` VALUES (47,'read_packages','packages','2022-02-06 09:46:01','2022-02-06 09:46:01');
INSERT INTO `permissions` VALUES (48,'edit_packages','packages','2022-02-06 09:46:01','2022-02-06 09:46:01');
INSERT INTO `permissions` VALUES (49,'add_packages','packages','2022-02-06 09:46:01','2022-02-06 09:46:01');
INSERT INTO `permissions` VALUES (50,'delete_packages','packages','2022-02-06 09:46:01','2022-02-06 09:46:01');
INSERT INTO `permissions` VALUES (51,'browse_abouts','abouts','2022-02-06 10:09:05','2022-02-06 10:09:05');
INSERT INTO `permissions` VALUES (52,'read_abouts','abouts','2022-02-06 10:09:05','2022-02-06 10:09:05');
INSERT INTO `permissions` VALUES (53,'edit_abouts','abouts','2022-02-06 10:09:05','2022-02-06 10:09:05');
INSERT INTO `permissions` VALUES (54,'add_abouts','abouts','2022-02-06 10:09:05','2022-02-06 10:09:05');
INSERT INTO `permissions` VALUES (55,'delete_abouts','abouts','2022-02-06 10:09:05','2022-02-06 10:09:05');
INSERT INTO `permissions` VALUES (56,'browse_nutrition_special_features','nutrition_special_features','2022-02-06 10:10:38','2022-02-06 10:10:38');
INSERT INTO `permissions` VALUES (57,'read_nutrition_special_features','nutrition_special_features','2022-02-06 10:10:38','2022-02-06 10:10:38');
INSERT INTO `permissions` VALUES (58,'edit_nutrition_special_features','nutrition_special_features','2022-02-06 10:10:38','2022-02-06 10:10:38');
INSERT INTO `permissions` VALUES (59,'add_nutrition_special_features','nutrition_special_features','2022-02-06 10:10:38','2022-02-06 10:10:38');
INSERT INTO `permissions` VALUES (60,'delete_nutrition_special_features','nutrition_special_features','2022-02-06 10:10:38','2022-02-06 10:10:38');
INSERT INTO `permissions` VALUES (61,'browse_weekly_diet_plans','weekly_diet_plans','2022-02-06 10:27:06','2022-02-06 10:27:06');
INSERT INTO `permissions` VALUES (62,'read_weekly_diet_plans','weekly_diet_plans','2022-02-06 10:27:06','2022-02-06 10:27:06');
INSERT INTO `permissions` VALUES (63,'edit_weekly_diet_plans','weekly_diet_plans','2022-02-06 10:27:06','2022-02-06 10:27:06');
INSERT INTO `permissions` VALUES (64,'add_weekly_diet_plans','weekly_diet_plans','2022-02-06 10:27:06','2022-02-06 10:27:06');
INSERT INTO `permissions` VALUES (65,'delete_weekly_diet_plans','weekly_diet_plans','2022-02-06 10:27:06','2022-02-06 10:27:06');
INSERT INTO `permissions` VALUES (66,'browse_appointments','appointments','2022-02-09 06:38:11','2022-02-09 06:38:11');
INSERT INTO `permissions` VALUES (67,'read_appointments','appointments','2022-02-09 06:38:11','2022-02-09 06:38:11');
INSERT INTO `permissions` VALUES (68,'edit_appointments','appointments','2022-02-09 06:38:11','2022-02-09 06:38:11');
INSERT INTO `permissions` VALUES (69,'add_appointments','appointments','2022-02-09 06:38:11','2022-02-09 06:38:11');
INSERT INTO `permissions` VALUES (70,'delete_appointments','appointments','2022-02-09 06:38:11','2022-02-09 06:38:11');
INSERT INTO `permissions` VALUES (71,'browse_contacts','contacts','2022-02-11 18:29:00','2022-02-11 18:29:00');
INSERT INTO `permissions` VALUES (72,'read_contacts','contacts','2022-02-11 18:29:00','2022-02-11 18:29:00');
INSERT INTO `permissions` VALUES (73,'edit_contacts','contacts','2022-02-11 18:29:00','2022-02-11 18:29:00');
INSERT INTO `permissions` VALUES (74,'add_contacts','contacts','2022-02-11 18:29:00','2022-02-11 18:29:00');
INSERT INTO `permissions` VALUES (75,'delete_contacts','contacts','2022-02-11 18:29:00','2022-02-11 18:29:00');
INSERT INTO `permissions` VALUES (76,'browse_bad_habits','bad_habits','2022-02-23 23:11:50','2022-02-23 23:11:50');
INSERT INTO `permissions` VALUES (77,'read_bad_habits','bad_habits','2022-02-23 23:11:50','2022-02-23 23:11:50');
INSERT INTO `permissions` VALUES (78,'edit_bad_habits','bad_habits','2022-02-23 23:11:50','2022-02-23 23:11:50');
INSERT INTO `permissions` VALUES (79,'add_bad_habits','bad_habits','2022-02-23 23:11:50','2022-02-23 23:11:50');
INSERT INTO `permissions` VALUES (80,'delete_bad_habits','bad_habits','2022-02-23 23:11:50','2022-02-23 23:11:50');
INSERT INTO `permissions` VALUES (81,'browse_results','results','2022-02-24 00:01:56','2022-02-24 00:01:56');
INSERT INTO `permissions` VALUES (82,'read_results','results','2022-02-24 00:01:56','2022-02-24 00:01:56');
INSERT INTO `permissions` VALUES (83,'edit_results','results','2022-02-24 00:01:56','2022-02-24 00:01:56');
INSERT INTO `permissions` VALUES (84,'add_results','results','2022-02-24 00:01:56','2022-02-24 00:01:56');
INSERT INTO `permissions` VALUES (85,'delete_results','results','2022-02-24 00:01:56','2022-02-24 00:01:56');
INSERT INTO `permissions` VALUES (86,'browse_infos','infos','2022-03-04 00:08:55','2022-03-04 00:08:55');
INSERT INTO `permissions` VALUES (87,'read_infos','infos','2022-03-04 00:08:55','2022-03-04 00:08:55');
INSERT INTO `permissions` VALUES (88,'edit_infos','infos','2022-03-04 00:08:55','2022-03-04 00:08:55');
INSERT INTO `permissions` VALUES (89,'add_infos','infos','2022-03-04 00:08:55','2022-03-04 00:08:55');
INSERT INTO `permissions` VALUES (90,'delete_infos','infos','2022-03-04 00:08:55','2022-03-04 00:08:55');
INSERT INTO `permissions` VALUES (91,'browse_info_details','info_details','2022-03-04 00:19:19','2022-03-04 00:19:19');
INSERT INTO `permissions` VALUES (92,'read_info_details','info_details','2022-03-04 00:19:19','2022-03-04 00:19:19');
INSERT INTO `permissions` VALUES (93,'edit_info_details','info_details','2022-03-04 00:19:19','2022-03-04 00:19:19');
INSERT INTO `permissions` VALUES (94,'add_info_details','info_details','2022-03-04 00:19:19','2022-03-04 00:19:19');
INSERT INTO `permissions` VALUES (95,'delete_info_details','info_details','2022-03-04 00:19:19','2022-03-04 00:19:19');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `results` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` text DEFAULT NULL,
  `title_ar` text DEFAULT NULL,
  `text_en` text DEFAULT NULL,
  `text_ar` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `results` WRITE;
/*!40000 ALTER TABLE `results` DISABLE KEYS */;
INSERT INTO `results` VALUES (1,'Before & After','قبل وبعد',NULL,NULL,'results/December2022/iSz1qeEgNKVlSPLoElx5.jpg','2022-02-24 00:07:00','2022-12-29 18:22:09');
INSERT INTO `results` VALUES (2,'Before & After','قبل وبعد',NULL,NULL,'results/December2022/GRaMlVfS1dGMsMuhNMOK.jpg','2022-02-24 00:12:00','2022-12-29 17:25:19');
INSERT INTO `results` VALUES (3,'Before & After','قبل وبعد',NULL,NULL,'results/December2022/85x6AWsfY4Fl81VbbHvt.jpg','2022-02-24 00:12:00','2022-12-29 17:23:57');
INSERT INTO `results` VALUES (4,'Before & After','قبل وبعد','Manal is one of the participants who did a lot of diet and many attempts and failures, but when I got to know the balanced nutrition with me, I made the decision to change. Approximately 14 kg of fat. It took time, and the change became a lot. She increased her confidence in herself and became a healthy lifestyle. She eats everything and lives her life normally. They have been maintaining a healthy body for more than a year.','منال من المشتركين يلي عملت كتير دايت و محاولات كتير و عطول توصل للفشل و لكن وقتا  تعرفت على التغذية التوازنة معي اخدت القرار بالتغيير بقيت تقريبا مدت سنة و نص و مرقت بكتير صعوبات و لكن اشتغلت على حاا و طبقت كل النصايح يلي كنت اعطيها ياه و خسرت تقريبا 14 كغ دهون ز اخدت وقت و لك التغيير يلي صار كتير زادت ثقتها  بحالا و صار عندا نمط حياة صحي و بتاكل من كلشي و عايشة حياتها طبيعي و صرلا اكتر من سنة محافظة على جسما','results/December2022/e6nLTVt75q2gpxVr19Qs.jpg','2022-02-24 00:13:00','2022-12-29 17:12:32');
INSERT INTO `results` VALUES (5,'before & after','قبل و بعد',NULL,NULL,'results/December2022/dRKbYVnDiZmEnjHdrVjU.jpg','2022-12-29 18:23:00','2022-12-29 18:23:47');
INSERT INTO `results` VALUES (6,'Before & After','قبل و بعد',NULL,NULL,'results/December2022/QnLSVrz0NWgFLli4ysN9.jpg','2022-12-29 18:24:48','2022-12-29 18:24:48');
/*!40000 ALTER TABLE `results` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(190) NOT NULL,
  `display_name` varchar(190) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','Administrator','2022-02-06 08:36:49','2022-02-06 08:36:49');
INSERT INTO `roles` VALUES (2,'user','Normal User','2022-02-06 08:36:49','2022-02-06 08:36:49');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) DEFAULT NULL,
  `title_Ar` varchar(255) DEFAULT NULL,
  `text_en` longtext DEFAULT NULL,
  `text_ar` longtext DEFAULT NULL,
  `images` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (9,'How do you benefit from a healthy lifestyle?','كيف تستفيد من اتّباع أسلوب حياة صحي؟','On this journey, we will work together to learn how to:\r\n- Knowing and following the effective diet that your body responds to to achieve the body and health you desire.\r\n- Improve your lifestyle step by step, to reach a healthy body.\r\n- Nutrition education to learn to become an expert in nourishing your body.\r\n- Get rid of harsh regimes and food deprivation.\r\n- Treating the problem of constant hunger and appetite.\r\n- Eat in moderation of any kind without any restrictions or deprivations.\r\n- Enjoy the food you love without feeling guilty.\r\n- Exercise properly to reach your goal.\r\n- Maintain your weight forever.\r\nFrom now on, your diet will be \'your\' to reach your health goals','خلال هذه الرحلة، سنعمل معاً على تعلّم كيفية:\r\nمعرفة وإتباع النظام الغذائي الفعال الذي يستجيب عليه جسمك لتحقيق الجسم والصحة يلي تتمناها .\r\nتحسين أسلوب حياتك خطوة بخطوة، للوصول إلى جسم صحي.\r\n-التثقيف الغذائي لتتعلم تصير خبير في تغذية جسدك .\r\nالتخلص من الأنظمة القاسية والحرمان من الأكل.\r\nعلاج مشكلة الجوع والشهية المستمرة.\r\nتناول الطعام باعتدال مهما كان نوعه من دون أي قيود أو حرمان.\r\nالاستمتاع بالطعام الذي تحبه من دون الإحساس بالذنب.\r\nممارسة الرياضة بشكل صحيح للوصول الى هدفك.\r\nالمحافظة على وزنك للأبد.\r\nمن الآن فصاعداً، نظامك الغذائي سيكون “خاصًا بك” لتصل إلى أهدافك الصحية.','[\"services\\\\February2022\\\\3WgbQw5BIh3AX1trkQzG.jpg\"]','2022-02-10 09:26:48','2022-03-15 19:13:33');
INSERT INTO `services` VALUES (10,'Consultation (first time)','استشارة (أول مرة)','Consultation\r\nWeight Assesment\r\nWeight Tracking\r\nMeal Plans and Analysis','التشاور\r\nتقدير الوزن\r\nتتبع الوزن\r\nخطط الوجبات وتحليلها',NULL,'2022-02-23 21:29:21','2022-02-23 21:29:21');
INSERT INTO `services` VALUES (11,'Consultation','استشارة','Consultation\r\nWeight Assesment\r\nWeight Tracking\r\nMeal Plans and Analysis','التشاور\r\nتقدير الوزن\r\nتتبع الوزن\r\nخطط الوجبات وتحليلها',NULL,'2022-02-23 21:31:08','2022-02-23 21:31:08');
INSERT INTO `services` VALUES (12,'Zumba Classes','دروس زومبا','Let`s have fun and dance','دعونا نمرح ونرقص',NULL,'2022-02-23 21:31:52','2022-02-23 21:31:52');
INSERT INTO `services` VALUES (13,'Online Consultation','الاستشارة عبر الإنترنت','Online Consultation','الاستشارة عبر الإنترنت',NULL,'2022-02-23 21:32:25','2022-02-23 21:35:01');
INSERT INTO `services` VALUES (14,'Power Plate','لوحة الطاقة','Power Plate','لوحة الطاقة',NULL,'2022-02-23 21:33:02','2022-02-23 21:33:02');
INSERT INTO `services` VALUES (15,'Diet Shop','متجر النظام الغذائي','Enjoy a great selection of foods,snacks,delicious and fresh sandwiches,salads and pastries','استمتع بتشكيلة رائعة من الأطعمة والوجبات الخفيفة والسندويشات اللذيذة والطازجة والسلطات والمعجنات',NULL,'2022-02-23 21:34:31','2022-02-23 21:34:31');
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) DEFAULT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `text_en` text DEFAULT NULL,
  `text_ar` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,NULL,NULL,'Thank you for helping my family and I eat more healthy','Thank you for helping me during pregnancy to nourish my body and the fetus and gain weight in a healthy way. Now I am following advice after giving birth and breastfeeding, and I am down 10 kg.','testimonials\\February2022\\ThNBOiVWnYDiXn0ny3c9.jpg','2022-02-09 15:40:10','2022-12-29 16:52:45');
INSERT INTO `testimonials` VALUES (2,NULL,NULL,'Dear Layla without you i would never have changed my bad habits, so thank you','عزيزتي ليلى بدونك ما كنت لأغير عاداتي السيئة ، لذا شكرا لك','testimonials\\February2022\\rtNAjcpxKMyCIC6Z9gmK.jpg','2022-02-09 15:40:36','2022-12-29 16:50:32');
INSERT INTO `testimonials` VALUES (3,NULL,NULL,'Thank you very much, Doctor, but I see that I feel psychologically comfortable. I learned a lot from you about habits and behaviors, and you taught me how to increase my self-confidence and how to nourish my body and keep me away from everything.','شكراً جزيلاً دكتورة بس شوفك بحس براحة نفسية ز تعلمت منك كتير عادات و سلوكيات وعلمتيني كيف زيد ثقتي بحالي و كيف غذي جسمي و بعدني لهلقد محافظة على كلشي .','testimonials\\February2022\\jU32Em2Fnaygkzl4OKy0.jpg','2022-02-09 15:41:42','2022-12-29 16:49:26');
INSERT INTO `testimonials` VALUES (4,'Unknown',NULL,'Thank you Dt.Layla for all your help and advices.With your help I lost 16 kg in one year and you made me realize how easy healthy eating can be.','أشكرك ليلى على كل ما قدمته من مساعدة ونصائح ، وبمساعدتك فقدت 16 كجم في عام واحد وجعلتني أدرك مدى سهولة تناول الطعام الصحي.',NULL,'2022-02-23 21:38:22','2022-02-23 21:38:22');
INSERT INTO `testimonials` VALUES (5,NULL,NULL,'The best doctor, I benefited a lot and lost weight without depriving myself of eating at home. I have been maintaining it for more than a year. I am very comfortable and happy. Thank you very much.','احلى دكتورة انا كتير استفدت و نزل وزني من دون حرمان و من اكل البيت و صرلي اكتر من سنة محافظة عليه كتير مرتاحة و مبسوطة شكرا كتير',NULL,'2022-02-23 22:30:33','2022-12-29 16:46:33');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `translations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(191) NOT NULL,
  `column_name` varchar(191) NOT NULL,
  `foreign_key` int(10) unsigned NOT NULL,
  `locale` varchar(191) NOT NULL,
  `value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `translations_table_name_column_name_foreign_key_locale_unique` (`table_name`,`column_name`,`foreign_key`,`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `translations` WRITE;
/*!40000 ALTER TABLE `translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `translations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
DROP TABLE IF EXISTS `weekly_diet_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `weekly_diet_plans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) DEFAULT NULL,
  `title_ar` varchar(255) DEFAULT NULL,
  `text_en` text DEFAULT NULL,
  `text_ar` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `day` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `meel_type` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `weekly_diet_plans` WRITE;
/*!40000 ALTER TABLE `weekly_diet_plans` DISABLE KEYS */;
INSERT INTO `weekly_diet_plans` VALUES (2,'Avocado Toast','توست الأفوكادو','Avocado toast is a type of open sandwich consisting of toast with mashed avocado, and usually salt, black pepper, and sometimes citrus juice.','.توست الأفوكادو هو نوع من السندويشات المفتوحة التي تتكون من الخبز المحمص مع الأفوكادو المهروس ، وعادة ما يكون الملح والفلفل الأسود وعصير الحمضيات في بعض الأحيان','weekly-diet-plans\\February2022\\rlQO9PBwmaADDPNaG5Vb.jpg','option1','2022-02-09 17:01:00','2022-02-13 07:41:18','option3');
INSERT INTO `weekly_diet_plans` VALUES (3,'Salmon','سمك السالمون','A serving of salmon -- 3 to 4 ounces -- is about 200 calories. It\'s very low in saturated fat and a good source of protein.','.حصة السلمون - 3 إلى 4 أونصات - حوالي 200 سعر حراري. إنه منخفض جدًا في الدهون المشبعة ومصدر جيد للبروتين','weekly-diet-plans\\February2022\\G94o4uOGl90ROQskwgTU.jpg','option1','2022-02-09 17:02:00','2022-02-13 07:31:49','option2');
INSERT INTO `weekly_diet_plans` VALUES (4,'Avocado Salad','سلطة الافوكادو','A healthy side that’s perfect for a summer lunch or dinner!','جانب صحي مثالي لوجبة غداء أو عشاء في الصيف','weekly-diet-plans\\February2022\\4eG5YKctHAtPl5gqyrHB.jpg','option1','2022-02-09 17:03:00','2022-02-13 07:38:01','option5');
INSERT INTO `weekly_diet_plans` VALUES (5,'Chicken Parmesan','بارميزان بالدجاج','Chicken parmigiana,is a dish that consists of breaded chicken breast covered in tomato sauce and parmesan.','.دجاج بارميجيانا ،هو طبق يتكون من صدر دجاج مغطى أو بارميزان','weekly-diet-plans\\February2022\\Hdw7pi8g4dejbXvJUcVx.jpg','option1','2022-02-09 17:03:00','2022-02-13 07:40:46','option4');
INSERT INTO `weekly_diet_plans` VALUES (6,'Chicken Breakfast','فطور دجاج','Chicken has a very high protein content.','.إمداد البروتين. يحتوي الدجاج على نسبة عالية جدًا من البروتين ، والذي يلعب دورًا مهمًا جدًا في الحفاظ على عضلاتنا','weekly-diet-plans\\February2022\\k10TOOmkLyjVjafafSsX.jpg','option7','2022-02-09 17:50:00','2022-02-13 13:46:34','option1');
/*!40000 ALTER TABLE `weekly_diet_plans` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

/*M!999999\- enable the sandbox mode */ 

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) NOT NULL,
  `display_name` varchar(191) NOT NULL,
  `value` text DEFAULT NULL,
  `details` text DEFAULT NULL,
  `type` varchar(191) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `group` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site.title','Site Title','Site Title','','text',1,'Site');
INSERT INTO `settings` VALUES (2,'site.description','Site Description','Site Description','','text',2,'Site');
INSERT INTO `settings` VALUES (3,'site.logo','Site Logo','','','image',3,'Site');
INSERT INTO `settings` VALUES (5,'admin.bg_image','Admin Background Image','settings/October2022/MqwC3xatYGyYWha7UWBV.jpg','','image',5,'Admin');
INSERT INTO `settings` VALUES (6,'admin.title','Admin Title','Voyager','','text',1,'Admin');
INSERT INTO `settings` VALUES (7,'admin.description','Admin Description','Welcome to Voyager. The Missing Admin for Laravel','','text',2,'Admin');
INSERT INTO `settings` VALUES (8,'admin.loader','Admin Loader','settings/October2022/xH6NYgpJs0wiS5D13EZc.jpg','','image',3,'Admin');
INSERT INTO `settings` VALUES (9,'admin.icon_image','Admin Icon Image','','','image',4,'Admin');
INSERT INTO `settings` VALUES (10,'admin.google_analytics_client_id','Google Analytics Client ID (used for admin dashboard)',NULL,'','text',1,'Admin');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

