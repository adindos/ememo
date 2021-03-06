<?php
  if($type == 'budget'){
    $encBudgetID = $this->Mems->encrypt($module_info['Budget']['budget_id']);
    $this->Html->addCrumb(" ( Review Budget ) ".$module_info['Budget']['year'], array('controller' => 'budget', 'action' => 'review',$encBudgetID));
    $this->Html->addCrumb('Remarks', $this->here,array('class'=>'active'));
  }
  elseif( $type == 'financial'){
    $encFinancialID = $this->Mems->encrypt($module_info['FMemo']['memo_id']);
    $this->Html->addCrumb(" ( Review Financial Memo ) Ref. No : ".$module_info['FMemo']['ref_no'], array('controller' => 'fMemo', 'action' => 'review',$encFinancialID));
    $this->Html->addCrumb('Remarks', $this->here,array('class'=>'active'));
  }
  elseif( $type == 'nonfinancial'){
    $encNonFinancialID = $this->Mems->encrypt($module_info['NfMemo']['memo_id']);
    $this->Html->addCrumb(" ( Review Non Financial Memo ) Ref. No : ".$module_info['NfMemo']['ref_no'], array('controller' => 'NfMemo2', 'action' => 'review',$encNonFinancialID));
    $this->Html->addCrumb('Remarks', $this->here,array('class'=>'active'));
  }
?>

