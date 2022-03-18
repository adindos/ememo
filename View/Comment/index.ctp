<?php
  if( $type == 'financial'){
    $encFinancialID = $this->Mems->encrypt($module_info['FMemo']['memo_id']);
    $this->Html->addCrumb(" ( Review Financial Memo ) Ref. No : ".$module_info['FMemo']['ref_no'], array('controller' => 'fMemo', 'action' => 'review',$encFinancialID));
    $this->Html->addCrumb('Comments', $this->here,array('class'=>'active'));
  }
  elseif( $type == 'nonfinancial'){
    $encNonFinancialID = $this->Mems->encrypt($module_info['NfMemo']['memo_id']);
    $this->Html->addCrumb(" ( Review Non Financial Memo ) Ref. No : ".$module_info['NfMemo']['ref_no'], array('controller' => 'NfMemo2', 'action' => 'review',$encNonFinancialID));
    $this->Html->addCrumb('Comments', $this->here,array('class'=>'active'));
  }
?>

<section class="mems-content">
  <!-- page start-->
  <?php
    if ($type=='financial'){
        $commentModel='FComment';
        $replyModel='FReply';
        $title='Financial Memo Summary';
        $first='Reference no.';
        $firstVal=$module_info['FMemo']['ref_no'];
        $second='To';
        if (!empty($module_info['FMemoTo'])){ 
          $temp=array();
          foreach ($module_info['FMemoTo'] as $to) {

            $temp[]=$to['User']['staff_name'].' ('.$to['User']['designation'].')';
          }
          $tos=implode(', ',$temp); 
          
          }
        $secondVal=$tos;
        $third='From';
        $thirdVal=$module_info['User']['staff_name'].' ('.$module_info['User']['designation'].', '.$module_info['User']['Department']['department_name'].')';
        //$thirdVal=$module_info['User']['Department']['department_name'];
        $fourth='Subject';
        $fourthVal=$module_info['FMemo']['subject'];
       

    }
    elseif ($type=='nonfinancial'){ 
        $commentModel='NfComment';
        $replyModel='NfReply';
        $title='Non-Financial Memo Summary';
        $first='Reference no.';
        $firstVal=$module_info['NfMemo']['ref_no'];
        $second='To';
        if (!empty($module_info['NfMemoTo'])){ 
          $temp=array();
          foreach ($module_info['NfMemoTo'] as $to) {

            $temp[]=$to['User']['staff_name'].' ('.$to['User']['designation'].')';
          }
          $tos=implode(', ',$temp); 
          
          }
        $secondVal=$tos;
        $third='From';
        $thirdVal=$module_info['User']['staff_name'].' ('.$module_info['User']['designation'].', '.$module_info['User']['Department']['department_name'].')';
        $fourth='Subject';
        $fourthVal=$module_info['NfMemo']['subject'];
        

    }
  ?>
  <section class="panel">
    <header class="panel-heading">
      
      <h4><?php echo $title;?>
                <span class="tools pull-right">
                    <a href="javascript:;" class="fa fa-chevron-down"></a>
                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
                </span>
            </h4>
    </header>
    
    <div class="panel-body">
      
      <div class="row">
        <br/>
        <div class="col-lg-12"> 
              <table class="table table-bordered table-striped table-condensed">
                <tbody>
                  <tr><th style="width:20%" ><?php echo $first; ?></th><td><b><?php echo $firstVal;?></b></td></tr>
                  <tr><th style="width:20%"><?php echo $second; ?></th><td><b><?php echo $secondVal;?></b></td></tr>
                  <tr><th style="width:20%"><?php echo $third; ?></th><td><b><?php echo $thirdVal;?></b></td></tr>
                  <tr><th style="width:20%"><?php echo $fourth;?></th><td><b><?php echo $fourthVal;?></b></td></tr>
                </tbody>
              </table>
            </div>
          </div>
         
        </div>
  </section>
  <div class="chat-room">
      <aside class="left-side">
          <div class="user-head2">
              <i class="fa fa-comments-o"></i>
              <h7><strong>Comment List</strong></h7>

          </div>
          
          <ul class="chat-list chat-user">
              <?php 


                   
                if (!empty($commentInfo)):

                  foreach ($commentInfo as $value) {
              ?>
              <li <?php if (!empty($comment_id)&&($this->Mems->decrypt($comment_id)==$value['comment_id'])) echo 'class="active"';?>>
                  <?php
                    // $tmp=array();
                    // foreach ($value['assign'] as $val) {
                    //   $tmp[]=$val['User']['staff_name'];
                    // }
                    // $assignedUser=implode(', ', $tmp);
                    $created=date('d M Y',strtotime($value['created']));
                    echo $this->Html->link('<span><strong>'.$value['title'].'</strong></span>
                      <span class="text-muted"><br> <b>by</b> '.$value['creator'].'</span>
                    <span class="text-muted pull-right">'.$created.'</span>',array('controller'=>'Comment','action'=>'index',$module_id,$type,$this->Mems->encrypt($value['comment_id'])),array('escape'=>false)); 

                    ?>
                    
              </li>
              
              <?php }
                  else : echo ' <h5 class="pull-left"> No comment yet.</h5>';
                endif; ?>
          </ul>

      </aside>
      <aside class="mid-side">

          <div class="chat-room-head2">
              <h7><b>Selected Comment</b></h7> 
              <span class='pull-right'><a href="#new" data-toggle="modal" class="chat-tools2 btn-default tooltips" data-toggle="tooltip" data-placement="left" data-original-title="Add new comment" ><i class="fa fa-plus-square"></i> </a></span>
          </div>
          <div class="invite-row">
              <h5 class="pull-left"><strong><?php echo $subject; ?></strong></h5>
                <div class='btn-group btn-group-xs pull-right'>
                  <?php
                      echo $this->Html->link('Back',array('action'=>'back',$module_id,$type),array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'top','data-toggle'=>'tooltip','data-original-title'=>'Back'));
                  ?>
                </div>
                <?php if (!empty($reply) && ($activeUser['user_id']==$creatorId)) :  ?>
                <div class='btn-group btn-group-xs pull-right'>
                  <?php
                    echo $this->Html->link('<i class="fa fa-times"></i>',array('action'=>'delete',$module_id,$type,$comment_id),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'top','data-toggle'=>'tooltip','data-original-title'=>'Delete'),"Are you sure? This action cannot be undone.");
                  ?>
                </div>
              <?php endif; ?>
              
          </div>

          <?php 

            if (!empty($reply)): 
          ?>

                  <div class="panel-body">
                		<div class="timeline-messages">
                      <?php foreach ($reply as $key=>$value) {   ?>
                			<!-- Comment -->
                			<div class="msg-time-chat">
                        <a href="#" class="message-img"><img class="avatar" src="<?php echo ACCESS_URL."img/Faces_Users-13.png"; ?>" alt=""></a>

                        <?php if ($key==0) echo '<div class="message-body msg-out">'; else echo '<div class="message-body msg-in">'; ?>
                          <span class="arrow"></span>
                          <div class="text">
                            <p class="attribution"><a href="#"><?php echo $value['staff_name']?></a> at <?php echo date('h:ia, d M Y',strtotime($value['created']));?></p>
                            <p><?php echo ($value['reply']);?> </p>
                            <br>
                           
                             <?php 
                             if (!empty($value['attachment'])){
                             ?>
                              <!-- <p>Attachment</p> -->
                              <table class="table">
                                <thead>
                                  <th>Attachment</th>
                                  <th></th>
                                </thead>
                                <tbody>
                                  
                                  <?php foreach ($value['attachment'] as $val) {
                                    $tmpName=explode('___',$val['filename']);
                                    if (count($tmpName)>1)
                                      $filename=$tmpName[1];
                                    else
                                      $filename=$val['filename'];

                                  ?>
                                  <tr>
                                      
                                      <td>
                                        <?php echo $filename; ?>
                                      </td>
                                      <td style="width:15%"> 
                                        <div class="btn-group pull-right">

                                          <?php 
                                           echo $this->Html->link('<i class="fa fa-cloud-download"></i>',array('controller'=>'Comment','action'=>'downloadAttachment',$type,$this->Mems->encrypt($val['attachment_id'])),array('escape'=>false,'class'=>"btn btn-xs btn-primary btn-xs tooltips data-toggle='tooltip' data-placement='top' data-original-title='Download'"));
                                           
                                          ?>
                                        </div>
                                      </td>
                                  </tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                               <?php
                               }
                               ?>
                          </div>
                        </div>
                      </div>

                			<!-- /comment -->
                      <?php } ?>
                		</div>
                    
                    <?php echo $this->Form->create($replyModel,array('url'=>array('controller'=>'comment','action'=>'reply',$module_id,$type),'class'=>'form-horizontal','type'=>'file','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                      ?>
                		<div class="chat-form">
                     
                			<div class="input-cont ">
                				<?php 
                          echo $this->Form->input("$replyModel.reply",array('type'=>'textarea','class'=>'wysihtml5 form-control','rows'=>'10'));
                          echo $this->Form->input("$replyModel.comment_id",array('type'=>'hidden','value'=>$comment_id));
                        ?>
                			</div>
                      <!-- start Attachment -->
                      <div class="form-group">
                       <div class="col-sm-2">
                          <label class="control-label">Attach File (optional)<br/></label>
                          </div>
                        <div class="col-sm-10"> 
                           <?php 
                            echo $this->Form->input("$replyModel.files.",array('type'=>'file','class'=>'file','multiple','accept'=>'application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf','id'=>'vendor-files'));
                            echo '<small>Max file size : 10Mb | File type : pdf, word, excel </small>'; 
                            ?>
                       
                        </div>
                      </div>
                    <!-- end attachment   -->
                			
                				<div class="pull-right chat-features">
                					<?php
                            echo $this->Form->button('Add Reply',array('type'=>'submit','class'=>'btn btn-success'));
                            echo $this->Html->link("<li class='btn btn-danger'>Back</li>",array('action'=>'back',$module_id,$type),array('escape'=>false));

                          ?>
                				
                				</div>
                			
                      
                		</div>
                    <?php
                      echo $this->Form->end();
                    ?>
                    
                	</div>
        <?php 
            endif; ?>
      </aside>
      
      
  </div>
  <!-- page end-->
  <div aria-hidden="true" aria-labelledby="new" role="dialog" tabindex="-1" id="new" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header blue-bg">
                  <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                  <h4 class="modal-title">New Comment</h4>
              </div>
              <div class="modal-body">

                  <?php
                    echo $this->Form->create($commentModel,array('url'=>array('controller'=>'comment','action'=>'add',$module_id,$type),'class'=>'form-horizontal','type'=>'file','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                   ?>
                      
                  	  <!-- <div class="form-group">
                          <label class="col-lg-2 col-sm-2 control-label">Title</label>
                          <div class="col-lg-10">
                            <?php
                              echo $this->Form->input("$commentModel.title",array('type'=>'text','class'=>'form-control'));
                            ?>
                          </div>
                      </div> -->
                      <div class="form-group">
                          <label class="col-lg-2 col-sm-2 control-label">Comment</label>
                          <div class="col-lg-10">
                            <?php 
                              echo $this->Form->input("$replyModel.reply",array('type'=>'textarea','class'=>'wysihtml5 form-control','rows'=>'10'));
                              echo $this->Form->input("$commentModel.memo_id",array('type'=>'hidden','value'=>$module_id));
                              
                            ?>
                          </div>

                      </div>
                    <!-- start Attachment -->
                      <div class="form-group">
                       <div class="col-sm-3">
                          <label class="control-label">Attach File (optional)<br/></label>
                          </div>
                        <div class="col-sm-9"> 
                           <?php 
                            echo $this->Form->input("$replyModel.files.",array('type'=>'file','class'=>'file','multiple','accept'=>'application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf','id'=>'vendor-files'));
                            echo '<small>Max file size : 10Mb | File type : pdf, word, excel </small>'; 
                            ?>
                       
                        </div>
                      </div>
                    <!-- end attachment   -->
                     
                     <div class="modal-footer text-center">
                      <?php
                        echo $this->Form->button('Add',array('type'=>'submit','class'=>'btn btn-success','id'=>'secondButton'));
                      ?>
                      <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
                    </div>
                  <?php echo $this->Form->end(); ?>
              </div>
          </div>
      </div>
  </div>

</section>

<script type="text/javascript">
    $('#vendor-files').bind('change', function() {
      var permitted=['application/msword','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf'];
      for (var i = 0; i < this.files.length; i++) {
        if ($.inArray(this.files[i].type, permitted) == -1){
          alert('Invalid file type for '+this.files[i].name+'. Only pdf, word, excel are allowed.');
          break;
        }
        if (this.files[i].size > 10485760){
          alert(this.files[i].name+' is too big. Maximum size per file is 10Mb');
          break;
        }
      };
    });
</script>