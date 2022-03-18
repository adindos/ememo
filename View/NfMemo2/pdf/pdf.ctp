<section class="mems-content pdf-text-overwrite">
	<hr class="pdf-desc">
		<table class='pdf table bigger-text'>
            <tr>
                <td class='bold noborder' style="width:14%">
                    <strong class='subject'>Ref. No</strong>
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%" >  <span class="pdfdesc">
                    <?php
                        if (!empty($memo['NfMemo']['ref_no'])) 
                        	echo $memo['NfMemo']['ref_no'];
                    ?></span>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    <strong class='subject'>To</strong>
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">  <span class="pdfdesc">
                    <?php
                        if ( !empty($memo['NfMemoTo']) ){ 
          					$temp=array();
							foreach ($memo['NfMemoTo'] as $to) {
								$temp[]=$to['User']['staff_name'].' ('.$to['User']['designation'].')';
							}
							$tos=implode(', ',$temp); 
							echo $tos;
						  }
                    ?></span>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    <strong class='subject'>From</strong><strong class='subject'>
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">  <span class="pdfdesc">
                	<?php echo $memo['User']['staff_name']; ?>  
                    ( 
                        <?php
                            if (!empty($memo['User']['designation'])) 
                                echo $memo['User']['designation'];
                        ?>
                        ,
                        <?php
                        if (!empty($memo['NfMemo']['department_id'])) 
                        	echo $memo['User']['Department']['department_name'];
                    ?>)</span>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    <strong class='subject' style="font-size:12px">Date</strong>
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">    <span class="pdfdesc" style="font-size:12px">
                    <?php
                    	echo date('d F Y',strtotime($memo['NfMemo']['created']));
                    ?></span>
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
                        if (!empty($memo['NfMemo']['subject']))
                        	echo "<strong style='text-transform:uppercase' class='subject'>". $memo['NfMemo']['subject'] ."</strong>";
                    ?>
                </td>
            </tr>
        </table>
	<hr class="pdf-desc">

	<div class="col-lg-12" >
		<div class='prequisite' id="tabletext">
       	  	<em> Prerequisites :</em>
       	  	<table class="pdf table" >
       	  		<tr>
       	  			<td style="width: 70%" class="noborder">
       	  				<table class="pdf table">
       	  					<tr>
       	  						<td class="noborder"> 
       	  							<div class="check-box"></div> <em> Financial </em>
       	  						</td>
       	  					</tr>
       	  					<tr>
       	  						<td class="noborder"> 
       	  							<div class="check-box"><span class="fa fa-check"></span></div> <em> Non-Financial </em>
       	  						</td>
       	  					</tr>
       	  				</table>                        
       	  			</td>   
                    <td style="width: 70%" class="noborder">
                        <table class="pdf table">
                            <tr rowspan=2>
                                <td class="noborder"> 
                                    <b>Date required :</b> <?php if (!empty($memo['NfMemo']['date_required'])) echo date('d F Y',strtotime($memo['NfMemo']['date_required'])); ?> 
                                </td>
                            </tr>                          
                        </table>                        
                    </td>                   
       	  		</tr>
       	  	</table> 
	    </div>

		<?php if (!empty($memo['NfAttachment'])): ?>
				<section class="panel">
					<header class="panel-heading noborder">
					<strong> Related File(s) </strong>
				</header>			
				
				<div class="panel-body">
	              <div class="col-lg-12"> 
	              	<table class="table" id="tabletext">
                          <thead>
                          	<th colspan="2" >File</th>
                          </thead>
                          <tbody>
                          	<?php foreach ($memo['NfAttachment'] as $value) {?>
                            <tr>
                                <td style="width:10%">
                                    <i class=" fa fa-list-ol"></i> 
                                </td>
                                <td style="width:75%">
                                	<?php if (!empty($value['filename'])){
											$tmpName=explode('___',$value['filename']);
					                        if (count($tmpName)>1)
					                          $filename=$tmpName[1];
					                        else
					                          $filename=$value['filename'];

					                      	echo $filename;

                                		}  ?>
                                </td>
                                </td>
                               
                            </tr>
                            <?php } ?>
                          </tbody>
                    </table>
                  	</div>
                	</div>
				</section>
			<?php endif;?>
			
			<section class="panel pdf-panel">
				<header class="panel-heading noborder">
					<strong> 1. Introduction </strong>
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">
					<?php 
						if (!empty($memo['NfMemo']['introduction']))
							echo ($memo['NfMemo']['introduction']); 
					?>
                </div>
			</section>

			<section class="panel pdf-panel">
				<header class="panel-heading noborder" >
					<strong> 2. Subject Matters </strong>
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">
					<?php 
						if (!empty($memo['NfMemo']['subject_matters']))
							echo ($memo['NfMemo']['subject_matters']); 
					?>
                </div>
			</section>

			
			<section class="panel pdf-panel">
				<header class="panel-heading noborder">
					<strong> 3. Recommendation/Conclusion </strong>
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">
					<?php 
						if (!empty($memo['NfMemo']['recommendation'])) 
							echo ($memo['NfMemo']['recommendation']); 
					?>          
                </div>
                <div class="panel-body pdf-panel-body" id="bodytext">
                    <table class="pdf table no-break">
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
                                                <?php if (!empty($memo['NfMemo']['remark'])) echo ($memo['NfMemo']['remark']); ?><br/>
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
					<small> (Guided by Division/Department LOA) </small> 
				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">

		            <?php 
		            	if (!empty($reviewer)):
		            		$counter=0;
                            foreach ($reviewer as $value){
                    ?>
                		<table class="pdf table no-break" >
                			<tr>
                                <td class="col-lg-3 noborder" style="width:25%">
                                    <h5 class="bold no-margin-bottom">
                                        <?php
                                            echo $this->Mems->ordinal(($counter+1));
                                        ?>
                                        Reviewed by : 
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
					            						<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. ($value['NfStatus'][0]['remark']); ?>
					            						</p>
					            					</td>
					            					<td colspan="2">
					            						<!-- <h5><b>Remark(s) : </b></h5> -->
					            						<p style="text-align:justify">
					            							<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.($value['NfStatus'][0]['remark']); ?>
					            							</p>
					            						</td>
					            						<td>
					            							<?php 
								                          		if (!empty($remark_reviewer[$counter])):

									                            	foreach ($remark_reviewer[$counter] as $val):
								                            	 //   		if (!empty($val['NfRemark']['subject'])) 
										                          		// 	echo "<b class='bigger-text'>". $val['NfRemark']['subject'] ."</b>";

										                          		// echo "<br>";

																		if (!empty($val['NfRemarkFeedback'][0]['feedback'])) 
																		 	echo  $val['NfRemarkFeedback'][0]['feedback'] ; 


									                          			if (count($val['NfRemarkFeedback'])>1):                    				
										                          			for ($i=1;$i<count($val['NfRemarkFeedback']);$i++):
										                          				echo "<div style='margin-left:10px;margin-bottom:3px; background: rgba(102,178,255,0.2);padding: 3px 10px;color:#333'>";
										                          				echo "<b>".$val['NfRemarkFeedback'][$i]['User']['staff_name']."</b>";
										                          				echo "<small><em>";
										                          				echo " on ";
										                          				echo date('d F Y',strtotime($val['NfRemarkFeedback'][$i]['created']));
										                          				echo "</em></small>"; 

																				if (!empty($val['NfRemarkFeedback'][$i]['feedback'])) 
																					echo "<br><small>".$val['NfRemarkFeedback'][$i]['feedback']."</small>"; 
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
                    		<table class="pdf table no-break" >
                            <tr>
                                <td class="col-lg-3 noborder" style="width:25%">
                                    <h5 class="bold no-margin-bottom">
                                        <?php
                                            echo $this->Mems->ordinal(($counter+1));
                                        ?>
                                        Recommended by : 
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
                                						<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. ($value['NfStatus'][0]['remark']); ?>
                                						</p>
                                					</td>
                                					<td colspan="2">
                                						<!-- <h5><b>Remark(s) : </b></h5> -->
                                						<p style="text-align:justify">
                                							<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.($value['NfStatus'][0]['remark']); ?>
                                							</p>
                                						</td>
                                						<td>
                                							<?php 
								                          		if (!empty($remark_recommender[$counter])):

									                            	foreach ($remark_recommender[$counter] as $val):
								                            	 //   		if (!empty($val['NfRemark']['subject'])) 
										                          		// 	echo "<b class='bigger-text'>". $val['NfRemark']['subject'] ."</b>";

										                          		// echo "<br>";

																		if (!empty($val['NfRemarkFeedback'][0]['feedback'])) 
																		 	echo  $val['NfRemarkFeedback'][0]['feedback'] ; 
																		 

									                          			if (count($val['NfRemarkFeedback'])>1):                    				
										                          			for ($i=1;$i<count($val['NfRemarkFeedback']);$i++):
										                          				echo "<div style='margin-left:10px;margin-bottom:3px; background: rgba(102,178,255,0.2);padding: 3px 10px;color:#333'>";
										                          				echo "<b>".$val['NfRemarkFeedback'][$i]['User']['staff_name']."</b>";
										                          				echo "<small><em>";
										                          				echo " on ";
										                          				echo date('d F Y',strtotime($val['NfRemarkFeedback'][$i]['created']));
										                          				echo "</em></small>"; 

																				if (!empty($val['NfRemarkFeedback'][$i]['feedback'])) 
																					echo "<br><small>".$val['NfRemarkFeedback'][$i]['feedback']."</small>"; 
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
					<!-- <strong> 5. COO and/or CEO Approval</strong>-->
                     <strong> 5. COO/CFO and/or CEO Approval</strong>
					<small> (Guided by Corporate LOA) </small> 

				</header>
				
				<div class="panel-body pdf-panel-body" id="bodytext">
					
		            <?php 
		            	if (!empty($approver)):

		            		$counter=0;

                            foreach ($approver as $value) {
                    ?>
                    		<table class="pdf table no-break">
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
                                						<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='approved') echo '<h5><b>Remark(s) : </b></h5>'. ($value['NfStatus'][0]['remark']); ?>
                                						</p>
                                					</td>
                                					<td colspan="2">
                                						<!-- <h5><b>Remark(s) : </b></h5> -->
                                						<p style="text-align:justify">
                                							<?php if (!empty($value['NfStatus'][0]['remark'])&&$value['NfStatus'][0]['status']=='rejected') echo '<h5><b>Remark(s) : </b></h5>'.($value['NfStatus'][0]['remark']); ?>
                                							</p>
                                						</td>
                                						<td>
                                							<?php 
								                          		if (!empty($remark_approver[$counter])):

									                            	foreach ($remark_approver[$counter] as $val):
								                            	 //   		if (!empty($val['NfRemark']['subject'])) 
										                          		// 	echo "<b class='bigger-text'>". $val['NfRemark']['subject'] ."</b>";

										                          		// echo "<br>";

																		if (!empty($val['NfRemarkFeedback'][0]['feedback'])) 
																		 	echo  $val['NfRemarkFeedback'][0]['feedback'] ; 
																		 

									                          			if (count($val['NfRemarkFeedback'])>1):                    				
										                          			for ($i=1;$i<count($val['NfRemarkFeedback']);$i++):
										                          				echo "<div style='margin-left:10px;margin-bottom:3px; background: rgba(102,178,255,0.2);padding: 3px 10px;color:#333'>";
										                          				echo "<b>".$val['NfRemarkFeedback'][$i]['User']['staff_name']."</b>";
										                          				echo "<small><em>";
										                          				echo " on ";
										                          				echo date('d F Y',strtotime($val['NfRemarkFeedback'][$i]['created']));
										                          				echo "</em></small>"; 

																				if (!empty($val['NfRemarkFeedback'][$i]['feedback'])) 
																					echo "<br><small>".$val['NfRemarkFeedback'][$i]['feedback']."</small>"; 
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