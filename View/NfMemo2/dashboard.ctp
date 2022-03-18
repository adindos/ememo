<?php
	$this->Html->addCrumb('Non-Financial Memo', array('controller' => 'NfMemo2', 'action' => 'index'));
	if(empty($memo['NfMemo']['ref_no']))
		$this->Html->addCrumb(' ( Dashboard ) Temporary Ref.No : 000/'.$memo['NfMemo']['memo_id'], $this->here,array('class'=>'active'));
	else
		$this->Html->addCrumb( ' ( Dashboard ) Ref.No : '.$memo['NfMemo']['ref_no'], $this->here,array('class'=>'active'));
?>

<section class="mems-content">
	
	<div class="row">
		<div class="col-md-8">
			<section class="panel">
				<div class="bio-graph-heading project-heading blue-bg">
				<strong> <?php echo $memo['NfMemo']['subject'] ?> </strong><br/>
				<small> <?php echo $memo['Department']['department_name'] ?> </small>
				</div>
				<div class="panel-body">
					<div class="col-lg-8">
						<h5 class="bold"> MEMO DESCRIPTION </h5>
						<p class="text-justify">


							<?php echo $memo['NfMemo']['introduction']; ?>

						</p>
						<br/>
						
						<br/>

						<h5 class="bold">Progress</h5>

						<?php

							// progress calculation
							$totalStatus = count($reviewers)+count($approvers) + count($recommenders) ;


							$countApproved =0;
							$isRejected = false;
							$isApproved = false;
							
							foreach($reviewers as $r):
								if($r['NfStatus']['status'] == 'approved')
									$countApproved++;
								elseif($r['NfStatus']['status'] == 'rejected')
									$isRejected = true;
							endforeach;

							foreach($approvers as $a):
								if($a['NfStatus']['status'] == 'approved')
									$countApproved++;
								elseif($a['NfStatus']['status'] == 'rejected')
									$isRejected = true;
							endforeach;

							foreach($recommenders as $rc):
								if($rc['NfStatus']['status'] == 'approved')
									$countApproved++;
								elseif($rc['NfStatus']['status'] == 'rejected')
									$isRejected = true;
							endforeach;
							



							if($totalStatus == 0)
								$progress = 0;
							else
								$progress = round($countApproved/$totalStatus,2) * 100;


							$style = 'info';
							$active = '';
							if($progress == 100){
								$style = 'success';
								$isApproved = true;
							}
							elseif($isRejected){
								$style = 'danger';
							}
							elseif($progress < 100){
								$style = 'warning';
								$active = 'active';
							}

							$isTracked =false;
						?>


						<div class="progress progress-striped <?php echo $active; ?>" style="margin-bottom:5px;">
							<div style="width: <?php echo $progress; ?>%;" class="progress-bar progress-bar-<?php echo $style; ?>"></div>
						</div>
						<?php
							if($isRejected)
								echo "<small> Memo is <strong>rejected</strong> by reviewer/approver. Pending for resubmission by requestor. </small>";
							else
								echo "<small>Memo approval is <strong>{$progress} %</strong> completed.</small>"
						?>


						<p>
							Non Financial Memo
						</p>

						<br/>


						<h5 class="bold">MEMO TYPE</h5>
						<p>
							Non Financial Memo
						</p>

						<br/>

						
					</div>

					<div class="col-lg-4">
						<?php
						//phase 2:disable memo  if memo access is disabled
 						if (!$setting['Setting']['nonfinancial_memo']):
							if($isRejected && ($activeUser['user_id'] == $memo['NfMemo']['user_id'])){
								echo $this->Html->link('<strong> Resubmit </strong><br><small> Resubmit this Memo </small>',array('controller'=>'NfMemo2','action'=>'request',$this->Mems->encrypt($memo['NfMemo']['memo_id']),'edit'),array('escape'=>false,'class'=>'btn bg-primary btn-shadow full-width'));
								echo "<br><br>";
							}
						endif;

							// echo $this->Html->link('View Memo Details',array('controller'=>'NfMemo2','action'=>'review',$memo['NfMemo']['memo_id']),array('escape'=>false,'class'=>'btn btn-lg bg-primary btn-shadow btn-round full-width'));


							echo $this->Html->link('View Memo Details',array('controller'=>'NfMemo2','action'=>'review',$this->Mems->encrypt($memo['NfMemo']['memo_id'])),array('escape'=>false,'class'=>'btn btn-lg bg-primary btn-shadow btn-round full-width'));

						?>
						<hr>
						<!-- <div class="widget red-bg">
							<div class="widget-content">
								<p class="stats"> None </p>
							</div>
							<div class="widget-title">
								Budget Requested
							</div>
						</div> -->

						<div class="widget blue-bg">
							<div class="widget-content">
								<p class="stats"> <?php echo $memo['NfMemo']['submission_no'] ?></p>
							</div>
							<div class="widget-title">
								No.of Submission
							</div>
						</div>
						
					</div>
				</div>

			</section>

			<section class="panel">
				<div class="panel-body">
					<div class="text-center">
						<h3 class="timeline-title">Memo Tracking</h3>
						<p class="t-info">Track the Memo status and delay</p>
					</div>
				</div>

				<div class="tracking">
					<?php
						function trackingBullet($status){
							$trackingApproved = "<a class='tracking-bullet green'></a>";
							$trackingRejected = "<a class='tracking-bullet red'></a>";
							$trackingPending = "<a class='tracking-bullet yellow'></a>";
							$trackingOther = "<a class='tracking-bullet black'></a>";

							if($status == 'approved')
								return $trackingApproved;
							elseif($status == 'rejected')
								return $trackingRejected;
							elseif($status == 'pending')
								return $trackingPending;
							else
								return $trackingOther;
						}

						function getColor($status){
							if($status == 'approved')
								return "green";
							elseif($status == 'rejected')
								return "red";
							elseif($status == 'pending')
								return "yellow";
							else
								return "black";
						}
					?>
					<!-- Requestor -->
					<div class="tracking-panel">
						<div class="overall-tracking">
							<?php
								
								if($isRejected){
									echo "<a data-scroll href='#current-tracking' class='tracking-bullet red now'></a>";
									echo "<a data-scroll href='#current-tracking' class='btn btn-danger'>
											<i class='fa fa-times-circle'></i>
											<strong> REJECTED </strong><br>
											<small> Pending for requestor to resubmit </small>
										</a>";
								}
								elseif($isApproved){
									echo "<a data-scroll href='#current-tracking' class='tracking-bullet green now'></a>";
									echo "<a data-scroll href='#current-tracking' class='btn btn-green'>
											<i class='fa fa-check-circle'></i>
											<strong> APPROVED </strong><br>
											<small> Memo is approved </small>
										</a>";
								}
								else{
									echo "<a data-scroll href='#current-tracking' class='tracking-bullet yellow now'></a>";
									echo "<a data-scroll href='#current-tracking' class='btn btn-warning'>
											<i class='fa fa-circle'></i>
											<strong> PENDING </strong><br>
											<small> Memo is in review </small>
										</a>";
								}
							?>
						</div>

						<div class='requester' <?php if($isRejected && !$isTracked ){ echo "id='current-tracking'";$isTracked=true; } ?> >
							<a class='tracking-bullet primary'></a>	
							<div class="tracking-layer">				
								<h1 class="primary">
									<strong> REQUESTOR </strong><br>
									<?php
										echo $memo['User']['staff_name'];
									?>
								</h1>
								<div class="tracking-status">
									<strong class="bigger-text"> Memo Created </strong><br>
									<small> 
										Memo created on :  
										<?php
											echo date('d F Y',strtotime($memo['NfMemo']['created']));
										?>
									</small><br>
									<?php
										if($memo['NfMemo']['submission_no'] > 1):
											echo "<small>Latest resubmission on : ".date('d F Y',strtotime($memo['NfMemo']['modified']))."</small>";
										endif;
									?>
									<br><small> 
										Current Submission :  
										<?php
											echo $memo['NfMemo']['submission_no'];
										?>
									</small><br>
								</div>	
							</div>
						</div>
					</div>
					<div class="tracking-panel">
						<?php
							$i=0;
							foreach($reviewers as $reviewer):
						?>
						<div class="tracking-item" <?php if($reviewer['NfStatus']['status'] == 'pending'  && !$isTracked){ echo "id='current-tracking'";$isTracked=true; } ?> >
							<div class="tracking-title tt-adjustment"> 
								<strong> REVIEWER </strong> <br>
								<small>
									<?php
										echo $reviewer['NfReviewer']['User']['staff_name'];
									?>
								</small>
							</div>
							<?php
								// tracking bullet
								echo trackingBullet($reviewer['NfStatus']['status']);
							?>
							<div class="tracking-layer tl-adjustment">				
								<h1 class="<?php echo getColor($reviewer['NfStatus']['status']); ?>">
									<strong class='uppercase'>
										<?php 
											echo ($reviewer['NfStatus']['status'] != 'pending-rejected') ? $reviewer['NfStatus']['status'] : "NA"; 
										?>
									</strong><br>
								</h1>
								<div class="tracking-status text-left">
 									<small> 
										<?php
											if($reviewer['NfStatus']['status'] == 'approved'){
												echo "<strong> Date approved : </strong>";
												echo date('d F Y',strtotime($reviewer['NfStatus']['modified']));
											}
											elseif($reviewer['NfStatus']['status'] == 'rejected'){
												echo "<strong> Date rejected : </strong>";
												echo date('d F Y',strtotime($reviewer['NfStatus']['modified']));
											}
										?>
									</small><br>
									<?php
										// calculate delay time

										// first review -- take from budget creation/modified date
										if($i==0){
											$previous = new DateTime($memo['NfMemo']['modified']); // if first review
										}

										$current = new DateTime($reviewer['NfStatus']['modified']);

										$diff = $current->diff($previous);
										$diffDay = $diff->d;

										$delay = $diffDay - $setting['Setting']['max_review_day_memo'];

										//reset previous to current 
										$previous = $current;
										$i = $i+1;
									?>
									<small>
										<strong> Delay Time : </strong>
										<?php 
											if($delay > 0)
												echo ($delay == 1) ? $delay."  day" : $delay."  days";
											else
												echo "<em> None </em>"; 
										?> 
									</small>
									<?php
										if(!empty($reviewer['NfStatus']['remark'])){
											echo "<div class='tracking-remark small text-left'>
													<strong> Remark :</strong><br>
													<em> {$reviewer['NfStatus']['remark']} </em>
												</div>";
										}
									?>
								</div>	
							</div>
						</div>
						<?php
							endforeach;
						?>



						<?php
							foreach($recommenders as $recommender):
						?>
						<div class="tracking-item">
							<div class="tracking-title tt-adjustment"> 
								<strong> RECOMMENDER </strong> <br>
								<small>
									<?php
										echo $recommender['NfReviewer']['User']['staff_name'];
									?>
								</small>
							</div>
							<?php
								// tracking bullet
								echo trackingBullet($recommender['NfStatus']['status']);
							?>
							<div class="tracking-layer tl-adjustment">				
								<h1 class="<?php echo getColor($recommender['NfStatus']['status']); ?>">
									<strong class='uppercase'>
										<?php 
											echo ($recommender['NfStatus']['status'] != 'pending-rejected') ? $recommender['NfStatus']['status'] : "NA"; 
										?>
									</strong><br>
								</h1>
								<div class="tracking-status text-left">
									<!-- <strong class="bigger-text"> Reviewer approved the budget </strong><br> -->
									<small> 
										<?php
											if($recommender['NfStatus']['status'] == 'approved'){
												echo "<strong> Date approved : </strong>";
												echo date('d F Y',strtotime($recommender['NfStatus']['modified']));
											}
											elseif($recommender['NfStatus']['status'] == 'rejected'){
												echo "<strong> Date rejected : </strong>";
												echo date('d F Y',strtotime($recommender['NfStatus']['modified']));
											}
										?>
									</small><br>
									<?php
										// calculate delay time
										$current = new DateTime($recommender['NfStatus']['modified']);

										$diff = $current->diff($previous);
										$diffDay = $diff->d;

										$delay = $diffDay - $setting['Setting']['max_review_day_memo'];

										//reset previous to current 
										$previous = $current;
										$i = $i+1;
									?>
									<small>
										<strong> Delay Time : </strong>
										<?php 
											if($delay > 0)
												echo ($delay == 1) ? $delay."  day" : $delay."  days";
											else
												echo "<em> None </em>"; 
										?> 
									</small>
									<?php
										if(!empty($recommender['NfStatus']['remark'])){
											echo "<div class='tracking-remark small text-left'>
													<strong> Remark :</strong><br>
													<em> {$recommender['NfStatus']['remark']} </em>
												</div>";
										}
									?>
								</div>	
							</div>
						</div>
						<?php
							endforeach;
						?>

						

						<?php
							foreach($approvers as $approver):
						?>
						<div class="tracking-item">
							<div class="tracking-title tt-adjustment"> 
								<strong> APPROVER </strong> <br>
								<small>
									<?php
										echo $approver['NfReviewer']['User']['staff_name'];
									?>
								</small>
							</div>
							<?php
								// tracking bullet
								echo trackingBullet($approver['NfStatus']['status']);
							?>
							<div class="tracking-layer tl-adjustment">				
								<h1 class="<?php echo getColor($approver['NfStatus']['status']); ?>">
									<strong class='uppercase'>
										<?php 
											echo ($approver['NfStatus']['status'] != 'pending-rejected') ? $approver['NfStatus']['status'] : "NA"; 
										?>
									</strong><br>
								</h1>
								<div class="tracking-status text-left">
									<!-- <strong class="bigger-text"> Reviewer approved the budget </strong><br> -->
									<small> 
										<?php
											if($approver['NfStatus']['status'] == 'approved'){
												echo "<strong> Date approved : </strong>";
												echo date('d F Y',strtotime($approver['NfStatus']['modified']));
											}
											elseif($approver['NfStatus']['status'] == 'rejected'){
												echo "<strong> Date rejected : </strong>";
												echo date('d F Y',strtotime($approver['NfStatus']['modified']));
											}
										?>
									</small><br>
									<?php
										// calculate delay time
										$current = new DateTime($approver['NfStatus']['modified']);

										$diff = $current->diff($previous);
										$diffDay = $diff->d;

										$delay = $diffDay - $setting['Setting']['max_review_day_memo'];

										//reset previous to current 
										$previous = $current;
										$i = $i+1;
									?>
									<small>
										<strong> Delay Time : </strong>
										<?php 
											if($delay > 0)
												echo ($delay == 1) ? $delay."  day" : $delay."  days";
											else
												echo "<em> None </em>"; 
										?> 
									</small>
									<?php
										if(!empty($approver['NfStatus']['remark'])){
											echo "<div class='tracking-remark small text-left'>
													<strong> Remark :</strong><br>
													<em> {$approver['NfStatus']['remark']} </em>
												</div>";
										}
									?>
								</div>	
							</div>
						</div>
						<?php
							endforeach;
						?>


						


					</div>
				</div>
			</section>

		</div>
		<div class="col-md-4">
			<section class="panel">
				<header class="panel-heading">
					Non Financial Memo Participant
				</header>
					<?php
						function displayStatus($status){
							$statusApproved = "<a class='btn btn-xs btn-success'><i class='fa fa-check-circle'></i>  Approved</a>";
							$statusPending = "<a class='btn btn-xs btn-warning'><i class='fa fa-circle'></i>  Pending</a>";
							$statusRejected = "<a class='btn btn-xs btn-danger'><i class='fa fa-times-circle'></i>  Rejected</a>";
							$otherStatus = "<a class='btn btn-xs btn-inverse'><i class='fa fa-circle-o'></i>  Not Applicable</a>";

							if($status == 'approved')
								return $statusApproved;
							elseif($status == 'rejected')
								return $statusRejected;
							elseif($status == 'pending')
								return $statusPending;
							else
								return $otherStatus;
						}
					?>
				
					<ul class="text-center">
						<!-- <h5 class="participant-requestor"> <strong> REQUESTOR </strong> </h5> -->
						<h5 class="participant-approver navy-bg"> <strong> REQUESTOR </strong> </h5>

						<li class="participant-list-item">
							<?php
								echo $this->Html->Image('unitar-logo-only.png',array('class'=>'participant-image rounded-img'));
							?>
							<strong>
								<?php
									echo $memo['User']['staff_name'];
								?>
							</strong><br/>
							<small>
								<?php
									echo $memo['User']['Department']['department_name'];
								?>
							</small>
						</li>
						<!-- Reviewer -->
						<!-- <h5 class="participant-reviewer yellow-bg"> <strong> REVIEWER </strong> </h5> -->
						<h5 class="participant-approver navy-bg"> <strong> REVIEWER </strong> </h5>

						<?php
							foreach($reviewers as $reviewer):
						?>
							<li class="participant-list-item"> 
								<?php
									echo $this->Html->Image('unitar-logo-only.png',array('class'=>'participant-image rounded-img'));
								?>
								<strong>
									<?php
										echo $reviewer['NfReviewer']['User']['staff_name'];
									?>
								</strong><br/>
								<small>
									<?php
										echo $reviewer['NfReviewer']['User']['designation'];
									?>
								</small><br>
								<?php
									echo displayStatus($reviewer['NfStatus']['status']);
								?>
							</li>
						<?php
							endforeach;
						?>

						<!-- Recommender -->
						<!-- <h5 class="participant-reviewer orange-bg"> <strong> RECOMMENDER </strong> </h5> -->
						<h5 class="participant-approver navy-bg"> <strong> RECOMMENDER </strong> </h5>

						<?php
							foreach($recommenders as $recommender):
						?>
							<li class="participant-list-item"> 
								<?php
									echo $this->Html->Image('unitar-logo-only.png',array('class'=>'participant-image rounded-img'));
								?>
								<strong>
									<?php
										echo $recommender['NfReviewer']['User']['staff_name'];
									?>
								</strong><br/>
								<small>
									<?php
										echo $recommender['NfReviewer']['User']['designation'];
									?>
								</small><br>
								<?php
									echo displayStatus($recommender['NfStatus']['status']);
								?>
							</li>
						<?php
							endforeach;
						?>


						

						<!-- Approver -->
						<h5 class="participant-approver navy-bg"> <strong> APPROVER </strong> </h5>
						<?php
							foreach($approvers as $approver):
						?>
							<li class="participant-list-item"> 
								<?php
									echo $this->Html->Image('unitar-logo-only.png',array('class'=>'participant-image rounded-img'));
								?>
								<strong>
									<?php
										echo $approver['NfReviewer']['User']['staff_name'];
									?>
								</strong><br/>
								<small>
									<?php
										echo $approver['NfReviewer']['User']['designation'];
									?>
								</small><br>
								<?php
									echo displayStatus($approver['NfStatus']['status']);
								?>
							</li>
						<?php
							endforeach;
						?>
					</ul>

			</section>
		</div>
	</div>

</section>