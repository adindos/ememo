<?php
    $this->Html->addCrumb('Statistics', $this->here,array('class'=>'active'));
?>

<!-- Je Suis JavaScript -->

<!-- Functions to make the counter effect on the dashboard counters-->
<!-- Last modified @ Faridi 4/March-->
<script>

    // // Budget counter animation
    // function countUpBudget(count)
    // {
    //     var div_by = 100,
    //         speed = Math.round(count / div_by),
    //         $display = $('.countBudget'),
    //         run_count = 1,
    //         int_speed = 24;

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

    // // Approved Budget counter 
    // function countUpBudgetApproved(count)
    // {
    //     var div_by = 100,
    //         speed = Math.round(count / div_by),
    //         $display = $('.countBudgetApproved'),
    //         run_count = 1,
    //         int_speed = 24;

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



    // // Approved Financial Memo counter animation
    // function countUpFinancial(count)
    // {
    //     var div_by = 100,
    //         speed = Math.round(count / div_by),
    //         $display = $('.countFinancial'),
    //         run_count = 1,
    //         int_speed = 24;

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


    //  // Approved Budget counter 
    // function countUpFinancialApproved(count)
    // {
    //     var div_by = 100,
    //         speed = Math.round(count / div_by),
    //         $display = $('.countFinancialApproved'),
    //         run_count = 1,
    //         int_speed = 24;

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


    //  // Count Financial counter 
    // function countUpXFinancial(count)
    // {
    //     var div_by = 100,
    //         speed = Math.round(count / div_by),
    //         $display = $('.countXFinancial'),
    //         run_count = 1,
    //         int_speed = 24;

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

    //  // Approved nonFinancial counter 
    // function countUpXFinancialApproved(count)
    // {
    //     var div_by = 100,
    //         speed = Math.round(count / div_by),
    //         $display = $('.countXFinancialApproved'),
    //         run_count = 1,
    //         int_speed = 24;

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

