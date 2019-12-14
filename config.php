<?php
// HTTP
define('HTTP_SERVER', 'http://192.168.1.150/shop/');

// HTTPS
define('HTTPS_SERVER', 'http://192.168.1.150/shop/');

// DIR
define('DIR_APPLICATION', '/var/www/html/shop/catalog/');
define('DIR_SYSTEM', '/var/www/html/shop/system/');
define('DIR_LANGUAGE', '/var/www/html/shop/catalog/language/');
define('DIR_TEMPLATE', '/var/www/html/shop/catalog/view/theme/');
define('DIR_CONFIG', '/var/www/html/shop/system/config/');
define('DIR_IMAGE', '/var/www/html/shop/image/');
define('DIR_CACHE', '/var/www/html/shop/system/cache/');
define('DIR_DOWNLOAD', '/var/www/html/shop/system/download/');
define('DIR_UPLOAD', '/var/www/html/shop/system/upload/');
define('DIR_MODIFICATION', '/var/www/html/shop/system/modification/');
define('DIR_LOGS', '/var/www/html/shop/system/logs/');

// DB
/*define('DB_DRIVER', 'mpdo');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'unnati');
define('DB_PASSWORD', 'unnati@aksha');
define('DB_DATABASE', 'shop');
define('DB_PREFIX', 'oc_');*/
 define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', '127.0.0.1');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'Mysql#321');
define('DB_DATABASE', 'shop');
 define('DB_PREFIX', 'oc_');
 

//sms
define('SMS_USERNAME', 'akshamaala4');
define('SMS_PASSWORD', 'akshamaala4');
define('SMS_DISPLAYNAME', 'UNNATI');
define('SMS_HOSTNAME', 'https://www.smscountry.com/SMSCwebservice.asp');

define('CASH_IN_HAND_MIN_AMOUNT_FORM_SMS', '500'); 

/* sms-country
//sms
define('SMS_USERNAME', 'akshamaala');
define('SMS_PASSWORD', 'aksha123@');
define('SMS_DISPLAYNAME', 'UNNATI');
define('SMS_HOSTNAME', 'http://websms.itfisms.com/vendorsms/pushsms.aspx');
*/
