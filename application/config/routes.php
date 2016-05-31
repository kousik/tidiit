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
$route['my-orders/(:num)']="user/my_orders/$1";
$route['my-orders/parent/(:any)']="user/my_parent_orders/$1";
$route['my-groups']="user/my_groups";
$route['my-groups-orders']="user/my_group_orders";
$route['edit_groups/(:any)']="user/edit_groups/$1";
$route['my-finance-info']="user/my_finance_info";
$route['my-profile']="user/my_profile";

$route['update-message']="user/ajax_get_update_message";
$route['top-search']="index/top_search/";
$route['reviews']="index/reviews/";
$route['contact-us']="index/contact_us/";
$route['seller-faq']="index/seller_faq/";
$route['buyer-faq']="index/buyer_faq/";
$route['brand-zone']="index/brand_zone/";

$route['content/(:any)/(:any)']="content/show_content/$2";


$route['webadmin']="webadmin/index";
$route['webadmin/index']="webadmin/index/login";

//Order
$route['shopping/add-group-order']="shopping/process_my_group_orders";
$route['shopping/mod-group-order/(:any)']="shopping/process_my_group_orders_by_id/$1";
$route['shopping/mod-pt-group-order/(:any)']="shopping/process_my_parent_group_orders_by_id/$1";
$route['shopping/checkout/(:any)']="shopping/process_checkout/$1";
$route['shopping/remove_group_cart']="shopping/remove_group_cart";
$route['shopping/ajax-process-group-payment']="shopping/ajax_process_group_payment";

$route['shopping/success']="shopping/oredr_process_success";
$route['shopping/group-order-accept-process/(:any)']="shopping/process_group_parent_order/$1";
$route['shopping/ord-message']="shopping/oredr_default_message";
$route['shopping/group-order-decline/(:any)']="shopping/order_group_decline_process/$1";

$route['shopping/group-re-order-process/(:any)']="shopping/order_group_re_order_process/$1";

$route['shopping/group-re-order-accept-process/(:any)/(:any)']="shopping/process_group_parent_re_order/$1/$2";
$route['edit_groups_re_order/(:any)/(:any)']="user/edit_groups_re_order/$1/$2";

$route['shopping/add-order']="shopping/process_my_single_orders";
$route['shopping/single-checkout']="shopping/single_order_check_out";
$route['shopping/remove-single-cart']="shopping/remove_single_cart_processing";
$route['shopping/my-cart']="shopping/show_my_cart";
$route['order/details/(:any)']="shopping/view_order_details/$1";
$route['order/cancellation/(:any)']="shopping/order_cancel_process_view/$1";
$route['order/cancel_processing']="shopping/order_cancel_processing";


//Notification
$route['my-notifications']="notification/get_my_notifications";
$route['my-notifications/(:num)']="notification/get_my_notifications/$1"; 

//Logout
$route['logout']="index/logout";

$route['default_controller'] = "index";
$route['404_override'] = 'index/under_construnction';


//Category - Product Listing
$route['products/(:any)']="category/display_category_products/$1";
$route['products/ord-message']="category/display_default_message";

//Brand - Product listing
$route['brand/(:any)']="category/display_brand_products/$1";

//Search
$route['search']="category/display_search_products";
$route['logistics/out-for-delivery']="index/out_for_delivery_update/";
$route['logistics/delivery']="index/delivery_update/";
$route['help']='index/help';
$route['customer-service']='index/help';

/* End of file routes.php */
/* Location: ./application/config/routes.php */