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
                        if (!empty($memo['FMemo']['ref_no'])) 
                        	echo $memo['FMemo']['ref_no'];
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
                        if ( !empty($memo['FMemoTo']) ){ 
          					$temp=array();
							foreach ($memo['FMemoTo'] as $to) {
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
                        if (!empty($memo['FMemo']['department_id'])) 
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
                        if (!empty($memo['FMemo']['subject']))
                        	echo "<strong>". $memo['FMemo']['subject'] ."</strong>";
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
	       	  							<div class="check-box">
	       	  								<span class="fa fa-check"></span>
	       	  							</div> 
	       	  							<em> Financial </em>
	       	  						</td>
	       	  						<td class="noborder"> 
	       	  							<div class="check-box">
	       	  								<?php 
	       	  									if ($memo['FMemo']['budgeted']=='1') 
	       	  										echo "<span class='fa fa-check'></span>";
	       	  								?>
	       	  							</div> 
	       	  							<em> Budgeted </em>
	       	  						</td>
	       	  						<td class="noborder"> 
	       	  							<div class="check-box">
	       	  								<?php 
	       	  									if ($memo['FMemo']['budgeted']=='0') 
	       	  										echo "<span class='fa fa-check'></span>";
	       	  								?>
	       	  							</div> 
	       	  							<em> Approved Vendor </em>
	       	  						</td>
	       	  					</tr>
	       	  					<tr>
	       	  						<td class="noborder"> 
	       	  							<div class="check-box">	</div> 
	       	  							<em> Non-Financial </em>
	       	  						</td>
	       	  						<td class="noborder"> 
	       	  							<div class="check-box">
	       	  								<?php 
	       	  									if ($memo['FMemo']['vendor']=='1') 
	       	  										echo "<span class='fa fa-check'></span>";
	       	  								?>
	       	  							</div> 
	       	  							<em> Unbudgeted </em>
	       	  						</td>
	       	  						<td class="noborder"> 
	       	  							<div class="check-box">
	       	  								<?php 
	       	  									if ($memo['FMemo']['vendor']=='0') 
	       	  										echo "<span class='fa fa-check'></span>";
	       	  								?>
	       	  							</div> 
	       	  							<em> New Vendor </em>
	       	  						</td>
	       	  					</tr>
	       	  				</table>
	       	  			</td>
	       	  			<td style="width: 30%" class="text-right noborder">
	       	  				<strong> Date required :  </strong>
	       	  				<?php 
	       	  					if (!empty($memo['FMemo']['date_required'])) 
	       	  						echo date('d F Y',strtotime($memo['FMemo']['date_required'])); 
	       	  				?> 
	       	  			</td>
	       	  		</tr>
	       	  	</table>
	              
	       </div>
		</section>

		<?php 
			if (!empty($memo['FVendorAttachment'])): 
		?>
			<section class="panel">
				<header class="panel-heading noborder">
					<strong> Vendor Quotation(s) / Related File(s) </strong>
				</header>
				
				<div class="panel-body pdf-panel-body">
					<table class="table">
						<thead>
							<th colspan="2" >File</th>
						</thead>
						<tbody>
							<?php foreach ($memo['FVendorAttachment'] as $value) {?>
							<tr>
								<td style="width:5%">
									<i class=" fa fa-list-ul"></i> 
								</td>
								<td style="width:95%">
									<?php if (!empty($value['filename'])){
										$tmpName=explode('___',$value['filename']);
										if (count($tmpName)>1)
											$filename=$tmpName[1];
										else
											$filename=$value['filename'];

										echo $filename;

									}  ?>
								</td>
							</tr>
						</tbody>
						<?php 
							} 
						?>
					</table>
                </div>
			</section>
            <?php 
            	endif;
            ?>

			<section class="panel">
				<header class="panel-heading noborder">
					<strong> 1. Introduction </strong>
				</header>
				
				<div class="panel-body pdf-panel-body">
					<table class="pdf table hasBorder">
						<tr>
							<td class="noborder">
								<?php 
									if (!empty($memo['FMemo']['introduction']))echo nl2br($memo['FMemo']['introduction']); 
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
									if (!empty($memo['FMemo']['subject_matters']))
										echo nl2br($memo['FMemo']['subject_matters']); 
								?>
							</td>
						</tr>
						<tr>
							<td class="noborder">
								<strong> Budget summary </strong><br>
								<?php 
									if (!empty($memo['FMemoBudget'])){
								?> 
					              	<table class="table table-bordered table-striped table-condensed">

						                <thead>
						                    <tr>
						                        <th scope="col" width="5%" style="text-align:center">No.</th>
						                        <th scope="col" width="10%" style="text-align:center">Year</th>
						                        <!-- <th scope="col" width="20%" style="text-align:center">Quarter</th> -->
						                        <th scope="col" width="45%" style="text-align:left">Item</th>
						                        <th scope="col" width="10%" style="text-align:center">Amount (RM)</th>
						                    </tr>
						                </thead>

						                <tbody>
						                    <?php
						                        if(!empty($memo['FMemoBudget'])):
						                            $counter=1;
						                          $total=0;
						                            //debug($items);exit();
						                            foreach ($memo['FMemoBudget'] as $value) {
						                                $total+=$value['amount'];
						                        ?>
						                                <tr>
						                                    <td style="text-align: center"><b><?php echo __($counter);  ?></b></td>
						                                    <td style="text-align: center"> <?php echo __(nl2br($value['year'])); ?></td>
						                                    <!-- <td style="text-align: center"> <?php echo __(nl2br($value['quarter'])); ?></td> -->
						                                    <td style="text-align: left;"><?php echo ($value['BItem']['item']);?></td>
						                                    <td style="text-align: center"> <?php echo __(nl2br($value['amount'])); ?></td>
						                                </tr>
						                           <?php
						                            $counter++;
						                            }
						                            endif;
						                           ?>
						                                  
						                </tbody>
						                <tfoot style="font-weight:bold">
						                    <tr>                                                
						                       <td colspan="4" style="text-align:right"> TOTAL (RM)</td>
						                        <td style="text-align:center"> <b><?php if (!empty($total)) echo $total; ?></b></td>
						                    </tr>
						                </tfoot>
						              </table> 
								<?php } ?>
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
									if (!empty($memo['FMemo']['recommendation']))
										echo nl2br($memo['FMemo']['recommendation']); 
								?>
							</td>
						</tr>
					</table>   
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading noborder">
					<strong> 4. Division/Department's Review/Recommendation </strong>
					<small> ( Guided by Division/Department LOA ) </small>
				</header>
				
				<div class="panel-body pdf-panel-body">
					<table class="pdf table">
                    	<tr>
                    		<td class="col-lg-3 noborder" style="width:25%">
                    			<h5><b>Prepared by:</b></h5>
				                <p>
				                 	<?php if (!empty($memo['FMemo']['user_id'])) echo $memo['User']['staff_name']; ?><br/>
				                 	<?php if (!empty($memo['FMemo']['department_id'])) echo $memo['User']['Department']['department_name']; ?><br/>
				                 	<?php if (!empty($memo['User']['designation'])) echo $memo['User']['designation']; ?><br/>
				                 	<?php if (!empty($memo['FMemo']['created'])) echo date('d M Y',strtotime($memo['FMemo']['created'])); ?><br/>
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
				                 				<?php if (!empty($memo['FMemo']['remark'])) echo nl2br($memo['FMemo']['remark']); ?><br/>
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

                            foreach ($reviewer as $value) {
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
					                 	<?php if (!empty($value['FStatus'][0]['modified'])) echo date('d M Y',strtotime($value['FStatus'][0]['modified'])); ?><br/>
					                </p>
					            </td>
					            <td class="col-lg-9 noborder" style="width:75%">
					            	<table class="table table-bordered table-condensed">
					            		<thead>
					            			<tr class="">
					            				<th style="width:5%;text-align:center" class="light-grey-bg">
					            					<?php if (!empty($value['FStatus'][0]['status'])) {if ($value['FStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					            				</th>
					            				<th style="width:25%">Approved</th>
					            				<th style="width:5%;text-align:center" class="light-grey-bg">
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
					            						<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. nl2br($value['FStatus'][0]['remark']); ?>
					            						</p>
					            					</td>
					            					<td colspan="2">
					            						<!-- <h5><b>Remark(s) : </b></h5> -->
					            						<p style="text-align:justify">
					            							<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.nl2br($value['FStatus'][0]['remark']); ?>
					            							</p>
					            						</td>
					            						<td>
					            							<?php if (!empty($remark_reviewer[$counter])):

					            							foreach ($remark_reviewer[$counter] as $val) {?>

					            							<h5><b>Remarks : <?php if (!empty($val['FRemark']['subject'])) echo $val['FRemark']['subject']; ?></b></h5>
					            							<p>
					            								<b>To</b> : <?php if (!empty($val['FRemarkAssign'])):

					            								foreach ($val['FRemarkAssign'] as $v) {
					            									if ($val['User']['staff_name']!=$v['User']['staff_name'])
					            										echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
					            								}

					            								endif;

					            								?>
					            							</p>
					            							<p style="text-align:justify">
					            								<?php if (!empty($val['FRemarkFeedback'][0]['feedback'])) echo $val['FRemarkFeedback'][0]['feedback']; ?>
					            							</p> 


					            							<table class="table">
					            								<?php if (count($val['FRemarkFeedback'])>1):
					            								for ($i=1;$i<count($val['FRemarkFeedback']);$i++){
					            									?>
					            									<tr style="text-align:justify">
					            										<td>
					            											<h5><b>Feedback from <?php echo $val['FRemarkFeedback'][$i]['User']['staff_name']; ?> (<?php echo date('d M Y',strtotime($val['FRemarkFeedback'][$i]['created'])); ?>) :</b></h5>
					            											<p><?php if (!empty($val['FRemarkFeedback'][$i]['feedback'])) echo $val['FRemarkFeedback'][$i]['feedback']; ?></p>
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
					                 	<?php if (!empty($value['User']['staff_name'])) echo $value['User']['staff_name']; ?><br/>
					                 	<?php if (!empty($value['User']['Department']['department_name'])) echo $value['User']['Department']['department_name']; ?><br/>
					                 	<?php if (!empty($value['User']['designation'])) echo $value['User']['designation']; ?><br/>
					                 	<?php if (!empty($value['FStatus'][0]['modified'])) echo date('d M Y',strtotime($value['FStatus'][0]['modified'])); ?><br/>
					                 </p>
					            </td>
						        <td class="col-lg-9 noborder" style="width:75%">
									<table class="table table-bordered table-condensed">
				                      <thead>
				                      	<tr class="">
					                      <th style="width:5%;text-align:center" class="light-grey-bg">
					                      	<?php  if (!empty($value['FStatus'][0]['status'])) {if ($value['FStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Approved</th>
					                      <th style="width:5%;text-align:center" class="light-grey-bg">
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
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. nl2br($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td colspan="2">
					                          	 <!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.nl2br($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td>
					                          	<?php if (!empty($remark_recommender[$counter])):

						                            foreach ($remark_recommender[$counter] as $val) {?>

							                          	<h5><b>Remarks : <?php if (!empty($val['FRemark']['subject'])) echo $val['FRemark']['subject']; ?></b></h5>
							                          	<p>
							                          		<b>To</b> : <?php if (!empty($val['FRemarkAssign'])):

										                            			foreach ($val['FRemarkAssign'] as $v) {
										                            				if ($val['User']['staff_name']!=$v['User']['staff_name'])
										                            				echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
										                            			}

										                            		endif;

										                            	?>
							                          	</p>
							                          	<p style="text-align:justify">
							                          		<?php if (!empty($val['FRemarkFeedback'][0]['feedback'])) echo $val['FRemarkFeedback'][0]['feedback']; ?>
							                          	</p> 
							                          	 

						                          		<table class="table">
						                          			<?php if (count($val['FRemarkFeedback'])>1):
								                          			for ($i=1;$i<count($val['FRemarkFeedback']);$i++){
								                          	?>
						                          			<tr style="text-align:justify">
						                          				<td>
																	<h5><b>Feedback from <?php echo $val['FRemarkFeedback'][$i]['User']['staff_name']; ?> (<?php echo date('d M Y',strtotime($val['FRemarkFeedback'][$i]['created'])); ?>) :</b></h5>
																	<p><?php if (!empty($val['FRemarkFeedback'][$i]['feedback'])) echo $val['FRemarkFeedback'][$i]['feedback']; ?></p>
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
					
		            <?php if (!empty($finance)):

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
					                 	<?php if (!empty($value['FStatus'][0]['modified'])) echo date('d M Y',strtotime($value['FStatus'][0]['modified'])); ?><br/>
					                 </p>
					            </td>
					            <td class="col-lg-9 noborder" style="width:75%">
									<table class="table table-bordered table-condensed">
				                      <thead>
				                      	<tr class="">
					                      <th style="width:5%;text-align:center" class="light-grey-bg">
					                      	<?php  if (!empty($value['FStatus'][0]['status'])) {if ($value['FStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Approved</th>
					                      <th style="width:5%;text-align:center" class="light-grey-bg">
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
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. nl2br($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td colspan="2">
					                          	 <!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.nl2br($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td>
					                          	<?php if (!empty($remark_finance[$counter])):

						                            foreach ($remark_finance[$counter] as $val) {?>

							                          	<h5><b>Remarks : <?php if (!empty($val['FRemark']['subject'])) echo $val['FRemark']['subject']; ?></b></h5>
							                          	<p>
							                          		<b>To</b> : <?php if (!empty($val['FRemarkAssign'])):

										                            			foreach ($val['FRemarkAssign'] as $v) {
										                            				if ($val['User']['staff_name']!=$v['User']['staff_name'])
										                            				echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
										                            			}

										                            		endif;

										                            	?>
							                          	</p>
							                          	<p style="text-align:justify">
							                          		<?php if (!empty($val['FRemarkFeedback'][0]['feedback'])) echo $val['FRemarkFeedback'][0]['feedback']; ?>
							                          	</p> 
							                          	 

						                          		<table class="table">
						                          			<?php if (count($val['FRemarkFeedback'])>1):
								                          			for ($i=1;$i<count($val['FRemarkFeedback']);$i++){
								                          	?>
						                          			<tr style="text-align:justify">
						                          				<td>
																	<h5><b>Feedback from <?php echo $val['FRemarkFeedback'][$i]['User']['staff_name']; ?> (<?php echo date('d M Y',strtotime($val['FRemarkFeedback'][$i]['created'])); ?>) :</b></h5>
																	<p><?php if (!empty($val['FRemarkFeedback'][$i]['feedback'])) echo $val['FRemarkFeedback'][$i]['feedback']; ?></p>
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
					                 	<?php if (!empty($value['FStatus'][0]['modified'])) echo date('d M Y',strtotime($value['FStatus'][0]['modified'])); ?><br/>
					                 </p>
					            </td>
					            <td class="col-lg-9 noborder" style="width:75%">
									<table class="table table-bordered table-condensed">
				                      <thead>
				                      	<tr class="">
					                      <th style="width:5%;text-align:center" class="light-grey-bg">
					                      	<?php  if (!empty($value['FStatus'][0]['status'])) {if ($value['FStatus'][0]['status']=='approved') echo '<i class="fa fa-check"></i>' ;}?>
					                      </th>
					                      <th style="width:25%">Approved</th>
					                      <th style="width:5%;text-align:center" class="light-grey-bg">
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
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. nl2br($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td colspan="2">
					                          	 <!-- <h5><b>Remark(s) : </b></h5> -->
						                         <p style="text-align:justify">
						                         	<?php if (!empty($value['FStatus'][0]['remark'])&&$value['FStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.nl2br($value['FStatus'][0]['remark']); ?>
						                         </p>
					                          </td>
					                          <td>
					                          	<?php if (!empty($remark_approver[$counter])):

						                            foreach ($remark_approver[$counter] as $val) {?>

							                          	<h5><b>Remarks : <?php if (!empty($val['FRemark']['subject'])) echo $val['FRemark']['subject']; ?></b></h5>
							                          	<p>
							                          		<b>To</b> : <?php if (!empty($val['FRemarkAssign'])):

										                            			foreach ($val['FRemarkAssign'] as $v) {
										                            				if ($val['User']['staff_name']!=$v['User']['staff_name'])
										                            				echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
										                            			}

										                            		endif;

										                            	?>
							                          	</p>
							                          	<p style="text-align:justify">
							                          		<?php if (!empty($val['FRemarkFeedback'][0]['feedback'])) echo $val['FRemarkFeedback'][0]['feedback']; ?>
							                          	</p> 
							                          	 

						                          		<table class="table">
						                          			<?php if (count($val['FRemarkFeedback'])>1):
								                          			for ($i=1;$i<count($val['FRemarkFeedback']);$i++){
								                          	?>
						                          			<tr style="text-align:justify">
						                          				<td>
																	<h5><b>Feedback from <?php echo $val['FRemarkFeedback'][$i]['User']['staff_name']; ?> (<?php echo date('d M Y',strtotime($val['FRemarkFeedback'][$i]['created'])); ?>) :</b></h5>
																	<p><?php if (!empty($val['FRemarkFeedback'][$i]['feedback'])) echo $val['FRemarkFeedback'][$i]['feedback']; ?></p>
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
</section>