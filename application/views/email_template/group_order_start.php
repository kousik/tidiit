<div style="width:800px;">
    <div style="width:100%">
        <div style="width: 30%;text-align: left;float: left;"><img src="http://tidiit.com/resources/images/logo.png" title="Tidiit Inc Ltd" width="150px"></div>
        <div style="width: 70%;text-align: left;float: left;"><h1>New Group order running by <b><?php echo $group_order_start['TEMPLATE_GROUP_ORDER_START_TITLE'];?></b></h1></div>
    </div>
    <div style="clear: both;width:100%; height: 40px"></div>
    <div style="clear: both;width:100%;text-align: left;">
        <table style="text-align: left;">
            <tr><td>Hi, <br /><br /> You have requested to buy group order product.</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Product is <a href='#'><?php echo $group_order_start['TEMPLATE_GROUP_ORDER_START_PRODUCT_TITLE'];?></a></td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Want to process the order ?</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td><a href='<?php echo BASE_URL;?>shopping/group-order-decline/<?php echo base64_encode($group_order_start['TEMPLATE_GROUP_ORDER_START_ORDERID']*226201);?>'>Decline the Group Order</a></td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td><a href='<?php echo BASE_URL;?>shopping/group-order-accept-process/<?php echo base64_encode($group_order_start['TEMPLATE_GROUP_ORDER_START_ORDERID']*226201);?>'>Accept the Group Order</a></td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>Thanks <br> Tidiit Team.</td></tr>
        </table>
    </div>
    <div style="clear: both;width:100%; height: 40px"></div>
    <div style="clear: both;width:100%; text-align: center;">Copyright &copy; <?php echo date('Y');?> Tidiit.com All rights reserved.</div>
</div>