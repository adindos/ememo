

<script type="text/javascript">

$(document).ready(function() {
  
    $(document).on('click', '#budgetButton', function () {
        $.post($("#memoForm").attr("action"), $("#memoForm").serialize(),
          function() {});
      });

    $(document).on('click', '#staffButton', function () {
        $.post($("#memoForm").attr("action"), $("#memoForm").serialize(),
          function() {});
      });

    $(document).on('click', '#memoButton', function () {
         //alert();
         var memo_id="<?php echo $memo_id;?>";
         var edit="<?php echo $edit;?>";
          $("#memoForm").attr("action", "<?php echo $this->webroot ?>/NfMemo/validateMemo/" + memo_id +"/"+ edit);
      });
      
  });

</script>
<section class="mems-content">
    <div class="row">
                <div class="col-sm-12">
                  <section class="panel">
                    <header class="panel-heading">
                       NON FINANCIAL MEMO  
                    </header>
                    <div class="panel-body">
                                      
                        <?php                   

                        echo $this->Form->create('NfMemo',array('url'=>array('controller'=>'NfMemo','action'=>'addMemo',$memo_id,$edit),'class'=>'form-horizontal tasi-form','type'=>'file','id'=>'memoForm','inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false,)));

                        ?>

                        <table class="table table-hover">
                          <tr>
                            <td>
                              
                              <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>Reference no. </b></label>
                                <div class="col-sm-8">                                    
                                     <?php
                                        if (empty($this->request->data['NfMemo']['ref_no'])) 
                                          echo "Will be auto generated when the form is submitted";
                                        else
                                          echo $this->request->data['NfMemo']['ref_no'];
                                        
                                      ?> 
                                     <!--  <?php echo "memo_id=".$memo_id;?> -->                                        
                                </div>
                              </div>


                              <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>To</b></label>
                                <div class="col-sm-10">
                                  <span class='pull-left'><a href="#addStaff" data-toggle="modal" class="btn btn-round btn-primary btn-xs margin-left" data-toggle="tooltip" data-placement="top" data-original-title="Add staff" ><i class="fa fa-plus"></i> Add Staff</a></span>
                                    
                                </div>

                              </div>

                             <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>From</b></label>
                                <div class="col-sm-8">                                   
                                  <b><?php echo $deptName ['Department']['department_name'];
    								                        echo $this->Form->input('NfMemo.department_id',array('type'=>'hidden','id'=>'autoexpanding','class'=>'form-control'));
                                  ?></b>
                                </div>
                              </div>

                              <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>Subject</b></label>
                                <div class="col-sm-8">
                                     <?php
                                      echo $this->Form->input('NfMemo.subject',array('type'=>'text','id'=>'autoexpanding','class'=>'form-control'));
                                    ?>
                                </div>
                              </div>

                              <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>Date Required</b></label>
                                <div class="col-sm-8">									 
    							
            			                <?php
            			                echo $this->Form->input('NfMemo.date_required',array('type'=>'text','class'=>'form-control datepicker','style'=>'width:260px'));
            			                ?>
                                                   
                                </div>
                              </div>
                            
                            </td>                         
                          </tr>                       
                          <tr>
                            <td>
                                <div class="form-group">
                                <label class="col-sm-3 col-sm-3 control-label"><b>1. Introduction</b></label>
                                <div class="col-sm-10">
                                     <div class="form-group">                                                   
                                        <div class="col-md-12">                                            
                                            <?php
                                              echo $this->Form->input('NfMemo.introduction',array('type'=>'textarea','id'=>'autoexpanding','class'=>'ckeditor form-control'));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </td>                          
                          </tr>
                          <tr>
                            <td>
                              <div class="form-group">
                                <label class="col-sm-3 col-sm-3 control-label"><b>2. Subject Matters</b></label>
                                <div class="col-sm-10">
                                     <div class="form-group">                                                   
                                        <div class="col-md-12">
                                            <?php
                                              echo $this->Form->input('NfMemo.subject_matters',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </td>                          
                          </tr>
                          <tr>
                            <td>
                              <div class="form-group">
                                <label class="col-sm-3 col-sm-3 control-label"><b>3. Recommendation/Conclusion</b></label>
                                <div class="col-sm-10">
                                     <div class="form-group">                                                   
                                        <div class="col-md-12">
                                            <?php
                                              echo $this->Form->input('NfMemo.recommendation',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </td>                          
                          </tr>
                          <tr>
                            <td>
                               <div class="form-group">
                                  <label class="col-sm-2 col-sm-2 control-label"><b>Attach File(s)</b><br/></label>
                                  <div class="col-sm-10">  
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
                                                    <div class="btn-group pull-left">

                                                      <?php 
                                                       echo $this->Html->link('<i class="fa fa-cloud-download"></i>',array('controller'=>'NfMemo2','action'=>'downloadAttachment',$this->Mems->encrypt($value['attachment_id'])),array('escape'=>false,'class'=>"btn btn-xs btn-primary btn-xs tooltips data-toggle='tooltip' data-placement='top' data-original-title='Download'"));
                                                       
                                                       echo $this->Html->link('<i class="fa fa fa-times"></i>',array('controller'=>'NfMemo2','action'=>'deleteAttachment',$this->Mems->encrypt($value['attachment_id'])),array('escape'=>false,'class'=>"btn btn-xs btn-danger btn-xs tooltips data-toggle='tooltip' data-placement='top' data-original-title='Remove'"),'Are you sure? This action cannot be undone.');

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
                            </td>
                          </tr>
                        </table>

                       <div class="form-group" style="text-align:center">                              
                        

                          <?php echo $this->Html->link("<i class='fa fa-times'></i> Cancel",array('controller'=>'user','action'=>'userDashboard'),array('escape'=>false,'class'=>"btn btn-default")); ?>

                           <?php echo $this->Form->button("<i class='fa fa-save'></i> Proceed",array('type'=>'submit','class'=>'btn btn-success','id'=>'memoButton'));
                           ?>

                        </div>
                          <?php
                           echo $this->Form->end();
                          ?>
                                              
                      </div>
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
                  <h4 class="modal-title">Add Staff to Send Memo to</h4>
              </div>
              <div class="modal-body">

                  <?php
                     echo $this->Form->create('NfMemoTo',array('url'=>array('controller'=>'NfMemo','action'=>'addStaff',$memo_id,$edit),'class'=>'form-horizontal','type'=>'file','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                    //echo $this->input->hidden('BStatus.budget_id',array('value'=>$budgetID));
                  ?>
                        
                  <div class="form-group">
                      <label class="col-lg-2 col-sm-2 control-label">Add Staff</label>
                      <div class="col-lg-10">
                        <?php
                            echo $this->Form->input('NfMemoTo.selectedStaff.',array('type'=>'select','options'=>$staffs,'class'=>'select2-multiple full-width','multiple','empty'=>'Select staff(s)','default'=>$selected));
                        ?>
                      </div>

                  </div>
                  <br/><br/><br/>
                  <div class="modal-footer text-center">
                    <?php
                      echo $this->Form->button('Add',array('type'=>'submit','class'=>'btn btn-danger','id'=>'staffButton'));
                    ?>
                    <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
                  </div> 

                  <?php echo $this->Form->end(); ?>
              </div>
          </div>
      </div>
  </div>
  </section>
                   
                    

              