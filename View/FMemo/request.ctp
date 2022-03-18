<?php
  $decryptedMemoID = $this->Mems->decrypt($memo_id);

  $this->Html->addCrumb('Financial Memo', array('controller' => 'fMemo', 'action' => 'index'));
  if(empty($this->request->data['FMemo']['ref_no']))
    $this->Html->addCrumb('Temporary Ref. No : 000/'.$decryptedMemoID,array('controller' => 'fMemo', 'action' => 'dashboard',$memo_id));
  else
    $this->Html->addCrumb('Ref. No : '.$this->request->data['FMemo']['ref_no'],array('controller' => 'fMemo', 'action' => 'dashboard',$memo_id));
  $this->Html->addCrumb('Request', $this->here,array('class'=>'active'));
?>

<script type="text/javascript">

$(document).ready(function() {
    $('.edit-budget-amount').on('click',function(){
    var memo_budget_id = $(this).data('memo_budget_id');
    var amount = $(this).data('amount');
    console.log(memo_budget_id);
    

    $('#memo_budget_idE').val(memo_budget_id);
    $('#amount').val(amount);
    
  });
    $('.add-unbudgeted').on('click',function(){
    var memo_budget_id = $(this).data('memo_budget_id');
    var budget_id = $(this).data('budget_id');
    var unbudgeted_amount = $(this).data('unbudgeted_amount');
    console.log(memo_budget_id);
    

    $('#memo_budget_id').val(memo_budget_id);
    $('#budget_id').val(budget_id);
    $('#unbudgeted_amount').val(unbudgeted_amount);
    
  });

  $('.add-transfer').on('click',function(){
    var memo_budget_id = $(this).data('memo_budget_id');
    var budget_id = $(this).data('budget_id');
    var transferred_amount = $(this).data('transferred_amount');
   

    $('#memo_budget_idT').val(memo_budget_id);
    $('#budget_idT').val(budget_id);
    $('#transferred_amount').val(transferred_amount);
    
  });

    var deptID="<?php echo $department_id;?>";
    // console.log($("#memoForm").serialize());

    $(document).on('click', '#budgetButton', function () {
    	// save TinyMCE instances before serialize -- to fix textarea not grabbed
    	tinyMCE.triggerSave();
        $.post($("#memoForm").attr("action"), $("#memoForm").serialize(),
          function() {

          });
      });

    $(document).on('click', '#staffButton', function () {
    	// save TinyMCE instances before serialize -- to fix textarea not grabbed
    	tinyMCE.triggerSave();
        $.post($("#memoForm").attr("action"), $("#memoForm").serialize(),
          function() {

          });
        //console.log($("#memoForm").serialize());
      });

    $(document).on('click', '#memoButton', function () {
         //alert();
         var memo_id="<?php echo $this->Mems->decrypt($memo_id);?>";
         var edit="<?php echo $edit;?>";
          $("#memoForm").attr("action", "<?php echo ACCESS_URL ?>/FMemo/validateMemo/" + memo_id +"/"+ edit);
      });

    $('#deptSelect').on('change',function(){
        var deptVal = $(this).val();
        $('#itemSelectT').select2("val",'');
        // var budgetVal = $(this).val('budget_idT');alert(deptVal);
        var budgetVal = document.getElementById("budget_idT").value;
            $.ajax({
                url : "<?php echo ACCESS_URL ?>/FMemo/deptChange/"+budgetVal+"/"+deptVal,
                dataType: 'json',
                data : "data",
                success: function (data){
                     var options1 = "<option value=''>Select budget items</option>";
                    $('#itemSelectT').html('');
                    if(data.length > 0){//alert(deptID);
                        // console.log(data);
                        $.each(data,function(key,object){
                             //console.log(object.item_id);
                            
                             options1 = options1 + "<option value='"+object.item_amount_id+"'>"+object.item+" (RM"+Number(object.balance).toFixed(2)+")</option>";
                                $('#itemSelectT').html(options1);
                            
                        })
                        
                        // refresh the dropdown
                        // if ($('.selectpicker').length) 
                        //     $('.selectpicker').selectpicker('refresh');
                        
                        //$('.required-batch').show();
                    }
                    else{
                      $('#itemSelectT').select2("val",'');
                    }
                }
            });
        });
    $('#yearSelect').on('change',function(){
        var yearVal = $(this).val();
            //var yearVal = document.getElementById("yearSelect").value;
            $('#itemSelect').select2("val",'');

            $.ajax({
                url : "<?php echo ACCESS_URL ?>/FMemo/yearChange/"+yearVal+"/"+deptID,
                dataType: 'json',
                data : "data",
                success: function (data){
                     var options1 = "<option value=''>Select budget items</option>";
                    $('#itemSelect').html('');
                    if(data.length > 0){//alert(deptID);
                        // console.log(data);
                        $.each(data,function(key,object){
                             //console.log(object.item_id);
                            
                             options1 = options1 + "<option value='"+object.item_amount_id+"'>"+object.item+" (RM"+Number(object.balance).toFixed(2)+")</option>";
                                $('#itemSelect').html(options1);
                            
                        })
                        
                        // refresh the dropdown
                        // if ($('.selectpicker').length) 
                        //     $('.selectpicker').selectpicker('refresh');
                        
                        //$('.required-batch').show();
                    }
                    else{
                      $('#itemSelect').select2("val",'');
                      // $('#itemSelect').select2();
                    }
                }
            });
        
           
        });

      
  });

