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
//print_r($segArr);die;
$dynRoutingStart=false;
$SecondParam=false;
$afer2nd=1;

$routeParam='';
$routeVal='';

$adminController=false;
$adminControllerFun=false;


/*if(in_array('admin', $segArr)){
    $LastIndex=count($segArr);
    unset($segArr[0]);
    if($segArr[$LastIndex-1]==""){
       unset($segArr[$LastIndex-1]); 
    }
    //echo '<pre>';print_r($segArr);
    $controllerPath=APPPATH.'controllers/admin/';
    
    $key=array_search('admin',$segArr);
    //echo $key.' ++ '.count($segArr);die;
    foreach($segArr AS $k => $v){
        if($dynRoutingStart==false){
            if($v=='admin'){
                $dynRoutingStart=true;
            }
        }else{
            if($adminController==false){
                $routeParam=$k;
                $routeVal=$k;
                $adminController=true;
            }else{
                if($adminControllerFun==false){
                    $routeParam.='/'.$v;
                    $routeVal.='/'.$v;
                    $SecondParam=true;
                }else{
                    $routeParam.='(:any)';
                    $routeVal.='$'.$afer2nd;
                    $afer2nd++;
                }
            }
        }
    }
    if($routeParam=='' && $routeVal==''){
        $routeParam='admin/index';
        $routeVal='admin/index';
    }
}else{
    
}*/
$controllerPath=APPPATH.'controllers/';
foreach ($segArr AS $k){
    if($dynRoutingStart==false){
        if(file_exists($controllerPath.$k.'.php')){
            $routeParam=$k;
            $routeVal=$k;
            $dynRoutingStart=true;
        }

    }else{
        if($SecondParam==false){
            $routeParam.='/'.$k;
            $routeVal.='/'.$k;
            $SecondParam=true;
        }else{
            $routeParam.='/(:any)';
            $routeVal.='/$'.$afer2nd;
            $afer2nd++;
        }
    }
}

if($routeParam!='' && $routeVal!='')
    $route[$routeParam]=$routeVal;

//echo '<pre>';print_r($route);die;

$route['webadmin']="webadmin/index";
$route['webadmin/index']="webadmin/index/login";

$route['default_controller'] = "index";
$route['about_us']="content/about_us";
$route['post-your-review/(:num)']="index/order_review/$1";
$route['home']='index/home';
$route['customer-review']='index/customer_review';
$route['login']='user/login';
$route['register']='user/register';
$route['register/(:any)']='user/register/$1';
$route['forgot-password']='user/forgot_password';
$route['change-password']='user/change_password';
$route['update-bill-address']='user/change_bill_address';
$route['cart']='cart/show';
$route['cart/show']='cart/show';
//$route['all-products-of-(:any)/(:num)/(:num)']="product/of/$2/$3";
//$route['send-online-(:any)/(:num)/(:num)']="product/of/$2/$3";
$route['filter-of-(:any)/(:num)/(:num)']="ajax/filter_product/$2/$3";
//$route['all-products-of-(:any)/(:num)/(:num)']="product/of/$2/$3";
$route['send-gifts-to-(:any)/(:num)/(:num)']="product/country/$2/$3";
$route['send-(:any)/(:num)/(:num)']="product/of/$2/$3";
$route['gift-searching-for-(:any)/(:num)/(:num)']="product/search/$2/$3";
$route['filter-of-gift-searching-for-(:any)/(:num)/(:num)']="ajax/filter_product/$2/$3";
if(strpos($_SERVER['REQUEST_URI'],'admin')===FALSE && strpos($_SERVER['REQUEST_URI'],'ajax')===FALSE && strpos($_SERVER['REQUEST_URI'],'user')===FALSE && strpos($_SERVER['REQUEST_URI'],'payment' )===FALSE && strpos($_SERVER['REQUEST_URI'],'cart' )===FALSE && strpos($_SERVER['REQUEST_URI'],'resources' )===FALSE && strpos($_SERVER['REQUEST_URI'],'index')===FALSE){
    //echo 'zzz';die;
    $route['(:any)']="product/detailsof/$1";
}



// static and footer item links
$route['about-dailyplaza']="content/about_daily_plaza";
$route['fulfillment-policies']="content/fulfillment_policies";
$route['partner-with-us']="content/partner_with_us";
$route['connect-with-us']="content/connect_with_us";
$route['press-releases']="content/press_releases";
$route['terms-and-conditions']="content/term_and_conditions";
$route['privacy-policies']="content/privacy_policies";
$route['careers']="content/careers";
$route['contact-us']="index/contact_us";
$route['faq']="index/faq";
$route['what-cvv']="index/what_cvv";

$route['featured-products']="index/featured_products";
$route['new-products']="index/new_products";
$route['new-products']="index/new_products";


$route['popular-store']="index/popular_store";
$route['dailyplaza-deals']="index/deals";
$route['send-gifts-worldwide']="index/home/240";
$route['send-wine-cakes-flowers-online-india']="index/home/99";
$route['send-online-gifts-usa']="index/home/1";

//echo '<pre>';print_r($route);die;
// all ajax ativity
/*$route['ajax/get_state']='ajax/get_state/';
$route['ajax/reset_secret']='ajax/reset_secret/';
$route['ajax/check_guest_email_is_exists']='ajax/check_guest_email_is_exists/';
$route['ajax/get_state_checkbox']='ajax/get_state_checkbox/';
$route['ajax/get_edit_state']='ajax/get_edit_state/';
$route['ajax/check_user_name']='ajax/check_user_name/';
$route['ajax/check_edit_user_name']='ajax/check_edit_user_name/';
$route['ajax/check_category_name']='ajax/check_category_name/';
$route['ajax/check_edit_category_name']='ajax/check_edit_category_name/';
$route['ajax/get_sub_category']='ajax/get_sub_category/';
$route['ajax/get_exist_geozone_state']='ajax/get_exist_geozone_state/';
$route['ajax/get_user_shiipin_details_by_id']='ajax/get_user_shiipin_details_by_id/';
$route['ajax/isStateExistsInZeoZone']='ajax/isStateExistsInZeoZone/';
$route['ajax/billingaddressforccavenu']='ajax/billingaddressforccavenu/';
$route['ajax/get_user_shiipin_details_by_id']='ajax/get_user_shiipin_details_by_id/';
$route['ajax/get_user_shiipin_details_by_id']='ajax/get_user_shiipin_details_by_id/';
$route['ajax/get_user_shiipin_details_by_id']='ajax/get_user_shiipin_details_by_id/';
$route['ajax/get_user_shiipin_details_by_id']='ajax/get_user_shiipin_details_by_id/';*/

//$route['(.*)']="product/detailsof/$1";

$route['under-construnction']="index/under_construnction";
$route['logout']="index/logout";
$route['myaccount']="user/my_account";
$route['my-shipping-address']="user/my_shipping_address";
$route['my-billing-address']="user/my_billing_address";
$route['my-orders']="user/my_orders";
$route['my-groups']="user/my_groups";
$route['404_override'] = 'index/under_construnction';


/* End of file routes.php */
/* Location: ./application/config/routes.php */