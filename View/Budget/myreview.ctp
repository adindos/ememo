<?php
	$this->Html->addCrumb('Budget', array('controller' => 'budget', 'action' => 'index'));
	$this->Html->addCrumb('Request Management', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
	<!-- page start-->
	<div class="row">
	<!-- <div class="row" style='margin-top:40px;'> -->
		<!-- <div style="position:fixed;top:52px;z-index:99999;width:400px;padding:10px 20px;margin: 10px;background:rgba(255,255,255,0.7)">
			<strong> Go to : </strong>
			<a href="#my-pending-review" data-scroll class="btn btn-round btn-sm btn-warning margin-left">
				My Pending Review
			</a>
			<a href="#my-reviewed-request" data-scroll class="btn btn-round btn-sm btn-success margin-left">
				My Reviewed Request
			</a>
		</div>
		<div class="clear"></div> -->
		<div class="col-lg-12">
			<section class="panel">
				<header class="panel-heading">
					My Review Panel
 					<span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
				</header>

				<div class="clear"></div>
				<div class="panel-body" id="my-pending-review" >
					<table class="table table-striped table-hover dataTable">
						<thead>
							<tr class="bg-primary">
								<td colspan="7" >
									<span class="bold bigger-text"> My Pending Review </span><br>
									<small> Budget pending for your review </small>
								</td>
							</tr>
							<tr>
								<th >No.</th>
								<th >Year</th>
								<th class="text-center">Company</th>
								<th class="text-center">Requested By</th>
								<th class="text-center">Progress</th>
								<th class="text-center">Status</th>
								<th class="col-lg-1 text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($pendingBudget as $pb):
									$budgetid = $pb['Budget']['budget_id'];
									$encBudgetID = $this->Mems->encrypt($budgetid);
									$counter=1;

							?>
							<tr>
								<td><?php echo $counter++; ?></td>
								<td>
									<?php
										echo $this->Html->link($pb['Budget']['year'],array('controller'=>'budget','action'=>'dashboard',$encBudgetID),array('escape'=>false));
									?>
									<br>
									<small>
										<?php
											$created = date('d-F-Y',strtotime($pb['Budget']['created']));
											$modified = date('d-F-Y',strtotime($pb['Budget']['modified']));
											echo "Created on : ".$created;
											
										?>
									</small>
								</td>
								<td class="text-center"> <?php echo $pb['Budget']['Company']['company'] ;?> </td>
								<td class="text-center">
									
									<?php
										echo $pb['Budget']['User']['staff_name'];
										echo "<br>";
										echo "<small>";
										echo $pb['Budget']['User']['Department']['department_name'];
										echo "<br>";
										echo "( ".$pb['Budget']['Company']['company'] ." )";
										echo "</small>";
									?>
								</td>
								<td class="p-progress text-center">
									<?php
										// check the status and progress
										$totalStatus = 0;
										$countApproved =0;
										$currentStatus = 'Active';
										foreach($pb['Budget']['BStatus'] as $bstatus):
											if($bstatus['submission_no'] == $pb['Budget']['submission_no']):
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
									<?php
										echo $this->Html->link('<i class="fa fa-eye"></i> Review',array('controller'=>'budget','action'=>'review',$encBudgetID),array('escape'=>false,'class'=>'btn btn-warning btn-xs btn-block'));
										echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'budget','action'=>'dashboard',$encBudgetID),array('escape'=>false,'class'=>'btn bg-primary btn-xs btn-block'));

									?>
								</td>
							</tr>
							<?php
								endforeach;
							?>
						</tbody>
					</table>
				</div>
				<hr style="margin:0">
				<div class="panel-body" id="my-reviewed-request">
					<table class="table table-striped table-hover dataTable">
						<thead>
							<tr class="bg-primary">
								<td colspan="7" >
									<span class="bold bigger-text"> My Reviewed Request </span><br>
									<small> Budget reviewed</small>
								</td>
							</tr>
							<tr>
								<th>No.</th>
								<th>Year</th>
								<th class="text-center">Company</th>
								<th class="text-center">Requested By</th>
								<th class="text-center">Progress</th>
								<th class="text-center">Status</th>
								<th class="col-lg-1 text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($reviewedBudget as $rb):
									$budgetid = $rb['Budget']['budget_id'];
									$encBudgetID = $this->Mems->encrypt($budgetid);
									$counter=1;
							?>
							<tr>
								<td><?php echo $counter++; ?></td>
								<td>
									<?php
										echo $this->Html->link($rb['Budget']['year'],array('controller'=>'budget','action'=>'dashboard',$encBudgetID),array('escape'=>false));
									?>
									<br>
									<small>
										<?php
											$created = date('d-F-Y',strtotime($rb['Budget']['created']));
											$modified = date('d-F-Y',strtotime($rb['Budget']['modified']));
											echo "Created on : ".$created;
											
										?>
									</small>
								</td>
								<td class="text-center"> <?php echo $rb['Budget']['Company']['company'] ;?> </td>

								<td class="text-center">
									
									<?php
										echo $rb['Budget']['User']['staff_name'];
										echo "<br>";
										echo "<small>";
										echo $rb['Budget']['User']['Department']['department_name'];
										echo "<br>";
										echo "( ".$rb['Budget']['Company']['company'] ." )";
										echo "</small>";
									?>
								</td>
								<td class="p-progress text-center">
									<?php
										// check the status and progress
										$totalStatus = 0;
										$countApproved =0;
										$currentStatus = 'Active';
										foreach($rb['Budget']['BStatus'] as $bstatus):
											if($bstatus['submission_no'] == $rb['Budget']['submission_no']):
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
									<?php
										echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'budget','action'=>'dashboard',$encBudgetID),array('escape'=>false,'class'=>'btn bg-primary btn-xs btn-block'));

									?>
								</td>
							</tr>
							<?php
								endforeach;
							?>
						</tbody>
					</table>
				</div>

			</section>

		</div>

	</div>

	<!-- page end-->

</section>
