<?php
  $decryptedMemoID = $this->Mems->decrypt($memo_id);

  $this->Html->addCrumb('Non-Financial Memo', array('controller' => 'NfMemo2', 'action' => 'index'));
  if(empty($this->request->data['NfMemo']['ref_no']))
     $this->Html->addCrumb('Temporary Ref. No : 000/'.$decryptedMemoID,array('controller' => 'NfMemo2', 'action' => 'dashboard',$memo_id));
   else
      $this->Html->addCrumb('Ref. No : '.$this->request->data['NfMemo']['ref_no'],array('controller' => 'NfMemo2', 'action' => 'dashboard',$memo_id));
  $this->Html->addCrumb('New Request', $this->here,array('class'=>'active'));
?>



<script type="text/javascript">

  $(document).ready(function() {
    var deptID="<?php echo $department_id;?>";
    

    $(document).on('click', '#staffButton', function () {
      // save TinyMCE instances before serialize -- to fix textarea not grabbed
      tinyMCE.triggerSave();
        $.post($("#memoForm").attr("action"), $("#memoForm").serialize(),
          function() {});
      });

    $(document).on('click', '#memoButton', function () {
         //alert();
         var memo_id="<?php echo $this->Mems->decrypt($memo_id);?>";
         var edit="<?php echo $edit;?>";
         //alert(edit);
          $("#memoForm").attr("action", "<?php echo ACCESS_URL ?>/NfMemo2/validateMemo/" + memo_id +"/"+ edit);
      });

  });


