<div class="col-md-3 pad_rit_none">
    <div class="lft_nav_btn dashbrd_bg">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs dashbrd_tab" role="tablist">
          <li <?php if($userMenuActive==1){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'myaccount';?>" >General Information <img src="<?php echo SiteImagesURL;?>genrl_infrmtn.png" /></a></li>
          <li <?php if($userMenuActive==2){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-shipping-address';?>">Shipping Address <img src="<?php echo SiteImagesURL;?>media.png" /></a> </li>
          <li <?php if($userMenuActive==3){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-billing-address';?>">Billing Address <img src="<?php echo SiteImagesURL;?>promtrs.png" /></a></li>
          <li <?php if($userMenuActive==4){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-orders';?>">Orders <img src="<?php echo SiteImagesURL;?>tickts.png" /></a></li>
          <li <?php if($userMenuActive==5){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-groups';?>">Groups <img src="<?php echo SiteImagesURL;?>evnts.png" /></a></li>
          <li <?php if($userMenuActive==6){?>class="active"<?php }?>><a href="#">Financ Info <img src="<?php echo SiteImagesURL;?>state.png" /></a></li>
        </ul>
    </div>
</div>