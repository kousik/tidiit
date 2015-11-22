<?php $orderInfo=$single_order_success['TEMPLATE_ORDER_SUCCESS_ORDER_INFO'];?>
<div style="width:800px;">
    <div style="width:100%">
        <div style="width: 30%;text-align: left;float: left;"><img src="<?php echo SiteImagesURL;?>logo.png" title="Tidiit Inc Ltd" width="150px"></div>
        <div style="width: 70%;text-align: left;float: left;"><img src="<?php echo SiteImagesURL;?>android-ios.gif"></div>
    </div>
    <div style="clear: both;width:100%; height: 40px"></div>
    <div style="clear: both;width:100%;">
        Hi <?php echo $orderInfo['shipping']->firstName.' '.$orderInfo['shipping']->firstName;?>,<br/>
        Thank you for your order. <br />Your order will be processed soon. <br />
        You will receive another email confirming shipment of the items with all of the information you will need to track your order.<br />
        <br />
         Order Details :
    </div>
    <div style="clear: both;width:100%; height: 40px"></div>
    <div style="clear: both;width:100%;text-align: left;">
        <table style="text-align: left;border-collapse: collapse;width: 100%">
            <tr style="border: 1px solid #999;padding: 0.5rem;text-align: left;">
                <th style="border: 1px solid #999;padding: 0.5rem;text-align: left;width: 35%">Product Name</th>
                <th style="border: 1px solid #999;padding: 0.5rem;text-align: left;width: 25%">Image</th>
                <th style="border: 1px solid #999;padding: 0.5rem;text-align: left;width: 20%">Quantity</th>
                <th style="border: 1px solid #999;padding: 0.5rem;text-align: left;width: 20%">Price</th>
            </tr>
            <tr>
                <td><?php echo $orderInfo['pdetail']->title;?></td>
                <td><img src="<?php echo $single_order_success['ProductImageURLForMail']. $orderInfo['pimage']->image;?>"></td>
                <td><?php echo $orderInfo['priceinfo']->qty;?></td>
                <td><?php echo $orderInfo['priceinfo']->price;?></td>
            </tr>
            <tr>
                <td colspan="2" style="width: 60%">
                    <?php echo $orderInfo['shipping']->firstName.' '.$orderInfo['shipping']->firstName;?><br/>
                    <?php echo $orderInfo['shipping']->address.' , '.$orderInfo['shipping']->locality;?><br/>
                    <?php echo $orderInfo['shipping']->city.' , '.$orderInfo['shipping']->stateName;?><br/>
                    <?php echo $orderInfo['shipping']->zip.' , '.$orderInfo['shipping']->countryName;?><br/>
                </td>
                <td style="width: 20%"><b>Total</b></td>
                <td style="width: 20%"><b><?php echo $orderInfo['priceinfo']->price;?></b></td>
            </tr>
        </table>
    </div>
    <div style="clear: both;width:100%; height: 40px"></div>
    <div style="clear: both;width:100%;">
        <div style="float:left;width: 25%;"><img src="<?php echo SiteImagesURL;?>phone.gif"> +91 80 6770 1010</div>
        <div style="float:left;width: 25%;"><img src="<?php echo SiteImagesURL;?>mail.gif"><a href="mailto:customercare@tidiit.com">customercare@tidiit.com</a></div>
        <div style="float:left;width: 16%;"><img src="<?php echo SiteImagesURL;?>fb.gif"></div>
        <div style="float:left;width: 16%;"><img src="<?php echo SiteImagesURL;?>twitter.gif"></div>
        <div style="float:left;width: 18%;"><img src="<?php echo SiteImagesURL;?>gplus.gif"></div>
    </div>
    <div style="clear: both;width:100%; height: 40px"></div>
    <div style="clear: both;width:100%; text-align: center;">Copyright &copy; <?php echo date('Y');?> Tidiit.com All rights reserved.</div>
</div>