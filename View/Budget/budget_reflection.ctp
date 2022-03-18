<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <b><?php echo $reflection['Item']['item_code'].' - '.$reflection['Item']['item'].' ('.$reflection['BDepartment']['Department']['department_shortform'].')'?></b>
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>

            <div class="panel-body" style="width:100%">
            		<h5><strong>Original Amount :</strong> RM<?php echo number_format($reflection['BItemAmount']['amount'],2,".",",") ?>
            		</h5>

            		<br>
	               <table class="table table-bordered  ">
						<thead>
							<tr class='unitar-blue-bg'>
								<th class="text-center " >No.</th>
								<th class="text-left " width="40%">Memo Detail</th>
								<th class="text-left " width="55%">Budget Activity Description</th>
							</tr>
						</thead>
						<tbody>
							<!-- <tr>
								<th class="text-right" colspan="2">Original Amount (RM)</th>
								<th class="text-center"><?php echo number_format($reflection['BItemAmount']['amount'],2,".",",") ?></th>

							</tr> -->
						   <?php
						   $counter=1;
						   if (!empty($reflection['FMemoBudget'])):

						   	foreach ($reflection['FMemoBudget']as $mb):
						   		if (!empty($mb['FMemo'])):
						   ?>
							   		<tr class='info'>
								   		<td class="text-center"><?php echo $counter++;?></td>
								   		<td class="text-left"><?php echo 'Ref No. : <br><small><b>'.$mb['FMemo']['ref_no'].'</b></small><br>Subject : <br><small><b>'.$mb['FMemo']['subject'].'</b></small>';?></td>
								   		<td class="text-left">

									   	<?php 
									   		echo "Memo budget approval ";
								   			echo"<b><br><small>Approved amount :";

								   			echo "<br><font color='red'>( - ) RM".number_format($mb['amount'],2,".",",").'</font>';


								   			//deduct the approved budget from original
									   		$reflection['BItemAmount']['amount']-=$mb['amount'];

									   		if (!empty($mb['transferred_item_amount_id']))://meaning transferred budget
								   				
								   				echo"<br>Budget transfer from ".$mb['BItemAmountTransfer']['Item']['item'].' ('.$mb['BItemAmountTransfer']['BDepartment']['Department']['department_shortform'].") :";

								   				echo "<br><font color='green'>( + ) RM".number_format($mb['transferred_amount'],2,".",",").'</font>';
								   				// echo "<br>Transferred from :";
								   				// echo "<br>".$mb['BItemAmountTransfer']['Item']['item'].' ('.$mb['BItemAmountTransfer']['BDepartment']['Department']['department_shortform'].')';
									   			
									   			//add unbudgeted/transferred amount to original
									   				
									   			$reflection['BItemAmount']['amount']+=$mb['transferred_amount'];
								   				
								   			endif;

								   			if (!empty($mb['unbudgeted_amount']))://unbudgeted amount allocated

								   				echo"<br>Unbudgeted amount :";

								   				echo "<br><font color='green'>( + ) RM".number_format($mb['unbudgeted_amount'],2,".",",").'</font>';
									   			
									   			//add unbudgeted/transferred amount to original
									   			
									   			$reflection['BItemAmount']['amount']+=$mb['unbudgeted_amount'];

								   			endif;

								   			echo "</small></b>";
									   		

									   	?>

								   		</td>
							   		</tr>
								
						   	<?php
						   		endif;
						   	endforeach;

						   endif;
						   
						   if (!empty($reflection['FMemoBudgetTransfer'])):

						   	foreach ($reflection['FMemoBudgetTransfer']as $mb):
						   		if (!empty($mb['FMemo'])):
						   ?>
							   		<tr class='success'>
								   		<td class="text-center"><?php echo $counter++;?></td>
								   		<td class="text-left"><?php echo 'Ref No. : <br><small><b>'.$mb['FMemo']['ref_no'].'</b></small><br>Subject : <br><small><b>'.$mb['FMemo']['subject'].'</b></small>';?></td>
								   		<td class="text-left">

									   	<?php 
									   		
							   				echo "Budget transfer allocation";
								   			echo"<b><br><small>Transferred amount :";

							   				echo "<br><font color='red'>( - ) RM".number_format($mb['transferred_amount'],2,".",",").'</font>';
							   				echo "<br>Transferred to :";
							   				echo "<br><b>".$mb['BItemAmountT']['Item']['item'].' ('.$mb['BItemAmountT']['BDepartment']['Department']['department_shortform'].')</b>';
							   				echo "</small></b>";
						   			
									   				//deduct the approved budget from original
									   		$reflection['BItemAmount']['amount']-=$mb['transferred_amount'];
									   	?>

								   		</td>
							   		</tr>
								
						   	<?php
						   		endif;
						   	endforeach;

						   endif;
						   ?>

	            		</tbody>
	            	</table>
	            	<br>
	            	<h5><strong>Available Balance :</strong> RM<?php echo number_format($reflection['BItemAmount']['amount'],2,".",",") ?>
            		</h5>

	            </div>
        </section>
    </div>
</div>