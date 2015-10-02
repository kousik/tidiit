<?php echo $html_heading; ?>  
<style>
    #LoadingDiv{display: none;}
    .logoinpanel{width:550px;font-family:Tahoma, Geneva, sans-serif;font-size:13px;border:4px solid #FF9900;padding:10px;background-color:#fff;overflow: hidden;}
    .clear { clear: both;}
</style>

<body class="login">
    <div class="row">
    <div class="col-md-6 col-md-offset-3 well">
        <div class="text-center">
            <img src="<?php echo SiteImagesURL; ?>logo.png" alt="Metis Logo">
        </div>
        <hr>
        <div class="tab-content">
            <div id="login" class="tab-pane active">
                <form name="login_form" id="login_form">
                    <p class="text-muted text-center">
                        Login
                    </p>
                    <div class="col-md-12">
                        <input type="email" name="userName" id="userName" placeholder="Username" class="form-control top">
                        <input type="password" placeholder="Password" name="password" id="password" class="form-control bottom">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="rememberMe" id="rememberMe"> Remember Me
                            </label>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                    </div>
                </form>
            </div>
            
            <div id="forgot" class="tab-pane">
                <form name="forgot_password_form" id="forgot_password_form">
                    <p class="text-muted text-center">Forgot Password</p>
                    <div class="col-md-12">
                        <input type="email" name="userName" id="userName" placeholder="mail@domain.com" class="form-control">
                        <br>
                        <button class="btn btn-lg btn-danger btn-block" type="submit">Recover Password</button>
                    </div>
                </form>
            </div>

            <div id="signup" class="tab-pane">
                <form id="registration_form" name="registration_form">
                    <p class="text-muted text-center">Registration</p>
                    <div class="col-md-6">
                        <input type="text" name="userName" id="userName" placeholder="userName" class="form-control top" required>
                        <input type="password" name="password" id="password" placeholder="Password" class="form-control middle" required>
                        <input type="password" name="confirmPassword"  id="confirmPassword" placeholder="Re-password" class="form-control middle" required>            
                        <input type="text" name="firstName" id="firstName" placeholder="First Name" class="form-control middle" required>
                        <input type="text"  name="lastName" id="lastName" placeholder="Last Name" class="form-control middle" required>
                        <input type="email" name="email" id="email" placeholder="mail@domain.com" class="form-control middle" required>
                        <input type="text"  name="mobile" id="mobile" placeholder="Mobile." class="form-control middle">
                        <canvas id="secret_img" width="110" height="25" style="border:1px solid #d3d3d3;padding-top: 8px; padding-left:5px;float: left;"><br /> Browser Not Support HTML5.</canvas><p style="font-size:12px;font-weight: bold;float: left;padding:10px 0 0 8px;">Not getting it ?<a href="javascript:void(0);" onclick="myJsMain.commonFunction.GeneratNewImage();">Click here</a>  to get a new.</p> 
                    </div>
                    <div class="col-md-6">
                        <input type="text"  name="contactNo" id="contactNo" placeholder="Contact No." class="form-control top" required>
                        <input type="text" name="fax" id="fax" placeholder="Fax Number" class="form-control middle" required>
                        <input type="text" name="address" id="address" placeholder="Address" class="form-control middle" required>
                        <input type="text" name="city" id="city" placeholder="City" class="form-control middle" required>
                        <select name="countryId" id="countryId" class="form-control middle" required>
                            <option> * Select * </option>
                            <?php foreach ($CountryDataArr AS $k) { ?>
                                <option value="<?php echo $k->countryId; ?>"><?php echo $k->countryName; ?></option>
                            <?php } ?>
                        </select>
                        <span class="stateIdSpan"><select name="stateId" id="stateId" class="form-control middle" required>
                                <option value=""> * Select * </option>

                            </select></span>
                        <input type="text" name="zip" placeholder="Zip Code / Postal Code" id="zip" class="form-control middle" required>
                        <input type="text" class="form-control bottom" placeholder="Security Code Shown on Left Side" name="secret" id="secret">
                    </div>
                    <div class="col-md-12"><textarea name="aboutMe" id="aboutMe" placeholder="About Me" class="form-control"></textarea></div>
                    <div class="col-md-12"><p><input type="checkbox" value="1" class="required" id="agree" name="agree"> I agree to the <a style="text-decoration:underline;" href="javascript:void(0);">terms and conditions</a></p> </div>
                    <div class="col-md-12"><p><input type="checkbox" value="1" id="receiveNewsLetter" name="receiveNewsLetter"> I want to receive newsletter updates</p></div>

                    <div class="col-md-12">
                        <button class="btn btn-lg btn-success btn-block" type="submit">Register</button>
                    </div>

                </form>
            </div>
        </div>
        
        <div class="text-center clear">
            <ul class="list-inline">
                <li> <a class="text-muted" href="#login" data-toggle="tab">Login</a>  </li>
                <li> <a class="text-muted" href="#forgot" data-toggle="tab">Forgot Password</a>  </li>
                <li> <a class="text-muted" href="#signup" data-toggle="tab">Signup</a>  </li>
            </ul>
        </div>
    </div>
    </div>
    <?php echo $footer; ?>
</body>
</html>
<script src="<?php echo SiteJSURL; ?>login-registration.js"></script>
<script type="text/javascript">
            myJsMain.login();
            myJsMain.registration();
            (function ($) {
                $(document).ready(function () {
                    $('.list-inline li > a').click(function () {
                        var activeForm = $(this).attr('href') + ' > form';
                        //console.log(activeForm);
                        $(activeForm).addClass('animated fadeIn');
                        //set timer to 1 seconds, after that, unload the animate animation
                        setTimeout(function () {
                            $(activeForm).removeClass('animated fadeIn');
                        }, 1000);
                    });
                });

                jQuery('#countryId').change(function () {
                    jQuery.ajax({
                        type: "POST",
                        url: '<?php echo BASE_URL . 'ajax/show_state/' ?>',
                        data: 'countryId=' + $(this).val(),
                        success: function (msg) {
                            if (msg != "") {
                                jQuery('.stateIdSpan').html(msg);
                            }
                        }
                    });
                });
            })(jQuery);
</script>



<!-- Background  for popup start here -->
<div id="fade_background" class="lightbox_background"></div>
<!-- Background  for popup end here -->
<div class="logoinpanel lightbox_fourground" id="LoadingDiv">
    <div><img src="<?php echo SiteImagesURL; ?>bx_loader.gif" alt="loading"/></div>
</div>
<!--Popup Login pannel end -->
</body>
</html>