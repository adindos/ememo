<section class="mems-content">
  <div style="margin:5px 20px;padding:10px">
    <div class="text-center">
      <?php 
        
        echo "<h4><u>Department Budget</u>";
        echo "<h4>".nl2br($budgetDetail['Company']['company'])."</h4> ";
        echo '<h4>01/01/'.$budgetDetail['Budget']['year'].' to '.'31/12/'.$budgetDetail['Budget']['year']."</h4>";
      ?> 
    </div>
    
  </div>                  
 
  <table class="table table-bordered table-striped" style="width:70%;margin-left:15%; margin-right:15%; " >
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
                  $deptid=$activeUser['department_id'];
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
                      if(empty($bi[$i]['department_id'])):    ///indicates new row,ytd
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
                          if (!($deptid==$bi[$i]['department_id']&&$item==$bi[$i]['item_id'])){
                              
                              $bi[$i]['amount']=0;
                              echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";    
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
                                          if (!empty($mb['adjusted_amount'])):
                                              //
                                              $bi[$i]['amount']+=$mb['adjusted_amount'];
                                              //if no transferred_item_amount_id means unbudgeted,so add to accumulated unbudgeted
                                              if (empty($mb['transferred_item_amount_id']))
                                                  $totalUnbudgeted[$deptid] +=$mb['adjusted_amount'];
                                              
                                          endif;
                                          
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
                                          $bi[$i]['amount']-=$mb['adjusted_amount'];
                                          
                                      
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
                      if(empty($bi[$i]['department_id'])):    ///indicates new row,ytd
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
                          if (!($deptid==$bi[$i]['department_id']&&$item==$bi[$i]['item_id'])){
                              
                              $bi[$i]['amount']=0;
                              echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";    
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
                                          if (!empty($mb['adjusted_amount'])):
                                              //
                                              $bi[$i]['amount']+=$mb['adjusted_amount'];
                                              //if no transferred_item_amount_id means unbudgeted,so add to accumulated unbudgeted
                                              if (empty($mb['transferred_item_amount_id']))
                                                  $totalUnbudgeted[$deptid] +=$mb['adjusted_amount'];
                                              
                                          endif;
                                          
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
                                          $bi[$i]['amount']-=$mb['adjusted_amount'];
                                          
                                      
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
                      if(empty($bi[$i]['department_id'])):    ///indicates new row,ytd
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
                          if (!($deptid==$bi[$i]['department_id']&&$item==$bi[$i]['item_id'])){
                              
                              $bi[$i]['amount']=0;
                              echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";    
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
                                          if (!empty($mb['adjusted_amount'])):
                                              //
                                              $bi[$i]['amount']+=$mb['adjusted_amount'];
                                              //if no transferred_item_amount_id means unbudgeted,so add to accumulated unbudgeted
                                              if (empty($mb['transferred_item_amount_id']))
                                                  $totalUnbudgeted[$deptid] +=$mb['adjusted_amount'];
                                              
                                          endif;
                                          
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
                                          $bi[$i]['amount']-=$mb['adjusted_amount'];
                                          
                                      
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
                      if(empty($bi[$i]['department_id'])):    ///indicates new row,ytd
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
                          if (!($deptid==$bi[$i]['department_id']&&$item==$bi[$i]['item_id'])){
                              
                              $bi[$i]['amount']=0;
                              echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";    
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
                                          if (!empty($mb['adjusted_amount'])):
                                              //
                                              $bi[$i]['amount']+=$mb['adjusted_amount'];
                                              //if no transferred_item_amount_id means unbudgeted,so add to accumulated unbudgeted
                                              if (empty($mb['transferred_item_amount_id']))
                                                  $totalUnbudgeted[$deptid] +=$mb['adjusted_amount'];
                                              
                                          endif;
                                          
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
                                          $bi[$i]['amount']-=$mb['adjusted_amount'];
                                          
                                      
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
                      if(empty($bi[$i]['department_id'])):    ///indicates new row,ytd
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
                          if (!($deptid==$bi[$i]['department_id']&&$item==$bi[$i]['item_id'])){
                              
                              $bi[$i]['amount']=0;
                              echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";    
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
                                          if (!empty($mb['adjusted_amount'])):
                                              //
                                              $bi[$i]['amount']+=$mb['adjusted_amount'];
                                              //if no transferred_item_amount_id means unbudgeted,so add to accumulated unbudgeted
                                              if (empty($mb['transferred_item_amount_id']))
                                                  $totalUnbudgeted[$deptid] +=$mb['adjusted_amount'];
                                              
                                          endif;
                                          
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
                                          $bi[$i]['amount']-=$mb['adjusted_amount'];
                                          
                                      
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
     
      </tbody>
      <tfoot>
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
      </tfoot> 
      
  </table>
</section>