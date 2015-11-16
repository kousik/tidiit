<?php foreach($categoryMenu As $k): //pre($k);?>
<div class="col-md-12">
    <a class="showInerCategoryData" href="javascript://" data-catdivid="<?php echo $k->categoryId;?>" data-isRoot="yes"><?php echo $k->categoryName;?></a>
    <?php if(property_exists($k, 'SubCategory')): $subCategoryArr=$k->SubCategory;
            foreach($subCategoryArr As $kk =>$vv): //pre($v);die;
                $step1ExitFor=false;?>
            <div class="col-md-12">
                <?php if(in_array($vv->categoryId, $userProductTypeArr)): $step1ExitFor=true;?>
                <input type="checkbox" style="height:auto;margin-right:5px;" class="required productTypeCategorySelection" value="<?php echo $vv->categoryId;?>" name="productTypeId[]" checked/>
                <?php else :?>
                <input type="checkbox" style="height:auto;margin-right:5px;" class="required productTypeCategorySelection" value="<?php echo $vv->categoryId;?>" name="productTypeId[]" />
                <?php endif;?>
                <a data-isroot="no" data-catdivid="<?php echo $vv->categoryId;?>" href="javascript://" class="showInerCategoryData"><?php echo $vv->categoryName;?></a>
                <?php if($step1ExitFor==FALSE):
                        if(property_exists($vv, 'SubCategory')): $subSubCategory=$vv->SubCategory;
                        foreach($subSubCategory AS $kkk => $vvv): $step2ExitFor=FALSE;?>
                        <div class="col-md-12">
                            <?php if(in_array($vvv->categoryId, $userProductTypeArr)): $step2ExitFor=true;?>
                            <input type="checkbox" style="height:auto;margin-right:5px;" class="required productTypeCategorySelection" value="<?php echo $vvv->categoryId;?>" name="productTypeId[]" checked/>
                            <?php else :?>
                            <input type="checkbox" style="height:auto;margin-right:5px;" class="required productTypeCategorySelection" value="<?php echo $vvv->categoryId;?>" name="productTypeId[]" />
                            <?php endif;?>
                            <a data-isroot="no" data-catdivid="<?php echo $vvv->categoryId;?>" href="javascript://" class="showInerCategoryData"><?php echo $vvv->categoryName;?></a>
                            <?php 
                                if($step2ExitFor==FALSE):
                                    if(property_exists($vvv, 'SubCategory')):
                                        foreach($vvv->SubCategory AS $j =>$l):$step3ExitFor=FALSE;?>
                                        <div class="col-md-12">
                                            <?php if(in_array($l->categoryId, $userProductTypeArr)): $step3ExitFor=true;?>
                                            <input type="checkbox" style="height:auto;margin-right:5px;" class="required productTypeCategorySelection" value="<?php echo $l->categoryId;?>" name="productTypeId[]" checked/>
                                            <?php else : ?>
                                            <input type="checkbox" style="height:auto;margin-right:5px;" class="required productTypeCategorySelection" value="<?php echo $l->categoryId;?>" name="productTypeId[]" />
                                            <?php endif;?>
                                            <a data-isroot="no" data-catdivid="<?php echo $l->categoryId;?>" href="javascript://" class="showInerCategoryData"><?php echo $l->categoryName;?></a>
                                        </div>
                            <?php       endforeach;
                                    endif;
                                else :
                                    $step2ExitFor=FALSE;
                                endif;
?>
                        </div>
                <?php   endforeach;
                    endif;
                else :
                    $step1ExitFor=FALSE;
                endif;
                    ?>
            </div>
    <?php   endforeach;
    endif; ?>
</div>
<div class="col-md-12" style="height: 10px;"></div>
<?php endforeach;?>
