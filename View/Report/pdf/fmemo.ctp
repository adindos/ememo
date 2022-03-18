<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
			Financial Memo Report Summary
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
                     ?>
					<tr>
						<td class="text-center"><?php echo ++$key;?></td>
                        <td class="text-center"><?php echo date('d M Y', strtotime($m['FMemo']['created'])); ?></td>
						<td>
						
					   <?php 
					  
					   	echo $m['FMemo']['subject'];

					   ?>
						</td>
						<td class="text-center"><?php echo $m['FMemo']['ref_no'] ?></td>
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