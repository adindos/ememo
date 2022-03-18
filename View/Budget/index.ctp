<?php
	$this->Html->addCrumb('Budget', array('controller' => 'budget', 'action' => 'index'));
	$this->Html->addCrumb('My Request', $this->here,array('class'=>'active'));
?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#compSelect').on('change',function(){
        	var compVal = $(this).val();
        	// refresh the dropdown
              $('#acad').html('');
	          $('#nonacad').html('');
	          $('#acad').select2("val",'');
              $('#nonacad').select2("val",'');
            $.ajax({
                url : "<?php echo ACCESS_URL ?>/Budget/deptData/"+compVal,
                dataType: 'json',
                data : "data",
                success: function (data){
                    
                    var acad ;
                    var nonacad ;
                    
                    $('#acad').html('');
                    $('#nonacad').html('');

                    if(data.length > 0){
                    	
                        //console.log(data[0]);
                        $.each(data[0],function(key,object){
                             acad = acad + "<option value='"+key+"'>"+object+"</option>";
                                $('#acad').html(acad);
                        })

                        $.each(data[1],function(key,object){
                             nonacad = nonacad + "<option value='"+key+"'>"+object+"</option>";
                                $('#nonacad').html(nonacad);
                        })
                        
                    
                    }
                    else{
                      $('#acad').html('');
	          		  $('#nonacad').html('');
                      $('#acad').select2("val",'');
             		 $('#nonacad').select2("val",'');
                    }
                }
            });
        });
     });
