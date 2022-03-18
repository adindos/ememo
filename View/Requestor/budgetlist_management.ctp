<section class="mems-content">
	<!-- page start-->

	<div class="row">

		<div class="col-lg-12">

			<section class="panel">

				<header class="panel-heading">
					Budget List
 					<!-- <button type="button" class="btn btn-round btn-primary btn-xs margin-left">Upload New Budget</button> -->
 					

					<span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
				</header>

				<div class="panel-body">
					<header class="panel-heading yellow-bg">
	    				<strong> My Pending Request </strong>	    				
	    			</header>
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
									<a class="" href="">Budget Request for Monitor</a>
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
									<a class="" href="">Budget Request for Monitor</a>
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

				<div class="panel-body">
					<header class="panel-heading green-bg">
	    				<strong> My Reviewed Request </strong>	    				
	    			</header>
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
									<a class="" href="">Budget Request for Monitor</a>
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
									<a class="" href="">Budget Request for Monitor</a>
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
									<a class="" href="">Budget Request for Monitor</a>
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
				<h4 class="modal-title">New Budget Archive</h4>
			</div>
			<?php
				echo $this->Form->create('Budget',array('url'=>array('controller'=>'','action'=>''),
										'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
										'class'=>'form-horizontal','type'=>'file'));
			?>
			<div class="modal-body">
				<div class="form-group">
					<label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Company</label>
					<div class="col-lg-9 col-sm-9">
						<?php
							$companies = array("UNITAR","UNIRAZAK");
							echo $this->Form->input('BudgetArchive.company',array('type'=>'select','options'=>$companies,'class'=>' select2 full-width','style'=>'width:98%','placeholder'=>'Company'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Budget Year</label>
					<div class="col-lg-9 col-sm-9">
						<?php
							echo $this->Form->input('BudgetArchive.year',array('type'=>'text','class'=>'form-control','placeholder'=>'Budget Year'));
						?>
					</div>
				</div>	
				<div class="form-group">
					<label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Description</label>
					<div class="col-lg-9 col-sm-9">
						<?php
							echo $this->Form->input('BudgetArchive.description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'Some description her'));
						?>
					</div>
				</div>	
				<div class="form-group">
					<label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Upload</label>
					<div class="col-lg-9 col-sm-9">
						<?php
							echo $this->Form->input('BudgetArchive.file',array('type'=>'file','class'=>'default'));
						?>
					</div>
				</div>	

			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
				<button class="btn btn-success" type="button">Save changes</button>
			</div>
		</div>
	</div>
</div>
  <!-- modal -->