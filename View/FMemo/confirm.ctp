<?php
  $encMemoID = $this->Mems->encrypt($memo_id);
  $this->Html->addCrumb('Financial Memo', array('controller' => 'fMemo', 'action' => 'index'));
  $this->Html->addCrumb('Temporary Ref. No : 000/'.$memo_id, array('controller' => 'fMemo', 'action' => 'dashboard',$encMemoID));
  $this->Html->addCrumb('Confirm Financial Memo', $this->here,array('class'=>'active'));
?>


<section class="mems-content">
  <div class="row">
	<div class="col-sm-12">
	  	<section class="panel">
			<header class="panel-heading">
				Complete the form below to confirm the memo
		   	</header>
	   		<div class="panel-body">
	   			<?php
	   				echo $this->Form->create('FMemo',array('url'=>array('controller'=>'FMemo','action'=>'confirmMemo'),
	   														'class'=>'form-horizontal tasi-form','type'=>'file',
	   														'onSubmit'=>'return confirm("By confirming the memo, there will be no changes possible after. Are you sure you want to confirm this memo?")','inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false)));
	   				echo $this->Form->hidden('FMemo.memo_id',array('value'=>$memo_id));
	   			?>
	   				<div class="form-group">
	   				<div class="col-sm-3">
	   					<label class="control-label bold"> Remarks </label>
	   					</div>
	   					<div class="col-lg-8 col-sm-8">
		   					<?php
		   						echo $this->Form->input('FMemo.remark',array('type'=>'textarea','class'=>'wysihtml5 form-control'));
		   					?>
		   				</div>
	   				</div>

	   				<div class="form-group">
	   					<div class="col-sm-3">
	   					<label class="control-label bold"> Reviewer * </label>
							<button type="button" data-original-title="Reviewer" 
							data-content="   <div>Select your reviewers(s). You can have more than one (1) Reviewer(s), someone within and outside your Department that involve directly or indirectly in the matter. Prioritise the Reviewer(s) in ascending order.</div></br> "     
							data-placement="top" data-trigger="hover" class="btn btn-round bg-primary btn-xs popovers-with-html"><i class="fa fa-question-circle"></i>
							</button>
						</div>
	   					<div class="col-lg-8 col-sm-8">
		   					<?php
		   						// $reviewers = array();
		   						echo $this->Form->input('FReviewer.reviewer',array('type'=>'select','options'=>$reviewers,'class'=>'select2-sortable full-width','multiple'=>'multiple','style'=>'width:100%'));
		   					?>
		   					<br><small> Please select the reviewer </small>
		   				</div>
	   				</div>
	   				<div class="form-group">
	   					<div class="col-sm-3">
	   					<label class="control-label bold"> Recommender </label>
							<button type="button" data-original-title="Recommender" 
							data-content="   <div>Select only one (1) Recommender. Recommender should be someone external from your Department, e.g. the Vice Chancellor.</div></br> "     
							data-placement="top" data-trigger="hover" class="btn btn-round bg-primary btn-xs popovers-with-html"><i class="fa fa-question-circle"></i>
							</button>
					</div>
	   					<div class="col-lg-8 col-sm-8">
		   					<?php
		   						// $reviewers = array();
		   						echo $this->Form->input('FReviewer.recommender',array('type'=>'select','options'=>$recommenders,'class'=>'select2-sortable full-width','multiple'=>'multiple','style'=>'width:100%'));
		   					?>
		   					<br><small> Please select the recommender </small>
		   				</div>
	   				</div>
	   				<div class="form-group">
	   				<div class="col-sm-3">
	   					<label class="control-label bold"> Finance *</label>
	   					</div>
	   					<div class="col-lg-8 col-sm-8">
		   					<?php
		   						// $reviewers = array();
		   						echo $this->Form->input('FReviewer.finance',array('type'=>'select','options'=>$finances,'class'=>'select2-sortable full-width','multiple'=>'multiple','style'=>'width:100%'));
		   					?>
		   					<br><small> Please select the finance </small>
		   				</div>
	   				</div>

	   				<div class="form-group">
	   				<div class="col-sm-3">
	   					<label class="control-label bold"> Approver * </label>
	   					<button type="button" data-original-title="Reviewer" 
							data-content="   <div>Select COO/CFO and/or CEO only, which guided by the Limit of Authority (LOA). Prioritise the Approver in ascending order.</div></br> "     
							data-placement="top" data-trigger="hover" class="btn btn-round bg-primary btn-xs popovers-with-html" ><i class="fa fa-question-circle"></i>
							</button>
	   					</div>
	   					<div class="col-lg-8 col-sm-8">
		   					<?php
		   						// $approvers = array();
		   						echo $this->Form->input('FReviewer.approver',array('type'=>'select','options'=>$approvers,'class'=>'select2-sortable full-width','multiple'=>'multiple','style'=>'width:100%'));
		   					?>
		   					<br><small> Please select the approver </small>
		   				</div>
	   				</div>

	   				<div class="form-group" style="text-align:center">
	   					<?php
	   						echo $this->Html->link("<i class='fa fa-times'></i> Cancel",array('controller'=>'user','action'=>'userDashboard'),array('escape'=>false,'class'=>"btn btn-danger"));
	   						echo "&nbsp;&nbsp;";
	   						echo $this->Form->button("<i class='fa fa-save'></i> Confirm Memo",array('type'=>'submit','class'=>'btn btn-success'));
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