</script>
<section class="mems-content">
	<!-- page start-->
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<?php
                    echo $this->Form->create('Budget',array('url'=>array('controller'=>'Budget','action'=>'deleteBudgetMulti'),'class'=>'form-horizontal','type'=>'file','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
                    //echo $this->input->hidden('BStatus.budget_id',array('value'=>$budgetID));
                  ?>
				<header class="panel-heading">
					My Request
					<?php
						
						//phase 2-only finance admin/superadmin can create new budget
						if ($activeUser['role_id']==17||$activeUser['finance'])
						// if(in_array($activeUser['Role']['role_id'], array(17,18)))
							echo $this->Html->link('Create New Budget','#modal-create-budget',array('class'=>'btn btn-round btn-primary btn-xs margin-left','data-toggle'=>'modal'));
					?>
				</header>

				<div class="panel-body">
					<table class="table table-striped dataTable">
						<thead>
							<tr>
								<th class="text-center"> </th>
								<th class="text-center">Year</th>
								<th class="text-center">Company</th>
								<th class="text-center">Progress</th>
								<th class="text-center">Status</th>
								<th class="text-center">Remark</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($budget as $b):
									$budgetid = $b['Budget']['budget_id'];
									$encBudgetID = $this->Mems->encrypt($budgetid);
							?>
							<tr class="text-center">
								<td class="text-center">
									<?php 
										// check the status and progress
										$totalStatus = 0;
										$countApproved =0;
										$currentStatus = 'Active';
										foreach($b['BStatus'] as $bstatus):
											if($bstatus['submission_no'] == $b['Budget']['submission_no']):
												$totalStatus++; //update total for current submission
												if($bstatus['status'] == 'approved'):
													$countApproved++;
												endif;

												if($bstatus['status'] == 'rejected'):
													$currentStatus = 'Rejected';
												endif;
											endif;
										endforeach;
										if($totalStatus == 0)
											$progress = 0;
										else
											$progress = round($countApproved/$totalStatus,2) * 100;
										$submission_no=$b['Budget']['submission_no'] ;
										//allow delete for incomplete or newly submitted budget rejected/not yet approved 
										if($submission_no==0||($submission_no ==1 && $progress!=100)):
									?>
										<input type="checkbox"  name="budgetid[]" value=<?php echo $b['Budget']['budget_id'] ?> />
									<?php endif;?>
								</td>
								<td>
									<?php
										echo $this->Html->link($b['Budget']['year'],array('controller'=>'budget','action'=>'dashboard',$encBudgetID),array('escape'=>false));
									?>
									<br>
									<small>
										<?php
											$created = date('d M Y',strtotime($b['Budget']['created']));
											$modified = date('d M Y',strtotime($b['Budget']['modified']));
											echo "Created on : ".$created;
											
										?>
									</small>
								</td>
								
								<td class="text-center">
									<?php
										echo $b['Company']['company'];
									?>
								</td>
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
									<?php
										// if(!empty($b['BRemark']))
										// 	echo $this->Html->link('<em> Remark </em>',array('controller'=>'remark','action'=>'index',$budgetid,'budget'),array('escape'=>false,'class'=>'btn btn-info btn-xs'));
										// else
										// 	echo "<em> None </em>";
									?>
									<?php
										$assignedFlag=false;
										if (!empty($b['BRemark'])){
											 foreach ($b['BRemark'] as $value) {
											 	if (!empty($value['BRemarkAssign'])){
													$assignedFlag=true;
													break;
											 	}

											 }
										}
										if($assignedFlag)
											echo $this->Html->link('<em> Remark </em>',array('controller'=>'remark','action'=>'index',$encBudgetID,'budget'),array('escape'=>false,'class'=>'btn btn-info btn-xs'));
										else
											echo "<em> None </em>";
									?>
								</td>
								<td class="text-center">
									<!-- <div class='btn-group'> -->
									<?php

										if($b['Budget']['submission_no'] == 0)://havent submitted
											echo $this->Html->link('<i class="fa fa-pencil"></i>',array('controller'=>'budget','action'=>'request',$encBudgetID),array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-original-title'=>'Edit'));
										else:
											echo $this->Html->link('<i class="fa fa-dashboard"></i>',array('controller'=>'budget','action'=>'dashboard',$encBudgetID),array('escape'=>false,'class'=>'btn bg-primary btn-xs tooltips','data-original-title'=>'Dashboard'));
											
										endif;
										
										$submission_no=$b['Budget']['submission_no'] ;
										//allow delete for incomplete or newly submitted budget rejected/not yet approved 
										if($submission_no==0||($submission_no ==1 && $progress!=100))
											echo $this->Html->link('<i class="fa fa-times"></i>',array('controller'=>'budget','action'=>'deleteBudget',$encBudgetID),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title'=>'Delete'),'Are you sure you want to delete this budget?');
									?>
									<!-- </div> -->
								</td>
							</tr>
							<?php
								endforeach;
							?>
						</tbody>
					</table>
				</div>
				<div class="panel-body">
						<h5><strong>Multi-select option:</strong></h5>
						<button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure? This action cannot be undone.');"><i class="fa fa-times"></i> Delete Budget</button>	
                  
				</div>	
					<?php		
				
              		echo $this->Form->end();
               ?>
			</section>
		</div>
	</div>
	<!-- page end-->
</section>

<!-- Modal -->
<div class="modal fade" id="modal-create-budget" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Create New Budget</h4>
			</div>
			<?php
				echo $this->Form->create('Budget',array('url'=>array('controller'=>'budget','action'=>'createBudget'),
										'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
										'class'=>'form-horizontal','type'=>'file'));
			?>
			<div class="modal-body ">
				<div class="form-group">
					<label class="col-lg-3 col-sm-3 control-label"> Company (*)</label>
					<div class="col-sm-9">
                      	<?php
                      		
                      		echo $this->Form->input('Budget.company_id',array('type'=>'select','options'=>$compList,'class'=>'select2 full-width','id'=>'compSelect','required','empty'=>'-- Select company --'));
                      	?>
                      	<small><i class="fa fa-exclamation-circle"></i> Select the company you want to create budget for </small>
                  	</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 col-sm-3 control-label"> Academic Dept.(*)</label>
					<div class="col-sm-9">
                      	<?php
                      		
                      		echo $this->Form->input('Budget.acad_id',array('type'=>'select','options'=>array(),'class'=>'select2-sortable full-width','id'=>'acad','required','multiple'=>'multiple','placeholder'=>'-- Select academic departments --'));
                      	?>
                      	<small><i class="fa fa-exclamation-circle"></i> Select according to the sequence you want  in budget</small>
                  	</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 col-sm-3 control-label"> Non-Academic Dept.(*)</label>
					<div class="col-sm-9">
                      	<?php
                      		
                      		echo $this->Form->input('Budget.nonacad_id',array('type'=>'select','options'=>array(),'class'=>'select2-sortable full-width','id'=>'nonacad','required','multiple'=>'multiple','placeholder'=>'-- Select non-academic departments --'));
                      	?>
                      	<small><i class="fa fa-exclamation-circle"></i> Select according to the sequence you want in budget</small>
                  	</div>
				</div>
				
                <div class="form-group text-center">
	                <label class="col-lg-3 col-sm-3 control-label">Year (*)</label>
                  	<div class="col-sm-9 text-left">	  
                  		<?php
                  			$year = array_combine( range(date('Y'), date('Y') + 2), range(date('Y'), date('Y') + 2) );
                      		echo $this->Form->input('Budget.year',array('type'=>'select','options'=>$year,'class'=>'select2 full-width','id'=>'select-year','required'=>'required','empty'=>'-- Select year --'));
                      	?>                    
	                </div>
                </div>
                <div class="form-group text-center">
	                <label class="col-lg-3 col-sm-3 control-label">Description</label>
                  	<div class="col-sm-9">	  
                  		<?php
                      		echo $this->Form->input('Budget.description',array('type'=>'textarea','class'=>'form-control'));
                      	?>                    
	                </div>
                </div>

                <div class="form-group">
                	<div class="col-sm-9">	  
                  		(*) Denotes a required field                 
	                </div>
                </div>
	        </div>
				
			<div class="modal-footer text-center">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
				<?php
					echo $this->Form->button('Create Budget',array('type'=>'submit','class'=>'btn btn-success'));
				?>
			</div>

			<?php
				echo $this->Form->end();
			?>
		</div>
	</div>
</div>
  <!-- modal -->