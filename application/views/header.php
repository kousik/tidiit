<?php $currencySymbol=($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')=="IN") ? '<i class="fa fa-rupee"></i>' :'KSh';?>
<script type="text/javascript">
    $(document).ready(function(){
        var webId=myJsMain.commonFunction.js_dynamic_text(8);
        $('#webIdRegistration').val(webId);
        $('#webIdLogin').val(webId);
        $('#webIdForgotpass').val(webId);
    });
</script>
<body>
    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-3 padng_right_none">
                    <div class="logo"><a href="<?php echo BASE_URL;?>"><img src="<?php echo SiteImagesURL;?>logo.png" /></a></div>
                </div>
                <div class="col-md-9 padng_left_none">
                    <div class="header_right_sectn">
                        <div class="right_top">
                            <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <p class="wlcm_txt">Welcome !<span><a class='iframe signIn' data-toggle="modal" data-target="#myModalLogin" >Join Free</a></span> or <span><a class='iframe' data-toggle="modal" data-target="#myModalLogin" >Sign in</a></span></p>
                                    
                                </div>
                                <div class="col-md-3 col-sm-3 search padng_right_none">
                                    <div class="inner">
                                        <form name="site-search" class="js-site-search" action="<?php echo BASE_URL;?>search/" method="get">
                                        <div class="button-search js-click-search"><i class="search_icn"></i></div>
                                        <input type="search" id="topic_title" data-query="" name="s" placeholder="Search" value="<?=isset($_GET['s'])?$_GET['s']:""?>">
                                            <input type="hidden" name="q" class="js-s-q" value="<?=isset($_GET['q'])?$_GET['q']:""?>">
                                            <input type="hidden" name="id" class="js-s-id" value="<?=isset($_GET['id'])?$_GET['id']:""?>">
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-5 col-sm-5 padng_left_none right-nav-c">
                                    <ul class="sub_menu">
                                        <li><a href="<?php echo BASE_URL.'content/buyer-protection/MTc==/';?>">Buyer Protection <i class="fa fa-lock"></i></a></li>
                                        <?php /*<li><a href="<?php echo BASE_URL.'content/help/MTU=/';?>">Help <i class="fa fa-question-circle"></i></a></li> */?>
                                        <li><a href="<?php echo BASE_URL.'help';?>">Help <i class="fa fa-question-circle"></i></a></li>
                                        <li><a href="javascript:void(0);" class="showLogin">My Account <i class="fa fa-user"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div> 
                        <div class="bdr_lft">                               
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="icon_text">
                                        <div class="circle_brdr">
                                            <span><i class="fa fa-diamond"></i></span>
                                        </div>
                                        <div class="content">
                                            <h3>More About Coupon</h3>
                                            <p>Visit now</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="icon_text goForPartner">
                                        <div class="circle_brdr">
                                            <span><i class="fa fa-anchor"></i></span>
                                        </div>
                                        <div class="content">
                                            <h3>Best Suppliers</h3>
                                            <p class="blue_txt">Partner With Us</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="icon_text showContactUs">
                                        <div class="circle_brdr">
                                            <span><i class="fa fa-phone"></i></span>
                                        </div>
                                        <div class="content">
                                            <h3><?php echo $callForUsFree;?></h3>
                                            <p>CALL US FOR FREE</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="icon_text active showCartDetails">
                                        <div class="circle_brdr">
                                            <span><i class="fa fa-truck"></i></span>
                                        </div>
                                        <div class="content">
                                            <h3 class="js-cart-auto-update"><?php echo $currencySymbol;?>0.00 - 0 item</h3>
                                            <p>YOUR TRUCK</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mdl_menu">
                <div class="row">
                    <!--<div class="col-md-3 padng_right_none">
                            <div class="all_catgrs mobl_vrsn" style="cursor: pointer"><h3>All Categories</h3></div>
                    </div>-->
                    <div class="col-md-3 padng_right_none">
                        <div class="all_catgrs category_inner"><h3 data-toggle="dropdown">All Categories <span class="down_srrow">&nbsp;</span></h3>
                            <?=$float_menu?>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-8">
                        <ul class="main_menu">
                            <li><a href="<?php echo BASE_URL.'content/tidiit-payment-policy/MjA=';?>">Tidiit Payment Policy</a></li>
                            <li><a href="<?php echo BASE_URL.'new-arrivals';?>">New Arrivals</a></li>
                            <li><a href="<?php echo BASE_URL.'brand-zone';?>">Brand Zone</a></li>
                            <li><a href="<?php echo BASE_URL.'customer-service';?>">Customer service</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3 col-sm-4">
                        <p class="down_app"><a href="#"><img src="<?php echo SiteImagesURL;?>downld_ap.png" /> <span>&nbsp;Download </span>Now</a></p>
                    </div>
                </div>
            </div>
