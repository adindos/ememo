<?php
	$this->Html->addCrumb('Budget', array('controller' => 'budget', 'action' => 'index'));
	$this->Html->addCrumb('Budget List', array('controller' => 'budget', 'action' => 'allrequest'),array('class'=>'active'));
?>

<section class="mems-content">
	<!-- page start-->
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<?php
                    echo $this->Form->create('Budget',array('url'=>array('controller'=>'Budget','action'=>'deleteBudgetMulti'),'class'=>'form-horizontal','type'=>'file','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                    //echo $this->input->hidden('BStatus.budget_id',array('value'=>$budgetID));
                  ?>
				<header class="panel-heading">
					Budget List
				</header>

				<div class="panel-body">
					<table class="table table-striped dataTable">
						<thead>
							<tr>
								<th class=""> </th>
								<th class="text-center"> Year</th>
								<th class="text-center">Company</th>
								<th class="text-center">Department</th>
								<th class="text-center">Progress</th>
								<th class="text-center">Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								// debug($budget);exit();
								foreach($budget as $b):
									$budgetid = $b['Budget']['budget_id'];
									$encBudgetID = $this->Mems->encrypt($budgetid);
									if( !empty($b['BStatus']) ): // show all except incomplete one
							?>
							<tr>
								<td class="text-center">
									<?php 
										// check the status and progress
										$totalStatus = 0;
										$countApproved =0;
										$currentStatus = 'Active';
										foreach($b['BStatus'] as $bstatus):
											if($bstatus['submission_no'] == $b['Budget']['submission_no']):
												$totalStatus++; //update total for current submission
												if($bstatus['status'] == 'approved'):
													$countApproved++;
												endif;

												if($bstatus['status'] == 'rejected'):
													$currentStatus = 'Rejected';
												endif;
											endif;
										endforeach;
										if($totalStatus == 0)
											$progress = 0;
										else
											$progress = round($countApproved/$totalStatus,2) * 100;
										$submission_no=$b['Budget']['submission_no'] ;
										//allow delete for incomplete or newly submitted budget rejected/not yet approved 
										if(($submission_no ==1 && $progress!=100)&&in_array($activeUser['Role']['role_id'], array(17))):
									?>
										<input type="checkbox"  name="budgetid[]" value=<?php echo $b['Budget']['budget_id'] ?> />
									<?php endif;?>
								</td>
								<td class="text-center">
									<?php
										echo $this->Html->link($b['Budget']['year'],array('controller'=>'budget','action'=>'dashboard',$encBudgetID),array('escape'=>false));
									?>
									<br>
									<small>
										<?php
											$created = date('d M Y',strtotime($b['Budget']['created']));
											echo "Created on : ".$created;
											
										?>
									</small>
								</td>
								<td class="text-center">
									<?php
										echo $b['Company']['company'];
									?>
								</td>
								<td class="text-center">
									<?php
										echo $activeUser['Department']['department_name'];
									?>
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
									<!-- <div class='btn-group'> -->
									<?php

										
										echo $this->Html->link('<i class="fa fa-dashboard"></i>',array('controller'=>'budget','action'=>'dashboard',$encBudgetID),array('escape'=>false,'class'=>'btn bg-primary btn-xs tooltips','data-original-title'=>'Dashboard'));
											
										$submission_no=$b['Budget']['submission_no'] ;
										//allow delete for incomplete or newly submitted budget rejected/not yet approved 
										if(($submission_no ==1 && $progress!=100)&&in_array($activeUser['Role']['role_id'], array(17)))
											echo $this->Html->link('<i class="fa fa-times"></i>',array('controller'=>'budget','action'=>'deleteBudget',$encBudgetID),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title'=>'Delete'),'Are you sure you want to delete this budget?');
									?>
									<!-- </div> -->
								</td>
							</tr>
							<?php
									endif;
								endforeach;
							?>
						</tbody>
					</table>
				</div>
				<div class="panel-body">
						<?php //phase 2-only finance admin/superadmin can create new budget
						if(in_array($activeUser['Role']['role_id'], array(17,18))):
							?>
							<h5><strong>Multi-select option:</strong></h5>
							<button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure? This action cannot be undone.');"><i class="fa fa-times"></i> Delete Budget</button>
						<?php endif;?>	
                  
				</div>	
					<?php		
				
              		echo $this->Form->end();
               ?>
			</section>
		</div>
	</div>
	<!-- page end-->
</section>

<!-- Modal -->
<div class="modal fade" id="modal-create-budget" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create New Budget</h4>
			</div>
			<?php
				echo $this->Form->create('Budget',array('url'=>array('controller'=>'budget','action'=>'createBudget'),
										'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
										'class'=>'form-horizontal','type'=>'file'));
			?>
			<div class="modal-body ">
				<div class="form-group">
					<label class="col-lg-3 col-sm-3 control-label"> Department </label>
					<div class="col-sm-9">
                      	<?php
                      		echo $this->Form->input('Budget.read',array('type'=>'text','class'=>'form-control','readonly'=>'readonly','value'=>$strDepartment));
                      	?>
                      	<small><i class="fa fa-exclamation-circle"></i> You can only create budget for your department </small>
                  	</div>
				</div>
				<div class="form-group">
                  	<label class="col-lg-3 col-sm-3 control-label">Budget Title</label>
                  	<div class="col-sm-9">
                      	<?php
                      		echo $this->Form->input('Budget.title',array('type'=>'text','class'=>'form-control'));
                      	?>
                  	</div>
                </div>
                <div class="form-group text-center">
	                <label class="col-lg-3 col-sm-3 control-label">Year</label>
                  	<div class="col-sm-9">	  
                  		<?php
                      		echo $this->Form->input('Budget.year',array('type'=>'text','class'=>'form-control text-center','style'=>'width:20%','data-mask'=>'9999'));
                      	?>                    
	                </div>
                </div>
                <div class="form-group">
	                <label class="col-lg-3 col-sm-3 control-label">Quarter</label>
                  	<div class="col-sm-9">	  
                  		<?php
                  			$quarterOptions = array('January - March'=>'January - March','April - June'=>'April - June','July - September'=>'July - September','October - December'=>'October - December');
                      		echo $this->Form->input('Budget.quarter',array('type'=>'select','options'=>$quarterOptions,'class'=>'select2 full-width'));
                      	?>                    
	                </div>
                </div>
                <div class="form-group text-center">
	                <label class="col-lg-3 col-sm-3 control-label">Description</label>
                  	<div class="col-sm-9">	  
                  		<?php
                      		echo $this->Form->input('Budget.description',array('type'=>'textarea','class'=>'form-control'));
                      	?>                    
	                </div>
                </div>
	        </div>
				
			<div class="modal-footer text-center">
				<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
				<?php
					echo $this->Form->button('Create Budget',array('type'=>'submit','class'=>'btn btn-success'));
				?>
			</div>
			<?php
				echo $this->Form->end();
			?>
		</div>
	</div>
</div>
  <!-- modal -->