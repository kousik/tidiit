<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<?php
$orderinfo = unserialize(base64_decode($order->orderInfo));

$ws = unserialize($order->setWarehouse);
$ps = unserialize($order->productsSerial);
//print_r($ws);
//print_r($order);
//print_r($orderinfo);
$disc = isset($order->discountAmount)?$order->discountAmount:0;
?>
<div style="font-size:12px; font-family:arial, verdana, sans-serif;">

    <table style="width:100%; font-size:13px;min-height: 50px;" cellpadding="5" cellspacing="0">
        <tbody>
        <tr>
            <td style="width: 20%;">
                <img src="resources/images/logo.png" alt="">
            </td>
            <td style="width: 80%;">
                <table border="0" style="width:100%;" cellpadding="5" cellspacing="5">
                    <tbody>
                    <tr>
                        <td style="width: 50%;">
                            <p style="float: left;font-size: 8px;">Contact us: <?=$config['customercontactnumber']?> || <?=$config['CustomerCareEmail']?></p>
                        </td>
                        <td style="width: 50%;">
                            <p style="float: right; border: 1px dashed #000;width:170px;font-size: 10px;margin: 3px;">Tax Invoice # <?=$ws['taxInvoice']?></p>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="left" valign="middle">
                            <span  style="font-size: 14px; font-weight: bold;width:40%;
"><?=$ws['companyName']?></span>
                            <span style="font-size: 10px;width:60%;"> Warehouse Address: <?=$ws['address1']?>, <?=$ws['address2']?>, <?=$ws['city']?>, <?=$ws['stateName']?>, <?=$ws['countryName']?> - <?=$ws['zip']?></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>




    <table style="border:1px solid #000; width:100%; font-size:13px;" cellpadding="5" cellspacing="0">
        <tr>
            <td style="width:40%; vertical-align:middle; line-height: 17px; text-align: left;">
                <strong>Order ID: </strong>TIDIIT-OD-<?php echo $order->orderId;?><br>
                <strong>Order Date: </strong><?php echo date("Y-m-d", strtotime($order->orderDate));?><br>
                <strong>Invoice Date: </strong><?php echo date("Y-m-d", strtotime($order->orderDate));?><br>
                <strong>VAT/TIN: </strong><?=$ws['vatNumber']?><br>
                <strong>Service tax #: </strong><?=$config['servicetax']?><br>
            </td>
            <td style="width:40%; vertical-align:top;">
                <strong>Shipping Address</strong><br/>
                <strong><?=$orderinfo['shipping']->firstName?> <?=$orderinfo['shipping']->lastName?></strong><br>
                <small><?=$orderinfo['shipping']->contactNo?> | <?=$order->buyerEmail?><br>
                    <?=$orderinfo['shipping']->locality?><br>
                    <?=$orderinfo['shipping']->city?>, <?=$orderinfo['shipping']->stateName?> <?=$orderinfo['shipping']->zip?><br>
                    <?=$orderinfo['shipping']->countryName?>
                </small><br>
                <strong>Land mark :</strong><?=$orderinfo['shipping']->landmark?>
            </td>
            <td style="width:20%; vertical-align:middle;" class="packing">
                *Keep this invoice and manufacturer box for warranty purposes.
            <br/>
            </td>
        </tr>
    </table>



    <table border="1" style="width:100%; margin-top:10px; border-color:#000; font-size:13px; border-collapse:collapse;" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th width="15%" class="packing">Product</th>
                <th width="40%" class="packing">Title</th>
                <th width="5%" class="packing" align="middle" >Qty</th>
                <th width="10%" class="packing" align="middle" >Price</th>
                <th width="10%" class="packing" align="middle" >Tax</th>
                <th width="10%" class="packing"  align="middle">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$orderinfo['pdetail']->brandTitle?></td>
                <td>
                    <strong><?=$orderinfo['pdetail']->title?></strong> <br>
                    1. [IMEI/Serial No: <?=implode(" , ",$ps)?> ]
                </td>
                <td align="middle"><?=$order->productQty?></td>
                <td align="middle"><?=$order->subTotalAmount-$disc?></td>
                <td align="middle"><?=$order->taxAmount?></td>
                <td align="middle"><?=$order->orderAmount?></td>
            </tr>
            <tr>
                <td align="right" colspan="2"><strong style="font-size: 18px;">Total</strong></td>
                <td align="middle"><?=$order->productQty?></td>
                <td align="middle"><?=$order->subTotalAmount-$disc?></td>
                <td align="middle"><?=$order->taxAmount?></td>
                <td align="middle"><?=$order->orderAmount?></td>
            </tr>
            <tr>
                <td align="right" colspan="5"><strong style="font-size: 22px;">Grand Total</strong></td>

                <td align="middle"><strong style="font-size: 18px;">&#8377; <?=$order->orderAmount?></strong></td>
            </tr>
            <tr>
                <td align="middle" colspan="6" style="font-style: italic; font-size: 11px;">This is a computer generated invoice. No signature required.</td>
            </tr>
        </tbody>
    </table>


    <table border="0" style="width:100%; margin-top:10px; font-size:13px; border-collapse:collapse;margin-top: 200px;" cellpadding="5" cellspacing="0">
        <tbody>
        <tr>
            <td align="right"><img src="resources/images/logo.png" alt="" style="width: 65px;"><br>
            <p style="font-size: 16px;">Thank You!<br><span style="font-size: 11px;">for shopping with us</span></p></td>
        </tr>
        </tbody>
    </table>

    <table border="1" style="width:100%; margin-top:10px; border-color:#000; font-size:13px; border-collapse:collapse;" cellpadding="5" cellspacing="0">
        <tbody>
        <tr>
            <td style="width: 100%;">
                <strong>Returns Policy:</strong> At Tidiit we try to deliver perfectly each and every time. But in the off-chance that you need to return the item, please do so with the <strong>original Brand
                box/price tag, original packing and invoice</strong> without which it will be really difficult for us to act on your request. Please help us in helping you. Terms and conditions apply.<br><br>
                <p style="font-size: 10px; font-style: italic;">The goods sold as part of this shipment are intended for end user consumption / retail sale and not for re-sale.
                Regd. office: <?=$ws['companyName']?>, <?=$ws['address1']?>, <?=$ws['address2']?>, <?=$ws['city']?>, <?=$ws['stateName']?>, <?=$ws['countryName']?> - <?=$ws['zip']?></p>
            </td>
        </tr>
        </tbody>
    </table>




</div>
</body>
</html>
<?php //die;?>