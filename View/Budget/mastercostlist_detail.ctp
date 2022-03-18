<?php
	$this->Html->addCrumb('Budget', array('controller' => 'budget', 'action' => 'index'));
	$this->Html->addCrumb('Master Cost List', array('controller' => 'budget', 'action' => 'mastercostlist'));
	$this->Html->addCrumb('Details', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
	<div class="row">
	    <div class="col-lg-12">
	        <section class="panel">
	            <header class="panel-heading">
	                Master Cost List
	                <span class="tools pull-right">
	                    <a class="fa fa-chevron-down" href="javascript:;"></a>
	                </span>
	            </header>

	            <div class="panel-body">
	            	<div style="margin:5px 30px;padding:10px">
		            	<div class="pull-left">
		            		<?php 
		            			echo "<small> Master cost list of <strong>". $mclsCompany['Company']['company'] . "</strong> for year <strong>".$mclsYear ."</strong></small>";
		            		?> 
		            	</div>
		            	<div class="pull-right margin-right">
		            		<?php
		            			echo $this->Html->link('<i class="fa fa-chevron-left"></i> Back to main',array('controller'=>'budget','action'=>'mastercostlist'),array('escape'=>false,'class'=>'btn btn-sm btn-round btn-default margin-right'));

		            			echo $this->Form->postlink('<i class="fa fa-external-link-square"></i> Download Excel',array('controller'=>'budget','action'=>'mastercostlist',$mclsCompany['Company']['company_id'],$mclsQuarter,$mclsYear,'excel',$mclsQuarter."-".$mclsYear.'.xlsx'),array('escape'=>false,'class'=>'btn btn-sm btn-round btn-info margin-right'));

		            			echo "<div class='btn-group'>";
		            			echo "<button data-toggle='dropdown' class='btn btn-sm btn-round btn-primary margin-right dropdown-toggle' type='button'><i class='fa fa-save'></i> Download PDF <span class='caret'></span></button>";
		            			echo "<ul role='menu' class='dropdown-menu'>";
		            			$pdfSize = array('a4','a3','a2','a1');
		            			foreach($pdfSize as $size):
			            			echo "<li>".
			            					$this->Html->link(ucfirst($size),array('controller'=>'budget','action'=>'mastercostlist',$mclsCompany['Company']['company_id'],$mclsQuarter,$mclsYear,'pdf',$size."_".$mclsQuarter."-".$mclsYear.".pdf")) .
			            				"</li>";
			            		endforeach;
	                            echo "</ul>";
	                            echo "</div>";
		            			// echo $this->Html->link('<i class="fa fa-save"></i> Download PDF',array('controller'=>'budget','action'=>'mastercostlist',$mclsCompany['Company']['company_id'],$mclsYear,'print'),array('escape'=>false,'class'=>'btn btn-sm btn-round btn-primary margin-right'));
		            		?>
		            	</div>
		            </div>
	            	<div class="clear"></div>
	            	<div class="mCustomScrollbar" style="height: 68vh;padding : 10px 30px;">
		                <table class="table table-bordered table-striped">
		                    <thead>
		                        <tr class="">
		                            <th colspan="2" class="text-center dark-grey-bg" style="white-space:nowrap">Department</th>
		                            <?php
		                            	$countColumn = 3; // including the colspan and subtotal
		                            	foreach($groups as $group):
		                            		echo "<th colspan='".count($group['Department'])."' class='text-center unitar-blue-bg' style='white-space:nowrap'>".$group['Group']['group_name']."</th>";
		                            		$countColumn += count($group['Department']);
		                        		endforeach;
		                        	?>
		                        	<th rowspan="2" class="col-lg-1 text-center dark-grey-bg" style="vertical-align:middle">Total</th>
		                        </tr>
		                        <tr class="">
		                        	<th colspan="2" class="text-center dark-grey-bg" style="width:auto;white-space:nowrap;vertical-align:top">Description of Income <br> Statement Item</th>
		                        	<?php
		                        		$departmentLists = array();
		                        		foreach($groups as $group):
		                        			foreach($group['Department'] as $department):
		                        				echo "<th class='text-center unitar-grey-bg' style='width:auto'>".$department['department_name']."</th>";
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
	                    					echo "<tr class='info'>";
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
	                    				echo "<td class='text-center bold warning'>".number_format($totalByItem,2,".",",")."</td>";
	                    				echo "</tr>";
	                    			endfor;

	                    			// debug($totalByDept);
		                    	?>
		                	</tbody>
		                	<tfoot>
		                		<tr class="success">
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
	            </div>
	        </section>
	    </div>
	</div>
</section>