<section class="mems-content">
	<!-- page start-->

	<div class="row">

		<div class="col-lg-12">

			<section class="panel">

				<header class="panel-heading">
					Budget Archive
 					<!-- <button type="button" class="btn btn-round btn-primary btn-xs margin-left">Upload New Budget</button> -->
 					<?php
 						echo $this->Html->link('Upload New Budget','#modal-upload-budget-archive',array('class'=>'btn btn-round btn-primary btn-xs margin-left','data-toggle'=>'modal'));
 					?>

					<span class="tools pull-right">
                        <a class="fa fa-chevron-down" href="javascript:;"></a>
                    </span>
				</header>

				<div class="panel-body">
					<table class="table table-striped table-advance table-hover dataTable">

						<thead>

							<tr>
								<th>  Company</th>
								<th class="hidden-phone"> Description</th>
								<th class="text-center"> Year</th>
								<th class="text-center" >Actions</th>

							</tr>

						</thead>

						<tbody>

							<?php 
								foreach ($allArchives as $key => $archive) {
							 ?>

							<tr>
								<td><a href="#"><?php echo $archive['Company']['company'] ?></a></td>
								<td><?php echo $archive['BArchive']['description'] ?></td>
								<td class="text-center"><span class="label label-info label-mini"><?php echo $archive['BArchive']['year'] ?></span></td>
								<td class="text-center">
									
									<?php 

									echo $this->Form->postlink('<i class="fa fa-download"></i>',array('controller'=>'archive','action'=>'downloadArchiveBudget',$archive['BArchive']['archive_id']),array('escape'=>false,'class'=>'btn btn-success btn-xs tooltips','data-original-title="Downlaod Archive"'),"Are you sure you want to download the budget archive?");

									 ?>
								
									

									<?php

									echo "<a href='#modal-edit-upload-budget-archive' data-toggle='modal' class='btn btn-info btn-xs edit-archive-btn tooltips' data-placement='bottom', data-toggle = 'tooltips', data-original-title='Edit' data-archive-id='".$archive['BArchive']['archive_id']."' data-company-id='".$archive['BArchive']['company_id']."' data-year='".$archive['BArchive']['year']."' data-description = '".$archive['BArchive']['description']."'  > <i class='fa fa-pencil'></i></a>";


									echo $this->Form->postlink('<i class="fa fa-download"></i>',array('controller'=>'archive','action'=>'downloadPdf'),array('escape'=>false,'class'=>'btn btn-success btn-xs tooltips','data-original-title="Delete Archive"'));

									echo $this->Form->postlink('<i class="fa fa-times"></i>',array('controller'=>'archive','action'=>'deleteArchiveBudget',$archive['BArchive']['archive_id']),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title="Delete Archive"'),"Are you sure you want to delete the budget archive?");


									 ?>

								</td>
							</tr>
							<?php } ?>

						</tbody>

					</table>
				</div>

			</section>

		</div>

	</div>

	<!-- page end-->

</section>

<!-- Modal -->

<!-- modal add archive budget -->
<div class="modal fade" id="modal-upload-budget-archive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">New Budget Archive</h4>
			</div>
			 <?php
                
                $model = 'BArchive';
                $options = array('class'=>'form-horizontal','type' => 'file', 'inputDefaults' => array('label' => false),'url' =>  array('controller'=>'archive','action'=>'addBudgetArchive'));

                echo $this->Form->create($model, $options);


            ?>
			<div class="modal-body">
				<div class="form-group">
					<label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Company</label>
					<div class="col-lg-9 col-sm-9">
						<?php

						    $companies_list = array();
						    foreach ($allcompanies as $key => $company) {
						        $companies_list[$company['Company']['company_id']] = $company['Company']['company'];
						    }

						    echo $this->Form->input('company_id',array('type'=>'select','options'=>$companies_list,'class'=>'select full-width','required'));
						?>
					</div>
				</div>
				<div class="form-group">
					<label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Budget Year</label>
					<div class="col-lg-9 col-sm-9">
						<?php
						    echo $this->Form->input('year',array('type'=>'number','max'=> '2500', 'min' => '1900','class'=>'form-control','placeholder'=>'Budget Year','required'));
						?>
					</div>
				</div>	
				<div class="form-group">
					<label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Description</label>
					<div class="col-lg-9 col-sm-9">
						<?php
						    echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control','maxlength' => '200','placeholder'=>'Details about the budget', 'id' => 'add-archive-description'));
						?>
					</div>
				</div>	
				<div class="form-group">
					<label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Upload</label>
					<div class="col-lg-9 col-sm-9">
						<?php
							echo $this->Form->input('filename',array('type'=>'file','class'=>'file', 'required'));
						?>
					</div>
				</div>	

			</div>
			<div class="modal-footer">
				<?php 

				echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
				echo $this->Form->end();
				echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

				?>

			</div>
		</div>
	</div>
</div>
  <!-- modal end  add archive -->



  <!-- modal edit archive budget -->
  <div class="modal fade" id="modal-edit-upload-budget-archive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
  				<h4 class="modal-title">Edit Budget Archive</h4>
  			</div>
  			 <?php
                  
                  $model = 'BArchive';
                  $options = array('class'=>'form-horizontal', 'inputDefaults' => array('label' => false),'url' =>  array('controller'=>'archive','action'=>'editBudgetArchive'));

                  echo $this->Form->create($model, $options);
                  echo $this->Form->hidden('archive_id', array('id' => 'edit-archive-id'));



              ?>
  			<div class="modal-body">
  				<div class="form-group">
  					<label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Company</label>
  					<div class="col-lg-9 col-sm-9">
  						<?php

  						    $companies_list = array();
  						    foreach ($allcompanies as $key => $company) {
  						        $companies_list[$company['Company']['company_id']] = $company['Company']['company'];
  						    }

  						    echo $this->Form->input('company_id',array('type'=>'select','options'=>$companies_list,'class'=>'select full-width','required','id' => 'edit-archivecompany-id'));
  						?>
  					</div>
  				</div>
  				<div class="form-group">
  					<label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Budget Year</label>
  					<div class="col-lg-9 col-sm-9">
  						<?php
  						    echo $this->Form->input('year',array('type'=>'number','max'=> '2500', 'min' => '1900','class'=>'form-control','placeholder'=>'Budget Year','required','id'=> 'edit-year'));
  						?>

  					</div>
  				</div>	
  				<div class="form-group">
  					<label for="inputEmail1" class="col-lg-3 col-sm-3 control-label">Description</label>
  					<div class="col-lg-9 col-sm-9">
  						<?php
  						    echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control','maxlength' => '200','placeholder'=>'Details about the budget', 'id' => 'edit-archive-description'));
  						?>
  					</div>
  				</div>	
  				

  			</div>
  			<div class="modal-footer">
  				<?php 

  				echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
  				echo $this->Form->end();
  				echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

  				?>

  			</div>
  		</div>
  	</div>
  </div>
    <!-- modal end  edit archive -->



    <script type="text/javascript">

    $(document).ready(function () {


    $('.edit-archive-btn').on('click',function(){
        var archive_id = $(this).data('archive-id');
        var company_id = $(this).data('company-id');
        var year = $(this).data('year');
        var description = $(this).data('description');

        $('#edit-archive-id').val(archive_id);
        $('#edit-archivecompany-id').val(company_id);
        $('#edit-year').val(year);
        $('#edit-archive-description').val(description);


    })


    });


    </script>