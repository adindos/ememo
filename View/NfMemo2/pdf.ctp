<section class="mems-content pdf-text-overwrite">
	<hr class="pdf-desc">
		<table class='pdf table bigger-text'>
            <tr>
                <td class='bold noborder' style="width:14%">
                    Reference No
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                    <?php
                        if (!empty($memo['NfMemo']['ref_no'])) 
                        	echo $memo['NfMemo']['ref_no'];
                    ?>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    To
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                    <?php
                        if ( !empty($memo['NfMemoTo']) ){ 
          					$temp=array();
							foreach ($memo['NfMemoTo'] as $to) {
								$temp[]=$to['Staff']['staff_name'];
							}
							$tos=implode(', ',$temp); 
							echo $tos;
						  }
                    ?>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    From
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                    <?php
                        if (!empty($memo['NfMemo']['department_id'])) 
                        	echo $memo['User']['Department']['department_name'];
                    ?>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    Subject
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                    <?php
                        if (!empty($memo['NfMemo']['subject']))
                        	echo "<strong>". $memo['NfMemo']['subject'] ."</strong>";
                    ?>
                </td>
            </tr>
        </table>
	<hr class="pdf-desc">

	<div class="col-lg-12">
		<section class="panel">
			<div class="panel-body pdf-panel-body">
	       	  	<em> Prerequisites :</em>
	       	  	<table class="pdf table">
	       	  		<tr>
	       	  			<td style="width: 70%" class="noborder">
	       	  				<table class="pdf table">
	       	  					<tr>
	       	  						<td class="noborder"> 
	       	  							<div class="check-box"></div> <em> Financial </em>
	       	  						</td>
<!-- 	       	  						<td class="noborder"> 
	       	  							<div class="check-box"></div> <em> Budgeted </em>
	       	  						</td>
	       	  						<td class="noborder"> 
	       	  							<div class="check-box"></div> <em> Approved Vendor </em>
	       	  						</td> -->
	       	  					</tr>
	       	  					<tr>
	       	  						<td class="noborder"> 
	       	  							<div class="check-box"><span class="fa fa-check"></span></div> <em> Non-Financial </em>
	       	  						</td>
