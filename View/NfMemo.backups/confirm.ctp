 <div class="row">
                <div class="col-sm-12">
                  <section class="panel">
                    <header class="panel-heading">
                       INTERNAL MEMO  
                    </header>
                    <div class="panel-body">
                       <?php
                      	echo $this->Form->create('Nonfin',array('url'=>array('controller'=>'NfMemo','action'=>'confirmAdd'),'class'=>'form-horizontal tasi-form','type'=>'file','inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false)));

 					   	echo $this->Form->hidden('NfMemo.memo_id',array('value'=>$memo_id));
 					  
                      ?>

                        <table class="table table-hover">
	                        <tr>
	                          <td>    
	                            <div class="form-group">
	                             <label class="col-sm-2 col-sm-2 control-label"><b>Remarks</b></label>                                                   
	                                <div class="col-sm-10">
	                                  <div class="form-group">                                                   
	                                    <div class="col-md-10">
	                                     
	                                      <?php
	                                        echo $this->Form->input('NfMemo.remark',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
	                                      ?>
	                                    </div>
	                                  </div>
	                              </div>
	                          </td>
	                        </tr>
	                         <tr>
	                            <td>
	                             <div class="form-group">
	                                <label class="col-sm-2 col-sm-2 control-label"><b>Select Reviewer</b></label>
	                                <div class="col-sm-8">									
		                            
		                            <?php
			   								   						
			   						
			   						echo $this->Form->input('NfReviewer.reviewer',array('type'=>'select','options'=>$reviewers,'class'=>'select2-sortable full-width','multiple'=>'multiple','value'=>$selectedReviewers));		   					


		   							?>
		   							<br><small> Please select the reviewer </small>
	                                </div>
	                              </div>
	                            </td>
	                          </tr>
		                           <tr>
		                            <td>
		                             <div class="form-group">
		                                <label class="col-sm-2 col-sm-2 control-label"><b>Select Recommender</b></label>
		                                <div class="col-sm-8">
		                                     <?php    
		                                                             
		                                         // echo $this->Form->input('NfReviewer.recommender',array('type'=>'select','id'=>'recommender','options'=>$recommender,'class'=>'select2-sortable full-width','multiple'=>'multiple'));
		                                     
		                                     echo $this->Form->input('NfReviewer.recommender',array('type'=>'select','options'=>$recommenders,'class'=>'select2-sortable full-width','multiple'=>'multiple','value'=>$selectedRecommenders));
		                                       ?>
		                                       <br><small> Please select the Recommender </small>
		                                </div>
		                              </div>
		                            </td>
	                          </tr>
	                          <tr>
		                            <td>
		                             <div class="form-group">
		                                <label class="col-sm-2 col-sm-2 control-label"><b>Select Approver</b></label>
		                                <div class="col-sm-8">
		                                     <?php    
		                                                             
		                                          // echo $this->Form->input('NfReviewer.approver',array('type'=>'select','id'=>'approver','options'=>$approvers,'class'=>'select2-sortable full-width','multiple'=>'multiple'));
		                                     
		                                     	echo $this->Form->input('NfReviewer.approver',array('type'=>'select','options'=>$approvers,'class'=>'select2-sortable full-width','multiple'=>'multiple','value'=>$selectedApprovers));
		                                       ?>
		                                       <br><small> Please select the approver </small>
		                                </div>
		                              </div>
		                            </td>
	                          </tr>
                        </table>
                   

                    <div class="form-group" style="text-align:center"> 

                             <?php                              	

                             	echo $this->Html->link('<button class="btn btn-default margin-right"><i class="fa fa-times"></i> Cancel </button>',array('controller'=>'user','action'=>'userDashboard'),array('escape'=>false));
		   						echo $this->Form->button('<i class="fa fa-save"></i> Preview ',array('type'=>'submit','escape'=>false,'class'=>'btn btn-success'));
		   						echo $this->Form->end();

                             ?>
                    </div>
                    <?php
				      echo $this->Form->end();
				     ?> 
                    </div> 
                    </section>
                    </div>
                  </section>
              </div>
              </div>  