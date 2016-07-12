<?php echo $html_heading;
$productPageTypeArr=$this->config->item('productPageTypeArr');
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

      <form action="<?php echo BASE_URL.'product/update_common_product/';?>" autocomplete="off" class="idealforms" style="margin-bottom:40px;" method="POST" enctype="multipart/form-data" name="product_add_form" id="product_add_form">

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
                <?php if($options):?>
                    <?php foreach($options_arrenge as $opid):
                        $optdata = $options[$opid];
                        $optionsdata = $optdata->option_data;
                        $pieces = explode(",", $optionsdata);
                        $prod_options = $proptions[$optdata->id];

                        ?>
                        <div class="form-group field required field">
                            <div class="row">
                                <p class="main_head"><?=$optdata->display_name?></p>
                                <div class="col-sm-4">
                                    <label for="input-model" class="control-label main"><?=$optdata->display_name?></label>
                                </div>
                                <div class="col-sm-8">
                                    <?php if($optdata->type == 'text'):?>
                                        <div class="text">
                                            <label>
                                                <input type="text" name="options[<?=$optdata->id?>][]" value="<?=$prod_options[0]->value?>">
                                            </label>
                                        </div>
                                    <?php endif;?>
                                    <?php if($optdata->type == 'textarea'):?>
                                        <div class="text">
                                            <label>
                                                <textarea name="options[<?=$optdata->id?>][]" value=""><?=$prod_options[0]->value?></textarea>
                                            </label>
                                        </div>
                                    <?php endif;?>

                                    <?php if($optdata->type == 'dropdown'):?>
                                        <select class="form-control" name="options[<?=$optdata->id?>][]">
                                            <?php foreach ($pieces AS $sk => $sv): ?>
                                                <option value="<?=$sv?>" <?php if($prod_options[0]->value == $sv){?>selected<?php }?>><?=$sv?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif;?>

                                    <?php if($optdata->type == 'checkbox' || $optdata->type == 'radio'):?>
                                        <?php foreach ($pieces AS $k => $v): ?>
                                            <div class="<?=$optdata->type?>" style="width:30%; float:left;">
                                                <?php
                                                $value = trim($v);
                                                $cbox = "";
                                                if(strtolower($optdata->display_name) == "color" ): $clrs = explode("||", $v); $value = trim($clrs[0]);
                                                    $cbox = "<div style='width: 15px; height: 15px; background-color:".$clrs[1]."; padding-top:3px; float:right;'></div>";?>
                                                <?php endif;
                                                $checked = "";
                                                foreach($prod_options  as $pkey => $opval):
                                                    if($value == $opval->value):
                                                        $checked = 'checked="checked"';
                                                    endif;
                                                endforeach;

                                                ?>
                                                <label>
                                                    <input type="<?=$optdata->type?>" value="<?=$value?>" class="" name="options[<?=$optdata->id?>][]" <?=$checked?>><?=$value?><?=$cbox?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif;?>
                                    <?php if($optdata->required):?>
                                        <span class="error"></span>
                                    <?php endif;?>

                                </div>
                                <div style="clear: both;"></div>
                            </div>
                        </div>
                    <?php endforeach;?>
                <?php endif;?>
                <div class="field buttons">
                    <label class="main">&nbsp;</label>
                    <button type="button" class="prev">&laquo; Prev</button>
                    <button type="button" class="next">Next &raquo;</button>
                </div>

            </section>
          
          <!-- Step 3 -->
          <section class="idealsteps-step">
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

              <div class="form-group field">
                  <div class="row">
                      <div class="col-sm-4">
                          <label for="input-status"
                                 class=" control-label main">Is New ?</label>
                      </div>
                      <div class="col-sm-8">
                          <label>
                          <input type="checkbox" name="isNew" value="1" <?php if(1==$detail->isNew){?>checked<?php }?>>
                          </label>
                      </div>
                  </div>
              </div>
              <div class="form-group field">
                  <div class="row">
                      <div class="col-sm-4">
                          <label for="input-status"
                                 class=" control-label main">Is Popular Product ?</label>
                      </div>
                      <div class="col-sm-8">
                          <div class="checkbox">
                              <label>
                            <input type="checkbox" name="popular" value="1" <?php if(1==$detail->popular){?>checked<?php }?>>
                                  </label>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="form-group field">
                  <div class="row">
                      <div class="col-sm-4">
                          <label for="input-status"
                                 class=" control-label main">Is Featured Product ?</label>
                      </div>
                      <div class="col-sm-8">
                          <label>
                          <input type="checkbox" name="featured" value="1" <?php if(1==$detail->featured){?>checked<?php }?>>
                          </label>
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
        'title': 'required minmax:5:50',
        'shortDescription':'required minmax:10:350',
        'metaTitle': 'required',
        'metaDescription': 'required',
        'metaKeyword': 'required',
        'tag': 'required',
        'brandId': 'select:default',
        <?php if($options): foreach($options as $opj): if($opj->required): if($opj->type=='checkbox' || $opj->type=='radio'):?>
        'options[<?=$opj->id?>][]': 'minoption:1 maxoption:1000',
        <?php endif;?>
        <?php /*if($opj->type=='dropdown'):?>
        'options[<?=$opj->id?>][]':'select:default',    
        <?php endif;*/?>
        <?php if($opj->type=='text' || $opj->type=='textarea'):?>
        'options[<?=$opj->id?>][]':'required',    
        <?php endif;?>    
        <?php endif; endforeach; endif;?>
        'qty': 'required',
        'minQty': 'required',
        //'mobileRearCamera': 'select:default',
        //'frontCamera': 'select:default',
        //'processorSpeed': 'required',
        //'processorCores': 'select:default',
        'img1': 'required extension:jpg:png',
        'bulkQty': 'select:default',
        'price': 'required'
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