<section class="mems-content pdf-text-overwrite">
	<hr class="pdf-desc" >
		<table class='pdf table bigger-text'>
            <tr>
                <td class='bold noborder' style="width:14%">
                     <strong class='subject'>Ref. No</strong>
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                    <?php
                        if (!empty($memo['FMemo']['ref_no'])) 
                        	//echo $memo['FMemo']['ref_no'];
                           echo "<span class='pdfdesc'>". $memo['FMemo']['ref_no'] ."</span>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    <strong class='subject'>To</strong>
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                    <?php
                        if ( !empty($memo['FMemoTo']) ){ 
          					$temp=array();
							foreach ($memo['FMemoTo'] as $to) {
								$temp[]=$to['User']['staff_name'].' ('.$to['User']['designation'].')';
							}
							$tos=implode(', ',$temp); 
							// echo $tos;
							echo "<span class='pdfdesc'>". $tos ."</span>";
						  }
                    ?>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    <strong class='subject'>From</strong>
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                    <?php
                        if (!empty($memo['FMemo']['department_id'])) 
                        	//echo $memo['User']['staff_name'].' ('.$memo['User']['designation'].', '.$memo['User']['Department']['department_name'].')';
                            echo "<span class='pdfdesc'>". $memo['User']['staff_name'].' ('.$memo['User']['designation'].', '.$memo['User']['Department']['department_name'].')' ."</span>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    <strong class='subject'>Date</strong>
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                <span class="pdfdesc">
                    <?php
                        echo date('d F Y',strtotime($memo['FMemo']['created']));                        
                    ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    <strong class='subject'>Subject</strong>
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                    <?php
                        if (!empty($memo['FMemo']['subject']))
                        	echo "<strong style='text-transform:uppercase' class='subject'>". $memo['FMemo']['subject'] ."</strong>";
                    ?>
                </td>
            </tr>
        </table>
	<hr class="pdf-desc">

	<div class="col-lg-12">
		<div class='prequisite-data' id="tabletext">
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
       	  									if ($memo['FMemo']['vendor']=='1') 
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
       	  									if ($memo['FMemo']['budgeted']=='0') 
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
       	  					<tr>
       	  						<td class="noborder" colspan="3" style="text-align:right  ">
       	  							<b>Date required :</b> <?php if (!empty($memo['FMemo']['date_required'])) echo date('d F Y',strtotime($memo['FMemo']['date_required'])); ?> 
       	  						</td>
       	  					</tr>
       	  				</table>
       	  			</td>       	  			
       	  		</tr>
       	  		
       	  	</table>
	    </div>

		<?php 
			if (!empty($memo['FVendorAttachment'])): 
		?>
			<section class="panel">
				<header class="panel-heading noborder">
					<strong> Vendor Quotation(s) / Related File(s) </strong>
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">
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
            <?php 
				if (!empty($memo['FMemo']['vendor_remark'])): 
			?>
            <section class="panel pdf-panel">
				<header class="panel-heading noborder">
					<strong> Vendor Remark </strong>
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">
					<?php 
						if (!empty($memo['FMemo']['vendor_remark']))echo ($memo['FMemo']['vendor_remark']); 
					?>
                </div>
			</section>
			<?php 
            	endif;
            ?>

			<section class="panel pdf-panel">
				<header class="panel-heading noborder">
					<strong> 1. Introduction </strong>
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">
					<?php 
						if (!empty($memo['FMemo']['introduction']))echo ($memo['FMemo']['introduction']); 
					?>
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading noborder">
					<strong> 2. Subject Matters </strong>
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">

					<?php 
						if (!empty($memo['FMemo']['subject_matters']))
							echo ($memo['FMemo']['subject_matters']); 
					?>
					<strong> Budget summary </strong><br>
					<?php 
						if (!empty($memo['FMemoBudget'])){
					?> 
		              	<table class="table table-bordered table-condensed no-break" style="width: 100%;">

			                <thead>
			                    <tr class='unitar-blue-bg'>
			                        <th scope="col" width="5%" style="text-align:center">No.</th>
			                        <th scope="col" width="10%" style="text-align:center">Budget Year</th>
			                        <!-- <th scope="col" width="20%" style="text-align:center">Quarter</th> -->
			                        <th scope="col" width="35%" style="text-align:left">Budget Item</th>
			                        
			                        <th scope="col" width="20%" style="text-align:left">Unbudgeted</th>
			                        <th scope="col" width="20%" style="text-align:left">Budget Transfer</th>
			                        <th scope="col" width="10%" style="text-align:left"> Amount (RM)</th>
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
			                                    <td style="text-align: center"> <?php echo __(($value['Budget']['year'])); ?></td>
			                                  
			                                    <td style="text-align: left;"><?php echo ($value['BItemAmount']['Item']['code_item']);?></td>
			                                    <td style="text-align: left;">

			                                      <?php 

			                                        if (!empty($value['unbudgeted_amount'])):
			                                          echo 'RM'.number_format($value['unbudgeted_amount'],2,".",",");
													  echo "<br><small><b>Unbudgeted</b></small>";

			                                        endif;
			                                      ?>
			                                    </td>
			                                    <td style="text-align: left;">

			                                      <?php 

			                                        if (!empty($value['transferred_item_amount_id'])){
                                              
		                                              echo 'RM'.number_format($value['transferred_amount'],2,".",",");

		                                              echo "<br><small><b>Budget transfer from : <br>".$value['BItemAmountTransfer']['Item']['item'].' ('.$value['BItemAmountTransfer']['BDepartment']['Department']['department_shortform'].')</b></small>';
		                                          }
			                                      ?>
			                                    </td>
			                                    <td style="text-align: left"> 

			                                    <?php 
			                                    	
                                          			echo __(number_format($value['amount'],2,".",",")); 
			                                    ?></td>
			                                </tr>
			                           <?php
			                            $counter++;
			                            }
			                            endif;
			                           ?>
			                                  
			                </tbody>
			                <tfoot style="font-weight:bold">
			                    <tr class='info'>                                                
			                       <td colspan="5" style="text-align:right"> TOTAL (RM)</td>
			                        <td style="text-align:center"> <b><?php if (!empty($total)) echo number_format($total,2,".",","); ?></b></td>
			                    </tr>
			                </tfoot>
			              </table> 
					<?php } ?>
                </div>
			</section>

			<section class="panel pdf-panel">
				<header class="panel-heading noborder">
					<strong> 3. Recommendation/Conclusion </strong>
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">
					<?php 
						if (!empty($memo['FMemo']['recommendation']))
							echo ($memo['FMemo']['recommendation']); 
					?> 
                </div>
                <div class="panel-body pdf-panel-body" id="bodytext">
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
				                 				<?php if (!empty($memo['FMemo']['remark'])) echo ($memo['FMemo']['remark']); ?><br/>
				              				</td>
				              			</tr>
				              		</tbody>
				              	</table>
				            </td>
				        </tr>
				    	</table>
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading noborder">
					<strong> 4. Division/Department's Review/Recommendation </strong>
					<!-- <small> ( Guided by Division/Department LOA ) </small> -->
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">
					

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
                                        Reviewed by : 
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
								                            	 //   		if (!empty($val['FRemark']['subject'])) 
										                          		// 	echo "<b class='bigger-text'>". $val['FRemark']['subject'] ."</b>";

										                          		// echo "<br>";

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
																					echo "<br/><small>".$val['FRemarkFeedback'][$i]['feedback']."</small>"; 
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
                                        Recommended by : 
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
					                            	 //   		if (!empty($val['FRemark']['subject'])) 
							                          		// 	echo "<b class='bigger-text'>". $val['FRemark']['subject'] ."</b>";

							                          		// echo "<br>";

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
																		echo "<br/><small>".$val['FRemarkFeedback'][$i]['feedback']."</small>"; 
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
				<!-- 	<strong> 5. COO/CFO Approval </strong>
					<small> (Guided by Corporate LOA) </small> -->
						<strong> 5. Finance Approval </strong>
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">
					
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
                                        Reviewed by : 
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
					                            	 //   		if (!empty($val['FRemark']['subject'])) 
							                          		// 	echo "<b class='bigger-text'>". $val['FRemark']['subject'] ."</b>";

							                          		// echo "<br>";

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
																		echo "<br/><small>".$val['FRemarkFeedback'][$i]['feedback']."</small>"; 
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
					<strong> 6. COO/CFO or/and CEO Approval </strong>
					<small> (Guided by Corporate LOA) </small>
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">
					
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
                                        Approved by : 
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
					                            	 //   		if (!empty($val['FRemark']['subject'])) 
							                          		// 	echo "<b class='bigger-text'>". $val['FRemark']['subject'] ."</b>";

							                          		// echo "<br>";

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
																		echo "<br/><small>".$val['FRemarkFeedback'][$i]['feedback']."</small>"; 
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