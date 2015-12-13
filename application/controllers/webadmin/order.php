<?php
class Order extends MY_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('Product_model');
		$this->load->model('Cart_model');
		$this->load->model('Category_model');
		$this->load->model('User_model');
		$this->load->model('Order_model');
		
	}
	
	public function index(){
		redirect(base_url().'admin');
	}
	
	public function viewlist(){
		$data=$this->_show_admin_logedin_layout();
                
                $per_page=500;
                $PaginationConfig=array(
                    'base_url'=>base_url() . "webadmin/order/viewlist",
                    'total_rows'=>$this->Order_model->admin_list_total(),
                    'per_page'=>$per_page,
                    'num_links'=>15,
                    'uri_segment'=>4
                    );
                $this->_my_pagination($PaginationConfig);
                
                $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
                //echo 'KK  '.$config['per_page'];die;
                if($page==0){
                    $offcet=0;
                }else{
                    $offcet=$per_page*($page-1);
                }
                
		$data['OrderStateDataArr']=$this->Order_model->get_state();
                $dataArr=$this->Order_model->admin_list($per_page,$offcet);
                //pre($dataArr);die;
		$data['DataArr']=$dataArr;
		$data['$OrderStateDataArr']=$this->Order_model->get_state();
                $data["links"] = $this->pagination->create_links();
		$this->load->view('webadmin/order_list',$data);
	}
        
        public function viewUncompleteList(){
		$data=$this->_show_admin_logedin_layout();
                
                $per_page=20;
                $PaginationConfig=array(
                    'base_url'=>base_url() . "admin/order/viewUncompleteList",
                    'total_rows'=>$this->Order_model->get_admin_uncomplete_total(),
                    'per_page'=>$per_page,
                    'num_links'=>15,
                    'uri_segment'=>4
                    );
                $this->_my_pagination($PaginationConfig);
                
                $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
                //echo 'KK  '.$config['per_page'];die;
                if($page==0){
                    $offcet=0;
                }else{
                    $offcet=$per_page*($page-1);
                }
                
		$data['DataArr']=$this->Order_model->admin_uncomplete_list($per_page,$offcet);
                $data["links"] = $this->pagination->create_links();
		$this->load->view('admin/order_list1',$data);
	}
	
	public function update_state(){
		$OrderStateID=$this->input->post('OrderStateID',TRUE);
		$ShortMessage=$this->input->post('ShortMessage',TRUE);
		$TrackingURL=$this->input->post('TrackingURL',TRUE);
		$OrderID=$this->input->post('OrderID',TRUE);
		//echo 'kkk';die;
                
		if($OrderID>0){
                        $HistoryDataArr=array();
                        $HistoryDataArr[]=array('OrderID'=>$OrderID,'State'=>$OrderStateID);
                        $this->Order_model->add_history($HistoryDataArr);
                        //pre('rrr');die;
			$this->Order_model->change_state($OrderID,$OrderStateID);
			$OrderDetailsForMail=$this->Order_model->details($OrderID);
                        
			$body_ex='';
			if($OrderStateID==3){
				if($TrackingURL==""){
					$this->session->set_flashdata('Message','Invalid tracking url provided.');
					redirect(base_url().'admin/order/viewlist');
				}
			}
			$OrderDetails=$this->Order_model->details($OrderID);
			$this->load->library('email');
			
                        $SupportEmail=$this->Siteconfig_model->get_value_by_name('SupportEmail');
                        
			$this->email->from($SupportEmail, 'Daily Plaza Support');
			$this->email->to($OrderDetails[0]->Email,$OrderDetails[0]->FirstName.' '.$OrderDetails[0]->LastName);
			
			//$this->email->cc('another@another-example.com');
			//$this->email->bcc('them@their-example.com');

			$this->email->subject('Your order status change information from Daily Plaza.');
			
                        echo '$OrderStateID = '.$OrderStateID;die;
                        
			if($OrderStateID==2){
				$msg=$this->order_process_email_body($OrderID);
			}else if($OrderStateID==3){
				$msg=$this->order_shipped_email_body($OrderID,$TrackingURL);
				$DataArr=$this->Order_model->get_cart_details_by_order($OrderID);
				$BatchDataArr=array();
				foreach($DataArr As $k){
					$TempDataArr=array('OrderID'=>$OrderID,'UserID'=>$k->UserID,'TrackingURL'=>$TrackingURL,'ProductID'=>$k->ProductID,'Qty'=>$k->Qty);
					//print_r($TempDataArr);die;
					$BatchDataArr[]=$TempDataArr;
				}
				//print_r($BatchDataArr);die;
				$this->Order_model->add_shipped_history($BatchDataArr);
			}elseif($OrderStateID=4){
				$msg=$this->order_complete_email_body($OrderID,$OrderDetails[0]->FirstName,$OrderDetails[0]->LastName);
			}
			
			//$msg .= $body_ex;
			//$msg .= 'If you need any more assistance please fill free to put email by customercare@dailyplaza.com.<br>';
			//$msg .= '<br><br>Thanks,<br>Daily Plaza Team';
			$this->email->message($msg);

			$this->email->send();
			
			
			
			////message to admin.
			$this->load->model('Siteconfig_model');
			$AdminEmail=$this->Siteconfig_model->get_value_by_name('AdminMail');
			
			$OrderDetailsForMail=$this->Order_model->details($OrderID);
			
			$this->email->from('no-reply@daily-plaza.com', 'Daily Plaza Administrator');
			$this->email->to($AdminEmail,'Admin Email');
			
			//$this->email->cc('another@another-example.com');
			//$this->email->bcc('them@their-example.com');

			$this->email->subject('Order status change notification.');
			$msg='Hi ,<br><br>Order of Invoice No : '.$OrderDetailsForMail[0]->InvoiceNo.', vide Order Amount : '.$OrderDetailsForMail[0]->OrderAmount.'.Order Id is  : '.$OrderDetailsForMail[0]->OrderID.'. has been changed to '.$OrderDetailsForMail[0]->OrderStateName.'.<br> ';
			$msg .= '<br><br>Thanks,<br>Daily Plaza Team';
			$this->email->message($msg);

			$this->email->send();
			$this->session->set_flashdata('Message','Order status change successfully.');
		}else{
			$this->session->set_flashdata('Message','Invalid Order Index for status change,Please try again.');
		}
		redirect(base_url().'admin/order/viewlist');
	}
	
	public function order_process_email_body($OrderID){
		$ImgURL=SiteImagesURL;
		$ShippingDetails=$this->Order_model->get_shipping_address_by_order_id($OrderID);
		$body='<div style="width: 760px; height:auto; float: none; margin: 0 auto; padding: 10px; overflow: hidden; background: #ebebeb;">
<div style="width: 700px; float: none; margin: 0 auto; height: 150px;">
<div style="width: 260px; float: left; margin: 30px auto; height: auto;"><img src="'.$ImgURL.'logo.png" alt="" width="256" height="88" border="0;" /></div>
<div style="float: right; margin: 70px auto 0 auto; width: 150px; color: #444; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:24px; font-size: 21px; font-weight: normal;">Order Processing</div>
</div>
<div style="clear: both;"></div>
<div style="width: 700px; float: none; margin: 0 auto; height: auto; background: #FFFFFF; overflow: hidden; padding: 10px;">
<div style="width: 700px; float: left; margin: 0 auto; height: auto; border-bottom: 1px solid #CCCCCC; color: #444; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; padding-bottom: 10px; font-weight: normal;">Greetings from DailyPlaza.com. </div>
<div style="clear: both;"></div>
<div style="float: left; margin: 20px auto 0 auto; width: 700px; color: #444; border-bottom: 1px solid #CCCCCC; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; font-weight: normal;">
We thought you\'d like to know that we are precessing your items of Order No #<b>'.$OrderID.'</b>. Some of the items ordered may be shipped separately. You will get emails for the status of each of your product as we process your order.<br/><br/><br/>

</div>

<div style="clear:both;"></div>
<div style="width: 760px; height:auto; float: none; margin: 0 auto;">
<div style="color: #444; width: 700px;  float: left; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 10px 0; padding: 9px 10px 9ps 0; font-weight: normal;">
 This shipment will sent to:<br/>

<span style="margin-left: 200px;">'.$ShippingDetails[0]->ShippToName.'</span><br/>

<span style="margin-left: 200px;">'.$ShippingDetails[0]->Address.'</span><br/>

<span style="margin-left: 200px;">'.$ShippingDetails[0]->City.'</span><br/>

<span style="margin-left: 200px;">'.$ShippingDetails[0]->StateName.'</span><br/>

<span style="margin-left: 200px;">'.$ShippingDetails[0]->CountryName.','.$ShippingDetails[0]->Zip.'</span><br/><br/>

Note:<br/>
* Once an order has been shipped we are unable stop the delivery.<br/>
* For faster service when emailing us please include your name, address and order # used during the purchase.<br/><br/>

If you\'ve explored the links on the Your Account page but still need assistance with your order, you\'ll find links to e-mail or call customer service.
<br/><br/>

Please be aware that items in this order may be subject to California\'s Electronic Waste Recycling Act. If any items in this order are subject to that Act, the seller of that item has elected to pay any fees due on your behalf.<br/><br/>

Please note: This e-mail was sent from a notification-only address that cannot accept incoming e-mail. Please do not reply to this message.<br/><br/><br/>


Thank you for shopping with us.<br/><br/><a href="#">www.dailyplaza.com</a><br/><br/>
</div>
</div>
</div>
<div style="clear:both;"></div>
<div style="width: 760px; height:auto; float: none; margin: 0 auto; padding: 10px; overflow: hidden; background: #ebebeb;">
<div style="color: #979797; width: 400px;  float: left; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 10px 0; padding: 9px 0px; font-weight: normal;">&copy '.date('Y').' dailyplaza. All rights reserved.</div>
<div style="color: #444; width: 180px; float: right; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 11px; margin: 10px 0; padding: 9px 0px; font-weight: normal;">   <a href="'.BASE_URL.'privacy-policies" style="padding: 0 8px;"> Privacy Policy</a>    |    <a href="'.BASE_URL.'faq" style="padding: 0 8px;">FAQs</a></div>
</div>';
	return $body;
	}
	
	public function order_complete_email_body($OrderID,$FirstName,$LastName){
		$ImgURL=SiteImagesURL;
		
		$body='<div style="width: 760px; height:auto; float: none; margin: 0 auto; padding: 10px; overflow: hidden; background: #ebebeb;">
<div style="width: 700px; float: none; margin: 0 auto; height: 150px;">
<div style="width: 260px; float: left; margin: 30px auto; height: auto;"><img src="'.$ImgURL.'logo.png" alt="" width="256" height="88" border="0;" /></div>
<div style="float: right; margin: 70px auto 0 auto; width: 195px; color: #797979; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:24px; font-size: 18px; font-weight: bold;">  Order Completed

</div>
</div>
<div style="clear: both;"></div>
<div style="width: 700px; float: none; margin: 0 auto; height: auto; background: #FFFFFF; overflow: hidden; padding: 10px; color: #444; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px;  font-weight: normal;">


Dear '.$FirstName.' '.$LastName.',<br/><br/>

Your order ('.$OrderID.')  has been completed. For improving our services and for making us serve you better, we would like to have a feedback from you.This will only take less than 5 minutes of your time. To do so please click the link <a href="'.BASE_URL.'post-your-review/'.$OrderID.'">'.BASE_URL.'post-your-review/'.$OrderID.'</a><br/><br/><br/>

Some of the items ordered may be shipped separately. You will get emails for the status of each of your product as we process your order.<br/><br/>

Thank you for shopping with us.<br/><br/>

DailyPlaza.com... and you\'re done!<br/>
  <a href="'.BASE_URL.'">'.BASE_URL.'</a><br/><br/>
  </div>
</div>
<div style="clear:both;"></div>
<div style="width: 760px; height:auto; float: none; margin: 0 auto; padding: 10px; overflow: hidden; background: #ebebeb;">
<div style="color: #979797; width: 400px;  float: left; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 10px 30px; padding: 9px 0px; font-weight: normal;">&copy; '.date('Y').' dailyplaza. All rights reserved.</div>
<div style="color: #444; width: 180px; float: right; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 11px; margin: 10px 0; padding: 9px 0px; font-weight: normal;">   <a href="'.BASE_URL.'privacy-policies" style="padding: 0 8px;"> Privacy Policy</a>    |    <a href="'.BASE_URL.'faq" style="padding: 0 8px;">FAQs</a></div>
</div>';
		return $body;
	}

	function order_shipped_email_body($OrderID,$TrackingURL){
		$PDURL=SiteResourcesURL.'product/95X76/';
		$ImgURL=SiteImagesURL;
		$OrderDetails=$this->Order_model->details($OrderID);
		
		$body='<div style="width: 760px; height:auto; float: none; margin: 0 auto; padding: 10px; overflow: hidden; background: #ebebeb;">
<div style="width: 700px; float: none; margin: 0 auto; height: 150px;">
<div style="width: 260px; float: left; margin: 30px auto; height: auto;"><img src="'.$ImgURL.'logo.png" alt="" width="256" height="88" border="0;" /></div>
<div style="float: right; margin: 70px auto 0 auto; width: 150px; color: #444; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:24px; font-size: 21px; font-weight: normal;">Order Shipped</div>
</div>
<div style="clear: both;"></div>
<div style="width: 700px; float: none; margin: 0 auto; height: auto; background: #FFFFFF; overflow: hidden; padding: 10px;">
<div style="width: 700px; float: left; margin: 0 auto; height: auto; border-bottom: 1px solid #CCCCCC; color: #444; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; padding-bottom: 10px; font-weight: normal;">Greetings from DailyPlaza.com. </div>
<div style="clear: both;"></div>
<div style="float: left; margin: 20px auto 0 auto; width: 700px; color: #444; border-bottom: 1px solid #CCCCCC; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; font-weight: normal;">
We thought you\'d like to know that we shipped your items. Some of the items ordered may be shipped separately. You will get emails for the status of each of your product as we process your order.<br/><br/><br/>



Shipped via UPS on Fri Mar 28 10:57:03 MST 2014<br/>

Tracking URL: <a href="'.$TrackingURL.'">'.$TrackingURL.'</a><br/><br/> </div>
<div style="clear:both;"></div>
<div style="width: 700px; float: none; margin: 0 auto; height: auto;">
<div style="width: 680px; float: left; margin: 10px auto; height: auto; background: #2d5e9f; color: #fff; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 14px; padding: 10px;"><strong>Products in this shipment</strong></div>
</div>
<div style="clear:both;"></div>
<div style="width: 700px; float: none; margin: 0 auto; height: auto;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="15%" style="color: #444; border-bottom: 1px solid #CCCCCC; overflow: hidden; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 0; padding: 0px; font-weight: bold; padding: 12px 0;">Product</td>
    <td width="38%" style="color: #444; border-bottom: 1px solid #CCCCCC; overflow: hidden; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 0; padding: 0px; font-weight: bold;">Product Description</td>
    <td width="18%" style="color: #444; border-bottom: 1px solid #CCCCCC; overflow: hidden; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 0; padding: 0px; font-weight: bold;">Qty</td>
    <td width="12%" style="color: #444; border-bottom: 1px solid #CCCCCC; overflow: hidden; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 0; padding: 0px; font-weight: bold;">Price</td>
    <td width="15%" style="color: #444; border-bottom: 1px solid #CCCCCC; overflow: hidden; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 0; padding: 0px; font-weight: bold;">Total Price</td>
    <td width="2%" style="color: #444; font-family: \'Lucida Sans Unicode\',sans-serif; border-bottom: 1px solid #CCCCCC; overflow: hidden; line-height:17px; font-size: 13px; margin: 0; padding: 0px; font-weight: bold;">&nbsp;</td>
  </tr>';
  $CurrencySimbolArr=$this->config->item('CorrencySysmbol');
  $CartDataArr=$this->Order_model->get_cart_details_by_order($OrderID);
  $CurrencySimbol=$CurrencySimbolArr[$CartDataArr[0]->PurchaseLocation];
  $TotalDiscount=0;
  foreach($CartDataArr AS $k){
  	$PurchasePrice=number_format(($k->Price*$k->Qty),2);
  $body .= '
   <tr>
    <td  style="border-bottom: 1px solid #CCCCCC; overflow: hidden; padding: 12px 0;"><img src="'.$PDURL.$k->Image.'" alt="" width="71" height="71" border="0" /></td>
    <td style="color: #444; border-bottom: 1px solid #CCCCCC; overflow: hidden; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 10px 0; border-bottom: 1px solid #CCCCCC; overflow: hidden; padding: 0px; font-weight: normal;">'.$k->Title.'</td>
    <td style="color: #444; border-bottom: 1px solid #CCCCCC; overflow: hidden; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 10px 0; padding: 0px; font-weight: normal;">'.$k->Qty.'</td>
    <td style="color: #444; border-bottom: 1px solid #CCCCCC; overflow: hidden; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 10px 0; border-bottom: 1px solid #CCCCCC; overflow: hidden; padding: 0px; font-weight: normal;">'.$CurrencySimbol.$k->Price.'</td>
    <td style="color: #444; border-bottom: 1px solid #CCCCCC; overflow: hidden; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 10px 0; padding: 0px; font-weight: normal;">'.$CurrencySimbol.$PurchasePrice.'</td>
    <td style="border-bottom: 1px solid #CCCCCC; overflow: hidden;">&nbsp;</td>
  </tr>';
  $TotalDiscount +=$k->DiscountAmount;
  }
  $body .= '
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3"><p style="color: #444; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 12px; margin: 10px 0; padding: 0px; font-weight: normal;">Subtotal<span style="float:right; width: 101px;">'.$CurrencySimbol.number_format($OrderDetails[0]->SubTotalAmount,2).'</span></p>
      <p style="color: #444; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 12px; margin: 10px 0; padding: 0px; font-weight: normal;">Shipping and handilng<span style="float:right; width: 101px;"> '.$CurrencySimbol.number_format($OrderDetails[0]->ShippingAmount,2).'</span></p>
      <p style="color: #444; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 12px; margin: 10px 0; padding: 0px; font-weight: normal;">Discount<span style="float:right; width: 101px;">'.$CurrencySimbol.number_format($TotalDiscount,2).'</span></p>
      <p style="color: #444; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 12px; margin: 10px 0; padding: 0px; font-weight: normal;">Sales Tax</span></p>
      <p style="color: #444; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 12px; margin: 10px 0; padding: 9px 0px; font-weight: normal; border-top: 1px solid #CCCCCC; overflow: hidden;"><strong>Order Total</strong><span style="float:right; width: 101px;">'.$CurrencySimbol.number_format($OrderDetails[0]->OrderAmount,2).'</span></p></td>
    <td>&nbsp;</td>
  </tr>
</table>
</div>
<div style="clear:both;"></div>
<div style="width: 760px; height:auto; float: none; margin: 0 auto;">
<div style="color: #444; width: 700px;  float: left; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 10px 0; padding: 9px 10px 9ps 0; font-weight: normal;">
<br/><br/>

This shipment was sent to:<br/>

<span style="margin-left: 200px;">'.$OrderDetails[0]->ShippToName.'</span><br/>

<span style="margin-left: 200px;">'.$OrderDetails[0]->ShippingAddress.'</span><br/>

<span style="margin-left: 200px;">'.$OrderDetails[0]->ShippingCity.'</span><br/>

<span style="margin-left: 200px;">'.$OrderDetails[0]->ShippingStateName.','.$OrderDetails[0]->ShippingZip.'</span><br/>

<span style="margin-left: 200px;">'.$OrderDetails[0]->ShippingCountryName.'</span><br/><br/>

Login to your dailyplaza.com account and you can track your orders and see the status of other items in your order or other orders. Please note that tracking information may not be available immediately.<br/><br/>

Note:<br/>
* Once an order has been shipped we are unable stop the delivery.<br/>
* For faster service when emailing us please include your name, address and order # used during the purchase.<br/><br/>

If you\'ve explored the links on the Your Account page but still need assistance with your order, you\'ll find links to e-mail or call customer service.
<br/><br/>


Please be aware that items in this order may be subject to California\'s Electronic Waste Recycling Act. If any items in this order are subject to that Act, the seller of that item has elected to pay any fees due on your behalf.<br/><br/><br>
Please note: This e-mail was sent from a notification-only address that cannot accept incoming e-mail. Please do not reply to this message.<br/><br/><br/>
Thank you for shopping with us.<br/><br/>
<br/><br/>

DailyPlaza.com<br/><br/><a href="#">www.dailyplaza.com</a><br/><br/>
</div>
</div>
</div>
<div style="clear:both;"></div>
<div style="width: 760px; height:auto; float: none; margin: 0 auto; padding: 10px; overflow: hidden; background: #ebebeb;">
<div style="color: #979797; width: 400px;  float: left; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 13px; margin: 10px 0; padding: 9px 0px; font-weight: normal;">&copy; '.date('Y').' dailyplaza. All rights reserved.</div>
<div style="color: #444; width: 180px; float: right; font-family: \'Lucida Sans Unicode\',sans-serif; line-height:17px; font-size: 11px; margin: 10px 0; padding: 9px 0px; font-weight: normal;">   <a href="'.BASE_URL.'privacy-policies" style="padding: 0 8px;"> Privacy Policy</a>    |    <a href="'.BASE_URL.'faq" style="padding: 0 8px;">FAQs</a></div>
</div>';
	return $body;
	}
        
        public function filter(){
            $data=$this->_show_admin_logedin_layout();
                
            $per_page=500;
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            //echo 'KK  '.$config['per_page'];die;
            if($page==0){
                $offcet=0;
            }else{
                $offcet=$per_page*($page-1);
            }

            $data['OrderStateDataArr']=$this->Order_model->get_state();
            $data['DataArr']=$this->Order_model->admin_list($per_page,$offcet);
            $data["links"] = "";
            $this->load->view('admin/order_list',$data);
        }
}