<section class="mems-content">
    <div class="row">
          <section class="col-lg-12">
           <?php
           // debug($user); exit;
            if($user['Role']['role_id']!= '18'):
            ?>
                <div class="rosw">
                    <div class="border-head">
                        <h3>Budget Info</h3>
                    </div>
                    <div class="row state-overview">
                        <div class="col-lg-3">
                            <section class="panel">
                                <div class="symbol bg-primary">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="value">
                                    <h1 class="countBudget">
                                        <?php echo $budgetCount ?>
                                        <script>           
                                            //countUpBudget(<?php echo $budgetCount ?>);
                                        </script>
                                    </h1>
                                    <p>Budget Requested</p>
                                </div>
                            </section>

                            <section class="panel">
                                <div class="symbol bg-primary">
                                    <i class="fa fa-tags"></i>
                                </div>
                                <div class="value">
                                    <h1 class="countBudgetApproved">
                                        <?php echo $counterBudgetApporved ?>
                                        <script>           
                                            //countUpBudgetApproved(<?php echo $counterBudgetApporved ?>);
                                        </script>
                                    </h1>
                                    <p>Budget Approved</p>
                                </div>
                            </section>
                        </div>
                        <div class="col-lg-9">
                            <section class="panel">
                                <div class="panel-body progress-panel">
                                    <div class="task-progress">
                                        <h1>Budget Request</h1>
                                        <p class="small">Showing all budget requests</p>
                                    </div>

                                    <div class="task-option">
                                        <?php
                                            //echo $this->Html->link('View All Budgets',array('controller'=>'requestor','action'=>'budgetList'),array('escape'=>false,'class'=>'btn bg-primary btn-xs btn-round'));
                                        ?>
                                    </div>
                                </div>
                                <table class="table table-stripped dataTable">
                                    <thead>
                                        <tr>
                                            <th class="text-left">No</th>
                                            <th class="text-left">Year</th>
                                            <th class="text-center">Company</th>
                                            <th class="text-center">Requestor</th>
                                            <th class="text-left">Progress</th>
                                            <th class="text-left">Status</th>
                                            <th class="text-center">Action</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php 
                                            $counter = 0;
                                            foreach ($budgets as $key => $budget) {


                                                $budget['Budget']['budget_id']=$this->Mems->encrypt($budget['Budget']['budget_id']);


                                        ?>
                                        <tr>
                                            <td><?php echo (++$counter); ?></td>
                                            <td class = "text-left">
                                                <?php echo $this->Html->link($budget['Budget']['year'],array('controller'=>'budget','action'=>'dashboard',$budget['Budget']['budget_id']),array('escape'=>false));?>
                                                <br>
                                                <small>
                                                    <?php
                                                        $created = date('d-F-Y',strtotime($budget['Budget']['created']));
                                                       
                                                        echo "Created on : ".$created;
                                                       
                                                    ?>
                                                </small>
                                            </td>
                                            <td class = "text-center">
                                                <?php echo($budget['Company']['company']); ?>
                                            </td>
                                            <td class = "text-center">
                                                <?php echo($budget['User']['staff_name']); ?>
                                            </td>
                                            <td class="p-progress text-center">
                                              <?php
                                                // check the status and progress
                                                $totalStatus = 0;
                                                $countApproved =0;
                                                $currentStatus = 'Active';
                                                foreach($budget['BStatus'] as $bstatus):
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
                                            <td class = "text-center">
                                                
                                                <?php 
                                                
                                                   echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'Budget','action'=>'dashboard',$budget['Budget']['budget_id']),array('escape'=>false,'class'=>'btn bg-primary btn-xs tooltips data-toggle="tooltip" data-placement="top" data-original-title="Dashboard" '));

                                                ?>

                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </section>
                        </div>
                    </div>
                </div>
            <?php
            endif;           
                ?>
            <div class="rosw">
                <div class="border-head">
                    <h3>Financial Memo Info</h3>
                </div>
                <div class="row state-overview">
                    <div class="col-lg-3">
                        <section class="panel">
                            <div class="symbol bg-primary">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="value">
                                <h1 class="countFinancial">
                                   <?php echo $financialCount ?>
                                   <script>           
                                       //countUpFinancial(<?php echo $financialCount ?>);
                                   </script>
                                </h1>
                                <p>Financial Memos Requested</p>
                            </div>
                        </section>

                        <section class="panel">
                            <div class="symbol bg-primary">
                                <i class="fa fa-tags"></i>
                            </div>
                            <div class="value">
                                <h1 class="countFinancialApproved">
                                    <?php echo $counterFinancialApporved ?>
                                    <script type="text/javascript">
                                        //countUpFinancialApproved(<?php echo $counterFinancialApporved ?>);
                                    </script>
                                </h1>
                                <p>Financial Memos Approved</p>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-9">
                        <section class="panel">
                            <div class="panel-body progress-panel">
                                <div class="task-progress">
                                    <h1>Financial Memo Request</h1>
                                    <p class="small">Showing all  financial memos</p>
                                </div>

                                <div class="task-option">
                                    <?php
                                        //echo $this->Html->link('View All Financial Memos',array('controller'=>'requestor','action'=>'memoList'),array('escape'=>false,'class'=>'btn bg-primary btn-xs btn-round'));
                                    ?>
                                </div>
                            </div>
                            <table class="table table-hover dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-left">No</th>
                                        <th class="text-left">Subject</th>
                                        <th class="text-left">Ref. No</th>
                                        <th class="text-center">Department</th>
                                        <th class="text-center">Requestor</th>
                                        <th class="text-left">Progress</th>
                                        <th class="text-left">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter = 0;
                                        foreach ($financialmemos as $key => $financialmemo) {

                                            $financialmemo['FMemo']['memo_id']=$this->Mems->encrypt($financialmemo['FMemo']['memo_id']);


                                        ?>
                                    <tr>
                                        <td><?php echo ++$counter ?></td>
                                        <td class = "text-left">
                                            <?php 
                                                echo $this->Html->link($financialmemo['FMemo']['subject'],array('controller'=>'FMemo','action'=>'dashboard',$financialmemo['FMemo']['memo_id']),array('escape'=>false)); ?>
                                            <br>
                                                <small>
                                                    <?php
                                                        $created = date('d-F-Y',strtotime($financialmemo['FMemo']['created']));
                                                       
                                                        echo "Created on : ".$created;
                                                       
                                                    ?>
                                                </small>
                                        </td>
                                        <td class = "text-left">
                                            <?php echo $financialmemo['FMemo']['ref_no'] ?>
                                        </td>
                                        <td class = "text-center">
                                            <?php echo($financialmemo['Department']['department_name']); ?>
                                        </td>
                                        <td class = "text-center">
                                             <?php echo $financialmemo['User']['staff_name'] ?>
                                        </td>
                                         <td class="p-progress text-center">
                                          <?php
                                            // check the status and progress
                                            $totalStatus = 0;
                                            $countApproved =0;
                                            $currentStatus = 'Active';
                                            foreach($financialmemo['FStatus'] as $fstatus):
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
                                        <td class = "text-center">

                                            <?php 
                                            
                                               echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'FMemo','action'=>'dashboard',$financialmemo['FMemo']['memo_id']),array('escape'=>false,'class'=>'btn bg-primary btn-xs tooltips data-toggle="tooltip" data-placement="top" data-original-title="Dashboard" '));

                                            ?>

                                        </td>
                               
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </section>
                    </div>
                </div>
            </div>
             <?php
                 if($user['Role']['role_id']!= '18'):
            ?>
            <div class="rosw">
                <div class="border-head">
                    <h3>Non-Financial Memos Info</h3>
                </div>
                <div class="row state-overview">
                    <div class="col-lg-3">
                        <section class="panel">
                            <div class="symbol bg-primary">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="value">
                                <h1 class="countXFinancial">
                                    <?php echo $xfinancialCount ?>
                                    <script>           
                                       // countUpXFinancial(<?php echo $xfinancialCount ?>);
                                    </script>
                                </h1>
                                <p>Non-Financial Memos Requested</p>
                            </div>
                        </section>

                        <section class="panel">
                            <div class="symbol bg-primary">
                                <i class="fa fa-tags"></i>
                            </div>
                            <div class="value">
                                <h1 class="countXFinancialApproved">
                                    <?php echo $counterXFinancialApporved ?>
                                    <script type="text/javascript">
                                       // countUpXFinancialApproved(<?php echo $counterXFinancialApporved ?>);
                                    </script>
                                    
                                </h1>
                                <p>Non-Financial Memos Approved</p>
                            </div>
                        </section>
                    </div>
                    <div class="col-lg-9">
                        <section class="panel">
                            <div class="panel-body progress-panel">
                                <div class="task-progress">
                                    <h1>Memo Request</h1>
                                    <p class="small">Showing all non-financial memos</p>
                                </div>

                                <div class="task-option">
                                    <?php
                                       // echo $this->Html->link('View All Non-Financial Memos',array('controller'=>'requestor','action'=>'memoList'),array('escape'=>false,'class'=>'btn bg-primary btn-xs btn-round'));
                                    ?>
                                </div>
                            </div>
                            <table class="table table-stripped dataTable">
                                <thead>
                                    <tr>
                                        <th class="text-left">No</th>
                                        <th class="text-left">Subject</th>
                                        <th class="text-left">Ref. No</th>
                                        <th class="text-center">Department</th>
                                        <th class="text-center">Requestor</th>
                                        <th class="text-left">Progress</th>
                                        <th class="text-left">Status</th>
                                        <th class="text-center">Action</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $counter = 0;
                                        foreach ($xfinancialmemos as $key => $xfinancialmemo) {


                                            $xfinancialmemo['NfMemo']['memo_id']=$this->Mems->encrypt($xfinancialmemo['NfMemo']['memo_id']);


                                        ?>
                                    <tr>
                                        <td><?php echo ++$counter ?></td>
                                        <td>
                                            <?php 
                                                echo $this->Html->link($xfinancialmemo['NfMemo']['subject'],array('controller'=>'NfMemo2','action'=>'dashboard',$xfinancialmemo['NfMemo']['memo_id']),array('escape'=>false)); ?>
                                            <br>
                                            <small>
                                                <?php
                                                    $created = date('d-F-Y',strtotime($xfinancialmemo['NfMemo']['created']));
                                                   
                                                    echo "Created on : ".$created;
                                                   
                                                ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php echo $xfinancialmemo['NfMemo']['ref_no'] ?>
                                        </td>
                                        <td class = "text-center">
                                            <?php echo($xfinancialmemo['Department']['department_name']); ?>
                                        </td>
                                        <td>
                                            <?php echo $xfinancialmemo['User']['staff_name'] ?>
                                        </td>
                                        <td class="p-progress text-center">
                                        <?php
                                          // check the status and progress
                                          $totalStatus = 0;
                                          $countApproved =0;
                                          $currentStatus = 'Active';
                                          foreach($xfinancialmemo['NfStatus'] as $xfstatus):
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
                                        <td class = "text-center">

                                            <?php 
                                            
                                               echo $this->Html->link('<i class="fa fa-dashboard"></i> Dashboard',array('controller'=>'NfMemo2','action'=>'dashboard',$xfinancialmemo['NfMemo']['memo_id']),array('escape'=>false,'class'=>'btn bg-primary btn-xs tooltips data-toggle="tooltip" data-placement="top" data-original-title="Dashboard" '));

                                            ?>

                                        </td>



                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </section>
                    </div>
                </div>
            </div>
            <?php
             endif;           
                ?>
        </section>
    </div>
</section>