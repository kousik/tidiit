<?php echo $html_heading;?>  
<style type="text/css">
    #LoadingDiv{display: none;}
    .logoinpanel{width:550px;font-family:Tahoma, Geneva, sans-serif;font-size:13px;border:4px solid #FF9900;padding:10px;background-color:#fff;overflow: hidden;}
    /*start-contact-form*/
</style>    
  <body class="login">
   <div class="row">
    <div class="col-md-6 col-md-offset-3 well">   
      <div class="text-center">
        <img src="<?php echo SiteImagesURL;?>logo.png" alt="Metis Logo">
      </div>
      <hr>
      <div class="tab-content">
        <div id="login" class="tab-pane active">
            <form name="login_form" id="login_form" class="contact_form">
            <p class="text-muted text-center pad1">
            <h1 style="font-size: 26px;color:#474646;font-family:controllerfive !important;" class="text-center">Login</h1>
            </p>
             <div class="col-md-12">
                <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                      <input id="userName" name="userName" type="email" placeholder="enter your email for login" class="form-control email" required >
                      <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>
                    <label id="userName-error" class="error" for="userName"></label>
                </div>
                 <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                        <input id="password" name="password" type="password" placeholder="enter your password for login" class="form-control" required >
                      <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>
                    <label id="password-error" class="error" for="password"></label>
                </div>
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
            <form name="forgot_password_form" id="forgot_password_form" class="contact_form">
                <p class="text-muted text-center"><h1 style="font-size: 26px;color:#474646;font-family:controllerfive !important;" class="text-center">Forgot Password</h1></p>
                <div class="col-md-12" style=" height: 20px;"></div>
                <div class="form-group has-error col-md-12" style="text-align: center;">
                    <div class="col-md-2"></div>
                    <div class="input-group col-md-6" style="text-align: center;"> 
                      <input id="forgot_password_email" name="forgot_password_email" type="email" placeholder="enter your email for retribe password" class="form-control email required">
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    </div>
                    <div class="col-md-2"></div>
                </div>
                <div class="col-md-12" style=" height: 20px;"></div>
             <div class="col-md-12">
                <button class="btn btn-lg btn-danger btn-block" type="submit">Recover Password</button>
                </div>
                <div class="col-md-12" style=" height: 20px;"></div>
          </form>
        </div>
        
        <div id="signup" class="tab-pane">
            <form id="registration_form" name="registration_form">
                <p class="text-muted text-center"><h1 style="font-size: 26px;color:#474646;font-family:controllerfive !important;" class="text-center">Registration</h1></p>
          <div class="col-md-6">
                <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                      <input id="userName" name="userName" type="email" placeholder="enter your email for login" class="form-control email" required >
                      <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>
                    
                </div>
                <div class="form-group has-error">
                    <div class="input-group  col-md-8"> 
                      <input id="password" name="password" type="password" placeholder="enter your  password" class="form-control" required >
                      <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    </div>
                    
                </div>
                <div class="form-group has-error">
                    <div class="input-group  col-md-8"> 
                      <input id="confirmPassword" name="confirmPassword" type="password" placeholder="enter your  confirm password" class="form-control" required >
                      <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>
                    
                </div>
                <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                        <input id="firstName" name="firstName" type="text" placeholder="ener seller first name" class="form-control" required >
                      <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>
                    
                </div>
              <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                        <input id="lastName" name="lastName" type="text" placeholder="ener seller last name" class="form-control" required >
                      <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>
                  
                </div>
                <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                      <input id="email" name="email" type="email" placeholder="enter your email" class="form-control email" required >
                      <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                      
                    </div>
                </div>
                <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                      <input id="mobile" name="mobile" type="phone" placeholder="enter your mobile no" class="form-control " required >
                      <span class="input-group-addon"><i class="fa fa-mobile"></i></span>
                    </div>
                </div>
                <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                      <canvas id="secret_img" width="110" height="25" style="border:1px solid #d3d3d3;padding-top: 8px; padding-left:5px;float: left;"><br /> Browser Not Support HTML5.</canvas><p style="font-size:12px;font-weight: bold;float: left;padding:10px 0 0 8px;">Not getting it ?<a href="javascript:void(0);" onclick="myJsMain.commonFunction.GeneratNewImage();">Click here</a>  to get a new.</p> 
                    </div>
                </div>
                <!--<input type="text" name="userName" id="userName" placeholder="userName" class="form-control top" required>
                <input type="password" name="password" id="password" placeholder="Password" class="form-control middle" required> 
                <input type="password" name="confirmPassword"  id="confirmPassword" placeholder="Re-password" class="form-control middle" required>            
            <input type="text" name="firstName" id="firstName" placeholder="First Name" class="form-control middle" required>
            <input type="text"  name="lastName" id="lastName" placeholder="Last Name" class="form-control middle" required>
            <input type="email" name="email" id="email" placeholder="mail@domain.com" class="form-control middle" required>
            <input type="text"  name="mobile" id="mobile" placeholder="Mobile." class="form-control middle">-->
          </div>
          <div class="col-md-6">
              <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                      <input id="contactNo" name="contactNo" type="phone" placeholder="enter your contact number" class="form-control " required >
                      <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                    </div>
                  
                </div>
              <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                      <input id="fax" name="fax" type="phone" placeholder="enter your fax" class="form-control " >
                      <span class="input-group-addon"><i class="fa fa-fax"></i></span>
                    </div>
                  
                </div>
              <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                      <input id="address" name="address" type="text" placeholder="enter your address" class="form-control " required >
                      <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                    </div>
                  
                </div>
              <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                      <input id="city" name="city" type="text" placeholder="enter your city" class="form-control " required >
                      <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                    </div>
                  
                </div>
              
              <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
              <select name="countryId" id="countryId" class="form-control middle" required>
                  <option> * Select * </option>
                  <?php foreach($CountryDataArr AS $k){?>
                  <option value="<?php echo $k->countryId;?>"><?php echo $k->countryName;?></option>
                  <?php }?>
            </select>
                        
                    </div>
              </div>
              <div class="form-group has-error">
                    <div class="input-group col-md-8 stateIdSpan"> 
                        <select name="stateId" id="stateId" class="form-control middle" required>
                            <option value=""> * Select * </option>  
                        </select>
                    </div>
                  
              </div>
              <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                      <input id="zip" name="zip" type="text" placeholder="enter your zip" class="form-control " required >
                      <span class="input-group-addon"><i class="fa fa-area-chart"></i></span>
                    </div>
                  
                </div>
            <div class="form-group has-error">
                    <div class="input-group col-md-8"> 
                    <input type="text" class="form-control bottom" placeholder="Security Code Shown on Left Side" name="secret" id="secret">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    </div>
                
            </div>
            <!--<input type="text"  name="contactNo" id="contactNo" placeholder="Contact No." class="form-control top" required>
              
              <input type="text" name="fax" id="fax" placeholder="Fax Number" class="form-control middle" required>
              <input type="text" name="address" id="address" placeholder="Address" class="form-control middle" required>
              <input type="text" name="city" id="city" placeholder="City" class="form-control middle" required>
              <input type="text" name="zip" placeholder="Zip Code / Postal Code" id="zip" class="form-control middle" required>-->
          </div>
              <div class="col-md-6"><textarea name="aboutMe" id="aboutMe" placeholder="About Me" class="form-control"></textarea></div>
          <div class="col-md-12"><p><input type="checkbox" value="1" class="required" id="agree" name="agree"> I agree to the <a style="text-decoration:underline;" href="javascript:void(0);">terms and conditions</a></p> </div>
          <div class="col-md-12"><p><input type="checkbox" value="1" id="receiveNewsLetter" name="receiveNewsLetter"> I want to receive newsletter updates</p></div>
              
          <div class="col-md-12">
           <button class="btn btn-lg btn-success btn-block" type="submit">Register</button>
          </div>
              
          </form>
        </div>
      </div>
      <hr>
      <div class="text-center">
        <ul class="list-inline">
          <li> <a class="text-muted" href="#login" data-toggle="tab">Login</a>  </li>
          <li> <a class="text-muted" href="#forgot" data-toggle="tab">Forgot Password</a>  </li>
          <li> <a class="text-muted" href="#signup" data-toggle="tab">Signup</a>  </li>
        </ul>
      </div>
    </div>
      <?php echo $footer;?>
      <script src="<?php echo SiteJSURL;?>login-registration.js"></script>
    <script type="text/javascript">
        myJsMain.login();
        myJsMain.registration();
        myJsMain.forgot_password();
      (function($) {
        $(document).ready(function() {
          $('.list-inline li > a').click(function() {
            var activeForm = $(this).attr('href') + ' > form';
            //console.log(activeForm);
            $(activeForm).addClass('animated fadeIn');
            //set timer to 1 seconds, after that, unload the animate animation
            setTimeout(function() {
              $(activeForm).removeClass('animated fadeIn');
            }, 1000);
          });
        });
        
        jQuery('#countryId').change(function(){
            jQuery.ajax({
                type:"POST",
                url:'<?php echo BASE_URL.'ajax/show_state/'?>',
                data:'countryId='+$(this).val(),
                success:function(msg){
                    if(msg!=""){
                        jQuery('.stateIdSpan').html(msg);
                    }
                }
            });
        });
      })(jQuery);
    </script>
    <!-- Modal Waiting -->
    
  </body>
</html>