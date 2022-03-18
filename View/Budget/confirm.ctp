<?php
  $encBudgetID = $this->Mems->encrypt($budgetID);

  $this->Html->addCrumb('Budget', array('controller' => 'budget', 'action' => 'index'));
  $this->Html->addCrumb($budget['Budget']['year'], array('controller' => 'budget', 'action' => 'dashboard',$encBudgetID));
  $this->Html->addCrumb('Confirm Budget', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
  <div class="row">
	<div class="col-sm-12">
	  	<section class="panel">
			<header class="panel-heading">
				Please complete the form below to confirm the budget
		   	</header>
	   		<div class="panel-body">
	   			<?php
	   				echo $this->Form->create('Budget',array('url'=>array('controller'=>'budget','action'=>'confirmBudget'),
	   														'class'=>'form-horizontal tasi-form','type'=>'file',
	   														'onSubmit'=>'return confirm("Are you sure you want to confirm this budget? You will still be able to edit the budget afterwards.")',
	   														'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
	   												)
	   										);
	   				echo $this->Form->hidden('Budget.budget_id',array('value'=>$budgetID));
	   			?>
	   				<div class="form-group">
	   					<label class="col-lg-2 col-sm-2 control-label bold"> Remarks </label>
	   					<div class="col-lg-8 col-sm-8">
		   					<?php
		   						echo $this->Form->input('Budget.remark',array('type'=>'textarea','class'=>'wysihtml5 form-control'));
		   					?>
		   				</div>
	   				</div>

	   				<div class="form-group">
	   					<label class="col-lg-2 col-sm-2 control-label bold"> Financial Controller (FC) (*) </label>
	   					<div class="col-lg-8 col-sm-8">
		   					<?php
		   						// $reviewers = array();
		   						echo $this->Form->input('BReviewer.finance',array('type'=>'select','options'=>$finances,'class'=>'select2-sortable full-width','multiple'=>'multiple','required'));
		   					?>
		   					<br><small> Please select the financial controller </small>
		   				</div>
	   				</div>

	   				<div class="form-group">
	   					<label class="col-lg-2 col-sm-2 control-label bold"> Approver (CFO) (*) </label>
	   					<div class="col-lg-8 col-sm-8">
		   					<?php
		   						// $approvers = array();
		   						echo $this->Form->input('BReviewer.approver',array('type'=>'select','options'=>$approvers,'class'=>'select2-sortable full-width','multiple'=>'multiple','required'));
		   					?>
		   					<br><small> Please select the approver </small>
		   				</div>
	   				</div>
	   				<div class="form-group">
	   					<div class='col-lg-8'>
	   						(*) Denotes a required field
	   					</div>
	   				</div>

	   				<div class="form-group text-center">
	   					<?php
	   						echo $this->Html->link("<i class='fa fa-times'></i> Cancel",array('controller'=>'budget','action'=>'index'),array('escape'=>false,'class'=>"btn btn-danger"));
	   						echo "&nbsp;&nbsp;";
	   						echo $this->Form->button('<i class="fa fa-save"></i> Confirm ',array('type'=>'submit','escape'=>false,'class'=>'btn btn-success'));
	   						echo $this->Form->end();
	   					?>
	   				</div>
		  	</div> 
		</section>
		  </div>
  </section>
  </div>
  </div>  
</section>