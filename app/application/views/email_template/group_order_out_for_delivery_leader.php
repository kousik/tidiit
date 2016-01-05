<div bgcolor="#8d8e90">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#8d8e90">
    <tr>
      <td><table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="200"><a href= "<?php echo $MainSiteBaseURL;?>" target="_blank"><img src="<?php echo $MainSiteImagesURL;?>logo.png" title="Tidiit Inc Ltd" width="200px" height="100" border="0" alt=""/></a></td>
                  <td width="1"></td>
                  <td width="393"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td height="46" align="right" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="67%" align="right"><font style="font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:#68696a; font-size:8px; text-transform:uppercase"><a href= "#" style="color:#68696a; text-decoration:none"><strong></strong></a></font></td>
                              <td width="29%" align="right"><a href= "<?php echo $MainSiteBaseURL;?>" style="color:#68696a; text-decoration:none; text-transform:uppercase"><img src="<?php  echo $MainSiteImagesURL;?>android-ios.gif"  title="Download App" alt="" border="0" height="60"/></a> </td>
                              <td width="4%">&nbsp;</td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td height="30"><img src="<?php echo $MainSiteImagesURL;?>promo-green2_01_04.jpg" width="393" height="30" border="0" alt=""/></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="10%">&nbsp;</td>
                  <td width="80%" align="left" valign="top"><font style="font-family: Georgia, 'Times New Roman', Times, serif; color:#010101; font-size:14px"><strong><em>Hi <?php echo $leaderFullName;?>,</em></strong></font><br />
                    <br />
					<?php echo $buyerFullName?> had placed the Buying Club order <a style="text-decoration:underline;" target="_blank" href="#"><span style="color:#565656;font-size:13px;">TIDIIT-OD-<?php echo $orderId;?></span></a> under your Buying Club <strong><?php echo $orderInfo['group']->groupTitle?></strong>. <br />
                    
                    <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
					Master order no is <a style="text-decoration:underline;" target="_blank" href="#"><span style="color:#565656;font-size:13px;">TIDIIT-OD-<?php echo $orderDetails[0]->parrentOrderID;?></span></a><br />This is a maessage that order is ready to out for delivery. <br />
                    <br />
					Delivery Company Name : <?php echo $deliveryCompanyName;?><br />
					Delivery person name : <?php echo $deliveryStaffName;?><br />
					Contact number of delivery person : <?php echo $deliveryStaffContactNo;?><br >
					Email of delivery person : <?php echo $deliveryStaffEmail;?><br >
					<?php if($isPaid==0){?><br />
					As your member <strong><?php echo $buyerFullName?></strong> had order order the item with <strong>Settle on Delivery</strong> Method,<br /> please discussion with member(<strong><?php echo $buyerFullName?></strong>) to make payment from his/her my account so logistics people delivery your item in your door step.
					<?php }?>	</font> <br /></td>
                  <td width="10%">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3"><table class="ecxbody-wrapper" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;border-left:solid 1px #e6e6e6;border-right:solid 1px #e6e6e6;">
                      <tbody>
                        <tr>
                          <td align="left" valign="top" style="background-color:#FFFFFF;display:block;clear:both;padding:20px 20px 0 20px;" bgcolor=""><table border="0" cellspacing="0" cellpadding="0" width="100%" style="">
                              <tbody>
                                <tr>
                                  <td colspan="4" width="100%" align="middle" valign="top"><p style="padding:0;color:#565656;line-height:22px;font-size:13px;">Please find below, the summary of your order <a style="text-decoration:underline;" target="_blank" href="#"><span style="color:#565656;font-size:13px;">TIDIIT-OD-<?php echo $orderId;?></span></a> </p>
                                    <br>
                                  </td>
                                </tr>
                              </tbody>
                            </table></td>
                        </tr>
                      </tbody>
                    </table></td>
                </tr>
                <tr>
                  <td colspan="3"><table class="ecxbody-wrapper" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;border-left:solid 1px #e6e6e6;border-right:solid 1px #e6e6e6;">
                      <tbody>
                        <tr>
                          <td valign="top" align="center" class="col" width="350" style="background-color:#ffffff;"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                              <tbody>
                                <tr>
                                  <td width="40%" style="padding-left:20px;text-align:center;" valign="middle" align="center">
								  <a style="text-decoration:none;" target="_blank" href="#<?php echo $MainSiteBaseURL.'product/details/';?>">
								  <img border="0" src="<?php echo $SiteProductImageURL. $orderInfo['pimage']->image;?>">
								  </a> 
								  </td>
                                  <td width="60%" align="center" valign="top" style="padding:12px 15px 0 10px;vertical-align:top;min-width:100px;"><p style="padding:0;"> <a style="text-decoration:none;color:#565656;" target="_blank" href="#"> <span style="color:#565656;font-size:13px;"><?php echo $orderInfo['pdetail']->title;?></span> </a> </p></td>
                                </tr>
                              </tbody>
                            </table></td>
                          <td valign="top" align="center" class="col" width="250" style="background-color:#ffffff;"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                              <tbody>
                                <tr>
                                  <td width="33%" valign="top" align="center" style="padding:12px 10px 0 10px;text-align:center;"><p style="white-space:nowrap;padding:0;color:#848484;font-size:12px;">Item Price</p>
                                    <p style="white-space:nowrap;padding:7px 0 0 0;color:#565656;font-size:13px;"> Rs. <?php echo $orderInfo['priceinfo']->price/$orderInfo['priceinfo']->qty;?> </p></td>
                                  <td width="33%" valign="top" align="center" style="padding:12px 10px 0 10px;text-align:center;"><p style="padding:0;color:#848484;font-size:12px;">Quantity</p>
                                    <p style="padding:7px 0 0 0;color:#565656;font-size:13px;"> <?php echo $orderInfo['priceinfo']->qty;?> </p></td>
                                  <td width="33%" valign="top" align="center" style="padding:12px 20px 0 10px;text-align:center;"><p style="white-space:nowrap;padding:0;color:#848484;font-size:12px;">Subtotal </p>
                                    <p style="white-space:nowrap;padding:7px 0 0 0;color:#565656;font-size:13px;"> Rs. <?php echo $orderInfo['priceinfo']->price;?> </p></td>
                                </tr>
                              </tbody>
                            </table></td>
                        </tr>
                      </tbody>
                    </table></td>
                </tr>
				<tr>
                  <td width="10%">&nbsp;</td>
                  <td width="80%" align="left" valign="top">
				  	<font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:15px; line-height:21px">
						Item will delivered at bellow address
					</font>
                    <br />
                    <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
					<?php echo $orderInfo['shipping']->firstName.' '.$orderInfo['shipping']->firstName;?><br/>
                    <?php echo $orderInfo['shipping']->address.' , '.$orderInfo['shipping']->locality;?><br/>
                    <?php echo $orderInfo['shipping']->city.' , '.$orderInfo['shipping']->stateName;?><br/>
                    <?php echo $orderInfo['shipping']->zip.' , '.$orderInfo['shipping']->countryName;?><br/>
					<br />
                    </font> </td>
                  <td width="10%">&nbsp;</td>
                </tr>
                <tr>
                  <td width="10%">&nbsp;</td>
                  <td width="80%" align="left" valign="top"><br />
                    <br />
                    <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px"> Regards<br />
                    The Tidiit Team </font> </td>
                  <td width="10%">&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td align="right" valign="top"></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><img src="<?php echo $MainSiteImagesURL;?>promo-green2_07.jpg" width="598" height="7" style="display:block" border="0" alt=""/></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="13%" align="center">&nbsp;</td>
                  <td width="14%" align="center"><font style="font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:#010203; font-size:9px; text-transform:uppercase"><a href= "<?php echo $MainSiteBaseURL;?>" style="color:#010203; text-decoration:none"><strong>24x7 Customer Support </strong></a></font></td>
                  <td width="2%" align="center"><font style="font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:#010203; font-size:9px; text-transform:uppercase"><strong>|</strong></font></td>
                  <td width="9%" align="center"><font style="font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:#010203; font-size:9px; text-transform:uppercase"><a href= "<?php echo $MainSiteBaseURL;?>" style="color:#010203; text-decoration:none"><strong>Buyer Protection</strong></a></font></td>
                  <td width="2%" align="center"><font style="font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:#010203; font-size:9px; text-transform:uppercase"><strong>|</strong></font></td>
                  <td width="11%" align="center"><font style="font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:#010203; font-size:9px; text-transform:uppercase"><a href= "<?php echo $MainSiteBaseURL;?>" style="color:#010203; text-decoration:none"><strong>Flexible Payment Options</strong></a></font></td>
                  <td width="2%" align="center"><font style="font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:#010203; font-size:9px; text-transform:uppercase"><strong>|</strong></font></td>
                  <td width="17%" align="center"><font style="font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:#010203; font-size:9px; text-transform:uppercase"><a href= "<?php echo $MainSiteBaseURL;?>" style="color:#010203; text-decoration:none"><strong>STAY CONNECTED</strong></a></font></td>
                  <td width="4%" align="right"><a href="https://www.facebook.com/" target="_blank"><img src="<?php echo $MainSiteImagesURL;?>promo-green2_09_01.jpg" alt="facebook" width="22" height="19" border="0" /></a></td>
                  <td width="5%" align="center"><a href="https://twitter.com/" target="_blank"><img src="<?php echo $MainSiteImagesURL;?>promo-green2_09_02.jpg" alt="twitter" width="23" height="19" border="0" /></a></td>
                  <td width="4%" align="right"><a href="http://www.linkedin.com/" target="_blank"><img src="<?php echo $MainSiteImagesURL;?>promo-green2_09_03.jpg" alt="linkedin" width="20" height="19" border="0" /></a></td>
                  <td width="5%">&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center"><font style="font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:#231f20; font-size:8px"><strong>Head Office &amp; Registered Office | Tidiit Inc. Ltd, Adress Line, Company Street, City, State, Zip Code | Tel: 123 555 555 | <a href= "mailto:customercare@tidiit.com" style="color:#010203; text-decoration:none">customercare@tidiit.com</a></strong></font></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
  </table>
</div>
