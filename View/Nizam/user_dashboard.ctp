<section class="mems-content">
	<div class="row">
		<aside class="profile-nav col-lg-4">
			<section class="panel">
				<div class="user-heading">
					<a href="#" class="round">
 						<?php
							// echo $this->Html->Image('profile-avatar.jpg');
                            // echo $this->Html->Image('Faces_Users-13.png');

						?>
					</a>
					<h1>Muhammad Nizams</h1>
					<h5> Head of IT Department </h5>
					<p>nizam@grtech.com.my</p>
					<?php
						// echo $this->Html->link('Edit Profile',array('controller'=>'','action'=>''),array('escape'=>false,'class'=>'btn btn-xs btn-default'));
					?>
				</div>

				<!-- <div class="user-quick white-bg text-center">
					<?php
						// echo $this->Html->link('Create New Budget',array('controller'=>'','action'=>''),array('escape'=>false,'class'=>'btn btn-primary btn-round btn-lg btn-shadow margin-bottom'));
						// echo "<br/>";
						// echo $this->Html->link('Create New Memo',array('controller'=>'','action'=>''),array('escape'=>false,'class'=>'btn btn-warning btn-round btn-lg btn-shadow margin-bottom'));
						// echo "<br/>";
					?>
				</div> -->

				<ul class="nav nav-pills nav-stacked">
					<li>
                        <?php
                            echo $this->Html->link('<i class="fa fa-money"></i> Create New Budget',array('controller'=>'requestor','action'=>'budgetList'),array('escape'=>false,'class'=>'bold'));
                        ?>
                    </li>
                    <li>
                        <?php
                            echo $this->Html->link('<i class="fa fa-paste"></i> Create New Memo',array('controller'=>'requestor','action'=>'memoList'),array('escape'=>false,'class'=>'bold'));
                        ?>
                    </li>
					<!-- <li><a href="profile-edit.html"> <i class="fa fa-edit"></i> Edit profile</a></li> -->
				</ul>

			</section>
        </aside>
        <section class="col-lg-8">
        	<div class="noti">
        		<div class="alert alert-info fade in">
	                <strong>Attention!</strong> You have to respond to a memo / budget.
	            </div>
        	</div>
        	<div class="row state-overview">
	        	<div class="col-lg-4">
        			<section class="panel">
        				<div class="symbol terques">
        					<i class="fa fa-money"></i>
        				</div>
        				<div class="value">
        					<h1 class="count">
        						0
        					</h1>
        					<p>Budget</p>
        				</div>
        			</section>
        		</div>
        		<div class="col-lg-4">
        			<section class="panel">
        				<div class="symbol red">
        					<i class="fa fa-tags"></i>
        				</div>
        				<div class="value">
        					<h1 class=" count2">
        						0
        					</h1>
        					<p>Financial Memo</p>
        				</div>
        			</section>
        		</div>
        		<div class="col-lg-4">
        			<section class="panel">
        				<div class="symbol yellow">
        					<i class="fa fa-barcode"></i>
        				</div>
        				<div class="value">
        					<h1 class=" count3">
        						0
        					</h1>
        					<p>No Financial Memo</p>
        				</div>
        			</section>
        		</div>
        	</div>

        	<!-- Action to be taken -->
        	<div class="row">
        		<div class="col-lg-6">
        			<section class="panel">
        				<div class="panel-body progress-panel">
        					<div class="task-progress">
        						<h1>Budget</h1>
        						<p class="small">Budget that requires your attention</p>
        					</div>
        				</div>
        				<table class="table table-hover personal-task">
        					<tbody>
        						<tr>
        							<td>1</td>
        							<td>
        								Target Sell
        							</td>
        							<td>
        								<span class="badge bg-important">75%</span>
        							</td>
        							<td>
        								<!-- <div id="work-progress1"></div> -->
                                        <?php
                                            echo $this->Html->link('Dashboard',array('controller'=>'nizam','action'=>'budgetDashboard'),array('escape'=>false,'class'=>'btn btn-xs btn-round btn-primary tooltips','data-toggle'=>'tooltips','data-original-title'=>'You are the approver for this budget'));
                                        ?>
        							</td>
        						</tr>
        						<tr>
        							<td>2</td>
        							<td>
        								Product Delivery
        							</td>
        							<td>
        								<span class="badge bg-success">43%</span>
        							</td>
        							<td>
        								<!-- <div id="work-progress2"></div> -->
                                        <?php
                                            echo $this->Html->link('Dashboard',array('controller'=>'nizam','action'=>'budgetDashboard'),array('escape'=>false,'class'=>'btn btn-xs btn-round btn-primary tooltips','data-toggle'=>'tooltips','data-original-title'=>'You are the reviewer for this budget'));
                                        ?>
        							</td>
        						</tr>
        						<tr>
        							<td>3</td>
        							<td>
        								Payment Collection
        							</td>
        							<td>
        								<span class="badge bg-info">67%</span>
        							</td>
        							<td>
        								<!-- <div id="work-progress3"></div> -->
                                        <?php
                                            echo $this->Html->link('Dashboard',array('controller'=>'nizam','action'=>'budgetDashbaord'),array('escape'=>false,'class'=>'btn btn-xs btn-round btn-primary tooltips','data-toggle'=>'tooltips','data-original-title'=>'You are the approver for this budget'));
                                        ?>
        							</td>
        						</tr>
        						<tr>
        							<td>4</td>
        							<td>
        								Work Progress
        							</td>
        							<td>
        								<span class="badge bg-warning">30%</span>
        							</td>
        							<td>
        								<!-- <div id="work-progress4"></div> -->
                                        <?php
                                            echo $this->Html->link('Dashboard',array('controller'=>'nizam','action'=>'budgetDashbaord'),array('escape'=>false,'class'=>'btn btn-xs btn-round btn-primary tooltips','data-toggle'=>'tooltips','data-original-title'=>'You are the reviwer for this budget'));
                                        ?>
        							</td>
        						</tr>
        						<tr>
        							<td>5</td>
        							<td>
        								Delivery Pending
        							</td>
        							<td>
        								<span class="badge bg-primary">15%</span>
        							</td>
        							<td>
        								<!-- <div id="work-progress5"></div> -->
                                        <?php
                                            echo $this->Html->link('Dashboard',array('controller'=>'nizam','action'=>'budgetDashbaord'),array('escape'=>false,'class'=>'btn btn-xs btn-round btn-primary tooltips','data-toggle'=>'tooltips','data-original-title'=>'You are the reviewer for this budget'));
                                        ?>
        							</td>
        						</tr>
        					</tbody>
        				</table>
        			</section>
        		</div>
        		<div class="col-lg-6">
        			<section class="panel">
        				<div class="panel-body progress-panel">
        					<div class="task-progress">
        						<h1>Memo</h1>
        						<p class="small">Memo that requires your attention</p>
        					</div>
        				</div>
        				<table class="table table-hover personal-task">
        					<tbody>
        						<tr>
        							<td colspan="4" class="text-center">
        								You don't have any pending memo to act now. <br/>
        								<a data-scroll class="btn btn-xs btn-primary btn-round margin-top" href="#my-budget-memo">
        									<small> My Memo </small>
        								</a>
        							</td>
        						</tr>
        					</tbody>
        				</table>
        			</section>
        		</div>
        	</div>
        </section>
    </div>

    <hr>
    <div id="my-budget-memo" class="my-section text-center margin-bottom">
    	<?php
    		echo $this->Html->link('MY BUDGET & MEMO','#',array('escape'=>false,'class'=>'btn btn-lg btn-danger btn-round'));
    	?>
    </div>
    <!-- My Budget and Memo -->
    <div class="row">
    	<!-- Budget -->
    	<div class="col-lg-6">
    		<section class="panel">
    			<header class="panel-heading turqoise-bg">
    				<strong> My Budget </strong>
    				<span class="pull-right">
    					<!-- <a href="#" class=" btn btn-success btn-xs"> View All Budget</a> -->
                        <?php
                            echo $this->Html->link('View All Budget',array('controller'=>'requestor','action'=>'budgetList'),array('class'=>'btn btn-success btn-xs'));
                        ?>
    				</span>
    			</header>
				<table class="table table-hover p-table">
					<thead>
						<tr>
							<th class="col-lg-6">Project Name</th>
							<th class="text-center">Progress</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="p-name">
								<a class="">Budget Request for Monitor</a>
								<br>
								<small>Created on 27/11/2014</small>
							</td>
							<td class="p-progress text-center">
								<span class="badge bg-default">59%</span>
							</td>
							<td>
								<span class="label label-primary">Active</span>
							</td>
							<td>
								<!-- <a href="project_details.html" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a> -->
								<?php
									echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'nizam','action'=>'budgetDashboard'),array('escape'=>false,'class'=>'btn btn-primary btn-xs'));
								?>
							</td>
						</tr>
						<tr>
							<td class="p-name">
								<a class="">Budget Request for Monitor</a>
								<br>
								<small>Created on 27/11/2014</small>
							</td>
							<td class="p-progress text-center">
								<span class="badge bg-default">100%</span>
							</td>
							<td>
								<span class="label label-success">Approved</span>
							</td>
							<td>
								<!-- <a href="project_details.html" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a> -->
								<?php
									echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'nizam','action'=>'budgetDashboard'),array('escape'=>false,'class'=>'btn btn-primary btn-xs'));
								?>
							</td>
						</tr>
						<tr>
							<td class="p-name">
								<a class="">Budget Request for Monitor</a>
								<br>
								<small>Created on 27/11/2014</small>
							</td>
							<td class="p-progress text-center">
								<span class="badge bg-default">49%</span>
							</td>
							<td>
								<span class="label label-danger">Rejected</span>
							</td>
							<td>
								<!-- <a href="project_details.html" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a> -->
								<?php
									echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'nizam','action'=>'budgetDashboard'),array('escape'=>false,'class'=>'btn btn-primary btn-xs'));
								?>
							</td>
						</tr>
					</tbody>
				</table>
    		</section>
    	</div>

    	<!-- Memo -->
    	<div class="col-lg-6">
    		<section class="panel">
    			<header class="panel-heading yellow-bg">
    				<strong> My Memo </strong>
    				<span class="pull-right">
    					<!-- <a href="#" class=" btn btn-info btn-xs"> View All Memo</a> -->
                        <?php
                            echo $this->Html->link('View All Memo',array('controller'=>'requestor','action'=>'memoList'),array('class'=>'btn btn-info btn-xs'));
                        ?>
    				</span>
    			</header>
				<table class="table table-hover p-table">
					<thead>
						<tr>
							<th class="col-lg-6">Project Name</th>
							<th class="text-center">Progress</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="p-name">
								<a class="">Budget Request for Monitor</a>
								<br>
								<small>Created on 27/11/2014</small>
							</td>
							<td class="p-progress text-center">
								<span class="badge bg-default">99%</span>
							</td>
							<td>
								<span class="label label-danger">Rejected</span>
							</td>
							<td>
								<!-- <a href="project_details.html" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a> -->
								<?php
									echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'nizam','action'=>'memoDashboard'),array('escape'=>false,'class'=>'btn btn-primary btn-xs'));
								?>
							</td>
						</tr>
						<tr>
							<td class="p-name">
								<a class="">Budget Request for Monitor</a>
								<br>
								<small>Created on 27/11/2014</small>
							</td>
							<td class="p-progress text-center">
								<span class="badge bg-default">100%</span>
							</td>
							<td>
								<span class="label label-success">Approved</span>
							</td>
							<td>
								<!-- <a href="project_details.html" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a> -->
								<?php
									echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'nizam','action'=>'memoDashboard'),array('escape'=>false,'class'=>'btn btn-primary btn-xs'));
								?>
							</td>
						</tr>
						<tr>
							<td class="p-name">
								<a class="">Budget Request for Monitor</a>
								<br>
								<small>Created on 27/11/2014</small>
							</td>
							<td class="p-progress text-center">
								<span class="badge bg-default">99%</span>
							</td>
							<td>
								<span class="label label-primary">Active</span>
							</td>
							<td>
								<!-- <a href="project_details.html" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a> -->
								<?php
									echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'nizam','action'=>'memoDashboard'),array('escape'=>false,'class'=>'btn btn-primary btn-xs'));
								?>
							</td>
						</tr>
					</tbody>
				</table>
    		</section>
    	</div>
    </div>
</section>