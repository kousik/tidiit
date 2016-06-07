<style type="text/css">
/*start-contact-form*/
.contact_form {width: 95%;float: left;position: relative;}
.contact-form{background:#fff;padding: 7%;position: relative;border-radius: 12px;-webkit-border-radius: 12px;-moz-border-radius: 12px;-o-border-radius: 12px;}
.contact-form h1{font-size: 26px;color:#474646;font-family: "controllerfive";}
</style>
<footer>
    <div id="cartInnerHtmlDiv"></div>
    <div class="container">
        <div class="fotr_top">
            <div class="row">
                <div class="col-md-6">
                    <div class="social_media">
                        <div class="row">
                            <div class="col-md-4 col-sm-3">
                                <p>Keep In Touch</p>
                                <span>Social Connect</span>
                            </div>
                            <div class="col-md-8 col-sm-9">
                                <ul class="scl_icn">
                                    <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                                    <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                    <li><a href="#"><i class="fa fa-rss"></i></a></li>
                                    <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="Scr_trstd">
                        <p><strong>100% Secure and Trusted Payment </strong></p>
                        <span>We accept Net Banking, mPesa. We also offer Cash on Delivery.</span>
                        <img src="<?php echo SiteImagesURL; ?>trstd_pymnt.png" />
                    </div>
                </div>
            </div>
        </div>

        <div class="fotr_btm">
            <p><strong><?php echo ($this->session->userdata('FE_SESSION_USER_LOCATION_VAR')=='IN') ? 'India' : 'Kenya';?> Wholesale Marketplace</strong></p>
            <span>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span>
            <div class="row">
                <div class="col-md-2">
                    <div class="get_tidiit">
                        <p class="mbl_img"><img src="<?php echo SiteImagesURL; ?>get_tdt.png" /></p>
                        <span>Get Tidiit on your mobile</span>
                        <a href="#">Find out how &nbsp;<i class="fa fa-caret-right"></i></a>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <ul class="fotr_menu">
                                <h6>Tidiit Inc. Ltd.</h6>
                                <li><a href="<?php echo BASE_URL.'content/careers/Ng==/';?>">Careers</a></li>
                                <li><a href="#http://blog.tidiit.com">Blog</a></li>
                                <li><a href="<?php echo BASE_URL.'content/press/Nw==/';?>">Press</a></li>
                                <h6>Community</h6>
                                <li><a href="#">Tidiit Facebook</a></li>
                                <li><a href="#">Tidiit Twitter</a></li>
                                <li><a href="#">Tidiit LinkedIn</a></li>
                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <ul class="fotr_menu">
                                <h6>Dispatch & Delivery</h6>
                                <li><a href="<?php echo BASE_URL.'content/delivery-options/OA==/';?>">Delivery Options</a></li>
                                <li><a href="<?php echo BASE_URL.'content/customs-and-import-tax/OQ==/';?>">Customs &amp; Import Tax</a></li>
                                <li><a href="<?php echo BASE_URL.'index/track-order/';?>">Tracking Your Items</a></li>
                                <h6>Refund & Return</h6>
                                <li><a href="<?php echo BASE_URL.'content/refund-and-return-process/MTA=/';?>">Refund & Return Process</a></li>
                                <li><a href="<?php echo BASE_URL.'content/tidiit-resolution-center/MTE=/';?>">Tidiit Resolution Center</a></li>
                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <ul class="fotr_menu">
                                <h6>Policy</h6>
                                <li><a href="<?php echo BASE_URL.'content/privacy-policy/Mw==/';?>">Privacy Policy</a></li>
                                <li><a href="<?php echo BASE_URL.'content/terms-of-use/Mg==/';?>">Terms of Use</a></li>
                                <li><a href="<?php echo BASE_URL.'content/terms-of-sale/MTI=/';?>">Terms of Sale</a></li>
                                <li><a href="<?php echo BASE_URL.'content/report-abuse/MTM=/';?>">Report Abuse</a></li>
                                <li><a href="#">Infringement Policy</a></li>
                            </ul>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <ul class="fotr_menu brdr_rit_none">
                                <h6>Customer Service</h6>
                                <li><a href="<?php echo BASE_URL.'contact-us/';?>">Contact Us</a></li>
                                <li><a href="<?php echo BASE_URL.'help';?>">Help</a></li>
                                <li><a href="<?php echo BASE_URL.'customer-service';?>">Customer Service</a></li>
                                <li><a href="#">Brand Owner Report</a></li>
                                <h6>Payment</h6>
                                <li><a href="<?php echo BASE_URL.'content/payment-methods/NA==/';?>">Payment Methods</a></li>
                                <li><a href="<?php echo BASE_URL.'content/coupon/NQ==/';?>">Coupon</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fotr_end">
                <?php /*<img src="<?php echo SiteImagesURL; ?>fotr_img.png" />*/?>
                <ul class="ftr_sbmnu">
                    <?php 
                    //echo '='.$this->session->userdata('FE_SESSION_USER_LOCATION_VAR').'=';die;
                    /*
                    if(trim($this->session->userdata('FE_SESSION_USER_LOCATION_VAR'))=='IN'):?>
                    <li><a href="<?php echo BASE_URL.'content/india-wholesale/';?>">India Wholesale</a></li>
                    <li><a href="<?php echo BASE_URL.'content/india-manufacturers';?>">India Manufacturers</a></li>
                    <?php else:?>
                    <li><a href="<?php echo BASE_URL.'content/kenya-wholesale/';?>">Kenya Wholesale</a></li>
                    <li><a href="<?php echo BASE_URL.'content/kenya-manufacturers/';?>">Kenya Manufacturers</a></li>
                    <?php endif */?>
                    <li><a href="http://seller.tidiit.com">Seller home</a></li>
                    <li><a href="<?php echo BASE_URL.'top-search/';?>">Top Searches</a></li>
                    <?php /*<li><a href="<?php echo BASE_URL.'reviews/';?>">Reviews</a></li>*/?>
                </ul>
                <p>Copyright Notice &copy; <?php echo date('Y');?> Tiditt Inc. Ltd. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>  

<!-- Modal -->
<div class="modal fade" id="myModalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg opn_box" style='padding:10px; background:#fff; border-radius:10px;'>
        <button aria-label="Close" data-dismiss="modal" class="close" type="button" id="closemodal"><span aria-hidden="true">×</span></button>

        <div class="container">
            <div class="row">
                <div class="col-md-5 col-sm-5 col-md-push-1">
                    <div class="contact-form popLeftForm">
                        <div id="login_form">
                            <form class="contact_form" action="#" method="post" name="tidiit_user_login_form" id="tidiit_user_login_form">
                                <h1>Login Into Your Account</h1>

                                <div class="form-group">
                                    <div class="input-group col-md-8"> 
                                        <input id="userName" name="userName" type="text" placeholder="user@tidiit.com" class="form-control email" required >
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    </div>
                                    <label id="userName-error" class="error" for="userName"></label>
                                </div>

                                <div class="form-group">
                                    <div class="input-group  col-md-8"> 
                                        <input id="loginPassword" name="loginPassword" type="password" placeholder="password" class="form-control" required >
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    </div>
                                    <label id="loginPassword-error" class="error" for="loginPassword"></label>
                                </div>
                                <input type="hidden" name="webIdLogin" id="webIdLogin" value="">

                                <div class="clear"></div>
                                <div class="row rem t-login-bottom-action">
                                    <div class="col-sm-5">	
                                        <div class="checkbox">
                                            <label class="checkbox">
                                                <input type="checkbox" name="checkbox" checked><i></i> Remember me
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="forgot">
                                            <a href="javascript:void(0);" id="forgot">forgot password?</a>
                                        </div>
                                    </div>
                                </div>

                                <input type="submit" name="loginInSubmit" id="loginInSubmit" value="Sign In" class="btn btn-default col-md-8"/>	
                                <input type="hidden" name="redirect_url" value="<?=$this->session->flashdata('redirect_url')?>">         

                                <div class="clear"></div>	
                            </form>
                        </div>


                        <div id="forgot_form" style="display:none;">
                            <form class="contact_form" action="#" method="post" name="tidiit_user_forgot_form" id="tidiit_user_forgot_form">
                                <h1>Forgot Password</h1>
                                <div class="form-group">
                                    <div class="input-group col-md-8"> 
                                        <input id="userForgotPasswordEmail" name="userForgotPasswordEmail" type="email" placeholder="user@tidiit.com" class="form-control email" required >
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    </div>
                                    <label id="userEmail-error" class="error" for="userEmail"></label>
                                </div>
                                <input type="submit" name="forgotPasswrod" id="forgotPasswrod" value="Submit" class="btn btn-default col-md-8"/>	
                                <input type="hidden" name="webIdForgotpass" id="webIdForgotpass" value="">
                                <input type="hidden" name="redirect_url" value="<?=$this->session->flashdata('redirect_url')?>">         
                                <div class="clear"></div>	
                            </form>
                        </div>

                    </div>
                </div>

                <div class="col-md-5 col-sm-5 col-md-push-1">
                    <div class="contact-form">
                        <div id="login_form ">
                            <form class="contact_form" action="#" method="post" name="tidiit_user_register_form" id="tidiit_user_register_form" novalidate="novalidate">
                                <h1>Signup for New Account</h1>
                                <ul>
                                    <div class="form-group">
                                        <div class="input-group col-md-8"> 
                                            <input id="firstName" name="firstName" type="text" placeholder="Enter First Name" class="form-control" required >
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        </div>
                                        <label id="firstName-error" class="error" for="firstName"></label>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group col-md-8"> 
                                            <input id="lastName" name="lastName" type="text" placeholder="Enter Last Name" class="form-control" required >
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        </div>
                                        <label id="lastName-error" class="error" for="lastName"></label>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group col-md-8"> 
                                            <input id="email" name="email" type="email" placeholder="Enter email as username" class="form-control" required >
                                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        </div>
                                        <label id="email-error" class="error" for="email"></label>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group col-md-8"> 
                                            <input id="password" name="password" type="password" placeholder="Enter Password" class="form-control" required >
                                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                        </div>
                                        <label id="password-error" class="error" for="password"></label>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group col-md-8"> 
                                            <input id="confirmPassword" name="confirmPassword" type="password" placeholder="Enter Confirm Password" class="form-control" required >
                                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                        </div>
                                        <label id="confirmPassword-error" class="error" for="confirmPassword"></label>
                                    </div>

                                    <!-- <li class="frm_gender">
                                         <span><input type="radio" name="gender" value="male" />Male</span>
                                         <span><input type="radio" name="gender" value="female" />Female	</span>            
                                             <p><img src="<?php //echo SiteImagesURL; ?>lock.png" alt=""></p>
                                         </li>-->
                                    <input type="hidden" name="webIdRegistration" id="webIdRegistration" value="">

                                    <input type="submit" name="SignIn" id="SignIn" value="Sign In" class="btn btn-default col-md-8" />	
                                    <div class="clear"></div>	

                                    <div class="clear"></div>	
                            </form>
                        </div>
                    </div>
                </div>
                <!-- start-form -->
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('.showCartDetails').click(function(){
            jQuery.ajax({
                type:"POST",
                url:myJsMain.baseURL+'ajax/show_cart/',
                success:function(msg){
                    jQuery('#cartInnerHtmlDiv').html(msg);
                }
            });
        });
        
        jQuery('.showContactUs').click(function(){
            location.href='<?php echo BASE_URL.'contact-us/'?>';
        });
        
        jQuery('.goForPartner').click(function(){
            location.href='<?php echo BASE_URL.'content/partner-with-us/MTg=/'?>';
        });
        
        
        jQuery('.showLogin').click(function(){
            <?php if($this->session->userdata("FE_SESSION_VAR")==""):?>
            jQuery('#myModalLogin').modal('show');
            <?php else:?>
            location.href='<?php echo BASE_URL.'myaccount';?>';    
            <?php endif;?>    
        });
        jQuery('.showMyAccount').click(function(){location.href=myJsMain.baseURL+'myaccount';});
        jQuery('.all_catgrs').click(function () {
            //location.href = '<?php //echo BASE_URL; ?>';
        });
        jQuery('#demo1').skdslider({'delay': 5000, 'animationSpeed': 2000, 'showNextPrev': true, 'showPlayButton': true, 'autoSlide': true, 'animationType': 'sliding'});

        jQuery('#responsive').change(function () {
            $('#responsive_wrapper').width(jQuery(this).val());
        });


        jQuery('#demo2').skdslider({'delay': 3000, 'animationSpeed': 2000, 'showNextPrev': true, 'showPlayButton': true, 'autoSlide': true, 'animationType': 'sliding'});

        jQuery('#responsive').change(function () {
            $('#responsive_wrapper').width(jQuery(this).val());
        });


        $("#owl-demo").owlCarousel({
            items: 6, //10 items above 1000px browser width
            itemsDesktop: [1024, 4], //5 items between 1000px and 901px
            itemsDesktopSmall: [900, 3], // 3 items betweem 900px and 601px
            itemsTablet: [600, 2], //2 items between 600 and 0;
            itemsMobile: 2, // itemsMobile disabled - inherit from itemsTablet option
            autoPlay: true,
            stopOnHover: true,
            navigationText: true,
            pagination: false
        });

        $("#owl-demo1").owlCarousel({
            items: 6, //10 items above 1000px browser width
            itemsDesktop: [1024, 4], //5 items between 1000px and 901px
            itemsDesktopSmall: [900, 3], // 3 items betweem 900px and 601px
            itemsTablet: [600, 2], //2 items between 600 and 0;
            itemsMobile: 2, // itemsMobile disabled - inherit from itemsTablet option
            autoPlay: true,
            stopOnHover: true,
            navigationText: true,
            pagination: false
        });

        $("#owl-demo2").owlCarousel({
            items: 6, //10 items above 1000px browser width
            itemsDesktop: [1024, 4], //5 items between 1000px and 901px
            itemsDesktopSmall: [900, 3], // 3 items betweem 900px and 601px
            itemsTablet: [600, 2], //2 items between 600 and 0;
            itemsMobile: 2, // itemsMobile disabled - inherit from itemsTablet option
            autoPlay: true,
            stopOnHover: true,
            navigationText: true,
            pagination: false
        });

        var owl = $("#owl-demo3");

        owl.owlCarousel({
            items: 6, //10 items above 1000px browser width
            itemsDesktop: [1024, 4], //5 items between 1000px and 901px
            itemsDesktopSmall: [900, 4], // 3 items betweem 900px and 601px
            itemsTablet: [600, 2], //2 items between 600 and 0;
            itemsMobile: 2, // itemsMobile disabled - inherit from itemsTablet option
            pagination: false
        });

        // Custom Navigation Events
        $(".next").click(function () {
            owl.trigger('owl.next');
        });
        $(".prev").click(function () {
            owl.trigger('owl.prev');
        });

        //$('.howItWork').click();
        $("#button1").click(function () {
            $("#t1").toggle("slow");
        });

        $('#forgot').click(function () {
            $('#login_form').hide();
            $('#forgot_form').show();
        });
        
        jQuery('#myModalLogin').on('hidden.bs.modal', function () {
            $('#login_form').show();
            $('#forgot_form').hide();
        })
    });

    myJsMain.login();
    myJsMain.registration();
    myJsMain.forgot_password();
    $(document).ready(function () {
        //alert(myJsMain.baseURL);
        //myJsMain.commonFunction.tidiitConfirm('Tidiit System','Are you sure  ?','silent','',180);
        //myJsMain.commonFunction.tidiitAlert('Tidiit System','returning ',180);
        <?php 
        if($this->session->userdata('FE_SESSION_VAR')):?>
            setInterval(function(){
                var mesg = true;
                jQuery.post( myJsMain.baseURL+'update-message/', {
                    mesg: mesg
                },
                function(data){
                    $('li span.js-notfy-auto-update').text(data.tot_notfy);
                    $('h3.js-cart-auto-update').html('<span>`</span>'+data.carttotal+' - '+data.totalitem+' item');
                   // $('h3.js-cart-auto-update').html(data.totalitem+' item');
                }, 'json' );
            }, 5000);
        <?php 
        endif;?>
               
        <?php 
        if($this->session->flashdata('open_login') && !$this->session->userdata('FE_SESSION_VAR')):?> 
                $('.bs-example-modal-lg button.close').trigger('click');
                 $('a.showLogin').trigger( "click" );
        <?php 
        elseif($this->uri->segment('1')=='logistics'):?>
                $('.bs-example-modal-lg1 button.close').trigger('click');
        <?php
        endif;?>
    });
    

    function manualClick() {
        alert(manualClick + ' from fun');
    }
</script>
<div id="dialog-confirm" title="Alert" style="display: none;">
    <p><span id="dialog-confirm-message-text"></span></p>
</div>
</body>
</html>
