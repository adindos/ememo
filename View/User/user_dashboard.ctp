<!-- Je Suis JavaScript -->

<!-- Functions to make the counter effect on the dashboard counters-->
<!-- Last modified @ Faridi 24/Feb-->
<script>

    // // Budget counter animation
    // function countUp(count)
    // {
    //     var div_by = 100,
    //     speed = Math.round(count / div_by),
    //     $display = $('.count'),
    //     run_count = 1,
    //     int_speed = 24;

    //     var int = setInterval(function() {
    //         if(run_count < div_by){
    //             $display.text(speed * run_count);
    //             run_count++;
    //         } else if(parseInt($display.text()) < count) {
    //             var curr_count = parseInt($display.text()) + 1;
    //             $display.text(curr_count);
    //         } else {
    //             clearInterval(int);
    //         }
    //     }, int_speed);
    // }    

    // // Financial Memo counter animation
    // function countUp2(count)
    // {
    //     var div_by = 100,
    //     speed = Math.round(count / div_by),
    //     $display = $('.count2'),
    //     run_count = 1,
    //     int_speed = 24;

    //     var int = setInterval(function() {
    //         if(run_count < div_by){
    //             $display.text(speed * run_count);
    //             run_count++;
    //         } else if(parseInt($display.text()) < count) {
    //             var curr_count = parseInt($display.text()) + 1;
    //             $display.text(curr_count);
    //         } else {
    //             clearInterval(int);
    //         }
    //     }, int_speed);
    // }    


    // // Non Financial counter animation
    // function countUp3(count)
    // {
    //     var div_by = 100,
    //     speed = Math.round(count / div_by),
    //     $display = $('.count3'),
    //     run_count = 1,
    //     int_speed = 24;

    //     var int = setInterval(function() {
    //         if(run_count < div_by){
    //             $display.text(speed * run_count);
    //             run_count++;
    //         } else if(parseInt($display.text()) < count) {
    //             var curr_count = parseInt($display.text()) + 1;
    //             $display.text(curr_count);
    //         } else {
    //             clearInterval(int);
    //         }
    //     }, int_speed);
    // }    
    </script>




<!-- Le Main View Boday -->

<!-- Functions to make the counter effect on the dashboard counters-->
<!-- Last modified @ Faridi 2/April-->

    <section class="mems-content">
       <div class="row">
          <aside class="profile-nav col-lg-4">
             <section class="panel">
                <div class="user-heading grey-bg">
                   <a href="#" class="round">
                     <?php
                     echo $this->Html->Image('Faces_Users-13.png');
                     ?>
                 </a>
                 <h1> <?php echo $userInfo['staff_name'] ?> </h1>
                 <h5> <?php echo $userInfo['designation']?> </h5>
                 <h5> <?php echo $userInfo['Department']['department_name'] ?> </h5>
                 <!-- <p>  <?php echo $userInfo['email']?> </p> -->

             </div>

             

         </section>
     </aside>
     <section class="col-lg-8">
       <div class="noti">
        		<!--<div class="alert alert-info fade in">
	                 <strong>Attention!</strong> You have to respond to a memo / budget. 
                </div> -->
            </div>
            <div class="row state-overview">
              <div class="col-lg-4">
                 <section class="panel">
                    <div class="symbol bg-primary">
                       <i class="fa fa-money"></i>
                   </div>
                   <div class="value">
                       <h1 class="count">
                          <?php echo $budgetCount ?>
                          <script>           
                          //countUp(<?php echo $budgetCount ?>);
                          </script>
                      </h1>
                      <p>Budget</p>
                  </div>
              </section>
          </div>
          <div class="col-lg-4">
             <section class="panel">
                <div class="symbol bg-primary">
                   <i class="fa fa-tags"></i>
               </div>
               <div class="value">
                   <h1 class=" count2">
                      <?php echo $financialCount ?>
                      <script>           
                      //countUp2(<?php echo $financialCount ?>);
                      </script>
                  </h1>
                  <p>Financial Memo</p>
              </div>
          </section>
      </div>
      <div class="col-lg-4">
         <section class="panel">
            <div class="symbol bg-primary">
               <i class="fa fa-barcode"></i>
           </div>
           <div class="value">
               <h1 class=" count3">
                  <?php echo $xfinancialCount ?>
                  <script>           
                  //countUp3(<?php echo $xfinancialCount ?>);
                  </script>
              </h1>
              <p>Non-Financial Memo</p>
          </div>
      </section>
  </div>
