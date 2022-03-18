<section class="mems-content pdf-text-overwrite">
    <hr style='border-bottom: 1px solid #666;width:95%'>
        <table class='pdf table'>
            <tr>
                <td class='bold ' style="width:14%">
                    Budget Title
                </td>
                <td class="bold " style="width:1%">
                    :
                </td>
                <td style="width:85%">
                    <?php
                        echo $budgetDetail['Budget']['title'];
                    ?>
                </td>
            </tr>
            <tr>
                <td class='bold ' style="width:14%">
                    Department
                </td>
                <td class="bold " style="width:1%">
                    :
                </td>
                <td style="width:85%">
                    <?php
                        echo $budgetDetail['Department']['department_name'];
                    ?>
                </td>
            </tr>
            <tr>
                <td class='bold ' style="width:14%">
                    Quarter / Year
                </td>
                <td class="bold " style="width:1%">
                    :
                </td>
                <td style="width:85%">
                    <?php
                        echo $budgetDetail['Budget']['quarter'] . " ( ".$budgetDetail['Budget']['year']. " )";
                    ?>
                </td>
            </tr>
        </table>  
    <hr style='border-bottom: 1px solid #666;width:95%'>
	<!-- Budget -->
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading unitar-grey-bg">
				<strong>
					Budget Review
				</strong>
			</header>
			<div class="panel-body" style="padding:0">
	            <table class="table table-striped table-advance table-bordered">
		            <thead>
		              <tr class="">
		                <th colspan="2" class="col-lg-7"> Description </th>
		                <th class="text-center col-lg-2"> Amount (RM) </th>
		              </tr>
		            </thead>
		            <tbody>
		              <?php
		                $categoryID =null;
		                $no=1;
		                $totalAmount  = 0;
		                foreach($budgetItem as $b):
		                  if($b['BItem']['category_id'] != $categoryID):
		                    echo "<tr class='info'>";
		                    echo "<td colspan='4'>".$b['BItem']['BCategory']['category'] . "</td>";
		                    echo "</tr>";
		                    $no=1;
		                  endif;

		                  //iterate item
		                  echo "<tr>";
		                  echo "<td class='text-center col-lg-1'>".$no++.".</td>";
		                  echo "<td>".$b['BItem']['item']."</td>";
		                  echo "<td class='text-center'><b>".number_format($b['BNewAmount']['amount'],0,".",",")."</b></td>";
		                  echo "</tr>";

		                  $categoryID = $b['BItem']['category_id']; 
		                  $totalAmount += $b['BNewAmount']['amount'];
		                endforeach;
		              ?>
		              <tr class="success">
		                <td colspan="2" class="bold text-right bigger-text"> Total Amount </td>
		                <td class="text-center bold bigger-text"><?php echo number_format($totalAmount,0,".",","); ?></td>
		                <td class="edit-activated hide"></td>
		              </tr>
		            </tbody>
		          </table>
            </div>
		</section>
	</div>

    

	<div class="col-lg-12">

        <div class="pdf-page-break"></div>

		<section class="panel">
			<header class="panel-heading unitar-grey-bg">
				<strong>
					Division/Department's Review/Recommendation <br>
				</strong>
				<small> ( Guided by Division/Department LOA ) </small>
			</header>
			
			<div class="panel-body" style="padding:0">
					<table class="table table-bordered table-condensed">
						<thead >
							<tr>
								<th class='light-grey-bg' style="width:25%"> Prepared By : </th>
								<th class='info' style="width:75%"> Remark(s)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<p>
									<?php
										echo "<span class='bigger-text'>".$budgetDetail['User']['staff_name']."</span>";
										echo "<br>";
										echo "<small>";
										echo $budgetDetail['Department']['department_name'];
										echo "<br>";
										echo $budgetDetail['User']['designation'];
										echo "<br>";
										echo date('d F Y',strtotime($budgetDetail['Budget']['created'])); 
										echo "</small>";
									?>
									</p>
								</td>
								<td >
									<?php 
										if (!empty($budgetDetail['Budget']['remark'])) 
											echo nl2br($budgetDetail['Budget']['remark']); 
									?>
								</td>
							</tr>
						</tbody>
					</table>

				<!-- Reviewer remark -->
				<?php 
					if (!empty($reviewers)):
	            		$counter=0;
                        foreach ($reviewers as $reviewer):
                ?>
            				<table class="table table-bordered table-condensed">
            					<thead>
            						<tr>
            							<th colspan="2" class="light-grey-bg col-lg-3"> Reviewed By </th>
            							<th class="info" style="width:5%;text-align:center">
            								<?php 
            									if ($reviewer['BStatus'][0]['status']=='approved') 
            										echo '<i class="fa fa-check"></i>' ;
            								?>
            							</th>
            							<th class="info" style="width:17%">Approved</th>
            							<th class="info" style="width:5%;text-align:center">
            								<?php 
            									if ($reviewer['BStatus'][0]['status']=='rejected') 
            										echo '<i class="fa fa-check"></i>' ;
            								?>
            							</th>
            							<th class="info" style="width:17%">Rejected</th>
            							<th class="info" style="width:31%">Other remark(s)</th>
            						</tr>
            					</thead>
            					<tbody>
            						<tr style="text-align:justify">
            							<td class='text-center' style="width:3%">
            								<?php
            									echo $counter+1;
            								?>
            							</td>
            							<td>
				            				<p>
				            					<?php
													echo "<span class='bigger-text'>".$reviewer['User']['staff_name'] ."</span>";
													echo "<br>";
													echo "<small>";
													echo $reviewer['User']['Department']['department_name'];
													echo "<br>";
													echo $reviewer['User']['designation'];
													echo "<br>";
													echo date('d F Y',strtotime($reviewer['BStatus'][0]['modified'])); 
													echo "</small>";
												?>
				            				</p>
            							</td>
            							<td colspan="2">
            								<!-- <h5><b>Remark(s) : </b></h5> -->
            								<p style="text-align:justify">
            									<?php 
            										if (!empty($reviewer['BStatus'][0]['remark']) && $reviewer['BStatus'][0]['status']=='approved') 
            											echo '<h5><b>Remark(s) : </b></h5>'. nl2br($reviewer['BStatus'][0]['remark']);
            										?>
            									</p>
            								</td>
            								<td colspan="2">
            									<!-- <h5><b>Remark(s) : </b></h5> -->
            									<p style="text-align:justify">
            										<?php 
            											if (!empty($reviewer['BStatus'][0]['remark']) && $reviewer['BStatus'][0]['status']=='rejected') 
            												echo '<h5><b>Remark(s) : </b></h5>'.nl2br($reviewer['BStatus'][0]['remark']); 
            										?>
            										</p>
            									</td>
            									<td>
            										<?php 
            											if (!empty($remark_reviewer[$counter])):
            												foreach ($remark_reviewer[$counter] as $val):
            										?>
            											<h5><b>Remarks : 
                										<?php 
                											if (!empty($val['BRemark']['subject'])) 
                												echo $val['BRemark']['subject']; 
                										?>
                										</b></h5>
                										<p>
                											<b>To</b> : 
                											<?php 
                												if (!empty($val['BRemarkAssign'])):
		                											foreach ($val['BRemarkAssign'] as $v) {
		                												if ($val['User']['staff_name']!=$v['User']['staff_name'])
		                													echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
		                											}
                												endif;
                											?>
                										</p>
                										<p style="text-align:justify">
                											<?php 
                												if (!empty($val['BRemarkFeedback'][0]['feedback'])) 
                													echo $val['BRemarkFeedback'][0]['feedback']; ?>
                										</p> 


            											<table class="table">
                											<?php 
                												if (count($val['BRemarkFeedback'])>1):
                													for ($i=1;$i<count($val['BRemarkFeedback']);$i++):
                											?>
                												<tr style="text-align:justify">
                													<td>
                														<h5><b>Feedback from 
                														<?php 
                															echo $val['BRemarkFeedback'][$i]['User']['staff_name'];
                															echo " ( "; 
                															echo date('d M Y',strtotime($val['BRemarkFeedback'][$i]['created']));
                															echo " ) ";
                														?>
                														:</b></h5>
                														<p>
                															<?php 
                																if (!empty($val['BRemarkFeedback'][$i]['feedback'])) echo $val['BRemarkFeedback'][$i]['feedback']; 
                															?>
                														</p>
                													</td>
                												</tr>
                											<?php 
                													endfor;
                												endif; 
                											?>
            											</table>

            											<?php 
            													endforeach;
            											 	endif; 
            											?>
            										</td>
            									</tr>
            								</tbody>
            							</table>
                <?php 
            			$counter++;
            			endforeach;
                	endif; 
                ?>
            </div>
		</section>

        <div class="pdf-page-break"></div>
        
		<section class="panel">
			<header class="panel-heading unitar-grey-bg">
				<strong> 
					CEO Approval 
				</strong><br>
				<small> ( Guided by Corporate LOA ) </small>
			</header>
			
			<div class="panel-body"  style="padding:0">
				<!-- Reviewer remark -->
				<?php 
					if (!empty($approvers)):
	            		$counter=0;
                        foreach ($approvers as $approver):
                ?>

            		<table class="table table-bordered table-condensed">
            					<thead>
            						<tr>
            							<th colspan="2" class="light-grey-bg col-lg-3"> Reviewed By </th>
            							<th class="info" style="width:5%;text-align:center">
            								<?php 
            									if ($approver['BStatus'][0]['status']=='approved') 
            										echo '<i class="fa fa-check"></i>' ;
            								?>
            							</th>
            							<th class="info" style="width:17%">Approved</th>
            							<th class="info" style="width:5%;text-align:center">
            								<?php 
            									if ($approver['BStatus'][0]['status']=='rejected') 
            										echo '<i class="fa fa-check"></i>' ;
            								?>
            							</th>
            							<th class="info" style="width:17%">Rejected</th>
            							<th class="info" style="width:31%">Other remark(s)</th>
            						</tr>
            					</thead>
            					<tbody>
            						<tr style="text-align:justify">
            							<td class='text-center' style="width:3%">
            								<?php
            									echo $counter+1;
            								?>
            							</td>
            							<td>
				            				<p>
				            					<?php
													echo "<span class='bigger-text'>".$approver['User']['staff_name'] ."</span>";
													echo "<br>";
													echo "<small>";
													echo $approver['User']['Department']['department_name'];
													echo "<br>";
													echo $approver['User']['designation'];
													echo "<br>";
													echo date('d F Y',strtotime($approver['BStatus'][0]['modified'])); 
													echo "</small>";
												?>
				            				</p>
            							</td>
            							<td colspan="2">
            								<!-- <h5><b>Remark(s) : </b></h5> -->
            								<p style="text-align:justify">
            									<?php 
            										if (!empty($approver['BStatus'][0]['remark']) && $approver['BStatus'][0]['status']=='approved') 
            											echo '<h5><b>Remark(s) : </b></h5>'. nl2br($approver['BStatus'][0]['remark']);
            										?>
            									</p>
            								</td>
            								<td colspan="2">
            									<!-- <h5><b>Remark(s) : </b></h5> -->
            									<p style="text-align:justify">
            										<?php 
            											if (!empty($approver['BStatus'][0]['remark']) && $approver['BStatus'][0]['status']=='rejected') 
            												echo '<h5><b>Remark(s) : </b></h5>'.nl2br($approver['BStatus'][0]['remark']); 
            										?>
            										</p>
            									</td>
            									<td>
            										<?php 
            											if (!empty($remark_reviewer[$counter])):
            												foreach ($remark_reviewer[$counter] as $val):
            										?>
            											<h5><b>Remarks : 
                										<?php 
                											if (!empty($val['BRemark']['subject'])) 
                												echo $val['BRemark']['subject']; 
                										?>
                										</b></h5>
                										<p>
                											<b>To</b> : 
                											<?php 
                												if (!empty($val['BRemarkAssign'])):
		                											foreach ($val['BRemarkAssign'] as $v) {
		                												if ($val['User']['staff_name']!=$v['User']['staff_name'])
		                													echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
		                											}
                												endif;
                											?>
                										</p>
                										<p style="text-align:justify">
                											<?php 
                												if (!empty($val['BRemarkFeedback'][0]['feedback'])) 
                													echo $val['BRemarkFeedback'][0]['feedback']; ?>
                										</p> 


            											<table class="table">
                											<?php 
                												if (count($val['BRemarkFeedback'])>1):
                													for ($i=1;$i<count($val['BRemarkFeedback']);$i++):
                											?>
                												<tr style="text-align:justify">
                													<td>
                														<h5><b>Feedback from 
                														<?php 
                															echo $val['BRemarkFeedback'][$i]['User']['staff_name'];
                															echo " ( "; 
                															echo date('d M Y',strtotime($val['BRemarkFeedback'][$i]['created']));
                															echo " ) ";
                														?>
                														:</b></h5>
                														<p>
                															<?php 
                																if (!empty($val['BRemarkFeedback'][$i]['feedback'])) echo $val['BRemarkFeedback'][$i]['feedback']; 
                															?>
                														</p>
                													</td>
                												</tr>
                											<?php 
                													endfor;
                												endif; 
                											?>
            											</table>

            											<?php 
            													endforeach;
            											 	endif; 
            											?>
            										</td>
            									</tr>
            								</tbody>
            							</table>
                <?php 
            			$counter++;
            			endforeach;
                	endif; 
                ?>
            </div>
		</section>
	</div>
</section>