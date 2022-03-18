<section class="mems-content">
	<div class="alert alert-info fade in">
      <strong>Attention</strong> This memo requires your action to accept / reject the request. You may go to approval / rejection page <strong><u><a href="#"> here </a></u></strong>.
  </div>
	<div class="row">
		<div class="col-md-8">
			<section class="panel">
				<div class="bio-graph-heading project-heading purple-bg">
				<strong><?php echo $memo ['NfMemo']['subject'];?></strong><br/>
				<small> <?php echo $memo['Department']['department_name'];?></small>
				</div>
				<div class="panel-body">
					<div class="col-lg-8">
						<h5 class="bold"> MEMO DESCRIPTION </h5>
						<p class="text-justify">
							<?php 

							?>
							
						</p>
						<br/>
						<h5 class="bold">PRIORITY</h5>
						<ul class="nav nav-pills nav-stacked labels-info ">
							<li><i class=" fa fa-circle text-warning"></i> Medium Priority</p></li>
						</ul>
						<br/>
						<h5 class="bold">MEMO TYPE</h5>
						<p>
						Non-Financial
						</p>

						<br/>

						<h5 class="bold">TAGS</h5>
						<ul class="p-tag-list">
							<li><a href=""><i class="fa fa-tag"></i> Dlor</a></li>
							<li><a href=""><i class="fa fa-tag"></i> Lorem ipsum</a></li>
							<li><a href=""><i class="fa fa-tag"></i> Google</a></li>
							<li><a href=""><i class="fa fa-tag"></i> Excellent</a></li>
						</ul>
					</div>

					<div class="col-lg-4">
						<?php
							echo $this->Html->link('View Memo Details',array('controller'=>'NfMemo','action'=>'review',$memo_id),array('escape'=>false,'class'=>'btn btn-lg btn-success btn-shadow btn-round full-width'));
						?>
						<hr>
						<div class="widget red-bg">
							<div class="widget-content">
								<p class="stats"> None </p>
							</div>
							<div class="widget-title">
								Budget Requested
							</div>
						</div>

						<div class="widget turqoise-bg">
							<div class="widget-content">
								<p class="stats"><?php echo $memo['NfMemo']['submission_no']; ?></p>
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
						<p class="t-info">Track the memo status and delay</p>
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

					<div class="tracking-panel">
						<a class='tracking-bullet primary now'></a>	
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
								<small> 
									Current Submission :  
									<?php
										echo $memo['NfMemo']['submission_no'];
									?>
								</small><br>
							</div>	
						</div>
					</div>
					<div class="tracking-panel">
						<?php
							foreach($reviewers as $reviewer):
						?>
						<div class="tracking-item">
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
									<!-- <strong class="bigger-text"> Reviewer approved the budget </strong><br> -->
									<small> 
										Date Approved : 
										<?php
											echo date('d F Y',strtotime($reviewer['NfStatus']['modified']));
										?>	
									</small><br>
									<small><em> Delay Time : </em></small>
								</div>	
							</div>
						</div>
						<?php
							endforeach;
						?>
						<!-- recommender -->
						<?php
							foreach($Recommenders as $recommender):
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
								<h1 class="<?php echo getColor($reviewer['NfStatus']['status']); ?>">
									<strong class='uppercase'>
										<?php 
											echo ($recommender['NfStatus']['status'] != 'pending-rejected') ? $recommender['NfStatus']['status'] : "NA"; 
										?>
									</strong><br>
								</h1>
								<div class="tracking-status text-left">
									<!-- <strong class="bigger-text"> Reviewer approved the budget </strong><br> -->
									<small> 
										Date Approved : 
										<?php
											echo date('d F Y',strtotime($recommender['NfStatus']['modified']));
										?>	
									</small><br>
									<small><em> Delay Time : </em></small>
								</div>	
							</div>
						</div>
						<?php
							endforeach;
						?>
						<!--  -->

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
										Date Approved : 
										<?php
											echo date('d F Y',strtotime($approver['NfStatus']['modified']));
										?>	
									</small><br>
									<small><em> Delay Time : </em></small>
								</div>	
							</div>
						</div>
						<?php
							endforeach;
						?>
					</div>
					
					</div>
				</div>
			</section>

		</div>
		<div class="col-md-4">
			<section class="panel">
				<header class="panel-heading">
					Memo Participant
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
						<h5 class="participant-requestor"> <strong> REQUESTOR </strong> </h5>
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
						<h5 class="participant-reviewer yellow-bg"> <strong> REVIEWER </strong> </h5>
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
						<!-- Reconmender -->
						<h5 class="participant-reviewer green-bg"> <strong> RECONMENDER </strong> </h5>
						<?php
							foreach($Recommenders as $Recommender):
						?>
							<li class="participant-list-item"> 
								<?php
									echo $this->Html->Image('unitar-logo-only.png',array('class'=>'participant-image rounded-img'));
								?>
								<strong>
									<?php
										echo $Recommender['NfReviewer']['User']['staff_name'];
									?>
								</strong><br/>
								<small>
									<?php
										echo $Recommender['NfReviewer']['User']['designation'];
									?>
								</small><br>
								<?php
									echo displayStatus($Recommender['NfStatus']['status']);
								?>
							</li>
						<?php
							endforeach;
						?>
						<!-- Approver -->
						<h5 class="participant-approver blue-bg"> <strong> APPROVER </strong> </h5>
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