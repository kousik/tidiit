<div id="top">

        <!-- .navbar -->
        <nav class="navbar navbar-inverse navbar-fixed-top">
          <div class="container-fluid">

            <!-- Brand and toggle get grouped for better mobile display -->
            <header class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span> 
                <span class="icon-bar"></span> 
                <span class="icon-bar"></span> 
                <span class="icon-bar"></span> 
              </button>
              <a href="<?php echo BASE_URL;?>" class="navbar-brand">
                <img src="<?php echo SiteImagesURL;?>logo.png" alt="">
              </a> 
            </header>
            <div class="topnav">              
              <div class="btn-group">
                <a href="<?php echo BASE_URL.'index/logout';?>" data-toggle="tooltip" data-original-title="Logout" data-placement="bottom" class="btn btn-metis-1 btn-sm">
                  <i class="fa fa-power-off"></i>
                </a> 
              </div>
              <div class="btn-group">
                <a data-placement="bottom" data-original-title="Show / Hide Left" data-toggle="tooltip" class="btn btn-primary btn-sm toggle-left" id="menu-toggle">
                  <i class="fa fa-bars"></i>
                </a>                
              </div>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">

              <!-- .nav -->
              <ul class="nav navbar-nav">
                <li class="dropdown <?php if($this->uri->segment(1)=="" || $this->uri->segment(1)=='index'){ echo 'active';}?>">
                  <a href="<?php echo BASE_URL;?>" class="dropdown-toggle" data-toggle="dropdown">Dashboard <b class="caret"></b></a> 
                  <ul class="dropdown-menu">
                      <li> <a href="<?php echo BASE_URL.'index/my_warehouse/';?>">My Warehouse</a>  </li>
                        <li> <a href="<?php echo BASE_URL.'index/edit_profile/';?>">Update Profile</a>  </li>
                        <li> <a href="<?php echo BASE_URL;?>index/edit_finance_info/">Update Finance Info</a>  </li>
                  </ul>
                </li>
                <li class='dropdown <?php if($this->uri->segment(1)=="product"){ echo 'active';}?>' >
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Inventory <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li> <a href="<?php echo BASE_URL.'product/viewlist/';?>">Manage Inventory</a>  </li>
                        <li> <a href="<?php echo BASE_URL;?>product/add_product/">Add a Product</a>  </li>
                      </ul>                
                </li>
                
                <li class='dropdown <?php if($this->uri->segment(1)=="order"){ echo 'active';}?>'> 
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Orders <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li> <a href="<?php echo BASE_URL;?>order/viewlist/">Manage Orders</a>  </li>
                        <li> <a href="<?php echo BASE_URL;?>">Manage Return</a>  </li>
                      </ul>                
                </li>
                
                <li class='dropdown '> 
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li> <a href="<?php echo BASE_URL;?>payment">Payments</a>  </li>
                        <li> <a href="<?php echo BASE_URL;?>">Others</a>  </li>
                      </ul>                
                </li>
                
                <!--<li class='dropdown '> 
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Performance<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li> <a href="satisfaction.html">Customer Satisfaction</a>  </li>
                        <li> <a href="<?php //echo BASE_URL;?>seller_feedback">Feedback</a>  </li>
                         <li> <a href="guarantee.html">A-to-z Guarantee Claims</a>  </li>
                        <li> <a href="chargback.html">Chargeback Claims</a>  </li>
                        <li> <a href="performance.html">Performance Notifications</a>  </li>
                      </ul>                
                </li> -->
                
               
              </ul><!-- /.nav -->
            </div>
          </div><!-- /.container-fluid -->
        </nav><!-- /.navbar -->
        <header class="head">
          <!-- /.search-bar -->
          <div class="main-bar">
            <h3>
              <i class="fa fa-dashboard"></i>&nbsp; Dashboard</h3>
          </div><!-- /.main-bar -->
        </header><!-- /.head -->
      </div><!-- /#top -->