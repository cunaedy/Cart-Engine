-- RESET
SET NAMES utf8;
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

-- 1. Recreate tables that may be altered

DROP TABLE IF EXISTS `__PREFIX__product_cf_value`;
CREATE TABLE `__PREFIX__product_cf_value` (
  `idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `cf_1` varchar(255) NOT NULL,
  `cf_2` varchar(255) NOT NULL,
  `cf_3` varchar(255) NOT NULL,
  `cf_4` varchar(255) NOT NULL,
  `cf_5` varchar(255) NOT NULL,
  `cf_6` varchar(255) NOT NULL,
  `cf_7` varchar(255) NOT NULL,
  `cf_8` varchar(255) NOT NULL,
  `cf_9` varchar(255) NOT NULL,
  `cf_10` varchar(255) NOT NULL,
  `cf_11` varchar(255) NOT NULL,
  `cf_12` varchar(255) NOT NULL,
  `cf_13` varchar(255) NOT NULL,
  `cf_14` varchar(255) NOT NULL,
  `cf_15` varchar(255) NOT NULL,
  PRIMARY KEY (`idx`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2. Clean all tables

TRUNCATE TABLE `__PREFIX__cache`;
TRUNCATE TABLE `__PREFIX__ip_log`;
TRUNCATE TABLE `__PREFIX__mailog`;
TRUNCATE TABLE `__PREFIX__menu_item`;
TRUNCATE TABLE `__PREFIX__menu_set`;
TRUNCATE TABLE `__PREFIX__module`;
TRUNCATE TABLE `__PREFIX__module_pos`;
TRUNCATE TABLE `__PREFIX__notification`;
TRUNCATE TABLE `__PREFIX__page`;
TRUNCATE TABLE `__PREFIX__page_cat`;
TRUNCATE TABLE `__PREFIX__page_group`;
TRUNCATE TABLE `__PREFIX__permalink`;
TRUNCATE TABLE `__PREFIX__qadmin_log`;
TRUNCATE TABLE `__PREFIX__qcomment`;
TRUNCATE TABLE `__PREFIX__qcomment_set`;
TRUNCATE TABLE `__PREFIX__user`;
TRUNCATE TABLE `__PREFIX__permalink`;
TRUNCATE TABLE `__PREFIX__product_cat`;
TRUNCATE TABLE `__PREFIX__product_cf_define`;
TRUNCATE TABLE `__PREFIX__product_cf_value`;
TRUNCATE TABLE `__PREFIX__gift`;
TRUNCATE TABLE `__PREFIX__order_final`;
TRUNCATE TABLE `__PREFIX__order_summary`;
TRUNCATE TABLE `__PREFIX__orders`;
TRUNCATE TABLE `__PREFIX__payment_log`;
TRUNCATE TABLE `__PREFIX__products`;
TRUNCATE TABLE `__PREFIX__user_file`;

-- 3. Refill tables

UPDATE __PREFIX__config SET config_value='1,13,7,5' WHERE config_id='featured_product' LIMIT 1;

DELETE FROM `__PREFIX__config` WHERE group_id='var' AND config_id='distro';

INSERT INTO `__PREFIX__config` (`idx`, `group_id`, `config_id`, `config_value`) VALUES
(151, 'var',  'distro', 'BMW'),
(152, 'var',  'distro', 'Apple'),
(153, 'var',  'distro', 'HP'),
(154, 'var',  'distro', 'Microsoft'),
(155, 'var',  'distro', 'Asus'),
(156, 'var',  'distro', 'Mercedez Benz'),
(157, 'var',  'distro', 'LG');

DELETE FROM `__PREFIX__config` WHERE group_id LIKE 'mod_%';

INSERT INTO `__PREFIX__config` (`idx`, `group_id`, `config_id`, `config_value`) VALUES
(69,  'mod_qstats', 'stats_last_update',  ''),
(115, 'mod_pay_paypal', 'sandbox',  '0'),
(116, 'mod_pay_paypal', 'currency_code',  'USD'),
(117, 'mod_pay_paypal', 'bussiness',  'seller@c97.net'),
(118, 'mod_pay_paypal', 'ipn',  '1'),
(119, 'mod_pay_cheque', 'recipient',  'My Name'),
(120, 'mod_pay_bank', 'bankname', 'My Bank'),
(121, 'mod_pay_bank', 'bankaddress',  ''),
(122, 'mod_pay_bank', 'bankcode', 'Bank code (BIC/IBAN/SWIFT)'),
(123, 'mod_pay_bank', 'account',  '1234567890'),
(124, 'mod_pay_bank', 'holder', 'My Name'),
(125, 'mod_ship_percent', 'fee',  '10'),
(126, 'mod_ship_item',  'per_item', '10'),
(127, 'mod_ship_cod', 'fee',  '5'),
(128, 'mod_ship_pickup',  'fee',  '10'),
(129, 'mod_ship_free',  'minimum',  '1000000'),
(132, 'mod_pay_paypal', 'conversion_rate',  ''),
(165, 'mod_ship_weight',  'fee',  'a:4:{s:4:\"city\";s:2:\"10\";s:5:\"state\";s:2:\"20\";s:7:\"country\";s:2:\"30\";s:5:\"world\";s:2:\"40\";}');

INSERT INTO `__PREFIX__menu_item` (`idx`, `menu_id`, `menu_parent`, `menu_item`, `menu_url`, `menu_permalink`, `menu_order`, `ref_idx`) VALUES
(1, 1,  0,  'Home', '__SITE__/index.php', '', 100,  0),
(4, 2,  0,  'Contact Us', '__SITE__/contact.php', '', 100,  0),
(5, 2,  0,  'Site Map', '__SITE__/sitemap.php', '', 110,  0),
(7, 2,  0,  'Tell a Friend',  '__SITE__/tell.php',  '', 120,  0),
(8, 3,  0,  'Privacy Policy', '2',  '', 100,  0),
(9, 3,  0,  'Terms &amp; Conditions', '7',  '', 110,  0),
(10,  3,  0,  'Powered by qEngine', '8',  '', 130,  0),
(11,  3,  0,  'FAQ',  '3',  '', 120,  0),
(12,  7,  0,  'Computing',  '__SITE__/shop_search.php?cat_id=1',  '__SITE__/category/computing.php',  100,  0),
(13,  7,  12, 'Apple',  '__SITE__/shop_search.php?cat_id=2',  '__SITE__/category/apple.php',  110,  0),
(14,  7,  13, 'iPod', '__SITE__/shop_search.php?cat_id=3',  '__SITE__/category/ipod.php', 120,  0),
(17,  7,  0,  'Deep Category',  '__SITE__/shop_search.php?cat_id=6',  '__SITE__/category/deep-category.php',  220,  0),
(18,  7,  17, 'Category 1-1', '__SITE__/shop_search.php?cat_id=7',  '__SITE__/category/category-1-1.php', 230,  0),
(19,  7,  17, 'Category 1-2', '__SITE__/shop_search.php?cat_id=8',  '__SITE__/category/category-1-2.php', 330,  0),
(20,  7,  17, 'Category 1-3', '__SITE__/shop_search.php?cat_id=9',  '__SITE__/category/category-1-3.php', 340,  0),
(21,  7,  18, 'Category 1-1-1', '__SITE__/shop_search.php?cat_id=10', '__SITE__/category/category-1-1-1.php', 240,  0),
(22,  7,  18, 'Category 1-1-2', '__SITE__/shop_search.php?cat_id=11', '__SITE__/category/category-1-1-2.php', 310,  0),
(23,  7,  21, 'Category 1-1-1-1', '__SITE__/shop_search.php?cat_id=12', '__SITE__/category/category-1-1-1-1.php', 250,  0),
(24,  7,  23, 'Category 1-1-1-1-1', '__SITE__/shop_search.php?cat_id=13', '__SITE__/category/category-1-1-1-1-1.php', 260,  0),
(25,  7,  22, 'Category 1-1-1-2-1', '__SITE__/shop_search.php?cat_id=14', '__SITE__/category/category-1-1-1-2-1.php', 320,  0),
(26,  7,  23, 'Category 1-1-1-1-2', '__SITE__/shop_search.php?cat_id=15', '__SITE__/category/category-1-1-1-1-2.php', 270,  0),
(27,  7,  12, 'Asus', '__SITE__/shop_search.php?cat_id=16', '__SITE__/category/asus.php', 130,  0),
(28,  7,  12, 'HP', '__SITE__/shop_search.php?cat_id=17', '__SITE__/category/hp.php', 140,  0),
(29,  7,  12, 'Peripherals',  '__SITE__/shop_search.php?cat_id=18', '__SITE__/category/peripherals.php',  150,  0),
(30,  7,  0,  'Smartphones',  '__SITE__/shop_search.php?cat_id=19', '__SITE__/category/smartphones.php',  170,  0),
(31,  7,  30, 'Apple',  '__SITE__/shop_search.php?cat_id=20', '__SITE__/category/apple-2.php',  180,  0),
(32,  7,  30, 'Nokia',  '__SITE__/shop_search.php?cat_id=21', '__SITE__/category/nokia.php',  190,  0),
(33,  7,  23, 'Kaiju Powder', '__SITE__/shop_search.php?cat_id=22', '__SITE__/category/kaiju-powder.php', 280,  0),
(34,  7,  23, 'Kaiju Bones',  '__SITE__/shop_search.php?cat_id=23', '__SITE__/category/kaiju-bones.php',  290,  0),
(35,  7,  23, 'Used Jaegers', '__SITE__/shop_search.php?cat_id=24', '__SITE__/category/used-jaegers.php', 300,  0),
(36,  7,  0,  'Books',  '__SITE__/shop_search.php?cat_id=25', '__SITE__/category/books.php',  210,  0),
(37,  7,  12, 'Build Your Own', '__SITE__/shop_search.php?cat_id=26', '__SITE__/category/build-your-own.php', 160,  0),
(38,  7,  0,  'Clothing', '__SITE__/shop_search.php?cat_id=27', '__SITE__/category/clothing.php', 200,  0),
(39,  8,  0,  'Screen Size',  '', '', 100,  0),
(40,  8,  0,  'Screen Resolution',  '', '', 110,  0),
(41,  8,  0,  'Color',  '', '', 120,  0),
(42,  8,  0,  'Product Dimension',  '', '', 240,  0),
(43,  8,  0,  'Product Weight', '', '', 230,  0),
(44,  8,  0,  'Internal Storage', '', '', 130,  0),
(45,  8,  0,  'Screen Size - For smartphones &amp; small devices',  '', '', 140,  0),
(46,  8,  0,  'Screen Resolution -- For smartphones &amp; small devices', '', '', 150,  0),
(47,  8,  0,  'Processor',  '', '', 160,  0),
(48,  8,  0,  'Processor Speed',  '', '', 170,  0),
(49,  8,  0,  'RAM',  '', '', 180,  0),
(50,  8,  0,  'Internal Storage', '', '', 190,  0),
(51,  8,  0,  'Battery Life', '', '', 200,  0),
(52,  8,  0,  'Author', '', '', 210,  0),
(53,  8,  0,  'ISBN', '', '', 220,  0),
(67,  1,  0, 'Shop',  '#', '', 110,  0),
(68,  1,  67, '#',  '[[sm:product]]', '', 120,  0);

TRUNCATE TABLE `__PREFIX__menu_set`;
INSERT INTO `__PREFIX__menu_set` (`idx`, `menu_id`, `menu_title`, `menu_preset`, `menu_class`, `menu_notes`, `menu_cache`, `menu_locked`, `max_depth`, `ref_idx`) VALUES
(1, 'main_menu',  'Main Menu',  'bsnav',  '', 'Main menu, usually located at the top of the page.', '<ul id=\"qmenu_main_menu\" class=\"nav navbar-nav\">\n <li><a href=\"__SITE__/index.php\">Home</a></li>\n <li class=\"dropdown\"><a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\">Shop <span class=\"caret\"></span></a>  <ul class=\"dropdown-menu\">\n    <li><a href=\"[[sm:product]]\">#</a></li>\n </ul>\n</li>\n</ul>\n', '0',  1,  0),
(2, 'footer_menu',  'Footer Menu',  'list_1', '', 'Footer menu, usually located at the end of the page.', '<ul id=\"qmenu_footer_menu\" class=\"list_1\">\n <li><a href=\"__SITE__/contact.php\">Contact Us</a></li>\n <li><a href=\"__SITE__/sitemap.php\">Site Map</a></li>\n <li><a href=\"__SITE__/tell.php\">Tell a Friend</a></li>\n</ul>\n',  '0',  1,  0),
(3, 'page_menu',  'Page Menu',  'list_1', '', 'Menu linking to some important, but not that important pages.',  '<ul id=\"qmenu_page_menu\" class=\"list_1\">\n <li><a href=\"__SITE__/privacy-policy.php\">Privacy Policy</a></li>\n  <li><a href=\"__SITE__/terms-and-conditions.php\">Terms & Conditions</a></li>\n  <li><a href=\"__SITE__/faqs.php\">FAQ</a></li>\n <li><a href=\"__SITE__/powered-by-qengine.php\">Powered by qEngine</a></li>\n</ul>\n', '0',  1,  0),
(7, 'product',  'Product Menu', 'bsnav',  '', 'Container for product category menu. Do NOT remove!',  '<ul id=\"qmenu_product\" class=\"nav navbar-nav\">\n <li><a href=\"__SITE__/category/computing.php\">Computing</a>\n  <ul class=\"dropdown-menu\">\n    <li><a href=\"__SITE__/category/apple.php\">Apple</a>\n    <ul class=\"dropdown-menu\">\n      <li><a href=\"__SITE__/category/ipod.php\">iPod</a></li>\n   </ul>\n </li>\n   <li><a href=\"__SITE__/category/asus.php\">Asus</a></li>\n   <li><a href=\"__SITE__/category/hp.php\">HP</a></li>\n   <li><a href=\"__SITE__/category/peripherals.php\">Peripherals</a></li>\n   <li><a href=\"__SITE__/category/build-your-own.php\">Build Your Own</a></li>\n </ul>\n</li>\n  <li><a href=\"__SITE__/category/smartphones.php\">Smartphones</a>\n  <ul class=\"dropdown-menu\">\n    <li><a href=\"__SITE__/category/apple-2.php\">Apple</a></li>\n   <li><a href=\"__SITE__/category/nokia.php\">Nokia</a></li>\n </ul>\n</li>\n  <li><a href=\"__SITE__/category/clothing.php\">Clothing</a></li>\n <li><a href=\"__SITE__/category/books.php\">Books</a></li>\n <li><a href=\"__SITE__/category/deep-category.php\">Deep Category</a>\n  <ul class=\"dropdown-menu\">\n    <li><a href=\"__SITE__/category/category-1-1.php\">Category 1-1</a>\n    <ul class=\"dropdown-menu\">\n      <li><a href=\"__SITE__/category/category-1-1-1.php\">Category 1-1-1</a>\n      <ul class=\"dropdown-menu\">\n        <li><a href=\"__SITE__/category/category-1-1-1-1.php\">Category 1-1-1-1</a>\n        <ul class=\"dropdown-menu\">\n          <li><a href=\"__SITE__/category/category-1-1-1-1-1.php\">Category 1-1-1-1-1</a></li>\n         <li><a href=\"__SITE__/category/category-1-1-1-1-2.php\">Category 1-1-1-1-2</a></li>\n         <li><a href=\"__SITE__/category/kaiju-powder.php\">Kaiju Powder</a></li>\n         <li><a href=\"__SITE__/category/kaiju-bones.php\">Kaiju Bones</a></li>\n         <li><a href=\"__SITE__/category/used-jaegers.php\">Used Jaegers</a></li>\n       </ul>\n     </li>\n     </ul>\n   </li>\n     <li><a href=\"__SITE__/category/category-1-1-2.php\">Category 1-1-2</a>\n      <ul class=\"dropdown-menu\">\n        <li><a href=\"__SITE__/category/category-1-1-1-2-1.php\">Category 1-1-1-2-1</a></li>\n     </ul>\n   </li>\n   </ul>\n </li>\n   <li><a href=\"__SITE__/category/category-1-2.php\">Category 1-2</a></li>\n   <li><a href=\"__SITE__/category/category-1-3.php\">Category 1-3</a></li>\n </ul>\n</li>\n</ul>\n', '1',  0,  0),
(8, 'product_cf', 'Product cf', '', '', 'Container for product custom field ordering. Do NOT remove!',  '<ul id=\"qmenu_product_cf\" class=\"\">\n  <li>Screen Size</li>\n  <li>Screen Resolution</li>\n  <li>Color</li>\n  <li>Internal Storage</li>\n <li>Screen Size - For smartphones & small devices</li>\n  <li>Screen Resolution -- For smartphones & small devices</li>\n <li>Processor</li>\n  <li>Processor Speed</li>\n  <li>RAM</li>\n  <li>Internal Storage</li>\n <li>Battery Life</li>\n <li>Author</li>\n <li>ISBN</li>\n <li>Product Weight</li>\n <li>Product Dimension</li>\n</ul>\n', '1',  1,  0);

INSERT INTO `__PREFIX__module` (`mod_id`, `mod_type`, `mod_name`, `mod_desc`, `mod_version`, `mod_css`, `mod_js`, `mod_enabled`) VALUES
('box', 'general',  'Box',  'A simple module to display static html for your page, without editing .tpl files, best used with qE 4.x\'s Module Manager.', '1.0.0',  '', '', '1'),
('page_gallery',  'general',  'Page Gallery', 'Display selected pages or categories or groups anywhere!', '2.0.0',  '', '', '1'),
('qbanner', 'general',  'qBanner',  'Use this module to upload & display banners.', '1.0.0',  '', '', '1'),
('qcomment',  'general',  'qComment', 'Add user comments & user ratings to your site and your modules, easily!',  '3.0.0',  '', '', '1'),
('qmenu', 'general',  'qMenu',  'Use qMenu module to display your designed menu easily!', '1.0.0',  '', '', '1'),
('qstats',  'general',  'Simple Stats', 'This module replaces qEngine\'s old simple statistics of visitors\' hits & visits.', '1.0.0',  '', '', '1'),
('slideshow', 'general',  'Slideshow',  'This module to replace qEngine\'s old featured contents.', '1.0.0',  'slideshow.css',  '', '1'),
('ztopwatch', 'general',  'Ztopwatch',  'This module replaces the old stopwatch in qEngine 1',  '1.0.0',  '', '', '1'),
('ce_core', 'general',  'CE Core Module', 'This is a multipurpose module for Cart Engine. This module contains support for product feature, custom field, product option and sub product.', '1.0.0',  '', '', '1'),
('pay_bank',  'payment',  'Bank Wire Transfer', 'A payment gateway for Bank Wire Transfer.',  '1.0.0',  '', '', '1'),
('pay_cheque',  'payment',  'Cheque', 'A payment gateway for By Cheque.', '1.0.0',  '', '', '1'),
('pay_cod', 'payment',  'Cash on Delivery', 'A payment gateway for Cash on Delivery.',  '1.0.0',  '', '', '1'),
('pay_paypal',  'payment',  'PayPal', 'A payment gateway for PayPal (IPN). Also a documentation on creating your own payment gateway.', '1.0.0',  '', '', '1'),
('ship_cod',  'shipping', 'Cash on Delivery (Shipping Module)', 'A shipping gateway for Cash on Delivery (best combined with COD payment module).', '1.0.0',  '', '', '1'),
('ship_free', 'shipping', 'Free Shipping',  'A shipping gateway for Free Shipping.',  '1.0.0',  '', '', '1'),
('ship_item', 'shipping', 'Per Item', 'A shipping gateway for Per Item Shipping.',  '1.0.0',  '', '', '1'),
('ship_percent',  'shipping', 'Percentage', 'A shipping gateway for Percentage of Purchased Total.',  '1.0.0',  '', '', '1'),
('ship_pickup', 'shipping', 'Pickup', 'A shipping gateway for Store Pickup.', '1.0.0',  '', '', '1'),
('ship_weight', 'shipping', 'By Weight (manual calculation)', 'A really simple shipping gateway for By Weight, need to manually enter rates in configuration screen.',  '1.0.0',  '', '', '1');

INSERT INTO `__PREFIX__module_pos` (`idx`, `mod_id`, `mod_title`, `mod_config`, `mod_pos`) VALUES
(1, 'box',  'Add Anything!',  '&lt;p&gt;You can easily adds any HTML or JavaScript tags by editing this module, or create a new box, by using Box Module in ACP &gt; Modules &gt; Layout.&lt;/p&gt;\r\n\r\n&lt;p&gt;Add Google AdSense, Facebook Feeds, Twitter Updates, by editing this module.&lt;/p&gt;',  'R1'),
(2, 'box',  'Info Box', '&lt;p&gt;Manage this module from ACP. Display up to 40 modules easily!&lt;/p&gt;\r\n\r\n&lt;p&gt;You can also remove this information from Module Management.&lt;/p&gt;\r\n\r\n&lt;p&gt;In default skin, this module appears on the right.&lt;/p&gt;', 'L2'),
(3, 'qbanner',  'Banner', '', 'L1'),
(4, 'qmenu',  'Tools',  'menu=footer_menu', 'B1'),
(5, 'qmenu',  'Pages',  'menu=page_menu', 'B1'),
(9, 'qstats', 'Simple Stats', '', 'R2');

INSERT INTO `__PREFIX__page` (`group_id`, `cat_id`, `page_id`, `permalink`, `page_image`, `page_date`, `page_time`, `page_unix`, `page_title`, `page_keyword`, `page_body`, `page_author`, `page_related`, `page_allow_comment`, `last_update`, `page_rating`, `page_comment`, `page_list`, `page_hit`, `page_img_tmp`, `page_attachment`, `page_attachment_stat`, `page_download`, `page_pinned`, `page_status`, `page_template`, `page_mode`) VALUES
('GENPG', 1,  1,  'welcome.php',  '', '2011-11-11', '14:15:00', 1321017300, 'Welcome',  'add,your,keyword,here,qengine,c97net', '<p>Welcome to our site, please enjoy your stay here. If you have any question, please contact us.</p>\r\n<p>This is an example of a page, you could edit this to put information about yourself or your site so readers know where you are coming from. As mentioned before, you can create as many pages like this one.</p>\r\n<p>You can edit this text in Admin &gt; Page Manager.</p>',  'admin',  '', '0',  1447165085, 0.00, 0,  '0',  111,  '', '', 0,  '', '0',  'P',  'page_default.tpl', 'html'),
('GENPG', 1,  2,  'privacy-policy.php', '', '2011-11-11', '14:30:00', 1321018200, 'Privacy Policy', '', '<h2>Information that is gathered from visitors</h2>\r\n<p>In common with other websites, log files are stored on the web server saving details such as the visitor\'s IP address, browser type, referring page and time of visit.</p>\r\n<p>Cookies may be used to remember visitor preferences when interacting with the website.</p>\r\n<p>Where registration is required, the visitor\'s email and a username will be stored on the server.</p>\r\n<h2>How the Information is used</h2>\r\n<p>The information is used to enhance the vistor\'s experience when using the website to display personalised content and possibly advertising.</p>\r\n<p>E-mail addresses will not be sold, rented or leased to 3rd parties.</p>\r\n<p>E-mail may be sent to inform you of news of our services or offers by us or our affiliates.</p>\r\n<h2>Visitor Options</h2>\r\n<p>If you have subscribed to one of our services, you may unsubscribe by following the instructions which are included in e-mail that you receive.</p>\r\n<p>You may be able to block cookies via your browser settings but this may prevent you from access to certain features of the website.</p>\r\n<h2>Cookies</h2>\r\n<p>Cookies are small digital signature files that are stored by your web browser that allow your preferences to be recorded when visiting the website. Also they may be used to track your return visits to the website.</p>\r\n<p>3rd party advertising companies may also use cookies for tracking purposes.</p>\r\n<h2>Google Ads</h2>\r\n<p>Google, as a third party vendor, uses cookies to serve ads.</p>\r\n<p>Google\'s use of the DART cookie enables it to serve ads to visitors based on their visit to sites they visit on the Internet.</p>\r\n<p>Website visitors may opt out of the use of the DART cookie by visiting the Google ad and content network privacy policy.</p>\r\n<p>(last updated March 2009)<br />Based on <a href=\"http://www.freeprivacypolicy.org/\">FPP</a></p>', 'admin',  '', '1',  1509379812, 0.00, 0,  '1',  71, '', '', 6,  ',5,',  '0',  'P',  'page_default.tpl', 'html'),
('GENPG', 1,  3,  'faqs.php', '', '2011-11-11', '00:00:00', 1320944400, 'FAQ&#039;s', '', '<p>Here you will find answers to many of your questions. If there is something which you cannot find the answer to, let us know and we will add your question to this list.</p>',  'admin',  '', '1',  1366442718, 0.00, 0,  '1',  69, '', '', 0,  '', '0',  'P',  'page_default.tpl', 'html'),
('GENPG', 1,  4,  'about-us.php', '', '2011-11-11', '00:00:00', 1320966000, 'About Us', '', '<p>Tell who you are, what you do, and anything else. Company history and the chairman will be a nice addition.</p>', 'admin',  '', '1',  1443117458, 0.00, 0,  '1',  338,  '', '', 3,  '', '0',  'P',  'page_default.tpl', 'html'),
('GENPG', 1,  5,  'success.php',  '', '2011-11-11', '00:00:00', 1320966000, 'Success',  '', '<p>Thank you for purchasing in our site.</p>\r\n<p>You will be guided to pay your transaction.</p>', '', '', '0',  1510418346, 0.00, 0,  '0',  0,  '', '', 0,  '', '0',  'P',  '', 'html'),
('GENPG', 1,  6,  'contact-us.php', '', '2011-11-11', '00:00:00', 1320944400, 'Contact Us', '', '<p>Put your contact information here, such as office hour, parking spot, direction, etc.</p>\r\n<p>Change this text in Admin &gt; Page Manager</p>', 'admin',  '', '0',  1366442727, 0.00, 0,  '0',  0,  '', '', 0,  '', '0',  'P',  'page_default.tpl', 'html'),
('GENPG', 1,  7,  'terms-and-conditions.php', '', '2011-11-11', '00:00:00', 1320944400, 'Terms &amp; Conditions', '', '<p>Welcome to our website. If you continue to browse and use this website, you are agreeing to comply with and be bound by the following terms and conditions of use, which together with our privacy policy govern [business name]\'s relationship with you in relation to this website. If you disagree with any part of these terms and conditions, please do not use our website.</p>\r\n<p>The term \'[business name]\' or \'us\' or \'we\' refers to the owner of the website whose registered office is [address]. Our company registration number is [company registration number and place of registration]. The term \'you\' refers to the user or viewer of our website.</p>\r\n<p>The use of this website is subject to the following terms of use:</p>\r\n<ul>\r\n<li>The content of the pages of this website is for your general information and use only. It is subject to change without notice.</li>\r\n<li>This website uses cookies to monitor browsing preferences. If you do allow cookies to be used, the following personal information may be stored by us for use by third parties: [insert list of information].</li>\r\n<li>Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.</li>\r\n<li>Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</li>\r\n<li>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.</li>\r\n<li>All trade marks reproduced in this website which are not the property of, or licensed to, the operator are acknowledged on the website.</li>\r\n<li>Unauthorised use of this website may give rise to a claim for damages and/or be a criminal offence.</li>\r\n<li>From time to time this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).</li>\r\n<li>Your use of this website and any dispute arising out of such use of the website is subject to the laws of England, Northern Ireland,&nbsp;Scotland and Wales.</li>\r\n</ul>',  'admin',  '', '1',  1366442731, 0.00, 0,  '1',  30, '', '', 0,  '', '0',  'P',  'page_default.tpl', 'html'),
('GENPG', 1,  8,  'powered-by-qengine.php', '', '2011-11-11', '00:00:00', 1320944400, 'Powered by qEngine', '', '<p>This site is using qEngine CMS, a simple CMS engine created by <a href=\"http://www.c97.net\">C97.net</a>. qEngine is very easy to use &amp; maintain, no need to understand PHP, and it\'s <strong>FREE!</strong> If you are interested to use this awesome script please visit <a href=\"http://www.c97.net\">www.c97.net</a> now!</p>',  'admin',  '', '1',  1366442735, 0.00, 0,  '1',  24, '', '', 0,  '', '0',  'P',  'page_default.tpl', 'html'),
('NEWS',  2,  9,  'news/site-is-now-up-and-ready.php',  '', '2011-11-11', '00:00:00', 1320966000, 'Site is Now Up &amp; Ready', '', '<h2>Congratulations!</h2>\r\n<p>You have succesfully installed qEngine!<br />To learn more about qEngine, please visit <a href=\"http://www.c97.net\" target=\"_blank\">www.c97.net</a>.<br /><br />(To remove this message, go to ACP &gt; Contents &gt; Manage Contents)</p>', 'admin',  '', '0',  1469804396, 0.00, 0,  '1',  83, '', '', 0,  '', '0',  'P',  'page_default.tpl', 'html'),
('QBANR', 0,  10, '', 'banner2.jpg',  '0000-00-00', '00:00:00', 0,  'The Banner', '#',  'This page is part of qBanner module. Please use qBanner Manager to edit this page.', '', '', '', 0,  0.00, 0,  '', 0,  '', '', 0,  '', '0',  'P',  '', 'html'),
('SSHOW', 0,  11, '', 'slide1.jpg', '0000-00-00', '00:00:00', 0,  'Change this content from ACP', '#',  'This page is part of SlideShow module. Please use SlideShow Manager to edit this page.', '', '', '', 0,  0.00, 0,  '', 0,  '', '', 0,  '', '0',  'P',  '', 'html'),
('SSHOW', 0,  12, '', 'slide2.jpg', '0000-00-00', '00:00:00', 0,  'The Mountain', '#',  'This page is part of SlideShow module. Please use SlideShow Manager to edit this page.', '', '', '', 0,  0.00, 0,  '', 0,  '', '', 0,  '', '0',  'P',  '', 'html'),
('SSHOW', 0,  13, '', 'slide3.jpg', '0000-00-00', '00:00:00', 0,  'The city', '#',  'This page is part of SlideShow module. Please use SlideShow Manager to edit this page.', '', '', '', 0,  0.00, 0,  '', 0,  '', '', 0,  '', '0',  'P',  '', 'html');

INSERT INTO `__PREFIX__page_cat` (`idx`, `group_id`, `parent_id`, `permalink`, `cat_name`, `cat_details`, `cat_image`) VALUES
(1, 'GENPG',  0,  'general-pages.php',  'General Pages',  '<p>General Pages</p>', ''),
(2, 'NEWS', 0,  'news.php', 'General News', '<p>General News</p>',  '');

INSERT INTO `__PREFIX__page_group` (`idx`, `group_id`, `group_title`, `group_notes`, `all_cat_list`, `cat_list`, `page_cat`, `page_image`, `page_image_size`, `page_thumb`, `page_gallery`, `page_gallery_thumb`, `page_author`, `page_comment`, `page_attachment`, `page_download`, `page_date`, `page_folder`, `page_sort`, `hidden_private`, `group_template`, `page_template`) VALUES
(1, 'GENPG',  'Common Page',  'General pages, eg: company history, about you, etc. (please do NOT remove this content type, you can edit this message in ACP &gt; Contents &gt; Manage Types)', '1',  '1',  '1',  '1',  500,  200,  '1',  200,  '1',  'pagecomment',  '1',  ',3,4,',  '0',  '', 't',  '0',  'body_default.tpl', 'page_default.tpl'),
(2, 'NEWS', 'News', 'Site news (please do NOT remove this content type, you can edit this message in ACP &gt; Contents &gt; Manage Types)', '1',  '1',  '1',  '1',  500,  200,  '0',  0,  '0',  '0',  '0',  '', '1',  'news', 't',  '0',  'body_news.tpl',  'page_default.tpl'),
(3, 'QBANR',  'qBanner',  'qBanner module storage', '0',  '0',  '0',  '1',  0,  0,  '0',  0,  '0',  '0',  '', '', '0',  '', 't',  '1',  'body_default.tpl', 'page_default.tpl'),
(4, 'SSHOW',  'Slideshow',  'Slideshow module storage', '0',  '0',  '0',  '1',  0,  0,  '0',  0,  '0',  '0',  '', '', '0',  '', 't',  '1',  'body_default.tpl', 'page_default.tpl');

INSERT INTO `__PREFIX__permalink` (`idx`, `url`, `target_script`, `target_idx`, `target_param`) VALUES
(1, 'welcome.php',  'page.php', '1',  ''),
(2, 'privacy-policy.php', 'page.php', '2',  ''),
(3, 'faqs.php', 'page.php', '3',  ''),
(4, 'about-us.php', 'page.php', '4',  ''),
(5, 'contact-us.php', 'page.php', '6',  ''),
(6, 'terms-and-conditions.php', 'page.php', '7',  ''),
(7, 'powered-by-qengine.php', 'page.php', '8',  ''),
(8, 'news/site-is-now-up-and-ready.php',  'page.php', '9',  ''),
(9, 'general-pages.php',  'page.php', '1',  'list'),
(10,  'news.php', 'page.php', '2',  'list'),
(11,  'category/computing.php', 'shop_search.php',  '1',  ''),
(12,  'success.php',  'page.php', '5',  ''),
(13,  'apple-ipod-touch-5g-32gb-black.php', 'detail.php', '1',  ''),
(14,  'apple-ipod-touch-5g-32gb-red-w-laser-engrave.php', 'detail.php', '2',  ''),
(15,  'asus-v550ca-15-6-quot-touchscreen-laptop.php', 'detail.php', '8',  ''),
(16,  'asus-zenbook-13-3-quot-touchscreen-ultrabook.php', 'detail.php', '7',  ''),
(17,  'build-your-own-pc.php',  'detail.php', '13', ''),
(18,  'create-your-own-long-sleeve.php',  'detail.php', '25', ''),
(19,  'create-your-own-t-shirt.php',  'detail.php', '24', ''),
(20,  'hp-envy-17-j053ea-17.php', 'detail.php', '10', ''),
(21,  'hp-envy-m6-1310sa-15-6-quot.php',  'detail.php', '9',  ''),
(22,  'lg-22en33s-full-hd-21-5-quot-led-monitor.php', 'detail.php', '22', ''),
(23,  'lg-d2343p-full-hd-23-quot-3d-ips-led-monitor.php', 'detail.php', '23', ''),
(24,  'mac-mini.php', 'detail.php', '6',  ''),
(25,  'new-apple-imac-21-5-quot.php', 'detail.php', '4',  ''),
(26,  'new-apple-imac-27-quot.php', 'detail.php', '5',  ''),
(27,  'pacific-rim-the-official-movie-novelization.php',  'detail.php', '27', ''),
(28,  'pacific-rim-the-official-movie-novelization-ebook-digital-product.php',  'detail.php', '28', ''),
(29,  'patriot-signature-line-pc3-10600-ddr3-pc-memory-16gb-dimm-ram.php',  'detail.php', '21', ''),
(30,  'patriot-signature-line-pc3-10600-ddr3-pc-memory-8gb-dimm-ram.php', 'detail.php', '14', ''),
(31,  'special-edition-harry-potter-paperback-box-set.php', 'detail.php', '26', ''),
(32,  'apple-ipod-touch-5g-64gb-blue.php',  'detail.php', '3',  ''),
(33,  'wd-internal-hdd-2-5-quot-500gb.php', 'detail.php', '11', ''),
(34,  'wd-internal-hdd-2-5-quot-1tb.php', 'detail.php', '12', ''),
(35,  'category/apple.php', 'shop_search.php',  '2',  ''),
(36,  'category/ipod.php',  'shop_search.php',  '3',  ''),
(37,  'category/deep-category.php', 'shop_search.php',  '6',  ''),
(38,  'category/category-1-1.php',  'shop_search.php',  '7',  ''),
(39,  'category/category-1-2.php',  'shop_search.php',  '8',  ''),
(40,  'category/category-1-3-2.php',  'shop_search.php',  '9',  ''),
(41,  'category/category-1-1-1.php',  'shop_search.php',  '10', ''),
(42,  'category/category-1-1-2.php',  'shop_search.php',  '11', ''),
(43,  'category/category-1-1-1-1.php',  'shop_search.php',  '12', ''),
(44,  'category/category-1-1-1-1-1.php',  'shop_search.php',  '13', ''),
(45,  'category/category-1-1-1-2-1.php',  'shop_search.php',  '14', ''),
(46,  'category/category-1-1-1-1-2.php',  'shop_search.php',  '15', ''),
(47,  'category/asus.php',  'shop_search.php',  '16', ''),
(48,  'category/hp.php',  'shop_search.php',  '17', ''),
(49,  'category/peripherals.php', 'shop_search.php',  '18', ''),
(50,  'category/smartphones.php', 'shop_search.php',  '19', ''),
(51,  'category/apple-2.php', 'shop_search.php',  '20', ''),
(52,  'category/nokia.php', 'shop_search.php',  '21', ''),
(53,  'category/kaiju-powder.php',  'shop_search.php',  '22', ''),
(54,  'category/kaiju-bones.php', 'shop_search.php',  '23', ''),
(55,  'category/used-jaegers.php',  'shop_search.php',  '24', ''),
(56,  'category/books.php', 'shop_search.php',  '25', ''),
(57,  'category/build-your-own.php',  'shop_search.php',  '26', ''),
(58,  'category/clothing.php',  'shop_search.php',  '27', '');

INSERT INTO `__PREFIX__products` (`cat_id`, `idx`, `sku`, `add_category`, `permalink`, `title`, `price`, `price_msrp`, `price_qty`, `details`, `weight`, `distro`, `list_date`, `keywords`, `stock`, `min_buy`, `max_buy`, `tax_class`, `see_also`, `sub_product`, `digital_file`, `is_call_for_price`, `is_invisible`, `stat_hits`, `stat_last_hit`, `stat_purchased`, `stat_last_purchased`, `smart_search`) VALUES
('3', 1,  '10001232', '', 'apple-ipod-touch-5g-32gb-black.php', 'Apple iPod Touch 5G - 32GB (Black)', 299.00, 0.00, 'a:0:{}', '<ul>\r\n<li>Basic product demo</li>\r\n<li>Multiple images</li>\r\n<li>Portable media player</li>\r\n<li>32GB storage</li>\r\n<li>4\" screen</li>\r\n</ul>\r\n<p>The new 5th Generation <strong>iPod touch</strong> brings you even more functionality and enjoyment in a cool black design.<br /><br />Not only does it store and play back all your favourite music, it has photo and video capture and playback functions included too, and can connect to the internet!<br /><br />Enjoy those special songs or recommended tunes from your vast iTunes library, and add to the magic from the iTunes store &ndash; the perfect song is just a tap away.<br /><br />The new <strong>iPod touch</strong> makes video content look even better, with true widescreen video playback. 44% higher saturation means colours pop out at you, and iTunes is right there for you download any movie or program you desire.<br /><br />What\'s more, the inclusion of the amazing iCloud technology means that all your favourite music, apps, films, programs and books you\'ve downloaded from iTunes is uploaded to a cloud space for access on all your other devices, doing away with cabling and manual syncs.</p>',  0.50, 152,  '2013-10-06', '', 100,  1,  0,  159,  '2',  '', '', '0',  '0',  0,  '2017-12-26', 0,  '0000-00-00', '10001232 apple ipod touch 5g 32gb black basic product demo multiple images portable media player storage 4 screen the new 5th generation brings you even more functionality and enjoyment in a cool designnot only does it store play back all your favourite music has photo video capture playback functions included too can connect to internetenjoy those special songs or recommended tunes from vast itunes library add magic ndash perfect song is just tap awaythe makes content look better with true widescreen 44 higher saturation means colours pop out at right there for download any movie program desirewhats inclusion of amazing icloud technology that apps films programs books youve downloaded uploaded cloud space access on other devices doing away cabling manual syncs 45 inch 1136x640 '),
('3', 2,  '10001332', '', 'apple-ipod-touch-5g-32gb-red-w-laser-engrave.php', 'Apple iPod Touch 5G - 32GB (Red) w/ Laser Engrave',  299.00, 0.00, 'a:0:{}', '<ul>\r\n<li>Simple product option demo</li>\r\n<li>Portable media player</li>\r\n<li>32GB storage</li>\r\n<li>4\" screen</li>\r\n</ul>\r\n<p>The new 5th Generation <strong>iPod touch</strong> brings you even more functionality and enjoyment in a cool black design.<br /><br />Not only does it store and play back all your favourite music, it has photo and video capture and playback functions included too, and can connect to the internet!<br /><br />Enjoy those special songs or recommended tunes from your vast iTunes library, and add to the magic from the iTunes store &ndash; the perfect song is just a tap away.<br /><br />The new <strong>iPod touch</strong> makes video content look even better, with true widescreen video playback. 44% higher saturation means colours pop out at you, and iTunes is right there for you download any movie or program you desire.<br /><br />What\'s more, the inclusion of the amazing iCloud technology means that all your favourite music, apps, films, programs and books you\'ve downloaded from iTunes is uploaded to a cloud space for access on all your other devices, doing away with cabling and manual syncs.</p>',  0.50, 152,  '2013-10-08', '', 100,  1,  0,  159,  '', '', '', '0',  '0',  0,  '2017-11-21', 1,  '2017-11-12', '10001332 apple ipod touch 5g 32gb red w laser engrave simple product option demo portable media player storage 4 screen the new 5th generation brings you even more functionality and enjoyment in a cool black designnot only does it store play back all your favourite music has photo video capture playback functions included too can connect to internetenjoy those special songs or recommended tunes from vast itunes library add magic ndash perfect song is just tap awaythe makes content look better with true widescreen 44 higher saturation means colours pop out at right there for download any movie program desirewhats inclusion of amazing icloud technology that apps films programs books youve downloaded uploaded cloud space access on other devices doing away cabling manual syncs 45 inch 1136x640 '),
('3', 3,  '10001364', '', 'apple-ipod-touch-5g-64gb-blue.php',  'Apple iPod Touch 5G - 64GB (Blue)',  399.00, 0.00, 'a:0:{}', '<ul>\r\n<li>Try to filter this product with 64GB internal storage</li>\r\n<li>Portable media player</li>\r\n<li>32GB storage</li>\r\n<li>4\" screen</li>\r\n</ul>\r\n<p>The new 5th Generation <strong>iPod touch</strong> brings you even more functionality and enjoyment in a cool black design.<br /><br />Not only does it store and play back all your favourite music, it has photo and video capture and playback functions included too, and can connect to the internet!<br /><br />Enjoy those special songs or recommended tunes from your vast iTunes library, and add to the magic from the iTunes store &ndash; the perfect song is just a tap away.<br /><br />The new <strong>iPod touch</strong> makes video content look even better, with true widescreen video playback. 44% higher saturation means colours pop out at you, and iTunes is right there for you download any movie or program you desire.<br /><br />What\'s more, the inclusion of the amazing iCloud technology means that all your favourite music, apps, films, programs and books you\'ve downloaded from iTunes is uploaded to a cloud space for access on all your other devices, doing away with cabling and manual syncs.</p>', 0.50, 0,  '2013-10-08', '', 100,  1,  0,  158,  '1,2',  '', '', '0',  '0',  0,  '2017-11-11', 1,  '2017-11-12', '10001364 apple ipod touch 5g 64gb blue try to filter this product with internal storage portable media player 32gb 4 screen the new 5th generation brings you even more functionality and enjoyment in a cool black designnot only does it store play back all your favourite music has photo video capture playback functions included too can connect internetenjoy those special songs or recommended tunes from vast itunes library add magic ndash perfect song is just tap awaythe makes content look better true widescreen 44 higher saturation means colours pop out at right there for download any movie program desirewhats inclusion of amazing icloud technology that apps films programs books youve downloaded uploaded cloud space access on other devices doing away cabling manual syncs 45 inch 1136x640 '),
('2', 4,  '1001358',  ',1,',  'new-apple-imac-21-5-quot.php', 'New Apple iMac 21.5&quot;',  1299.00,  0.00, 'a:0:{}', '<ul>\r\n<li>Multiple category</li>\r\n<li>Complex custom field</li>\r\n<li>Powerful desktop</li>\r\n<li>I wish someone buy this for me</li>\r\n</ul>\r\n<p>Breathtakingly slim, staggeringly powerful and unbelievably usable, the ultra-smooth new Apple iMac All-in-one is a thing of beauty that brings elegance and sophistication to home computing like no other desktop.</p>',  0.00, 152,  '2013-10-08', '', 100,  1,  0,  159,  '5,6',  '', '', '0',  '0',  0,  '2017-11-20', 1,  '2017-11-17', '1001358 new apple imac 215quot multiple category complex custom field powerful desktop i wish someone buy this for me breathtakingly slim staggeringly and unbelievably usable the ultrasmooth allinone is a thing of beauty that brings elegance sophistication to home computing like no other 20123quot 1920x1080 intel i5 8gb 1tb '),
('2', 5,  '', ',1,',  'new-apple-imac-27-quot.php', 'New Apple iMac 27&quot;',  1999.00,  0.00, 'a:0:{}', '<ul>\r\n<li>Multiple category</li>\r\n<li>Complex custom field</li>\r\n<li>Powerful desktop</li>\r\n<li>I wish someone buy this for me</li>\r\n</ul>\r\n<p>Breathtakingly slim, staggeringly powerful and unbelievably usable, the ultra-smooth new Apple iMac All-in-one is a thing of beauty that brings elegance and sophistication to home computing like no other desktop.</p>',  11.00,  152,  '2013-10-09', '', 100,  1,  0,  159,  '4',  '', '', '0',  '0',  0,  '2017-12-24', 0,  '0000-00-00', ' new apple imac 27quot multiple category complex custom field powerful desktop i wish someone buy this for me breathtakingly slim staggeringly and unbelievably usable the ultrasmooth allinone is a thing of beauty that brings elegance sophistication to home computing like no other gt231quot 1920x1080 intel i5 8gb 1tb'),
('2', 6,  '', ',1,',  'mac-mini.php', 'Mac Mini', 0.00, 0.00, 'a:0:{}', '<ul>\r\n<li>Call For Price demo</li>\r\n<li>Try to filter this product by internal storage</li>\r\n</ul>\r\n<p>Nothing short of amazing, the 2.5 GHz Apple MD387B/A Mac Mini is an excellent solution for your computing needs.</p>\r\n<p>All you have to do to get started is plug in your own display, keyboard and mouse, to the 2.5 GHz Mac Mini turn it on and everything works together as one, even when using third party peripherals!</p>', 3.00, 152,  '2013-10-09', '', 100,  1,  0,  159,  '', '', '', '1',  '0',  0,  '2017-11-17', 0,  '0000-00-00', ' mac mini call for price demo try to filter this product by internal storage nothing short of amazing the 25 ghz apple md387ba is an excellent solution your computing needs all you have do get started plug in own display keyboard and mouse turn it on everything works together as one even when using third party peripherals na intel i5 4gb 500gb'),
('16',  7,  '', ',1,',  'asus-zenbook-13-3-quot-touchscreen-ultrabook.php', 'ASUS Zenbook 13.3&quot; Touchscreen Ultrabook',  1799.00,  1899.00,  'a:0:{}', '<p>Weighing just 1.45 kg and with more than 7 hours of battery life, the sleek Asus Zenbook UX31A-C4027H 13.3\" Touchscreen Ultrabook&trade; is perfectly designed to travel everywhere with you.</p>\r\n<p>The Zenbook UX31A includes the Windows 8 operating system, helping you to keep in touch with everything that\'s important to you.</p>',  2.50, 155,  '2013-10-09', '', 100,  1,  0,  159,  '', '', '', '0',  '0',  0,  '2017-12-18', 2,  '2017-12-18', ' asus zenbook 133quot touchscreen ultrabook weighing just 145 kg and with more than 7 hours of battery life the sleek ux31ac4027h 133 ultrabooktrade is perfectly designed to travel everywhere you ux31a includes windows 8 operating system helping keep in touch everything thats important 12114quot 1920x1080 intel i7 4gb 256gb ssd 517'),
('16',  8,  '', ',1,',  'asus-v550ca-15-6-quot-touchscreen-laptop.php', 'ASUS V550CA 15.6&quot; Touchscreen Laptop',  599.00, 0.00, 'a:0:{}', '<p>Sleek, slim and packed with high powered hardware, the Asus V550CA-CJ104H 15.6\" Touchscreen Laptop helps you to keep in touch with everything that\'s important to you.</p>\r\n<p>Based on the Windows 7 operating system, the new Windows 8 operating system allows you to keep your digital world close to you. The new Start screen features a tiled design with tiles that update in real-time, so you can see updates from social networks, news feeds and more as and when they happen.</p>',  3.00, 155,  '2013-10-10', '', 0,  1,  0,  159,  '7',  '', '', '0',  '0',  0,  '2017-11-20', 0,  '0000-00-00', ' asus v550ca 156quot touchscreen laptop sleek slim and packed with high powered hardware the v550cacj104h 156 helps you to keep in touch everything thats important based on windows 7 operating system new 8 allows your digital world close start screen features a tiled design tiles that update realtime so can see updates from social networks news feeds more as when they happen 14117quot 1366x768 intel i3 8gb 1tb 45 hours'),
('17',  9,  '', ',1,',  'hp-envy-m6-1310sa-15-6-quot.php',  'HP ENVY m6-1310sa 15.6&quot;', 699.00, 749.00, 'a:0:{}', '<p>When you\'re looking to get premium performance from a laptop, consider the HP ENVY m6-1310 15.6\" Laptop. Packed full of features and with a thin, stylish design it boasts a quad core AMD A10-5750M processor, 8 GB of RAM and 2 GB of dedicated graphics memory, meaning that no matter what you like to do while mobile the ENVY m6 will have you covered.</p>\r\n<p>With a quad core AMD A10-5750M processor sitting at its heart, the m6-1310sa packs some serious processing punch. Whether you plan on using your laptop for business or pleasure, you can be assured that you\'ll always have the power you need to fly through tasks and programmes with ease.</p>', 3.00, 153,  '2013-10-10', '', 100,  1,  0,  159,  '', '', '', '0',  '0',  0,  '2017-11-14', 0,  '0000-00-00', ' hp envy m61310sa 156quot when youre looking to get premium performance from a laptop consider the m61310 156 packed full of features and with thin stylish design it boasts quad core amd a105750m processor 8 gb ram 2 dedicated graphics memory meaning that no matter what you like do while mobile m6 will have covered sitting at its heart packs some serious processing punch whether plan on using your for business or pleasure can be assured youll always power need fly through tasks programmes ease 14117quot 1366x768 aseries 8gb 1tb 719 hours'),
('17',  10, '', ',1,',  'hp-envy-17-j053ea-17.php', 'HP ENVY 17-j053ea 17”',  999.00, 1299.00,  'a:0:{}', '<p>With exceptional processing, graphics, storage and design, the HP ENVY 17-j053ea 17&rdquo; Laptop is built for serious mobile computing.</p>\r\n<p>The ENVY 17 is powered by an incredibly potent quad-core Intel&reg; Core&trade; i7-4700MQ processor. The everyday running speed of 2.4 GHz is more than enough for everyday tasks though it\'s capable of reaching a desktop-rivalling 3.4 GHz when required.</p>',  4.00, 153,  '2013-10-10', '', 100,  1,  0,  159,  '9',  '', '', '0',  '0',  0,  '2017-12-18', 5,  '2013-10-27', ' hp envy 17j053ea 17 with exceptional processing graphics storage and design the 17rdquo laptop is built for serious mobile computing powered by an incredibly potent quadcore intelreg coretrade i74700mq processor everyday running speed of 24 ghz more than enough tasks though its capable reaching a desktoprivalling 34 when required 17120quot 1920x1080 intel i7 12gb 1tb 517 hours'),
('18',  11, '', '', 'wd-internal-hdd-2-5-quot-500gb.php', 'WD Internal HDD 2.5&quot; - 500GB',  99.00,  0.00, 'a:0:{}', '<p>This Western Digital Internal 2.5\" SATA Hard Drive boasts a generous 500GB capacity, enabling storage of approximately 125,000 music files, or 100,000 digital photos!</p>\r\n<p>Incorporating advanced technologies such as WhisperDrive and SecurePark, the Internal 2.5\" SATA Hard Drive is incredibly quiet and energy-efficient in use, which makes it an exceptionally good choice of laptop drive.</p>', 1.00, 0,  '2013-10-10', '', 100,  1,  0,  158,  '', '', '', '0',  '0',  0,  '0000-00-00', 0,  '0000-00-00', ' wd internal hdd 25quot 500gb this western digital 25 sata hard drive boasts a generous capacity enabling storage of approximately 125000 music files or 100000 photos incorporating advanced technologies such as whisperdrive and securepark the is incredibly quiet energyefficient in use which makes it an exceptionally good choice laptop'),
('18',  12, '', '', 'wd-internal-hdd-2-5-quot-1tb.php', 'WD Internal HDD 2.5&quot; - 1TB',  179.00, 0.00, 'a:0:{}', '<p>This Western Digital Internal 2.5\" SATA Hard Drive boasts a generous 1000GB capacity, enabling storage of approximately 250,000 music files, or 200,000 digital photos!</p>\r\n<p>Incorporating advanced technologies such as WhisperDrive and SecurePark, the Internal 2.5\" SATA Hard Drive is incredibly quiet and energy-efficient in use, which makes it an exceptionally good choice of laptop drive.</p>',  1.00, 0,  '2013-10-10', '', 100,  1,  0,  158,  '', '', '', '0',  '0',  0,  '2013-11-04', 4,  '2013-10-24', ' wd internal hdd 25quot 1tb this western digital 25 sata hard drive boasts a generous 1000gb capacity enabling storage of approximately 250000 music files or 200000 photos incorporating advanced technologies such as whisperdrive and securepark the is incredibly quiet energyefficient in use which makes it an exceptionally good choice laptop'),
('26',  13, '10001000', '', 'build-your-own-pc.php',  'Build Your Own PC',  599.00, 0.00, 'a:0:{}', '<p>This product is to demonstrate how \"sub product\" can be used as \"build your own\" product.</p>\r\n<p>In this product, you can buy several other components directly, eg: hdd, monitor &amp; RAM, without visiting each product page separately. Then the sub-products will be added when you add the main product to shopping cart.</p>\r\n<p>Build your own PC easily. We offer the barebone PC where you can customize the peripherals easily. The PC specificiations are:</p>\r\n<ul>\r\n<li>Really awesome computer case</li>\r\n<li>AMD A10-6800 4.1GHz quad core processor</li>\r\n<li>Integrated high performace VGA ATI Radeon 8670D</li>\r\n<li>Bluray driver</li>\r\n<li>Standard keyboard, mouse &amp; speakers</li>\r\n<li>Microsoft Windows 8</li>\r\n<li>HDD: customize</li>\r\n<li>RAM: customize</li>\r\n<li>Monitor: customize</li>\r\n<li>Start customizing, click \"Buy Together\" tab.</li>\r\n</ul>', 5.00, 0,  '2013-10-10', '', 100,  1,  0,  158,  '', 'a:3:{i:0;a:2:{s:5:\"title\";s:9:\"Hard Disk\";s:6:\"member\";s:5:\"12,11\";}i:1;a:2:{s:5:\"title\";s:3:\"RAM\";s:6:\"member\";s:5:\"21,14\";}i:2;a:2:{s:5:\"title\";s:7:\"Monitor\";s:6:\"member\";s:5:\"22,23\";}}',  '', '0',  '0',  3,  '2018-03-02', 2,  '2013-10-24', '10001000 build your own pc this product is to demonstrate how sub can be used as in you buy several other components directly eg hdd monitor amp ram without visiting each page separately then the subproducts will added when add main shopping cart easily we offer barebone where customize peripherals specificiations are really awesome computer case amd a106800 41ghz quad core processor integrated high performace vga ati radeon 8670d bluray driver standard keyboard mouse speakers microsoft windows 8 start customizing click together tab '),
('18',  14, '', '', 'patriot-signature-line-pc3-10600-ddr3-pc-memory-8gb-dimm-ram.php', 'PATRIOT Signature Line PC3-10600 DDR3 PC Memory - 8GB DIMM RAM', 69.00,  0.00, 'a:0:{}', '<p>Easy to install, high performance and providing the RAM you need to multitask, the Patriot Signature Line DDR3 PC Memory is perfectly designed give your PC a boost.</p>\r\n<p>Ideal for today\'s demanding computer needs, the Signature PSD38G13332 is designed to run at 1333 MHz, and has been tested to make sure it meets industry standards.</p>', 0.50, 0,  '2013-10-11', '', 100,  1,  0,  158,  '', '', '', '0',  '0',  0,  '2017-12-18', 0,  '0000-00-00', ' patriot signature line pc310600 ddr3 pc memory 8gb dimm ram easy to install high performance and providing the you need multitask is perfectly designed give your a boost ideal for todays demanding computer needs psd38g13332 run at 1333 mhz has been tested make sure it meets industry standards'),
('18',  21, '', '', 'patriot-signature-line-pc3-10600-ddr3-pc-memory-16gb-dimm-ram.php',  'PATRIOT Signature Line PC3-10600 DDR3 PC Memory - 16GB DIMM RAM',  129.00, 0.00, 'a:0:{}', '<p>Easy to install, high performance and providing the RAM you need to multitask, the Patriot Signature Line DDR3 PC Memory is perfectly designed give your PC a boost.</p>\r\n<p>Ideal for today\'s demanding computer needs, the Signature PSD38G13332 is designed to run at 1333 MHz, and has been tested to make sure it meets industry standards.</p>', 0.50, 0,  '2013-10-11', '', 100,  1,  0,  158,  '', '', '', '0',  '0',  0,  '0000-00-00', 2,  '2013-10-24', ' patriot signature line pc310600 ddr3 pc memory 16gb dimm ram easy to install high performance and providing the you need multitask is perfectly designed give your a boost ideal for todays demanding computer needs psd38g13332 run at 1333 mhz has been tested make sure it meets industry standards'),
('18',  22, '', '', 'lg-22en33s-full-hd-21-5-quot-led-monitor.php', 'LG 22EN33S Full HD 21.5&quot; LED Monitor',  149.00, 169.00, 'a:0:{}', '<p>Discover cutting-edge technology in a slim form and at an affordable price with the LG 22EN33S Full HD 21.5\" LED Monitor.</p>\r\n<p>The alluring LED monitor offers Full HD (1080p) picture quality that fills your screen with glittering colours and unprecedented sharpness.</p>\r\n<p>Navigate around your screen with excellent colour accuracy and visual contrast; enjoy movies, browsing and everything else in crystal clear quality.</p>', 3.00, 0,  '2013-10-12', '', 100,  1,  0,  158,  '', '', '', '0',  '0',  0,  '2013-10-18', 0,  '0000-00-00', ' lg 22en33s full hd 215quot led monitor discover cuttingedge technology in a slim form and at an affordable price with the 215 alluring offers 1080p picture quality that fills your screen glittering colours unprecedented sharpness navigate around excellent colour accuracy visual contrast enjoy movies browsing everything else crystal clear'),
('18',  23, '', '', 'lg-d2343p-full-hd-23-quot-3d-ips-led-monitor.php', 'LG D2343P Full HD 23&quot; 3D IPS LED Monitor',  199.00, 219.00, 'a:0:{}', '<p>Experience next-level picture quality with the LG D2343P Full HD 23\" 3D IPS LED Monitor.&nbsp;&nbsp; Uncompromised quality&nbsp;&nbsp; With a full High Definition 1080p display, the D2343P gives you your favourite media at its very best. The sharp contrast, vivid colours and deep blacks deliver a detailed, natural and highly refined picture you won\'t believe.&nbsp;&nbsp; The D2343P features IPS screen technology, letting you enjoy lifelike pictures at any angle. By reducing colour shift, you can be sure of the best quality HD picture no matter where you choose to place your monitor.&nbsp;&nbsp; Cinema 3D&nbsp;&nbsp; LG\'s Cinema monitors support 3D media. From games to 3D Blu-ray, the D2343P offers an unparalleled, immersive experience with outstanding depth.&nbsp;&nbsp;&nbsp; No 3D source? Don\'t worry, the D2343P can convert your 2D films, games and pictures into high quality 3D experiences, displaying your favourite games and films like never before.&nbsp;&nbsp; A pair of 3D glasses is included with the unit, letting you jump right into the immersive world of 3D.&nbsp;&nbsp; Convenient connectivity&nbsp;&nbsp;&nbsp; Regardless of your preferred connection, you can make the most of your media with the D2343P.</p>',  3.00, 0,  '2013-10-12', '', 100,  1,  0,  158,  '', '', '', '0',  '0',  0,  '2017-11-20', 2,  '2013-10-24', ' lg d2343p full hd 23quot 3d ips led monitor experience nextlevel picture quality with the 23 monitornbspnbsp uncompromised qualitynbspnbsp a high definition 1080p display gives you your favourite media at its very best sharp contrast vivid colours and deep blacks deliver detailed natural highly refined wont believenbspnbsp features screen technology letting enjoy lifelike pictures any angle by reducing colour shift can be sure of no matter where choose to place cinema 3dnbspnbsp lgs monitors support from games bluray offers an unparalleled immersive outstanding depthnbspnbspnbsp source dont worry convert 2d films into experiences displaying like never beforenbspnbsp pair glasses is included unit jump right world convenient connectivitynbspnbspnbsp regardless preferred connection make most'),
('27',  24, '', '', 'create-your-own-t-shirt.php',  'Create Your Own T-Shirt',  15.00,  0.00, 'a:4:{i:12;s:2:\"13\";i:24;s:2:\"12\";i:36;s:2:\"11\";i:48;s:2:\"10\";}', '<p>Demonstration of multi level pricing based on purchased quantity.</p>\r\n<p>You can design your own t-shirt by using original artwork and words. You can create not only tee shirts but hoody jackets, baseball caps, cups, mugs, mousepads, sweatshirts and tote bags. You\'re assured of getting exactly what you want with our money-back guarantee. Design a funny, religious or team t shirt in just minutes. Your order will arrive in 14 days, guaranteed. There\'s a rush service, too, in case you need your order in 7 days. Don\'t worry about ordering online if you\'ve never done so before - our sales and service team is available seven days a week to assist with your design. Design your own tee shirts, sweatshirts and more.</p>', 0.20, 0,  '2013-10-13', '', 100,  6,  0,  159,  '13', '', '', '0',  '0',  0,  '2017-11-11', 0,  '0000-00-00', ' create your own tshirt this item is to demostrate how you can use product options allow visitors customize their products design by using original artwork and words not only tee shirts but hoody jackets baseball caps cups mugs mousepads sweatshirts tote bags youre assured of getting exactly what want with our moneyback guarantee a funny religious or team t shirt in just minutes order will arrive 14 days guaranteed theres rush service too case need 7 dont worry about ordering online if youve never done so before sales available seven week assist more'),
('27',  25, '', '', 'create-your-own-long-sleeve.php',  'Create Your Own Long Sleeve',  17.00,  0.00, 'a:4:{i:12;s:2:\"16\";i:24;s:2:\"15\";i:36;s:2:\"14\";i:48;s:2:\"13\";}', '<p>Demonstration of multi level pricing based on purchased quantity.</p>\r\n<p>You can design your own t-shirt by using original artwork and words. You can create not only tee shirts but hoody jackets, baseball caps, cups, mugs, mousepads, sweatshirts and tote bags. You\'re assured of getting exactly what you want with our money-back guarantee. Design a funny, religious or team t shirt in just minutes. Your order will arrive in 14 days, guaranteed. There\'s a rush service, too, in case you need your order in 7 days. Don\'t worry about ordering online if you\'ve never done so before - our sales and service team is available seven days a week to assist with your design. Design your own tee shirts, sweatshirts and more.</p>', 0.20, 0,  '2013-10-13', '', 100,  6,  0,  158,  '13', '', '', '0',  '0',  1,  '2017-12-26', 0,  '0000-00-00', ' create your own long sleeve this item is to demostrate how you can use product options allow visitors customize their products design tshirt by using original artwork and words not only tee shirts but hoody jackets baseball caps cups mugs mousepads sweatshirts tote bags youre assured of getting exactly what want with our moneyback guarantee a funny religious or team t shirt in just minutes order will arrive 14 days guaranteed theres rush service too case need 7 dont worry about ordering online if youve never done so before sales available seven week assist more'),
('25',  26, '', '', 'special-edition-harry-potter-paperback-box-set.php', 'Special Edition Harry Potter Paperback Box Set', 58.50,  100.00, 'a:0:{}', '<p>More custom field demo. You can search this product by searching for its <strong>AUTHOR</strong> name OR ISBN instead of its title.</p>\r\n<p>The perfect gift for collectors and new readers alike, we now present a breathtaking special edition boxed set of J. K. Rowling\'s seven bestselling Harry Potter books! The box itself is beautifully designed with new artwork by Kazu Kibuishi, and the books create a gorgeous, magical vista when the spines are lined up together. The Harry Potter series has been hailed as \"one for the ages\" by Stephen King and \"a spellbinding saga\" by USA Today. Now is your chance to give this set to a reader who is ready to embark on the series that has changed so many young readers\' lives.</p>', 3.80, 0,  '2013-10-13', '', 100,  1,  0,  159,  '', '', '', '0',  '0',  0,  '2017-11-08', 0,  '0000-00-00', ' special edition harry potter paperback box set more custom field demo you can search this product by searching for its author name or isbn instead of title the perfect gift collectors and new readers alike we now present a breathtaking boxed j k rowlings seven bestselling books itself is beautifully designed with artwork kazu kibuishi create gorgeous magical vista when spines are lined up together series has been hailed as one ages stephen king spellbinding saga usa today your chance to give reader who ready embark on that changed so many young lives jk rowling 0545596270'),
('25',  27, '', '', 'pacific-rim-the-official-movie-novelization.php',  'Pacific Rim: The Official Movie Novelization', 7.19, 7.99, 'a:0:{}', '<p>More custom field demo. You can search this product by searching for its <strong>AUTHOR</strong> name OR ISBN instead of its title.</p>\r\n<p>The official novelization of the upcoming sci-fi blockbuster Pacific Rim from visionary director Guillermo del Toro!</p>\r\n<p>When legions of monstrous creatures, known as Kaiju, started rising from the sea, a war began that would take millions of lives and consume humanity\'s resources for years on end. To combat the giant Kaiju, a special type of weapon was devised: massive robots, called Jaegers, which are controlled simultaneously by two pilots whose minds are locked in a neural bridge. But even the Jaegers are proving nearly defenseless in the face of the relentless Kaiju.</p>', 0.20, 0,  '2013-10-13', '', 100,  1,  0,  160,  '', '', '', '0',  '0',  0,  '2017-12-18', 0,  '0000-00-00', ' pacific rim the official movie novelization more custom field demo you can search this product by searching for its author name or isbn instead of title upcoming scifi blockbuster from visionary director guillermo del toro when legions monstrous creatures known as kaiju started rising sea a war began that would take millions lives and consume humanitys resources years on end to combat giant special type weapon was devised massive robots called jaegers which are controlled simultaneously two pilots whose minds locked in neural bridge but even proving nearly defenseless face relentless alexander irvine 1781166781'),
('25',  28, '', '', 'pacific-rim-the-official-movie-novelization-ebook-digital-product.php',  'Pacific Rim: The Official Movie Novelization (ebook) -- Digital Product',  4.19, 4.99, 'a:0:{}', '<p>Digital product demo. More custom field demo. You can search this product by searching for its <strong>AUTHOR</strong> name OR ISBN instead of its title.</p>\r\n<p>The official novelization of the upcoming sci-fi blockbuster Pacific Rim from visionary director Guillermo del Toro!</p>\r\n<p>When legions of monstrous creatures, known as Kaiju, started rising from the sea, a war began that would take millions of lives and consume humanity\'s resources for years on end. To combat the giant Kaiju, a special type of weapon was devised: massive robots, called Jaegers, which are controlled simultaneously by two pilots whose minds are locked in a neural bridge. But even the Jaegers are proving nearly defenseless in the face of the relentless Kaiju.</p>\r\n<p>This is an ebook, you will need a compatible program to read this book.</p>', 0.00, 0,  '2013-10-13', '', 100,  1,  0,  159,  '', '', '_sample.zip',  '0',  '0',  0,  '2017-12-24', 0,  '0000-00-00', ' pacific rim the official movie novelization ebook digital product demo more custom field you can search this by searching for its author name or isbn instead of title upcoming scifi blockbuster from visionary director guillermo del toro when legions monstrous creatures known as kaiju started rising sea a war began that would take millions lives and consume humanitys resources years on end to combat giant special type weapon was devised massive robots called jaegers which are controlled simultaneously two pilots whose minds locked in neural bridge but even proving nearly defenseless face relentless is an will need compatible program read book alexander irvine 1781166781');

INSERT INTO `__PREFIX__product_cat` (`parent_id`, `idx`, `cat_name`, `cat_details`, `cat_image`, `cat_keywords`, `cat_featured`, `cat_page`, `menu_mid`, `permalink`, `subcat_order`) VALUES
(0, 1,  'Computing',  '', 'Laptop_256.jpg', '', '10,8,4', '', 12, 'category/computing.php', ''),
(1, 2,  'Apple',  '', 'Apple_logo_black.svg.png', '', '', '', 13, 'category/apple.php', ''),
(2, 3,  'iPod', '', 'iPod-touch-menu_256.jpg',  '', '', '', 14, 'category/ipod.php',  ''),
(0, 6,  'Deep Category',  '', '', '', '', '', 17, 'category/deep-category.php', ''),
(6, 7,  'Category 1-1', '', '', '', '', '', 18, 'category/category-1-1.php',  ''),
(6, 8,  'Category 1-2', '', '', '', '', '', 19, 'category/category-1-2.php',  ''),
(6, 9,  'Category 1-3', '', '', '', '', '', 20, 'category/category-1-3-2.php',  ''),
(7, 10, 'Category 1-1-1', '', '', '', '', '', 21, 'category/category-1-1-1.php',  ''),
(7, 11, 'Category 1-1-2', '', '', '', '', '', 22, 'category/category-1-1-2.php',  ''),
(10,  12, 'Category 1-1-1-1', '', '', '', '', '', 23, 'category/category-1-1-1-1.php',  ''),
(12,  13, 'Category 1-1-1-1-1', '', '', '', '', '', 24, 'category/category-1-1-1-1-1.php',  ''),
(11,  14, 'Category 1-1-1-2-1', '', '', '', '', '', 25, 'category/category-1-1-1-2-1.php',  ''),
(12,  15, 'Category 1-1-1-1-2', '', '', '', '', '', 26, 'category/category-1-1-1-1-2.php',  ''),
(1, 16, 'Asus', '', '200px-ASUS_Logo.svg.jpg',  '', '', '', 27, 'category/asus.php',  ''),
(1, 17, 'HP', '', 'HP_New_Logo_2D.svg.jpg', '', '', '', 28, 'category/hp.php',  ''),
(1, 18, 'Peripherals',  '', '', '', '', '', 29, 'category/peripherals.php', ''),
(0, 19, 'Smartphones',  '', '', '', '', '', 30, 'category/smartphones.php', ''),
(19,  20, 'Apple',  '', '', '', '', '', 31, 'category/apple-2.php', ''),
(19,  21, 'Nokia',  '', '', '', '', '', 32, 'category/nokia.php', ''),
(12,  22, 'Kaiju Powder', '', '', '', '', '', 33, 'category/kaiju-powder.php',  ''),
(12,  23, 'Kaiju Bones',  '', '', '', '', '', 34, 'category/kaiju-bones.php', ''),
(12,  24, 'Used Jaegers', '', 'ppdc.jpg', '', '', '', 35, 'category/used-jaegers.php',  ''),
(0, 25, 'Books',  '', '', '', '', '', 36, 'category/books.php', ''),
(1, 26, 'Build Your Own', '<p>Just a demo to show how sub-product works.</p>',  '', '', '', '', 37, 'category/build-your-own.php',  ''),
(0, 27, 'Clothing', '', '', '', '', '', 38, 'category/clothing.php',  '');

INSERT INTO `__PREFIX__product_cf_define` (`idx`, `cf_category`, `cf_title`, `cf_type`, `cf_option`, `cf_help`, `is_searchable`, `is_list`, `menu_idx`, `menu_item_id`, `is_removed`) VALUES
(1, ',3,19,20,21,', 'Screen Size',  'select', '&lt;4 inch\r\n4-5 inch\r\n5.1-7 inch\r\n7.1-9 inch\r\n&gt;9 inch', 'For smartphones &amp; small devices',  '1',  '1',  8,  45, '0'),
(2, ',3,19,20,21,', 'Screen Resolution',  'select', '480x800\r\n1024x768\r\n1136x640\r\n1280x1080\r\n2048x1536',  'For smartphones &amp; small devices',  '1',  '0',  8,  46, '0'),
(3, ',3,19,20,21,', 'Color',  'select', 'White\r\nBlack\r\nBlue\r\nGreen\r\nRed', '', '1',  '1',  8,  41, '0'),
(4, '', 'Product Dimension',  'varchar',  '', '', '0',  '0',  8,  42, '0'),
(5, '', 'Product Weight', 'varchar',  '', '', '0',  '0',  8,  43, '0'),
(6, ',3,19,20,21,', 'Internal Storage', 'select', 'None\r\n4GB\r\n8GB\r\n16GB\r\n32GB\r\n64GB\r\n128GB\r\n256GB', '', '1',  '0',  8,  50, '0'),
(7, ',1,2,16,17,',  'Screen Size',  'select', 'N/A\r\n&lt;10&quot;\r\n10-12&quot;\r\n12.1-14&quot;\r\n14.1-17&quot;\r\n17.1-20&quot;\r\n20.1-23&quot;\r\n&gt;23.1&quot;', 'For pc, laptop, etc',  '1',  '0',  8,  0,  '0'),
(8, ',1,2,16,17,',  'Screen Resolution',  'select', '1024x600\r\n1280x768\r\n1366x768\r\n1600x900\r\n1920x1080',  'For pc, laptop, etc',  '1',  '0',  8,  0,  '0'),
(9, ',1,2,16,17,',  'Processor',  'select', 'Intel i3\r\nIntel i5\r\nIntel i7\r\nAMD FX-series\r\nAMD A-series\r\nAMD Phenom',  '', '1',  '0',  8,  47, '0'),
(10,  ',1,2,16,17,',  'Processor Speed',  'varchar',  '', '', '0',  '0',  8,  48, '0'),
(11,  ',1,2,16,17,',  'RAM',  'select', '4GB\r\n8GB\r\n12GB\r\n16GB\r\n32GB', '', '1',  '0',  8,  49, '0'),
(12,  ',1,2,16,17,',  'Internal Storage', 'select', '256GB SSD\r\n500GB\r\n1TB\r\n1.5TB\r\n2TB\r\n3TB', '', '1',  '0',  8,  0,  '0'),
(13,  ',1,2,3,16,17,',  'Battery Life', 'select', '&lt; 4 hours\r\n4-5 hours\r\n5.1-7 hours\r\n7.1-9 hours\r\n&gt;9.1 hours', '', '1',  '0',  8,  51, '0'),
(14,  ',25,', 'Author', 'varchar',  '', '', '1',  '1',  8,  52, '0'),
(15,  ',25,', 'ISBN', 'varchar',  '', '', '1',  '0',  8,  53, '0');

INSERT INTO `__PREFIX__product_cf_value` (`idx`, `item_id`, `cf_1`, `cf_2`, `cf_3`, `cf_4`, `cf_5`, `cf_6`, `cf_7`, `cf_8`, `cf_9`, `cf_10`, `cf_11`, `cf_12`, `cf_13`, `cf_14`, `cf_15`) VALUES
(1, 1,  '4-5 inch', '1136x640', 'Black',  '123.4 x 58.6 x 6.1 mm',  '88 gr',  '32GB', '', '', '', '', '', '', '', '', ''),
(2, 2,  '4-5 inch', '1136x640', 'Red',  '123.4 x 58.6 x 6.1 mm',  '88 gr',  '32GB', '', '', '', '', '', '', '', '', ''),
(3, 3,  '4-5 inch', '1136x640', 'Blue', '123.4 x 58.6 x 6.1 mm',  '88 gr',  '64GB', '', '', '', '', '', '', '', '', ''),
(4, 4,  '', '', '', '450 x 528 x 175 mm', '5.68 kg',  '', '20.1-23&quot;',  '1920x1080',  'Intel i5', '2.7GHz quad-core', '8GB',  '1TB',  '', '', ''),
(5, 5,  '', '', '', '516 x 650 x 203 mm', '11 kg',  '', '&gt;23.1&quot;', '1920x1080',  'Intel i5', '3.4GHz quad-core', '8GB',  '1TB',  '', '', ''),
(6, 6,  '', '', '', '36 x 197 x 197 mm',  '1.22 kg',  '', 'N/A',  '', 'Intel i5', '2.5 GHz dual-core',  '4GB',  '500GB',  '', '', ''),
(7, 7,  '', '', '', '233 x 325 x 233 mm', '1.45 kg',  '', '12.1-14&quot;',  '1920x1080',  'Intel i7', '1.9 GHz',  '4GB',  '256GB SSD',  '5.1-7 hours',  '', ''),
(8, 8,  '', '', '', '22 x 266 x 266 mm',  '2.6 kg', '', '14.1-17&quot;',  '1366x768', 'Intel i3', '1.4 Ghz',  '8GB',  '1TB',  '4-5 hours',  '', ''),
(9, 9,  '', '', '', '26.5 x 379.5 x 250.7 mm',  '2.45', '', '14.1-17&quot;',  '1366x768', 'AMD A-series', '2.5 GHz quad-core',  '8GB',  '1TB',  '7.1-9 hours',  '', ''),
(10,  10, '', '', '', '30.6 x 416 x 274.4 mm',  '2.85 kg',  '', '17.1-20&quot;',  '1920x1080',  'Intel i7', '2.4 GHz quad-core',  '12GB', '1TB',  '5.1-7 hours',  '', ''),
(11,  11, '', '', '', '', '0.5 kg', '', '', '', '', '', '', '', '', '', ''),
(12,  12, '', '', '', '', '0.5 kg', '', '', '', '', '', '', '', '', '', ''),
(13,  13, '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(14,  14, '', '', '', '240 pins', '0.1 kg', '', '', '', '', '', '', '', '', '', ''),
(15,  21, '', '', '', '240 pins', '0.1 kg', '', '', '', '', '', '', '', '', '', ''),
(16,  22, '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(17,  23, '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(18,  24, '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(19,  25, '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(20,  26, '', '', '', '8.3 x 5.3 x 8.1 inches', '', '', '', '', '', '', '', '', '', 'J.K. Rowling', '0545596270'),
(21,  27, '', '', '', '1.1 x 4.2 x 7 inches', '', '', '', '', '', '', '', '', '', 'Alexander Irvine', '1781166781'),
(22,  28, '', '', '', '1.1 x 4.2 x 7 inches', '', '', '', '', '', '', '', '', '', 'Alexander Irvine', '1781166781');

INSERT INTO `__PREFIX__qcomment_set` (`group_id`, `comment_mode`, `comment_approval`, `member_only`, `unique_comment`, `comment_helpful`, `comment_on_comment`, `captcha`, `detail`, `mod_id`, `notes`) VALUES
(1, '2',  '0',  '0',  '0',  '0',  '1',  '0',  '0',  'conc', 'Comments on comments'),
(3, '2',  '1',  '0',  '0',  '0',  '1',  '0',  '1',  'pagecomment',  'Page Comment'),
(4, '3',  '1',  '1',  '1',  '1',  '1',  '0',  '1',  'product',  'Products');