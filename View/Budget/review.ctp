<?php
	$encBudgetID = $this->Mems->encrypt($budgetID);

	$this->Html->addCrumb('Budget', array('controller' => 'budget', 'action' => 'index'));
	$this->Html->addCrumb($budgetDetail['Budget']['year'], array('controller' => 'budget', 'action' => 'dashboard',$encBudgetID));
	$this->Html->addCrumb('Review Budget', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
	<div class="row">
	    <div class="col-lg-12">
	        <section class="panel">
	            <header class="panel-heading">
					<strong>
						<?php echo $budgetDetail['Company']['company'] ." (".$budgetDetail['Budget']['year'].")";?>
					</strong>
					<!-- <small> ( Guided by Division/Department LOA ) </small> -->
					<span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
	                </span>  
				</header>

	            <div class="panel-body" style="width:100%">
	            	<div class="btn-group pull-right" style="margin-bottom: 20px">
						<?php
							
							if($editFlag):

								echo $this->Html->link("<button class='btn btn-default btn-sm tooltips editButton' data-toggle='tooltip' data-placement='top' data-original-title='Edit Budget'><i class='fa fa-pencil'></i> Edit</button>",array('controller'=>'budget','action'=>'request',$encBudgetID),array('escape'=>false,'class'=>'small-margin-left'));
							endif;

							if($approvalFlag):		
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
							endif;

							if($remarkFlag):
								echo $this->Html->link("<button class='btn btn-warning btn-sm tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Remark'><i class='fa fa-comment'></i> Remarks</button>",array('controller'=>'remark','action'=>'index',$encBudgetID,'budget'),array('escape'=>false,'class'=>'small-margin-left')); 
							endif;
						?>

						<?php
							echo $this->Html->link("<button class='btn btn-info btn-sm tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Compare the budget'><i class='fa fa-table'></i> Compare</button>",array('controller'=>'budget','action'=>'compare',$encBudgetID,$deptSpecific? '1':'0'),array('escape'=>false,'class'=>'small-margin-left','target'=>'_blank'));

                            $deptFlag= ($deptSpecific&&!($activeUser['role_id']==17||$activeUser['finance'])) ? '1':'0';
                            echo $this->Form->postlink("<button class='btn btn-primary btn-sm tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Download Excel'><i class='fa fa-external-link-square'></i> Excel </button>",array('controller'=>'budget','action'=>'budgetExcel',$budgetID,$deptFlag.".xlsx"),array('escape'=>false,'class'=>'small-margin-left'));
                        ?>
                        &nbsp;
                        <div class='btn-group pull-right'>
                        <?php
                        
                			echo "<button data-toggle='dropdown' class='btn btn-sm btn-primary small-margin-left dropdown-toggle' type='button'><i class='fa fa-save'></i> Download PDF <span class='caret'></span></button>";
                			echo "<ul role='menu' class='dropdown-menu'>";
                			$pdfSize = array('a4','a3','a2','a1','a0');
                			foreach($pdfSize as $size):
                    			echo "<li>".
                    					$this->Html->link(ucfirst($size),array('controller'=>'budget','action'=>'pdf',$encBudgetID,$size.".pdf")) .
                    				"</li>";
                    		endforeach;
                            echo "</ul>";
                                
        				?>
                        </div>
                    </div>
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
		                
    	            	<?php if ($deptSpecific&&!in_array($activeUser['Role']['role_id'], array(17))&&!$activeUser['finance'])://show only dept based to non admin users and not budget involved user ?>
                            <table class="table tableB table-bordered table-striped" style="width:70%;margin-left:15%; margin-right:15%; " >
                                <thead>
                                    <tr class="">
                                        <th rowspan='2' class="text-center dark-grey-bg" style="vertical-align:middle;width:40%" >Item</th>
                                        <th class="text-center dark-grey-bg" style="white-space:nowrap;width:30%">Budget Year to Date <?php echo $budgetDetail['Budget']['year'];?> </th>

                                        <?php
                                            $countColumn = 3; // including the colspan and subtotal
                                            echo "<th  style='vertical-align:middle;width:30%;white-space:nowrap' class='text-center unitar-blue-bg' > ".($activeUser['Department']['department_type']==1 ? 'ACADEMIC' : 'NON-ACADEMIC')."</th>";
                                        
                                      
                                        ?>
                                    
                                    </tr>
                                    <tr class="">
                                        
                                        <th class="text-center dark-grey-bg" style="width:auto;white-space:nowrap;vertical-align:middle"> RM </th>
                                        <?php
                                            // $deptid=$activeUser['department_id'];
                                            $deptsform=$activeUser['Department']['department_shortform'];
                                                echo "<th style='vertical-align:middle' class='text-center unitar-grey-bg' >".$deptsform."</th>";
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
                                                if(empty($bi[$i]['b_dept_id'])):    ///indicates new row,ytd
                                                    echo "<th class='' style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
                                                    echo "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                                    $item=$bi[$i]['item_id'];//set item for the whole row
                                                    
                                                    if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
                                                        $totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

                                                    $totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
                                                    
                                                    
                                                    $i++;
                                                
                                                endif;
                                                
                                                $infoFlag=false;
                                                
                                                if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$deptid]))
                                                    $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] = 0;
                                                //initiate total unbudgeted
                                                if(!isset($totalUnbudgeted[$deptid]))
                                                    $totalUnbudgeted[$deptid] = 0;
                                                //check if dept same and item same only set val
                                                if(isset($bi[$i])):
                                                    if (!($deptid==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
                                                        
                                                        echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";    
                                                    }
                                                    else{   

                                                        //deduct approved fmemobudget from balance
                                                        if(!empty($bi[$i]['FMemoBudget'])):
                                                            foreach($bi[$i]['FMemoBudget'] as $mb):
                                                                //deduct only for approved memo budget
                                                                if (!empty($mb['FMemo'])):
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
                                                                    $infoFlag=true;

                                                                    //1.deduct approved transfer amount from balance
                                                                    $bi[$i]['amount']-=$mb['transferred_amount'];
                                                                    
                                                                
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                        
                                                        $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] +=$bi[$i]['amount'];

                                                       
                                                        echo  "<td class='text-center' style='white-space:nowrap;'>";
                                                                
                                                        //info button
                                                        if ($infoFlag):
                                                            echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
                                                            
                                                            echo "&nbsp;&nbsp;";

                                                        endif;

                                                        echo number_format($bi[$i]['amount'],2,".",",")."</td>";

                                                        $i++; //update the index to next element
                                                       
                                                    }
                                                else:
                                                    echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
                                                endif;

                                                echo "</tr>";
                                                
                                            endfor;//end foritem 

                                        ?>
                                            <!-- at the end of each group display the accumulated val for each col -->
                                            <tr class="success">
                                                <td class="bold text-right" > Sub Total </td>
                                                <td class='bold text-center bigger-text'><?php echo number_format($totalByDept[$b['BItemGroup']['item_group_id']]['ytd'],2,".",",")?> </td>

                                            <?php
                                                //calculate net sales for ytd
                                                if (!isset($totAllGroup['revenue']['ytd']))
                                                    $totAllGroup['revenue']['ytd']=0;
                                                $totAllGroup['revenue']['ytd']+=$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'];
                                                
                                                    //calculate net sales for each acad dept
                                                    if (!isset($totAllGroup['revenue'][$deptid]))
                                                        $totAllGroup['revenue'][$deptid]=0;
                                                    $totAllGroup['revenue'][$deptid]+=$totalByDept[$b['BItemGroup']['item_group_id']][$deptid]; 
                                                    echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$deptid],2,".",",")."</td>";

                                                

                                            ?>
                                            </tr>

                                                
                                    <?php
                                        endforeach;//end foreach group
                                        //show total of groups if only groups exist
                                        if (!empty($budgetR)):
                                    ?>

                                    <tr class='danger'>
                                        <td class="bold text-right" > Total Revenue </td>
                                                <!-- <td class="bold text-right" colspan="4"> Sub Total </td> -->
                                        <td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['revenue']['ytd'],2,".",",")?> </td>

                                        <?php
                                            
                                                
                                            echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue'][$deptid],2,".",",")."</td>";

                                        ?>
                                            
                                    
                                    </tr>
                                    <tr class='info'>
                                        <th >NET SALES</th>
                                        <td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['revenue']['ytd'],2,".",",")?> </td>

                                        <?php
                                                
                                            echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['revenue'][$deptid],2,".",",")."</td>";

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
                                                if(empty($bi[$i]['b_dept_id'])):    ///indicates new row,ytd
                                                    echo "<th class='' style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
                                                    echo "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                                    $item=$bi[$i]['item_id'];//set item for the whole row
                                                    
                                                    if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
                                                        $totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

                                                    $totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
                                                    
                                                    
                                                    $i++;
                                                
                                                endif;
                                               
                                                $infoFlag=false;
                                                
                                                if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$deptid]))
                                                    $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] = 0;
                                                //initiate total unbudgeted
                                                if(!isset($totalUnbudgeted[$deptid]))
                                                    $totalUnbudgeted[$deptid] = 0;
                                                //check if dept same and item same only set val
                                                if(isset($bi[$i])):
                                                    if (!($deptid==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
                                                        
                                                        echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";    
                                                    }
                                                    else{   

                                                        //deduct approved fmemobudget from balance
                                                        if(!empty($bi[$i]['FMemoBudget'])):
                                                            foreach($bi[$i]['FMemoBudget'] as $mb):
                                                                //deduct only for approved memo budget
                                                                if (!empty($mb['FMemo'])):
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
                                                                    $infoFlag=true;

                                                                    //1.deduct approved transfer amount from balance
                                                                    $bi[$i]['amount']-=$mb['transferred_amount'];
                                                                    
                                                                
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                        
                                                        $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] +=$bi[$i]['amount'];

                                                       
                                                        echo  "<td class='text-center' style='white-space:nowrap;'>";
                                                                
                                                        //info button
                                                        if ($infoFlag):
                                                            echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
                                                            
                                                            echo "&nbsp;&nbsp;";

                                                        endif;

                                                        echo number_format($bi[$i]['amount'],2,".",",")."</td>";
                                                        
                                                        $i++; //update the index to next element
                                                       
                                                    }
                                                else:
                                                    echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
                                                endif;

                                                echo "</tr>";
                                                
                                            endfor;//end foritem  

                                            ?>
                                            <!-- at the end of each group display the accumulated val for each col -->
                                            <tr class="success">
                                                <td class="bold text-right" > Sub Total </td>
                                                <td class='bold text-center bigger-text'><?php echo number_format($totalByDept[$b['BItemGroup']['item_group_id']]['ytd'],2,".",",")?> </td>

                                            <?php
                                                //calculate total groups for ytd
                                                if (!isset($totAllGroup['costofrevenue']['ytd']))
                                                    $totAllGroup['costofrevenue']['ytd']=0;
                                                $totAllGroup['costofrevenue']['ytd']+=$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'];
                                                
                                                //calculate total groups for each acad dept
                                                if (!isset($totAllGroup['costofrevenue'][$deptid]))
                                                    $totAllGroup['costofrevenue'][$deptid]=0;
                                                $totAllGroup['costofrevenue'][$deptid]+=$totalByDept[$b['BItemGroup']['item_group_id']][$deptid];   
                                                echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$deptid],2,".",",")."</td>";


                                            ?>
                                            </tr>

                                                
                                    <?php
                                        endforeach;//end foreach group
                                        //show total of groups if only groups exist
                                        if (!empty($budgetCOR)):
                                    ?>

                                    <tr class='danger'>
                                        <td class="bold text-right" > Total Cost of Revenue </td>
                                                <!-- <td class="bold text-right" colspan="4"> Sub Total </td> -->
                                        <td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['costofrevenue']['ytd'],2,".",",")?> </td>

                                        <?php
                                            
                                            echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['costofrevenue'][$deptid],2,".",",")."</td>";

                                        ?>
                                            
                                    
                                    </tr>
                                    <?php endif;

                                    if (!empty($budgetR)&&!empty($budgetCOR)):
                                        $grossProfit['ytd']=$totAllGroup['revenue']['ytd']-$totAllGroup['costofrevenue']['ytd'];
                                        ?>
                                    <tr class='info'>
                                        <th >GROSS PROFIT/(LOSS)</th>
                                        <td class='bold text-center bigger-text'><?php echo number_format($grossProfit['ytd'],2,".",",")?> </td>

                                        <?php
                                        
                                                $grossProfit[$deptid]=$totAllGroup['revenue'][$deptid]-$totAllGroup['costofrevenue'][$deptid];

                                                echo "<td class='bold text-center bigger-text'>".number_format($grossProfit[$deptid],2,".",",")."</td>";


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
                                                if(empty($bi[$i]['b_dept_id'])):    ///indicates new row,ytd
                                                    echo "<th class='' style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
                                                    echo "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                                    $item=$bi[$i]['item_id'];//set item for the whole row
                                                    
                                                    if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
                                                        $totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

                                                    $totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
                                                    
                                                    
                                                    $i++;
                                                
                                                endif;
                                               
                                                $infoFlag=false;
                                                
                                                if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$deptid]))
                                                    $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] = 0;
                                                //initiate total unbudgeted
                                                if(!isset($totalUnbudgeted[$deptid]))
                                                    $totalUnbudgeted[$deptid] = 0;
                                                //check if dept same and item same only set val
                                                if(isset($bi[$i])):
                                                    if (!($deptid==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
                                                        
                                                        echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";    
                                                    }
                                                    else{   

                                                        //deduct approved fmemobudget from balance
                                                        if(!empty($bi[$i]['FMemoBudget'])):
                                                            foreach($bi[$i]['FMemoBudget'] as $mb):
                                                                //deduct only for approved memo budget
                                                                if (!empty($mb['FMemo'])):
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
                                                                    $infoFlag=true;

                                                                    //1.deduct approved transfer amount from balance
                                                                    $bi[$i]['amount']-=$mb['transferred_amount'];
                                                                    
                                                                
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                        
                                                        $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] +=$bi[$i]['amount'];

                                                       
                                                        echo  "<td class='text-center' style='white-space:nowrap;'>";
                                                                
                                                        //info button
                                                        if ($infoFlag):
                                                            echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
                                                            
                                                            echo "&nbsp;&nbsp;";

                                                        endif;

                                                        echo number_format($bi[$i]['amount'],2,".",",")."</td>";
                                                        
                                                        $i++; //update the index to next element
                                                       
                                                    }
                                                else:
                                                    echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
                                                endif;

                                                echo "</tr>";
                                                
                                            endfor;//end foritem 

                                            ?>
                                            <!-- at the end of each group display the accumulated val for each col -->
                                            <tr class="success">
                                                <td class="bold text-right" > Sub Total </td>
                                                <td class='bold text-center bigger-text'><?php echo number_format($totalByDept[$b['BItemGroup']['item_group_id']]['ytd'],2,".",",")?> </td>

                                            <?php
                                                //calculate total groups for ytd
                                                if (!isset($totAllGroup['otherincome']['ytd']))
                                                    $totAllGroup['otherincome']['ytd']=0;
                                                $totAllGroup['otherincome']['ytd']+=$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'];
                                            
                                                if (!isset($totAllGroup['otherincome'][$deptid]))
                                                    $totAllGroup['otherincome'][$deptid]=0;
                                                $totAllGroup['otherincome'][$deptid]+=$totalByDept[$b['BItemGroup']['item_group_id']][$deptid]; 
                                                echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$deptid],2,".",",")."</td>";


                                            ?>
                                            </tr>

                                                
                                    <?php
                                        endforeach;//end foreach group
                                    //show total of groups if only groups exist
                                        if (!empty($budgetOI)):
                                    ?>

                                    <tr class='danger'>
                                        <td class="bold text-right" > Total Other Income </td>
                                                <!-- <td class="bold text-right" colspan="4"> Sub Total </td> -->
                                        <td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['otherincome']['ytd'],2,".",",")?> </td>

                                        <?php
                                            
                                            echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['otherincome'][$deptid],2,".",",")."</td>";

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
                                                if(empty($bi[$i]['b_dept_id'])):    ///indicates new row,ytd
                                                    echo "<th class='' style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
                                                    echo "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                                    $item=$bi[$i]['item_id'];//set item for the whole row
                                                    
                                                    if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
                                                        $totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

                                                    $totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
                                                    
                                                    
                                                    $i++;
                                                
                                                endif;
                                               
                                                $infoFlag=false;
                                                
                                                if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$deptid]))
                                                    $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] = 0;
                                                //initiate total unbudgeted
                                                if(!isset($totalUnbudgeted[$deptid]))
                                                    $totalUnbudgeted[$deptid] = 0;
                                                //check if dept same and item same only set val
                                                if(isset($bi[$i])):
                                                    if (!($deptid==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
                                                        
                                                        echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";    
                                                    }
                                                    else{   

                                                        //deduct approved fmemobudget from balance
                                                        if(!empty($bi[$i]['FMemoBudget'])):
                                                            foreach($bi[$i]['FMemoBudget'] as $mb):
                                                                //deduct only for approved memo budget
                                                                if (!empty($mb['FMemo'])):
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
                                                                    $infoFlag=true;

                                                                    //1.deduct approved transfer amount from balance
                                                                    $bi[$i]['amount']-=$mb['transferred_amount'];
                                                                    
                                                                
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                        
                                                        $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] +=$bi[$i]['amount'];

                                                       
                                                        echo  "<td class='text-center' style='white-space:nowrap;'>";
                                                                
                                                        //info button
                                                        if ($infoFlag):
                                                            echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
                                                            
                                                            echo "&nbsp;&nbsp;";

                                                        endif;

                                                        echo number_format($bi[$i]['amount'],2,".",",")."</td>";
                                                        
                                                        $i++; //update the index to next element
                                                       
                                                    }
                                                else:
                                                    echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
                                                endif;

                                                echo "</tr>";
                                                
                                            endfor;//end foritem 

                                            ?>
                                            <!-- at the end of each group display the accumulated val for each col -->
                                            <tr class="success">
                                                <td class="bold text-right" > Sub Total </td>
                                                <td class='bold text-center bigger-text'><?php echo number_format($totalByDept[$b['BItemGroup']['item_group_id']]['ytd'],2,".",",")?> </td>

                                            <?php
                                                //calculate total groups for ytd
                                                if (!isset($totAllGroup['expenses']['ytd']))
                                                    $totAllGroup['expenses']['ytd']=0;
                                                $totAllGroup['expenses']['ytd']+=$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'];
                                                
                                                //calculate total groups for each acad dept
                                                if (!isset($totAllGroup['expenses'][$deptid]))
                                                    $totAllGroup['expenses'][$deptid]=0;
                                                $totAllGroup['expenses'][$deptid]+=$totalByDept[$b['BItemGroup']['item_group_id']][$deptid];    
                                                echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$deptid],2,".",",")."</td>";
                                                

                                            ?>
                                            </tr>

                                                
                                    <?php
                                        endforeach;//end foreach group
                                    //show total of groups if only groups exist
                                        if (!empty($budgetE)):
                                    ?>

                                    <tr class='danger'>
                                        <td class="bold text-right" > Total Expenses </td>
                                                <!-- <td class="bold text-right" colspan="4"> Sub Total </td> -->
                                        <td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['expenses']['ytd'],2,".",",")?> </td>

                                        <?php
                                            
                                            
                                                echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['expenses'][$deptid],2,".",",")."</td>";

                                        ?>
                                            
                                    
                                    </tr>
                                    <?php endif;

                                    if (!empty($budgetR)&&!empty($budgetCOR)&&!empty($budgetOI)&&!empty($budgetE)):

                                        $netProfit['ytd']=$grossProfit['ytd']+$totAllGroup['otherincome']['ytd']-$totAllGroup['expenses']['ytd'];
                                    ?>
                                    
                                    <tr class='info'>
                                        <th >NET PROFIT/(LOSS)</th>
                                        <td class='bold text-center bigger-text'><?php echo number_format($netProfit['ytd'],2,".",",")?> </td>

                                        <?php
                                            
                                                
                                                $netProfit[$deptid]=$grossProfit[$deptid]+$totAllGroup['otherincome'][$deptid]-$totAllGroup['expenses'][$deptid];

                                                echo "<td class='bold text-center bigger-text'>".number_format($netProfit[$deptid],2,".",",")."</td>";

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
                                                if(empty($bi[$i]['b_dept_id'])):    ///indicates new row,ytd
                                                    echo "<th class='' style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
                                                    echo "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                                    $item=$bi[$i]['item_id'];//set item for the whole row
                                                    
                                                    if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
                                                        $totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

                                                    $totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
                                                    
                                                    
                                                    $i++;
                                                
                                                endif;
                                               
                                                $infoFlag=false;
                                                
                                                if(!isset($totalByDept[$b['BItemGroup']['item_group_id']][$deptid]))
                                                    $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] = 0;
                                                //initiate total unbudgeted
                                                if(!isset($totalUnbudgeted[$deptid]))
                                                    $totalUnbudgeted[$deptid] = 0;
                                                //check if dept same and item same only set val
                                                if(isset($bi[$i])):
                                                    if (!($deptid==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
                                                        
                                                        echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";    
                                                    }
                                                    else{   

                                                        //deduct approved fmemobudget from balance
                                                        if(!empty($bi[$i]['FMemoBudget'])):
                                                            foreach($bi[$i]['FMemoBudget'] as $mb):
                                                                //deduct only for approved memo budget
                                                                if (!empty($mb['FMemo'])):
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
                                                                    $infoFlag=true;

                                                                    //1.deduct approved transfer amount from balance
                                                                    $bi[$i]['amount']-=$mb['transferred_amount'];
                                                                    
                                                                
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                        
                                                        $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] +=$bi[$i]['amount'];

                                                       
                                                        echo  "<td class='text-center' style='white-space:nowrap;'>";
                                                                
                                                        //info button
                                                        if ($infoFlag):
                                                            echo $this->Html->link("<button class='btn btn-round btn-default btn-xs'>&nbsp;<i class='fa fa-info'></i>&nbsp;</button>",array('controller'=>'budget','action'=>'budgetReflection',$bi[$i]['item_amount_id']),array('escape'=>false,'class'=>'small-margin-left','onclick'=>"window.open(this.href, 'Budget Reflection Details','left=20,top=20,width=900,height=700,toolbar=0,location=1,directories=0,status=0,menubar=0,resizable=1'); return false;"));
                                                            
                                                            echo "&nbsp;&nbsp;";

                                                        endif;

                                                        echo number_format($bi[$i]['amount'],2,".",",")."</td>";
                                                        
                                                        $i++; //update the index to next element
                                                       
                                                    }
                                                else:
                                                    echo "<td class='text-center' style='white-space:nowrap;'> ".number_format(0,2,".",",")."</td>";
                                                endif;

                                                echo "</tr>";
                                                
                                            endfor;//end foritem 

                                            ?>
                                            <!-- at the end of each group display the accumulated val for each col -->
                                            <tr class="success">
                                                <td class="bold text-right" > Sub Total </td>
                                                <td class='bold text-center bigger-text'><?php echo number_format($totalByDept[$b['BItemGroup']['item_group_id']]['ytd'],2,".",",")?> </td>

                                            <?php
                                                //calculate total groups for ytd
                                                if (!isset($totAllGroup['taxation']['ytd']))
                                                    $totAllGroup['taxation']['ytd']=0;
                                                $totAllGroup['taxation']['ytd']+=$totalByDept[$b['BItemGroup']['item_group_id']]['ytd'];
                                                
                                                //calculate total groups for each acad dept
                                                if (!isset($totAllGroup['taxation'][$deptid]))
                                                    $totAllGroup['taxation'][$deptid]=0;
                                                $totAllGroup['taxation'][$deptid]+=$totalByDept[$b['BItemGroup']['item_group_id']][$deptid];    
                                                echo "<td class='bold text-center bigger-text'>".number_format($totalByDept[$b['BItemGroup']['item_group_id']][$deptid],2,".",",")."</td>";

                                            ?>
                                            </tr>

                                                
                                    <?php
                                        endforeach;//end foreach group
                                    //show total of groups if only groups exist
                                        if (!empty($budgetT)):
                                    ?>

                                    <tr class='danger'>
                                        <td class="bold text-right" > Total Taxation </td>
                                                <!-- <td class="bold text-right" colspan="4"> Sub Total </td> -->
                                        <td class='bold text-center bigger-text'><?php echo number_format($totAllGroup['taxation']['ytd'],2,".",",")?> </td>

                                        <?php
                                            
                                            
                                            echo "<td class='bold text-center bigger-text'>".number_format($totAllGroup['taxation'][$deptid],2,".",",")."</td>";

                                        ?>
                                            
                                    
                                    </tr>
                                    <?php endif;?>
                                    <?php 
                                    if (!empty($budgetR)&&!empty($budgetCOR)&&!empty($budgetOI)&&!empty($budgetE)&&!empty($budgetT)):
                                        $netProfitTax['ytd']=$netProfit['ytd']-$totAllGroup['taxation']['ytd'];
                                ?>
                                    <tr class="">
                                        <td colspan="2" class="bold text-left"> UNBUDGETED ITEM </td>

                                        <?php
                                            
                                            echo "<td class='bold text-center bigger-text'>".number_format($totalUnbudgeted[$deptid],2,".",",")."</td>";
                                           
                                        ?>
                                    </tr>
                                    <tr class="info">
                                        <td  class="bold text-right"> NET PROFIT/(LOSS) AFTER TAX </td>

                                        <td class='bold text-center bigger-text'><?php echo number_format($netProfitTax['ytd'],2,".",",")?> </td>

                                        <?php
                                            
                                            
                                                
                                                $netProfitTax[$deptid]=$netProfit[$deptid]-$totAllGroup['taxation'][$deptid];

                                                echo "<td class='bold text-center bigger-text'>".number_format($netProfitTax[$deptid],2,".",",")."</td>";

                                        ?>
                                    </tr>
                                    <?php endif;?>
                                </tbody>
                               
                            </table>
                        <?php else: //show all dept budget 
                        ?>
                            <table class="table tableB table-bordered table-striped" style="width: 100%;">
                                <thead>
                                    <tr class="">
                                        <th colspan='4' rowspan='2' class="text-center dark-grey-bg" style="vertical-align:middle" >Item</th>
                                        <th class="text-center dark-grey-bg" style="white-space:nowrap">Budget Year to Date <?php echo $budgetDetail['Budget']['year'];?> </th>

                                        <?php
                                            $countColumn = 8+count($deptAcad)+count($deptNonAcad); // including the colspan and subtotal
                                            echo "<th colspan='".(count($deptAcad)+1)."' style='vertical-align:middle;white-space:nowrap' class='text-center unitar-blue-bg' >ACADEMIC</th>";
                                            echo "<th colspan='".(count($deptNonAcad)+1)."' style='vertical-align:middle;white-space:nowrap' class='text-center unitar-blue-bg' >NON-ACADEMIC</th>";
                                      
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
                                                if(empty($bi[$i]['b_dept_id'])):    ///indicates new row,ytd
                                                    echo "<th class='' colspan=4 style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
                                                    echo "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
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
                                                                            $totalUnbudgeted[$key] +=$mb['unbudgeted_amount'];
                                                                        endif;
                                                                    endforeach;
                                                                endif;

                                                                //deduct approved fmemobudgettransfer from balance
                                                                if(!empty($bi[$i]['FMemoBudgetTransfer'])):
                                                                    foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
                                                                        //deduct only for approved memo budget
                                                                        if (!empty($mb['FMemo'])):
                                                                            
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
                                                                            $totalUnbudgeted[$key] +=$mb['unbudgeted_amount'];
                                                                        endif;
                                                                    endforeach;
                                                                endif;

                                                                //deduct approved fmemobudgettransfer from balance
                                                                if(!empty($bi[$i]['FMemoBudgetTransfer'])):
                                                                    foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
                                                                        //deduct only for approved memo budget
                                                                        if (!empty($mb['FMemo'])):
                                                                            
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
                                                if(empty($bi[$i]['b_dept_id'])):    ///indicates new row,ytd
                                                    echo "<th class='' colspan=4 style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
                                                    echo "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
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
                                                                            $totalUnbudgeted[$key] +=$mb['unbudgeted_amount'];
                                                                        endif;
                                                                    endforeach;
                                                                endif;

                                                                //deduct approved fmemobudgettransfer from balance
                                                                if(!empty($bi[$i]['FMemoBudgetTransfer'])):
                                                                    foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
                                                                        //deduct only for approved memo budget
                                                                        if (!empty($mb['FMemo'])):
                                                                            
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
                                                                            $totalUnbudgeted[$key] +=$mb['unbudgeted_amount'];
                                                                        endif;
                                                                    endforeach;
                                                                endif;

                                                                //deduct approved fmemobudgettransfer from balance
                                                                if(!empty($bi[$i]['FMemoBudgetTransfer'])):
                                                                    foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
                                                                        //deduct only for approved memo budget
                                                                        if (!empty($mb['FMemo'])):
                                                                            
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
                                                if(empty($bi[$i]['b_dept_id'])):    ///indicates new row,ytd
                                                    echo "<th class='' colspan=4 style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
                                                    echo "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
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
                                                                            $totalUnbudgeted[$key] +=$mb['unbudgeted_amount'];
                                                                        endif;
                                                                    endforeach;
                                                                endif;

                                                                //deduct approved fmemobudgettransfer from balance
                                                                if(!empty($bi[$i]['FMemoBudgetTransfer'])):
                                                                    foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
                                                                        //deduct only for approved memo budget
                                                                        if (!empty($mb['FMemo'])):
                                                                            
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
                                                                            $totalUnbudgeted[$key] +=$mb['unbudgeted_amount'];
                                                                        endif;
                                                                    endforeach;
                                                                endif;

                                                                //deduct approved fmemobudgettransfer from balance
                                                                if(!empty($bi[$i]['FMemoBudgetTransfer'])):
                                                                    foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
                                                                        //deduct only for approved memo budget
                                                                        if (!empty($mb['FMemo'])):
                                                                            
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
                                                if(empty($bi[$i]['b_dept_id'])):    ///indicates new row,ytd
                                                    echo "<th class='' colspan=4 style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
                                                    echo "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
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
                                                                            $totalUnbudgeted[$key] +=$mb['unbudgeted_amount'];
                                                                        endif;
                                                                    endforeach;
                                                                endif;

                                                                //deduct approved fmemobudgettransfer from balance
                                                                if(!empty($bi[$i]['FMemoBudgetTransfer'])):
                                                                    foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
                                                                        //deduct only for approved memo budget
                                                                        if (!empty($mb['FMemo'])):
                                                                            
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
                                                                            $totalUnbudgeted[$key] +=$mb['unbudgeted_amount'];
                                                                        endif;
                                                                    endforeach;
                                                                endif;

                                                                //deduct approved fmemobudgettransfer from balance
                                                                if(!empty($bi[$i]['FMemoBudgetTransfer'])):
                                                                    foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
                                                                        //deduct only for approved memo budget
                                                                        if (!empty($mb['FMemo'])):
                                                                            
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
                                                if(empty($bi[$i]['b_dept_id'])):    ///indicates new row,ytd
                                                    echo "<th class='' colspan=4 style='white-space:nowrap;'>".$bi[$i]['Item']['code_item']."</th>";
                                                    echo "<td class='text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
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
                                                                            $totalUnbudgeted[$key] +=$mb['unbudgeted_amount'];
                                                                        endif;
                                                                    endforeach;
                                                                endif;

                                                                //deduct approved fmemobudgettransfer from balance
                                                                if(!empty($bi[$i]['FMemoBudgetTransfer'])):
                                                                    foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
                                                                        //deduct only for approved memo budget
                                                                        if (!empty($mb['FMemo'])):
                                                                            
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
                                                                            $totalUnbudgeted[$key] +=$mb['unbudgeted_amount'];
                                                                        endif;
                                                                    endforeach;
                                                                endif;

                                                                //deduct approved fmemobudgettransfer from balance
                                                                if(!empty($bi[$i]['FMemoBudgetTransfer'])):
                                                                    foreach($bi[$i]['FMemoBudgetTransfer'] as $mb):
                                                                        //deduct only for approved memo budget
                                                                        if (!empty($mb['FMemo'])):
                                                                            
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
                                ?>
                                    <tr class="">
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
                        <?php endif;?>  
		            </div>
	            </div>
	        </section>
	    </div>
	</div>
	
	<?php if (!$deptSpecific||$activeUser['role_id']==17||$activeUser['finance'])://show only dept based to non admin users and not budget involved user)://show only if not dept based ?>
	<div class="row">
		<div class="col-lg-12">

			<section class="panel">
				<header class="panel-heading">
					<strong>
						Financial Controller Approval
					</strong>
					<!-- <small> ( Guided by Division/Department LOA ) </small> -->
					<span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
	                </span>  
				</header>
				
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-3"> 
							<h5><b>Prepared by:</b></h5>
							<p>
							<?php
								echo $budgetDetail['User']['staff_name'];
								echo "<br>";
								echo $budgetDetail['User']['Department']['department_name'];
								echo "<br>";
								echo $budgetDetail['User']['designation'];
								echo "<br>";
								echo date('d F Y',strtotime($budgetDetail['Budget']['created'])); 
							?>
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
											<?php 
												if (!empty($budgetDetail['Budget']['remark'])) 
													echo ($budgetDetail['Budget']['remark']); 
											?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<!-- Reviewer remark -->
					<?php 
						if (!empty($reviewers)):
		            		$counter=0;
                            foreach ($reviewers as $reviewer):
                    ?>

                		<div class="row">
                			<div class="col-lg-3"> 

                				<h5><b><?php echo $counter+1; ?>) Reviewed by:</b></h5>
                				<p>
                					<?php
										echo $reviewer['User']['staff_name'];
										echo "<br>";
										echo $reviewer['User']['Department']['department_name'];
										echo "<br>";
										echo $reviewer['User']['designation'];
										echo "<br>";
										echo date('d F Y',strtotime($reviewer['BStatus'][0]['modified'])); 
									?>
                				</p>

                			</div>
                			<div class="col-lg-9">
                				<table class="table table-bordered table-condensed">
                					<thead>
                						<tr class="info">
                							<th style="width:5%;text-align:center">
                								<?php 
                									if ($reviewer['BStatus'][0]['status']=='approved') 
                										echo '<i class="fa fa-check"></i>' ;
                								?>
                							</th>
                							<th style="width:25%">Approved</th>
                							<th style="width:5%;text-align:center">
                								<?php 
                									if ($reviewer['BStatus'][0]['status']=='rejected') 
                										echo '<i class="fa fa-check"></i>' ;
                								?>
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
							                            	 //   		if (!empty($val['BRemark']['subject'])) 
									                          		// 	echo "<b class='bigger-text'>". $val['BRemark']['subject'] ."</b>";

									                          		// echo "<br>";

																	if (!empty($val['BRemarkFeedback'][0]['feedback'])) 
																	 	echo  $val['BRemarkFeedback'][0]['feedback'] ; 


								                          			if (count($val['BRemarkFeedback'])>1):                    				
									                          			for ($i=1;$i<count($val['BRemarkFeedback']);$i++):
									                          				echo "<div style='margin-left:10px;margin-bottom:3px; background: rgba(102,178,255,0.2);padding: 3px 10px;color:#333'>";
									                          				echo "<b>".$val['BRemarkFeedback'][$i]['User']['staff_name']."</b>";
									                          				echo "<small><em>";
									                          				echo " on ";
									                          				echo date('d F Y',strtotime($val['BRemarkFeedback'][$i]['created']));
									                          				echo "</em></small>"; 

																			if (!empty($val['BRemarkFeedback'][$i]['feedback'])) 
																				echo "<br><small>".$val['BRemarkFeedback'][$i]['feedback']."</small>"; 
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
	            			endforeach;
	                	endif; 
	                ?>
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading">
					<strong> 
						CFO Approval 
					</strong><br>
					<!-- <small> ( Guided by Corporate LOA ) </small> -->
					<span class="tools pull-right">
	                    <a href="javascript:;" class="fa fa-chevron-down"></a>
	                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
	                </span>
				</header>
				
				<div class="panel-body">
					<!-- Reviewer remark -->
					<?php 
						if (!empty($approvers)):
		            		$counter=0;
                            foreach ($approvers as $approver):
                    ?>

                		<div class="row">
                			<div class="col-lg-3"> 

                				<h5><b><?php echo $counter+1; ?>) Approved by:</b></h5>
                				<p>
                					<?php
										echo $approver['User']['staff_name'];
										echo "<br>";
										echo $approver['User']['Department']['department_name'];
										echo "<br>";
										echo $approver['User']['designation'];
										echo "<br>";
										echo date('d F Y',strtotime($approver['BStatus'][0]['modified'])); 
									?>
                				</p>

                			</div>
                			<div class="col-lg-9">
                				<table class="table table-bordered table-condensed">
                					<thead>
                						<tr class="info">
                							<th style="width:5%;text-align:center">
                								<?php 
                									if ($approver['BStatus'][0]['status']=='approved') 
                										echo '<i class="fa fa-check"></i>' ;
                								?>
                							</th>
                							<th style="width:25%">Approved</th>
                							<th style="width:5%;text-align:center">
                								<?php 
                									if ($approver['BStatus'][0]['status']=='rejected') 
                										echo '<i class="fa fa-check"></i>' ;
                								?>
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
							                          		if (!empty($remark_approver[$counter])):

								                            	foreach ($remark_approver[$counter] as $val):
							                            	 //   		if (!empty($val['BRemark']['subject'])) 
									                          		// 	echo "<b class='bigger-text'>". $val['BRemark']['subject'] ."</b>";

									                          		// echo "<br>";

																	if (!empty($val['BRemarkFeedback'][0]['feedback'])) 
																	 	echo  $val['BRemarkFeedback'][0]['feedback'] ; 


								                          			if (count($val['BRemarkFeedback'])>1):                    				
									                          			for ($i=1;$i<count($val['BRemarkFeedback']);$i++):
									                          				echo "<div style='margin-left:10px;margin-bottom:3px; background: rgba(102,178,255,0.2);padding: 3px 10px;color:#333'>";
									                          				echo "<b>".$val['BRemarkFeedback'][$i]['User']['staff_name']."</b>";
									                          				echo "<small><em>";
									                          				echo " on ";
									                          				echo date('d F Y',strtotime($val['BRemarkFeedback'][$i]['created']));
									                          				echo "</em></small>"; 

																			if (!empty($val['BRemarkFeedback'][$i]['feedback'])) 
																				echo "<br><small>".$val['BRemarkFeedback'][$i]['feedback']."</small>"; 
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
	            			endforeach;
	                	endif; 
	                ?>
                </div>
			</section>
		</div>
	</div>
	<?php endif?>

	<div aria-hidden="true" aria-labelledby="new" role="dialog" tabindex="-1" id="approval" class="modal fade">
      	<div class="modal-dialog">
          	<div class="modal-content">
              	<div class="modal-header">
                  	<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                  	<h4 class="modal-title">Approve / Reject Budget Request</h4>
              	</div>
              	<div class="modal-body">
              		<?php
              			echo $this->Form->create('BStatus',array('url'=>array('controller'=>'budget','action'=>'approveRejectBudget'),'class'=>'form-horizontal','onSubmit'=>'return confirm("Are you sure you want to approve/reject this budget request?")','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
              			echo $this->Form->hidden('BStatus.budget_id',array('value'=>$budgetID));
              		?>
                  	<div class="form-group">
                      	<label class="col-lg-2 col-sm-2 control-label">Decision</label>
                      	<div class="col-lg-10">
                          	<div class="radios">
                              	<?php
                              		$options = array("approved" => '  Approve', "rejected" => '  Reject');
									echo $this->Form->input('BStatus.status',array('type'=>'radio','options'=>$options,'separator'=>'<br>',));
                              	?>
                          	</div>
                     	</div>
                 	</div>
                      
                  	<div class="form-group">
                  		<label class="col-lg-2 col-sm-2 control-label">Remark</label>
                    	<div class="col-lg-10">
                          	<?php
                          		echo $this->Form->input('BStatus.remark',array('type'=>'textarea','class'=>'wysihtml5 form-control','rows'=>'10'));
                          	?>
                     	</div>
                  	</div>

                  	<div class="modal-footer text-center">
						<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						<?php
							echo $this->Form->button('Submit',array('type'=>'submit','class'=>'btn btn-success'));
						?>
					</div>
                   
             		<?php
             			echo $this->Form->end();
             		?>
              	</div>
          	</div>
      	</div>
    </div>

    <div aria-hidden="true" aria-labelledby="new" role="dialog" tabindex="-1" id="edit-budget" class="modal fade">
      	<div class="modal-dialog">
          	<div class="modal-content">
              	<div class="modal-header">
                  	<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                  	<h4 class="modal-title">Edit Budget Item bsdjfbsdfbsdfbiu</h4>
              	</div>
              	<div class="modal-body">
              		<?php
              			echo $this->Form->create('BNewAmount',array('url'=>array('controller'=>'budget','action'=>'editBudgetAmount'),'class'=>'form-horizontal','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
              			echo $this->Form->hidden('BNewAmount.amount_id',array('id'=>'edit-budget-amount-id'));
              		?>
                  	<div class="form-group">
	                    <label class="col-sm-4 control-label">Budget Item</label>
	                    <div class="col-sm-8">
	                        <?php
	                        	echo $this->Form->input('BNewAmount.item',array('type'=>'text','id'=>'edit-budget-item','disabled'=>'disabled','class'=>'form-control'));
	                        ?>
	                    </div>
	                </div>

	                <div class="form-group">
	                    <label class="col-sm-4 control-label">Budget Amount</label>
	                    <div class="col-sm-4">
	                        <div class="input-group">
	                          <span class="input-group-addon">RM</span>
	                          <?php
	                          	echo $this->Form->input('BNewAmount.amount',array('type'=>'text','id'=>'edit-budget-amount','class'=>'form-control'));
	                          ?>
	                        </div>
	                    </div>
	                </div>
              	</div>

              	<div class="modal-footer text-center">
					<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
					<?php
						echo $this->Form->button('Save the edit',array('type'=>'submit','class'=>'btn btn-success'));
					?>
				</div>
				<?php
					echo $this->Form->end();
				?>
          	</div>
      	</div>
    </div>
</section>

<script>
$(document).ready(function () {
  

        $(".scrollable-area").niceScroll();

    
// passing a fixedOffset param will cause the table header to stick to the bottom of this element
    $(".tableB").stickyTableHeaders({ scrollableArea: $(".mCustomScrollbar")[0], "fixedOffset": 2 });

	// js to auto select radio
	$('.approval-btn-clicked').click(function(){
    	$('#approval-approve').prop("checked",false);
    	$('#approval-reject').prop("checked",false);
    	var status = $(this).data('action');
    	if(status == 'approve'){
    		$('#BStatusStatusApproved').prop('checked',true);
    		
    	}
    	else{
    		$('#BStatusStatusRejected').prop('checked',true);
    	}
    	// alert(status);
    	// if(status)
    });

   $('#BStatusStatusApproved').click(function(){
   		return false;
   });

   $('#BStatusStatusRejected').click(function(){
   		return false;
   });
   // end js autoselect


	// if(<?php //echo $this->params->query['edit']; ?>){
	// 	$('.edit-budget-btn').show();
	// 	$('.edit-activated').removeClass('hide').show();
 //        $('.edit-adjust').removeClass('col-lg-12').addClass('col-lg-8');
	// }

    $('.edit-budget-btn').on('click',function(){
    	var amount_id = $(this).data('amount-id');
    	var item = $(this).data('item');
    	var amount = $(this).data('amount');

    	$('#edit-budget-amount-id').val(amount_id);
    	$('#edit-budget-item').val(item);
    	$('#edit-budget-amount').val(amount);
    })
});
</script>