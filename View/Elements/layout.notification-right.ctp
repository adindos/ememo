<!-- Quick note:
            notifications_r     --- remark
            notifications_c     --- comment
            notifications_s     --- submitted
            notifications_re    --- re-submitted
            notifications_a     --- approved
            notifications_j     --- rejected
            notifications_p     --- pending
            notifications_tor   --- to review
            notifications_na    --- n/a in progress--> 

<!-- Right Slidebar start -->
<div class="sb-slidebar sb-right sb-style-overlay">
    <h5 class="side-title"> User Access</h5>
    <ul class="quick-chat-list">
        <li>
            <div class="media">
                <div class="pull-left media-thumb">
                    <?php
                    echo $this->Html->Image('Faces_Users-13.png',array('class'=>'media-object'));
                    ?>
                </div>
                <div class="media-body pull-left">
                    <strong>
                        <?php
                            echo $activeUser['staff_name'];
                        ?>
                    </strong>
                    <small>
                        <?php
                            echo $activeUser['designation'];
                        ?>
                    </small>
                </div>
                <?php
                    echo $this->Html->link('Logout',array('controller'=>'user','action'=>'logout'),array('class'=>'logout-noti sb-disable-close'));
                ?>
            </div><!-- media -->
        </li>
    </ul>

    <h5 class="side-title">
        Notifications 
        <?php
            echo " ( <span class='num-noti'>". count($notifications) . "</span> ) ";
            //mark seen
            echo "<a class='seen-link sb-disable-close tooltips small' id='clear-all' style='text-transform:none;margin-top:0;font-size:95%;cursor:pointer'  data-original-title='clear notification'> clear all </a>";
        ?>
    </h5>
      <ul class="quick-chat-list" id="all-notification-list">
        <?php
            echo "<div id='loading-bar' style='display:none'>";
                echo "<li class='text-center'>";
                echo $this->Html->image('loading-bar.gif');
                echo "</li>";
            echo "</div>";

            if(count($notifications) == 0){
                echo "<li class='text-center'>
                        <em> You don't have any notification </em>
                    </li>";
            }
            //foreach($notifications as $n):
        ?>
        <!-- old -->
           <!--  <li>
                <div class="media" style="font-size:85%">
                    <?php
                        // $text = $n['UserNotification']['text'];
                        // $datetime = date('c',strtotime($n['UserNotification']['created']));
                        // $timeago = "<small class='timeago' title='{$datetime}'>".$datetime." </small>";
                        // $link = $n['UserNotification']['link'];

                        // echo $this->Html->link("{$text} {$timeago}",array('controller'=>'mems','action'=>'notification','?'=>array('noti'=>$n['UserNotification']['token'])),array('escape'=>false,'class'=>'media-body pull-left width-70pc noti-link sb-disable-close'));

                        // //mark seen
                        // echo "<a class='seen-link mark-seen sb-disable-close tooltips' data-token='{$n['UserNotification']['token']}' data-original-title='Mark as seen'> X </a>";
                    ?>
                    
                </div>
            </li> -->

        <!-- ===code update for ememo2=== -->
            <!-- R e m a r k s -->
                <!-- ======================================================================= -->
                <?php       
                if( count($notifications_r)!=0){
                     echo "<li class='text-center' style='background-color:#35404D'>  <span style='color:#A9D96C'><em>R e m a r k s </em></span> &nbsp; 
                    </li>";
                if(count($notifications_r) == 0){                
                        echo "<li class='text-center'>
                                <em> You don't have any message </em>
                            </li>";
                    } 
                    foreach($notifications_r as $n):

                ?>    
                <li>                  
                    <div class="media" style="font-size:85%">
                        <?php                   
                        $text = $n['UserNotification']['text'];
                        $datetime = date('c',strtotime($n['UserNotification']['created']));
                        $timeago = "<small class='timeago' title='{$datetime}'>".$datetime." </small>";
                        $link = $n['UserNotification']['link'];

                        echo $this->Html->link("{$text} {$timeago}",array('controller'=>'mems','action'=>'notification','?'=>array('noti'=>$n['UserNotification']['token'])),array('escape'=>false,'class'=>'media-body pull-left width-70pc noti-link sb-disable-close'));

                                    //mark seen
                        echo "<a class='seen-link mark-seen sb-disable-close tooltips' data-token='{$n['UserNotification']['token']}' data-original-title='Mark as seen'> X </a>";
                        ?>

                    </div>
                </li>
                <?php
                // endfor;
                    endforeach;
                }//end of if
                ?>

            <!-- C o m m e n t s -->
                <!-- ======================================================================= -->
                <?php       
                if( count($notifications_c)!=0){
                     echo "<li class='text-center' style='background-color:#35404D'>  <span style='color:#FCF8E3'><em>C o m m e n t s </em></span> &nbsp; 
                    </li>";
                if(count($notifications_c) == 0){
                        echo "<li class='text-center'>
                                <em> You don't have any message </em>
                            </li>";
                    } 
                    foreach($notifications_c as $n):

                ?>    
                <li>                  
                    <div class="media" style="font-size:85%">
                        <?php                   
                        $text = $n['UserNotification']['text'];
                        $datetime = date('c',strtotime($n['UserNotification']['created']));
                        $timeago = "<small class='timeago' title='{$datetime}'>".$datetime." </small>";
                        $link = $n['UserNotification']['link'];

                        echo $this->Html->link("{$text} {$timeago}",array('controller'=>'mems','action'=>'notification','?'=>array('noti'=>$n['UserNotification']['token'])),array('escape'=>false,'class'=>'media-body pull-left width-70pc noti-link sb-disable-close'));

                                    //mark seen
                        echo "<a class='seen-link mark-seen sb-disable-close tooltips' data-token='{$n['UserNotification']['token']}' data-original-title='Mark as seen'> X </a>";
                        ?>

                    </div>
                </li>
                <?php
                // endfor;
                    endforeach;
                }//end of if
                ?>

            <!-- M e m o s -->
                <!-- ======================================================================= -->
                <?php 
                // debug($notifications);       
                if( count($notifications)!=0){
                     echo "<li class='text-center' style='background-color:#35404D'>  <span style='color:#D9EDF7'><em>M e m o </em></span> &nbsp; 
                    </li>";
                    if(count($notifications) == 0){
                        echo "<li class='text-center'>
                                <em> You don't have any message </em>
                            </li>";
                    } 
                
                }//end of if
                ?>
               
                <!-- M e m o : type = memo -->        
                    <!-- ======================================================================= -->
                    <?php            
                    if( count($notification_memo)!=0){
                        echo " &nbsp;<span style='color:#D9EDF7'><em> New notification </em></span> &nbsp;";
                        
                        foreach($notification_memo as $n):

                    ?>    
                        <li>                  
                            <div class="media" style="font-size:85%">
                                <?php                   
                                $text = $n['UserNotification']['text'];
                                $datetime = date('c',strtotime($n['UserNotification']['created']));
                                $timeago = "<small class='timeago' title='{$datetime}'>".$datetime." </small>";
                                $link = $n['UserNotification']['link'];
                                $type=null;

                                echo $this->Html->link("{$text} {$timeago} {$type}",array('controller'=>'mems','action'=>'notification','?'=>array('noti'=>$n['UserNotification']['token'])),array('escape'=>false,'class'=>'media-body pull-left width-70pc noti-link sb-disable-close'));

                                            //mark seen
                                echo "<a class='seen-link mark-seen sb-disable-close tooltips' data-token='{$n['UserNotification']['token']}' data-type='{$type}' data-original-title='Mark as seen'> X </a>";
                                ?>

                            </div>
                        </li>
                    <?php
                    // endfor;
                        endforeach;
                    }//end of if
                    ?>
                <!-- for old ememo notification -->
                <!-- M e m o : type = null -->        
                    <!-- ======================================================================= -->
                    <?php            
                    if( count($notifications_null)!=0){
                        echo " &nbsp;<span style='color:#F2B968'><em> Old notification </em></span>  &nbsp; ";
                        
                        foreach($notifications_null as $n):

                    ?>    
                        <li>                  
                            <div class="media" style="font-size:85%">
                                <?php                   
                                $text = $n['UserNotification']['text'];
                                $datetime = date('c',strtotime($n['UserNotification']['created']));
                                $timeago = "<small class='timeago' title='{$datetime}'>".$datetime." </small>";
                                $link = $n['UserNotification']['link'];
                                $type=null;
                                

                                echo $this->Html->link("{$text} {$timeago} {$type}",array('controller'=>'mems','action'=>'notification','?'=>array('noti'=>$n['UserNotification']['token'])),array('escape'=>false,'class'=>'media-body pull-left width-70pc noti-link sb-disable-close'));

                                            //mark seen
                                echo "<a class='seen-link mark-seen sb-disable-close tooltips' data-token='{$n['UserNotification']['token']}' data-type='{$type}' data-original-title='Mark as seen'> X </a>";
                                ?>

                            </div>
                        </li>
                    <?php
                    // endfor;
                        endforeach;
                    }//end of if
                    ?>        

            <!-- B u d g e t -->
                <!-- ======================================================================= -->
                <?php       
                if( count($notifications_b)!=0){
                     echo "<li class='text-center' style='background-color:#35404D'>  <span style='color:#A9D86E'><em>B u d g e t </em></span> &nbsp;</li>";
                if(count($notifications_b) == 0){
                        echo "<li class='text-center'>
                                <em> You don't have any message </em>
                            </li>";
                    } 
                    foreach($notifications_b as $n):

                ?>    
                <li>                  
                    <div class="media" style="font-size:85%">
                        <?php                   
                        $text = $n['UserNotification']['text'];
                        $datetime = date('c',strtotime($n['UserNotification']['created']));
                        $timeago = "<small class='timeago' title='{$datetime}'>".$datetime." </small>";
                        $link = $n['UserNotification']['link'];

                        echo $this->Html->link("{$text} {$timeago}",array('controller'=>'mems','action'=>'notification','?'=>array('noti'=>$n['UserNotification']['token'])),array('escape'=>false,'class'=>'media-body pull-left width-70pc noti-link sb-disable-close'));

                                    //mark seen
                        echo "<a class='seen-link mark-seen sb-disable-close tooltips' data-token='{$n['UserNotification']['token']}' data-original-title='Mark as seen'> X </a>";
                        ?>

                    </div>
                </li>
                <?php
                // endfor;
                    endforeach;
                }//end of if
                ?>
        <!-- ===end of updated codes==== -->
    </ul>
