<a id="button1" class="quick_cntct" href="javascript:void(0)">FEEDBACK</a>
        
<div class="side_contact" id="t1">
    <h3>Feedback</h3>
    <form method="post" action="" name="tidiitFeedback" id="tidiitFeedback" class="feed_back">
        <input type="text" class="required" placeholder="Enter Your Name" id="feedBackName" name="feedBackName">
        <input type="text" class="required" placeholder="Enter Your Phone" id="feedBackPhone" name="feedBackPhone">
        <input type="text" class="required email" placeholder="Enter Your Email Id" id="feedBackEmail" name="feedBackEmail">
        <textarea class="required" placeholder="Enter Your Message" rows="" cols="" name="feedBackMessage" id="feedBackMessage"></textarea>
        <div style="height:10px;"></div>
        <canvas id="secret_img" width="130" height="25" style="border:1px solid #d3d3d3;padding-top: 8px; padding-left: 15px;"> 
        <?php echo 'Browser Not Support HTML5';?>.</canvas>
        <br /><span style="color: #fff !important;">Not getting ?<a href="javascript:void(0);" onclick="myJsMain.commonFunction.GeneratNewImage();" style="color: #fff !important;text-decoration: underline;">Click here</a> for a new.</span>
        <input type="text" class="required" name="secret" id="secret" placeholder="Enter the above text here"/>
        <input type="submit" value="Submit" name="feedBackSubmit" id="feedBackSubmit">
    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
       myJsMain.commonFunction.GeneratNewImage(); 
    });
    var feedBackValidationRules = {
        feedBackEmail: {required: true,email:true},
        feedBackName:{required: true},
        feedBackPhone:{required: true},
        feedBackMessage:{required: true},
        secret:{required: true},
    };
    $('#tidiitFeedback').validate({rules: feedBackValidationRules,onsubmit: true});
    $('#tidiitFeedback').submit(function(e) {
        e.preventDefault();
        if ($(this).valid()) {
            myJsMain.commonFunction.showPleaseWait();
            $('#feedBackSubmit').prop('disabled',true);
            myJsMain.commonFunction.ajaxSubmit($(this),myJsMain.baseURL+'ajax/submit_feedback', feedBackFormCallback);
        }
    });
    
    function feedBackFormCallback(resultData){
        myJsMain.commonFunction.hidePleaseWait();
        $('#feedBackSubmit').prop('disabled',false);
        if(resultData.result=='bad'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
        }else if(resultData.result=='good'){
            myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
            $('#tidiitFeedback')[0].reset();
            myJsMain.commonFunction.GeneratNewImage(); 
        }
    }
    
</script>