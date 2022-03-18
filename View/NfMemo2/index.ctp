                <?php
	$this->Html->addCrumb('Non-Financial Memo', array('controller' => 'NfMemo2', 'action' => 'index'));
	$this->Html->addCrumb('My Request', $this->here,array('class'=>'active'));
?>


<section class="mems-content">
	<!-- page start-->
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<header class="panel-heading">
					Filtering Options For Non-Financial Memo
					
				</header>

				<div class="panel-body">
					<?php

	                    echo $this->Form->create('Filter', array(
	                        'url' => array('controller' => 'NfMemo2' , 'action' => 'index'),'id'=>'reportForm'));
	                 ?>
					
				    <div class="col-lg-4">
					</div>
					<div class="col-lg-4">
					
				          <div class="form-group">
				                    
				              <?php
				               echo $this->Form->input('date_from',array('type'=>'text','class'=>'form-control datepicker','placeholder'=>'Select date from'));
				              ?>
				           
				          </div>
				          <div class="form-group">
				              <?php
				               echo $this->Form->input('date_to',array('type'=>'text','class'=>'form-control datepicker','placeholder'=>'Select date to'));
				              ?>
				          </div>
	                 	  
					</div>
					<div class="col-lg-4">
					</div>	
					<div class="col-lg-12">
						<div class="form-group text-center">
			                 <?php
			                    echo $this->Form->button('Filter',array('type'=>'submit','class'=>'btn btn-success','name'=>'filter','id'=>'filter'));
			                    echo "&nbsp;&nbsp;";
			                    echo $this->Form->button('Show All',array('type'=>'submit','class'=>'btn btn-danger','name'=>'all','id'=>'all'));
			                   
							?>
						</div>
					</div>
					<?php 
						echo $this->Form->end(); 

						
                        $fromData='';
                        $toData='';

                       

                        if (!empty($this->request->data['Filter']['date_from']))
                            $fromData=$this->request->data['Filter']['date_from'];

                        if (!empty($this->request->data['Filter']['date_to']))
                            $toData=$this->request->data['Filter']['date_to'];

                        $viewData=$this->request->params['action'];
					?>
				</div>
			</section>
		</div>
	</div>
	<div class="row">

		<div class="col-lg-12">

			<section class="panel">
				<?php
                    echo $this->Form->create('NfMemo',array('url'=>array('controller'=>'NfMemo2','action'=>'deleteMemoMulti','view'=>$viewData,'from'=>$fromData,'to'=>$toData),'class'=>'form-horizontal','type'=>'file','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                  ?>
				<header class="panel-heading">
					My Financial Memo List
 					
 					<?php				
 					//phase 2:disable memo creation if memo access is disabled
 					if (!$setting['Setting']['nonfinancial_memo']):
 						if ($activeUser['requestor'])
                        	echo '<a href="#addMemo" data-toggle="modal" class="btn btn-round bg-primary tooltips btn-xs margin-left" data-toggle="tooltip" data-placement="top" data-original-title="Create Memo" ><i class="fa fa-plus"></i> Create New Memo</a>';
                        	
                     endif;                    
 					?>

					<span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
				</header>

				<div class="panel-body">
				
					<table class="table table-striped dataTable">
						<thead>
							<tr>
								<th class="text-center">No.</th>
								<th class="text-center">  <!-- <input type="checkbox" onclick="for(c in document.getElementsByName('rfile')) document.getElementsByName('rfile').item(c).checked = this.checked"> --></th>
								<th class="text-left">Subject</th>
								<th class="text-center">Department</th>
								<th class="text-center">Division</th>
								<th class="text-center">Progress</th>
								<th class="text-center">Remark</th>
								<th class="text-center">Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
						   <?php
						   if (!empty($memo)):
						   	foreach ($memo as $key=>$m){
								$m['NfMemo']['memo_id']=$this->Mems->encrypt($m['NfMemo']['memo_id']);

								// check the status and progress
								$totalStatus = 0;
								$countApproved =0;
								$currentStatus = 'Active';
								foreach($m['NfStatus'] as $mstatus):
									if($mstatus['submission_no'] == $m['NfMemo']['submission_no']):
										$totalStatus++; //update total for current submission
										if($mstatus['status'] == 'approved'):
											$countApproved++;
										endif;

										if($mstatus['status'] == 'rejected'):
											$currentStatus = 'Rejected';
										endif;
									endif;
								endforeach;

								if($totalStatus == 0)
									$progress = 0;
								else
									$progress = round($countApproved/$totalStatus,2) * 100;
								
	                         ?>
							<tr>
								<td class="text-center"><?php echo ++$key;?></td>
								<td class="text-center">
									<?php 
										$submission_no=$m['NfMemo']['submission_no'] ;
										//allow delete for incomplete or newly submitted memo (no progress yet)
										if($progress != 100):
									?>
										<input type="checkbox"  name="memoid[]" value=<?php echo $m['NfMemo']['memo_id'] ?> />
									<?php endif;?>
								</td>
								<td class="text-left">
								
							   <?php 
							  
							   	echo $this->Html->link($m['NfMemo']['subject'],array('controller'=>'NfMemo2','action'=>'dashboard',$m['NfMemo']['memo_id']),array('escape'=>false));

							   ?>
									<br>
									<small>Created on <?php echo date('d M Y',strtotime($m['NfMemo']['created']));?></small>
								</td>
								<td class="text-center"><?php echo $m['Department']['department_name'] ?></td>
								<td class="text-center"><?php echo $m['Department']['Group']['group_name'] ?></td>
								<td class="p-progress text-center">
									<?php
										if($progress < 40)
											echo "<span class='badge bg-important'>{$progress}%</span>";
										elseif($progress < 75)
											echo "<span class='badge bg-warning'>{$progress}%</span>";
										else
											echo "<span class='badge bg-primary'>{$progress}%</span>";
									?>
								</td>
								<td class="text-center">
									<?php
									$assignedFlag=false;
									if (!empty($m['NfRemark'])){
										 foreach ($m['NfRemark'] as $value) {
										 	if (!empty($value['NfRemarkAssign'])){
												$assignedFlag=true;
												break;
										 	}

										 }
									}
										if($assignedFlag)
											echo $this->Html->link('<em> Remark </em>',array('controller'=>'remark','action'=>'index',$m['NfMemo']['memo_id'],'nonfinancial'),array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips data-toggle="tooltip" data-placement="top" data-original-title="Remark" '));
										else
											echo "<em> None </em>";
									?>
								</td>
								<td class="text-center">
									<?php

											if($progress == 100)
												echo "<span class='label label-success'> Approved </span>";
											elseif($totalStatus == 0)
												echo "<span class='label label-default'><span class='fa fa-exclamation-circle'></span> Incomplete </span>";
											elseif($currentStatus == 'Active')
												echo "<span class='label label-warning'> {$currentStatus} </span>";
											else
												echo "<span class='label label-danger'> {$currentStatus} </span>";
										?>
								</td>
								<td class="text-center">
									<!-- <div class="btn-group btn-group-xs"> -->
										<?php

											if($m['NfMemo']['submission_no'] == 0)://havent submitted
												//phase 2:disable memo activity if memo access is disabled
	 											if (!$setting['Setting']['nonfinancial_memo']):
	 												echo $this->Html->link('<i class="fa fa-pencil"></i>',array('controller'=>'NfMemo2','action'=>'request',$m['NfMemo']['memo_id']),array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips data-toggle="tooltip" data-placement="top" data-original-title="Edit" '));
	 											endif;
											else:
												echo $this->Html->link('<i class="fa fa-dashboard"></i>',array('controller'=>'NfMemo2','action'=>'dashboard',$m['NfMemo']['memo_id']),array('escape'=>false,'class'=>'btn bg-primary btn-xs tooltips data-toggle="tooltip" data-placement="top" data-original-title="Dashboard" '));
												
											endif;
											
											$submission_no=$m['NfMemo']['submission_no'] ;
											//allow delete for incomplete or ongoing/rejected
											if($progress!=100):
												//phase 2:disable memo activity if memo access is disabled
 											if (!$setting['Setting']['nonfinancial_memo'])
 												echo $this->Html->link('<i class="fa fa-times"></i>',array('controller'=>'NfMemo2','action'=>'deleteMemo',$m['NfMemo']['memo_id'],'view'=>$viewData,'from'=>$fromData,'to'=>$toData),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title'=>'Delete'),'Are you sure? This action cannot be undone.');
	 										endif;
										?>
									
									<!-- </div> -->
								</td>
							</tr>
							 <?php
	                          
	                        }

	                        endif;?>						
						</tbody>
					</table>
					
				</div>
				<div class="panel-body">
					<?php		
						//phase 2:disable memo activity if memo access is disabled
		 				if (!$setting['Setting']['nonfinancial_memo']):
	 				?>	
	 					<h5><strong>Multi-select option:</strong></h5>
						<button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure? This action cannot be undone.');"><i class="fa fa-times"></i> Delete Memo</button>		
                  <?php 
              		endif;
              		?>
				</div>	
					<?php		
				
              		echo $this->Form->end();
               ?>
				

			</section>

		</div>

	</div>

	<!-- page end-->
<!-- modal foradd staff-->
    <div aria-hidden="true" aria-labelledby="addMemo" role="dialog" tabindex="-1" id="addMemo" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header blue-bg">
                  <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                  <h4 class="modal-title">Add New Non Financial Memo </h4>
              </div>
              <div class="modal-body">

                  <?php
                    echo $this->Form->create('NfMemo',array('url'=>array('controller'=>'NfMemo2','action'=>'request','new'),'class'=>'form-horizontal','type'=>'file','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                    //echo $this->input->hidden('BStatus.budget_id',array('value'=>$budgetID));
                  ?>
                        
                  <div class="form-group">
                      <label class="col-lg-2 col-sm-2 control-label"><b>Subject *</b></label>
                      <div class="col-lg-10">
                        <?php
		                  echo $this->Form->input('NfMemo.subject',array('type'=>'text','id'=>'autoexpanding','class'=>'form-control','required'));
		                ?>
                      </div>

                  </div>
                  <div class="form-group">
		            <label class="col-lg-2 col-sm-2 control-label"><b>Date Required</b></label>
                      <div class="col-lg-10">                 
		              
		                <?php
			              echo $this->Form->input('NfMemo.date_required',array('type'=>'text','class'=>'form-control datepicker','style'=>'width:260px'));
			              ?>
		              
		            </div>
		          </div>
		          <div class="form-group">
		            <label class="col-lg-2 col-sm-2 control-label"><b>Introduction</b></label>
                      <div class="col-lg-10">                 
		              
		                <?php
		                  echo $this->Form->input('NfMemo.introduction',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
		                ?>
		              
		            </div>
		          </div>
		           <div class="form-group text-center">
		           	<small> * denotes a required field</small>
		           </div>
		         
                  <div class="modal-footer text-center">
                    <?php
                      echo $this->Form->button('Add Memo',array('type'=>'submit','class'=>'btn btn-success'));
                    ?>
                    <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
                  </div> 

                  <?php echo $this->Form->end(); ?>
              </div>
          </div>
      </div>
  </div>
</section>

