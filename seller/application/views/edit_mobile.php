<?php echo $html_heading;
$productPageTypeArr=$this->config->item('productPageTypeArr');
$mobileBoxContents=$this->config->item('mobileBoxContents');
$mobileColor=$this->config->item('mobileColor');
$mobileDisplayResolution=$this->config->item('mobileDisplayResolution');
$mobileConnectivity=$this->config->item('mobileConnectivity');
$mobileOS=$this->config->item('mobileOS');
$mobileProcessorCores=$this->config->item('mobileProcessorCores');
$mobileBatteryType=$this->config->item('mobileBatteryType');
$mobileProcessorBrand=$this->config->item('mobileProcessorBrand');
$mobileCameraArr=$this->config->item('mobileCamera');
$ramData=$this->config->item('ramData');
$internalMemory=$this->config->item('internalMemory');
$expandableMemory=$this->config->item('expandableMemory');
$warrantyDuration=$this->config->item('warrantyDuration');
$priceRangeSettingsArr=$this->config->item('priceRangeSettings');
$priceRangeSettingsDataArr=$priceRangeSettingsArr[$productPageType];
?>
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>normalize.css"> 
<!--<link rel="stylesheet" href="http://necolas.github.io/normalize.css/2.1.3/normalize.css">-->
<link rel="stylesheet" href="<?php echo SiteCSSURL;?>jquery.idealforms.css">
<style>
.main_head {
    border-bottom: 1px solid #000;
    color: #1ab7be;
    font-size: 16px;
    font-weight: bold;
    margin: 15px 0 15px 15px;
}
</style>
<script type="text/javascript">
    priceOption='';
    <?php for($i=$priceRangeSettingsDataArr['start'];$i<$priceRangeSettingsDataArr['end'];$i=$i+$priceRangeSettingsDataArr['consistencyNo']){?>
        priceOption=priceOption+'<option value="<?php echo $i;?>"><?php echo $i;?></option>';
    <?php }?>    
