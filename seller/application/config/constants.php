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
define('FE_BASE_URL',BASE_URL);
define('REAL_URL',BASE_URL);
define('SiteResourcesURL',BASE_URL.'resources/');
define('SiteImagesURL',SiteResourcesURL.'images/');
define('SiteCSSURL',SiteResourcesURL.'css/');
define('SiteJSURL',SiteResourcesURL.'js/');
define('ResourcesPath',$_SERVER['DOCUMENT_ROOT'].'/'.SITE_SUB_DOMAIN.'resources/');
$mainHost=  explode('.',$_SERVER['HTTP_HOST']);
define('MainSiteURL','http://www.'.$mainHost[1].'.'.$mainHost[2]);


/// product image dir
define('DETAILS_PAGE_BIG_IMG',SiteResourcesURL.'product/350X280/');
define('DETAILS_PAGE_SMALL_IMG',SiteResourcesURL.'product/95X76/');
define('DEAL_BIG_IMG',SiteResourcesURL.'product/200X200/');
define('DEAL_IMG',SiteResourcesURL.'product/139X95/');
define('LISTING_PRODUCT_IMG',SiteResourcesURL.'product/180X180/');
define('PRODUCT_DEAILS_SMALL_IMG',SiteResourcesURL.'product/150X150/');
//define('COUNTRY_LISTING_IMG1','150X150');


/* End of file constants.php */
/* Location: ./application/config/constants.php */