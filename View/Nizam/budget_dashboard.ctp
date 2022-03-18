<section class="mems-content">
	<div class="alert alert-info fade in">
      <strong>Attention</strong> This budget requires your action to accept / reject the request. You may go to approval / rejection page <strong><u><a href="#"> here </a></u></strong>.
  </div>
	<div class="row">
		<div class="col-md-8">
			<section class="panel">
				<div class="bio-graph-heading project-heading">
				<strong> Budget Request for Extra Monitor </strong><br/>
				<small> I.T Department </small>
				</div>
				<div class="panel-body">
					<div class="col-lg-8">
						<h5 class="bold"> BUDGET DESCRIPTION </h5>
						<p class="text-justify">
							The IT Department requires extra monitors to help with the increasing staff and to instill good and efficient productivity of the workspace. The monitor can help us develop a good and better product in the
							future as we don't have to switch between windows that cause we to lose 2 second everytime we switch between these windows. Just imagine we switch 1000 times a day. That is about 2000 seconds or 33.3 minutes wasted.
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
								<p class="stats"> RM 3,000,000 </p>
							</div>
							<div class="widget-title">
								Total Budget 
							</div>
						</div>

						<div class="widget turqoise-bg">
							<div class="widget-content">
								<p class="stats">2</p>
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

				
					<ul class="text-center">
						<h5 class="participant-requestor"> <strong> REQUESTOR </strong> </h5>
						<li class="participant-list-item">
							<?php
								echo $this->Html->Image('chat-avatar2.jpg',array('class'=>'participant-image rounded-img'));
							?>
							<strong> Mr Muhammad Nizam </strong><br/>
							<small> Head of IT Department </small>
						</li>
						<h5 class="participant-reviewer yellow-bg"> <strong> REVIEWER </strong> </h5>
						<li class="participant-list-item"> 
							<?php
								echo $this->Html->Image('pro-ac-1.png',array('class'=>'participant-image rounded-img'));
							?>
							<strong> Mdm Syikin Yaakob </strong><br/>
							<small> Financial Advisor </small>
						</li>
						<li class="participant-list-item"> 
							<?php
								echo $this->Html->Image('chat-avatar.jpg',array('class'=>'participant-image rounded-img'));
							?>
							<strong> Mr Faridi Matin </strong><br/>
							<small> Financial Advisor II </small>
						</li>
						<h5 class="participant-approver blue-bg"> <strong> APPROVER </strong> </h5>
						<li class="participant-list-item"> 
							<?php
								echo $this->Html->Image('pro-ac-1.png',array('class'=>'participant-image rounded-img'));
							?>
							<strong> Ms Aisyah Ismail </strong><br/>
							<small> CEO </small>
						</li>
					</ul>

			</section>
		</div>
	</div>

</section>