<section class="mems-content pdf-text-overwrite">
    <div class="row col-lg-12">
        <section class="panel">

            <div class="panel-body">
	                <table class="table table-bordered" style="width:auto">
	                    <thead>
	                        <tr class="">
	                            <th colspan="2" class="text-centre dark-grey-bg" style="white-space:nowrap">Group</th>
	                            <?php
	                            	$countColumn = 3; // including the colspan and subtotal
	                            	foreach($groups as $group):
	                            		echo "<th colspan='".count($group['Department'])."' class='text-center dark-grey-bg' style='white-space:nowrap'>".$group['Group']['group_name']."</th>";
	                            		$countColumn += count($group['Department']);
	                        		endforeach;
	                        	?>
	                        	<th rowspan="2" class="text-center dark-grey-bg" style="min-width:120px; vertical-align:middle">Total</th>
	                        </tr>
	                        <tr class="">
	                        	<th colspan="2" class="text-left dark-grey-bg" style="width:auto;white-space:nowrap;vertical-align:top">Description of Income <br> Statement Item</th>
	                        	<?php
	                        		$departmentLists = array();
	                        		foreach($groups as $group):
	                        			foreach($group['Department'] as $department):
	                        				echo "<th class='text-center dark-grey-bg' style='width:auto;vertical-align:top'>".$department['department_name']."</th>";
	                        			array_push($departmentLists,$department['department_id']);
	                        			endforeach;
	                        		endforeach;
	                        	?>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    	<?php
	                    		$category = null;
	                    		$item = null;
	                    		
                    			//trial 3
                    			$no = 1;
                    			$totalByDept[] = 0;
                    			for( $i=0; $i < count($budgets); ):
                    				$totalByItem = 0; //init subtotal for each item
                    				
                    				if($category != $budgets[$i]['BItem']['BCategory']['category']):
                    					echo "<tr class='light-grey-bg bold'>";
                    					echo "<td colspan='".$countColumn."'>".$budgets[$i]['BItem']['BCategory']['category']."</td>";
                    					echo "</tr>";
                    					$category = $budgets[$i]['BItem']['BCategory']['category'];
                    					$no=1; // update the no. to start with 1 for different category
                    				endif;

                    				// create row of item
                    				echo "<tr>";
                    				if($item != $budgets[$i]['BNewAmount']['item_id']):	
                    					echo "<td class='text-center '>".$no++."</td>";
                    					echo "<td class=''>".$budgets[$i]['BItem']['item']."</td>";
                    					$item = $budgets[$i]['BNewAmount']['item_id'];
                    				endif;

                    				foreach($departmentLists as $key => $dept):

                    					if(!isset($totalByDept[$key]))
                    						$totalByDept[$key] = 0;

                    					if(isset($budgets[$i])):
	                    					if( ($budgets[$i]['BNewAmount']['department_id'] == $dept) && ($budgets[$i]['BNewAmount']['item_id'] == $item) ):
	                    						echo "<td class='text-center'>";
	                    						echo number_format($budgets[$i][0]['totalAmount'],2,".",",");
	                    						echo "</td>";

		                    					$totalByItem += $budgets[$i][0]['totalAmount']; // total by item
	                							$totalByDept[$key] += $budgets[$i][0]['totalAmount']; // total by department

	                    						$i++; //update the index to next element
	                    					else:
	                    						echo "<td class='text-center'><small><em> -  </em></small></td>";
	                    					endif;
                    					else: // the last row -- index is still added 1
                    						echo "<td class='text-center'><small><em> - </em></small></td>";
                    					endif;

                    				endforeach;
                    				echo "<td class='text-center bold'>".number_format($totalByItem,2,".",",")."</td>";
                    				echo "</tr>";
                    			endfor;

                    			// debug($totalByDept);
	                    	?>
	                	</tbody>
	                	<tfoot>
	                		<tr class="medium-grey-bg">
	                			<td colspan="2" class="bold text-right"> Total Amount </td>
	                			<?php
	                				$majorTotal = 0;
	                				foreach($totalByDept as $totalDept):
	                					echo "<td class='text-center bold bigger-text'>".number_format($totalDept,2,".",",")."</td>";
	                					$majorTotal += $totalDept;
	                				endforeach;
	                				echo "<td class='bold text-center bigger-text'>".number_format($majorTotal,2,".",",")."</td>";
	                			?>
	                		</tr>
	                	</tfoot>
	            	</table>
	            	<?php
	            		// debug($countColumn);
	            	?>
            </div>
        </section>
    </div>
</section>