</script>
<section class="mems-content">
 <div class="row">
    <div class="col-sm-12">
      <section class="panel">
        <header class="panel-heading">
           NON FINANCIAL MEMO FORM            
        </header>
        <div class="panel-body">
          
          
          <?php
          echo $this->Form->create('NfMemo2',array('url'=>array('controller'=>'NfMemo2','action'=>'addMemo',$encrypted,$edit),'class'=>'form-horizontal tasi-form','type'=>'file','id'=>'memoForm','inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false,)));
          //debug($edit);exit;
          ?>
           
          <div class="form-group">
            <div class="col-sm-3">
            <label class="control-label"><b>Reference no. </b></label>
            </div>
            <div class="col-sm-9">                                    
                <?php
                  if (empty($this->request->data['NfMemo']['ref_no'])) 
                    echo "Will be auto generated when the form is submitted";
                  else
                    echo $this->request->data['NfMemo']['ref_no'];
                  
                ?>                                     
            </div>
          </div>

          <div class="form-group">
           <div class="col-sm-3">
            <label class="control-label"><b>To *</b>
           </label>

             <button  type="button" data-original-title="TO" 
                      data-content="<div>Select the approvers(s) of your request and prioritise them in ascending order.</div>"     
                      data-placement="top" data-trigger="hover" class="btn btn-round bg-primary btn-xs popovers-with-html"><i class="fa fa-question-circle"></i>
                 </button>

           </div>
            <div class="col-sm-9">
              <span class='pull-left'><a href="#addStaff" data-toggle="modal" class="btn btn-round bg-primary btn-xs margin-left" data-toggle="tooltip" data-placement="top" data-original-title="Add staff" ><i class="fa fa-plus"></i><?php if (!empty($selectedStaff)) echo " Update Approvers"; else echo " Add Approvers"; ?> </a></span>
              <br/><br/>

              <?php if (!empty($selectedStaff)){ 
                          $temp=array();//debug($selectedStaff);exit;
                  foreach ($selectedStaff as $to) {

                    //$temp[]=$to['User']['staff_name'];
                      $temp[]=$to['User']['staff_name'].' ('.$to['User']['designation'].')';
                  }
                  $tos=implode(', ',$temp);
                  ?> 
                  <table class="table">
                  
                  <tr><td><b>Added Approvers : </b><br/><?php echo $tos; ?></td></tr>
                  </table>

              <?php
                  }
              ?>  
            </div>

          </div>

         <div class="form-group">
          <div class="col-sm-3">
            <label class="control-label"><b>From</b></label>
            </div>
            <div class="col-sm-9">                                   
              <b>
                <?php 
                  echo $this->request->data['User']['staff_name'].' ('.$this->request->data['User']['designation'].', '.$this->request->data['Department']['department_name'].')';
                  

                  //echo $deptName ['Department']['department_name'];
                  //echo $this->Form->input('NfMemo.department_id',array('type'=>'hidden','class'=>'form-control'));

               
                ?>
              </b>
            </div>
          </div>

          <div class="form-group">
           <div class="col-sm-3">
            <label class="control-label"><b>Subject *</b>  
            </label>
              <button type="button" data-original-title="Subject" 
                        data-content="<div>Provide a clear information about the proposal. Type in the Subject of your Memo in CAPITAL LETTERS. <i>Eg. APPROVAL TO EXPAND PARTNERSHIP WITH XYZ.</i></div>"     
                        data-placement="top" data-trigger="hover" class="btn btn-round bg-primary btn-xs popovers-with-html"><i class="fa fa-question-circle"></i>
                   </button>

            </div>
            <div class="col-sm-9">
                 <?php
                  echo $this->Form->input('NfMemo.subject',array('type'=>'text','id'=>'autoexpanding','class'=>'form-control','required'));
                ?>
            </div>
          </div>
        
          <div class="form-group">
           <div class="col-sm-3">
            <label class="control-label"><b>Date Required</b></label>

            </div>
            <div class="col-sm-9">									 
              <?php
              echo $this->Form->input('NfMemo.date_required',array('type'=>'text','class'=>'form-control datepicker','style'=>'width:260px','required'));
              ?>
            </div>
          </div>

            <div class="form-group">
             <div class="col-sm-3">
            
              <label class="control-label"><b>Introduction</b></label>
                <button  type="button" data-original-title="Introduction" 
                    data-content="  <div>The umbrella statement of your proposal and summary of the entire proposal. <i>E.g. ‘The objective of this memo is to seek for the Management approval on the partnership between UNITAR International University with XYZ in the following areas: (a)…, (b)….. and (d)..’</i></div>"     
                    data-placement="top" data-trigger="hover" class="btn btn-round bg-primary btn-xs popovers-with-html"><i class="fa fa-question-circle"></i>
                </button>
              </div>
             
              <div class="col-sm-9">
                <?php
                  echo $this->Form->input('NfMemo.introduction',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
                ?>
            </div>
          </div>
        
          <div class="form-group">
          <div class="col-sm-3">
            <label class="control-label"><b>Subject Matters</b></label>

             <button  type="button" data-original-title="Subject Matters" 
                    data-content="  <div>Provide background and details information of the proposal. The details should answer the 5W’s and 1H statements. <i>E.g. background information - what was the prior arrangement; who and how it benefits the company; when will it take effect, the financial/budget description and others. Also, specify any supporting documentation and denote these enclosures by typing 'Appendix'.</i></div> "     
                    data-placement="top" data-trigger="hover" class="btn btn-round bg-primary btn-xs popovers-with-html"><i class="fa fa-question-circle"></i>
                </button>
            </div>
            <div class="col-sm-9">
              <?php
                echo $this->Form->input('NfMemo.subject_matters',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
              ?>
            </div>
          </div>
                  
          <div class="form-group">
          <div class="col-sm-3">
            <label class="control-label"><b>Recommendation / Conclusion</b></label>
             <button  type="button" data-original-title="Recommendation / Conclusion" 
                    data-content="  <div>Summary of the proposal's main points and the next actions. </div> "     
                    data-placement="top" data-trigger="hover" class="btn btn-round bg-primary btn-xs popovers-with-html"><i class="fa fa-question-circle"></i>
                </button>
            </div>
            <div class="col-sm-9">
              <?php
                echo $this->Form->input('NfMemo.recommendation',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
              ?>
            </div>
          </div>

           <div class="form-group">
           <div class="col-sm-3">
            <label class="control-label"><b>Attach File(s)</b><br/></label>
            </div>
            <div class="col-sm-9">  
            <?php 
              echo $this->Form->input('NfAttachment.files.',array('type'=>'file','class'=>'file','multiple','accept'=>'application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf','id'=>'vendor-files'));
              echo '<small>Max file size : 10Mb | File type : pdf, word, excel </small>'; 
              if (!empty($this->request->data['NfAttachment'])){

                  ?>
                  <table class="table">
                    <thead>
                      <th colspan="2" >File</th>
                      <th>Action</th>
                    </thead>
                    <tbody>
                      
                      <?php foreach ($this->request->data['NfAttachment'] as $value) {
                        $tmpName=explode('___',$value['filename']);
                        if (count($tmpName)>1)
                          $filename=$tmpName[1];
                        else
                          $filename=$value['filename'];

                      ?>
                      <tr>
                          <td style="width:10%">
                              <i class=" fa fa-list-ol"></i> 
                          </td>
                          <td style="width:75%">
                            <?php echo $filename; ?>
                          </td>
                          <td style="width:15%"> 
                            <!-- <div class="btn-group pull-left"> -->

                              <?php 
                               echo $this->Html->link('<i class="fa fa-cloud-download"></i>',array('controller'=>'NfMemo2','action'=>'downloadAttachment',$this->Mems->encrypt($value['attachment_id'])),array('escape'=>false,'class'=>"btn btn-xs btn-primary btn-xs tooltips data-toggle='tooltip' data-placement='top' data-original-title='Download'"));
                               
                               echo $this->Html->link('<i class="fa fa fa-times"></i>',array('controller'=>'NfMemo2','action'=>'deleteAttachment',$this->Mems->encrypt($value['attachment_id'])),array('escape'=>false,'class'=>"btn btn-xs btn-danger btn-xs tooltips data-toggle='tooltip' data-placement='top' data-original-title='Remove'"),'Are you sure? This action cannot be undone.');

                              ?>
                            <!-- </div> -->
                          </td>
                      </tr>
                      <?php } ?>
                    </tbody>
              </table>
            <?php
               
                
              }
            ?>
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
            </div>
          </div>

          <div class="form-group" style="text-align:center">
               <small> * denotes a required field</small>
               </div>
           <div class="form-group" style="text-align:center">                              
            

             <?php if (($activeUser['user_id']==$this->request->data['NfMemo']['user_id'])&&($this->request->data['NfMemo']['submission_no']==0)) echo $this->Form->button("<i class='fa fa-save'></i> Save",array('type'=>'submit','class'=>'btn btn-success','name'=>'save'));
             ?>

             <?php if($this->request->data['NfMemo']['submission_no']==0) 
                echo $this->Form->button("<i class='fa fa-arrow-circle-right'></i> Next",array('type'=>'submit','class'=>'btn btn-success','id'=>'memoButton'));

              else
                echo $this->Form->button("<i class='fa fa-arrow-circle-right'></i> Resubmit",array('type'=>'submit','class'=>'btn btn-success','id'=>'memoButton'));
             ?>

         </div>
          <?php
           echo $this->Form->end();
          ?>
        </div>
      </section>
    </div>
  </div>

  <!-- modal foradd staff-->
    <div aria-hidden="true" aria-labelledby="addStaff" role="dialog" tabindex="-1" id="addStaff" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                  <h4 class="modal-title">Add Memo Approvers </h4>
              </div>
              <div class="modal-body">

                  <?php
                    echo $this->Form->create('NfMemoTo',array('url'=>array('controller'=>'NfMemo2','action'=>'addStaff',$encrypted,$edit),'class'=>'form-horizontal','type'=>'file','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                    //echo $this->input->hidden('BStatus.budget_id',array('value'=>$budgetID));
                  ?>
                        
                  <div class="form-group">
                      <label class="col-lg-2 col-sm-2 control-label">Add Approvers</label>
                      <div class="col-lg-10">
                        <?php
                            echo $this->Form->input('NfMemoTo.selectedStaff.',array('type'=>'select','options'=>$staffs,'class'=>'select2-sortable full-width','multiple','default'=>$selected,'style'=>'width:100%'));
                        ?>
                      </div>

                  </div>
                  <br/><br/><br/>
                  <div class="modal-footer text-center">
                    <?php
                      if (empty($selected))
                          echo $this->Form->button('Save',array('type'=>'submit','class'=>'btn btn-success','id'=>'staffButton'));
                      else
                          echo $this->Form->button('Update',array('type'=>'submit','class'=>'btn btn-success','id'=>'staffButton'));

                    ?>
                    <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
                  </div> 

                  <?php echo $this->Form->end(); ?>
              </div>
          </div>
      </div>
  </div>
</section>
    
