<?php
	$this->Html->addCrumb('Non Financial Memo', array('controller' => 'NfMemo2', 'action' => 'index'));
	if(empty($memo['NfMemo']['ref_no']))
		$this->Html->addCrumb(' Temporary Ref.No : 000/'.$memo['NfMemo']['memo_id'], array('controller'=>'NfMemo2','action'=>'dashboard',$memo_id));
	else
		$this->Html->addCrumb( ' Ref.No : '.$memo['NfMemo']['ref_no'], array('controller'=>'NfMemo2','action'=>'dashboard',$memo_id));

	$this->Html->addCrumb( 'Review Non Financial Memo', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
	<div class="row">
		<!-- Navigation -->
		<div class="col-sm-12">
			<section class="panel">
				<header class="panel-heading">
					<h4>Non Financial Memo
		                <span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
		              <div class="col-lg-12"> 
						<div class="btn-group pull-right">
		                	<?php 
		                		if ($editFlag){
		                			echo $this->Html->link("<button class='btn btn-sm btn-default tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Edit Memo'><i class='fa fa-pencil'></i> Edit</button>",array('controller'=>'NfMemo2','action'=>'request',$memo_id,'edit'),array('escape'=>false,'class'=>'small-margin-left')); 

		                			//echo '&nbsp;&nbsp;';
		                		}

		                		if ($approvalFlag){ 
		                	?>
				                	<a href="#approval" class="approval-btn-clicked small-margin-left" data-toggle="modal" data-action="approve">
										<button class='btn btn-success btn-sm tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Approve'>
											<i class='fa fa-check'></i> Approve
										</button>
									</a>

									<a href="#approval" class="approval-btn-clicked small-margin-left" data-toggle="modal" data-action="reject">
										<button class='btn btn-danger btn-sm tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Reject'>
											<i class='fa fa-times'></i> Reject
										</button>
									</a>

		                	<?php
		                		} 
		                		if ($remarkFlag){ 
		                			echo $this->Html->link("<button class='btn btn-sm btn-warning tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Remark'><i class='fa fa-comment'></i> Remarks</button>",array('controller'=>'Remark','action'=>'index',$memo_id,'nonfinancial'),array('escape'=>false,'class'=>'small-margin-left')); 
		                	
		                			//echo '&nbsp;&nbsp;';
		                		}
		                		if ($commentFlag){ 
		                			echo $this->Html->link("<button class='btn bg-primary btn-sm tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Comment'><i class='fa fa-comment'></i> Comments</button>",array('controller'=>'Comment','action'=>'index',$memo_id,'nonfinancial'),array('escape'=>false,'class'=>'small-margin-left')); 
		                	
		                			
		                		}

		                	?>
		                	
		                	<?php echo $this->Html->link("<button class='btn btn-sm btn-primary tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Download PDF'><i class='fa fa-print'></i> PDF</button>",array('controller'=>'NfMemo2','action'=>'pdf',$memo_id.'.pdf'),array('escape'=>false,'class'=>'small-margin-left')); ?>
		                </div>
		                <br/><br/>
	                  </div>
	                </div>
					<div class="row">
					  <br/>
		              <div class="col-lg-12"> 
		              	<table class="table table-bordered table-striped table-condensed">
		              		<tbody>
		              			<tr><th>Reference no.</th><td><b>(<?php if (!empty($memo['NfMemo']['ref_no'])) echo $memo['NfMemo']['ref_no']; ?>)</b></td></tr>
		              			<tr><th>To</th><td><b>
		              				<?php if (!empty($memo['NfMemoTo'])){ 
			              					$temp=array();
											foreach ($memo['NfMemoTo'] as $to) {

												$temp[]=$to['User']['staff_name'];
											}
											$tos=implode(', ',$temp); 
											echo $tos;
										  }
									?>
								</b></td></tr>
		              			<tr><th>From</th><td><b><?php if (!empty($memo['NfMemo']['department_id'])) echo $memo['User']['Department']['department_name']; ?></b></td></tr>
		              			<tr><th>Subject</th><td><b><?php if (!empty($memo['NfMemo']['subject']))echo $memo['NfMemo']['subject']; ?></b></td></tr>
		              		</tbody>
		              	</table>
		              </div>
		            </div>
		           <div class="row">
		           	   <br/>
			           <div class="col-md-1">
			           	  <b>Prerequisites :</b>
		                </div>
		                <div class="col-md-3">
			           	  <div class="checkboxes" >
		                      <label class="label_check" for="checkbox-01">
		                          <input type="checkbox" id="checkbox-01" > Financial
		                      </label>
		                      <label class="label_check" for="checkbox-02">
		                          <input type="checkbox" id="checkbox-02" checked> Non-Financial
		                      </label>
		                  </div>
		                </div>
		              
		                </div>
		                <div class="col-md-2 pull-right">
		                
			           	  <b>Date required :</b> <?php if (!empty($memo['NfMemo']['date_required'])) echo date('d M Y',strtotime($memo['NfMemo']['date_required'])); ?> 
		                </div>
		           </div>
                </div>
			</section>
			

			<section class="panel">
				<header class="panel-heading">
					<h4>1. Introduction
		                <span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
		              <div class="col-lg-12"> 
						<?php if (!empty($memo['NfMemo']['introduction']))echo nl2br($memo['NfMemo']['introduction']); ?>
	                  </div>
	                </div>
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading">
					<h4>2. Subject Matters
		                <span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
		              <div class="col-lg-12"> 
						<?php if (!empty($memo['NfMemo']['subject_matters']))echo nl2br($memo['NfMemo']['subject_matters']); ?>
	                  </div>
	                </div>
	                

                </div>
			</section>

			
			<section class="panel">
				<header class="panel-heading">
					<h4>3. Recommendation/Conclusion
		                <span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
		              <div class="col-lg-12 text"> 
						<?php if (!empty($memo['NfMemo']['recommendation'])) echo nl2br($memo['NfMemo']['recommendation']); ?>
	                  </div>
	                </div>
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading">
					<h4>
						4. Division/Department's Review/Recommendation (Guided by Division/Department LOA)
						<span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
		              <div class="col-lg-3"> 
		                 <h5><b>Prepared by:</b></h5>
		                 <p>
		                 	<?php if (!empty($memo['NfMemo']['user_id'])) echo $memo['User']['staff_name']; ?><br/>
		                 	<?php if (!empty($memo['NfMemo']['department_id'])) echo $memo['User']['Department']['department_name']; ?><br/>
		                 	<?php if (!empty($memo['User']['designation'])) echo $memo['User']['designation']; ?><br/>
		                 	<?php if (!empty($memo['NfMemo']['created'])) echo date('d M Y',strtotime($memo['NfMemo']['created'])); ?><br/>
		                 </p>
		              </div>

		              <div class="col-lg-9">
		              	<table class="table table-bordered table-condensed">
		              		<thead >
		              			<tr class="info">
		              				<th>Remark(s)</th>
		              			</tr>
		              		</thead>
		              		<tbody>
		              			<tr style="text-align:justify">
		              				<td>
		                 				<?php if (!empty($memo['NfMemo']['remark'])) echo nl2br($memo['NfMemo']['remark']); ?><br/>
		              				</td>
		              			</tr>
		              		</tbody>
		              	</table>
		              </div>
		            </div>


		            <?php if (!empty($reviewer)):

		            		$counter=0;

                            foreach ($reviewer as $value) {?>

								<div class="row">
								  <div class="col-lg-3"> 

					                 <h5><b><?php echo $counter+1; ?>) Reviewed by:</b></h5>
					                 <p>
					                 	<?php if (!empty($value['User']['staff_name'])) echo $value['User']['staff_name']; ?><br/>
					                 	<?php if (!empty($value['User']['Department']['department_name'])) echo $value['User']['Department']['department_name']; ?><br/>
					                 	<?php if (!empty($value['User']['designation'])) echo $value['User']['designation']; ?><br/>
					                 	<?php if (!empty($value['NfStatus'][0]['modified'])) echo date('d M Y',strtotime($value['NfStatus'][0]['modified'])); ?><br/>
					                 </p>
					              </div>
						          <div class="col-lg-9">
									<table class="table table-bordered table-condensed">
				                      <thead>
				                      	<tr class="info">
					                      <th style="width:5%;text-align:center">
					                      	<?php if (!empty($value['NfStatus'][0]['status'])) {if ($value['NfStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Approved</th>
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['NfStatus'][0]['status'])) {if ($value['NfStatus'][0]['status']=='rejected') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Rejected</th>
					                      <th style="width:40%">Other remark(s)</th>
					                    </tr>
				                      </thead>
				                      <tbody>
					                      <tr style="text-align:justify">
					                          <td colspan="2">
					                          	<!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. nl2br($value['NfStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td colspan="2">
					                          	 <!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.nl2br($value['NfStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td>
					                          	<?php if (!empty($remark_reviewer[$counter])):

						                            foreach ($remark_reviewer[$counter] as $val) {?>

							                          	<h5><b>Remarks : <?php if (!empty($val['NfRemark']['subject'])) echo $val['NfRemark']['subject']; ?></b></h5>
							                          	<p>
							                          		<b>To</b> : <?php if (!empty($val['NfRemarkAssign'])):
			              														$temp=array();
										                            			foreach ($val['NfRemarkAssign'] as $v) {
										                            				
											
																					if ($val['User']['staff_name']!=$v['User']['staff_name'])
																						$temp[]=$v['User']['staff_name'];
										                            			}
										                            			$tos=implode(', ',$temp); 
																				echo $tos;
										                            		endif;

										                            	?>
							                          	</p>
							                          	<p style="text-align:justify">
							                          		<?php if (!empty($val['NfRemarkFeedback'][0]['feedback'])) echo $val['NfRemarkFeedback'][0]['feedback']; ?>
							                          	</p> 
							                          	 

						                          		<table class="table">
						                          			<?php if (count($val['NfRemarkFeedback'])>1):
								                          			for ($i=1;$i<count($val['NfRemarkFeedback']);$i++){
								                          	?>
						                          			<tr style="text-align:justify">
						                          				<td>
																	<h5><b>Feedback from <?php echo $val['NfRemarkFeedback'][$i]['User']['staff_name']; ?> (<?php echo date('d M Y',strtotime($val['NfRemarkFeedback'][$i]['created'])); ?>) :</b></h5>
																	<p><?php if (!empty($val['NfRemarkFeedback'][$i]['feedback'])) echo $val['NfRemarkFeedback'][$i]['feedback']; ?></p>
						                          				</td>
						                          			</tr>
						                          			<?php } endif; ?>
						                          			
						                          		</table>

						                         <?php } endif; ?>
					                          	
					                          </td>
					                      </tr>
				                      </tbody>
				                    </table>
				                  </div>
			                    </div>
	                <?php 
	            			$counter++;
	            		}
	                	endif; 
	                ?>

	                <?php if (!empty($recommender)):

		            		$counter=0;

                            foreach ($recommender as $value) {?>

								<div class="row">
								  <div class="col-lg-3"> 

					                 <h5><b><?php echo $counter+1; ?>) Recommended by:</b></h5>
					                 <p>
					                 	<?php if (!empty($value['User']['staff_name'])) echo $value['User']['staff_name']; ?><br/>
					                 	<?php if (!empty($value['User']['Department']['department_name'])) echo $value['User']['Department']['department_name']; ?><br/>
					                 	<?php if (!empty($value['User']['designation'])) echo $value['User']['designation']; ?><br/>
					                 	<?php if (!empty($value['NfStatus'][0]['modified'])) echo date('d M Y',strtotime($value['NfStatus'][0]['modified'])); ?><br/>
					                 </p>
					              </div>
						          <div class="col-lg-9">
									<table class="table table-bordered table-condensed">
				                      <thead>
				                      	<tr class="info">
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['NfStatus'][0]['status'])) {if ($value['NfStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Approved</th>
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['NfStatus'][0]['status'])) {if ($value['NfStatus'][0]['status']=='rejected') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Rejected</th>
					                      <th style="width:40%">Other remark(s)</th>
					                    </tr>
				                      </thead>
				                      <tbody>
					                      <tr style="text-align:justify">
					                          <td colspan="2">
					                          	<!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. nl2br($value['NfStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td colspan="2">
					                          	 <!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.nl2br($value['NfStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td>
					                          	<?php if (!empty($remark_recommender[$counter])):

						                            foreach ($remark_recommender[$counter] as $val) {?>

							                          	<h5><b>Remarks : <?php if (!empty($val['NfRemark']['subject'])) echo $val['NfRemark']['subject']; ?></b></h5>
							                          	<p>
							                          		<b>To</b> : <?php if (!empty($val['NfRemarkAssign'])):
			              														$temp=array();
										                            			foreach ($val['NfRemarkAssign'] as $v) {
										                            				
											
																					if ($val['User']['staff_name']!=$v['User']['staff_name'])
																						$temp[]=$v['User']['staff_name'];
										                            			}
										                            			$tos=implode(', ',$temp); 
																				echo $tos;
										                            		endif;

										                            	?>
							                          	</p>
							                          	<p style="text-align:justify">
							                          		<?php if (!empty($val['NfRemarkFeedback'][0]['feedback'])) echo $val['NfRemarkFeedback'][0]['feedback']; ?>
							                          	</p> 
							                          	 

						                          		<table class="table">
						                          			<?php if (count($val['NfRemarkFeedback'])>1):
								                          			for ($i=1;$i<count($val['NfRemarkFeedback']);$i++){
								                          	?>
						                          			<tr style="text-align:justify">
						                          				<td>
																	<h5><b>Feedback from <?php echo $val['NfRemarkFeedback'][$i]['User']['staff_name']; ?> (<?php echo date('d M Y',strtotime($val['NfRemarkFeedback'][$i]['created'])); ?>) :</b></h5>
																	<p><?php if (!empty($val['NfRemarkFeedback'][$i]['feedback'])) echo $val['NfRemarkFeedback'][$i]['feedback']; ?></p>
						                          				</td>
						                          			</tr>
						                          			<?php } endif; ?>
						                          			
						                          		</table>

						                         <?php } endif; ?>
					                          	
					                          </td>
					                      </tr>
				                      </tbody>
				                    </table>
				                  </div>
			                    </div>
	                <?php 
	            			$counter++;
	            		}
	                	endif; 
	                ?>

                </div>

			</section>
			<section class="panel">
				<header class="panel-heading">
					<h4>
						5. COO/CFO Approval (Guided by Corporate LOA)
						<span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					
		            <?php if (!empty($finance)):

		            		$counter=0;

                            foreach ($finance as $value) {?>

								<div class="row">
								  <div class="col-lg-3"> 

					                 <h5><b><?php echo $counter+1; ?>) Reviewed by:</b></h5>
					                 <p>
					                 	<?php if (!empty($value['User']['staff_name'])) echo $value['User']['staff_name']; ?><br/>
					                 	<?php if (!empty($value['User']['Department']['department_name'])) echo $value['User']['Department']['department_name']; ?><br/>
					                 	<?php if (!empty($value['User']['designation'])) echo $value['User']['designation']; ?><br/>
					                 	<?php if (!empty($value['NfStatus'][0]['modified'])) echo date('d M Y',strtotime($value['NfStatus'][0]['modified'])); ?><br/>
					                 </p>
					              </div>
						          <div class="col-lg-9">
									<table class="table table-bordered table-condensed">
				                      <thead>
				                      	<tr class="info">
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['NfStatus'][0]['status'])) {if ($value['NfStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Approved</th>
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['NfStatus'][0]['status'])) {if ($value['NfStatus'][0]['status']=='rejected') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Rejected</th>
					                      <th style="width:40%">Other remark(s)</th>
					                    </tr>
				                      </thead>
				                      <tbody>
					                      <tr style="text-align:justify">
					                          <td colspan="2">
					                          	<!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. nl2br($value['NfStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td colspan="2">
					                          	 <!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.nl2br($value['NfStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td>
					                          	<?php if (!empty($remark_finance[$counter])):

						                            foreach ($remark_finance[$counter] as $val) {?>

							                          	<h5><b>Remarks : <?php if (!empty($val['NfRemark']['subject'])) echo $val['NfRemark']['subject']; ?></b></h5>
							                          	<p>
							                          		<b>To</b> : <?php if (!empty($val['NfRemarkAssign'])):
			              														$temp=array();
										                            			foreach ($val['NfRemarkAssign'] as $v) {
										                            				
											
																					if ($val['User']['staff_name']!=$v['User']['staff_name'])
																						$temp[]=$v['User']['staff_name'];
										                            			}
										                            			$tos=implode(', ',$temp); 
																				echo $tos;
										                            		endif;

										                            	?>
							                          	</p>
							                          	<p style="text-align:justify">
							                          		<?php if (!empty($val['NfRemarkFeedback'][0]['feedback'])) echo $val['NfRemarkFeedback'][0]['feedback']; ?>
							                          	</p> 
							                          	 

						                          		<table class="table">
						                          			<?php if (count($val['NfRemarkFeedback'])>1):
								                          			for ($i=1;$i<count($val['NfRemarkFeedback']);$i++){
								                          	?>
						                          			<tr style="text-align:justify">
						                          				<td>
																	<h5><b>Feedback from <?php echo $val['NfRemarkFeedback'][$i]['User']['staff_name']; ?> (<?php echo date('d M Y',strtotime($val['NfRemarkFeedback'][$i]['created'])); ?>) :</b></h5>
																	<p><?php if (!empty($val['NfRemarkFeedback'][$i]['feedback'])) echo $val['NfRemarkFeedback'][$i]['feedback']; ?></p>
						                          				</td>
						                          			</tr>
						                          			<?php } endif; ?>
						                          			
						                          		</table>

						                         <?php } endif; ?>
					                          	
					                          </td>
					                      </tr>
				                      </tbody>
				                    </table>
				                  </div>
			                    </div>
	                <?php 
	            			$counter++;
	            		}
	                	endif; 
	                ?>

                </div>
			</section>
			<section class="panel">
				<header class="panel-heading">
					<h4>
						6. CEO Approval (Guided by Corporate LOA)
						<span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					
		            <?php if (!empty($approver)):

		            		$counter=0;

                            foreach ($approver as $value) {?>

								<div class="row">
								  <div class="col-lg-3"> 

					                 <h5><b><?php echo $counter+1; ?>) Reviewed by:</b></h5>
					                 <p>
					                 	<?php if (!empty($value['User']['staff_name'])) echo $value['User']['staff_name']; ?><br/>
					                 	<?php if (!empty($value['User']['Department']['department_name'])) echo $value['User']['Department']['department_name']; ?><br/>
					                 	<?php if (!empty($value['User']['designation'])) echo $value['User']['designation']; ?><br/>
					                 	<?php if (!empty($value['NfStatus'][0]['modified'])) echo date('d M Y',strtotime($value['NfStatus'][0]['modified'])); ?><br/>
					                 </p>
					              </div>
						          <div class="col-lg-9">
									<table class="table table-bordered table-condensed">
				                      <thead>
				                      	<tr class="info">
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['NfStatus'][0]['status'])) {if ($value['NfStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Approved</th>
					                      <th style="width:5%;text-align:center">
					                      	<?php if (!empty($value['NfStatus'][0]['status'])) { if ($value['NfStatus'][0]['status']=='rejected') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Rejected</th>
					                      <th style="width:40%">Other remark(s)</th>
					                    </tr>
				                      </thead>
				                      <tbody>
					                      <tr style="text-align:justify">
					                          <td colspan="2">
					                          	<!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. nl2br($value['NfStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td colspan="2">
					                          	 <!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.nl2br($value['NfStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td>
					                          	<?php if (!empty($remark_approver[$counter])):

						                            foreach ($remark_approver[$counter] as $val) {?>

							                          	<h5><b>Remarks : <?php if (!empty($val['NfRemark']['subject'])) echo $val['NfRemark']['subject']; ?></b></h5>
							                          	<p>
							                          		<b>To</b> : <?php if (!empty($val['NfRemarkAssign'])):
			              														$temp=array();
										                            			foreach ($val['NfRemarkAssign'] as $v) {
										                            				
											
																					if ($val['User']['staff_name']!=$v['User']['staff_name'])
																						$temp[]=$v['User']['staff_name'];
										                            			}
										                            			$tos=implode(', ',$temp); 
																				echo $tos;
										                            		endif;

										                            	?>
							                          	</p>
							                          	<p style="text-align:justify">
							                          		<?php if (!empty($val['NfRemarkFeedback'][0]['feedback'])) echo $val['NfRemarkFeedback'][0]['feedback']; ?>
							                          	</p> 
							                          	 

						                          		<table class="table">
						                          			<?php if (count($val['NfRemarkFeedback'])>1):
								                          			for ($i=1;$i<count($val['NfRemarkFeedback']);$i++){
								                          	?>
						                          			<tr style="text-align:justify">
						                          				<td>
																	<h5><b>Feedback from <?php echo $val['NfRemarkFeedback'][$i]['User']['staff_name']; ?> (<?php echo date('d M Y',strtotime($val['NfRemarkFeedback'][$i]['created'])); ?>) :</b></h5>
																	<p><?php if (!empty($val['NfRemarkFeedback'][$i]['feedback'])) echo $val['NfRemarkFeedback'][$i]['feedback']; ?></p>
						                          				</td>
						                          			</tr>
						                          			<?php } endif; ?>
						                          			
						                          		</table>

						                         <?php } endif; ?>
					                          	
					                          </td>
					                      </tr>
				                      </tbody>
				                    </table>
				                  </div>
			                    </div>
	                <?php 
	            			$counter++;
	            		}
	                	endif; 
	                ?>

                </div>
			</section>
		</div>
	</div>
	 <div aria-hidden="true" aria-labelledby="approval" role="dialog" tabindex="-1" id="approval" class="modal fade">
      	<div class="modal-dialog">
          	<div class="modal-content">
              	<div class="modal-header">
                  	<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                  	<h4 class="modal-title">Approve / Reject Non Financial Memo Request</h4>
              	</div>
              	<div class="modal-body">
              		<?php
              			echo $this->Form->create('NfStatus',array('url'=>array('controller'=>'NfMemo2','action'=>'approveRejectMemo'),'class'=>'form-horizontal','onSubmit'=>'return confirm("Are you sure you want to approve/reject this memo request?")','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
              			echo $this->Form->hidden('NfStatus.memo_id',array('value'=>$memo_id));
              		?>
                  	<div class="form-group">
                      	<label class="col-lg-2 col-sm-2 control-label">Decision</label>
                      	<div class="col-lg-10">
                          	<div class="radios">

                              	<?php
                              		$options = array("approved" => '  Approve', "rejected" => '  Reject');
									echo $this->Form->input('NfStatus.status',array('type'=>'radio','options'=>$options,'separator'=>'<br>'));
                              	?>
                          	</div>
                     	</div>
                 	</div>
                 	
                      
                  	<div class="form-group">
                  		<label class="col-lg-2 col-sm-2 control-label">Remark</label>
                    	<div class="col-lg-10">
                          	<?php
                          		echo $this->Form->input('NfStatus.remark',array('type'=>'textarea','class'=>'wysihtml5 form-control','rows'=>'10'));
                          	?>
                     	</div>
                  	</div>

                  	<div class="modal-footer text-center">
						<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						<?php
							echo $this->Form->button('Submit',array('type'=>'submit','class'=>'btn btn-success'));
						?>
					</div>
                   
             		<?php
             			echo $this->Form->end();
             		?>
              	</div>
          	</div>
      	</div>
    </div>
</section>

<script type="text/javascript">
$(document).ready(function () {

	// js to auto select radio
	$('.approval-btn-clicked').click(function(){
    	$('#approval-approve').prop("checked",false);
    	$('#approval-reject').prop("checked",false);
    	var status = $(this).data('action');
    	if(status == 'approve'){
    		$('#NfStatusStatusApproved').prop('checked',true);
    		
    	}
    	else{
    		$('#NfStatusStatusRejected').prop('checked',true);
    	}
    	// alert(status);
    	// if(status)
    });

   $('#NfStatusStatusApproved').click(function(){
   		return false;
   });

   $('#NfStatusStatusRejected').click(function(){
   		return false;
   });
});
</script>