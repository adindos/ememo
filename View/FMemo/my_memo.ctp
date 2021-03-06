<?php
	$this->Html->addCrumb('Financial Memo', array('controller' => 'fMemo', 'action' => 'index'));
	$this->Html->addCrumb('My Memo', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
	<!-- page start-->
	<div class="row">

		<div class="col-lg-12">

			<section class="panel">

				<header class="panel-heading">
					My Financial Memo
					<span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
				</header>

				<div class="panel-body">
				<table class="table table-striped dataTable">
					<thead>
						<tr>
							<th class="text-center">No.</th>
							<th class="text-left">Subject</th>
							<th class="text-center">Department</th>
							<th class="text-center">Division</th>
							<th class="text-center">Progress</th>
							<th class="text-center">Comment</th>
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
							foreach($m['FMemo']['FStatus'] as $mstatus):
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
							
                         ?>
						<tr>
							<td class="text-center"><?php echo ++$key;?></td>
							<td class="text-left">
							
						   <?php 
						  
						   	echo $this->Html->link($m['FMemo']['subject'],array('controller'=>'FMemo','action'=>'dashboard',$m['FMemo']['memo_id']),array('escape'=>false));

						   ?>
								<br>
								<small>Created on <?php echo date('d M Y',strtotime($m['FMemo']['created']));?></small>
							</td>
							<td class="text-center"><?php echo $m['FMemo']['Department']['department_name'] ?></td>

							<td class="text-center"><?php echo $m['FMemo']['Department']['Group']['group_name'] ?></td>

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
								$commentFlag=false;
								if (!empty($m['FMemo']['FComment'])){
									$commentFlag=true;
									
								}
									if($commentFlag)
										echo $this->Html->link('<em> Comment </em>',array('controller'=>'Comment','action'=>'index',$m['FMemo']['memo_id'],'financial'),array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips data-toggle="tooltip" data-placement="top" data-original-title="Comment" '));
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
									
										echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'FMemo','action'=>'dashboard',$m['FMemo']['memo_id']),array('escape'=>false,'class'=>'btn bg-primary btn-xs tooltips data-toggle="tooltip" data-placement="top" data-original-title="Dashboard" '));
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

			</section>

		</div>

	</div>

	<!-- page end-->

</section>


    