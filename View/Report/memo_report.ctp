<?php
	$this->Html->addCrumb('Reports', array('controller' => 'report', 'action' => 'memoReport'));
	$this->Html->addCrumb('Non-financial Memo Report', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
	<!-- page start-->
    <div class="row">
		<div class="col-lg-12">
			<section class="panel">
				<header class="panel-heading">
					Filtering Options For Non-financial Memo
					
				</header>

				<div class="panel-body">
					<?php


	                    echo $this->Form->create('Filter', array(
	                        'url' => array('controller' => 'report' , 'action' => 'memoReport'),'id'=>'reportForm'));
	                 ?>
					<div class="col-lg-1">
					</div>
					<div class="col-lg-5">
					
				          <div class="form-group">
				                              
				              <?php
				              echo $this->Form->input('division_id',array('type'=>'select','options'=>$groups,'class'=>'select2-sortable full-width','multiple'=>'multiple','style'=>'width:100%','placeholder'=>'Select division(s)'));
				              ?>
				           
				          </div>
				          <div class="form-group">
				                             
				              <?php
				              echo $this->Form->input('department_id',array('type'=>'select','options'=>$departments,'class'=>'select2-sortable full-width','multiple'=>'multiple','style'=>'width:100%','placeholder'=>'Select department(s)'));
				              ?>
				            
				          </div>
				          <div class="form-group">
				            
				                          
				              <?php
				               echo $this->Form->input('user_id',array('type'=>'select','options'=>$allUsers,'class'=>'select2-sortable full-width','multiple'=>'multiple','style'=>'width:100%','placeholder'=>'Select requestor(s)'));
				              ?>
				            
				          </div>
					</div>

					<div class="col-lg-5">
					
		                 
				          
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
					<div class="col-lg-1">
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
						
                        $groupData='';
                        $deptData='';
                        $userData='';
                        $fromData='';
                        $toData='';

                        if (!empty($this->request->data['Filter']['division_id']))
                            $groupData=$this->request->data['Filter']['division_id'];

                        if (!empty($this->request->data['Filter']['department_id']))
                            $deptData=$this->request->data['Filter']['department_id'];

                       if (!empty($this->request->data['Filter']['date_from']))
                            $fromData=$this->request->data['Filter']['date_from'];

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
				Non-Financial Memo Report Summary
				</header>

				<div class="panel-body">
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
							$m['NfMemo']['memo_id']=$this->Mems->encrypt($m['NfMemo']['memo_id']);
					   		
							
                         ?>
						<tr>
							<td class="text-center"><?php echo ++$key;?></td>
                            <td class="text-center"><?php echo date('d M Y', strtotime($m['NfMemo']['created'])); ?></td>
							<td>
						   <?php 
						  
						   	echo '<b>'.$this->Html->link($m['NfMemo']['subject'],array('controller'=>'NfMemo2','action'=>'dashboard',$m['NfMemo']['memo_id']),array('escape'=>false)).'</b>';

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
				</div>

			</section>
		</div>
	</div>
<script type="text/javascript" charset="utf-8">
        $('#pdf').on('click',function(){
            $('#reportForm').attr('action','<?php echo ACCESS_URL ?>report/exportNFMemo/pdf.pdf');
        });
        $('#excel').on('click',function(){
            $('#reportForm').attr('action','<?php echo ACCESS_URL ?>report/exportNFMemo/excel.xlsx');
        });
        $('#filter').on('click',function(){
            $('#reportForm').attr('action','<?php echo ACCESS_URL ?>report/memoReport');
        });
        $('#all').on('click',function(){
            $('#reportForm').attr('action','<?php echo ACCESS_URL ?>report/memoReport');
        });
        // $('.comp1').change(function(){
        //     var id = $(this).val();
        //     $.ajax({
        //         url: '<?php echo ACCESS_URL ?>/report/getGroups/' + id,
        //         dataType: 'json',
        //         success: function (data) {
        //             if ( data.length === 0 ){
        //                 console.log('empty');
        //                 $('.comp2').html('');
        //                 $('.comp2').select2('destroy');
        //                 $('.comp2').select2();
        //             }
        //             else {
        //                 console.log(data);
        //                 var selectData = '';
        //                 for ( var key in data ){
        //                     selectData += "<option value='"+key+"'>"+data[key]+"</option>";
        //                     console.log(key+data[key]);
        //                 }
        //                 $('.comp2').html(selectData);
        //                 $('.comp2').select2('destroy');
        //                 $('.comp2').select2();
        //             }
        //         },
        //     });
        // });
</script>
	<!-- page end-->
</section>
