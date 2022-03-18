<?php
    $this->Html->addCrumb('Reports', array('controller' => 'report', 'action' => 'unbudgetedReport'));
    $this->Html->addCrumb('Unbudgeted Report', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
    <!-- page start-->
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading text-center">
                     <?php
                       echo $this->Html->link("<button class='btn btn-info tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Go to Budget Utilization Report'>Budget Utilization Report</button>",array('controller' => 'report', 'action' => 'budgetUtilizationReport'),array('escape'=>false)); 
                        
                        echo "&nbsp;&nbsp;";

                       echo $this->Html->link("<button class='btn btn-info tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Go to Unbudgeted Report'>Unbudgeted Report</button>",array('controller' => 'report', 'action' => 'unbudgetedReport'),array('escape'=>false)); 
                       
                        echo "&nbsp;&nbsp;";

                       echo $this->Html->link("<button class='btn btn-info tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Go to Budget Transfer Report'>Budget Transfer Report</button>",array('controller' => 'report', 'action' => 'budgetTransferReport'),array('escape'=>false)); 
                    ?>
                    
                </header>

               
            </section>
        </div>
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    Filtering Options For Unbudgeted Report
                    
                </header>

                <div class="panel-body">
                    <?php

                        
                        echo $this->Form->create('Filter', array(
                            'url' => array('controller' => 'report' , 'action' => 'unbudgetedReport'),'id'=>'reportForm'));
                     ?>
                    <div class="col-lg-4">
                    </div>
                    <div class="col-lg-4">
                          
                          <div class="form-group">
                           
                                    
                              <?php
                               echo $this->Form->input('date_from',array('type'=>'text','class'=>'form-control datepicker','placeholder'=>'Select date from'));
                              ?>
                           
                          </div>
                          <div class="form-group">
                              <?php
                               echo $this->Form->input('date_to',array('type'=>'text','class'=>'form-control datepicker','placeholder'=>'Select date to'));
                              ?>
                          </div>
                          
                    </div>
                    <div class="col-lg-4">
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group text-center">
                             <?php
                                echo $this->Form->button('Filter',array('type'=>'submit','class'=>'btn btn-success','name'=>'filter','id'=>'filter'));
                                echo "&nbsp;&nbsp;";
                                echo $this->Form->button('Show All',array('type'=>'submit','class'=>'btn btn-danger','name'=>'all','id'=>'all'));
                                echo "&nbsp;&nbsp;";
                                echo $this->Form->button('PDF',array('type'=>'submit','class'=>'btn btn-primary','id'=>'pdf'));
                                echo "&nbsp;&nbsp;";
                                echo $this->Form->button('Excel',array('type'=>'submit','class'=>'btn btn-primary','id'=>'excel'));

                            ?>
                        </div>
                    </div>
                    <?php 
                        echo $this->Form->end(); 

                       
                        $fromData='';
                        $toData='';

                        if (!empty($this->request->data['Filter']['date_to']))
                            $toData=$this->request->data['Filter']['date_to'];

                        if (!empty($this->request->data['Filter']['user_id']))
                            $userData=$this->request->data['Filter']['user_id'];
                    ?>
                </div>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                Unbudgeted Report Summary
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
                                <th class="text-center">Unbudgeted Allocation</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                           <?php
                           if (!empty($memo)):
                            $counter=0;
                            foreach ($memo as $key=>$m){
                                if (!empty($m['FMemoBudget']))://only show memo with budget
                                    $m['FMemo']['memo_id']=$this->Mems->encrypt($m['FMemo']['memo_id']);

                                    foreach ($m['FMemoBudget'] as $mb):
                                        //add condition to show memo budget with budget transfer only
                                        if (!empty($mb['unbudgeted_amount'])):
                                    ?>
                                            <tr>
                                                <td class="text-center"><?php echo ++$counter;?></td>
                                                <td class="text-center"><?php echo date('d M Y', strtotime($m['FMemo']['created'])); ?></td>
                                                <td class="text-left">
                                                
                                               <?php 
                                                $subject='<b>'.$m['FMemo']['subject'].'</b><br><small>Ref. No : '.$m['FMemo']['ref_no'].'<br>Requested by : '.$m['User']['staff_name'].'</small>';
                                                echo $this->Html->link($subject,array('controller'=>'FMemo','action'=>'dashboard',$m['FMemo']['memo_id']),array('escape'=>false));

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
    <script type="text/javascript" charset="utf-8">
            $('#pdf').on('click',function(){
                $('#reportForm').attr('action','<?php echo ACCESS_URL ?>report/exportUnbudgeted/pdf.pdf');
            });
            $('#excel').on('click',function(){
                $('#reportForm').attr('action','<?php echo ACCESS_URL ?>report/exportUnbudgeted/excel.xlsx');
            });
            $('#filter').on('click',function(){
                $('#reportForm').attr('action','<?php echo ACCESS_URL ?>report/unbudgetedReport');
            });
            $('#all').on('click',function(){
                $('#reportForm').attr('action','<?php echo ACCESS_URL ?>report/unbudgetedReport');
            });
            
    </script>
    <!-- page end-->
</section>
