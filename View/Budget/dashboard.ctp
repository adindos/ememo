<?php
	$this->Html->addCrumb('Budget', array('controller' => 'budget', 'action' => 'index'));
	$this->Html->addCrumb(' ( Dashboard ) '.$budget['Budget']['year'], $this->here,array('class'=>'active'));
?>

<section class="mems-content">
	<!-- <div class="alert alert-info fade in">
		<strong>Attention</strong> This budget requires your action to accept / reject the request. You may go to approval / rejection page <strong><u><a href="#"> here </a></u></strong>.
	</div> -->
	<div class="row">
		<div class="col-md-8">
			<section class="panel">
				<div class="bio-graph-heading project-heading blue-bg">
					<strong> 
						<?php
							echo $budget['Budget']['year'];
						?>
					</strong><br/>
					<small>
						<?php
							echo $budget['Company']['company'];
						?> 
					</small>
				</div>
				<div class="panel-body">
					<div class="col-lg-8">
						<h5 class="bold"> BUDGET DESCRIPTION </h5>
						<p class="text-justify">
							<?php
								echo $budget['Budget']['description'];
							?>
						</p>
						
						<h5 class="bold"> BUDGET YEAR </h5>
						<p>
							<?php
								echo $budget['Budget']['year'];
							?>
						</p>

						<h5 class="bold">PROGRESS</h5>
						<?php
							// progress calculation
							$totalStatus = count($finances)+count($approvers);
							$countApproved =0;
							$isRejected = false;
							$isApproved = false;
							foreach($finances as $f):
								if($f['BStatus']['status'] == 'approved')
									$countApproved++;
								elseif($f['BStatus']['status'] == 'rejected')
									$isRejected = true;
							endforeach;

							foreach($approvers as $a):
								if($a['BStatus']['status'] == 'approved')
									$countApproved++;
								elseif($a['BStatus']['status'] == 'rejected')
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
								echo "<small> Budget is <strong>rejected</strong> by Financial Controller/CFO. Pending for resubmission by requestor. </small>";
							else
								echo "<small>Budget approval is <strong class='bigger-text'>{$progress} %</strong> completed.</small>"
						?>
					</div>

					<div class="col-lg-4">
						<?php
							$encBudgetID = $this->Mems->encrypt($budget['Budget']['budget_id']);
							if(($activeUser['user_id'] == $budget['Budget']['user_id'])){
								if ($isRejected){
									echo $this->Html->link('<strong> Resubmit Budget </strong><br><small> Resubmit this budget </small>',array('controller'=>'budget','action'=>'request',$encBudgetID),array('escape'=>false,'class'=>'btn btn-success  full-width'));

									echo "<br><br>";

								}
								//allow edit only if status is not approved yet, or approved with fmemo locked
								elseif(empty($budget['Budget']['budget_status'])||($budget['Budget']['budget_status']=='approved'&&$setting['Setting']['financial_memo']))
									{
									echo $this->Html->link('<strong> Update Budget </strong><br><small> Update this budget </small>',array('controller'=>'budget','action'=>'request',$encBudgetID),array('escape'=>false,'class'=>'btn btn-success  full-width'));
									
									echo "<br><br>";

								}
								elseif($budget['Budget']['budget_status']=='approved'&&!$setting['Setting']['financial_memo']){//if budget already approved, to edit need to lock fmemo first
									echo $this->Html->link('<strong> Update Budget </strong><br><small> Please disable memo access first </small>',array('controller'=>'budget','action'=>'request',$encBudgetID),array('disabled','escape'=>false,'class'=>'btn btn-success  full-width'));
									
									echo "<br><br>";
								}

							}
							
							echo $this->Html->link('<strong> Budget Details </strong><br><small> View budget details </small>',array('controller'=>'budget','action'=>'review',$encBudgetID),array('escape'=>false,'class'=>'btn bg-primary btn-shadow full-width'));
						?>
						<hr>
						<!-- <div class="widget blue-bg">
							<div class="widget-content">
								<p class="stats"> RM <?php echo $totalBudget; ?> </p>
							</div>
							<div class="widget-title">
								Total Budget 
							</div>
						</div> -->

						<div class="widget blue-bg">
							<div class="widget-content">
								<p class="stats">
									<?php
										echo $budget['Budget']['submission_no'];
									?>
								</p>
							</div>
							<div class="widget-title">
								<small> Submission / Resubmission </small>
							</div>
						</div>
						
					</div>
				</div>

			</section>

			<section class="panel">
				<div class="panel-body">
					<div class="text-center">
						<h3 class="timeline-title">Budget Tracking</h3>
						<p class="t-info">Track the budget status and delay</p>
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
									echo "<a data-scroll href='#current-tracking' class='tracking-bullet red now track-click'></a>";
									echo "<a data-scroll href='#current-tracking' class='btn btn-danger track-click'>
											<i class='fa fa-times-circle'></i>
											<strong> REJECTED </strong><br>
											<small> Pending for requestor to resubmit </small>
										</a>";
								}
								elseif($isApproved){
									echo "<a data-scroll href='#current-tracking' class='tracking-bullet green now track-click'></a>";
									echo "<a data-scroll href='#current-tracking' class='btn btn-green track-click'>
											<i class='fa fa-check-circle'></i>
											<strong> APPROVED </strong><br>
											<small> Budget is approved </small>
										</a>";
								}
								else{
									echo "<a data-scroll href='#current-tracking' class='tracking-bullet yellow now track-click'></a>";
									echo "<a data-scroll href='#current-tracking' class='btn btn-warning track-click'>
											<i class='fa fa-circle'></i>
											<strong> PENDING </strong><br>
											<small> Budget is in review </small>
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
										echo $budget['User']['staff_name'];
									?>
								</h1>
								<div class="tracking-status">
									<strong class="bigger-text"> Budget Created </strong><br>
									<small> 
										Budget created on :  
										<?php
											echo date('d F Y',strtotime($budget['Budget']['created']));
										?>
									</small>
									<br>
									<?php
										// if($budget['Budget']['submission_no'] > 1):
										// 	echo "<small>Latest resubmission on : ".date('d F Y',strtotime($budget['Budget']['modified'])) . "</small>";
										// endif;
									?>
									<!-- <br> -->
									<small> 
										Current Submission :  
										<?php
											echo $budget['Budget']['submission_no'];
										?>
									</small><br>
								</div>	
							</div>
						</div>
					</div>
					<div class="tracking-panel">
						<?php
							$i=0;
							foreach($finances as $reviewer):

						?>
						<div class="tracking-item" <?php if($reviewer['BStatus']['status'] == 'pending'  && !$isTracked){ echo "id='current-tracking'";$isTracked=true; } ?> >
							<div class="tracking-title tt-adjustment"> 
								<strong> FINANCIAL CONTROLLER </strong> <br>
								<small>
									<?php
										echo $reviewer['BReviewer']['User']['staff_name'];
									?>
								</small>
							</div>
							<?php
								// tracking bullet
								echo trackingBullet($reviewer['BStatus']['status']);
							?>
							<div class="tracking-layer tl-adjustment">				
								<h1 class="<?php echo getColor($reviewer['BStatus']['status']); ?>">
									<strong class='uppercase'>
										<?php 
											echo ($reviewer['BStatus']['status'] != 'pending-rejected') ? $reviewer['BStatus']['status'] : "NA"; 
										?>
									</strong><br>
								</h1>
								<div class="tracking-status text-left">
									<!-- <strong class="bigger-text"> Reviewer approved the budget </strong><br> -->
									<small> 
										<?php
											if($reviewer['BStatus']['status'] == 'approved'){
												echo "<strong> Date approved : </strong>";
												echo date('d F Y',strtotime($reviewer['BStatus']['modified']));
											}
											elseif($reviewer['BStatus']['status'] == 'rejected'){
												echo "<strong> Date rejected : </strong>";
												echo date('d F Y',strtotime($reviewer['BStatus']['modified']));
											}
										?>
									</small><br>
									<?php
										// calculate delay time

										// first review -- take from budget creation/modified date
										if($i==0){
											$previous = new DateTime($budget['Budget']['modified']); // if first review
										}

										$current = new DateTime($reviewer['BStatus']['modified']);

										$diff = $current->diff($previous);
										$diffDay = $diff->d;

										$delay = $diffDay - $setting['Setting']['max_review_day_budget'];

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
										if(!empty($reviewer['BStatus']['remark'])){
											echo "<div class='tracking-remark small text-left'>
													<strong> Remark :</strong><br>
													<em> {$reviewer['BStatus']['remark']} </em>
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
						<div class="tracking-item" <?php if($approver['BStatus']['status'] == 'pending'  && !$isTracked){ echo "id='current-tracking'";$isTracked=true; } ?>>
							<div class="tracking-title tt-adjustment"> 
								<strong> CFO </strong> <br>
								<small>
									<?php
										echo $approver['BReviewer']['User']['staff_name'];
									?>
								</small>
							</div>
							<?php
								// tracking bullet
								echo trackingBullet($approver['BStatus']['status']);
							?>
							<div class="tracking-layer tl-adjustment">				
								<h1 class="<?php echo getColor($approver['BStatus']['status']); ?>">
									<strong class='uppercase'>
										<?php 
											echo ($approver['BStatus']['status'] != 'pending-rejected') ? $approver['BStatus']['status'] : "NA"; 
										?>
									</strong><br>
								</h1>
								<div class="tracking-status text-left">
									<!-- <strong class="bigger-text"> Reviewer approved the budget </strong><br> -->
									<small> 
										<?php
											if($approver['BStatus']['status'] == 'approved'){
												echo "<strong> Date approved : </strong>";
												echo date('d F Y',strtotime($approver['BStatus']['modified']));
											}
											elseif($approver['BStatus']['status'] == 'rejected'){
												echo "<strong> Date rejected : </strong>";
												echo date('d F Y',strtotime($approver['BStatus']['modified']));
											}
										?>
									</small><br>
									<?php
										// calculate delay time
										$current = new DateTime($approver['BStatus']['modified']);

										$diff = $current->diff($previous);
										$diffDay = $diff->d;

										$delay = $diffDay - $setting['Setting']['max_review_day_budget'];

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
										if(!empty($approver['BStatus']['remark'])){
											echo "<div class='tracking-remark small text-left'>
													<strong> Remark :</strong><br>
													<em> {$approver['BStatus']['remark']} </em>
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
					Budget Participant
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
						<!-- <h5 class="participant-requestor navy-bg"> <strong> REQUESTOR </strong> </h5> -->
						<h5 class="participant-approver navy-bg"> <strong> REQUESTOR </strong> </h5>
						<li class="participant-list-item">
							<?php
								echo $this->Html->Image('unitar-logo-only.png',array('class'=>'participant-image rounded-img'));
							?>
							<strong>
								<?php
									echo $budget['User']['staff_name'];
								?>
							</strong><br/>
							<small>
								<?php
									echo $budget['User']['Department']['department_name'];
								?>
							</small>
						</li>
						<!-- Reviewer -->
						<h5 class="participant-approver navy-bg"> <strong> FINANCIAL CONTROLLER </strong> </h5>
						<!-- <h5 class="participant-reviewer navy-bg"> <strong> REVIEWER </strong> </h5> -->
						<?php
							foreach($finances as $reviewer):
						?>
							<li class="participant-list-item"> 
								<?php
									echo $this->Html->Image('unitar-logo-only.png',array('class'=>'participant-image rounded-img'));
								?>
								<strong>
									<?php
										echo $reviewer['BReviewer']['User']['staff_name'];
									?>
								</strong><br/>
								<small>
									<?php
										echo $reviewer['BReviewer']['User']['designation'];
									?>
								</small><br>
								<?php
									echo displayStatus($reviewer['BStatus']['status']);
								?>
							</li>
						<?php
							endforeach;
						?>
						<!-- Approver -->
						<h5 class="participant-approver navy-bg"> <strong> CFO </strong> </h5>
						<?php
							foreach($approvers as $approver):
						?>
							<li class="participant-list-item"> 
								<?php
									echo $this->Html->Image('unitar-logo-only.png',array('class'=>'participant-image rounded-img'));
								?>
								<strong>
									<?php
										echo $approver['BReviewer']['User']['staff_name'];
									?>
								</strong><br/>
								<small>
									<?php
										echo $approver['BReviewer']['User']['designation'];
									?>
								</small><br>
								<?php
									echo displayStatus($approver['BStatus']['status']);
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
