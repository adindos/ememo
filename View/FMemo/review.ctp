<?php
	$this->Html->addCrumb('Financial Memo', array('controller' => 'fMemo', 'action' => 'index')); 
	if(empty($memo['FMemo']['ref_no']))
		$this->Html->addCrumb(' Temporary Ref.No : 000/'.$memo['FMemo']['memo_id'], array('controller'=>'fMemo','action'=>'dashboard',$memo_id));
	else
		$this->Html->addCrumb( ' Ref.No : '.$memo['FMemo']['ref_no'], array('controller'=>'fMemo','action'=>'dashboard',$memo_id));

	$this->Html->addCrumb( 'Review Financial Memo', $this->here,array('class'=>'active'));
?>

<script type="text/javascript">
$(document).ready(function () {

	// js to auto select radio
	$('.approval-btn-clicked').click(function(){
    	$('#approval-approve').prop("checked",false);
    	$('#approval-reject').prop("checked",false);
    	var status = $(this).data('action');
    	if(status == 'approve'){
    		$('#FStatusStatusApproved').prop('checked',true);
    		
    	}
    	else{
    		$('#FStatusStatusRejected').prop('checked',true);
    	}
    	// alert(status);
    	// if(status)
    });

   $('#FStatusStatusApproved').click(function(){
   		return false;
   });

   $('#FStatusStatusRejected').click(function(){
   		return false;
   });
});
</script>

