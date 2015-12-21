<div class="col-md-3 pad_rit_none">
    <div class="lft_nav_btn dashbrd_bg">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs dashbrd_tab" role="tablist">
          <li <?php if($userMenuActive==1){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'myaccount';?>" >General Information <span><i class="fa  fa-dashboard"></i></span></a></li>
          <li <?php if($userMenuActive==2){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-shipping-address';?>">Shipping Address <span><i class="fa  fa-truck"></i></span></a> </li>
          <?php /*<li <?php if($userMenuActive==3){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-billing-address';?>">Billing Address <span><i class="fa  fa-th-list"></i></span></a></li>*/?>
          <li <?php if($userMenuActive==4){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-orders';?>">Orders <span><i class="fa  fa-reorder"></i></span></a></li>
          <li <?php if($userMenuActive==5){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-groups';?>">Buyer Clubs <span><i class="fa  fa-group"></i></span></a></li>
          <?php if($userMenuActive==7){?>
          <li <?php if($userMenuActive==7){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-groups-orders';?>">Buyer Clubs Order<span><i class="fa fa-line-chart"></i></span></a></li>
          <?php }?>
          <li <?php if($userMenuActive==6){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-finance-info';?>">Finance Info <span><i class="fa  fa-credit-card"></i></span></a></li>
          <li <?php if($userMenuActive==8){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-profile';?>">Update Profile <span><i class="fa  fa-user"></i></span></a></li>
          <li <?php if($userMenuActive==9){?>class="active"<?php }?>><a href="<?php echo BASE_URL.'my-notifications';?>">Notifications <span class="pull-right"><i class="fa  fa-bell"></i></span> <span class="label label-danger label-as-badge js-notfy-auto-update" style="border-radius: 1.25em;"><?=$tot_notfy?></span> </a></li>
        </ul>
    </div>
</div>