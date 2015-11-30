<?php echo $html_heading; echo $header;
$CI =& get_instance();
$CI->load->model('Product_model');
$CI->load->model('Order_model');
$status = array('0'=>'Delete','1'=>'Order Running', '2'=>'Order Processing', '3'=>'Out of Delivery', '4'=>'Delivered', '5'=>'Cancelled');
?>
</div>
</header>
<article>
  <div class="container">
    <div class="row">
      <div class="col-md-12 col-sm-12 productInner">
        <div class="page_content">
            <div class="row">
                <?php echo $userMenu;?>
                <div class="col-md-9 wht_bg">
                    <!-- Tab panes -->
                    <div class="tab_dashbord">
                    	<div class="active row">
                        	<div class="col-md-12 col-sm-12">
                                <form action="#" method="get">
                                    <div class="gen_infmtn">
                                        <h6>My Orders</h6>
                                        <div class="row">
                                            <?php if($orders):?>
                                            <table class="table table-striped notyfy-table">
                                                <thead>
                                                <tr>
                                                    <th>Order ID</th>
                                                    <th>Description</th>
                                                    <th>Total Price</th>
                                                    <th>Order Date</th>
                                                    <th>Status</th>
                                                    <th>&nbsp;</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
            
                                            <?php foreach($orders as $key => $order):
                                                $info =  unserialize(base64_decode($order->orderInfo));
                                                if(!$info):
                                                    $ptitle = $order->title;
                                                    $pimage = $order->image;
                                                else:
                                                    $ptitle = $info['pdetail']->title?$info['pdetail']->title:$order->title;
                                                    $pimage = $info['pimage']->image?$info['pimage']->image:$order->image;
                                                endif;
                                                $porder = $this->Order_model->get_parent_order($order->orderId);
                                                ?>
                                                <tr class="" id="not-<?=base64_encode($order->orderId*226201);?>">
                                                    <td scope="row"><span class="label label-success">TIDIIT-OD-<?=$order->orderId?></span></td>
                                                    <td><a href="<?php echo BASE_URL.'product/details/'.base64_encode($order->productId);?>" class="" target="_blank"><img src="<?=PRODUCT_DEAILS_SMALL.$pimage?>" alt="..." class="img-responsive"/></a><a href="<?php echo BASE_URL.'product/details/'.base64_encode($order->productId);?>" class="" target="_blank"><?=$ptitle;?></a></td>
                                                    <td><b><i class="fa fa-rupee"></i> <?=$order->orderAmount;?></b></td>
                                                    <td><i class="fa fa-clock-o"></i><?=date('F j, Y, g:i a' , strtotime($order->orderDate));?></td>
                                                    <td><span class="label label-info"><?=$order->orderType;?> - <?=$status[$order->status];?></span></td>
                                                    <td align="center">
                                                <a class="btn btn-success btn-xs js-view-order" href="<?php echo BASE_URL.'order/details/'.base64_encode($order->orderId*226201);?>"><i class="fa fa-eye"></i> Order</a>
                                                    <?php if($porder):?><a class="btn btn-warning btn-xs" data-oid="<?=base64_encode($order->orderId*226201);?>"><i class="fa fa-eye"></i> Parent Order</a><?php endif;?>
                                                    <?php if($order->orderType == 'SINGLE' && $order->status < 3):?><a class="btn btn-danger btn-xs" data-oid="<?=base64_encode($order->orderId*226201);?>"><i class="fa fa-shopping-cart"></i> Cancel Order</a><?php endif;?>
                                                    </td>
                                                </tr>
                                            
                                            <?php endforeach;?>
                                            </tbody>
                                            </table>
                                            <?php else:?>
                                            <div class="list-group-item list-group-item-info">
                                                <h4>You have no any order!</h4>
                                            </div> 
                                            <?php endif;?>
                                        </div>
                                    </div>  
                                </form>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</article>
<?php echo $footer;?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        /*jQuery("body").delegate('.js-group-cart-remove', "click", function(e){
            e.preventDefault();
            var orderId = jQuery(this).attr('data-orderid');
            window.location.href = myJsMain.baseURL+'order/view/'+orderId;
        });  */  
    });
</script>    