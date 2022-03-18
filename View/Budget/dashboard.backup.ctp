<section class="mems-content">
	<div class="alert alert-info fade in">
      <strong>Attention</strong> This budget requires your action to accept / reject the request. You may go to approval / rejection page <strong><u><a href="#"> here </a></u></strong>.
  </div>
	<div class="row">
		<div class="col-md-8">
			<section class="panel">
				<div class="bio-graph-heading project-heading">
				<strong> 
					<?php
						echo $budget['Budget']['title'];
					?>
				</strong><br/>
				<small>
					<?php
						echo $budget['Department']['department_name'];
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
						<br/>
						<h5 class="bold">PRIORITY</h5>
						<ul class="nav nav-pills nav-stacked labels-info ">
							<li><i class=" fa fa-circle text-danger"></i> High Priority</p></li>
						</ul>

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
							echo $this->Html->link('View Budget Details',array('controller'=>'reviewer','action'=>'budget'),array('escape'=>false,'class'=>'btn btn-lg btn-success btn-shadow btn-round full-width'));
						?>
						<hr>
						<div class="widget red-bg">
							<div class="widget-content">
								<p class="stats"> RM <?php echo $totalBudget; ?> </p>
							</div>
							<div class="widget-title">
								Total Budget 
							</div>
						</div>

						<div class="widget turqoise-bg">
							<div class="widget-content">
								<p class="stats">
									<?php
										echo $budget['Budget']['submission_no'];
									?>
								</p>
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
						<h3 class="timeline-title">Budget Tracking</h3>
						<p class="t-info">Track the budget status and delay</p>
					</div>
					<div class="timeline">
					
						<article class="timeline-item">
							<div class="timeline-desk">
								<div class="panel">
									<div class="panel-body">
										<span class="arrow"></span>
										<span class="timeline-icon timeline-icon-now green"></span>
										<h1 class="timeline-date green">
											<strong>NOW </strong><br/>26 January 2015
										</h1>
										<p class="bigger-text">
											Waiting for action by Reviewer 2 <br/>
											<small> Till now, the system is waiting for approval / rejection by Reviewer 2.</small>
										</p>
									</div>
								</div>
							</div>
						</article>
						<article class="timeline-item">
							<div class="timeline-desk">
								<div class="panel">
									<div class="panel-body">
										<span class="arrow"></span>
										<span class="timeline-icon light-green"></span>
										<h1 class="timeline-date smaller-text">
											<strong>12 July 2015</strong><br/> 08:25 am
										</h1>
										<p>Reviewer 1 approved the budget</p>
									</div>
								</div>
							</div>
						</article>
						<article class="timeline-item">
							<div class="timeline-desk">
								<div class="panel">
									<div class="panel-body">
										<span class="arrow"></span>
										<span class="timeline-icon light-green"></span>
										<h1 class="timeline-date smaller-text">
											<strong>12 July 2015</strong><br/> 08:25 am
										</h1>
										<p>Requestor resubmit the budget</p>
									</div>
								</div>
							</div>
						</article>
						<article class="timeline-item">
							<div class="timeline-desk">
								<div class="panel">
									<div class="panel-body">
										<span class="arrow-alt"></span>
										<span class="timeline-icon red"></span>
										<h1 class="timeline-date smaller-text">
											<strong>12 July 2015</strong><br/> 08:25 am
										</h1>
										<p>Reviewer 1 reject the budget with a remark</p>
										<div class="notification">
											<i class="fa fa-exclamation-circle"></i> Please reduce the budget
										</div>
									</div>
								</div>
							</div>
						</article>
						<article class="timeline-item">
							<div class="timeline-desk">
								<div class="panel">
									<div class="panel-body">
										<span class="arrow"></span>
										<span class="timeline-icon light-green"></span>
										<h1 class="timeline-date smaller-text">
											<strong>12 July 2015</strong><br/> 08:25 am
										</h1>
										<p>Requestor request a budget</p>
									</div>
								</div>
							</div>
						</article>
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
						<h5 class="participant-requestor"> <strong> REQUESTOR </strong> </h5>
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