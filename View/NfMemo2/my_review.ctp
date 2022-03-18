<?php
	$this->Html->addCrumb('Non-Financial Memo', array('controller' => 'NfMemo2', 'action' => 'index'));
	$this->Html->addCrumb('Request Management', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
	<!-- page start-->
	<div class="row">
	<!-- <div class="row" style='margin-top:40px;'> -->
		<!-- <div style="position:fixed;top:52px;z-index:99999;width:100%;padding:10px 20px;margin: 10px;background:rgba(255,255,255,0.7)">
			<strong> Go to : </strong>
			<a href="#my-pending-review" data-scroll class="btn btn-round btn-sm btn-warning margin-left">
				My Pending Review
			</a>
			<a href="#my-reviewed-request" data-scroll class="btn btn-round btn-sm btn-success margin-left">
				My Reviewed Request
			</a>
		</div> -->
		<div class="clear"></div>
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
					<table class="table table-hover p-table dataTable">
						<thead>
							<tr class="bg-primary">
								<td colspan="8" >
									<span class="bold bigger-text"> My Pending Review </span><br>
									<small> Non Financial Memo that are pending for my review </small>
								</td>
							</tr>
							<tr>
								<th class="text-center">No.</th>
								<th class="text-left">Subject</th>
								<th class="text-center">Department</th>
								<th class="text-center">Division</th>
								<th class="text-center">Requested By</th>
								<th class="text-center">Progress</th>
								<th class="text-center">Status</th>
								<th class="col-lg-1 text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($pendingMemo as $key=>$pm):
									//$memo_id = $pm['NfMemo']['memo_id'];
								    $memo_id = $this->Mems->encrypt($pm['NfMemo']['memo_id']);
							?>
							<tr>
							<td class="text-center"><?php echo ++$key;?></td>
								<td class="text-left">
									<?php
										echo $this->Html->link($pm['NfMemo']['subject'],array('controller'=>'NfMemo2','action'=>'dashboard',$this->Mems->encrypt($pm['NfMemo']['memo_id'])),array('escape'=>false));
									?>
									<br>
									<small>
										<?php
											$created = date('d-F-Y',strtotime($pm['NfMemo']['created']));
											$modified = date('d-F-Y',strtotime($pm['NfMemo']['modified']));
											echo "Created on : ".$created;
											
										?>
									</small>
								</td>
								<td class="text-center"><?php echo $pm['NfMemo']['Department']['department_name'] ?></td>

								<td class="text-center"><?php echo $pm['NfMemo']['Department']['Group']['group_name'] ?></td>
								<td class="text-center">
									
									<?php
										echo $pm['NfMemo']['User']['staff_name'];
										echo "<br>";
										echo "<small>";
										echo $pm['NfMemo']['Department']['department_name'];
										echo "</small>";
									?>
								</td>
								<td class="p-progress text-center">
									<?php
										// check the status and progress
										$totalStatus = 0;
										$countApproved =0;
										$currentStatus = 'Active';
										foreach($pm['NfMemo']['NfStatus'] as $fstatus):
											if($fstatus['submission_no'] == $pm['NfMemo']['submission_no']):
												$totalStatus++; //update total for current submission
												if($fstatus['status'] == 'approved'):
													$countApproved++;
												endif;

												if($fstatus['status'] == 'rejected'):
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
									//phase 2:disable memo activity if memo access is disabled
 									if (!$setting['Setting']['nonfinancial_memo']):
										echo $this->Html->link('<i class="fa fa-eye"></i> Review',array('controller'=>'NfMemo2','action'=>'review',$memo_id),array('escape'=>false,'class'=>'btn btn-warning btn-xs btn-block tooltips data-toggle="tooltip" data-placement="top" data-original-title="Review memo"'));
									endif;
										echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'NfMemo2','action'=>'dashboard',$memo_id),array('escape'=>false,'class'=>'btn bg-primary btn-xs btn-block tooltips data-toggle="tooltip" data-placement="top" data-original-title="Memo Dashboard"'));

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
					<table class="table table-hover p-table dataTable">
						<thead>
							<tr class="bg-primary">
								<td colspan="8" >
									<span class="bold bigger-text"> My Reviewed Request </span><br>
									<small> Non Financial Memo that are already reviewed </small>
								</td>
							</tr>
							<tr>
								<th class="text-center">No.</th>
								<th class="text-left">Subject</th>
								<th class="text-center">Department</th>
								<th class="text-center">Division</th>
								<th class="text-center">Requested By</th>
								<th class="text-center">Progress</th>
								<th class="text-center">Status</th>
								<th class="col-lg-1 text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($reviewedMemo as $key=>$rm):
									//$memo_id = $rm['NfMemo']['memo_id'];
									$memo_id = $this->Mems->encrypt($rm['NfMemo']['memo_id']);
							?>
							<tr>
								<td class="text-center"><?php echo ++$key;?></td>

								<td class="text-left">
									<?php
										echo $this->Html->link($rm['NfMemo']['subject'],array('controller'=>'NfMemo2','action'=>'dashboard',$this->Mems->encrypt($rm['NfMemo']['memo_id'])),array('escape'=>false));
									?>
									<br>
									<small>
										<?php
											$created = date('d-F-Y',strtotime($rm['NfMemo']['created']));
											$modified = date('d-F-Y',strtotime($rm['NfMemo']['modified']));
											echo "Created on : ".$created;
											
										?>
									</small>
								</td>
								<td class="text-center"><?php echo $rm['NfMemo']['Department']['department_name'] ?></td>

								<td class="text-center"><?php echo $rm['NfMemo']['Department']['Group']['group_name'] ?></td>
								<td class="text-center">
									
									<?php
										echo $rm['NfMemo']['User']['staff_name'];
										echo "<br>";
										echo "<small>";
										echo $rm['NfMemo']['Department']['department_name'];
										echo "</small>";
									?>
								</td>
								<td class="p-progress text-center">
									<?php
										// check the status and progress
										$totalStatus = 0;
										$countApproved =0;
										$currentStatus = 'Active';
										foreach($rm['NfMemo']['NfStatus'] as $fstatus):
											if($fstatus['submission_no'] == $rm['NfMemo']['submission_no']):
												$totalStatus++; //update total for current submission
												if($fstatus['status'] == 'approved'):
													$countApproved++;
												endif;

												if($fstatus['status'] == 'rejected'):
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
										echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'NfMemo2','action'=>'dashboard',$this->Mems->encrypt($rm['NfMemo']['memo_id'])),array('escape'=>false,'class'=>'btn bg-primary btn-xs btn-block'));

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

