<!-- Modal -->
<div class="modal fade" id="myModalLogin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Group Details of Group Id :<?php echo $groupId;?>, Group Title : <?php echo $group->groupTitle;?> </h4>
      </div>
        <form action="#" method="post" name="add_groups" class="form-horizontal" id="add_groups"> 
            <div class="modal-body">
                <div class="col-md-12 col-sm-12"> 
                    <div class="gen_infmtn">
                        <div class="table-responsive">
                            <div class="panel panel-default">
                            <table class="table table-striped" id='js-print-container'>
                                <tbody>
                                <tr>
                                    <td>
                                        <p><i class="fa fa-sort-desc"></i> Group Leader Details</p>
                                     <table class="table">
                                        <thead>
                                        <tr class="info">
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone No</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td><?php echo $group->admin->firstName.' '.$group->admin->lastName;?></td>
                                            <td><?php echo $group->admin->email;?></td>
                                            <td><?php echo $group->admin->contactNo;?></td>
                                        </tr>
                                        </tbody>
                                    </table>

                                     <p><i class="fa fa-sort-desc"></i> Group Members</p>
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr class="danger">
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone No</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($group->users As $k):?>
                                            <tr>
                                                <td><?php echo $k->firstName.' '.$k->lastName;?></td>
                                                <td><?php echo $k->email;?></td>
                                                <td><?php echo $k->contactNo;?></td>
                                            </tr>
                                            <?php endforeach;?>
                                            </tbody>
                                            
                                        </table>  
                                    </td>
                                </tr>
                                </tbody>
                            </table>  
                            </div>
                        </div> 
                    </div>
                </div>    
            </div>
        <div class="modal-footer">&nbsp;</div>
      </form>    
    </div>
  </div>
</div>
<!-- /.modal -->
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#myModalLogin').modal('show');
    });
</script>