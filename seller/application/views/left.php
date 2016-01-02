<div id="left">
        <div class="media user-media bg-dark dker">
          <div class="user-media-toggleHover">
            <span class="fa fa-user"></span> 
          </div>
          <div class="user-wrapper bg-dark">
            <a class="user-link" href="">
              <img class="media-object img-thumbnail user-img" alt="User Picture" src="<?php echo SiteImagesURL;?>user.gif">
              <!--<span class="label label-danger user-label">16</span> -->
            </a> 
            <div class="media-body">
              <h5 class="media-heading"><?php echo $this->session->userdata('FE_SESSION_VAR_FNAME');?></h5>
              <ul class="list-unstyled user-info">
                <!--<li> <a href="">Administrator</a>  </li> -->
                <li>
                    <?php if(!empty($last_login)){?>
                    Last Access :
                    <br>
                    <small>
                        <i class="fa fa-calendar"></i>&nbsp;<?php echo date('d M H:i',  strtotime($last_login[0]->logedInTime));?></small> 
                    <?php }else {?>
                        First time
                    <?php }?>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- #menu -->
        <ul id="menu" class="bg-blue dker">
          <!--<li class="nav-header">Menu</li>-->
          <li class="nav-divider"></li>
          <li class="active">
            <a href="<?php echo BASE_URL;?>">
              <i class="fa fa-dashboard"></i><span class="link-title">&nbsp;Dashboard</span> 
            </a> 
          </li>           
          <li>
            <a href="<?php echo BASE_URL;?>order/viewlist">
              <i class="fa fa-table"></i>
              <span class="link-title">Orders</span>
            </a> 
          </li>
          <li>
            <a href="<?php echo BASE_URL;?>product/viewlist/">
              <i class="fa fa-bars"></i>
              <span class="link-title">Products</span> 
            </a> 
          </li>
          <li>
            <a href="performance.html">
              <i class="fa fa-font"></i>
              <span class="link-title">Performance</span>  </a> 
          </li>
          
          <li class="nav-divider"></li>
          <li>
            <a href="#">
              <i class="fa fa-sign-in"></i>
              <span class="link-title">Finance Information</span> 
            </a> 
          </li>
          
          <!--<li>
            <a href="javascript:;">
              <i class="fa fa-code"></i>
              <span class="link-title">Unlimited Level Menu </span> 
              <span class="fa arrow"></span> 
            </a> 
            <ul>
              <li>
                <a href="javascript:;">Level 1  <span class="fa arrow"></span>  </a> 
                <ul>
                  <li> <a href="javascript:;">Level 2</a>  </li>
                  <li> <a href="javascript:;">Level 2</a>  </li>
                  <li>
                    <a href="javascript:;">Level 2  <span class="fa arrow"></span>  </a> 
                    <ul>
                      <li> <a href="javascript:;">Level 3</a>  </li>
                      <li> <a href="javascript:;">Level 3</a>  </li>
                      <li>
                        <a href="javascript:;">Level 3  <span class="fa arrow"></span>  </a> 
                        <ul>
                          <li> <a href="javascript:;">Level 4</a>  </li>
                          <li> <a href="javascript:;">Level 4</a>  </li>
                          <li>
                            <a href="javascript:;">Level 4  <span class="fa arrow"></span>  </a> 
                            <ul>
                              <li> <a href="javascript:;">Level 5</a>  </li>
                              <li> <a href="javascript:;">Level 5</a>  </li>
                              <li> <a href="javascript:;">Level 5</a>  </li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li> <a href="javascript:;">Level 4</a>  </li>
                    </ul>
                  </li>
                  <li> <a href="javascript:;">Level 2</a>  </li>
                </ul>
              </li>
              <li> <a href="javascript:;">Level 1</a>  </li>
              <li>
                <a href="javascript:;">Level 1  <span class="fa arrow"></span>  </a> 
                <ul>
                  <li> <a href="javascript:;">Level 2</a>  </li>
                  <li> <a href="javascript:;">Level 2</a>  </li>
                  <li> <a href="javascript:;">Level 2</a>  </li>
                </ul>
              </li>
            </ul>
          </li> -->
        </ul><!-- /#menu -->
      </div><!-- /#left -->