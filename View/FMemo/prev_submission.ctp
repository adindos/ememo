<section class="mems-empty">
	<div class="row">
		<div class="col-sm-12">
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
					<div class="btn-group pull-right">
	                	<?php echo $this->Html->link("<button class='btn btn-primary btn-sm tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Download PDF'><i class='fa fa-print'></i> PDF</button>",array('controller'=>'FMemo','action'=>'pdf',$memo_id.'.pdf',),array('escape'=>false,'class'=>'small-margin-left')); ?>
	                </div>
	                <br>
	                <br>
	                <br>
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
					                 	<?php if (!empty($value['FStatus'][0]['modified'])) echo date('d M Y',strtotime($value['FStatus'][0]['modified'])); ?><br/>
					                 </p>
					              </div>
						          <div class="col-lg-9">
									<table class="table table-bordered table-condensed">
				                      <thead>
				                      	<tr class="info">
					                      <th style="width:5%;text-align:center">
					                      	<?php if (!empty($value['FStatus'][0]['status'])) {if ($value['FStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Approved</th>
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['FStatus'][0]['status'])) {if ($value['FStatus'][0]['status']=='rejected') echo '<i class="fa fa-check"></i>' ;}?>
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
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. ($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td colspan="2">
					                          	 <!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td>
					                          	<?php 
					                          		if (!empty($remark_reviewer[$counter])):

						                            	foreach ($remark_reviewer[$counter] as $val):
					                            	   		
															if (!empty($val['FRemarkFeedback'][0]['feedback'])) 
															 	echo  $val['FRemarkFeedback'][0]['feedback'] ; 


						                          			if (count($val['FRemarkFeedback'])>1):                    				
							                          			for ($i=1;$i<count($val['FRemarkFeedback']);$i++):
							                          				echo "<div style='margin-left:10px;margin-bottom:3px; background: rgba(102,178,255,0.2);padding: 3px 10px;color:#333'>";
							                          				echo "<b>".$val['FRemarkFeedback'][$i]['User']['staff_name']."</b>";
							                          				echo "<small><em>";
							                          				echo " on ";
							                          				echo date('d F Y',strtotime($val['FRemarkFeedback'][$i]['created']));
							                          				echo "</em></small>"; 

																	if (!empty($val['FRemarkFeedback'][$i]['feedback'])) 
																		echo "<br><small>".$val['FRemarkFeedback'][$i]['feedback']."</small>"; 
																	echo "</div>";
																endfor;
															endif; 
														endforeach;
													endif; 
												?>					                          	
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
					                 	<?php if (!empty($value['FStatus'][0]['modified'])) echo date('d M Y',strtotime($value['FStatus'][0]['modified'])); ?><br/>
					                 </p>
					              </div>
						          <div class="col-lg-9">
									<table class="table table-bordered table-condensed">
				                      <thead>
				                      	<tr class="info">
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['FStatus'][0]['status'])) {if ($value['FStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Approved</th>
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['FStatus'][0]['status'])) {if ($value['FStatus'][0]['status']=='rejected') echo '<i class="fa fa-check"></i>' ;}?>
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
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. ($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td colspan="2">
					                          	 <!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td>
					                          	<?php 
					                          		if (!empty($remark_recommender[$counter])):

						                            	foreach ($remark_recommender[$counter] as $val):
					                            	   		

															if (!empty($val['FRemarkFeedback'][0]['feedback'])) 
															 	echo  $val['FRemarkFeedback'][0]['feedback'] ; 


						                          			if (count($val['FRemarkFeedback'])>1):                    				
							                          			for ($i=1;$i<count($val['FRemarkFeedback']);$i++):
							                          				echo "<div style='margin-left:10px;margin-bottom:3px; background: rgba(102,178,255,0.2);padding: 3px 10px;color:#333'>";
							                          				echo "<b>".$val['FRemarkFeedback'][$i]['User']['staff_name']."</b>";
							                          				echo "<small><em>";
							                          				echo " on ";
							                          				echo date('d F Y',strtotime($val['FRemarkFeedback'][$i]['created']));
							                          				echo "</em></small>"; 

																	if (!empty($val['FRemarkFeedback'][$i]['feedback'])) 
																		echo "<br><small>".$val['FRemarkFeedback'][$i]['feedback']."</small>"; 
																	echo "</div>";
																endfor;
															endif; 
														endforeach;
													endif; 
												?>
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
					                 	<?php if (!empty($value['FStatus'][0]['modified'])) echo date('d M Y',strtotime($value['FStatus'][0]['modified'])); ?><br/>
					                 </p>
					              </div>
						          <div class="col-lg-9">
									<table class="table table-bordered table-condensed">
				                      <thead>
				                      	<tr class="info">
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['FStatus'][0]['status'])) {if ($value['FStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Approved</th>
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['FStatus'][0]['status'])) {if ($value['FStatus'][0]['status']=='rejected') echo '<i class="fa fa-check"></i>' ;}?>
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
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. ($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td colspan="2">
					                          	 <!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td>
					                          	<?php 
					                          		if (!empty($remark_finance[$counter])):

						                            	foreach ($remark_finance[$counter] as $val):
					                            	   		

															if (!empty($val['FRemarkFeedback'][0]['feedback'])) 
															 	echo  $val['FRemarkFeedback'][0]['feedback'] ; 


						                          			if (count($val['FRemarkFeedback'])>1):                    				
							                          			for ($i=1;$i<count($val['FRemarkFeedback']);$i++):
							                          				echo "<div style='margin-left:10px;margin-bottom:3px; background: rgba(102,178,255,0.2);padding: 3px 10px;color:#333'>";
							                          				echo "<b>".$val['FRemarkFeedback'][$i]['User']['staff_name']."</b>";
							                          				echo "<small><em>";
							                          				echo " on ";
							                          				echo date('d F Y',strtotime($val['FRemarkFeedback'][$i]['created']));
							                          				echo "</em></small>"; 

																	if (!empty($val['FRemarkFeedback'][$i]['feedback'])) 
																		echo "<br><small>".$val['FRemarkFeedback'][$i]['feedback']."</small>"; 
																	echo "</div>";
																endfor;
															endif; 
														endforeach;
													endif; 
												?>
					                          	
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
						6. CEO Approval (Guided by Corporate LOA) Previous Remark
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
					                 	<?php if (!empty($value['FStatus'][0]['modified'])) echo date('d M Y',strtotime($value['FStatus'][0]['modified'])); ?><br/>
					                 </p>
					              </div>
						          <div class="col-lg-9">
									<table class="table table-bordered table-condensed">
				                      <thead>
				                      	<tr class="info">
					                      <th style="width:5%;text-align:center">
					                      	<?php  if (!empty($value['FStatus'][0]['status'])) {if ($value['FStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Approved</th>
					                      <th style="width:5%;text-align:center">
					                      	<?php if (!empty($value['FStatus'][0]['status'])) { if ($value['FStatus'][0]['status']=='rejected') echo '<i class="fa fa-check"></i>' ;}?>
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
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. ($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td colspan="2">
					                          	 <!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td>
					                          	<?php 
					                          		if (!empty($remark_approver[$counter])):

						                            	foreach ($remark_approver[$counter] as $val):
					                            	   		

															if (!empty($val['FRemarkFeedback'][0]['feedback'])) 
															 	echo  $val['FRemarkFeedback'][0]['feedback'] ; 


						                          			if (count($val['FRemarkFeedback'])>1):                    				
							                          			for ($i=1;$i<count($val['FRemarkFeedback']);$i++):
							                          				echo "<div style='margin-left:10px;margin-bottom:3px; background: rgba(102,178,255,0.2);padding: 3px 10px;color:#333'>";
							                          				echo "<b>".$val['FRemarkFeedback'][$i]['User']['staff_name']."</b>";
							                          				echo "<small><em>";
							                          				echo " on ";
							                          				echo date('d F Y',strtotime($val['FRemarkFeedback'][$i]['created']));
							                          				echo "</em></small>"; 

																	if (!empty($val['FRemarkFeedback'][$i]['feedback'])) 
																		echo "<br><small>".$val['FRemarkFeedback'][$i]['feedback']."</small>"; 
																	echo "</div>";
																endfor;
															endif; 
														endforeach;
													endif; 
												?>
					                          	
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
</section>