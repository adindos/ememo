<section class="mems-content">
  <div style="margin:5px 20px;padding:10px">
    <div class="text-center">
      <?php 
        
        echo "<h4><u>Originally Approved Budget (Before Financial Memo and Unbudgeted Item Calculation)</u></h4> ";
        echo "<h4>".nl2br($budgetDetail['Company']['company'])."</h4> ";
        echo '<h4>01/01/'.$budgetDetail['Budget']['year'].' to '.'31/12/'.$budgetDetail['Budget']['year']."</h4>";
      ?> 
    </div>
    
  </div>                  
  <?php if ($deptSpecific&&!in_array($activeUser['Role']['role_id'], array(17))&&!$activeUser['finance'])://show only dept based to non admin users and not budget involved user ?>
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
                                  echo "<td class='bold text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                  $item=$bi[$i]['item_id'];//set item for the whole row
                                  
                                  if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
                                      $totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

                                  $totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
                                  
                                  
                                  $i++;
                              
                              endif;
                             
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

                                      
                                      
                                      $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] +=$bi[$i]['amount'];

                                     
                                      echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";

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
                                  echo "<td class='bold text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                  $item=$bi[$i]['item_id'];//set item for the whole row
                                  
                                  if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
                                      $totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

                                  $totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
                                  
                                  
                                  $i++;
                              
                              endif;
                             
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

                                      
                                      
                                    
                                      $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] +=$bi[$i]['amount'];

                                     
                                      echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";

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
                                  echo "<td class='bold text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                  $item=$bi[$i]['item_id'];//set item for the whole row
                                  
                                  if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
                                      $totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

                                  $totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
                                  
                                  
                                  $i++;
                              
                              endif;
                             
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

                                      
                                      
                                    
                                      
                                      $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] +=$bi[$i]['amount'];

                                     
                                      echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";

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
                                  echo "<td class='bold text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                  $item=$bi[$i]['item_id'];//set item for the whole row
                                  
                                  if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
                                      $totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

                                  $totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
                                  
                                  
                                  $i++;
                              
                              endif;
                             
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

                                      
                                      
                                    
                                      
                                      $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] +=$bi[$i]['amount'];

                                     
                                      echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";

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
                                  echo "<td class='bold text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                  $item=$bi[$i]['item_id'];//set item for the whole row
                                  
                                  if (!isset($totalByDept[$b['BItemGroup']['item_group_id']]['ytd']))
                                      $totalByDept[$b['BItemGroup']['item_group_id']]['ytd']=0;

                                  $totalByDept[$b['BItemGroup']['item_group_id']]['ytd'] +=$bi[$i]['amount'];
                                  
                                  
                                  $i++;
                              
                              endif;
                             
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

                                      
                                      
                                    
                                      
                                      $totalByDept[$b['BItemGroup']['item_group_id']][$deptid] +=$bi[$i]['amount'];

                                     
                                      echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";

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
          <table class="table table-bordered table-striped" style="width: 100%;">
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
                                  echo "<td class='bold text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
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

                                              
                                              
                                              $totalByItem += $bi[$i]['amount']; //total by item
                                              $totalByAcad += $bi[$i]['amount']; //total by item for acad
                                              
                                              $totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];

                                             
                                              echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";

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

                                   echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByAcad,2,".",",")."</td>";
                                  //iterate thru acad dept first n set the val
                                  
                                  foreach($deptNonAcad as $key => $dept):
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
                                              
                                              
                                              $totalByItem += $bi[$i]['amount']; //total by item
                                              $totalByNonAcad += $bi[$i]['amount']; //total by item for acad
                                              
                                              $totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
                                              
                                              echo "<td class='text-center' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                             
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
                                  echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByNonAcad,2,".",",")."</td>";
                                  echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByItem,2,".",",")."</td>";

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
                                  echo "<td class='bold text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
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

                                              
                                              
                                              $totalByItem += $bi[$i]['amount']; //total by item
                                              $totalByAcad += $bi[$i]['amount']; //total by item for acad
                                              
                                              $totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];

                                             
                                              echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";

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

                                   echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByAcad,2,".",",")."</td>";
                                  //iterate thru acad dept first n set the val
                                  
                                  foreach($deptNonAcad as $key => $dept):
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
                                              
                                              
                                              $totalByItem += $bi[$i]['amount']; //total by item
                                              $totalByNonAcad += $bi[$i]['amount']; //total by item for acad
                                              
                                              $totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
                                              
                                              echo "<td class='text-center' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                             
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
                                  echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByNonAcad,2,".",",")."</td>";
                                  echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByItem,2,".",",")."</td>";

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
                                  echo "<td class='bold text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
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

                                              
                                              
                                              $totalByItem += $bi[$i]['amount']; //total by item
                                              $totalByAcad += $bi[$i]['amount']; //total by item for acad
                                              
                                              $totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];

                                             
                                              echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";

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

                                   echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByAcad,2,".",",")."</td>";
                                  //iterate thru acad dept first n set the val
                                  
                                  foreach($deptNonAcad as $key => $dept):
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
                                              
                                              
                                              $totalByItem += $bi[$i]['amount']; //total by item
                                              $totalByNonAcad += $bi[$i]['amount']; //total by item for acad
                                              
                                              $totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
                                              
                                              echo "<td class='text-center' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                             
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
                                  echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByNonAcad,2,".",",")."</td>";
                                  echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByItem,2,".",",")."</td>";

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
                                  echo "<td class='bold text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
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

                                              
                                              
                                              $totalByItem += $bi[$i]['amount']; //total by item
                                              $totalByAcad += $bi[$i]['amount']; //total by item for acad
                                              
                                              $totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];

                                             
                                              echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";

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

                                   echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByAcad,2,".",",")."</td>";
                                  //iterate thru acad dept first n set the val
                                  
                                  foreach($deptNonAcad as $key => $dept):
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
                                              
                                              
                                              $totalByItem += $bi[$i]['amount']; //total by item
                                              $totalByNonAcad += $bi[$i]['amount']; //total by item for acad
                                              
                                              $totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
                                              
                                              echo "<td class='text-center' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                             
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
                                  echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByNonAcad,2,".",",")."</td>";
                                  echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByItem,2,".",",")."</td>";

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
                                  echo "<td class='bold text-center dark-grey-bg' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
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

                                              
                                              
                                              $totalByItem += $bi[$i]['amount']; //total by item
                                              $totalByAcad += $bi[$i]['amount']; //total by item for acad
                                              
                                              $totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];

                                             
                                              echo "<td class='text-center' style='white-space:nowrap;'> ".number_format($bi[$i]['amount'],2,".",",")."</td>";

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

                                  echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByAcad,2,".",",")."</td>";
                                  //iterate thru acad dept first n set the val
                                  
                                  foreach($deptNonAcad as $key => $dept):
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
                                              
                                              
                                              $totalByItem += $bi[$i]['amount']; //total by item
                                              $totalByNonAcad += $bi[$i]['amount']; //total by item for acad
                                              
                                              $totalByDept[$b['BItemGroup']['item_group_id']][$key] +=$bi[$i]['amount'];
                                              
                                              echo "<td class='text-center' style='white-space:nowrap;'>".number_format($bi[$i]['amount'],2,".",",")."</td>";
                                             
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
                                  echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByNonAcad,2,".",",")."</td>";
                                  echo "<td class='bold text-center dark-grey-bg'>".number_format($totalByItem,2,".",",")."</td>";

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
                  <?php endif; ?>
              </tbody>
          </table>
      <?php endif;?>  
</section>

<script type="text/javascript">
$(document).ready(function(){

  $("table").stickyTableHeaders();
  
});

</script>