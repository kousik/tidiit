<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'index';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$segArr=explode('/', $_SERVER['REQUEST_URI']);
/*if($_SERVER['REQUEST_SCHEME']=='http' && $_SERVER['HTTP_HOST']=='tidiit-local.com'){
    header('location:https://tidiit-local.com/');
}*/
//$route['(:any)']="product/details/$1";
$route['my-billing-address']="user/my_billing_address";
$route['my-shipping-address']="user/my_shipping_address";
$route['myaccount']="user/my_account";
$route['my-orders']="user/my_orders";
$route['my-groups']="user/my_groups";
$route['my-groups-orders']="user/my_group_orders";
$route['edit_groups/(:any)']="user/edit_groups/$1";
$route['my-finance-info']="user/my_finance_info";
$route['my-profile']="user/my_profile";

$route['content/(:any)/(:any)']="content/show_content/$2";


$route['webadmin']="webadmin/index";
$route['webadmin/index']="webadmin/index/login";

//Order
$route['shopping/add-group-order']="shopping/process_my_group_orders";


//Logout
$route['logout']="index/logout";

$route['default_controller'] = "index";
$route['404_override'] = 'index/under_construnction';


/* End of file routes.php */
/* Location: ./application/config/routes.php */