<!-- 	       	  						<td class="noborder"> 
	       	  							<div class="check-box"></div> <em> Unbudgeted </em>
	       	  						</td>
	       	  						<td class="noborder"> 
	       	  							<div class="check-box"></div> <em> New Vendor </em>
	       	  						</td> -->
	       	  					</tr>
	       	  				</table>
	       	  			</td>
	       	  			<td style="width: 30%" class="text-right noborder">
	       	  				<strong> Date required :  </strong>
	       	  				<?php 
	       	  					if (!empty($memo['NfMemo']['date_required'])) 
	       	  						echo date('d F Y',strtotime($memo['NfMemo']['date_required'])); 
	       	  				?> 
	       	  			</td>
	       	  		</tr>
	       	  	</table>
	              
	       </div>
		</section>
			

			<section class="panel">
				<header class="panel-heading noborder">
					<strong> 1. Introduction </strong>
				</header>
				
				<div class="panel-body pdf-panel-body">
					<table class="pdf table hasBorder">
						<tr>
							<td class="noborder">
								<?php 
									if (!empty($memo['NfMemo']['introduction']))
										echo nl2br($memo['NfMemo']['introduction']); 
								?>
							</td>
						</tr>
					</table>
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading noborder">
					<strong> 2. Subject Matters </strong>
				</header>
				
				<div class="panel-body pdf-panel-body">
		            <table class="pdf table hasBorder">
						<tr>
							<td class="noborder">
								<?php 
									if (!empty($memo['NfMemo']['subject_matters']))
										echo nl2br($memo['NfMemo']['subject_matters']); 
								?>
							</td>
						</tr>
					</table>            
                </div>
			</section>

			
			<section class="panel">
				<header class="panel-heading noborder">
					<strong> 3. Recommendation/Conclusion </strong>
				</header>
				
				<div class="panel-body pdf-panel-body">
		            <table class="pdf table hasBorder">
						<tr>
							<td class="noborder">
								<?php 
									if (!empty($memo['NfMemo']['recommendation'])) 
										echo nl2br($memo['NfMemo']['recommendation']); 
								?>
	                  		</td>
						</tr>
					</table>            
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading noborder">
					<strong> 4. Division/Department's Review/Recommendation </strong>
					<small> (Guided by Division/Department LOA) </small> 
				</header>
				
				<div class="panel-body pdf-panel-body">
					<table class="pdf table">
                    	<tr>
                    		<td class="col-lg-3 noborder" style="width:25%">
                    			<h5><b>Prepared by:</b></h5>
								<p>
									<?php 
										if (!empty($memo['NfMemo']['user_id'])) 
											echo $memo['User']['staff_name']; 
										echo "<br>";
										if (!empty($memo['NfMemo']['department_id'])) 
											echo $memo['User']['Department']['department_name'];
										echo "<br>";
										if (!empty($memo['User']['designation'])) 
											echo $memo['User']['designation']; 
										echo "<br>";
										if (!empty($memo['NfMemo']['created'])) 
											echo date('d M Y',strtotime($memo['NfMemo']['created']));
									?>
								</p>
                    		</td>
                    		<td class="col-lg-9 noborder" style="width:75%">
                    			<table class="table table-bordered table-condensed">
									<thead >
										<tr class="">
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
                    		</td>
                    	</tr>
                    </table>


		            <?php 
		            	if (!empty($reviewer)):
		            		$counter=0;
                            foreach ($reviewer as $value){
                    ?>
                		<table class="pdf table">
                			<tr>
                                <td class="col-lg-3 noborder" style="width:25%">
                                    <h5 class="bold no-margin-bottom">
                                        <?php
                                            echo $this->Mems->ordinal(($counter+1));
                                        ?>
                                        Reviewed By : 
                                    </h5>
                                    <p>
                                    	<?php 
                                    		if (!empty($value['User']['staff_name'])) 
                                    			echo $value['User']['staff_name']; 
                                    		echo "<br>";
                                    		if (!empty($value['User']['Department']['department_name'])) 
                                    			echo $value['User']['Department']['department_name'];
                                    		echo "<br>";
                                    		if (!empty($value['User']['designation'])) 
                                    			echo $value['User']['designation'];
                                    		echo "<br>";
                                    		if (!empty($value['NfStatus'][0]['modified'])) 
                                    			echo date('d M Y',strtotime($value['NfStatus'][0]['modified']));
                                    	?>
                                    </p>
					            </td>
					            <td class="col-lg-9 noborder" style="width:75%">
					            	<table class="table table-bordered table-condensed">
					            		<thead>
					            			<tr class="">
					            				<th style="width:5%;text-align:center" class="light-grey-bg">
					            					<?php if (!empty($value['NfStatus'][0]['status'])) {if ($value['NfStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					            				</th>
					            				<th style="width:25%">Approved</th>
					            				<th style="width:5%;text-align:center" class="light-grey-bg">
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

					            								foreach ($val['NfRemarkAssign'] as $v) {
					            									if ($val['User']['staff_name']!=$v['User']['staff_name'])
					            										echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
					            								}

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
					            			</td>
					            		</tr>
					            	</table>
	                <?php 
	            			$counter++;
	            		}
	                	endif; 
	                ?>

	                <?php 
	                	if (!empty($recommender)):
		            		$counter=0;

                            foreach ($recommender as $value) {
                    ?>
                    		<table class="pdf table">
                            <tr>
                                <td class="col-lg-3 noborder" style="width:25%">
                                    <h5 class="bold no-margin-bottom">
                                        <?php
                                            echo $this->Mems->ordinal(($counter+1));
                                        ?>
                                        Recommended By : 
                                    </h5>
                                    <p>
                                    	<?php 
                                    		if (!empty($value['User']['staff_name']))
                                    		 	echo $value['User']['staff_name']; 
                                    		echo "<br>";
                                    		if (!empty($value['User']['Department']['department_name'])) 
                                    			echo $value['User']['Department']['department_name'];
                                    		echo "<br>";
                                    		if (!empty($value['User']['designation'])) 
                                    			echo $value['User']['designation']; 
                                    		echo "<br>";
                                    		if (!empty($value['NfStatus'][0]['modified'])) 
                                    			echo date('d M Y',strtotime($value['NfStatus'][0]['modified']));
                                    		echo "<br>";
                                    	?>
                                    </p>
					            </td>
                                <td class="col-lg-9 noborder" style="width:75%">
                                	<table class="table table-bordered table-condensed">
                                		<thead>
                                			<tr class="">
                                				<th style="width:5%;text-align:center" class="light-grey-bg">
                                					<?php  if (!empty($value['NfStatus'][0]['status'])) {if ($value['NfStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
                                				</th>
                                				<th style="width:25%">Approved</th>
                                				<th style="width:5%;text-align:center" class="light-grey-bg">
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

                                								foreach ($val['NfRemarkAssign'] as $v) {
                                									if ($val['User']['staff_name']!=$v['User']['staff_name'])
                                										echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
                                								}

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
				                  	</td>
				                </tr>
				            </table>
	                <?php 
	            			$counter++;
	            		}
	                	endif; 
	                ?>

                </div>

			</section>
			<section class="panel">
				<header class="panel-heading noborder">
					<strong> 5. COO/CFO Approval </strong>
					<small> (Guided by Corporate LOA) </small>
				</header>
				
				<div class="panel-body pdf-panel-body">
					
		            <?php 
		            	if (!empty($finance)):
		            		$counter=0;

                            foreach ($finance as $value) {
                    ?>
                    		<table class="pdf table">
                            <tr>
                                <td class="col-lg-3 noborder" style="width:25%">
                                    <h5 class="bold no-margin-bottom">
                                        <?php
                                            echo $this->Mems->ordinal(($counter+1));
                                        ?>
                                        Reviewed By : 
                                    </h5>
					                <p>
					                 	<?php if (!empty($value['User']['staff_name'])) echo $value['User']['staff_name']; ?><br/>
					                 	<?php if (!empty($value['User']['Department']['department_name'])) echo $value['User']['Department']['department_name']; ?><br/>
					                 	<?php if (!empty($value['User']['designation'])) echo $value['User']['designation']; ?><br/>
					                 	<?php if (!empty($value['NfStatus'][0]['modified'])) echo date('d M Y',strtotime($value['NfStatus'][0]['modified'])); ?><br/>
					                </p>
					            </td>
                                <td class="col-lg-9 noborder" style="width:75%">
                                	<table class="table table-bordered table-condensed">
                                		<thead>
                                		<tr class="">
                                				<th style="width:5%;text-align:center" class="light-grey-bg">
                                					<?php  if (!empty($value['NfStatus'][0]['status'])) {if ($value['NfStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
                                				</th>
                                				<th style="width:25%">Approved</th>
                                				<th style="width:5%;text-align:center" class="light-grey-bg">
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

                                								foreach ($val['NfRemarkAssign'] as $v) {
                                									if ($val['User']['staff_name']!=$v['User']['staff_name'])
                                										echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
                                								}

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
						                </td>
						            </tr>
						        </table>
	                <?php 
	            			$counter++;
	            		}
	                	endif; 
	                ?>

                </div>
			</section>
			<section class="panel">
				<header class="panel-heading noborder">
					<strong> 6. CEO Approval </strong>
					<small> (Guided by Corporate LOA) </small>
				</header>
				
				<div class="panel-body pdf-panel-body">
					
		            <?php 
		            	if (!empty($approver)):

		            		$counter=0;

                            foreach ($approver as $value) {
                    ?>
                    		<table class="pdf table">
                            	<tr>
                                	<td class="col-lg-3 noborder" style="width:25%">
                                		<h5 class="bold no-margin-bottom">
	                                        <?php
	                                            echo $this->Mems->ordinal(($counter+1));
	                                        ?>
	                                        Reviewed By : 
	                                    </h5>
						                <p>
						                 	<?php if (!empty($value['User']['staff_name'])) echo $value['User']['staff_name']; ?><br/>
						                 	<?php if (!empty($value['User']['Department']['department_name'])) echo $value['User']['Department']['department_name']; ?><br/>
						                 	<?php if (!empty($value['User']['designation'])) echo $value['User']['designation']; ?><br/>
						                 	<?php if (!empty($value['NfStatus'][0]['modified'])) echo date('d M Y',strtotime($value['NfStatus'][0]['modified'])); ?><br/>
						                </p>
					            </td>
                                <td class="col-lg-9 noborder" style="width:75%">
                                	<table class="table table-bordered table-condensed">
                                		<thead>
                                		<tr class="">
                                				<th style="width:5%;text-align:center" class="light-grey-bg">
                                					<?php  if (!empty($value['NfStatus'][0]['status'])) {if ($value['NfStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
                                				</th>
                                				<th style="width:25%">Approved</th>
                                				<th style="width:5%;text-align:center" class="light-grey-bg">
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

                                								foreach ($val['NfRemarkAssign'] as $v) {
                                									if ($val['User']['staff_name']!=$v['User']['staff_name'])
                                										echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
                                								}

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
                                			</td>
                                		</tr>
                                	</table>
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
              			echo $this->Form->create('NfStatus',array('url'=>array('controller'=>'NfMemo2','action'=>'approveRejectMemo'),'class'=>'form-horizontal','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
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