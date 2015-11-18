<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo $html_heading; echo $header;
$CI =& get_instance();

$url = $CI->config->site_url($CI->uri->uri_string());
$curl =  $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
$record_num = end($this->uri->segment_array());
$last = is_numeric($record_num)?$record_num:0;
?>
<script src="<?php echo SiteJSURL;?>user-all-my-js.js" type="text/javascript"></script>
</div>
</header>
<article>

    <div class="container">

        <div class="row">

            <div class="col-md-12 col-sm-12 productInner">

                <div class="page_content">

                    <div class="row">

                        <?= $userMenu ?>

                        <div class="col-md-9 wht_bg">

                            <!-- Tab panes -->

                            <div class="tab_dashbord">

                                <div class="active row">
                                    
                                    <div class="col-md-12 col-sm-12">
                                        
                                        <div class="list-group gen_infmtn">
                                            <div class="js-message" style="display: none;"></div>
                                            <div class="js-show-notification"></div>
                                            <div class="clearfix"></div>
                                            <h6>MY NOTIFICATIONS</h6>
                                            <?php if($notifications):?>
                                            <table class="table table-striped notyfy-table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Notification</th>
                                                    <th>Type</th>
                                                    <th>Date</th>
                                                    <th style="text-align: center;">Action</th>
                                                  </tr>
                                                </thead>
                                                <tbody>
            
                                            <?php foreach($notifications as $key => $nfn): ?>
                                                <tr class="<?php if($nfn->isRead == 0):?>info<?php else:?>warning<?php endif;?>" id="not-<?=base64_encode($nfn->id*226201);?>">
                                                    <td scope="row"><?=$rid+$key+1?></td>
                                                    <td><a href="javascript://" class="js-notfy-view" data-nd="<?=base64_encode($nfn->id*226201);?>" data-tp="<?=$nfn->nType;?>"><?=$nfn->nTitle;?></a></td>
                                                    <td><span class="label label-primary"><?=$nfn->nType;?></span></td>
                                                    <td><i class="fa fa-clock-o"></i><?=date('F j, Y, g:i a' , strtotime($nfn->createDate));?></td>
                                                    <td align="center"><a class="btn btn-info btn-xs js-notfy-view" data-nd="<?=base64_encode($nfn->id*226201);?>" data-tp="<?=$nfn->nType;?>"><i class="fa fa-info-circle"></i></a>
                                                <a class="btn btn-danger btn-xs js-notfy-delete" data-nd="<?=base64_encode($nfn->id*226201);?>"><i class="fa fa-trash"></i></a></td>
                                                </tr>
                                            
                                            <?php endforeach;?>
                                            </tbody>
                                            </table>
                                            <?php else:?>
                                            <div class="list-group-item list-group-item-info">
                                                <h4>You have no any notification!</h4>
                                            </div> 
                                            <?php endif;?>
                                        </div>
                                        <?php	if($this->pagination->create_links()):?>
                                            <div class="row">
                                                <div class="col-md-12 text-center"><?=$this->pagination->create_links();?></div></div>
                                        <?php 	endif;  ?>
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
<script type="text/javascript">

    jQuery(document).ready( function() {
        <?php if($this->session->flashdata('msg')): ?>
                $('div.js-message').html('<div class="alert alert-success" role="alert"><?=$this->session->flashdata('msg')?></div>');
                $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });                         
        <?php endif;?>
        
        jQuery("body").delegate('a.js-notfy-delete', "click", function(e){
            e.preventDefault();
            if( confirm("Are you sure you want to delete this entry?") ){
                var nd = jQuery(this).attr('data-nd');
                var url = '<?=$url?>';
                var last = <?=$last?>;
                var list = jQuery('table.notyfy-table tbody tr').length;
                var ppage = <?=$per_page?>;
                jQuery.post( myJsMain.baseURL+'notification/ajax_notification_delete/', {
                    nd: nd,
                    last: last,
                    list: list,
                    ppage: ppage,
                    url: url
                },
                function(data){ 
                    if(data.msg){                    
                       window.location.href = data.url;
                    }
                    
                    if(data.error){                    
                        $('div.js-message').html('<div class="alert alert-danger" role="alert">'+data.error+'</div>');
                $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
                    }
                }, 'json' );
            }
        });
        
        
        jQuery("body").delegate('a.js-notfy-view', "click", function(e){
            e.preventDefault();
            var nd = jQuery(this).attr('data-nd');
            var tp = jQuery(this).attr('data-tp');
            var obj = jQuery(this);
            jQuery.post( myJsMain.baseURL+'notification/ajax_notification_view/', {
                nd: nd,
                tp: tp
            },
            function(data){ 
                if(data.msg){                   
                    $('div.js-show-notification').html(data.msg);
                    obj.parent('td').parent('tr').removeClass('info');
                    obj.parent('td').parent('tr').addClass('warning');
                } else {
                    $('div.js-message').html('<div class="alert alert-danger" role="alert">Sorry! Unable to process!</div>');
                    $('div.js-message').fadeIn(300,function() { setTimeout( '$("div.js-message").fadeOut(300)', 15000 ); });
                }
            }, 'json' );
        });
        
    });
</script>
<?php echo $footer;?>