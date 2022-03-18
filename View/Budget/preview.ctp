<?php
  $encBudgetID = $this->Mems->encrypt($budgetID);

  $this->Html->addCrumb('Budget', array('controller' => 'budget', 'action' => 'index'));
  $this->Html->addCrumb($budgetDetail['Budget']['year'], array('controller' => 'budget', 'action' => 'dashboard',$encBudgetID));
  $this->Html->addCrumb('Budget Preview', $this->here,array('class'=>'active'));
?>
<section class="mems-content">
<!-- Modal -->
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Edit Amount</h4>
			</div>
			<?php
				echo $this->Form->create('Budget',array('url'=>array('controller'=>'budget','action'=>'editBudgetItem'),
										'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
										'class'=>'form-horizontal'));
			?>
			<div class="modal-body ">
				<div class="form-group">
					<label class="col-lg-3 col-sm-3 control-label"> Amount</label>
					<div class="col-sm-9">
						<div class="input-group">
						<span class="input-group-addon">RM</span>
                      	<?php
                      		
                      		echo $this->Form->input('BItemAmount.amount',array('type'=>'number','class'=>'form-control','id'=>'amount'));
                      		echo "</div>";
                      		echo $this->Form->input('BItemAmount.item_amount_id',array('type'=>'hidden','id'=>'item_amount_id'));

                      	?>
                      	<small><i class="fa fa-exclamation-circle"></i> Enter the new amount in RM</small>
                      	
                  	</div>
				</div>
				
	        </div>
				
			<div class="modal-footer text-center">
				<button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
				<?php
					echo $this->Form->button('Update Amount',array('type'=>'submit','class'=>'btn btn-success'));
				?>
			</div>

			<?php
				echo $this->Form->end();
			?>
		</div>
	</div>