</div>

<div class="row">
 <div class="col-lg-8">

    <?php

    //debug($userInfo);exit;
        
        //phase 2-only finance admin/admin can create new budget
        if(in_array($activeUser['Role']['role_id'], array(17))){

            echo $this->Html->link('<i class="fa fa-money"></i> Create New Budget',array('controller'=>'Budget','action'=>'index'),array('escape'=>false,'class'=>'bold btn btn-lg btn-block bg-primary', 'style' => 'height: 50' ));
        }
    ?>

    <?php
        //phase 2:disable memo creation if memo access is disabled
        if (!$setting['Setting']['financial_memo']):
          if($userInfo['requestor']&&$userInfo['Role']['my_request_financial_memo']==1){

          echo $this->Html->link('<i class="fa fa-money"></i> Create New Financial Memo',array('controller'=>'fMemo','action'=>'index'),array('escape'=>false,'class'=>'bold btn btn-lg btn-block bg-primary', 'style' => 'height: 50' ));
          
          }
        endif;
    ?>


    <?php
        //phase 2:disable memo creation if memo access is disabled
        if (!$setting['Setting']['nonfinancial_memo']):
         if($userInfo['requestor']&&$userInfo['Role']['my_request_memo']==1){

            echo $this->Html->link('<i class="fa fa-money"></i> Create New Non-Financial Memo',array('controller'=>'NfMemo2','action'=>'index'),array('escape'=>false,'class'=>'bold btn btn-lg btn-block bg-primary', 'style' => 'height: 50' ));

            }
        endif;
    ?>
 </div>
 <!-- exchange -->
 <div class="col-lg-4">
  <!--state overview start-->
    <div class="row state-overview">
    <div class="col-lg-12 col-sm-12">
        <section class="panel">
          <div class="symbol yellow">
            <i class="fa fa-dollar"></i><span style="color:white;font-size: 13px"> USD</span><br>
            <small style="color: #ddaf08">Exchange rates (current)</small>
          </div>
          <div class="value">
            <?php 
          // set API Endpoint and access key (and any options of your choice)
            $endpoint = 'live';
            $access_key = '714b2191033ba3d54fad1c83bf712173';

          // Initialize CURL:
            $ch = curl_init('http://apilayer.net/api/'.$endpoint.'?access_key='.$access_key.'');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          // Store the data:
            $json = curl_exec($ch);
            curl_close($ch);

          // Decode JSON response:
            $exchangeRates = json_decode($json, true);

          // Access the exchange rate values, e.g. GBP:
          // echo "Malaysia Ringgit Exchange to USD($1 USD)".round($exchangeRates['quotes']['USDMYR'],2);

            ?>
            <h1 class="count" style="color:#e7c418">
              <?php 
               echo "".round($exchangeRates['quotes']['USDMYR'],2);
               ?>
            </h1>
            <p style="color:#e7c418">Malaysia Ringgit (MYR)</p>
            
          </div>
        </section>
      </div>  
    </div>
    <!--state overview end-->
 </div>
 </div>


</section>
</div>


<hr>
<div id="my-budget-memo" class="my-section text-center margin-bottom">
    <?php
    echo $this->Html->link('MY PENDING BUDGET & MEMO FOR REVIEW','#',array('escape'=>false,'class'=>'btn btn-lg bg-primary btn-round'));
    ?>
    <button  type="button" data-original-title="Description" 
             data-content="<div>This section shows the latest budget and memos that are pending for the user's review </div>"     
             data-placement="top" data-trigger="hover" class="btn btn-round bg-primary btn-xs popovers-with-html"><i class="fa fa-question-circle"></i>
        </button>
</div>
<!-- My Financial and Non-Financial Memo -->
<?php
    if($userInfo['Role']['request_management_financial_memo']==1):
