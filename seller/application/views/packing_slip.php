<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<?php
$orderinfo = unserialize(base64_decode($order->orderInfo));
?>
<div style="font-size:12px; font-family:arial, verdana, sans-serif;">

    <h2>TIDIIT-OD-<?php echo $order->orderId;?></h2>

    <table style="border:1px solid #000; width:100%; font-size:13px;" cellpadding="5" cellspacing="0">
        <tr>
            <td style="width:40%; vertical-align:top;">
                <strong>Shipping / Customer Address</strong><br/>
                <strong><?=$orderinfo['shipping']->firstName?> <?=$orderinfo['shipping']->lastName?></strong><br>
                <small><?=$orderinfo['shipping']->contactNo?> | <?=$order->buyerEmail?><br>
                    <?=$orderinfo['shipping']->locality?><br>
                    <?=$orderinfo['shipping']->city?>, <?=$orderinfo['shipping']->stateName?> <?=$orderinfo['shipping']->zip?><br>
                    <?=$orderinfo['shipping']->countryName?>
                </small><br>
                <strong>Land mark :</strong><?=$orderinfo['shipping']->landmark?>
            </td>
            <td style="width:40%; vertical-align:top;" class="packing">
                <img src="<?=$this->config->item('main_site')?>resources/qr_code/<?=$order->qrCodeImageFile?>">
            <br/>
            </td>
        </tr>

        <tr>
            <td style="border-top:1px solid #000;">
                <strong>Payment Method</strong>
                <div><?=$order->paymentType?> Payment</div>
            </td>
            <td style="border-top:1px solid #000;">
                <strong>Shipping</strong>
                <div>Flat Rate</div>
            </td>
        </tr>

        <tr>
            <td style="border-top:1px solid #000;">
                Sold By :
            </td>
            <td style="border-top:1px solid #000;">
            </td>
        </tr>

    </table>

    <table border="1" style="width:100%; margin-top:10px; border-color:#000; font-size:13px; border-collapse:collapse;" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th width="5%" class="packing">
                    Qty
                </th>
                <th width="20%" class="packing">
                    Name
                </th>
                <th class="packing" >
                    Description
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:center; font-size:20px; font-weight:bold;"><?php echo $order->productQty;?></td>
                <td>
                    <strong><?=$orderinfo['pdetail']->title?></strong> <br>
                </td>
                <td>
                    <div class="orderItemOption">
                        <span><strong>Model :</strong></span> &nbsp;&nbsp;<?=$orderinfo['pdetail']->model?>
                    </div>
                    <div class="orderItemOption">
                        <span><strong>Brand :</strong></span> &nbsp;&nbsp;<?=$orderinfo['pdetail']->brandTitle?>
                    </div>
                    <div class="orderItemOption">
                        <span><strong>Description :</strong></span>
                    </div>
                    <span style="padding-left:20px;"> &bull; &nbsp;&nbsp;<?=$orderinfo['pdetail']->shortDescription?></span><br>
                </td>
            </tr>
        </tbody>
    </table>

    <table border="1" style="width:100%; margin-top:10px; border-color:#000; font-size:13px; border-collapse:collapse;" cellpadding="5" cellspacing="0">
        <tbody>
        <tr>
            <td style="width: 30%;"></td>
            <td style="width: 30%;"></td>
            <td style="width: 40%;"><span>The goods sold are intended for end user consumtion. <br>
            <strong>For whole sale only</strong></span></td>
        </tr>
        </tbody>
    </table>
    <table border="1" style="width:100%; margin-top:10px; border-color:#000; font-size:13px; border-collapse:collapse;" cellpadding="5" cellspacing="0">
        <tbody>
        <tr>
            <td style="width: 100%;">Ordered Through &nbsp;&nbsp;<span style="font-size: 16px; font-weight: bold;">Tidiit</span>&nbsp;&nbsp;<img src="<?php echo SiteImagesURL;?>logo.png" alt="" style="width: 65px;"></td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
<?php die;?>