</script>
<body class="  ">
    <div class="bg-dark dk" id="wrap">
        <?php echo $top.$left;?>
        <!-- /#left -->
  <div id="content">
    <div class="outer">
      <div class="inner bg-light lter">
        <div class="row">
          <div class="col-lg-12">
            <div class="box changed" style="display:block">
              <header>
                  <h5><i class="fa fa-pencil"></i> Update  Product <strong>{<?php echo $detail->title;?>}</strong></h5>
                <span style="padding-left: 150px;color: red;"><?php echo $this->session->flashdata('Message');?></span>
              </header>
                <input type="hidden" name="productPageType" value="<?php echo $productPageType;?>" />
                
                
                <div class="idealsteps-container">

      <nav class="idealsteps-nav"></nav>

      <form action="<?php echo BASE_URL.'product/edit_mobile_submit/';?>" autocomplete="off" class="idealforms" style="margin-bottom:40px;" method="POST" enctype="multipart/form-data" name="product_add_form" id="product_add_form">

        <div class="idealsteps-wrap">

          <!-- Step ppp -->

          <section class="idealsteps-step">
          
          <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Product Name</label>                     
                    </div>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" id="title" placeholder="Product Name" value="<?php echo $detail->title;?>" name="title" >
              <span class="error"></span>
                      </div>
                      </div>
                  <!--<div class="field">
               <label class="main">Product Name</label>
              <input type="text" class="form-control" id="title" placeholder="Product Name" value="" name="title">
              <span class="error"></span>
            </div> ---> 
                    </div>

           <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Product Short Description:</label>                     
                    </div>
                      <div class="col-sm-8">
                         <textarea class="form-control" id="shortDescription" placeholder="Short Description" rows="5" name="shortDescription"><?php echo $detail->shortDescription;?></textarea>
              <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    
           
            <div class="field">
              <div class="box">
                          <header>
                            <div class="icons"> <i class="fa fa-th-large"></i> </div>
                            <h5>Product Description</h5>
                            
                            <!-- .toolbar -->
                            <div class="toolbar">
                              <nav style="padding: 8px;"> <a href="javascript:;" class="btn btn-default btn-xs collapse-box"> <i class="fa fa-minus"></i> </a> <a href="javascript:;" class="btn btn-default btn-xs full-box"> <i class="fa fa-expand"></i> </a> </nav>
                            </div>
                            <!-- /.toolbar --> 
                          </header>
                          <div class="body">
                            <textarea class="ckeditor" cols="80" id="description" name="description" rows="10"><?php echo $detail->description;?></textarea>
                          </div>
                        </div>
            </div>


			<div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Meta Tag Title:</label>                     
                    </div>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" id="metaTitle" placeholder="Meta Tag Title" value="<?php echo $detail->metaTitle;?>" name="metaTitle" >
              <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    
          <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Meta Tag Description:</label>                     
                    </div>
                      <div class="col-sm-8">
                          <textarea class="form-control" id="metaDescription" placeholder="Meta Tag Description" rows="5" name="metaDescription"><?php echo $detail->metaDescription;?></textarea>
              <span class="error"></span>
                      </div>
                      </div>
                    </div>

           
			<div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Meta Tag Keywords:</label>                     
                    </div>
                      <div class="col-sm-8">
                           <textarea class="form-control" id="metaKeyword" placeholder="Meta Tag Keywords" rows="5" name="metaKeyword"><?php echo $detail->metaKeyword;?></textarea>
              <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    
          
			<div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                    <label class="control-label main">Product Tags:</label>                     
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="tag" placeholder="Product Tags" value="<?php echo $tag;?>" name="tag">
              <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    
          
          
            <div class="field buttons">
              <label class="main">&nbsp;</label>
              <button type="button" class="next">Next &raquo;</button>
            </div>

          </section>
          
          <!-- Step 2 -->
          <section class="idealsteps-step">
            
                    <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">In The Box</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Box Contents</label>
                    </div>
                      <div class="col-sm-8">
                          <?php 
                          $oldMobileBoxContents=  explode(',',$detail->mobileBoxContent);
                          foreach ($mobileBoxContents AS $k=>$v){?>
                          <div class="checkbox">
                            <label>
                                <input type="checkbox" value="<?php echo $k;?>" class="uniform" name="mobileBoxContent[]" <?php echo (in_array($k, $oldMobileBoxContents) ? 'checked' : '');?>><?php echo $v;?> 
                            </label>
                          </div>
                          <?php }?>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Brand</label>
                    </div>
                      <div class="col-sm-8">
                        <select class="form-control" name="brandId" id="brandId">
                            <option value="default">Select</option>  
                            <?php foreach ($brandArr AS $k){?>
                            <option value="<?php echo $k->brandId;?>" <?php if($k->brandId==$detail->brandId){?>selected<?php }?>><?php echo $k->title;?></option>  
                            <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
              
                  <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Model</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="model" placeholder="Model" value="<?php echo $detail->model;?>" name="model">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                 
              
              <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">No Of SIMs</label>
                    </div>
                      <div class="col-sm-8">
                        <select class="form-control" name="noOfSims" id="noOfSims">
                            <option value="default">Select</option>  
                            <option value="1" <?php if($detail->noOfSims==1){?>selected<?php }?>>1</option>  
                            <option value="2" <?php if($detail->noOfSims==2){?>selected<?php }?>>2</option>  
                            <option value="3" <?php if($detail->noOfSims==3){?>selected<?php }?>>3</option>  
                            <option value="4" <?php if($detail->noOfSims==4){?>selected<?php }?>>4</option>  
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="field idealforms-field idealforms-field-checkbox">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Color</label>
                    </div>
                      <div class="col-sm-8">
                          <select class="form-control" name="color" id="color">
                          <option value="default">Select</option>
                          <?php foreach ($mobileColor AS $k=>$v){?>   
                          <option value="<?php echo $k;?>" <?php if($k==$detail->color){?>selected<?php }?>><?php echo $v; ?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Other Features</label>
                    </div>
                      <div class="col-sm-8">
                          <textarea class="form-control" id="mobileOtherFeatures" placeholder="Other Features" rows="5" name="mobileOtherFeatures"><?php echo $detail->mobileOtherFeatures;?></textarea>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>                    
                    
                  <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Display</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Screen Size (in cm)</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" placeholder="Screen Size" value="<?php echo $detail->screenSize;?>" name="screenSize" id="screenSize">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Display Resolution</label>
                    </div>
                      <div class="col-sm-8">
                         <select class="form-control required" id="displayResolution" name="displayResolution">
                         <option value="">Select</option>
                         <?php foreach ($mobileDisplayResolution AS $k=>$v){?>
                          <option value="<?php echo $k;?>" <?php if($k==$detail->displayResolution){?>selected<?php }?>><?php echo $v;?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Display Type</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="displayType" placeholder="Display Type" value="<?php echo $detail->displayType;?>" name="displayType">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Pixel Density</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="pixelDensity" placeholder="Pixel Density" value="<?php echo $detail->pixelDensity;?>" name="pixelDensity">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Software</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Operating System</label>
                    </div>
                      <div class="col-sm-8">
                          <select class="form-control required" id="os" name="os">
                         <option value="default">Select</option>  
                         <?php foreach ($mobileOS AS $k=>$v){?>
                          <option value="<?php echo $k;?>" <?php if($k==$detail->os){?>selected<?php }?>><?php echo $v;?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                     
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">OS Version</label>
                    </div>
                      <div class="col-sm-8">
                         <input type="text" class="form-control" id="osVersion" placeholder="OS Version" value="<?php echo $detail->osVersion;?>" name="osVersion">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                     
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Multi-languages Supported</label>
                    </div>
                      <div class="col-sm-8">
                          <label class="ideal-radiocheck-label" onClick="">
                              <input type="radio" value="Yes" name="multiLanguages" style="position: absolute; left: -9999px;" <?php if($detail->multiLanguages=='Yes'){?>checked<?php }?>>
Yes</label>
                         <label class="ideal-radiocheck-label" onClick="">
                             <input type="radio" value="No" name="multiLanguages" style="position: absolute; left: -9999px;" <?php if($detail->multiLanguages=='No'){?>checked<?php }?>>
No</label>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                 <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Camera</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Rear Camera</label>
                    </div>
                      <div class="col-sm-8">
                          <select name="mobileRearCamera" id="mobileRearCamera" class="form-control">
                              <option value="default">Select</option>
                              <?php foreach($mobileCameraArr AS $k => $v):?>
                              <option value="<?php echo $k;?>" <?php if($k==$detail->mobileRearCamera){?>selected<?php }?>><?php echo $v;?></option>
                              <?php endforeach;?>
                          </select>
                          
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Flash</label>
                    </div>
                      <div class="col-sm-8">
                          <label class="ideal-radiocheck-label" onClick="">
<input type="radio" value="Yes" name="mobileFlash" style="position: absolute; left: -9999px;" <?php if($detail->mobileFlash=='Yes'){?>checked<?php }?>>
Yes</label>
                         <label class="ideal-radiocheck-label" onClick="">
                             <input type="radio" value="No" name="mobileFlash" style="position: absolute; left: -9999px;" <?php if($detail->mobileFlash=='No'){?>checked<?php }?>>
No</label>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Front Camera</label>
                    </div>
                      <div class="col-sm-8">
                          <select name="frontCamera" id="frontCamera" class="form-control">
                              <option value="default">Select</option>
                              <?php foreach($mobileCameraArr AS $k => $v):?>
                              <option value="<?php echo $k;?>" <?php if($k==$detail->frontCamera){?>selected<?php }?>><?php echo $v;?></option>
                              <?php endforeach;?>
                          </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                     
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Other Camera Features</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="mobileOtherCameraFeatures" placeholder="mobile Other Camera Features" value="<?php echo $detail->mobileOtherCameraFeatures;?>" name="mobileOtherCameraFeatures">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Connectivity</p>
                    <div class="col-sm-4">
                        <label for="input-model" class="control-label main">&nbsp;</label>
                    </div>
                      <div class="col-sm-8">
                          <?php $oldMobileConnectivity =  explode(',',$detail->mobileConnectivity);foreach ($mobileConnectivity AS $k=>$v){?>
                          <div class="checkbox">
                            <label>
                                <input type="checkbox" value="<?php echo $k;?>" class="uniform" name="mobileConnectivity[]" <?php echo (in_array($k, $oldMobileBoxContents) ? 'checked' : '');?>><?php echo $v;?> 
                            </label>
                          </div>
                          <?php }?>
                        <span class="error"></span>
                      </div>
                    
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Processor</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Processor Speed</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="processorSpeed" placeholder="Processor Speed" value="<?php echo $detail->processorSpeed;?>" name="processorSpeed">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Processor Cores</label>
                    </div>
                      <div class="col-sm-8">
                          <select class="form-control required" id="processorCores" name="processorCores">
                         <option value="default">Select</option>
                         <?php foreach ($mobileProcessorCores AS $k=>$v){?>
                          <option value="<?php echo $k;?>" <?php if($k==$detail->processorCores){?>selected<?php }?>><?php echo $v;?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Processor Brand</label>
                    </div>
                      <div class="col-sm-8">
                          <select class="form-control required" id="processorBrand" name="processorBrand">
                         <option value="">Select</option>
                         <?php foreach ($mobileProcessorBrand AS $k=>$v){?>
                          <option value="<?php echo $k;?>" <?php if($k==$detail->processorBrand){?>selected<?php }?>><?php echo $v;?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Memory & Storage</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">RAM</label>
                    </div>
                      <div class="col-sm-8">
                          <select name="ram" id="ram" class="form-control">
                              <option value="default">Select</option>
                              <?php foreach($ramData AS $k => $v):?>
                              <option value="<?php echo $k;?>" <?php if($k==$detail->ram){?>selected<?php }?>><?php echo $v;?></option>
                              <?php endforeach;?>
                          </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Internal Memory</label>
                    </div>
                      <div class="col-sm-8">
                          <select name="internalMemory" id="internalMemory" class="form-control">
                              <option value="default">Select</option>
                              <?php foreach($internalMemory AS $k => $v):?>
                              <option value="<?php echo $k;?>" <?php if($k==$detail->internalMemory){?>selected<?php }?>><?php echo $v;?></option>
                              <?php endforeach;?>
                          </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Expandable Memory</label>
                    </div>
                      <div class="col-sm-8">
                          <select name="expandableMemory" id="expandableMemory" class="form-control">
                              <option value="default">Select</option>
                              <?php foreach($expandableMemory AS $k=>$v):?>
                              <option value="<?php echo $k;?>" <?php if($k==$detail->expandableMemory){?>selected<?php }?>><?php echo $v;?></option>
                              <?php endforeach;?>
                          </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Memory Card Slot</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="memoryCardSlot" placeholder="Memory Card Slot" value="<?php echo $detail->memoryCardSlot;?>" name="memoryCardSlot">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Battery & Power</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Battery Capacity</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="batteryCapacity" placeholder="Battery Capacity" value="<?php echo $detail->batteryCapacity;?>" name="batteryCapacity">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Battery Type</label>
                    </div>
                      <div class="col-sm-8">
                          <select class="form-control required" id="batteryType" name="batteryType">
                         <option value="">Select</option>
                         <?php foreach ($mobileBatteryType AS $k=>$v){?>
                          <option value="<?php echo $k;?>" <?php if($k==$detail->batteryType){?>selected<?php }?>><?php echo $v;?></option>
                          <?php }?>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Talk Time</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="talkTime" placeholder="Talk Time" value="<?php echo $detail->talkTime;?>" name="talkTime">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Standby Time</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="standbyTime" placeholder="Standby Time" value="<?php echo $detail->standbyTime;?>" name="standbyTime">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Warranty</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Warranty Type</label>
                    </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="warrantyType" placeholder="Warranty Type" value="<?php echo $detail->warrantyType;?>" name="warrantyType">
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>
                    
                     <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Warranty Duration(In Month)</label>
                    </div>
                      <div class="col-sm-8">
                          <select name="warrantyDuration" id="warrantyDuration" class="form-control">
                              <option value="default">Select</option>
                              <?php foreach($warrantyDuration AS $k => $v):?>
                              <option value="<?php echo $k;?>" <?php if($k==$detail->warrantyDuration){?>selected<?php }?>><?php echo $v;?></option>
                              <?php endforeach;?>
                          </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div>  

                    
           <div class="field buttons">
              <label class="main">&nbsp;</label>
              <button type="button" class="prev">&laquo; Prev</button>
              <button type="button" class="next">Next &raquo;</button>
            </div>

          </section>
          
          <!-- Step 3 -->
          <section class="idealsteps-step">
                    <!--<div class="form-group field">
                      <label for="input-category" class=" control-label main"><span title="" data-toggle="tooltip" data-original-title="(Autocomplete)">Categories</span></label>
                      <div class="col-sm-10">
                        <!--<input type="text" class="form-control" id="input-category" placeholder="Categories" value="" name="category" autocomplete="off"><span class="error"></span>
                        <ul class="dropdown-menu">
                        </ul>
                          
                      </div>
                    </div>-->
                    <input type="hidden" name="categoryId" id="categoryId" value="<?php echo $categoryId;?>">
                                                         
                   <div class="form-group field required field">
                    <div class="row">
                    <p class="main_head">Other Informations</p>
                    <div class="col-sm-4">
                      <label for="input-model" class="control-label main">Price</label>
                    </div>
                    <div class="col-sm-8">
                        <div class="col-sm-12"  style="padding:0;">
                            <div class="col-sm-12 form-group field required field"  style="padding:0;">
                                <div class="col-sm-6" style="padding:0;"><label style="margin-top:8px;" for="bulkQty">Quantity</label></div>
                                <div class="col-sm-6"  style="padding:0;">
                                    <select class="" id="bulkQty" name="bulkQty" style="width:auto;">
                                          <option value="default">Select</option>
                                          <?php for($i=$priceRangeSettingsDataArr['start'];$i<$priceRangeSettingsDataArr['end'];$i+=5){?>
                                          <option value="<?php echo $i;?>" <?php if($i==$bulkQty){?>selected<?php }?>><?php echo $i;?></option>
                                          <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12"  style="padding:0;height: 5px;"></div>
                            <div class="col-sm-12 form-group field required field"  style="padding:0;">
                                <div class="col-sm-6" style="padding:0;"><label for="price">Price</label></div>
                                <div class="col-sm-6" style="padding:0;"><input type="text" class="form-control" id="price" placeholder="Price" value="<?php echo $bulkPrice;?>" name="price"  style="width:auto;"></div>
                            </div>
                        </div>
                        <div class="col-sm-12 more_price_quantity_for_product"  style="padding:0;">
                            <?php echo $productPriceView;?>
                        </div>
                        <div class="col-sm-12"  style="padding:0;"><button class="addRow" type="button" onClick="addPrice();">Add Another Row</button>
                            <input type="hidden" name="total_price_row_added" id="total_price_row_added" value="<?php echo $totalPriceRowAdded;?>">
                        </div>
                    </div>
                    </div>
                    </div>
                   
                   <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                     <label for="input-tax-class" class="control-label main">Tax Class</label>
                      </div>
                      <div class="col-sm-8">
                          <select class="form-control" id="taxable" name="taxable" >
                          <option value=""> --- Select --- </option>
                          <option value="1" <?php if(1==$detail->taxable){?>selected<?php }?>>Taxable Goods</option>
                          <option value="2" <?php if(2==$detail->taxable){?>selected<?php }?>>Non-taxable Products</option>
                        </select>
                        <span class="error"></span>
                      </div>
                      </div>
                    </div> 
                    
                   <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                     <label for="input-minimum" class="control-label main"><span title="" data-toggle="tooltip" data-original-title="Force a minimum ordered amount">Minimum Quantity</span></label> 
                       </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control required" id="minQty" placeholder="Minimum Quantity" value="<?php echo $detail->minQty;?>" name="minQty"><span class="error"></span>
                      </div>
                      </div>
                    </div> 
                 
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-subtract" class="control-label main">Quantity</label>
                      </div>
                      <div class="col-sm-8">
                          <input type="text" class="form-control" id="qty" placeholder="Minimum Quantity" value="<?php echo $detail->qty;?>" name="qty">
                        <span class="error"></span>
                       </div>
                      </div>
                    </div>
                    
                   <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-length" class=" control-label main">Dimensions (L x W x H)</label>
                     </div>
                      <div class="col-sm-8">
                        <div class="row">
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="length" placeholder="Length" value="<?php echo $detail->length;?>" name="length" style="width:100px;">
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="width" placeholder="Width" value="<?php echo $detail->width;?>" name="width"  style="width:100px;">
                          </div>
                          <div class="col-sm-4">
                            <input type="text" class="form-control" id="height" placeholder="Height" value="<?php echo $detail->height;?>" name="height"  style="width:100px;">
                          </div>
                        </div>
                      </div>
                    </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-length-class" class=" control-label main">Length Class</label>
                      </div>
                      <div class="col-sm-8">
                        <select class="form-control" id="lengthClass" name="lengthClass">
                          <option value="1" <?php if(1==$detail->lengthClass){?>selected<?php }?>>Centimeter</option>
                          <option value="2" <?php if(2==$detail->lengthClass){?>selected<?php }?>>Millimeter</option>
                          <option value="3" <?php if(3==$detail->lengthClass){?>selected<?php }?>>Inch</option>
                        </select>
                        
                        <span class="error"></span>
                      </div>
                    </div>
                    </div>
                    
                   <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-weight" class=" control-label main">Weight</label>
                      </div>
                      <div class="col-sm-8">
                        <input type="text" class="form-control" id="weight" placeholder="Weight" value="<?php echo $detail->weight;?>" name="weight"><span class="error"></span>
                      </div>
                    </div>
                    </div>
                    
                    <div class="form-group field required field">
                    <div class="row">
                    <div class="col-sm-4">
                      <label for="input-weight-class" class=" control-label main">Weight Class</label>
                     </div>
                      <div class="col-sm-8">
                        <select class="form-control" id="weightClass" name="weightClass">
                          <option value="1" <?php if(1==$detail->weightClass){?>selected<?php }?>>Kilogram</option>
                          <option value="2" <?php if(2==$detail->weightClass){?>selected<?php }?>>Gram</option>
                          <option value="3" <?php if(3==$detail->weightClass){?>selected<?php }?>>Pound </option>
                          <option value="4" <?php if(4==$detail->weightClass){?>selected<?php }?>>Quintal </option>
                        </select>
                        <span class="error"></span>
                      </div>
                    </div>
                    </div>
                    
                    
            <div class="field buttons">
              <label class="main">&nbsp;</label>
              <button type="button" class="prev">&laquo; Prev</button>
              <button type="button" class="next">Next &raquo;</button>
            </div>

          </section>
          
           <section class="idealsteps-step">
         	 <div class="table-responsive">
                      <table class="table table-striped table-bordered table-hover" id="images">
                        <thead>
                          <tr>
                            <td class="text-left">Image</td>
                          </tr>
                        </thead>
                        <tbody>
                         <tr>
                            <td class="text-left">
                          <div data-provides="fileinput" class="fileinput fileinput-new">
                            <div class="field">
                                <label class="main">Picture:</label>
                                <?php if(key_exists(1, $productImageArr)):
                                    if(file_exists($this->config->item('ResourcesPath').'product/100X100/'.$productImageArr[0]->image)): ?>
                                   <input type="hidden" name="oldImgFile1" value="<?php echo $productImageArr[0]->image;?>" >
                                   <img src="<?php echo SiteResourcesURL.'product/100X100/'.$productImageArr[0]->image;?>" class="showFileElement"/>
                                   <input id="img1" name="img1" type="file" style="display:none;" class="removeFileNow"> 
                                   <?php else:?> 
                                   <input id="img1" name="img1" type="file">                            
                                   <?php endif;?>
                                <?php else:?> 
                                    <input id="img1" name="img1" type="file">                            
                                <?php endif;?>
                                <span class="error"></span>
                            </div>                            
                          </div>
                              </td>
                          </tr>
                          <tr>
                            <td class="text-left">
                            	<div data-provides="fileinput" class="fileinput fileinput-new">
                            <div class="field">
                                <label class="main">Picture:</label>
                                <?php if(key_exists(1, $productImageArr)):
                                    if(file_exists($this->config->item('ResourcesPath').'product/100X100/'.$productImageArr[1]->image)): ?> 
                                    <input type="hidden" name="oldImgFile2" value="<?php echo $productImageArr[1]->image;?>" >
                                    <img src="<?php echo SiteResourcesURL.'product/100X100/'.$productImageArr[1]->image;?>" class="showFileElement"/>
                                    <input id="img2" name="img2" type="file" style="display:none;" class="removeFileNow"> 
                                    <?php else:?>
                                    <input id="img2" name="img2" type="file">                            
                                    <?php endif;?>
                                <?php else:?>
                                <input id="img2" name="img2" type="file">                            
                                <?php endif;?>
                                <span class="error"></span>
                            </div>                            
                          </div>
                              </td>
                          </tr>
                          
                          <tr>
                            <td class="text-left">
                            	<div data-provides="fileinput" class="fileinput fileinput-new">
                            <div class="field">
                                <label class="main">Picture:</label>                            
                                <?php if(key_exists(2, $productImageArr)):
                                    if(file_exists($this->config->item('ResourcesPath').'product/100X100/'.$productImageArr[2]->image)): ?> 
                                <input type="hidden" name="oldImgFile3" value="<?php echo $productImageArr[2]->image;?>" >
                                    <img src="<?php echo SiteResourcesURL.'product/100X100/'.$productImageArr[2]->image;?>" class="showFileElement"/>
                                    <input id="img3" name="img3" type="file" style="display:none;" class="removeFileNow"> 
                                    <?php else:?>
                                    <input id="img3" name="img3" type="file">                            
                                    <?php endif;?>
                                <?php else:?>
                                <input id="img3" name="img3" type="file">                            
                                <?php endif;?>
                                <span class="error"></span>
                            </div>                            
                          </div>
                              </td>
                          </tr>
                          
                          <tr>
                            <td class="text-left">
                            
                            <div data-provides="fileinput" class="fileinput fileinput-new">
                            <div class="field">
                                <label class="main">Picture:</label>                            
                                <?php if(key_exists(3, $productImageArr)):
                                    if(file_exists($this->config->item('ResourcesPath').'product/100X100/'.$productImageArr[3]->image)): ?> 
                                <input type="hidden" name="oldImgFile4" value="<?php echo $productImageArr[3]->image;?>" >
                                    <img src="<?php echo SiteResourcesURL.'product/100X100/'.$productImageArr[3]->image;?>" class="showFileElement"/>
                                    <input id="img4" name="img4" type="file" style="display:none;" class="removeFileNow"> 
                                    <?php else:?>
                                    <input id="img4" name="img4" type="file">                            
                                    <?php endif;?>
                                <?php else:?>
                                <input id="img4" name="img4" type="file">                            
                                <?php endif;?>
                                <span class="error"></span>
                            </div>                            
                          </div>
                              </td>
                          </tr>
                          
                          <tr>
                            <td class="text-left">
                            
                            <div data-provides="fileinput" class="fileinput fileinput-new">
                            <div class="field">
                                <label class="main">Picture:</label>                            
                                <?php if(key_exists(4, $productImageArr)):
                                    if(file_exists($this->config->item('ResourcesPath').'product/100X100/'.$productImageArr[4]->image)): ?> 
                                <input type="hidden" name="oldImgFile5" value="<?php echo $productImageArr[4]->image;?>" >
                                    <img src="<?php echo SiteResourcesURL.'product/100X100/'.$productImageArr[4]->image;?>" class="showFileElement"/>
                                    <input id="img5" name="img5" type="file" style="display:none;" class="removeFileNow"> 
                                    <?php else:?>
                                    <input id="img5" name="img5" type="file">                            
                                    <?php endif;?>
                                <?php else:?>
                                <input id="img5" name="img5" type="file">                            
                                <?php endif;?>
                                <span class="error"></span>
                            </div>                            
                          </div>
                              </td>
                          </tr>
                         
                        </tbody>
                        
                      </table>
                    </div>
               <input type="hidden" name="productId" value="<?php echo $detail->productId;?>">
                    <div class="field buttons">
              <label class="main">&nbsp;</label>
              <button type="button" class="prev">&laquo; Prev</button>
              <button type="submit" class="submit">Submit</button>
            </div>
 			</section>
        </div>
        <span id="invalid"></span>
      </form>
    </div>
    </div>
          </div>
          <!-- /.col-lg-12 --> 
        </div>
        <!-- /.row --> 
      </div>
      <!-- /.inner --> 
    </div>
    <!-- /.outer --> 
  </div>
        
    </div>
    <script src="<?php echo SiteJSURL;?>jquery.form.min.js" type="text/javascript"></script> 
    <script src="<?php echo SiteJSURL;?>jquery.idealforms.js" type="text/javascript"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.4.5/ckeditor.js"></script>
    <?php echo $footer;?>
    
    <script src="<?php echo SiteJSURL;?>metis-form-general.js" type="text/javascript"></script>
    <script src="<?php echo SiteJSURL;?>metis-form-validation.js" type="text/javascript"></script>
    <script src="<?php echo SiteJSURL;?>metis-form-wizard.js" type="text/javascript"></script>
    <script src="<?php echo SiteJSURL;?>metis-form-wysiwyg.js" type="text/javascript"></script>
    
</body>
</html>
<script type="text/javascript">
    var hideFileValueExistElement;
    function hideFileValueExistElementFun(){
        $('.removeFileNow').next().css('display','none');
        clearInterval(hideFileValueExistElement);
    }
    hideFileValueExistElement = setInterval(hideFileValueExistElementFun, 2000);
        
    jQuery(document).ready(function(){
        $('.showFileElement').click(function(){
            $(this).next().css('display','block');
            $(this).next().next().css('display','block');
        });
        jQuery('#div-2').show();
	jQuery('.changed').css('display','inline'); 
        
        jQuery('#productPageType').on('change',function(){
            if(jQuery(this).val()!=""){
                location.href='<?php echo BASE_URL .'product/add_product/'?>'+jQuery(this).val();
            }
        });
        
        //Metis.formWysiwyg();
        
          jQuery('form.idealforms').find('input, select, textarea').on('change keyup', function() {
            jQuery('#invalid').hide();
          });

          /*jQuery('form.idealforms').idealforms('addRules', {
            'comments': 'required minmax:50:200'
          });*/

          jQuery('.prev').click(function(){
            jQuery('.prev').show();
            jQuery('form.idealforms').idealforms('prevStep');
          });
          jQuery('.next').click(function(){
            jQuery('.next').show();
            jQuery('form.idealforms').idealforms('nextStep');
          });
          
          jQuery('.more_price_quantity_for_product').on('click','.removePriceRow',function(){
              var currentPriceRow=jQuery(this).attr('alt');
              jQuery('#remove_price_quantity_for_product_'+jQuery(this).attr('alt')).remove();
              if(jQuery('.more_price_quantity_for_product').html()==''){price_row=0;}
          });
          
          jQuery('#bulkQty').on('change',function(){
              if($.trim(jQuery('#price').val())== ""){
                  //
              }
          });
    });
    
var price_row = '<?php echo $totalPriceRowAdded-1;?>';
function addPrice() {
	
	price_row++;
        
	tabRow = '';
        //tabRow += '';
        //tabRow += '<div class="col-sm-12"  style="padding:0;"></div>';
        tabRow += '<div class="col-sm-12"  style="padding:0;margin-top:5px;" id="remove_price_quantity_for_product_'+price_row+'">';

        tabRow += '<div class="col-sm-12" style="padding:0;">';
        tabRow += '<div class="col-sm-6" style="padding:0;"><label style="margin-top:8px;" for="bulkQty_' + price_row + '">Quantity ' + price_row + '</label></div>';
        tabRow += '<div class="col-sm-6"  style="padding:0;">';
        tabRow += '<select class="required" id="bulkQty_' + price_row + '" name="bulkQty_' + price_row + '" style="width:auto;">';
        tabRow += '<option value="">-- Select --</option>';
        tabRow += priceOption;
        tabRow += '</select>';
        tabRow += '</div>';
        tabRow += '</div>';

        tabRow += '<div class="col-sm-12"  style="padding:0;height: 5px;"></div>';
        tabRow += '<div class="col-sm-12" style="padding:0;">';
        tabRow += '<div class="col-sm-6" style="padding:0;"><label for="price_' + price_row + '">Price ' + price_row + '</label></div>';
        tabRow += '<div class="col-sm-3" style="padding:0;"><input type="text" class="form-control required" id="price_' + price_row + '" placeholder="Price" value="" name="price_' + price_row + '"  style="width:auto;"></div>';
        tabRow += '<div class="col-sm-1" style="padding:0;"></div>';
        tabRow += '<div class="col-sm-2" style="padding:0;"><button class="removePriceRow" type="button" alt="' + price_row + '">Remove Row</button></div>';
        tabRow += '</div>';
        tabRow += '</div>';
        //alert(tabRow);
        jQuery('#total_price_row_added').val(price_row+1);
        //alert(tabRow);
	jQuery(".more_price_quantity_for_product").append(tabRow);
    //var qty='bulkQty_' + price_row ;
    //var prc='price_' + price_row ;
    //jQuery('form.idealforms').idealforms('toggleFields', qty+' '+prc);
    return false;
}

jQuery(document).ready(function(){
    
    jQuery('form.idealforms').idealforms({
      silentLoad: false,
      rules: {
        'title': 'required minmax:5:30',
        'shortDescription':'required minmax:10:200',
        'metaTitle': 'required',
        'metaDescription': 'required',
        'metaKeyword': 'required',
        'tag': 'required',
        'mobileBoxContent[]': 'minoption:2 maxoption:6',
        'brandId': 'select:default',
        'model': 'required',
        'noOfSims': 'select:default',
        'screenSize': 'required',
        'color': 'select:default',
        'os': 'select:default',
        'ram': 'required',
        'internalMemory': 'required',
        'talkTime':'required',
        'standbyTime':'required',
        'WarrantyDuration': 'required',
        'qty': 'required',
        'minQty': 'required',
        'mobileRearCamera': 'select:default',
        'frontCamera': 'select:default',
        'processorSpeed': 'required',
        'processorCores':'select:default',
        'bulkQty': 'select:default',
        'price': 'required',
      },

      onSubmit: function(invalid, e) {
        jQuery('#invalid')
          .show()
          .toggleClass('valid', ! invalid)
          .text(invalid ? (invalid +' invalid fields') : 'All good!');
          if(invalid==0){
              //alert('submiting form');
                /*for ( instance in CKEDITOR.instances )
                    CKEDITOR.instances[instance].updateElement();
              //alert('lets us submit the form by ajax');
              myJsMain.commonFunction.ajaxSubmit($('#product_add_form'),myJsMain.baseURL+'product/add_mobile' , productAddFormCallback);*/
          }else{
              e.preventDefault();
          }
      }
    });
});

function productAddFormCallback(){
    myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.msg,200);
    if(resultData.result=='good'){
        location.href=myJsMain.baseURL+'product/viewlist';
        //myJsMain.commonFunction.tidiitAlert('Tidiit System Message',resultData.url,200);
    }
}
</script>