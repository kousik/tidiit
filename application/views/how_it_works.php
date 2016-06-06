<button class="inline btn btn-primary howItWork1" data-toggle="modal" data-target=".bs-example-modal-lg1" href="#inline_content" style="display: none;">HOW IT WORKS</button>
<div class="modal fade bs-example-modal-lg1" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="inline_content">
    <div class="modal-dialog modal-lg opn_box" style='padding:10px; background:#fff;  border-radius:10px;'>
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <div class="container">
            <h2>HOW IT WORKS</h2>
            
            <?php 
            pre($howItWorksBoxContent);
            /*
                <img src="<?php echo SiteImagesURL;?>works_img.jpg" class="img-responsive" />
                <h4>A Better Way to Purchase For Your Business</h4>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-4">
                        <div class="work_box">
                        <img src="<?php echo SiteImagesURL;?>work_img.png" />
                        <p>Tell us what you need and we will send your request to all relevant suppliers </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                        <div class="work_box">
                        <img src="<?php echo SiteImagesURL;?>work_img1.png" />
                        <p>Receive and compare multiple customized quotes within 48 hours </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                        <div class="work_box">
                        <img src="<?php echo SiteImagesURL;?>work_img2.png" />
                        <p>Negotiate and finalize the deal directly with the suppliers, knowing that you have made the right choice </p>
                    </div>
                </div>
            </div> */?>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        $('.howItWork1').click();
    });
    /*$('.bs-example-modal-lg1').on('hidden.bs.modal', function () {
        // do something…
        myJsMain.commonFunction.tidiitAlert('Tidiit System','returning ',180);
      });*/
</script>