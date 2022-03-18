<?php libxml_use_internal_errors(true);?>
<table class="table table-striped dataTable" style="font-size:12px;">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Date</th>
                <th class="text-left">Memo</th>
                <th class="text-left">Department</th>
                <th class="text-left">Category</th>
                <th class="text-center">Requested Amount</th>
                <th class="text-center">Unbudgeted</th>
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
                    ?>
                        <tr>
                            <td class="text-center"><?php echo ++$counter;?></td>
                            <td class="text-center"><?php echo date('d M Y', strtotime($m['FMemo']['created'])); ?></td>
                            <td class="text-left">
                            
                           <?php 
                            $subject=$m['FMemo']['subject'].' (Ref. No : '.$m['FMemo']['ref_no'].') - Requested by : '.$m['User']['staff_name'];
                            echo $subject;

                           ?>
                            </td>
                            <td class="text-left"><?php echo $m['Department']['department_name'] ?></td>
                            <td class="text-left"><?php echo $mb['BItemAmount']['Item']['item'] ?></td>
                            <td class="text-center"><?php echo 'RM'.$mb['amount'] ?></td>
                            
                            <td class="text-center">
                                <?php 
                                    if (!empty($mb['unbudgeted_amount']))
                                         echo 'RM'.$mb['unbudgeted_amount'] ;
                                ?>
                                
                            </td>

                            <td class="text-left">
                                <?php 
                                    if (!empty($mb['transferred_item_amount_id'])){
                                         echo 'RM'.$mb['transferred_amount'] ;
                                        echo "  transferred from  ";
                                        echo $mb['BItemAmountTransfer']['Item']['item'].' ('.$mb['BItemAmountTransfer']['BDepartment']['Department']['department_name'].')';

                                    }

                                ?>
                                
                            </td>

                        </tr>
             <?php
                    endforeach;
               endif;
              }

            endif;?>                        
        </tbody>
</table>