</div>
<!-- Right Slidebar end -->

<!-- Ajax function to update seen -->
<script type="text/javascript">
$(document).ready(function(){
    $('a.mark-seen').click(function(){
        var noti = $(this).data('token');
        var clickedElement = $(this);
        // alert(noti);
        var num = $('.num-noti').html();
        var updatedNum = parseInt(num)-1;


        $.ajax({
            method: 'POST',
            url: "<?php echo ACCESS_URL ?>/markseen",
            data: {token:noti},
            success: function(){
                // console.log(data);
                clickedElement.addClass('seened'); //mark as seen              
            },
            complete: function(){
                var target = clickedElement.closest('li');  

                // alert(updatedNum);
                $('.num-noti').html(updatedNum);  

                target.delay(500).fadeOut(200, function(){ 
                    $(this).remove();
                });
            },
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
              }
        });
    });

    $('a#clear-all').click(function(){
        var updatedNum = 0;

        $.ajax({
            method: 'POST',
            url: "<?php echo ACCESS_URL ?>/clearAllNotifications",
            success: function(){
                // console.log(data);         
            },
            complete: function(){
                // alert(updatedNum);
                $('.num-noti').html(updatedNum); 

                var loading = $('#loading-bar').html();
                // alert(loading);
                $('ul#all-notification-list').html(loading).delay(1000).fadeOut(1000,function(){
                	$(this).html("<li class='text-center'><em> You don't have any notification </em></li>").fadeIn(1000);
                });
                // $('ul#all-notification-list').fadeOut(1000).html('').delay(1000).html("<li class='text-center'><em> You don't have any notification </em></li>").fadeIn();
                // $('.loading-bar').hide(1000);
                
            },
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
              }
        });
    });
})
</script>