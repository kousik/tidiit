<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


//define('SITE_SUB_DOMAIN','daily_plaza/');
define('SITE_SUB_DOMAIN','');
define('CAPTCHA_COOKIE_NAME','secret');
define('BASE_URL','http://'.$_SERVER['HTTP_HOST'].'/'.SITE_SUB_DOMAIN);
define('SELLER_URL','http://seller.tidiit.com/');
define('FE_BASE_URL',BASE_URL);
define('ADMIN_BASE_URL',FE_BASE_URL.'webadmin/');
define('REAL_URL',BASE_URL);
define('SiteResourcesURL',BASE_URL.'resources/');
define('SELLER_SITE_RESOURCES_URL',SELLER_URL.'resources/');
define('SiteImagesURL',SiteResourcesURL.'images/');
define('SiteCSSURL',SiteResourcesURL.'css/');
define('SiteJSURL',SiteResourcesURL.'js/');
define('ResourcesPath',$_SERVER['DOCUMENT_ROOT'].'/'.SITE_SUB_DOMAIN.'resources/');


/// product image dir
define('HOME_LISTING',SELLER_SITE_RESOURCES_URL.'product/200X200/');
define('PRODUCT_DETAILS_TRANDING_NOW_LISTING',SELLER_SITE_RESOURCES_URL.'product/200X200/');
define('PRODUCT_DETAILS_SIMILAR_PRODUCT_LISTING',SELLER_SITE_RESOURCES_URL.'product/230X230/');
define('PRODUCT_DEAILS_SMALL',SELLER_SITE_RESOURCES_URL.'product/100X100/');
define('PRODUCT_DEAILS_BIG',SELLER_SITE_RESOURCES_URL.'product/450X450/');
define('PRODUCT_DEAILS_EXTRA_BIG',SELLER_SITE_RESOURCES_URL.'product/700X700/');


/* End of file constants.php */
/* Location: ./application/config/constants.php */