<?php
	$this->Html->addCrumb('Financial Memo', array('controller' => 'fMemo', 'action' => 'index'));
	$this->Html->addCrumb('All Request', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
	<!-- page start-->
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<header class="panel-heading">
					Filtering Options For Financial Memo
					
				</header>

				<div class="panel-body">
					<?php

	                    echo $this->Form->create('Filter', array(
	                        'url' => array('controller' => 'fMemo' , 'action' => 'allRequest'),'id'=>'reportForm'));
	                 ?>
					<?php if (in_array($activeUser['Role']['role_id'], array(17,18))):?>
					<div class="col-lg-1">
					</div>
					<div class="col-lg-5">
				          <div class="form-group">
				                              
				              <?php
				              echo $this->Form->input('division_id',array('type'=>'select','options'=>$groups,'class'=>'select2-sortable full-width','multiple'=>'multiple','style'=>'width:100%','placeholder'=>'Select division(s)'));
				              ?>
				           
				          </div>
				          <div class="form-group">
				                             
				              <?php
				              echo $this->Form->input('department_id',array('type'=>'select','options'=>$departments,'class'=>'select2-sortable full-width','multiple'=>'multiple','style'=>'width:100%','placeholder'=>'Select department(s)'));
				              ?>
				            
				          </div>
				          <div class="form-group">
				            
				                          
				              <?php
				               echo $this->Form->input('user_id',array('type'=>'select','options'=>$allUsers,'class'=>'select2-sortable full-width','multiple'=>'multiple','style'=>'width:100%','placeholder'=>'Select requestor(s)'));
				              ?>
				            
				          </div>
					</div>

					<div class="col-lg-5">
					
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
					<div class="col-lg-1">
					</div>
				    <?php  else: ?>
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
				    <?php endif; ?>
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

						$groupData='';
                        $deptData='';
                        $userData='';
                        $fromData='';
                        $toData='';

                        if (!empty($this->request->data['Filter']['division_id']))
                            $groupData=$this->request->data['Filter']['division_id'];

                        if (!empty($this->request->data['Filter']['department_id']))
                            $deptData=$this->request->data['Filter']['department_id'];

                        if (!empty($this->request->data['Filter']['date_from']))
                            $fromData=$this->request->data['Filter']['date_from'];

                        if (!empty($this->request->data['Filter']['date_to']))
                            $toData=$this->request->data['Filter']['date_to'];

                        if (!empty($this->request->data['Filter']['user_id']))
                            $userData=$this->request->data['Filter']['user_id'];

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
                    echo $this->Form->create('FMemo',array('url'=>array('controller'=>'FMemo','action'=>'deleteMemoMulti','view'=>$viewData,'div'=>$groupData,'dept'=>$deptData,'user'=>$userData,'from'=>$fromData,'to'=>$toData),'class'=>'form-horizontal','type'=>'file','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                    //echo $this->input->hidden('BStatus.budget_id',array('value'=>$budgetID));
                  ?>
				<header class="panel-heading">
					Financial Memo List

					<span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
				</header>

				<div class="panel-body">
				<table class="table table-striped dataTable">
					<thead>
						<tr>
							<th class="text-center">No.</th>
							<th class="text-center"> </th>
							<th class="text-left">Subject</th>
							<th class="text-center">Ref. No</th>
							<th class="text-center">Department</th>
							<th class="text-center">Division</th>
							<th class="text-center">Requested by</th>
							<th class="text-center">Progress</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
					   <?php
					   if (!empty($memo)):
					   	foreach ($memo as $key=>$m){
							$m['FMemo']['memo_id']=$this->Mems->encrypt($m['FMemo']['memo_id']);
					   		
							// check the status and progress
							$totalStatus = 0;
							$countApproved =0;
							$currentStatus = 'Active';
							foreach($m['FStatus'] as $mstatus):
								if($mstatus['submission_no'] == $m['FMemo']['submission_no']):
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
							
							// //display only completed memo
							// if ($totalStatus != 0)	:
                         ?>
						<tr>
							<td class="text-center"><?php echo ++$key;?></td>
							<td class="text-center">
								<?php 
									$submission_no=$m['FMemo']['submission_no'] ;
									//allow delete for incomplete or ongoing/rejected memo (notify all users for ongoing/rejected)
									if(($progress != 100)&&in_array($activeUser['Role']['role_id'], array(17))):
								?>
									<input type="checkbox"  name="memoid[]" value=<?php echo $m['FMemo']['memo_id'] ?> />
								<?php endif;?>
							</td>
							<td>
							
						   <?php 
						  
						   	echo $this->Html->link($m['FMemo']['subject'],array('controller'=>'FMemo','action'=>'dashboard',$m['FMemo']['memo_id']),array('escape'=>false));

						   ?>
								<br>
								<small>Created on <?php echo date('d M Y',strtotime($m['FMemo']['created']));?></small>
							</td>
							<td class="text-center"><?php echo $m['FMemo']['ref_no'] ?></td>
							<td class="text-center"><?php echo $m['Department']['department_name'] ?></td>
							<td class="text-center"><?php echo $m['Department']['Group']['group_name'] ?></td>
							<td class="text-center">
								<?php echo $m['User']['staff_name']; ?>
							</td>
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

										
										echo $this->Html->link('<i class="fa fa-dashboard"></i>',array('controller'=>'FMemo','action'=>'dashboard',$m['FMemo']['memo_id']),array('escape'=>false,'class'=>'btn bg-primary btn-xs tooltips data-toggle="tooltip" data-placement="top" data-original-title="Dashboard" '));
																			
										$submission_no=$m['FMemo']['submission_no'] ;
										//allow delete for incomplete or ongoing/rejected memo (notify all users for ongoing/rejected)
										if(($progress != 100)&&in_array($activeUser['Role']['role_id'], array(17))):
											//phase 2:disable memo activity if memo access is disabled
 											if (!$setting['Setting']['financial_memo'])
 												echo $this->Html->link('<i class="fa fa-times"></i>',array('controller'=>'FMemo','action'=>'deleteMemo',$m['FMemo']['memo_id'],'view'=>$viewData,'div'=>$groupData,'dept'=>$deptData,'user'=>$userData,'from'=>$fromData,'to'=>$toData),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title'=>'Delete'),'Are you sure? This action cannot be undone.');
 										endif;
									?>
									
								<!-- </div> -->
							</td>
						</tr>
						 <?php
                            // endif;
                        }//endof foreach

                        endif;//endof if memo not empty?>						
					</tbody>
				</table>
				</div>
				<div class="panel-body">
					<?php		
						//phase 2:disable memo activity if memo access is disabled
		 				if (!$setting['Setting']['financial_memo']):
		 					if(in_array($activeUser['Role']['role_id'], array(17))):
	 				?>
	 					<h5><strong>Multi-select option:</strong></h5>
						<button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure? This action cannot be undone.');"><i class="fa fa-times"></i> Delete Memo</button>		
                  <?php 
                  		endif;
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

</section>