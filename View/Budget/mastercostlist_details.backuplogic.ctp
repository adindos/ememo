<section class="mems-content">
    <div class="row col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Master Cost List
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>

            <div class="panel-body">
            	<div class="margin-bottom pull-left">
            		<h4> UNITAR Master Cost List 2014 </h4>
            	</div>
            	<div class="margin-bottom pull-right">
            		<?php
            			echo $this->Html->link('<i class="fa fa-save"></i> Save in Computer',array('controller'=>'','action'=>''),array('escape'=>false,'class'=>'btn btn-sm btn-round btn-success margin-right'));
            			echo $this->Html->link('<i class="fa fa-download"></i> Download as PDF',array('controller'=>'','action'=>''),array('escape'=>false,'class'=>'btn btn-sm btn-round btn-info'));
            		?>
            	</div>
            	<div class="clear"></div>
            	<div class="mCustomScrollbar" style="padding : 30px;">
	                <table class="table table-bordered table-striped">
	                    <thead>
	                        <tr class="">
	                            <th colspan="2" class="col-lg-2 text-center light-grey-bg" style="white-space:nowrap">Department</th>
	                            <?php
	                            	$countColumn = 2; // including the colspan
	                            	foreach($groups as $group):
	                            		echo "<th colspan='".count($group['Department'])."' class='text-center' style='white-space:nowrap'>".$group['Group']['group_name']."</th>";
	                            		$countColumn += count($group['Department']);
	                        		endforeach;
	                        	?>
	                        </tr>
	                        <tr class="">
	                        	<th colspan="2" class="col-lg-2 text-center light-grey-bg" style="white-space:nowrap">Description of Income Statement Item</th>
	                        	<?php
	                        		$departmentLists = array();
	                        		foreach($groups as $group):
	                        			foreach($group['Department'] as $department):
	                        				echo "<th class='text-center' style='white-space:nowrap'>".$department['department_name']."(".$department['department_id'].")"."</th>";
	                        			array_push($departmentLists,$department['department_id']);
	                        			endforeach;
	                        		endforeach;
	                        	?>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    	<?php
	                    		// echo 
	                    		$category = null;
	                    		$item = null;
                    			// foreach($budgets as $budget):
                    			// 	$no = 1;
                    			// 	if($category != $budget['BItem']['BCategory']['category']):
                    			// 		echo "<tr class='info'>";
                    			// 		echo "<td colspan='".$countColumn."'>".$budget['BItem']['BCategory']['category']."</td>";
                    			// 		echo "</tr>";
                    			// 	endif;

                    			// 	// create row of item
                    			// 	if($item != $budget['BNewAmount']['item_id']):
                    			// 		echo "<tr>";
                    			// 		echo "<td class='text-center'>".$no++."</td>";
                    			// 		echo "<td class=''>".$budget['BItem']['item']."</td>";
                    			// 	endif;

                    			// 	if(empty($departmentList))
                    			// 		echo "</tr>";
	                				

                    			// 	$category = $budget['BItem']['BCategory']['category'];
                    			// 	$item = $budget['BNewAmount']['item_id'];
                    			// endforeach;

                    			//trial 2
                    	// 		for( $i=0; $i < count($budgets)-1; $i++):
                    	// 			$no = 1;
                    	// 			if($category != $budgets[$i]['BItem']['BCategory']['category']):
                    	// 				echo "<tr class='info'>";
                    	// 				echo "<td colspan='".$countColumn."'>".$budgets[$i]['BItem']['BCategory']['category']."</td>";
                    	// 				echo "</tr>";
                    	// 			endif;

                    	// 			// create row of item
                    	// 			echo "<tr>";
                    	// 			if($item != $budgets[$i]['BNewAmount']['item_id']):	
                    	// 				echo "<td class='text-center '>".$no++."</td>";
                    	// 				echo "<td class=''>".$budgets[$i]['BItem']['item']."</td>";
                    	// 			endif;

                    	// 			foreach($departmentLists as $dept):
                    	// 				if(($budgets[$i]['BNewAmount']['department_id'] == $dept) ):
                    	// 					echo "<td class='text-center'>".$budgets[$i][0]['totalAmount']."(".$budgets[$i]['BNewAmount']['department_id'].") -".$dept."</td>";
                    	// 					$i++;
                    	// 				else:
                    	// 					echo "<td class='text-center'> NA (".$budgets[$i]['BNewAmount']['department_id'].") -".$dept."</td>";
                    	// 				endif;
                    	// 			endforeach;
                    	// 			$category = $budgets[$i]['BItem']['BCategory']['category'];
                					// $item = $budgets[$i]['BNewAmount']['item_id'];
                    	// 			echo "</tr>";
                    	// 		endfor;

                    			//trial 3
                    			$no = 1;
                    			for( $i=0; $i < count($budgets)-1; ):
                    				if($category != $budgets[$i]['BItem']['BCategory']['category']):
                    					echo "<tr class='info'>";
                    					echo "<td colspan='".$countColumn."'>".$budgets[$i]['BItem']['BCategory']['category']."</td>";
                    					echo "</tr>";
                    				endif;

                    				// create row of item
                    				echo "<tr>";
                    				if($item != $budgets[$i]['BNewAmount']['item_id']):	
                    					echo "<td class='text-center '>".$no++."</td>";
                    					echo "<td class=''>".$budgets[$i]['BItem']['item']." (".$budgets[$i]['BItem']['item_id'].")"."</td>";
                    				else:
                    					$no=1;
                    				endif;

                    				foreach($departmentLists as $dept):
                    					if(isset($budgets[$i])):
	                    					if(($budgets[$i]['BNewAmount']['department_id'] == $dept) ):
	                    						echo "<td class='text-center'>"."(curitem:".$item.")(itemid:".$budgets[$i]['BNewAmount']['item_id'].")-(deptid:".$dept.")</td>";
	                    						$category = $budgets[$i]['BItem']['BCategory']['category'];
	                							$item = $budgets[$i]['BNewAmount']['item_id'];
	                    						
	                    						$i++; //update the index to next element
	                    					else:
	                    						echo "<td class='text-center'>"."(curitem:".$item.")(itemid:".$budgets[$i]['BNewAmount']['item_id'].")-(deptid:".$dept.")</td>";
	                    					endif;
                    					else: // the last row -- index is still added 1
                    						echo "<td class='text-center'> NA </td>";
                    					endif;
                    				endforeach;
                    				echo "</tr>";
                    			endfor;
	                    	?>
	                	</tbody>
	            	</table>
	            	<?php
	            		// debug($countColumn);
	            	?>
	            </div>
            </div>
        </section>
    </div>
</section>