</div>
  
	<div class="row">
	    <div class="col-lg-12">
	        <section class="panel">
	            <header class="panel-heading">
	                <?php echo $budgetDetail['Company']['company'] ." (".$budgetDetail['Budget']['year'].")"?>
	                <span class="tools pull-right">
	                    <a class="fa fa-chevron-down" href="javascript:;"></a>
	                </span>
	            </header>

	            <div class="panel-body" style="width:100%">
	            	<div class="col-lg-12 text-center">
		              <?php 
		              //allow re-edit page for all except for budget that has been used
		              	if (empty($bMemoExist)){
		               		 echo $this->Form->postLink("<i class='fa fa-arrow-circle-left'></i> Back to Edit ",array('controller'=>'budget','action'=>'request',$encBudgetID),array('type'=>'submit','escape'=>false,'class'=>'btn btn-primary'));
               			 	echo '&nbsp;&nbsp;&nbsp;';
               			}
               			 if (($budgetDetail['Budget']['submission_no']==0)&&($activeUser['user_id']==$budgetDetail['Budget']['user_id']))//meaning havent submitted,requestor editing 
		                 	echo $this->Html->link("<i class='fa fa-arrow-circle-right'></i> Proceed Next ",array('controller'=>'budget','action'=>'confirm',$encBudgetID,'new'),array('escape'=>false,'class'=>'btn btn-success'));
		                else//have submitted/requestor/reviewer edit-->check in confirm only resubmit if current submission status is rejected
		                  echo $this->Html->link("<i class='fa fa-arrow-circle-right'></i> Update/Resubmit ",array('controller'=>'budget','action'=>'confirm',$encBudgetID),array('escape'=>false,'class'=>'btn btn-success'));

		                 // //unbudgeted item
		                 //  // $this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));
               			 // 	echo '&nbsp;&nbsp;&nbsp;';

		                 // echo $this->Html->link("<i class='fa fa-plus'></i> Add Unbudgeted ",'#modal-unbudgeted',array('escape'=>false,'class'=>'btn btn-default','data-original-title'=>'Add Unbudgeted','data-toggle'=>'modal'));
		                
		              ?>
		            </div>
		            <br><br>
	            	<div style="margin:5px 20px;padding:10px">
		            	<div class="pull-left">
		            		<?php 
		            			echo "<small>".nl2br($budgetDetail['Budget']['description'])."</small> ";
		            			echo '<small><strong>01/01/'.$budgetDetail['Budget']['year'].' to '.'31/12/'.$budgetDetail['Budget']['year']."</strong></small>";
		            		?> 
		            	</div>
		            	
		            </div>
	            	<div class="clear"></div>
	            	<div  class='scrollable-area' style="height: 100%; padding : 10px 20px;width: 100% ;">
		                <table class="table tableB table-bordered table-striped" style="width: 100%;">
		                    <thead>
		                        <tr class="">
		                        	<th colspan='4' rowspan='2' class="text-center dark-grey-bg" style="vertical-align:middle" >Item</th>
		                            <th class="text-center dark-grey-bg" style="white-space:nowrap">Budget Year to Date <?php echo $budgetDetail['Budget']['year'];?> </th>

		                            <?php
		                            	$countColumn = 8+count($deptAcad)+count($deptNonAcad); // including the colspan and subtotal
		                            	echo "<th colspan='".(count($deptAcad)+1)."' style='vertical-align:middle' class='text-center unitar-blue-bg' style='white-space:nowrap'>ACADEMIC</th>";
		                            	echo "<th colspan='".(count($deptNonAcad)+1)."' style='vertical-align:middle' class='text-center unitar-blue-bg' style='white-space:nowrap'>NON-ACADEMIC</th>";
		                          
		                        	?>
		                        	<th rowspan="2" class="col-lg-1 text-center dark-grey-bg" style="vertical-align:middle">GRAND TOTAL</th>
		                        </tr>
		                        <tr class="">
		                        	
		                        	<th class="text-center dark-grey-bg" style="width:auto;white-space:nowrap;vertical-align:middle"> RM </th>
		                        	<?php
		                        		$deptLists=array();
	                        			foreach($deptAcad as $key=>$department):
	                        				echo "<th style='vertical-align:middle' class='text-center unitar-grey-bg' >".$department."</th>";
		                        			array_push($deptLists,$key);

	                        			endforeach;

	                        			echo "<th style='vertical-align:middle' class='text-center dark-grey-bg' >TOTAL ACADEMIC</th>";
	                        			foreach($deptNonAcad as $key=>$department):
	                        				
	                        				echo "<th style='vertical-align:middle' class='text-center unitar-grey-bg' >".$department."</th>";
		                        			array_push($deptLists,$key);

	                        			endforeach;
	                        			echo "<th style='vertical-align:middle' class='text-center dark-grey-bg' >TOTAL NON-ACADEMIC</th>";
		                        	?>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	<!-- 1. revenue -->
		                    	<tr class='info'>
		                    		<th colspan='<?php echo $countColumn ?>'>REVENUE</th>
		                    	</tr>
		                    	<?php
	                    			
	                    			foreach( $budgetR as $b ):

	                    				$item=null;
                    					// create row of item
	                    				for( $i=0; $i < count($b['BItemAmount']); ):
	                    					$bi=$b['BItemAmount'];
	                    					echo "<tr>";
	                    					if(empty($bi[$i]['b_dept_id'])):	///indicates new row,ytd
	                    						echo "<th class='' colspan=4 style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
	                    						
	                    						//edit button
	                    						$iamt=$bi[$i]['amount'];
	                    						$iid=$bi[$i]['item_amount_id'];
	                    						
	                    						echo  "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".$this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));

	                    						echo "&nbsp;&nbsp;".number_format($bi[$i]['amount'],2,".",",")."</td>";
	                    						$item=$bi[$i]['item_id'];//set item for the whole row
	                    						
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
	                    						
	                    						//reset total each row
	                    						$totalByItem = 0; //init subtotal for each item
			                    				$totalByAcad = 0;
			                    				$totalByNonAcad = 0;
			                    				$i++;
	                    					
	                    					endif;
	                    						//iterate thru acad dept first n set the val
	                    						
	                    						foreach($deptAcad as $key => $dept):
			                    					$editFlag=true;
			                    					$infoFlag=false;

	                    							if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$key]))
	                    								$totalByDept[$b['BItemGroup']['item_group_id']][$key] = 0;
	                    							//initiate total unbudgeted
		                    						if(!isset($totalUnbudgeted[$key]))
                										$totalUnbudgeted[$key] = 0;
	                    							//check if dept same and item same only set val
	                    							if(isset($bi[$i])):
		                    							if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
			                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";	
		                    							}
			                    						else{	
			                    							
			                    							//deduct approved fmemobudget from balance
				                    						if(!empty($bi[$i]['FMemoBudget'])):
				                    							foreach($bi[$i]['FMemoBudget'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;
							                    						//1.deduct approved amount from balance
		                                                                $bi[$i]['amount']-=$mb['amount'];
		                                                                //2.add the unbudgeted/transferred amount to the current item amount, if exist
		                                                                if (!empty($mb['transferred_item_amount_id'])):
		                                                                    //
		                                                                    $bi[$i]['amount']+=$mb['transferred_amount'];
		                                                                    
		                                                                endif;
		                                                                $bi[$i]['amount']+=$mb['unbudgeted_amount'];
		                                                                    // add to accumulated unbudgeted
		                                                                $totalUnbudgeted[$deptid] +=$mb['unbudgeted_amount'];
					                    								
					                    							endif;
				                    							endforeach;
				                    						endif;

				                    						//deduct approved fmemobudgettransfer from balance
				                    						if(!empty($bi[$i]['FMemoBudgetTransfer'])):
				                    							foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;

					                    								//1.deduct approved transfer amount from balance
					                    								$bi[$i]['amount']-=$mb['transferred_amount'];
					                    								
					                    							
					                    							endif;
				                    							endforeach;
				                    						endif;
				                    						
				                    						
			                    							$totalByItem += $bi[$i]['amount']; //total by item
			                    							$totalByAcad += $bi[$i]['amount']; //total by item for acad
			                    							
			                    							$totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
			                    							
			                    							echo  "<td class='text-center' style='white-space:nowrap;'>";
			                    							
			                    							//info button
			                    							if ($infoFlag):
			                    								echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
			                    								
				                    							echo "&nbsp;&nbsp;";

			                    							endif;

			                    							//edit button
			                    							if ($editFlag):
					                    						$iamt=$bi[$i]['amount'];
					                    						$iid=$bi[$i]['item_amount_id'];
					                    						echo  $this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));
				                    						
				                    							echo "&nbsp;&nbsp;";

				                    						endif;

				                    						echo number_format($bi[$i]['amount'],2,".",",")."</td>";
			                    							$i++; //update the index to next element
			                    							if ($bi[$i]['BDepartment']['department_type']==2)
			                    								break;
			                    						}
		                    						else:
		                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
		                    						endif;

	                    						endforeach;
	                    						//initiate totAcad
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totAcad']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'] +=$totalByAcad;

	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByAcad,2,".",",")."</td>";
	                    						//iterate thru acad dept first n set the val
	                    						
	                    						foreach($deptNonAcad as $key => $dept):
	                    							$editFlag=true;
			                    					$infoFlag=false;

	                    							if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$key]))
	                    								$totalByDept[$b['BItemGroup']['item_group_id']][$key] = 0;
	                    							//initiate total unbudgeted
		                    						if(!isset($totalUnbudgeted[$key]))
                										$totalUnbudgeted[$key] = 0;
	                    							//check if dept same and item same only set val
	                    							if(isset($bi[$i])):
		                    							if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
			                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";	
		                    							}
			                    						else{	
			                    							
			                    							//deduct approved fmemobudget from balance
				                    						if(!empty($bi[$i]['FMemoBudget'])):
				                    							foreach($bi[$i]['FMemoBudget'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;
							                    						//1.deduct approved amount from balance
		                                                                $bi[$i]['amount']-=$mb['amount'];
		                                                                //2.add the unbudgeted/transferred amount to the current item amount, if exist
		                                                                if (!empty($mb['transferred_item_amount_id'])):
		                                                                    //
		                                                                    $bi[$i]['amount']+=$mb['transferred_amount'];
		                                                                    
		                                                                endif;
		                                                                $bi[$i]['amount']+=$mb['unbudgeted_amount'];
		                                                                    // add to accumulated unbudgeted
		                                                                $totalUnbudgeted[$deptid] +=$mb['unbudgeted_amount'];
					                    								
					                    							endif;
				                    							endforeach;
				                    						endif;

				                    						//deduct approved fmemobudgettransfer from balance
				                    						if(!empty($bi[$i]['FMemoBudgetTransfer'])):
				                    							foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;

					                    								//1.deduct approved transfer amount from balance
					                    								$bi[$i]['amount']-=$mb['transferred_amount'];
					                    								
					                    							
					                    							endif;
				                    							endforeach;
				                    						endif;
				                    						
				                    						
			                    							$totalByItem += $bi[$i]['amount']; //total by item
			                    							$totalByNonAcad += $bi[$i]['amount']; //total by item for acad
			                    							
			                    							$totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
			                    							
			                    							echo  "<td class='text-center' style='white-space:nowrap;'>";
			                    							
			                    							//info button
			                    							if ($infoFlag):
			                    								echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
			                    								
				                    							echo "&nbsp;&nbsp;";

			                    							endif;

			                    							//edit button
			                    							if ($editFlag):
					                    						$iamt=$bi[$i]['amount'];
					                    						$iid=$bi[$i]['item_amount_id'];
					                    						echo  $this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));
				                    						
				                    							echo "&nbsp;&nbsp;";

				                    						endif;

				                    						echo number_format($bi[$i]['amount'],2,".",",")."</td>";
			                    							$i++; //update the index to next element
			                    							
			                    						}
		                    						else:
		                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
		                    						endif;
	                    							
	                    						endforeach;
	                    						
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'] +=$totalByNonAcad;

	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totGrand']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'] +=$totalByItem;
	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByNonAcad,2,".",",")."</td>";
	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByItem,2,".",",")."</td>";

	                    					echo "</tr>";
	                    					
	                    				endfor;//end foritem 

	                    				?>
	                    				<!-- at the end of each group display the accumulated val for each col -->
	                    				<tr class="success">
				                			<td class="bold text-right" colspan="4"> Sub Total </td>
				                			<td class='bold text-center bigger-text'><?php echo number_format($totalByDept[$b['BItemGroup']['item_group_id']]['ytd'],2,".",",")?> </td>

			                			<?php
			                				//calculate net sales for ytd
			                				if (!isset($totAllGroup['revenue']['ytd']))
			                					$totAllGroup['revenue']['ytd']=0;
			                				$totAllGroup['revenue']['ytd']+=$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'];
			                				//iterate thru acad dept first n set the total val
                    						foreach($deptAcad as $key => $dept):
                    							//calculate net sales for each acad dept
				                				if (!isset($totAllGroup['revenue'][$key]))
				                					$totAllGroup['revenue'][$key]=0;
				                				$totAllGroup['revenue'][$key]+=$totalByDept[$b['BItemGroup']['item_group_id']][$key];	
                    							echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$key],2,".",",")."</td>";

                    						endforeach;
                    						//calculate net sales for totacad
				                				if (!isset($totAllGroup['revenue']['totAcad']))
				                					$totAllGroup['revenue']['totAcad']=0;
				                				$totAllGroup['revenue']['totAcad']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'],2,".",",")."</td>";
                    						//iterate thru acad dept first n set the val
                    						
                    						foreach($deptNonAcad as $key => $dept):
                    							
												//calculate net sales for each acad dept
				                				if (!isset($totAllGroup['revenue'][$key]))
				                					$totAllGroup['revenue'][$key]=0;
				                				$totAllGroup['revenue'][$key]+=$totalByDept[$b['BItemGroup']['item_group_id']][$key];	
                    							echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$key],2,".",",")."</td>";

                    						endforeach;
                    						//calculate net sales for totacad
				                				if (!isset($totAllGroup['revenue']['totNonAcad']))
				                					$totAllGroup['revenue']['totNonAcad']=0;
				                				$totAllGroup['revenue']['totNonAcad']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'],2,".",",")."</td>";
                    						//calculate net sales for totacad
				                				if (!isset($totAllGroup['revenue']['totGrand']))
				                					$totAllGroup['revenue']['totGrand']=0;
				                				$totAllGroup['revenue']['totGrand']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'],2,".",",")."</td>";

                    						

			                			?>
				                		</tr>

		                    				
	                    		<?php
	                    			endforeach;//end foreach group
		                    		//show total of groups if only groups exist
	                    			if (!empty($budgetR)):
		                    	?>

		                    	<tr class='danger'>
		                    		<td class="bold text-right" colspan="4"> Total Revenue </td>
				                			<!-- <td class="bold text-right" colspan="4"> Sub Total </td> -->
				                	<td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['revenue']['ytd'],2,".",",")?> </td>

		                			<?php
		                				
		                				//iterate thru acad dept first n set the total val
                						foreach($deptAcad as $key => $dept):
                							
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue'][$key],2,".",",")."</td>";

                						endforeach;
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue']['totAcad'],2,".",",")."</td>";
                						//iterate thru acad dept first n set the val
                						
                						foreach($deptNonAcad as $key => $dept):
                						
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue'][$key],2,".",",")."</td>";

                						endforeach;
                						
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue']['totNonAcad'],2,".",",")."</td>";
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue']['totGrand'],2,".",",")."</td>";

		                			?>
				                		
		                    	
		                    	</tr>
		                    	<tr class='info'>
		                    		<th colspan='4'>NET SALES</th>
				                	<td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['revenue']['ytd'],2,".",",")?> </td>

		                			<?php
		                				
		                				//iterate thru acad dept first n set the total val
                						foreach($deptAcad as $key => $dept):
                							
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue'][$key],2,".",",")."</td>";

                						endforeach;
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue']['totAcad'],2,".",",")."</td>";
                						//iterate thru acad dept first n set the val
                						
                						foreach($deptNonAcad as $key => $dept):
                						
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue'][$key],2,".",",")."</td>";

                						endforeach;
                						
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue']['totNonAcad'],2,".",",")."</td>";
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue']['totGrand'],2,".",",")."</td>";

		                			?>
				                		
		                    	</tr>
		                    		<?php endif;//endof if rev exist?>

		                    	<tr class='info'>
		                    		<th colspan='<?php echo $countColumn ?>'>COST OF REVENUE</th>
		                    	</tr>
		                    		<?php
	                    			
	                    			foreach( $budgetCOR as $b ):

	                    				$item=null;
                    					// create row of item
	                    				for( $i=0; $i < count($b['BItemAmount']); ):
	                    					$bi=$b['BItemAmount'];
	                    					echo "<tr>";
	                    					if(empty($bi[$i]['b_dept_id'])):	///indicates new row,ytd
	                    						echo "<th class='' colspan=4 style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
	                    						//edit button
	                    						$iamt=$bi[$i]['amount'];
	                    						$iid=$bi[$i]['item_amount_id'];
	                    						echo  "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".$this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));

	                    						echo "&nbsp;&nbsp;".number_format($bi[$i]['amount'],2,".",",")."</td>";
	                    						$item=$bi[$i]['item_id'];//set item for the whole row
	                    						
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
	                    						
	                    						//reset total each row
	                    						$totalByItem = 0; //init subtotal for each item
			                    				$totalByAcad = 0;
			                    				$totalByNonAcad = 0;
			                    				$i++;
	                    					
	                    					endif;
	                    						//iterate thru acad dept first n set the val
	                    						
	                    						foreach($deptAcad as $key => $dept):
			                    					$editFlag=true;
			                    					$infoFlag=false;

	                    							if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$key]))
	                    								$totalByDept[$b['BItemGroup']['item_group_id']][$key] = 0;
	                    							//initiate total unbudgeted
		                    						if(!isset($totalUnbudgeted[$key]))
                										$totalUnbudgeted[$key] = 0;
	                    							//check if dept same and item same only set val
	                    							if(isset($bi[$i])):
		                    							if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
			                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";	
		                    							}
			                    						else{	
			                    							
			                    							//deduct approved fmemobudget from balance
				                    						if(!empty($bi[$i]['FMemoBudget'])):
				                    							foreach($bi[$i]['FMemoBudget'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;
							                    						//1.deduct approved amount from balance
		                                                                $bi[$i]['amount']-=$mb['amount'];
		                                                                //2.add the unbudgeted/transferred amount to the current item amount, if exist
		                                                                if (!empty($mb['transferred_item_amount_id'])):
		                                                                    //
		                                                                    $bi[$i]['amount']+=$mb['transferred_amount'];
		                                                                    
		                                                                endif;
		                                                                $bi[$i]['amount']+=$mb['unbudgeted_amount'];
		                                                                    // add to accumulated unbudgeted
		                                                                $totalUnbudgeted[$deptid] +=$mb['unbudgeted_amount'];
					                    								
					                    							endif;
				                    							endforeach;
				                    						endif;

				                    						//deduct approved fmemobudgettransfer from balance
				                    						if(!empty($bi[$i]['FMemoBudgetTransfer'])):
				                    							foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;

					                    								//1.deduct approved transfer amount from balance
					                    								$bi[$i]['amount']-=$mb['transferred_amount'];
					                    								
					                    							
					                    							endif;
				                    							endforeach;
				                    						endif;
				                    						
				                    						
			                    							$totalByItem += $bi[$i]['amount']; //total by item
			                    							$totalByAcad += $bi[$i]['amount']; //total by item for acad
			                    							
			                    							$totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
			                    							
			                    							echo  "<td class='text-center' style='white-space:nowrap;'>";
			                    							
			                    							//info button
			                    							if ($infoFlag):
			                    								echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
			                    								
				                    							echo "&nbsp;&nbsp;";

			                    							endif;

			                    							//edit button
			                    							if ($editFlag):
					                    						$iamt=$bi[$i]['amount'];
					                    						$iid=$bi[$i]['item_amount_id'];
					                    						echo  $this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));
				                    						
				                    							echo "&nbsp;&nbsp;";

				                    						endif;

				                    						echo number_format($bi[$i]['amount'],2,".",",")."</td>";
			                    							$i++; //update the index to next element
			                    							if ($bi[$i]['BDepartment']['department_type']==2)
			                    								break;
			                    						}
		                    						else:
		                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
		                    						endif;

	                    						endforeach;
	                    						//initiate totAcad
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totAcad']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'] +=$totalByAcad;

	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByAcad,2,".",",")."</td>";
	                    						//iterate thru acad dept first n set the val
	                    						
	                    						foreach($deptNonAcad as $key => $dept):
	                    							$editFlag=true;
			                    					$infoFlag=false;

	                    							if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$key]))
	                    								$totalByDept[$b['BItemGroup']['item_group_id']][$key] = 0;
	                    							//initiate total unbudgeted
		                    						if(!isset($totalUnbudgeted[$key]))
                										$totalUnbudgeted[$key] = 0;
	                    							//check if dept same and item same only set val
	                    							if(isset($bi[$i])):
		                    							if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
			                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";	
		                    							}
			                    						else{	
			                    							
			                    							//deduct approved fmemobudget from balance
				                    						if(!empty($bi[$i]['FMemoBudget'])):
				                    							foreach($bi[$i]['FMemoBudget'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;
							                    						//1.deduct approved amount from balance
		                                                                $bi[$i]['amount']-=$mb['amount'];
		                                                                //2.add the unbudgeted/transferred amount to the current item amount, if exist
		                                                                if (!empty($mb['transferred_item_amount_id'])):
		                                                                    //
		                                                                    $bi[$i]['amount']+=$mb['transferred_amount'];
		                                                                    
		                                                                endif;
		                                                                $bi[$i]['amount']+=$mb['unbudgeted_amount'];
		                                                                    // add to accumulated unbudgeted
		                                                                $totalUnbudgeted[$deptid] +=$mb['unbudgeted_amount'];
					                    								
					                    							endif;
				                    							endforeach;
				                    						endif;

				                    						//deduct approved fmemobudgettransfer from balance
				                    						if(!empty($bi[$i]['FMemoBudgetTransfer'])):
				                    							foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;

					                    								//1.deduct approved transfer amount from balance
					                    								$bi[$i]['amount']-=$mb['transferred_amount'];
					                    								
					                    							
					                    							endif;
				                    							endforeach;
				                    						endif;
				                    						
				                    						
			                    							$totalByItem += $bi[$i]['amount']; //total by item
			                    							$totalByNonAcad += $bi[$i]['amount']; //total by item for acad
			                    							
			                    							$totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
			                    							
			                    							echo  "<td class='text-center' style='white-space:nowrap;'>";
			                    							
			                    							//info button
			                    							if ($infoFlag):
			                    								echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
			                    								
				                    							echo "&nbsp;&nbsp;";

			                    							endif;

			                    							//edit button
			                    							if ($editFlag):
					                    						$iamt=$bi[$i]['amount'];
					                    						$iid=$bi[$i]['item_amount_id'];
					                    						echo  $this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));
				                    						
				                    							echo "&nbsp;&nbsp;";

				                    						endif;

				                    						echo number_format($bi[$i]['amount'],2,".",",")."</td>";
			                    							$i++; //update the index to next element
			                    							
			                    						}
		                    						else:
		                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
		                    						endif;
	                    							
	                    						endforeach;
	                    						
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'] +=$totalByNonAcad;

	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totGrand']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'] +=$totalByItem;
	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByNonAcad,2,".",",")."</td>";
	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByItem,2,".",",")."</td>";

	                    					echo "</tr>";
	                    					
	                    				endfor;//end foritem 

	                    				?>
	                    				<!-- at the end of each group display the accumulated val for each col -->
	                    				<tr class="success">
				                			<td class="bold text-right" colspan="4"> Sub Total </td>
				                			<td class='bold text-center bigger-text'><?php echo number_format($totalByDept[$b['BItemGroup']['item_group_id']]['ytd'],2,".",",")?> </td>

			                			<?php
			                				//calculate total groups for ytd
			                				if (!isset($totAllGroup['costofrevenue']['ytd']))
			                					$totAllGroup['costofrevenue']['ytd']=0;
			                				$totAllGroup['costofrevenue']['ytd']+=$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'];
			                				//iterate thru acad dept first n set the total val
                    						foreach($deptAcad as $key => $dept):
                    							//calculate total groups for each acad dept
				                				if (!isset($totAllGroup['costofrevenue'][$key]))
				                					$totAllGroup['costofrevenue'][$key]=0;
				                				$totAllGroup['costofrevenue'][$key]+=$totalByDept[$b['BItemGroup']['item_group_id']][$key];	
                    							echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$key],2,".",",")."</td>";

                    						endforeach;
                    						//calculate total groups for totacad
				                				if (!isset($totAllGroup['costofrevenue']['totAcad']))
				                					$totAllGroup['costofrevenue']['totAcad']=0;
				                				$totAllGroup['costofrevenue']['totAcad']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'],2,".",",")."</td>";
                    						//iterate thru acad dept first n set the val
                    						
                    						foreach($deptNonAcad as $key => $dept):
                    							
												//calculate total groups for each acad dept
				                				if (!isset($totAllGroup['costofrevenue'][$key]))
				                					$totAllGroup['costofrevenue'][$key]=0;
				                				$totAllGroup['costofrevenue'][$key]+=$totalByDept[$b['BItemGroup']['item_group_id']][$key];	
                    							echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$key],2,".",",")."</td>";

                    						endforeach;
                    						//calculate total groups for totacad
				                				if (!isset($totAllGroup['costofrevenue']['totNonAcad']))
				                					$totAllGroup['costofrevenue']['totNonAcad']=0;
				                				$totAllGroup['costofrevenue']['totNonAcad']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'],2,".",",")."</td>";
                    						//calculate total groups for grand tot
				                				if (!isset($totAllGroup['costofrevenue']['totGrand']))
				                					$totAllGroup['costofrevenue']['totGrand']=0;
				                				$totAllGroup['costofrevenue']['totGrand']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'],2,".",",")."</td>";

                    						

			                			?>
				                		</tr>

		                    				
	                    		<?php
	                    			endforeach;//end foreach group
	                    			//show total of groups if only groups exist
	                    			if (!empty($budgetCOR)):
		                    	?>

		                    	<tr class='danger'>
		                    		<td class="bold text-right" colspan="4"> Total Cost of Revenue </td>
				                			<!-- <td class="bold text-right" colspan="4"> Sub Total </td> -->
				                	<td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['costofrevenue']['ytd'],2,".",",")?> </td>

		                			<?php
		                				
		                				//iterate thru acad dept first n set the total val
                						foreach($deptAcad as $key => $dept):
                							
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['costofrevenue'][$key],2,".",",")."</td>";

                						endforeach;
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['costofrevenue']['totAcad'],2,".",",")."</td>";
                						//iterate thru acad dept first n set the val
                						
                						foreach($deptNonAcad as $key => $dept):
                						
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['costofrevenue'][$key],2,".",",")."</td>";

                						endforeach;
                						
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['costofrevenue']['totNonAcad'],2,".",",")."</td>";
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['costofrevenue']['totGrand'],2,".",",")."</td>";

		                			?>
				                		
		                    	
		                    	</tr>
		                    	<?php endif;

		                    	if (!empty($budgetR)&&!empty($budgetCOR)):
		                    		$grossProfit['ytd']=$totAllGroup['revenue']['ytd']-$totAllGroup['costofrevenue']['ytd'];
		                    		?>
		                    	<tr class='info'>
		                    		<th colspan='4'>GROSS PROFIT/(LOSS)</th>
		                    		<td class='bold text-center bigger-text'><?php echo number_format($grossProfit['ytd'],2,".",",")?> </td>

		                			<?php
		                				
		                				//iterate thru acad dept first n set the total val
                						foreach($deptAcad as $key => $dept):
                							
                							$grossProfit[$key]=$totAllGroup['revenue'][$key]-$totAllGroup['costofrevenue'][$key];

                							echo "<td class='bold text-center bigger-text'>".number_format($grossProfit[$key],2,".",",")."</td>";

                						endforeach;
                						$grossProfit['totAcad']=$totAllGroup['revenue']['totAcad']-$totAllGroup['costofrevenue']['totAcad'];

                						echo "<td class='bold text-center bigger-text'>".number_format($grossProfit['totAcad'],2,".",",")."</td>";
                						//iterate thru acad dept first n set the val
                						
                						foreach($deptNonAcad as $key => $dept):
                							
                							$grossProfit[$key]=$totAllGroup['revenue'][$key]-$totAllGroup['costofrevenue'][$key];

                							echo "<td class='bold text-center bigger-text'>".number_format($grossProfit[$key],2,".",",")."</td>";

                						endforeach;
                						
                						$grossProfit['totGrand']=$totAllGroup['revenue']['totGrand']-$totAllGroup['costofrevenue']['totGrand'];

                						$grossProfit['totNonAcad']=$totAllGroup['revenue']['totNonAcad']-$totAllGroup['costofrevenue']['totNonAcad'];

                						echo "<td class='bold text-center bigger-text'>".number_format($grossProfit['totNonAcad'],2,".",",")."</td>";
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($grossProfit['totGrand'],2,".",",")."</td>";

		                			?>
		                    	</tr>
		                    	<?php endif;?>
		                    	<tr class='info'>
		                    		<th colspan='<?php echo $countColumn ?>'>OTHER INCOME</th>
		                    	</tr>
		                    		<?php
	                    			
	                    			foreach( $budgetOI as $b ):

	                    				$item=null;
                    					// create row of item
	                    				for( $i=0; $i < count($b['BItemAmount']); ):
	                    					$bi=$b['BItemAmount'];
	                    					echo "<tr>";
	                    					if(empty($bi[$i]['b_dept_id'])):	///indicates new row,ytd
	                    						echo "<th class='' colspan=4 style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
	                    						//edit button
	                    						$iamt=$bi[$i]['amount'];
	                    						$iid=$bi[$i]['item_amount_id'];
	                    						echo  "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".$this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));

	                    						echo "&nbsp;&nbsp;".number_format($bi[$i]['amount'],2,".",",")."</td>";
	                    						$item=$bi[$i]['item_id'];//set item for the whole row
	                    						
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
	                    						
	                    						//reset total each row
	                    						$totalByItem = 0; //init subtotal for each item
			                    				$totalByAcad = 0;
			                    				$totalByNonAcad = 0;
			                    				$i++;
	                    					
	                    					endif;
	                    					//iterate thru acad dept first n set the val
	                    						
	                    						foreach($deptAcad as $key => $dept):
			                    					$editFlag=true;
			                    					$infoFlag=false;

	                    							if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$key]))
	                    								$totalByDept[$b['BItemGroup']['item_group_id']][$key] = 0;
	                    							//initiate total unbudgeted
		                    						if(!isset($totalUnbudgeted[$key]))
                										$totalUnbudgeted[$key] = 0;
	                    							//check if dept same and item same only set val
	                    							if(isset($bi[$i])):
		                    							if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
			                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";	
		                    							}
			                    						else{	
			                    							
			                    							//deduct approved fmemobudget from balance
				                    						if(!empty($bi[$i]['FMemoBudget'])):
				                    							foreach($bi[$i]['FMemoBudget'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;
							                    						//1.deduct approved amount from balance
		                                                                $bi[$i]['amount']-=$mb['amount'];
		                                                                //2.add the unbudgeted/transferred amount to the current item amount, if exist
		                                                                if (!empty($mb['transferred_item_amount_id'])):
		                                                                    //
		                                                                    $bi[$i]['amount']+=$mb['transferred_amount'];
		                                                                    
		                                                                endif;
		                                                                $bi[$i]['amount']+=$mb['unbudgeted_amount'];
		                                                                    // add to accumulated unbudgeted
		                                                                $totalUnbudgeted[$deptid] +=$mb['unbudgeted_amount'];
					                    								
					                    							endif;
				                    							endforeach;
				                    						endif;

				                    						//deduct approved fmemobudgettransfer from balance
				                    						if(!empty($bi[$i]['FMemoBudgetTransfer'])):
				                    							foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;

					                    								//1.deduct approved transfer amount from balance
					                    								$bi[$i]['amount']-=$mb['transferred_amount'];
					                    								
					                    							
					                    							endif;
				                    							endforeach;
				                    						endif;
				                    						
				                    						
			                    							$totalByItem += $bi[$i]['amount']; //total by item
			                    							$totalByAcad += $bi[$i]['amount']; //total by item for acad
			                    							
			                    							$totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
			                    							
			                    							echo  "<td class='text-center' style='white-space:nowrap;'>";
			                    							
			                    							//info button
			                    							if ($infoFlag):
			                    								echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
			                    								
				                    							echo "&nbsp;&nbsp;";

			                    							endif;

			                    							//edit button
			                    							if ($editFlag):
					                    						$iamt=$bi[$i]['amount'];
					                    						$iid=$bi[$i]['item_amount_id'];
					                    						echo  $this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));
				                    						
				                    							echo "&nbsp;&nbsp;";

				                    						endif;

				                    						echo number_format($bi[$i]['amount'],2,".",",")."</td>";
			                    							$i++; //update the index to next element
			                    							if ($bi[$i]['BDepartment']['department_type']==2)
			                    								break;
			                    						}
		                    						else:
		                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
		                    						endif;

	                    						endforeach;
	                    						//initiate totAcad
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totAcad']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'] +=$totalByAcad;

	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByAcad,2,".",",")."</td>";
	                    						//iterate thru acad dept first n set the val
	                    						
	                    						foreach($deptNonAcad as $key => $dept):
	                    							$editFlag=true;
			                    					$infoFlag=false;

	                    							if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$key]))
	                    								$totalByDept[$b['BItemGroup']['item_group_id']][$key] = 0;
	                    							//initiate total unbudgeted
		                    						if(!isset($totalUnbudgeted[$key]))
                										$totalUnbudgeted[$key] = 0;
	                    							//check if dept same and item same only set val
	                    							if(isset($bi[$i])):
		                    							if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
			                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";	
		                    							}
			                    						else{	
			                    							
			                    							//deduct approved fmemobudget from balance
				                    						if(!empty($bi[$i]['FMemoBudget'])):
				                    							foreach($bi[$i]['FMemoBudget'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;
							                    						//1.deduct approved amount from balance
		                                                                $bi[$i]['amount']-=$mb['amount'];
		                                                                //2.add the unbudgeted/transferred amount to the current item amount, if exist
		                                                                if (!empty($mb['transferred_item_amount_id'])):
		                                                                    //
		                                                                    $bi[$i]['amount']+=$mb['transferred_amount'];
		                                                                    
		                                                                endif;
		                                                                $bi[$i]['amount']+=$mb['unbudgeted_amount'];
		                                                                    // add to accumulated unbudgeted
		                                                                $totalUnbudgeted[$deptid] +=$mb['unbudgeted_amount'];
					                    								
					                    							endif;
				                    							endforeach;
				                    						endif;

				                    						//deduct approved fmemobudgettransfer from balance
				                    						if(!empty($bi[$i]['FMemoBudgetTransfer'])):
				                    							foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;

					                    								//1.deduct approved transfer amount from balance
					                    								$bi[$i]['amount']-=$mb['transferred_amount'];
					                    								
					                    							
					                    							endif;
				                    							endforeach;
				                    						endif;
				                    						
				                    						
			                    							$totalByItem += $bi[$i]['amount']; //total by item
			                    							$totalByNonAcad += $bi[$i]['amount']; //total by item for acad
			                    							
			                    							$totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
			                    							
			                    							echo  "<td class='text-center' style='white-space:nowrap;'>";
			                    							
			                    							//info button
			                    							if ($infoFlag):
			                    								echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
			                    								
				                    							echo "&nbsp;&nbsp;";

			                    							endif;

			                    							//edit button
			                    							if ($editFlag):
					                    						$iamt=$bi[$i]['amount'];
					                    						$iid=$bi[$i]['item_amount_id'];
					                    						echo  $this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));
				                    						
				                    							echo "&nbsp;&nbsp;";

				                    						endif;

				                    						echo number_format($bi[$i]['amount'],2,".",",")."</td>";
			                    							$i++; //update the index to next element
			                    							
			                    						}
		                    						else:
		                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
		                    						endif;
	                    							
	                    						endforeach;
	                    						
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'] +=$totalByNonAcad;

	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totGrand']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'] +=$totalByItem;
	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByNonAcad,2,".",",")."</td>";
	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByItem,2,".",",")."</td>";

	                    					echo "</tr>";
	                    					
	                    				endfor;//end foritem 

	                    				?>
	                    				<!-- at the end of each group display the accumulated val for each col -->
	                    				<tr class="success">
				                			<td class="bold text-right" colspan="4"> Sub Total </td>
				                			<td class='bold text-center bigger-text'><?php echo number_format($totalByDept[$b['BItemGroup']['item_group_id']]['ytd'],2,".",",")?> </td>

			                			<?php
			                				//calculate total groups for ytd
			                				if (!isset($totAllGroup['otherincome']['ytd']))
			                					$totAllGroup['otherincome']['ytd']=0;
			                				$totAllGroup['otherincome']['ytd']+=$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'];
			                				//iterate thru acad dept first n set the total val
                    						foreach($deptAcad as $key => $dept):
                    							//calculate total groups for each acad dept
				                				if (!isset($totAllGroup['otherincome'][$key]))
				                					$totAllGroup['otherincome'][$key]=0;
				                				$totAllGroup['otherincome'][$key]+=$totalByDept[$b['BItemGroup']['item_group_id']][$key];	
                    							echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$key],2,".",",")."</td>";

                    						endforeach;
                    						//calculate total groups for totacad
				                				if (!isset($totAllGroup['otherincome']['totAcad']))
				                					$totAllGroup['otherincome']['totAcad']=0;
				                				$totAllGroup['otherincome']['totAcad']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'],2,".",",")."</td>";
                    						//iterate thru acad dept first n set the val
                    						
                    						foreach($deptNonAcad as $key => $dept):
                    							
												//calculate total groups for each acad dept
				                				if (!isset($totAllGroup['otherincome'][$key]))
				                					$totAllGroup['otherincome'][$key]=0;
				                				$totAllGroup['otherincome'][$key]+=$totalByDept[$b['BItemGroup']['item_group_id']][$key];	
                    							echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$key],2,".",",")."</td>";

                    						endforeach;
                    						//calculate total groups for totacad
				                				if (!isset($totAllGroup['otherincome']['totNonAcad']))
				                					$totAllGroup['otherincome']['totNonAcad']=0;
				                				$totAllGroup['otherincome']['totNonAcad']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'],2,".",",")."</td>";
                    						//calculate total groups for grand tot
				                				if (!isset($totAllGroup['otherincome']['totGrand']))
				                					$totAllGroup['otherincome']['totGrand']=0;
				                				$totAllGroup['otherincome']['totGrand']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'],2,".",",")."</td>";

                    						

			                			?>
				                		</tr>

		                    				
	                    		<?php
	                    			endforeach;//end foreach group
		                    	//show total of groups if only groups exist
	                    			if (!empty($budgetOI)):
		                    	?>

		                    	<tr class='danger'>
		                    		<td class="bold text-right" colspan="4"> Total Other Income </td>
				                			<!-- <td class="bold text-right" colspan="4"> Sub Total </td> -->
				                	<td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['otherincome']['ytd'],2,".",",")?> </td>

		                			<?php
		                				
		                				//iterate thru acad dept first n set the total val
                						foreach($deptAcad as $key => $dept):
                							
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['otherincome'][$key],2,".",",")."</td>";

                						endforeach;
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['otherincome']['totAcad'],2,".",",")."</td>";
                						//iterate thru acad dept first n set the val
                						
                						foreach($deptNonAcad as $key => $dept):
                						
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['otherincome'][$key],2,".",",")."</td>";

                						endforeach;
                						
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['otherincome']['totNonAcad'],2,".",",")."</td>";
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['otherincome']['totGrand'],2,".",",")."</td>";

		                			?>
				                		
		                    	
		                    	</tr>
		                    		<?php endif;//endof other income exist?>
		                    	<tr class='info'>
		                    		<th colspan='<?php echo $countColumn ?>'>EXPENSES</th>
		                    	</tr>
		                    		<?php
	                    			
	                    			foreach( $budgetE as $b ):

	                    				$item=null;
                    					// create row of item
	                    				for( $i=0; $i < count($b['BItemAmount']); ):
	                    					$bi=$b['BItemAmount'];
	                    					echo "<tr>";
	                    					if(empty($bi[$i]['b_dept_id'])):	///indicates new row,ytd
	                    						echo "<th class='' colspan=4 style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
	                    						//edit button
	                    						$iamt=$bi[$i]['amount'];
	                    						$iid=$bi[$i]['item_amount_id'];
	                    						echo  "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".$this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));

	                    						echo "&nbsp;&nbsp;".number_format($bi[$i]['amount'],2,".",",")."</td>";
	                    						$item=$bi[$i]['item_id'];//set item for the whole row
	                    						
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
	                    						
	                    						//reset total each row
	                    						$totalByItem = 0; //init subtotal for each item
			                    				$totalByAcad = 0;
			                    				$totalByNonAcad = 0;
			                    				$i++;
	                    					
	                    					endif;
	                    						//iterate thru acad dept first n set the val
	                    						
	                    						foreach($deptAcad as $key => $dept):
			                    					$editFlag=true;
			                    					$infoFlag=false;

	                    							if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$key]))
	                    								$totalByDept[$b['BItemGroup']['item_group_id']][$key] = 0;
	                    							//initiate total unbudgeted
		                    						if(!isset($totalUnbudgeted[$key]))
                										$totalUnbudgeted[$key] = 0;
	                    							//check if dept same and item same only set val
	                    							if(isset($bi[$i])):
		                    							if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
			                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";	
		                    							}
			                    						else{	
			                    							
			                    							//deduct approved fmemobudget from balance
				                    						if(!empty($bi[$i]['FMemoBudget'])):
				                    							foreach($bi[$i]['FMemoBudget'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;
							                    						//1.deduct approved amount from balance
		                                                                $bi[$i]['amount']-=$mb['amount'];
		                                                                //2.add the unbudgeted/transferred amount to the current item amount, if exist
		                                                                if (!empty($mb['transferred_item_amount_id'])):
		                                                                    //
		                                                                    $bi[$i]['amount']+=$mb['transferred_amount'];
		                                                                    
		                                                                endif;
		                                                                $bi[$i]['amount']+=$mb['unbudgeted_amount'];
		                                                                    // add to accumulated unbudgeted
		                                                                $totalUnbudgeted[$deptid] +=$mb['unbudgeted_amount'];
					                    								
					                    							endif;
				                    							endforeach;
				                    						endif;

				                    						//deduct approved fmemobudgettransfer from balance
				                    						if(!empty($bi[$i]['FMemoBudgetTransfer'])):
				                    							foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;

					                    								//1.deduct approved transfer amount from balance
					                    								$bi[$i]['amount']-=$mb['transferred_amount'];
					                    								
					                    							
					                    							endif;
				                    							endforeach;
				                    						endif;
				                    						
				                    						
			                    							$totalByItem += $bi[$i]['amount']; //total by item
			                    							$totalByAcad += $bi[$i]['amount']; //total by item for acad
			                    							
			                    							$totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
			                    							
			                    							echo  "<td class='text-center' style='white-space:nowrap;'>";
			                    							
			                    							//info button
			                    							if ($infoFlag):
			                    								echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
			                    								
				                    							echo "&nbsp;&nbsp;";

			                    							endif;

			                    							//edit button
			                    							if ($editFlag):
					                    						$iamt=$bi[$i]['amount'];
					                    						$iid=$bi[$i]['item_amount_id'];
					                    						echo  $this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));
				                    						
				                    							echo "&nbsp;&nbsp;";

				                    						endif;

				                    						echo number_format($bi[$i]['amount'],2,".",",")."</td>";
			                    							$i++; //update the index to next element
			                    							if ($bi[$i]['BDepartment']['department_type']==2)
			                    								break;
			                    						}
		                    						else:
		                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
		                    						endif;

	                    						endforeach;
	                    						//initiate totAcad
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totAcad']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'] +=$totalByAcad;

	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByAcad,2,".",",")."</td>";
	                    						//iterate thru acad dept first n set the val
	                    						
	                    						foreach($deptNonAcad as $key => $dept):
	                    							$editFlag=true;
			                    					$infoFlag=false;

	                    							if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$key]))
	                    								$totalByDept[$b['BItemGroup']['item_group_id']][$key] = 0;
	                    							//initiate total unbudgeted
		                    						if(!isset($totalUnbudgeted[$key]))
                										$totalUnbudgeted[$key] = 0;
	                    							//check if dept same and item same only set val
	                    							if(isset($bi[$i])):
		                    							if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
			                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";	
		                    							}
			                    						else{	
			                    							
			                    							//deduct approved fmemobudget from balance
				                    						if(!empty($bi[$i]['FMemoBudget'])):
				                    							foreach($bi[$i]['FMemoBudget'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;
							                    						//1.deduct approved amount from balance
		                                                                $bi[$i]['amount']-=$mb['amount'];
		                                                                //2.add the unbudgeted/transferred amount to the current item amount, if exist
		                                                                if (!empty($mb['transferred_item_amount_id'])):
		                                                                    //
		                                                                    $bi[$i]['amount']+=$mb['transferred_amount'];
		                                                                    
		                                                                endif;
		                                                                $bi[$i]['amount']+=$mb['unbudgeted_amount'];
		                                                                    // add to accumulated unbudgeted
		                                                                $totalUnbudgeted[$deptid] +=$mb['unbudgeted_amount'];
					                    								
					                    							endif;
				                    							endforeach;
				                    						endif;

				                    						//deduct approved fmemobudgettransfer from balance
				                    						if(!empty($bi[$i]['FMemoBudgetTransfer'])):
				                    							foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;

					                    								//1.deduct approved transfer amount from balance
					                    								$bi[$i]['amount']-=$mb['transferred_amount'];
					                    								
					                    							
					                    							endif;
				                    							endforeach;
				                    						endif;
				                    						
				                    						
			                    							$totalByItem += $bi[$i]['amount']; //total by item
			                    							$totalByNonAcad += $bi[$i]['amount']; //total by item for acad
			                    							
			                    							$totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
			                    							
			                    							echo  "<td class='text-center' style='white-space:nowrap;'>";
			                    							
			                    							//info button
			                    							if ($infoFlag):
			                    								echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
			                    								
				                    							echo "&nbsp;&nbsp;";

			                    							endif;

			                    							//edit button
			                    							if ($editFlag):
					                    						$iamt=$bi[$i]['amount'];
					                    						$iid=$bi[$i]['item_amount_id'];
					                    						echo  $this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));
				                    						
				                    							echo "&nbsp;&nbsp;";

				                    						endif;

				                    						echo number_format($bi[$i]['amount'],2,".",",")."</td>";
			                    							$i++; //update the index to next element
			                    							
			                    						}
		                    						else:
		                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
		                    						endif;
	                    							
	                    						endforeach;
	                    						
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'] +=$totalByNonAcad;

	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totGrand']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'] +=$totalByItem;
	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByNonAcad,2,".",",")."</td>";
	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByItem,2,".",",")."</td>";

	                    					echo "</tr>";
	                    					
	                    				endfor;//end foritem 

	                    				?>
	                    				<!-- at the end of each group display the accumulated val for each col -->
	                    				<tr class="success">
				                			<td class="bold text-right" colspan="4"> Sub Total </td>
				                			<td class='bold text-center bigger-text'><?php echo number_format($totalByDept[$b['BItemGroup']['item_group_id']]['ytd'],2,".",",")?> </td>

			                			<?php
			                				//calculate total groups for ytd
			                				if (!isset($totAllGroup['expenses']['ytd']))
			                					$totAllGroup['expenses']['ytd']=0;
			                				$totAllGroup['expenses']['ytd']+=$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'];
			                				//iterate thru acad dept first n set the total val
                    						foreach($deptAcad as $key => $dept):
                    							//calculate total groups for each acad dept
				                				if (!isset($totAllGroup['expenses'][$key]))
				                					$totAllGroup['expenses'][$key]=0;
				                				$totAllGroup['expenses'][$key]+=$totalByDept[$b['BItemGroup']['item_group_id']][$key];	
                    							echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$key],2,".",",")."</td>";

                    						endforeach;
                    						//calculate total groups for totacad
				                				if (!isset($totAllGroup['expenses']['totAcad']))
				                					$totAllGroup['expenses']['totAcad']=0;
				                				$totAllGroup['expenses']['totAcad']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'],2,".",",")."</td>";
                    						//iterate thru acad dept first n set the val
                    						
                    						foreach($deptNonAcad as $key => $dept):
                    							
												//calculate total groups for each acad dept
				                				if (!isset($totAllGroup['expenses'][$key]))
				                					$totAllGroup['expenses'][$key]=0;
				                				$totAllGroup['expenses'][$key]+=$totalByDept[$b['BItemGroup']['item_group_id']][$key];	
                    							echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$key],2,".",",")."</td>";

                    						endforeach;
                    						//calculate total groups for totacad
				                				if (!isset($totAllGroup['expenses']['totNonAcad']))
				                					$totAllGroup['expenses']['totNonAcad']=0;
				                				$totAllGroup['expenses']['totNonAcad']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'],2,".",",")."</td>";
                    						//calculate total groups for grand tot
				                				if (!isset($totAllGroup['expenses']['totGrand']))
				                					$totAllGroup['expenses']['totGrand']=0;
				                				$totAllGroup['expenses']['totGrand']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'],2,".",",")."</td>";

                    						

			                			?>
				                		</tr>

		                    				
	                    		<?php
	                    			endforeach;//end foreach group
		                    	//show total of groups if only groups exist
	                    			if (!empty($budgetE)):
		                    	?>

		                    	<tr class='danger'>
		                    		<td class="bold text-right" colspan="4"> Total Expenses </td>
				                			<!-- <td class="bold text-right" colspan="4"> Sub Total </td> -->
				                	<td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['expenses']['ytd'],2,".",",")?> </td>

		                			<?php
		                				
		                				//iterate thru acad dept first n set the total val
                						foreach($deptAcad as $key => $dept):
                							
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['expenses'][$key],2,".",",")."</td>";

                						endforeach;
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['expenses']['totAcad'],2,".",",")."</td>";
                						//iterate thru acad dept first n set the val
                						
                						foreach($deptNonAcad as $key => $dept):
                						
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['expenses'][$key],2,".",",")."</td>";

                						endforeach;
                						
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['expenses']['totNonAcad'],2,".",",")."</td>";
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['expenses']['totGrand'],2,".",",")."</td>";

		                			?>
				                		
		                    	
		                    	</tr>
	                    		<?php endif;

	                    		if (!empty($budgetR)&&!empty($budgetCOR)&&!empty($budgetOI)&&!empty($budgetE)):

		                    		$netProfit['ytd']=$grossProfit['ytd']+$totAllGroup['otherincome']['ytd']-$totAllGroup['expenses']['ytd'];
		                    	?>
		                    	
		                    	<tr class='info'>
		                    		<th colspan='4'>NET PROFIT/(LOSS)</th>
		                    		<td class='bold text-center bigger-text'><?php echo number_format($netProfit['ytd'],2,".",",")?> </td>

		                			<?php
		                				
		                				//iterate thru acad dept first n set the total val
                						foreach($deptAcad as $key => $dept):
                							
                							$netProfit[$key]=$grossProfit[$key]+$totAllGroup['otherincome'][$key]-$totAllGroup['expenses'][$key];

                							echo "<td class='bold text-center bigger-text'>".number_format($netProfit[$key],2,".",",")."</td>";

                						endforeach;
                						$netProfit['totAcad']=$grossProfit['totAcad']+$totAllGroup['otherincome']['totAcad']-$totAllGroup['expenses']['totAcad'];

                						echo "<td class='bold text-center bigger-text'>".number_format($netProfit['totAcad'],2,".",",")."</td>";
                						//iterate thru acad dept first n set the val
                						
                						foreach($deptNonAcad as $key => $dept):
                							
                							$netProfit[$key]=$grossProfit[$key]+$totAllGroup['otherincome'][$key]-$totAllGroup['expenses'][$key];

                							echo "<td class='bold text-center bigger-text'>".number_format($netProfit[$key],2,".",",")."</td>";

                						endforeach;
                						
                						$netProfit['totGrand']=$grossProfit['totGrand']+$totAllGroup['otherincome']['totGrand']-$totAllGroup['expenses']['totGrand'];

                						$netProfit['totNonAcad']=$grossProfit['totNonAcad']+$totAllGroup['otherincome']['totNonAcad']-$totAllGroup['expenses']['totNonAcad'];

                						echo "<td class='bold text-center bigger-text'>".number_format($netProfit['totNonAcad'],2,".",",")."</td>";
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($netProfit['totGrand'],2,".",",")."</td>";

		                			?>
		                    	</tr>
		                    	<?php endif;?>
		                    	<tr class='info'>
		                    		<th colspan='<?php echo $countColumn ?>'>TAXATION</th>
		                    	</tr>
		                    		<?php
	                    			
	                    			foreach( $budgetT as $b ):

	                    				$item=null;
                    					// create row of item
	                    				for( $i=0; $i < count($b['BItemAmount']); ):
	                    					$bi=$b['BItemAmount'];
	                    					echo "<tr>";
	                    					if(empty($bi[$i]['b_dept_id'])):	///indicates new row,ytd
	                    						echo "<th class='' colspan=4 style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
	                    						//edit button
	                    						$iamt=$bi[$i]['amount'];
	                    						$iid=$bi[$i]['item_amount_id'];
	                    						echo  "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".$this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));

	                    						echo "&nbsp;&nbsp;".number_format($bi[$i]['amount'],2,".",",")."</td>";
	                    						$item=$bi[$i]['item_id'];//set item for the whole row
	                    						
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
	                    						
	                    						//reset total each row
	                    						$totalByItem = 0; //init subtotal for each item
			                    				$totalByAcad = 0;
			                    				$totalByNonAcad = 0;
			                    				$i++;
	                    					
	                    						endif;
	                    						//iterate thru acad dept first n set the val
	                    						
	                    						foreach($deptAcad as $key => $dept):
			                    					$editFlag=true;
			                    					$infoFlag=false;

	                    							if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$key]))
	                    								$totalByDept[$b['BItemGroup']['item_group_id']][$key] = 0;
	                    							//initiate total unbudgeted
		                    						if(!isset($totalUnbudgeted[$key]))
                										$totalUnbudgeted[$key] = 0;
	                    							//check if dept same and item same only set val
	                    							if(isset($bi[$i])):
		                    							if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
			                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";	
		                    							}
			                    						else{	
			                    							
			                    							//deduct approved fmemobudget from balance
				                    						if(!empty($bi[$i]['FMemoBudget'])):
				                    							foreach($bi[$i]['FMemoBudget'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;
							                    						//1.deduct approved amount from balance
		                                                                $bi[$i]['amount']-=$mb['amount'];
		                                                                //2.add the unbudgeted/transferred amount to the current item amount, if exist
		                                                                if (!empty($mb['transferred_item_amount_id'])):
		                                                                    //
		                                                                    $bi[$i]['amount']+=$mb['transferred_amount'];
		                                                                    
		                                                                endif;
		                                                                $bi[$i]['amount']+=$mb['unbudgeted_amount'];
		                                                                    // add to accumulated unbudgeted
		                                                                $totalUnbudgeted[$deptid] +=$mb['unbudgeted_amount'];
					                    								
					                    							endif;
				                    							endforeach;
				                    						endif;

				                    						//deduct approved fmemobudgettransfer from balance
				                    						if(!empty($bi[$i]['FMemoBudgetTransfer'])):
				                    							foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;

					                    								//1.deduct approved transfer amount from balance
					                    								$bi[$i]['amount']-=$mb['transferred_amount'];
					                    								
					                    							
					                    							endif;
				                    							endforeach;
				                    						endif;
				                    						
				                    						
			                    							$totalByItem += $bi[$i]['amount']; //total by item
			                    							$totalByAcad += $bi[$i]['amount']; //total by item for acad
			                    							
			                    							$totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
			                    							
			                    							echo  "<td class='text-center' style='white-space:nowrap;'>";
			                    							
			                    							//info button
			                    							if ($infoFlag):
			                    								echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
			                    								
				                    							echo "&nbsp;&nbsp;";

			                    							endif;

			                    							//edit button
			                    							if ($editFlag):
					                    						$iamt=$bi[$i]['amount'];
					                    						$iid=$bi[$i]['item_amount_id'];
					                    						echo  $this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));
				                    						
				                    							echo "&nbsp;&nbsp;";

				                    						endif;

				                    						echo number_format($bi[$i]['amount'],2,".",",")."</td>";
			                    							$i++; //update the index to next element
			                    							if ($bi[$i]['BDepartment']['department_type']==2)
			                    								break;
			                    						}
		                    						else:
		                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
		                    						endif;
		                    						
	                    						endforeach;
	                    						//initiate totAcad
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totAcad']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'] +=$totalByAcad;

	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByAcad,2,".",",")."</td>";
	                    						//iterate thru acad dept first n set the val
	                    						
	                    						foreach($deptNonAcad as $key => $dept):
	                    							$editFlag=true;
			                    					$infoFlag=false;

	                    							if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$key]))
	                    								$totalByDept[$b['BItemGroup']['item_group_id']][$key] = 0;
	                    							//initiate total unbudgeted
		                    						if(!isset($totalUnbudgeted[$key]))
                										$totalUnbudgeted[$key] = 0;
	                    							//check if dept same and item same only set val
	                    							if(isset($bi[$i])):
		                    							if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
			                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
			                    								
		                    							}
			                    						else{	
			                    							
			                    							//deduct approved fmemobudget from balance
				                    						if(!empty($bi[$i]['FMemoBudget'])):
				                    							foreach($bi[$i]['FMemoBudget'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;
							                    						//1.deduct approved amount from balance
		                                                                $bi[$i]['amount']-=$mb['amount'];
		                                                                //2.add the unbudgeted/transferred amount to the current item amount, if exist
		                                                                if (!empty($mb['transferred_item_amount_id'])):
		                                                                    //
		                                                                    $bi[$i]['amount']+=$mb['transferred_amount'];
		                                                                    
		                                                                endif;
		                                                                $bi[$i]['amount']+=$mb['unbudgeted_amount'];
		                                                                    // add to accumulated unbudgeted
		                                                                $totalUnbudgeted[$deptid] +=$mb['unbudgeted_amount'];
					                    								
					                    							endif;
				                    							endforeach;
				                    						endif;

				                    						//deduct approved fmemobudgettransfer from balance
				                    						if(!empty($bi[$i]['FMemoBudgetTransfer'])):
				                    							foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
					                    							//deduct only for approved memo budget
					                    							if (!empty($mb['FMemo'])):
					                    								$editFlag=false;
					                    								$infoFlag=true;

					                    								//1.deduct approved transfer amount from balance
					                    								$bi[$i]['amount']-=$mb['transferred_amount'];
					                    								
					                    							
					                    							endif;
				                    							endforeach;
				                    						endif;
				                    						
				                    						
			                    							$totalByItem += $bi[$i]['amount']; //total by item
			                    							$totalByNonAcad += $bi[$i]['amount']; //total by item for acad
			                    							
			                    							$totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
			                    							
			                    							echo  "<td class='text-center' style='white-space:nowrap;'>";
			                    							
			                    							//info button
			                    							if ($infoFlag):
			                    								echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
			                    								
				                    							echo "&nbsp;&nbsp;";

			                    							endif;

			                    							//edit button
			                    							if ($editFlag):
					                    						$iamt=$bi[$i]['amount'];
					                    						$iid=$bi[$i]['item_amount_id'];
					                    						echo  $this->Html->link('<i class="fa fa-pencil"></i>','#modal-edit',array('escape'=>false,'class'=>'btn btn-round btn-primary btn-xs modal-edit-btn','data-original-title'=>'Edit','data-toggle'=>'modal','data-id'=>$iid,'data-amount'=>$iamt));
				                    						
				                    							echo "&nbsp;&nbsp;";

				                    						endif;

				                    						echo number_format($bi[$i]['amount'],2,".",",")."</td>";
			                    							$i++; //update the index to next element
			                    							
			                    						}
		                    						else:
		                    							echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
		                    						endif;
	                    							
	                    						endforeach;
	                    						
	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'] +=$totalByNonAcad;

	                    						if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['totGrand']))
	                    							$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand']=0;

	                    						$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'] +=$totalByItem;
	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByNonAcad,2,".",",")."</td>";
	                    						echo "<td class='text-center dark-grey-bg'>".number_format($totalByItem,2,".",",")."</td>";

	                    					echo "</tr>";
	                    					
	                    				endfor;//end foritem 

	                    				?>
	                    				<!-- at the end of each group display the accumulated val for each col -->
	                    				<tr class="success">
				                			<td class="bold text-right" colspan="4"> Sub Total </td>
				                			<td class='bold text-center bigger-text'><?php echo number_format($totalByDept[$b['BItemGroup']['item_group_id']]['ytd'],2,".",",")?> </td>

			                			<?php
			                				//calculate total groups for ytd
			                				if (!isset($totAllGroup['taxation']['ytd']))
			                					$totAllGroup['taxation']['ytd']=0;
			                				$totAllGroup['taxation']['ytd']+=$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'];
			                				//iterate thru acad dept first n set the total val
                    						foreach($deptAcad as $key => $dept):
                    							//calculate total groups for each acad dept
				                				if (!isset($totAllGroup['taxation'][$key]))
				                					$totAllGroup['taxation'][$key]=0;
				                				$totAllGroup['taxation'][$key]+=$totalByDept[$b['BItemGroup']['item_group_id']][$key];	
                    							echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$key],2,".",",")."</td>";

                    						endforeach;
                    						//calculate total groups for totacad
				                				if (!isset($totAllGroup['taxation']['totAcad']))
				                					$totAllGroup['taxation']['totAcad']=0;
				                				$totAllGroup['taxation']['totAcad']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totAcad'],2,".",",")."</td>";
                    						//iterate thru acad dept first n set the val
                    						
                    						foreach($deptNonAcad as $key => $dept):
                    							
												//calculate total groups for each acad dept
				                				if (!isset($totAllGroup['taxation'][$key]))
				                					$totAllGroup['taxation'][$key]=0;
				                				$totAllGroup['taxation'][$key]+=$totalByDept[$b['BItemGroup']['item_group_id']][$key];	
                    							echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$key],2,".",",")."</td>";

                    						endforeach;
                    						//calculate total groups for totacad
				                				if (!isset($totAllGroup['taxation']['totNonAcad']))
				                					$totAllGroup['taxation']['totNonAcad']=0;
				                				$totAllGroup['taxation']['totNonAcad']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totNonAcad'],2,".",",")."</td>";
                    						//calculate total groups for grand tot
				                				if (!isset($totAllGroup['taxation']['totGrand']))
				                					$totAllGroup['taxation']['totGrand']=0;
				                				$totAllGroup['taxation']['totGrand']+=$totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'];	
                    						echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']]['totGrand'],2,".",",")."</td>";

                    						

			                			?>
				                		</tr>

		                    				
	                    		<?php
	                    			endforeach;//end foreach group
		                    	//show total of groups if only groups exist
	                    			if (!empty($budgetT)):
		                    	?>

		                    	<tr class='danger'>
		                    		<td class="bold text-right" colspan="4"> Total Taxation </td>
				                			<!-- <td class="bold text-right" colspan="4"> Sub Total </td> -->
				                	<td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['taxation']['ytd'],2,".",",")?> </td>

		                			<?php
		                				
		                				//iterate thru acad dept first n set the total val
                						foreach($deptAcad as $key => $dept):
                							
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['taxation'][$key],2,".",",")."</td>";

                						endforeach;
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['taxation']['totAcad'],2,".",",")."</td>";
                						//iterate thru acad dept first n set the val
                						
                						foreach($deptNonAcad as $key => $dept):
                						
                							echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['taxation'][$key],2,".",",")."</td>";

                						endforeach;
                						
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['taxation']['totNonAcad'],2,".",",")."</td>";
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['taxation']['totGrand'],2,".",",")."</td>";

		                			?>
				                		
		                    	
		                    	</tr>
	                    		<?php endif;?>
		                    	<?php 
		                		if (!empty($budgetR)&&!empty($budgetCOR)&&!empty($budgetOI)&&!empty($budgetE)&&!empty($budgetT)):
	                    			$netProfitTax['ytd']=$netProfit['ytd']-$totAllGroup['taxation']['ytd'];
		                	?>	<tr class="">
		                			<td colspan="5" class="bold text-left"> UNBUDGETED ITEM </td>

		                			<?php
		                				if (!isset($totalUnbudgeted['totAcad']))
		                					$totalUnbudgeted['totAcad']=0;
		                				if (!isset($totalUnbudgeted['totNonAcad']))
		                					$totalUnbudgeted['totNonAcad']=0;
		                				if (!isset($totalUnbudgeted['totGrand']))
		                					$totalUnbudgeted['totGrand']=0;
		                				//iterate thru acad dept first n set the total val
                						foreach($deptAcad as $key => $dept):
                							
                							echo "<td class='bold text-center bigger-text'>".number_format($totalUnbudgeted[$key],2,".",",")."</td>";
                							$totalUnbudgeted['totAcad']+=$totalUnbudgeted[$key];
                						endforeach;
		                    			
                						echo "<td class='bold text-center bigger-text  dark-grey-bg' >".number_format($totalUnbudgeted['totAcad'],2,".",",")."</td>";
                						//iterate thru acad dept first n set the val
                						
                						foreach($deptNonAcad as $key => $dept):
                							
                							echo "<td class='bold text-center bigger-text'>".number_format($totalUnbudgeted[$key],2,".",",")."</td>";
                							$totalUnbudgeted['totNonAcad']+=$totalUnbudgeted[$key];

                						endforeach;
                						
                						echo "<td class='bold text-center bigger-text  dark-grey-bg'>".number_format($totalUnbudgeted['totNonAcad'],2,".",",")."</td>";
                						$totalUnbudgeted['totGrand']=$totalUnbudgeted['totAcad']+$totalUnbudgeted['totNonAcad'];
                							
                						echo "<td class='bold text-center bigger-text  dark-grey-bg'>".number_format($totalUnbudgeted['totGrand'],2,".",",")."</td>";

		                			?>
		                    	</tr>
		                		<tr class="info">
		                			<td colspan="4" class="bold text-right"> NET PROFIT/(LOSS) AFTER TAX </td>

		                    		<td class='bold text-center bigger-text'><?php echo number_format($netProfitTax['ytd'],2,".",",")?> </td>

		                			<?php
		                				
		                				//iterate thru acad dept first n set the total val
                						foreach($deptAcad as $key => $dept):
                							
                							$netProfitTax[$key]=$netProfit[$key]-$totAllGroup['taxation'][$key];

                							echo "<td class='bold text-center bigger-text'>".number_format($netProfitTax[$key],2,".",",")."</td>";

                						endforeach;
		                    			
		                    			$netProfitTax['totAcad']=$netProfit['totAcad']-$totAllGroup['taxation']['totAcad'];

                						echo "<td class='bold text-center bigger-text'>".number_format($netProfitTax['totAcad'],2,".",",")."</td>";
                						//iterate thru acad dept first n set the val
                						
                						foreach($deptNonAcad as $key => $dept):
                							
                							$netProfitTax[$key]=$netProfit[$key]-$totAllGroup['taxation'][$key];
                							

                							echo "<td class='bold text-center bigger-text'>".number_format($netProfitTax[$key],2,".",",")."</td>";

                						endforeach;
                						
		                    			$netProfitTax['totNonAcad']=$netProfit['totNonAcad']-$totAllGroup['taxation']['totNonAcad'];

		                    			$netProfitTax['totGrand']=$netProfit['totGrand']-$totAllGroup['taxation']['totGrand'];
                						

                						echo "<td class='bold text-center bigger-text'>".number_format($netProfitTax['totNonAcad'],2,".",",")."</td>";
                							
                						echo "<td class='bold text-center bigger-text'>".number_format($netProfitTax['totGrand'],2,".",",")."</td>";

		                			?>
		                    	</tr>
		                    	<?php endif;?>

		                	</tbody>
		                
		                	
		            	</table>
		            	
		            </div>
	            </div>
	        </section>
	    </div>
	</div>
</section>
<script type="text/javascript">
	$(document).ready(function(){
	 $(".scrollable-area").niceScroll();
	 // passing a fixedOffset param will cause the table header to stick to the bottom of this element
    $(".tableB").stickyTableHeaders({ scrollableArea: $(".mCustomScrollbar")[0], "fixedOffset": 2 });
	  $('.modal-edit-btn').on('click',function(){
        var item_amount_id = $(this).data('id');
        var amount = $(this).data('amount');

        $('#item_amount_id').val(item_amount_id);
        $('#amount').val(amount);


   	 });
	});
	


</script>

