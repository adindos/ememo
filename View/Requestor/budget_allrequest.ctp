<section class="mems-content">
	<!-- page start-->

	<div class="row">

		<div class="col-lg-12">

			<section class="panel">

				<header class="panel-heading">
					Budget List
 					
					<span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
				</header>

				<div class="panel-body">
				<table class="table table-hover p-table">
					<thead>
						<tr>
							<th class="col-lg-6">Budget Title</th>
							<th class="text-center">Progress</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="p-name">
								<a class="" href="/nizam/budgetDashboard">Budget Request for Monitor</a>
								<br>
								<small>Created on 27/11/2014</small>
							</td>
							<td class="p-progress text-center">
								<span class="badge bg-success">99%</span>
							</td>
							<td class="text-center">
								<span class="label label-primary">Active</span>
							</td>
							<td class="text-center">
								<!-- <a href="project_details.html" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a> -->
								<?php
									echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'nizam','action'=>'budgetDashboard'),array('escape'=>false,'class'=>'btn btn-primary btn-xs'));
								?>
							</td>
						</tr>
						<tr>
							<td class="p-name">
								<a class="" href="/nizam/budgetDashboard" >Budget Request for Monitor</a>
								<br>
								<small>Created on 27/11/2014</small>
							</td>
							<td class="p-progress text-center">
								<span class="badge bg-success">99%</span>
							</td>
							<td class="text-center">
								<span class="label label-primary">Active</span>
							</td>
							<td class="text-center">
								<!-- <a href="project_details.html" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a> -->
								<?php
									echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'nizam','action'=>'budgetDashboard'),array('escape'=>false,'class'=>'btn btn-primary btn-xs'));
								?>
							</td>
						</tr>
						<tr>
							<td class="p-name">
								<a class="" href="/nizam/budgetDashboard">Budget Request for Monitor</a>
								<br>
								<small>Created on 27/11/2014</small>
							</td>
							<td class="p-progress text-center">
								<span class="badge bg-success">99%</span>
							</td>
							<td class="text-center">
								<span class="label label-primary">Active</span>
							</td>
							<td class="text-center">
								<!-- <a href="project_details.html" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a> -->
								<?php
									echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'nizam','action'=>'budgetDashboard'),array('escape'=>false,'class'=>'btn btn-primary btn-xs'));
								?>
							</td>
						</tr>
					</tbody>
				</table>
				</div>

			</section>

		</div>

	</div>

	<!-- page end-->

</section>
