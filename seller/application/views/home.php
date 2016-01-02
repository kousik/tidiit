<?php echo $html_heading;?>
<body class="  ">
    <div class="bg-dark dk" id="wrap">
        <?php echo $top.$left;?>
        <div id="content">
            <div class="outer">
          <div class="inner bg-light lter">
            <!--<div class="text-center">
              <ul class="stats_box">
                <li>
                  <div class="sparkline bar_week"></div>
                  <div class="stat_text">
                    <strong>2.345</strong> Weekly Visit
                    <span class="percent down"> <i class="fa fa-caret-down"></i> -16%</span> 
                  </div>
                </li>
                <li>
                  <div class="sparkline line_day"></div>
                  <div class="stat_text">
                    <strong>165</strong> Daily Visit
                    <span class="percent up"> <i class="fa fa-caret-up"></i> +23%</span> 
                  </div>
                </li>
                <li>
                  <div class="sparkline pie_week"></div>
                  <div class="stat_text">
                    <strong>$2 345.00</strong> Weekly Sale
                    <span class="percent"> 0%</span> 
                  </div>
                </li>
                <li>
                  <div class="sparkline stacked_month"></div>
                  <div class="stat_text">
                    <strong>$678.00</strong> Monthly Sale
                    <span class="percent down"> <i class="fa fa-caret-down"></i> -10%</span> 
                  </div>
                </li>
              </ul>
            </div>
            <hr>-->
            <div class="text-center">
              <a class="quick-btn" href="<?php echo BASE_URL.'order/viewlist/?HiddenFilterOrderStatus=2'?>">
                <i class="fa fa-flag-checkered fa-2x"></i>
                <span>Place Order</span> 
                <span class="label label-danger"><?php echo $placeOrders;?></span> 
              </a>   
              <a class="quick-btn" href="<?php echo BASE_URL.'order/viewlist/?HiddenFilterOrderStatus=3'?>">
                <i class="fa fa-check-square-o fa-2x"></i>
                <span>Confirm Orders</span> 
                <span class="label label-default"><?php echo $confirmOrders;?></span> 
              </a> 
              <a class="quick-btn" href="<?php echo BASE_URL.'order/viewlist/?HiddenFilterOrderStatus=4'?>">
                <i class="fa fa-truck fa-2x"></i>
                <span>Shipped Orders</span> 
                <span class="label label-success"><?php echo $shippedOrders;?></span> 
              </a> 
              <a class="quick-btn" href="<?php echo BASE_URL.'order/viewlist/?HiddenFilterOrderStatus=5'?>">
                <i class="fa fa-truck fa-2x"></i>
                <span>Our for delivery <br /> Orders</span> 
                <span class="label label-warning"><?php echo $outForDeliveryOrders;?></span> 
              </a> 
                
              <a class="quick-btn" href="<?php echo BASE_URL.'order/viewlist/?HiddenFilterOrderStatus=6'?>">
                <i class="fa fa-sun-o fa-2x"></i>
                <span>Delivered Order</span> 
                <span class="label label-warning"><?php echo $successOrders;?></span> 
              </a> 
              
              <a class="quick-btn" href="<?php echo BASE_URL.'order/viewlist/?HiddenFilterOrderStatus=7'?>">
                <i class="fa fa-remove fa-2x"></i>
                <span>Cancel Orders</span> 
                <span class="label btn-metis-2"><?php echo $cancelOrders;?></span> 
              </a> 
                
              <a class="quick-btn" href="#">
                <i class="fa fa-lemon-o fa-2x"></i>
                <span>é</span> 
                <span class="label btn-metis-4">2.71828</span> 
              </a> 
              <a class="quick-btn" href="#">
                <i class="fa fa-glass fa-2x"></i>
                <span>φ</span> 
                <span class="label btn-metis-3">1.618</span> 
              </a> 
            </div>
            <hr>
            <div class="row">
              <div class="col-lg-8">
                <div class="box">
                  <header>
                    <h5>Sale Chart</h5>
                  </header>
                  <div class="body" id="trigo" style="height: 250px;"></div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="box">
                  <div class="body">
                    <table class="table table-condensed table-hovered sortableTable">
                      <thead>
                        <tr>
                          <th>Products
                            <i class="fa sort"></i>
                          </th>
                          <th>Visit
                            <i class="fa sort"></i>
                          </th>
                          <th>Time
                            <i class="fa sort"></i>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <!--<tr class="active">
                          <td>Trident</td>
                          <td>1126</td>
                          <td>00:00:15</td>
                        </tr>
                        <tr class="danger">
                          <td>LED TV</td>
                          <td>43</td>
                          <td>00:00:30</td>
                        </tr>
                        <tr class="warning">
                          <td>Camera</td>
                          <td>547</td>
                          <td>00:10:20</td>
                        </tr>
                        <tr class="success">
                          <td>Rice</td>
                          <td>2450</td>
                          <td>00:10:00</td>
                        </tr> -->
                        <?php $i=0;foreach($viewsData AS $k): $i++;?>
                        <tr <?php if($i %2 ==0){?>class="success"<?php }else{?>class="warning"<?php } ?>>
                          <td><?php echo $k->title;?></td>
                          <td><?php echo $k->noOfViews;?></td>
                          <td><?php echo ($k->lastViewsDateTime!="") ? date('d-m-Y',  strtotime($k->lastViewsDateTime)): "";?></td>
                        </tr>  
                        <?php endforeach;?>  
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <!--<div class="row">
              <div class="col-lg-12">
                <div class="box">
                  <header>
                    <h5>Calendar</h5>
                  </header>
                  <div id="calendar_content" class="body">
                    <div id='calendar'></div>
                  </div>
                </div>
              </div>
            </div> -->
          </div><!-- /.inner -->
        </div><!-- /.outer -->
        </div><!-- /#content -->
    </div>
    <?php echo $footer;?>
    <script src="<?php echo SiteJSURL;?>fullcalendar.min.js"></script>
    <script src="<?php echo SiteJSURL;?>jquery.sparkline.min.js"></script>
    <script src="<?php echo SiteJSURL;?>jquery.flot.min.js"></script>
    <script src="<?php echo SiteJSURL;?>jquery.flot.selection.min.js"></script>
    <script src="<?php echo SiteJSURL;?>jquery.flot.resize.min.js"></script>
    <script src="<?php echo SiteJSURL;?>jquery.tablesorter.min.js"></script>
    <script src="<?php echo SiteJSURL;?>metis-dashboard-chart.js"></script>
    <script src="<?php echo SiteJSURL;?>metis-dashboard.js"></script>
    <script>
      $(function() {
        Metis.dashboard();
      });
    </script>
</body>
</html>