?>
<div class="row">
    
    <!-- Financial Memo -->
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading blue-bg">
                <strong> Review Financial Memo </strong>
                <small>(Showing maximum 5 memos) </small>
                <span class="pull-right">
                   <?php
                   echo $this->Html->link('See All',array('controller'=>'fMemo','action'=>'myReview'),array('class'=>'btn bg-blue btn-xs'));
                   ?>
               </span>
           </header>
           <table class="table table-hover personal-task">
            <thead>
                <tr>
                    <th class="text-left">No</th>
                    <th class="text-left">Subject</th>
                    <th class="text-center">Ref. No</th>
                    <th class="text-center">Department</th>
                    <th class="text-center">Requestor</th>
                    <th class="text-center">Progress</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php


                if (!empty($financialmemos)){
                  // debug($financialmemos);exit;
                    $counter = 0;
                    foreach ($financialmemos as $key => $financialmemo) {

                     ?>
                     <tr>
                        <td> <?php echo ++$counter ?></td>
                        <td>
                            <?php 
                                echo $this->Html->link($financialmemo['FMemo']['subject'],array('controller'=>'FMemo','action'=>'dashboard',$this->Mems->encrypt($financialmemo['FMemo']['memo_id'])),array('escape'=>false)); ?>
                            <br>
                            <small>
                                <?php
                                    $created = date('d-F-Y',strtotime($financialmemo['FMemo']['created']));
                                   
                                    echo "Created on : ".$created;
                                   
                                ?>
                            </small>
                        </td>
                        <td class="text-center">
                          <?php
                            echo $financialmemo['FMemo']['ref_no'];
                          ?>
                        </td>
                        <td class="text-center">
                          <?php
                            echo $financialmemo['FMemo']['Department']['department_name'];
                          ?>
                        </td>
                        <td class="text-center">
                          <?php
                            echo $financialmemo['FMemo']['User']['staff_name'];
                          ?>
                        </td>
                        <td class="p-progress text-center">

                        <?php
                            // check the status and progress
                            $totalStatus = 0;
                            $countApproved =0;
                            $currentStatus = 'Active';
                            foreach($financialmemo['FMemo']['FStatus'] as $fstatus):
                              if($fstatus['submission_no'] == $financialmemo['FMemo']['submission_no']):
                                $totalStatus++; //update total for current submission
                                if($fstatus['status'] == 'approved'):
                                  $countApproved++;
                                endif;

                                if($fstatus['status'] == 'rejected'):
                                  $currentStatus = 'Rejected';
                                endif;
                              endif;
                            endforeach;

                            if($totalStatus == 0)
                              $progress = 0;
                            else
                              $progress = round($countApproved/$totalStatus,2) * 100;
                            
                            if($progress < 40)
                              echo "<span class='badge bg-important'>{$progress}%</span>";
                            elseif($progress < 75)
                              echo "<span class='badge bg-warning'>{$progress}%</span>";
                            else
                              echo "<span class='badge bg-primary'>{$progress}%</span>";
                          ?>

                      </td>
                      <td class="text-center">
                          <?php
                            if($progress == 100)
                              echo "<span class='label label-success'> Approved </span>";
                            elseif($totalStatus == 0)
                              echo "<span class='label label-default'><span class='fa fa-exclamation-circle'></span> Incomplete </span>";
                            elseif($currentStatus == 'Active')
                              echo "<span class='label label-warning'> {$currentStatus} </span>";
                            else
                              echo "<span class='label label-danger'> {$currentStatus} </span>";
                          ?>
                      </td>
                      <td class="text-center">
                            <!-- <div id="work-progress1"></div> -->
                            
                            <?php
                            echo $this->Html->link('Dashboard',array('controller'=>'fMemo','action'=>'dashboard',$this->Mems->encrypt($financialmemo['FMemo']['memo_id'])),array('escape'=>false,'class'=>'btn btn-xs bg-primary tooltips','data-toggle'=>'tooltips','data-original-title'=>'Dashboard'));
                            ?>


                        </td>
                    </tr>

                    <?php }
                }
                else { ?>

                <tr>
                 <td colspan="4" class="text-center">
                  You don't have any pending financial memo to act now. <br/>

                  <?php
                  echo $this->Html->link(' Request Management (Financial Memo)',array('controller'=>'fMemo','action'=>'myReview'),array('escape'=>false,'class'=>'btn btn-xs bg-primary btn-round margin-top','data-toggle'=>'tooltips','data-original-title'=>'Request Management (Financial Memo)'));
                  ?>
                </td>

              </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
    </div>

</div>
<?php
    endif;
    if($userInfo['Role']['request_management_memo']==1):
?>
<div class = "row">
<!-- Non Financial Memo -->
  <div class="col-lg-12">
      <section class="panel">
          <header class="panel-heading blue-bg">
              <strong> Review Non-Financial Memo </strong>
              <small>(Showing maximum 5 memos) </small>
              <span class="pull-right">
                  <?php
                  echo $this->Html->link('See All',array('controller'=>'NfMemo2','action'=>'myReview'),array('class'=>'btn bg-blue btn-xs'));
                  ?>
              </span>
          </header>
          <table class="table table-hover personal-task">
           <thead>
               <tr>
                   <th class="text-left">No</th>
                    <th class="text-left">Subject</th>
                    <th class="text-center">Ref. No</th>
                    <th class="text-center">Department</th>
                    <th class="text-center">Requestor</th>
                   <th class="text-center">Progress</th>
                   <th class="text-center">Status</th>
                   <th class="text-center">Action</th>
               </tr>
           </thead>
           <tbody>
               <?php 

               if (!empty($xfinancialmemos)){

                   $counter = 0;
                   foreach ($xfinancialmemos as $key => $xfinancialmemo) {




                    ?>
                    <tr>
                       <td> <?php echo ++$counter ?></td>
                       <td>
                           <?php 
                                echo $this->Html->link($xfinancialmemo['NfMemo']['subject'],array('controller'=>'NfMemo2','action'=>'dashboard',$this->Mems->encrypt($xfinancialmemo['NfMemo']['memo_id'])),array('escape'=>false)); ?>
                           <br>
                            <small>
                                <?php
                                    $created = date('d-F-Y',strtotime($xfinancialmemo['NfMemo']['created']));
                                   
                                    echo "Created on : ".$created;
                                   
                                ?>
                            </small>
                       </td>
                        <td class="text-center">
                          <?php
                            echo $xfinancialmemo['NfMemo']['ref_no'];
                          ?>
                        </td>
                        <td class="text-center">
                          <?php
                            echo $xfinancialmemo['NfMemo']['Department']['department_name'];
                          ?>
                        </td>
                        <td class="text-center">
                          <?php
                            echo $xfinancialmemo['NfMemo']['User']['staff_name'];
                          ?>
                        </td>
                       <td class="p-progress text-center">
                         <?php
                            // check the status and progress
                            $totalStatus = 0;
                            $countApproved =0;
                            $currentStatus = 'Active';
                            foreach($xfinancialmemo['NfMemo']['NfStatus'] as $xfstatus):
                              if($xfstatus['submission_no'] == $xfinancialmemo['NfMemo']['submission_no']):
                                $totalStatus++; //update total for current submission
                                if($xfstatus['status'] == 'approved'):
                                  $countApproved++;
                                endif;

                                if($xfstatus['status'] == 'rejected'):
                                  $currentStatus = 'Rejected';
                                endif;
                              endif;
                            endforeach;

                            if($totalStatus == 0)
                              $progress = 0;
                            else
                              $progress = round($countApproved/$totalStatus,2) * 100;
                            
                            if($progress < 40)
                              echo "<span class='badge bg-important'>{$progress}%</span>";
                            elseif($progress < 75)
                              echo "<span class='badge bg-warning'>{$progress}%</span>";
                            else
                              echo "<span class='badge bg-primary'>{$progress}%</span>";
                          ?>

                      </td>
                      <td class="text-center">
                          <?php
                            if($progress == 100)
                              echo "<span class='label label-success'> Approved </span>";
                            elseif($totalStatus == 0)
                              echo "<span class='label label-default'><span class='fa fa-exclamation-circle'></span> Incomplete </span>";
                            elseif($currentStatus == 'Active')
                              echo "<span class='label label-warning'> {$currentStatus} </span>";
                            else
                              echo "<span class='label label-danger'> {$currentStatus} </span>";
                          ?>
                      </td>

                    <td class="text-center">
                          <?php
                          echo $this->Html->link('Dashboard',array('controller'=>'NfMemo2','action'=>'dashboard',$this->Mems->encrypt($xfinancialmemo['NfMemo']['memo_id'])),array('escape'=>false,'class'=>'btn btn-xs bg-primary tooltips','data-toggle'=>'tooltips','data-original-title'=>'Dashboard'));
                          ?>
                      </td>
                  </tr>

                  <?php }} else { ?>

                  <tr>
                    <td colspan="4" class="text-center">
                     You don't have any pending non-financial memo to act now. <br/>

                     <?php
                     echo $this->Html->link('Request Management (Non-Financial Memo)',array('controller'=>'NfMemo2','action'=>'myReview'),array('escape'=>false,'class'=>'btn btn-xs bg-primary btn-round margin-top','data-toggle'=>'tooltips','data-original-title'=>'Request Management (Non-Financial Memo)'));
                     ?>
                   </td>
               </tr>
               <?php } ?>
           </tbody>
       </table>
   </section>
  </div>
</div>

<?php
    endif;
    if($userInfo['Role']['request_management_budget']==1):
?>
<div class="row">
    <!-- Budget -->
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading blue-bg">
                <strong> Review Budget </strong>
                <small>(Showing maximum 5 budgets) </small>

                <span class="pull-right">
                   <?php
                   echo $this->Html->link('See All',array('controller'=>'Budget','action'=>'myreview'),array('class'=>'btn bg-blue btn-xs'));
                   ?>
               </span>
           </header>
           <table class="table table-hover personal-task">
            <thead>
                <tr>
                    <th class="text-left">No</th>
                    <th class="text-left">Year</th>
                    <th class="text-center">Company</th>
                    <th class="text-center">Requestor</th>
                    <th class="text-center">Progress</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                
                <?php 

                if (!empty($budgetForAction)){


                    $counter = 0;
                    foreach ($budgetForAction as $key => $budget) {

                     ?>
                     <tr>
                        <td> <?php echo ++$counter ?></td>
                        <td>
                            <?php echo $this->Html->link($budget['Budget']['year'],array('controller'=>'budget','action'=>'dashboard',$this->Mems->encrypt($budget['Budget']['budget_id'])),array('escape'=>false));?>
                            <br>
                            <small>
                                <?php
                                    $created = date('d-F-Y',strtotime($budget['Budget']['created']));
                                   
                                    echo "Created on : ".$created;
                                   
                                ?>
                            </small>
                        </td>
                        <td class="text-center">
                          <?php
                            echo $budget['Budget']['Company']['company'];
                          ?>
                        </td>
                        <td class="text-center">
                          <?php
                            echo $budget['Budget']['User']['staff_name'];
                          ?>
                        </td>
                        <td class="p-progress text-center">
                          <?php
                            // check the status and progress
                            $totalStatus = 0;
                            $countApproved =0;
                            $currentStatus = 'Active';
                            foreach($budget['Budget']['BStatus'] as $bstatus):
                              if($bstatus['submission_no'] == $budget['Budget']['submission_no']):
                                $totalStatus++; //update total for current submission
                                if($bstatus['status'] == 'approved'):
                                  $countApproved++;
                                endif;

                                if($bstatus['status'] == 'rejected'):
                                  $currentStatus = 'Rejected';
                                endif;
                              endif;
                            endforeach;

                            if($totalStatus == 0)
                              $progress = 0;
                            else
                              $progress = round($countApproved/$totalStatus,2) * 100;
                            
                            if($progress < 40)
                              echo "<span class='badge bg-important'>{$progress}%</span>";
                            elseif($progress < 75)
                              echo "<span class='badge bg-warning'>{$progress}%</span>";
                            else
                              echo "<span class='badge bg-primary'>{$progress}%</span>";
                          ?>
                        </td>
                        <td class="text-center">
                          <?php
                            if($progress == 100)
                              echo "<span class='label label-success'> Approved </span>";
                            elseif($totalStatus == 0)
                              echo "<span class='label label-default'><span class='fa fa-exclamation-circle'></span> Incomplete </span>";
                            elseif($currentStatus == 'Active')
                              echo "<span class='label label-warning'> {$currentStatus} </span>";
                            else
                              echo "<span class='label label-danger'> {$currentStatus} </span>";
                          ?>
                        </td>
                       <td class="text-center">
                            <?php
                            echo $this->Html->link('Dashboard',array('controller'=>'Budget','action'=>'dashboard',$this->Mems->encrypt($budget['Budget']['budget_id'])),array('escape'=>false,'class'=>'btn btn-xs bg-primary tooltips','data-toggle'=>'tooltips','data-original-title'=>'Dashboard'));
                            ?>

                           
                        </td>
                    </tr>

                    <?php } 
                      } 
                      else {

                        ?>

                        <tr>
                           <td colspan="4" class="text-center">
                              You don't have any pending budget to act now. <br/>

                              <?php
                              echo $this->Html->link('Request Management (Budget)',array('controller'=>'Budget','action'=>'myreview'),array('escape'=>false,'class'=>'btn btn-xs bg-primary btn-round margin-top','data-toggle'=>'tooltips','data-original-title'=>'Request Management (Budget)'));
                              ?>
                            </td>

                        </tr>

                        <?php } ?>

                    </tbody>
                </table>
            </section>
        </div>
</div>
<?php
    endif;
?>
    <hr>
    <div id="my-budget-memo" class="my-section text-center margin-bottom">
    	<?php
      echo $this->Html->link('MY BUDGET & MEMO REQUESTS','#',array('escape'=>false,'class'=>'btn btn-lg bg-primary btn-round'));
      ?>
      <button  type="button" data-original-title="Description" 
               data-content="<div>This section shows the latest budget and memos submitted by the user</div>"     
               data-placement="top" data-trigger="hover" class="btn btn-round bg-primary btn-xs popovers-with-html"><i class="fa fa-question-circle"></i>
          </button>
  </div>
  <!-- My Financial and Non-Financial Memo -->
<?php
    if($userInfo['Role']['my_request_financial_memo']==1):
?>
  <div class="row">
   <!-- Financial Memo -->
   <div class="col-lg-12">
      <section class="panel">
         <header class="panel-heading blue-bg">
            <strong> My Financial Memo </strong>
            <small>(Showing maximum 5 latest financial memos) </small>
            <span class="pull-right">
              <?php
              echo $this->Html->link('See All',array('controller'=>'fMemo','action'=>'index'),array('class'=>'btn bg-blue btn-xs'));
              ?>

           </span>
       </header>
       <table class="table table-hover p-table">
           <thead>
              <tr>
                <th class="text-left">No</th>
                <th class="text-left">Subject</th>
                <th class="text-center">Ref. No</th>
               <!--  <th class="text-center">Department</th>
                <th class="text-center">Requestor</th> -->
                <th class="text-center">Progress</th> 
                <th class="text-center">Status</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 

            $counter = 0;

            foreach ($userFinancialmemos as $key => $userFinancialmemo) {

            ?>
            <tr>
                <td> <?php echo ++$counter ?></td>

                <td class="p-name">
                   <?php 
                      echo $this->Html->link($userFinancialmemo['FMemo']['subject'],array('controller'=>'FMemo','action'=>'dashboard',$this->Mems->encrypt($userFinancialmemo['FMemo']['memo_id'])),array('escape'=>false)); ?>
                    <br>
                    <small>
                        <?php
                            $created = date('d-F-Y',strtotime($userFinancialmemo['FMemo']['created']));
                           
                            echo "Created on : ".$created;
                           
                        ?>
                    </small>
                    
                </td>
                <td class="text-center">
                  <?php
                    echo $userFinancialmemo['FMemo']['ref_no'];
                  ?>
                </td>
                <!-- <td class="text-center">
                  <?php
                    echo $userFinancialmemo['Department']['department_name'];
                  ?>
                </td>
                <td class="text-center">
                  <?php
                    echo $userFinancialmemo['User']['staff_name'];
                  ?>
                </td> -->
                <td class="p-progress text-center">
                  <?php
                    // check the status and progress
                    $totalStatus = 0;
                    $countApproved =0;
                    $currentStatus = 'Active';
                    foreach($userFinancialmemo['FStatus'] as $fstatus):
                      if($fstatus['submission_no'] == $userFinancialmemo['FMemo']['submission_no']):
                        $totalStatus++; //update total for current submission
                        if($fstatus['status'] == 'approved'):
                          $countApproved++;
                        endif;

                        if($fstatus['status'] == 'rejected'):
                          $currentStatus = 'Rejected';
                        endif;
                      endif;
                    endforeach;

                    if($totalStatus == 0)
                      $progress = 0;
                    else
                      $progress = round($countApproved/$totalStatus,2) * 100;
                    
                    if($progress < 40)
                      echo "<span class='badge bg-important'>{$progress}%</span>";
                    elseif($progress < 75)
                      echo "<span class='badge bg-warning'>{$progress}%</span>";
                    else
                      echo "<span class='badge bg-primary'>{$progress}%</span>";
                  ?>
                </td>
                <td class="text-center">
                  <?php
                    if($progress == 100)
                      echo "<span class='label label-success'> Approved </span>";
                    elseif($totalStatus == 0)
                      echo "<span class='label label-default'><span class='fa fa-exclamation-circle'></span> Incomplete </span>";
                    elseif($currentStatus == 'Active')
                      echo "<span class='label label-warning'> {$currentStatus} </span>";
                    else
                      echo "<span class='label label-danger'> {$currentStatus} </span>";
                  ?>
                </td>
                <td class="text-center">
                    <?php
                    echo $this->Html->link('Dashboard',array('controller'=>'FMemo','action'=>'dashboard',$this->Mems->encrypt($userFinancialmemo['FMemo']['memo_id'])),array('escape'=>false,'class'=>'btn btn-xs bg-primary tooltips','data-toggle'=>'tooltips','data-original-title'=>'Dashboard'));
                    ?>
                </td>
            </tr>
            <?php } ?>

            
        </tbody>
    </table>
    </section>
    </div>
  </div>
<?php
    endif;
    if($userInfo['Role']['my_request_memo']==1):
?>
<div class = "row">
<!-- Memo -->
  <div class="col-lg-12">
    <section class="panel">
       <header class="panel-heading blue-bg">
          <strong> My Non Financial Memo </strong>
          <small>(Showing maximum 5 latest memos) </small>
          <span class="pull-right">
              <?php
              echo $this->Html->link('See All',array('controller'=>'NfMemo2','action'=>'index'),array('class'=>'btn bg-blue btn-xs'));
              ?>
          </span>
      </header>
      <table class="table table-hover p-table">
         <thead>
            <tr>
              <th class="text-left">No</th>
              <th class="text-left">Subject</th>
              <th class="text-center">Ref. No</th>
             <!--  <th class="text-center">Department</th>
              <th class="text-center">Requestor</th> -->
              <th class="text-center">Progress</th>
              <th class="text-center">Status</th>
              <th class="text-center">Action</th>
          </tr>
      </thead>
      <tbody>
         
        <?php 

        $counter = 0;

        foreach ($userXfinancialmemos as $key => $userXfinancialmemo) {


      ?>
      <tr>
          <td> <?php echo ++$counter ?></td>

          <td class="p-name">
               <?php 
                    echo $this->Html->link($userXfinancialmemo['NfMemo']['subject'],array('controller'=>'NfMemo2','action'=>'dashboard',$this->Mems->encrypt($userXfinancialmemo['NfMemo']['memo_id'])),array('escape'=>false)); ?>
              <br>
              <small>
                  <?php
                      $created = date('d-F-Y',strtotime($userXfinancialmemo['NfMemo']['created']));
                     
                      echo "Created on : ".$created;
                     
                  ?>
              </small>
          </td>
          <td class="text-center">
            <?php
              echo $userXfinancialmemo['NfMemo']['ref_no'];
            ?>
          </td>
         <!--  <td class="text-center">
            <?php
              echo $userXfinancialmemo['Department']['department_name'];
            ?>
          </td>
          <td class="text-center">
            <?php
              echo $userXfinancialmemo['User']['staff_name'];
            ?>
          </td> -->
         <td class="p-progress text-center">
            <?php
              // check the status and progress
              $totalStatus = 0;
              $countApproved =0;
              $currentStatus = 'Active';
              foreach($userXfinancialmemo['NfStatus'] as $xfstatus):
                if($xfstatus['submission_no'] == $userXfinancialmemo['NfMemo']['submission_no']):
                  $totalStatus++; //update total for current submission
                  if($xfstatus['status'] == 'approved'):
                    $countApproved++;
                  endif;

                  if($xfstatus['status'] == 'rejected'):
                    $currentStatus = 'Rejected';
                  endif;
                endif;
              endforeach;

              if($totalStatus == 0)
                $progress = 0;
              else
                $progress = round($countApproved/$totalStatus,2) * 100;
              
              if($progress < 40)
                echo "<span class='badge bg-important'>{$progress}%</span>";
              elseif($progress < 75)
                echo "<span class='badge bg-warning'>{$progress}%</span>";
              else
                echo "<span class='badge bg-primary'>{$progress}%</span>";
            ?>
          </td>
          <td class="text-center">
            <?php
              if($progress == 100)
                echo "<span class='label label-success'> Approved </span>";
              elseif($totalStatus == 0)
                echo "<span class='label label-default'><span class='fa fa-exclamation-circle'></span> Incomplete </span>";
              elseif($currentStatus == 'Active')
                echo "<span class='label label-warning'> {$currentStatus} </span>";
              else
                echo "<span class='label label-danger'> {$currentStatus} </span>";
            ?>
          </td>
          <td class="text-center">
             <?php
              echo $this->Html->link('Dashboard',array('controller'=>'NfMemo2','action'=>'dashboard',$this->Mems->encrypt($userXfinancialmemo['NfMemo']['memo_id'])),array('escape'=>false,'class'=>'btn btn-xs bg-primary tooltips','data-toggle'=>'tooltips','data-original-title'=>'Dashboard'));
              ?>

          </td>
      </tr>
      <?php } ?>
  </tbody>
  </table>
  </section>
  </div>
</div>
<?php
    endif;
    if($userInfo['Role']['my_request_budget']==1):
?>
<div class="row">
    <!-- Budget -->
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading blue-bg">
                <strong> My Budget </strong>
                <small>(Showing maximum 5 latest budgets) </small>

                <span class="pull-right">
                   <?php
                   echo $this->Html->link('See All',array('controller'=>'Budget','action'=>'index'),array('class'=>'btn bg-blue btn-xs'));
                   ?>
               </span>
           </header>
           <table class="table table-hover p-table">
            <thead>
                <tr>
                    <th class="text-left">No</th>
                    <th class="text-left">Year</th>
                    <th class="text-center">Company</th>
                    <!-- <th class="text-center">Requestor</th> -->
                    <th class="text-center">Progress</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                
                <?php 

                $counter = 0;

                foreach ($userBudgets as $key => $userBudget) {


              ?>
              <tr>
                <td> <?php echo ++$counter ?></td>

                <td class="p-name">
                     <?php echo $this->Html->link($userBudget['Budget']['year'],array('controller'=>'budget','action'=>'dashboard',$this->Mems->encrypt($userBudget['Budget']['budget_id'])),array('escape'=>false));?>
                    <br>
                    <small>
                        <?php
                            $created = date('d-F-Y',strtotime($userBudget['Budget']['created']));
                           
                            echo "Created on : ".$created;
                           
                        ?>
                    </small>
                </td>
                <td class="text-center">
                  <?php
                    echo $userBudget['Company']['company'];
                  ?>
                </td>
                
                <td class="p-progress text-center">
                  <?php
                    // check the status and progress
                    $totalStatus = 0;
                    $countApproved =0;
                    $currentStatus = 'Active';
                    foreach($userBudget['BStatus'] as $bstatus):
                      if($bstatus['submission_no'] == $userBudget['Budget']['submission_no']):
                        $totalStatus++; //update total for current submission
                        if($bstatus['status'] == 'approved'):
                          $countApproved++;
                        endif;

                        if($bstatus['status'] == 'rejected'):
                          $currentStatus = 'Rejected';
                        endif;
                      endif;
                    endforeach;

                    if($totalStatus == 0)
                      $progress = 0;
                    else
                      $progress = round($countApproved/$totalStatus,2) * 100;
                    
                    if($progress < 40)
                      echo "<span class='badge bg-important'>{$progress}%</span>";
                    elseif($progress < 75)
                      echo "<span class='badge bg-warning'>{$progress}%</span>";
                    else
                      echo "<span class='badge bg-primary'>{$progress}%</span>";
                  ?>
                </td>
                <td class="text-center">
                  <?php
                    if($progress == 100)
                      echo "<span class='label label-success'> Approved </span>";
                    elseif($totalStatus == 0)
                      echo "<span class='label label-default'><span class='fa fa-exclamation-circle'></span> Incomplete </span>";
                    elseif($currentStatus == 'Active')
                      echo "<span class='label label-warning'> {$currentStatus} </span>";
                    else
                      echo "<span class='label label-danger'> {$currentStatus} </span>";
                  ?>
                </td>
                <td class="text-center">
                <?php
                 echo $this->Html->link('Dashboard',array('controller'=>'budget','action'=>'dashboard',$this->Mems->encrypt($userBudget['Budget']['budget_id'])),array('escape'=>false,'class'=>'btn btn-xs bg-primary tooltips','data-toggle'=>'tooltips','data-original-title'=>'Dashboard'));
                 ?>
            </td>
        </tr>
        <?php } ?>

        
    </tbody>
  </table>
  </section>
  </div>
</div>
<?php
    endif;
?>
</section>




