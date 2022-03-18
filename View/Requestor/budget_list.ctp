<section class="mems-content">
	<!-- page start-->

	<div class="row">

		<div class="col-lg-12">

			<section class="panel">

				<header class="panel-heading">
					Budget List
 					<!-- <button type="button" class="btn btn-round btn-primary btn-xs margin-left">Upload New Budget</button> -->
 					<?php
 						echo $this->Html->link('Create New Budget','#modal-upload-budget-archive',array('class'=>'btn btn-round btn-primary btn-xs margin-left','data-toggle'=>'modal'));
 					?>

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
								<a class="" href="/budgetDashboard">Budget Request for Monitor</a>
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
								<a class="" href="/budgetDashboard">Budget Request for Monitor</a>
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
								<a class="" href="/budgetDashboard">Budget Request for Monitor</a>
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

<!-- Modal -->
<div class="modal fade" id="modal-upload-budget-archive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create New Budget</h4>
			</div>
			<?php
				echo $this->Form->create('Budget',array('url'=>array('controller'=>'','action'=>''),
										'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
										'class'=>'form-horizontal','type'=>'file'));
			?>
			<div class="modal-body ">
				<div class="form-group text-center">
	                  <label class="col-sm-2 col-sm-2 control-label">Company</label>
	                  <div class="col-sm-10">
	                      <label class="col-sm-6 col-sm-6 control-label"><b>UNITAR</b></label>
	                  </div>
                
                
	                  <label class="col-sm-2 col-sm-2 control-label">Group</label>
	                  <div class="col-sm-10">
	                    <label class="col-sm-6 col-sm-6 control-label"><b>Corp Shared Services</b></label>
	                  </div>
                
                	 <label class="col-sm-2 col-sm-2 control-label">Department</label>
	                  <div class="col-sm-10">
	                      <label class="col-sm-6 col-sm-6 control-label"><b>ICT</b></label>
	                  </div>
	             </div>
				 <div class="form-group text-center">
	                  <label class="col-sm-2 col-sm-2 control-label">Year</label>
	                  <div class="col-sm-10">	  
	                  	<input type="text" class="form-control" style="width:100px">                     
	                  </div>
                   </div>
				
			<div class="modal-footer text-center">
				<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
				<button class="btn btn-success" type="button" onclick="location.href='index1'">Create Budget</button>
			</div>
		</div>
	</div>
</div>
  <!-- modal -->