</script>




<section class="mems-content">
 <div class="row">
    <div class="col-sm-12">
      <section class="panel">
        <header class="panel-heading">
           FINANCIAL MEMO FORM         
        </header>
        <div class="panel-body">
          
          <?php
          echo $this->Form->create('FMemo',array('url'=>array('controller'=>'FMemo','action'=>'addMemo',$memo_id,$edit),'class'=>'form-horizontal tasi-form','type'=>'file','id'=>'memoForm','inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false)));

          ?>
           
          <div class="form-group">
              <div class="col-sm-3">
            <label class="control-label"><b>Reference no. </b></label>
            </div>
            <div class="col-sm-9">                                
                <?php
                  if (empty($this->request->data['FMemo']['ref_no'])) 
                    echo "Will be auto generated when the form is submitted";
                  else
                    echo $this->request->data['FMemo']['ref_no'];
                  
                ?>                                     
            </div>
          </div>

          <div class="form-group">
             <div class="col-sm-3">
            <label class="control-label"><b>To *</b>
           </label>

             <button type="button" data-original-title="TO" 
                      data-content="<div>Select the approvers(s) of your request and prioritise them in ascending order.</div>"     
                      data-placement="top" data-trigger="hover" class="btn btn-round bg-primary btn-xs popovers-with-html"><i class="fa fa-question-circle"></i>
                 </button>

           </div>
            <div class="col-sm-9">
              <span class='pull-left'><a href="#addStaff" data-toggle="modal" class="btn btn-round bg-primary tooltips btn-xs margin-left" data-toggle="tooltip" data-placement="top" data-original-title="Add staff" ><i class="fa fa-plus"></i><?php if (!empty($selectedStaff)) echo " Update Approvers"; else echo " Add Approvers"; ?> </a></span>
              <br/><br/>

              <?php if (!empty($selectedStaff)){ 
                              $temp=array();//debug($selectedStaff);exit;
                      foreach ($selectedStaff as $to) {

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
                  //echo $this->Form->input('FMemo.department_id',array('type'=>'hidden','class'=>'form-control'));
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
                  echo $this->Form->input('FMemo.subject',array('type'=>'text','class'=>'form-control','required'));
                ?>
            </div>
          </div>

          


          <div class="form-group">
              <div class="col-sm-3">
            <label class="control-label"><b>Date Required</b></label>

            </div>
            <div class="col-sm-9">                  
              <?php
              echo $this->Form->input('FMemo.date_required',array('type'=>'text','class'=>'form-control datepicker','style'=>'width:260px'));
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
                  echo $this->Form->input('FMemo.introduction',array('type'=>'textarea','class'=>'form-control'));
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
                echo $this->Form->input('FMemo.subject_matters',array('type'=>'textarea','class'=>'form-control'));
              ?>
            </div>
          </div>

          <div class="form-group">
          <div class="col-sm-3">
            <label class="control-label"><b>Budget Summary *</b></label>
            </div>
            <div class="col-sm-9">
              
              <span class='pull-left'><a href="#addBudget" data-toggle="modal" class="btn btn-round bg-primary btn-xs tooltips margin-left" data-toggle="tooltip" data-placement="top" data-original-title="Add Budget" ><i class="fa fa-plus"></i> Add Budget Item</a></span>
              
              <br/><br/>
              <table class="table table-bordered table-condensed" style="width: 100%;">

                <thead>
                    <tr class="unitar-blue-bg">
                        <th scope="col" width="5%" style="text-align:center">No.</th>
                        <th scope="col" width="10%" style="text-align:center">Budget Year</th>
                        <!-- <th scope="col" width="20%" style="text-align:center">Quarter</th> -->
                        <th scope="col" width="30%" style="text-align:left">Budget Item</th>
                        <th scope="col" width="15%" style="text-align:left">Unbudgeted</th>
                        
                        <th scope="col" width="15%" style="text-align:left">Budget Transfer</th>
                        <th scope="col" width="10%" style="text-align:left"> Amount (RM)</th>
                        <th scope="col" width="15%" style="text-align:center">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        if(!empty($this->request->data['FMemoBudget'])):
                            $counter=1;
                          $total=0;
                            //debug($items);exit();
                            foreach ($this->request->data['FMemoBudget'] as $value) {
                                $total+=$value['amount'];
                        ?>
                                <tr>
                                    <td style="text-align: center"><b><?php echo __($counter);  ?></b></td>
                                    <td style="text-align: center"> <?php echo __(nl2br($value['Budget']['year'])); ?></td>
                                    
                                    <td style="text-align: left;"><?php echo ($value['BItemAmount']['Item']['code_item']);?></td>
                                    <td style="text-align: left;">

                                      <?php 

                                        if (!empty($value['unbudgeted_amount'])):
                                          echo 'RM'.number_format($value['unbudgeted_amount'],2,".",",");
                                          echo "<br><small><b>Unbudgeted</b></small>";

                                        endif;
                                      ?>
                                        
                                      </td>
                                      <td style="text-align: left;">

                                      <?php 

                                          if (!empty($value['transferred_item_amount_id'])){
                                              
                                              echo 'RM'.number_format($value['transferred_amount'],2,".",",");

                                              echo "<br><small><b>Budget transfer from : <br>".$value['BItemAmountTransfer']['Item']['item'].' ('.$value['BItemAmountTransfer']['BDepartment']['Department']['department_shortform'].')</b></small>';
                                          }

                                      
                                      ?>
                                        
                                      </td>
                                    <td style="text-align: left"> 
                                      <?php 
                                        echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$value['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
                                          echo "&nbsp;&nbsp;";

                                        echo __(number_format($value['amount'],2,".",",")) ;

                                      ?>
                                      
                                    </td>
                                    <td style="text-align: center">
                                        <!-- <div class="btn-group"> -->

                                          <?php 
                                           
                                           echo $this->Html->link('<i class="fa fa fa-times"></i>',array('controller'=>'FMemo','action'=>'deleteBudget',$this->Mems->encrypt($value['memo_budget_id'])),array('escape'=>false,'class'=>"btn btn-xs btn-danger btn-xs tooltips data-toggle='tooltip' data-placement='top' ",'data-original-title'=>'Remove'),'Are you sure? This action cannot be undone.');
                                              
                                            $memo_budget_id=$value['memo_budget_id'];
                                            $amount=$value['amount'];

                                           echo $this->Html->link("<i class='fa fa-pencil'></i>",'#modal-edit-budget-amount',array('escape'=>false,'class'=>"btn btn-info btn-xs edit-budget-amount tooltips data-toggle='tooltip' data-placement='top'",'data-original-title'=>'Edit','data-toggle'=>'modal','data-amount'=>$amount,'data-memo_budget_id'=>$memo_budget_id));

                                           if ($financeFlag):
                                              $unbudgeted_amount=$value['unbudgeted_amount'];
                                              $transferred_amount=$value['transferred_amount'];
                                              $budget_id=$value['budget_id'];

                                               echo $this->Html->link("<b>U</b>",'#modal-unbudgeted',array('escape'=>false,'class'=>"btn btn-default btn-xs add-unbudgeted tooltips data-toggle='tooltip' data-placement='top'",'data-original-title'=>'Unbudgeted','data-toggle'=>'modal','data-unbudgeted_amount'=>$unbudgeted_amount,'data-memo_budget_id'=>$memo_budget_id,'data-budget_id'=>$budget_id));

                                                echo $this->Html->link("<b>T</b>",'#modal-transfer',array('escape'=>false,'class'=>"btn btn-primary btn-xs add-transfer tooltips data-toggle='tooltip' data-placement='top'",'data-original-title'=>'Budget Transfer','data-toggle'=>'modal','data-transferred_amount'=>$transferred_amount,'data-memo_budget_id'=>$memo_budget_id,'data-budget_id'=>$budget_id));

                                           endif;

                                          ?>
                                        <!-- </div> -->
                                    </td>
                                    
                                   
                                </tr>
                           <?php
                            $counter++;
                            }
                            endif;
                           ?>
                                  
                </tbody>

                <tfoot style="font-weight:bold">
                    <tr class="info">                                                
                       <td colspan="5" style="text-align:right"> TOTAL (RM)</td>
                        <td colspan="2"><b><?php if (!empty($total)) echo number_format($total,2,".",","); ?></b></td>
                    </tr>
                </tfoot>

              </table>  
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
                echo $this->Form->input('FMemo.recommendation',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
              ?>
            </div>
          </div>
          <div class="form-group">
          <div class="col-sm-3">
            <label class="control-label"><b>Vendor Type</b></label>
            </div>
            <div class="col-sm-9">                   
              <div class="radios">
                <?php
                  $options=array(1=>' Approved Vendor',0=>' New Vendor');
                  echo $this->Form->input('FMemo.vendor',array('legend'=>false,'type'=>'radio','options'=>$options,'separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;',));
                ?>
              </div>
            </div>
          </div>
          

          <div class="form-group">
          <div class="col-sm-3">
            <label class="control-label"><b>Attach File(s)</b><br/> <small>Attach at least 3 quotations for new vendor </small></label>
            </div>
            <div class="col-sm-9">  
            <?php 
              echo $this->Form->input('FVendorAttachment.files.',array('type'=>'file','class'=>'file','multiple','accept'=>'application/msword,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf','id'=>'vendor-files'));
              echo '<small>Max file size : 10Mb | File type : pdf, word, excel </small>'; 
              if (!empty($this->request->data['FVendorAttachment'])){

                  ?>
                  <table class="table">
                    <thead>
                      <th colspan="2" >File</th>
                      <th>Action</th>
                    </thead>
                    <tbody>
                      
                      <?php foreach ($this->request->data['FVendorAttachment'] as $value) {
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
                               echo $this->Html->link('<i class="fa fa-cloud-download"></i>',array('controller'=>'FMemo','action'=>'downloadAttachment',$this->Mems->encrypt($value['attachment_id'])),array('escape'=>false,'class'=>"btn btn-xs btn-primary btn-xs tooltips data-toggle='tooltip' data-placement='top' data-original-title='Download'"));
                               
                               echo $this->Html->link('<i class="fa fa fa-times"></i>',array('controller'=>'FMemo','action'=>'deleteAttachment',$this->Mems->encrypt($value['attachment_id'])),array('escape'=>false,'class'=>"btn btn-xs btn-danger btn-xs tooltips data-toggle='tooltip' data-placement='top' data-original-title='Remove'"),'Are you sure? This action cannot be undone.');

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
          <div class="form-group">
          <div class="col-sm-3">
            <label class="control-label"><b>Vendor Remark </b><small>(if any)</small></label>
          </div>
            <div class="col-sm-9">
              <?php
                echo $this->Form->input('FMemo.vendor_remark',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
              ?>
            </div>
          </div>
          <div class="form-group" style="text-align:center">                              
            

             <?php if (($activeUser['user_id']==$this->request->data['FMemo']['user_id'])&&($this->request->data['FMemo']['submission_no']==0)) echo $this->Form->button("<i class='fa fa-save'></i> Save",array('type'=>'submit','class'=>'btn btn-success','name'=>'save'));
             ?>

             <?php 

             if($this->request->data['FMemo']['submission_no']==0) 
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
<!-- modal for memo budget-->
    <div aria-hidden="true" aria-labelledby="addBudget" role="dialog" tabindex="-1" id="addBudget" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header blue-bg">
                  <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                  <h4 class="modal-title">Add Budget to Memo</h4>
              </div>
              <div class="modal-body">

                  <?php
                    echo $this->Form->create('FMemoBudget',array('url'=>array('controller'=>'FMemo','action'=>'addBudget',$memo_id,$edit),'class'=>'form-horizontal','type'=>'file','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                  
                  //phase2: if budget item, sleect the same year
                  if(!empty($this->request->data['FMemoBudget'])):
                      $years=array();
                      $years[$this->request->data['FMemoBudget'][0]['budget_id']]=$this->request->data['FMemoBudget'][0]['Budget']['year'];
                    endif;
                  ?>  
                      <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Budget year</label>
                          <div class="col-lg-9">
                          <?php
                            echo $this->Form->input('FMemoBudget.budget_id',array('type'=>'select','options'=>$years,'class'=>'select2-sortable full-width','empty'=>'Select budget year','id'=>'yearSelect','required','style'=>'width:100%'));
                          ?>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-lg-3 col-sm-3 control-label">Budget Item</label>
                          <div class="col-lg-9">
                            <?php
                              echo $this->Form->input('FMemoBudget.item_amount_id',array('type'=>'select','class'=>'select2-sortable full-width','empty'=>'Select budget item','id'=>'itemSelect','required','style'=>'width:100%'));
                            ?>
                            <br><small><i class="fa fa-exclamation-circle"></i> Format: Item name (Available balance in RM)</small>
                          </div>

                      </div>
                   
                      <div class="form-group">
                          <label class="col-lg-3 col-sm-3 control-label">Amount</label>
                          <div class="col-lg-9">
                            <div class="input-group">
                              <span class="input-group-addon">RM</span>
                              <?php
                                echo $this->Form->input('FMemoBudget.amount',array('type'=>'number','id'=>'edit-budget-amount','class'=>'form-control','style'=>'width:50%','required','number','min'=>'0','step'=>'1.00'));
                              ?>
                            </div>
                          </div>

                      </div>
                      
                  <div class="modal-footer text-center">
                    <?php
                      echo $this->Form->button('Add',array('type'=>'submit','class'=>'btn btn-success','id'=>'budgetButton'));
                    ?>
                    <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
                  </div> 

                  <?php echo $this->Form->end(); ?>
              </div>
          </div>
      </div>
  </div>

  <!-- modal foradd staff-->
    <div aria-hidden="true" aria-labelledby="addStaff" role="dialog" tabindex="-1" id="addStaff" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header blue-bg">
                  <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                  <h4 class="modal-title">Add Memo Approvers </h4>
              </div>
              <div class="modal-body">

                  <?php
                    echo $this->Form->create('FMemoTo',array('url'=>array('controller'=>'FMemo','action'=>'addStaff',$memo_id,$edit),'class'=>'form-horizontal','type'=>'file','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                    //echo $this->input->hidden('BStatus.budget_id',array('value'=>$budgetID));
                  ?>
                        
                  <div class="form-group">
                      <label class="col-lg-2 col-sm-2 control-label">Add Approvers</label>
                      <div class="col-lg-10">
                        <?php
                            echo $this->Form->input('FMemoTo.selectedStaff.',array('type'=>'select','options'=>$staffs,'class'=>'select2-sortable full-width','multiple','default'=>$selected,'style'=>'width:100%'));
                        ?>
                      </div>

                  </div>
                  
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

  <!-- modal for budget transfer-->

  <div class="modal fade" id="modal-transfer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Add/Edit Budget Transfer</h4>
      </div>
      <?php
        echo $this->Form->create('FMemoBudget',array('url'=>array('controller'=>'FMemo','action'=>'addTransferItem'), 'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),'class'=>'form-horizontal'));
      ?>
      <div class="modal-body ">
        <div class="form-group">
          <label class="col-lg-3 col-sm-3 control-label"> Department (*)</label>
          <div class="col-sm-9">
              <?php
                echo $this->Form->input('FMemoBudget.b_dept_id',array('type'=>'select','options'=>$deptAcad+$deptNonAcad,'class'=>'select2-sortable full-width','style'=>'width:100%','empty'=>'Please select department','id'=>'deptSelect'));
                echo $this->Form->input('FMemoBudget.memo_budget_id',array('type'=>'hidden','id'=>'memo_budget_idT'));
                echo $this->Form->input('FMemoBudget.budget_id',array('type'=>'hidden','id'=>'budget_idT'));

              ?>
              
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 col-sm-3 control-label">Budget Item (*)</label>
          <div class="col-sm-9">
              <?php
                
                 echo $this->Form->input('FMemoBudget.transferred_item_amount_id',array('type'=>'select','class'=>'select2-sortable full-width','id'=>'itemSelectT','style'=>'width:100%','empty'=>'Please select budget item'));

              ?>
              
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 col-sm-3 control-label"> Transfer Amount (*)</label>
          <div class="col-sm-9">
            <div class="input-group">
              <span class="input-group-addon">RM</span>
              <?php
                
                echo $this->Form->input('FMemoBudget.transferred_amount',array('type'=>'number','class'=>'form-control','id'=>'transferred_amount'));
                

              ?>
            </div>
            <small><i class="fa fa-exclamation-circle"></i> Please enter the amount in RM</small>
          
          </div>
        </div>
        
    </div>
        
      <div class="modal-footer text-center">
        <?php
          echo $this->Form->button('Add/Edit Budget Transfer',array('type'=>'submit','class'=>'btn btn-success'));
        ?>
        <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>

      </div>

      <?php
        echo $this->Form->end();
      ?>
    </div>
  </div>
</div>
 <!-- modal for unbudgeted-->

  <div class="modal fade" id="modal-unbudgeted" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Add/Edit Unbudgeted Item</h4>
      </div>
      <?php
        echo $this->Form->create('FMemoBudget',array('url'=>array('controller'=>'FMemo','action'=>'addUnbudgetedItem'), 'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),'class'=>'form-horizontal'));
        
        echo $this->Form->input('FMemoBudget.memo_budget_id',array('type'=>'hidden','id'=>'memo_budget_id'));
        echo $this->Form->input('FMemoBudget.budget_id',array('type'=>'hidden','id'=>'budget_id'));

      ?>
      <div class="modal-body ">
        
        <div class="form-group">
          <label class="col-lg-3 col-sm-3 control-label"> Unbudgeted Amount (*)</label>
          <div class="col-sm-9">
            <div class="input-group">
              <span class="input-group-addon">RM</span>
                  <?php
                    
                    echo $this->Form->input('FMemoBudget.unbudgeted_amount',array('type'=>'number','class'=>'form-control','id'=>'unbudgeted_amount'));
                  ?>
            </div>
            <small><i class="fa fa-exclamation-circle"></i> Please enter the amount in RM</small>
              
          </div>
        </div>
        
      </div>
        
      <div class="modal-footer text-center">
        <?php
          echo $this->Form->button('Add/Edit Unbudgeted Item',array('type'=>'submit','class'=>'btn btn-success'));
        ?>
        <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>

      </div>

      <?php
        echo $this->Form->end();
      ?>
    </div>
  </div>
</div>

<!-- modal for edit budget amount-->

  <div class="modal fade" id="modal-edit-budget-amount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Edit Memo Budget Amount</h4>
      </div>
      <?php
        echo $this->Form->create('FMemoBudget',array('url'=>array('controller'=>'FMemo','action'=>'editMemoBudget'), 'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),'class'=>'form-horizontal'));
        
        echo $this->Form->input('FMemoBudget.memo_budget_id',array('type'=>'hidden','id'=>'memo_budget_idE'));

      ?>
      <div class="modal-body ">
        
        <div class="form-group">
          <label class="col-lg-3 col-sm-3 control-label"> Amount (*)</label>
          <div class="col-sm-9">
            <div class="input-group">
              <span class="input-group-addon">RM</span>
                  <?php
                    
                    echo $this->Form->input('FMemoBudget.amount',array('type'=>'number','class'=>'form-control','id'=>'amount'));
                  ?>
            </div>
            <small><i class="fa fa-exclamation-circle"></i> Please enter the amount in RM</small>
              
          </div>
        </div>
        
      </div>
        
      <div class="modal-footer text-center">
        <?php
          echo $this->Form->button('Save Changes',array('type'=>'submit','class'=>'btn btn-success'));
        ?>
        <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>

      </div>

      <?php
        echo $this->Form->end();
      ?>
    </div>
  </div>
</div>
</section>
    
