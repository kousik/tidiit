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
                  <td width="80%" align="left" valign="top"><font style="font-family: Georgia, 'Times New Roman', Times, serif; color:#010101; font-size:14px"><strong><em>Hi <?php echo $supportFullName;?>,</em></strong></font><br />
                    <br />
                    <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
						This is a pre-alert that Buying Club order <a style="text-decoration:underline;" target="_blank" href="#"><span style="color:#565656;font-size:13px;">TIDIIT-OD-<?php echo $orderDetails[0]->orderId;?></span></a> is ready to <strong>Out For Delivery</strong> for <?php echo $buyerFullName;?>
						<br /><br /><strong>Delivery Person Details as below.</strong>
                    <br />
					Delivery Company Name : <?php echo $deliveryCompanyName;?><br />
					Delivery person name : <?php echo $deliveryStaffName;?><br />
					Contact number of delivery person : <?php echo $deliveryStaffContactNo;?><br />
					Email of delivery person : <?php echo $deliveryStaffEmail;?><br /><br />
						<?php if($orderDetails[0]->parrentOrderID>0){?><br />
						Master order number : <a style="text-decoration:underline;" target="_blank" href="#"><span style="color:#565656;font-size:13px;">TIDIIT-OD-<?php echo $orderDetails[0]->parrentOrderID;?></span></a>
						<?php }?>
						<?php if($isPaid==0){?><br />
						<?php echo $buyerFullName;?> has placed the above order by <strong>Settlement on delivery</strong> method.<br />Follow up with the <strong><?php echo $buyerFullName; ?></strong> <?php if($orderDetails[0]->parrentOrderID>0){ ?> and <strong><?php echo $leaderFullName;?></strong><?php }?> regarding payment,So logistics people will delivered item at door step.
					<?php }?><br />
						</p>
                    <br />
                    Selected product slab price is <?php echo $orderInfoDataArr['priceinfo']->price;?> and quantity <?php echo $orderInfoDataArr['priceinfo']->qty;?></font> <br /></td>
                  <td width="10%">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3"><table class="ecxbody-wrapper" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px;border-left:solid 1px #e6e6e6;border-right:solid 1px #e6e6e6;">
                      <tbody>
                        <tr>
                          <td align="left" valign="top" style="background-color:#FFFFFF;display:block;clear:both;padding:20px 20px 0 20px;" bgcolor=""><table border="0" cellspacing="0" cellpadding="0" width="100%" style="">
                              <tbody>
                                <tr>
                                    <td width="10%" align="middle" valign="top">&nbsp;</td>
									<td width="80%" align="middle" valign="top">
										<font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:15px; line-height:21px;font-weight:bold;">
											Shipping Address
										</font>
										<br />
										<font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px;">
										<?php echo $orderInfoDataArr['shipping']->firstName.' '.$orderInfoDataArr['shipping']->firstName;?><br/>
										<?php echo $orderInfoDataArr['shipping']->address.' , '.$orderInfoDataArr['shipping']->locality;?><br/>
										<?php echo $orderInfoDataArr['shipping']->city.' , '.$orderInfoDataArr['shipping']->stateName;?><br/>
										<?php echo $orderInfoDataArr['shipping']->zip.' , '.$orderInfoDataArr['shipping']->countryName;?><br/>
										<br />
										</font> 
									</td>
                                  <td width="10%" align="middle" valign="top">&nbsp;</td>
									<?php /*<td width="48%" align="middle" valign="top">
										<font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:15px; line-height:21px;font-weight:bold;">
											Billing Address
										</font>
										<br />
										<font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px;">
										<?php echo $orderInfoDataArr['billing']->firstName.' '.$orderInfoDataArr['billing']->firstName;?><br/>
										<?php echo $orderInfoDataArr['billing']->email;?><br/>
										<?php echo $orderInfoDataArr['billing']->address.' , '.$orderInfoDataArr['billing']->locality;?><br/>
										<?php echo $orderInfoDataArr['billing']->city.' , '.$orderInfoDataArr['billing']->stateName;?><br/>
										<?php echo $orderInfoDataArr['billing']->zip.' , '.$orderInfoDataArr['billing']->countryName;?><br/>
										<br />
										</font> 

									</td>*/?>
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
                          <td valign="top" align="center" class="col" width="250" style="background-color:#ffffff;"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                              <tbody>
                                <tr>
                                  <td width="40%" style="padding-left:20px;text-align:center;" valign="middle" align="center">
								  <a style="text-decoration:none;" target="_blank" href="#<?php echo $MainSiteBaseURL.'product/details/';?>">
								  <img border="0" src="<?php echo $SiteProductImageURL. $orderInfoDataArr['pimage']->image;?>">
								  </a> 
								  </td>
                                  <td width="60%" align="center" valign="top" style="padding:12px 15px 0 10px;vertical-align:top;min-width:100px;"><p style="padding:0;"> <a style="text-decoration:none;color:#565656;" target="_blank" href="#"> <span style="color:#565656;font-size:13px;"><?php echo $orderInfoDataArr['pdetail']->title;?></span> </a> </p></td>
                                </tr>
                              </tbody>
                            </table></td>
                          <td valign="top" align="center" class="col" width="350" style="background-color:#ffffff;"><table border="0" cellspacing="0" cellpadding="0" width="100%">
                              <tbody>
                                <tr>
                                  <td width="33%" valign="top" align="center" style="padding:12px 10px 0 10px;text-align:center;"><p style="white-space:nowrap;padding:0;color:#848484;font-size:12px;">Item Price</p>
                                    <p style="white-space:nowrap;padding:7px 0 0 0;color:#565656;font-size:13px;"> Rs. <?php echo $orderDetails[0]->orderAmount/$orderDetails[0]->productQty;?> </p></td>
                                  <td width=33%" valign="top" align="center" style="padding:12px 10px 0 10px;text-align:center;"><p style="padding:0;color:#848484;font-size:12px;">Quantity</p>
                                    <p style="padding:7px 0 0 0;color:#565656;font-size:13px;"> <?php echo $orderDetails[0]->productQty;?> </p></td>
                                  <td width="33%" valign="top" align="center" style="padding:12px 20px 0 10px;text-align:center;"><p style="white-space:nowrap;padding:0;color:#848484;font-size:12px;">Subtotal </p>
                                    <p style="white-space:nowrap;padding:7px 0 0 0;color:#565656;font-size:13px;"> Rs. <?php echo $orderDetails[0]->orderAmount;?> </p></td>
                                </tr>
                              </tbody>
                            </table></td>
                        </tr>
                      </tbody>
                    </table></td>
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
                  <!--<td width="2%" align="center"><font style="font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:#010203; font-size:9px; text-transform:uppercase"><strong>|</strong></font></td>
<td width="10%" align="center"><font style="font-family:'Myriad Pro', Helvetica, Arial, sans-serif; color:#010203; font-size:9px; text-transform:uppercase"><a href= "http://yourlink" style="color:#010203; text-decoration:none"><strong>PRESS </strong></a></font></td>-->
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