<section class="mems-content">
  <!-- page start-->
  <?php 
    if ($type=='budget'){
        $remarkModel='BRemark';
        $feedbackModel='BRemarkFeedback';
        $assignModel='BRemarkAssign';
        $modelId='budget_id';
        $title='Budget Summary';
        $first='Title';
        $firstVal='Budget for '.$module_info['Budget']['year'];
        $second='Year';
        $secondVal=$module_info['Budget']['year'];
        $third='Department';
        $thirdVal=$module_info['User']['Department']['department_name'];
        $fourth='Total Amount';
        $fourthVal='RM'.number_format($total,2,".",",");
      }
    elseif ($type=='financial'){
        $remarkModel='FRemark';
        $feedbackModel='FRemarkFeedback';
        $assignModel='FRemarkAssign';
        $modelId='memo_id';
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
          //echo $tos;
          }
        $secondVal=$tos;
        $third='From';
        $thirdVal=$module_info['User']['staff_name'].' ('.$module_info['User']['designation'].', '.$module_info['User']['Department']['department_name'].')';
        //$thirdVal=$module_info['User']['Department']['department_name'];
        $fourth='Subject';
        $fourthVal=$module_info['FMemo']['subject'];

    }
    elseif ($type=='nonfinancial'){ 
        $remarkModel='NfRemark';
        $feedbackModel='NfRemarkFeedback';
        $assignModel='NfRemarkAssign';
        $modelId='memo_id';
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
          //echo $tos;
          }
        $secondVal=$tos;
        $third='From';
        $thirdVal=$module_info['User']['staff_name'].' ('.$module_info['User']['designation'].', '.$module_info['User']['Department']['department_name'].')';
        //$thirdVal=$module_info['User']['Department']['department_name'];
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
                 <?php if ($type!='budget'): ?>
                  <tr><th style="width:20%"><?php echo $third; ?></th><td><b><?php echo $thirdVal;?></b></td></tr>
                  <tr><th style="width:20%"><?php echo $fourth;?></th><td><b><?php echo $fourthVal;?></b></td></tr>
                <?php endif;?>
                </tbody>
              </table>
            </div>
          </div>
         
        </div>
  </section>
  <div class="chat-room">
      <aside class="left-side">
          <div class="user-head2">
              <i class="fa fa-comments-o" style="font-size=20px !important"></i>
              <h7><b>Remark List</b></h7>

          </div>
          
          <ul class="chat-list chat-user">
              <?php 

                
                   
                if (!empty($remarkInfo)):

                  foreach ($remarkInfo as $value) {
              ?>
              <li <?php if (!empty($remark_id)&&($this->Mems->decrypt($remark_id)==$value['remark_id'])) echo 'class="active"';?>>
                  <?php
                    $tmp=array();
                    foreach ($value['assign'] as $val) {
                      $tmp[]=$val['User']['staff_name'];
                    }
                    $assignedUser=implode(', ', $tmp);
                    $created=date('d M Y',strtotime($value['created']));
                    echo $this->Html->link('<span><strong>'.$value['subject'].'</strong></span>
                    <span class="text-muted"><br>'.$assignedUser.'</span><span class="text-muted pull-right">'.$created.'</span>',array('controller'=>'Remark','action'=>'index',$module_id,$type,$this->Mems->encrypt($value['remark_id'])),array('escape'=>false)); 

                    ?>
                    
              </li>
              
              <?php }
                  else : echo ' <h5 class="pull-left"> No remark yet.</h5>';
                endif; ?>
          </ul>

      </aside>
      <aside class="mid-side">

          <div class="chat-room-head2">
              <h7> <b>Selected Remark</b></h7> 
              <?php if ($add):?>
              <span class='pull-right'><a href="#new" data-toggle="modal" class="chat-tools2 btn-default tooltips" data-toggle="tooltip" data-placement="left" data-original-title="Add new remark" ><i class="fa fa-plus-square"></i> </a></span>
            <?php endif; ?>
          </div>
          <div class="invite-row">
              <h5 class="pull-left"><strong><?php echo $subject; ?></strong></h5>
                <div class='btn-group btn-group-xs pull-right'>
                  <?php
                      echo $this->Html->link('Back',array('action'=>'back',$module_id,$type),array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'top','data-toggle'=>'tooltip','data-original-title'=>'Back'));
                  ?>
                </div>
                <?php if (!empty($feedback) && ($activeUser['user_id']==$creatorId)) :  ?>
                <div class='btn-group btn-group-xs pull-right'>
                  <?php
                    echo $this->Html->link('<i class="fa fa-times"></i>',array('action'=>'delete',$module_id,$type,$remark_id),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'top','data-toggle'=>'tooltip','data-original-title'=>'Delete'),"Are you sure? This action cannot be undone.");
                  ?>
                </div>
              <?php endif; ?>
              
          </div>

          <?php 

            if (!empty($feedback)): 
          ?>

                  <div class="panel-body">
                		<div class="timeline-messages">
                      <?php foreach ($feedback as $key=>$value) { if ($key==0){ ?>
                			<!-- Comment -->
                			<div class="msg-time-chat">
                        <a href="#" class="message-img"><img class="avatar" src="<?php echo ACCESS_URL."img/Faces_Users-13.png"; ?>" alt=""></a>

                        <div class="message-body msg-out">
                          <span class="arrow"></span>
                          <div class="text">
                            <p class="attribution"><a href="#"><?php echo $value['staff_name']?></a> at <?php echo date('h:ia, d M Y',strtotime($value['created']));?></p>
                            <p><?php echo ($value['feedback']);?> </p>
                          </div>


                        </div>
                      </div>
                      <?php } else{?>
                      <div class="msg-time-chat">
                				<a href="#" class="message-img"><img class="avatar" src="<?php echo ACCESS_URL."img/Faces_Users-13.png"; ?>" alt=""></a>

                				<div class="message-body msg-in">
                					<span class="arrow"></span>
                					<div class="text">
                						<p class="attribution"><a href="#"><?php echo $value['staff_name']?></a> at <?php echo date('h:ia, d M Y',strtotime($value['created']));?></p>
                						<p><?php echo ($value['feedback']);?> </p>
                					</div>


                				</div>
                			</div>

                			<!-- /comment -->
                      <?php }} ?>
                		</div>
                    
                    <?php echo $this->Form->create($feedbackModel,array('url'=>array('controller'=>'remark','action'=>'reply',$module_id,$type),'class'=>'form-horizontal','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                      ?>
                		<div class="chat-form">
                     
                			<div class="input-cont ">
                				<?php 
                          echo $this->Form->input("$feedbackModel.feedback",array('type'=>'textarea','class'=>'wysihtml5 form-control','rows'=>'10'));
                          echo $this->Form->input("$feedbackModel.remark_id",array('type'=>'hidden','value'=>$remark_id));
                        ?>
                			</div>
                			
                				<div class="pull-right chat-features">
                					<?php
                            echo $this->Form->button('Add Feedback',array('type'=>'submit','class'=>'btn btn-success'));
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
                  <button aria-hidden="true" data-dismiss="modal" class="close" type="button">??</button>
                  <h4 class="modal-title">New Remark</h4>
              </div>
              <div class="modal-body">

                  <?php
                    echo $this->Form->create($remarkModel,array('url'=>array('controller'=>'remark','action'=>'add',$module_id,$type),'class'=>'form-horizontal','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                   ?>
                      <?php if (!empty($createAs)): ?>
                      <div class="form-group">
                        <label class="col-lg-2 col-sm-2 control-label">Remark as</label>
                        <div class="col-lg-10">
                          <?php
                            echo $this->Form->input("$remarkModel.reviewer_id",array('type'=>'select','options'=>$createAs,'class'=>'select2-sortable full-width','style'=>'width:100%'));
                          ?>
                         </div>
                      </div>
                    <?php endif; ?>
                  	  <!-- <div class="form-group">
                          <label class="col-lg-2 col-sm-2 control-label">Title</label>
                          <div class="col-lg-10">
                            <?php
                              echo $this->Form->input("$remarkModel.subject",array('type'=>'text','class'=>'form-control'));
                            ?>
                          </div>
                      </div> -->
                      <div class="form-group">
                          <label class="col-lg-2 col-sm-2 control-label">Remark</label>
                          <div class="col-lg-10">
                            <?php 
                              echo $this->Form->input("$feedbackModel.feedback",array('type'=>'textarea','class'=>'wysihtml5 form-control','rows'=>'10'));
                              echo $this->Form->input("$remarkModel.$modelId",array('type'=>'hidden','value'=>$module_id));
                              
                            ?>
                          </div>

                      </div>
                      
                      <div class="form-group">
                      	<label class="col-lg-2 col-sm-2 control-label">Send To</label>
                        <div class="col-lg-10">
                          <?php
                          echo $this->Form->input("$assignModel.selectedUser.",array('type'=>'select','options'=>$reviewer,'class'=>'select2-sortable full-width','multiple','style'=>'width:100%'));
                        ?>
                         </div>
                      </div>
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