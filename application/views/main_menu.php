<ul class="categrs">
    <?php foreach($categoryMenu As $k): //pre($k);?>
    <li>
        <a href="javascript:void(0);"><?php echo my_seo_freindly_url($k->categoryName);?> <span>&nbsp;</span></a>
        <?php if(property_exists($k, 'SubCategory')): $subCategoryArr=$k->SubCategory;?>
        <ul class="sub_catgrs">
            <?php 
            foreach($subCategoryArr As $kk =>$vv): //pre($v);die;?>
            <li>
                <a href="<?php echo BASE_URL.'category/details/'.my_seo_freindly_url($vv->categoryName).'/'.  base64_encode($vv->categoryId).'~'.md5('tidiit');?>"><?php echo $vv->categoryName;?> &ensp;<i class="fa fa-angle-right dsktp"></i><i class="fa fa-angle-down mobl_vrsn"></i></a>
                <?php if(property_exists($vv, 'SubCategory')): $subSubCategory=$vv->SubCategory;?>
                <ul class="sub_sub_ctgrs">
                    <?php foreach($subSubCategory AS $kkk => $vvv):?>
                    <li>
                        <a href="<?php echo BASE_URL.'category/details/'.my_seo_freindly_url($vvv->categoryName).'/'.  base64_encode($vvv->categoryId).'~'.md5('tidiit');?>"><?php echo $vvv->categoryName;?>&nbsp;<i class="fa fa-angle-down mobl_vrsn"></i></a>
                        <?php if(property_exists($vvv, 'SubCategory')):?>
                        <ul class="sub_sub_ctgrs">
                            <?php foreach($vvv->SubCategory AS $j =>$l):?>
                            <li><a href="<?php echo BASE_URL.'category/details/'.my_seo_freindly_url($l->categoryName).'/'.  base64_encode($l->categoryId).'~'.md5('tidiit');?>"><?php echo $l->categoryName;?></a></li>
                            <?php endforeach;?>
                        </ul>
                        <?php endif;?>
                    </li>
                    <?php endforeach;?>
                </ul>
                <?php endif;?>
            </li>
            <?php endforeach;?>
        </ul>
        <?php endif; ?>
    </li>
    <?php endforeach; //die;?> 
    
</ul>