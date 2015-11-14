<?php 
$CI =& get_instance();
?>
<div class="modal fade notification-popup" id="notification" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
    
    	<div class="modal-header">   
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="cart-heading clearfix">
                <h3><?=$n->nTitle?> <span class="label label-success"><?=$n->nType;?></span></h3>
            </div>
          </div>   
        <div class="modal-body">
            <?=$n->nMessage?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Close</span></button>
        </div>            
                    
    </div>
  </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#notification').modal('show');
    });
</script>