<?php echo $html_heading; echo $header;?>
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
                             <div class="gen_infmtn">
                            	<h6>My Groups <span class="pull-right"><input type="button" data-toggle="modal" data-target="#myModalLogin" name="" value="Creat new Group" /></span></h6>
                                </div>
                                        
                                <div class="col-md-3 col-sm-3 grp_dashboard ">
                                	<div class="red">
                                		<span><i class="fa  fa-group fa-5x"></i></span>
                                    </div>
                                    <div class="grp_title">Group 1</div>
                            	</div>
                                
                                <div class="col-md-3 col-sm-3 grp_dashboard ">
                                	<div class="maroon">
                                		<span><i class="fa  fa-group fa-5x"></i></span>
                                    </div>
                                    <div class="grp_title">Group 1</div>
                            	</div>
                                
                                <div class="col-md-3 col-sm-3 grp_dashboard ">
                                	<div class="purple">
                                		<span><i class="fa  fa-group fa-5x"></i></span>
                                    </div>
                                    <div class="grp_title">Group 1</div>
                            	</div>
                                
                                <div class="col-md-3 col-sm-3 grp_dashboard ">
                                	<div class="green">
                                		<span><i class="fa  fa-group fa-5x"></i></span>
                                    </div>
                                    <div class="grp_title">Group 1</div>
                            	</div>
                                
                                <div class="col-md-3 col-sm-3 grp_dashboard ">
                                	<div class="blue">
                                		<span><i class="fa  fa-group fa-5x"></i></span>
                                    </div>
                                    <div class="grp_title">Group 1</div>
                            	</div>
                                
                               
                                
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

 <!-- Modal -->
<div class="modal fade" id="myModalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg opn_box" style='padding:10px; background:#fff; border-radius:10px;'>
  <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <div class="container">
        <div class="row">
			<div class="col-md-12 col-sm-12">
            <div class="create_grp">
            	<div id="login_form">
					<form class="contact_form" action="#" method="post" name="contact_form">
		<h1>Select Group Memebers</h1>
        <hr />
            <div class="row">
           <div class="col-md-4">
           <label for="locality" class="col-md-6 pad_none">Select Locality :</label>
           	<div class="col-md-6 pad_none"><select name="locality" class="">
            	<option>11</option>
                <option>11</option>
                <option>11</option>
            </select>
            </div>
           </div>
           <div class="col-md-5">
           <label for="locality" class="col-md-6 pad_none">Select Product Type :</label>
           	<div class="col-md-6 pad_none"><select name="locality" class="">
            	<option>11</option>
                <option>11</option>
                <option>11</option>
            </select>
            </div>
           </div>
           <div class="col-md-3">
           <input type="button"  class="grpButton" name="creatGrp" value="Create New Group" />
           </div>
         </div>	
		<div class="clear"></div>	
	</form>
    
    <div class="contact_form">
    <hr />
    
    <div class="row grpMemeber grpHeader">    
    	<div class="col-md-2">Name</div>
        <div class="col-md-2">Locality</div>
        <div class="col-md-2">Product Type</div>
        <div class="col-md-2">option1</div>
        <div class="col-md-1">option2</div>
        <div class="col-md-3">Action</div>        
    </div>
    
    
     <div class="row grpMemeber odd">    
    	<div class="col-md-2">tester</div>
        <div class="col-md-2">tererer</div>
        <div class="col-md-2">ererer</div>
        <div class="col-md-2">erer</div>
        <div class="col-md-1">erer</div>
        <div class="col-md-3"><input type="button"  class="grpButton" name="creatGrp" value="Add to Group" /></div>        
    </div>
    
     <div class="row grpMemeber even">    
    	<div class="col-md-2">tester</div>
        <div class="col-md-2">tererer</div>
        <div class="col-md-2">ererer</div>
        <div class="col-md-2">erer</div>
        <div class="col-md-1">erer</div>
        <div class="col-md-3"><input type="button"  class="grpButton" name="creatGrp" value="Add to Group" /></div>        
    </div>
    
   </div> 
    
				</div>
			</div>
         </div>
            
            
<!-- start-form -->

       
    </div>
  </div>           
    
 </div>
</div>
<!-- /.modal -->

<?php echo $footer;?>