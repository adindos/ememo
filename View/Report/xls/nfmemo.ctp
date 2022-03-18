<?php libxml_use_internal_errors(true);?>
<table class="table table-striped dataTable" style="font-size:12px;">
    <thead>
        <tr>
            <th class="text-center">No.</th>
            <th class="text-center">Date</th>
            <th class="text-left">Subject</th>
            <th class="text-center">Ref. No</th>
            <th class="text-center">Department</th>
            <th class="text-center">Division</th>
            <th class="text-center">Requested by</th>
          
        </tr>
    </thead>
    <tbody>
       <?php
       if (!empty($memo)):
        foreach ($memo as $key=>$m){
            
         ?>
        <tr>
            <td class="text-center"><?php echo ++$key;?></td>
            <td class="text-center"><?php echo date('d M Y', strtotime($m['NfMemo']['created'])); ?></td>
            <td>
            
           <?php 
          
            echo $m['NfMemo']['subject'];

           ?>
            </td>
            <td class="text-center"><?php echo $m['NfMemo']['ref_no'] ?></td>
            <td class="text-center"><?php echo $m['Department']['department_name'] ?></td>
            <td class="text-center"><?php echo $m['Department']['Group']['group_name'] ?></td>
            <td class="text-center">
                <?php echo $m['User']['staff_name']; ?>
            </td>
           
        </tr>
         <?php
        }

        endif;?>                        
    </tbody>
</table>