<section class="mems-content">
	<div class="row">
		<!-- Navigation -->
		<?php if (!empty($approverReject)): ?>
		<div class="alert alert-block alert-info fade in">
		    <!-- <button data-dismiss="alert" class="close close-sm" type="button">
		        <i class="fa fa-times"></i>
		    </button> -->
		    <b> <?php 
                   echo $this->Html->link('Click here to see previous approval/rejection.',array('controller'=>'FMemo','action'=>'prevSubmission',$memo_id,$approverReject['FStatus']['submission_no']),array('escape'=>false,'target'=>'_blank'));

                  ?> </b>
		</div>

		<?php endif; ?>
		<div class="col-sm-12">
			<section class="panel">
				<header class="panel-heading">
					<h4>Financial Memo
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
		                			echo $this->Html->link("<button class='btn btn-default btn-sm tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Edit Memo'><i class='fa fa-pencil'></i> Edit</button>",array('controller'=>'FMemo','action'=>'request',$memo_id,'edit'),array('escape'=>false,'class'=>'small-margin-left')); 

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
		                			echo $this->Html->link("<button class='btn btn-warning btn-sm tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Remark'><i class='fa fa-comment'></i> Remarks</button>",array('controller'=>'Remark','action'=>'index',$memo_id,'financial'),array('escape'=>false,'class'=>'small-margin-left')); 
		                	
		                		}
		                		if ($commentFlag){ 
		                			echo $this->Html->link("<button class='btn bg-primary btn-sm tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Comment'><i class='fa fa-comment'></i> Comments</button>",array('controller'=>'Comment','action'=>'index',$memo_id,'financial'),array('escape'=>false,'class'=>'small-margin-left')); 
		                	
		                			
		                		}

		                	?>
		                	
		                	<?php echo $this->Html->link("<button class='btn btn-primary btn-sm tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Download PDF'><i class='fa fa-print'></i> PDF</button>",array('controller'=>'FMemo','action'=>'pdf',$memo_id.'.pdf',),array('escape'=>false,'class'=>'small-margin-left')); ?>
		                </div>
		                <br/><br/>
	                  </div>
	                </div>
					<div class="row">
					  <br/>
		              <div class="col-lg-12"> 
		              	<table class="table table-bordered table-striped table-condensed">
		              		<tbody>
		              			<tr><th>Reference no.</th><td><b>(<?php if (!empty($memo['FMemo']['ref_no'])) echo $memo['FMemo']['ref_no']; ?>)</b></td></tr>
		              			<tr><th>To</th><td><b>
		              				<?php if (!empty($memo['FMemoTo'])){ 
			              					$temp=array();
											foreach ($memo['FMemoTo'] as $to) {

												$temp[]=$to['User']['staff_name'].' ('.$to['User']['designation'].')';
											}
											$tos=implode(', ',$temp); 
											echo $tos;
										  }
									?>
								</b></td></tr>
		              			<tr><th>From</th><td><b><?php if (!empty($memo['FMemo']['department_id'])) echo $memo['User']['staff_name'].' ('.$memo['User']['designation'].', '.$memo['User']['Department']['department_name'].')'; ?></b></td></tr>
		              			<tr><th>Subject</th><td><b><?php if (!empty($memo['FMemo']['subject']))echo $memo['FMemo']['subject']; ?></b></td></tr>
		              		</tbody>
		              	</table>
		              </div>
		            </div>
		           <div class="row">
		           	   <br/>
			           <div class="col-md-2">
			           	  <b>Prerequisites :</b>
		                </div>
		                <div class="col-md-2">
			           	  <div class="checkboxes" >
		                      <label class="label_check" for="checkbox-01">
		                          <input type="checkbox" id="checkbox-01" checked> Financial
		                      </label>
		                      <label class="label_check" for="checkbox-02">
		                          <input type="checkbox" id="checkbox-02" > Non-Financial
		                      </label>
		                  </div>
		                </div>
		                <div class="col-md-2">
			           	  <div class="checkboxes">
		                      <label class="label_check" for="checkbox-03">
		                          <?php if ($memo['FMemo']['budgeted']=='1') $flag='checked'; else $flag='';?>
		                          <input type="checkbox" id="checkbox-03" <?php echo $flag; ?> > Budgeted
		                      </label>
		                      <label class="label_check" for="checkbox-04">
		                          <?php if ($memo['FMemo']['budgeted']=='0') $flag='checked'; else $flag='';?>
		                          <input type="checkbox" id="checkbox-04" <?php echo $flag; ?> > Unbudgeted
		                      </label>
		                  </div>
		                </div>
		                <div class="col-md-2">
		                	<div class="checkboxes">
		                      <label class="label_check" for="checkbox-05">
		                          <?php if ($memo['FMemo']['vendor']=='1') $flag='checked'; else $flag='';?>
		                          <input type="checkbox" id="checkbox-05" <?php echo $flag; ?> > Approved vendor
		                      </label>
		                      <label class="label_check" for="checkbox-06">
		                          <?php if ($memo['FMemo']['vendor']=='0') $flag='checked'; else $flag='';?>
		                          <input type="checkbox" id="checkbox-06" <?php echo $flag; ?> > New Vendor
		                      </label>
		                  	</div>
		                </div>
		                <div class="col-md-3 pull-right">
		                
			           	  <b>Date required :</b> <?php if (!empty($memo['FMemo']['date_required'])) echo date('d M Y',strtotime($memo['FMemo']['date_required'])); ?> 
		                </div>
		           </div>
                </div>
			</section>
			<?php if (!empty($memo['FVendorAttachment'])): ?>
			<section class="panel">
				<header class="panel-heading">
					<h4>Vendor Quotation(s) / Related File(s)
		                <span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
		              <div class="col-lg-12"> 
		              	<table class="table">
                              <thead>
                              	<th colspan="2" >File</th>
                              	<th>Action</th>
                              </thead>
                              <tbody>
                              	<?php foreach ($memo['FVendorAttachment'] as $value) {?>
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
                                    <td style="width:15%"> 
                                    	<div class="btn-group">

			                              <?php 
			                               echo $this->Html->link('<i class="fa fa-cloud-download"></i>',array('controller'=>'FMemo','action'=>'downloadAttachment',$this->Mems->encrypt($value['attachment_id'])),array('escape'=>false,'class'=>"btn btn-xs btn-primary btn-xs tooltips data-toggle='tooltip' data-placement='top' data-original-title='Download'"));

			                              ?>
                            </div>
                                    </td>
                                </tr>
                                <?php } ?>
                              </tbody>
                        </table>
	                  </div>
	                </div>
                </div>
			</section>
            <?php endif;?>

            <?php if (!empty($memo['FMemo']['vendor_remark'])): ?>
				<section class="panel">
					<header class="panel-heading">
						<h4>Vendor Remark
			                <span class="tools pull-right">
			                    <a href="javascript:;" class="fa fa-chevron-down"></a>
			                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
			                </span>
			            </h4>
					</header>
					
					<div class="panel-body">
						<div class="row">
			              <div class="col-lg-12"> 
							<?php if (!empty($memo['FMemo']['vendor_remark']))echo ($memo['FMemo']['vendor_remark']); ?>
		                  </div>
		                </div>
	                </div>
				</section>
            <?php endif;?>
            
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
						<?php if (!empty($memo['FMemo']['introduction']))echo ($memo['FMemo']['introduction']); ?>
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
						<?php if (!empty($memo['FMemo']['subject_matters']))echo ($memo['FMemo']['subject_matters']); ?>
	                  </div>
	                </div>
	                

                </div>
			</section>

			<section class="panel">
				<header class="panel-heading">
					<h4> Budget Summary
		                <span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<?php if (!empty($memo['FMemoBudget'])){?>
	                <div class="row">
		              <div class="col-lg-12"> 
		              	<table class="table table-bordered table-condensed" style="width: 100%;">

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
			                                    	echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$value['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
                                          			echo "&nbsp;&nbsp;";

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
	                  </div>
	                </div>
					<?php } ?>
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
						<?php if (!empty($memo['FMemo']['recommendation'])) echo ($memo['FMemo']['recommendation']); ?>
	                  </div>
	                </div>
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading">
					<h4>
						4. Division/Department's Review/Recommendation
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
		                 	<?php if (!empty($memo['FMemo']['user_id'])) echo $memo['User']['staff_name']; ?><br/>
		                 	<?php if (!empty($memo['FMemo']['department_id'])) echo $memo['User']['Department']['department_name']; ?><br/>
		                 	<?php if (!empty($memo['User']['designation'])) echo $memo['User']['designation']; ?><br/>
		                 	<?php if (!empty($memo['FMemo']['created'])) echo date('d M Y',strtotime($memo['FMemo']['created'])); ?><br/>
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
		                 				<?php if (!empty($memo['FMemo']['remark'])) echo ($memo['FMemo']['remark']); ?><br/>
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
						5. Finance Approval
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
						6. COO/CFO and/or CEO Approval (Guided by Corporate LOA)
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

					                 <h5><b><?php echo $counter+1; ?>) Approved by:</b></h5>
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
              	<div class="modal-header blue-bg">
                  	<button aria-hidden="true" data-dismiss="modal" class="close" type="button">??</button>
                  	<h4 class="modal-title">Approve / Reject Financial Memo Request</h4>
              	</div>
              	<div class="modal-body">
              		<?php
              			echo $this->Form->create('FStatus',array('url'=>array('controller'=>'FMemo','action'=>'approveRejectMemo'),'class'=>'form-horizontal','onSubmit'=>'return confirm("Are you sure you want to approve/reject this memo request?")','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
              			echo $this->Form->hidden('FStatus.memo_id',array('value'=>$memo_id));
              		?>
                  	<div class="form-group">
                      	<label class="col-lg-2 col-sm-2 control-label">Decision</label>
                      	<div class="col-lg-10">
                          	<div class="radios">

                              	<?php
                              		$options = array("approved" => '  Approve', "rejected" => '  Reject');
									echo $this->Form->input('FStatus.status',array('type'=>'radio','options'=>$options,'separator'=>'<br>'));
                              	?>
                          	</div>
                     	</div>
                 	</div>
                 	<?php if ($financeFlag) {?>
                 	<div class="form-group">
                      	<label class="col-lg-2 col-sm-2 control-label">Budgeted</label>
                      	<div class="col-lg-10">
                          	<div class="radios">

                              	<?php
                              		$options = array(1 => '  Budgeted', 0 => '  Unbudgeted');
									echo $this->Form->input('FMemo.budgeted',array('type'=>'radio','options'=>$options,'separator'=>'<br>'));
                              	?>
                          	</div>
                     	</div>
                 	</div>
                 	<?php }?>
                      
                  	<div class="form-group">
                  		<label class="col-lg-2 col-sm-2 control-label">Remark</label>
                    	<div class="col-lg-10">
                          	<?php
                          		echo $this->Form->input('FStatus.remark',array('type'=>'textarea','class'=>'wysihtml5 form-control','rows'=>'10'));
                          	?>
                     	</div>
                  	</div>

                  	<div class="modal-footer text-center">
						<?php
							echo $this->Form->button('Submit',array('type'=>'submit','class'=>'btn btn-success'));
						?>
						<button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
						
					</div>
                   
             		<?php
             			echo $this->Form->end();
             		?>
              	</div>
          	</div>
      	</div>
    </div>
</section>