<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
            Budget Transfer Report Summary
            </header>

            <div class="panel-body">
                <table class="table table-striped dataTable" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Date</th>
                            <th class="text-left">Memo</th>
                            <th class="text-left">Department</th>
                            <th class="text-left">Category</th>
                            <th class="text-center">Requested Amount</th>
                            <th class="text-left">Budget Transfer</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                       <?php
                       if (!empty($memo)):
                        $counter=0;
                        foreach ($memo as $key=>$m){
                            if (!empty($m['FMemoBudget']))://only show memo with budget

                                foreach ($m['FMemoBudget'] as $mb):
                                    //show only memo budget with budget transfer
                                    if (!empty($mb['transferred_item_amount_id'])):

                                ?>
                                        <tr>
                                            <td class="text-center"><?php echo ++$counter;?></td>
                                            <td class="text-center"><?php echo date('d M Y', strtotime($m['FMemo']['created'])); ?></td>
                                            <td class="text-left">
                                            
                                           <?php 
                                            $subject='<b>'.$m['FMemo']['subject'].'</b><br><small>Ref. No : '.$m['FMemo']['ref_no'].'<br>Requested by : '.$m['User']['staff_name'].'</small>';
                                            echo $subject;

                                           ?>
                                            </td>
                                            <td class="text-left"><?php echo $m['Department']['department_name'] ?></td>
                                            <td class="text-left"><?php echo $mb['BItemAmount']['Item']['item'] ?></td>
                                            <td class="text-center"><?php echo 'RM'.$mb['amount'] ?></td>
                                            
                                            <td class="text-left">
                                                <?php 
                                                    if (!empty($mb['transferred_item_amount_id'])){
                                                         echo 'RM'.$mb['transferred_amount'] ;
                                                        echo "<br><small>Transferred from: <br>";
                                                        echo $mb['BItemAmountTransfer']['Item']['item'].' ('.$mb['BItemAmountTransfer']['BDepartment']['Department']['department_name'].')</small>';

                                                    }

                                                ?>
                                                
                                            </td>

                                        </tr>
                         <?php
                                    endif;
                                endforeach;
                           endif;
                          }

                        endif;?>                        
                    </tbody>
                </table>
            </div>

        </section>
    </div>
</div>