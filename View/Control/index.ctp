<?php
    $this->Html->addCrumb('Memo Control', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
    <div class="row">
          <section class="col-lg-12">
          	<section class="panel" >
	          <header class="panel-heading">
	            Enable / Disable Memo Access
	          </header>
	          <div class="panel-body">
	          	<div class='col-lg-6'>
	          		<br/>
	          		<?php
	          			if ($setting['Setting']['financial_memo'])
	          				echo $this->Form->postLink('<strong> Enable Financial Memo Access</strong><br><small> Enable access for financial memo </small>',array('controller'=>'control','action'=>'enable','fmemo'),array('escape'=>false,'class'=>'btn btn-danger  full-width'));
	          			else
	          				echo $this->Form->postLink('<strong> Disable Financial Memo Access</strong><br><small> Disable access for financial memo </small>',array('controller'=>'control','action'=>'disable','fmemo'),array('escape'=>false,'class'=>'btn btn-primary  full-width'));
	          		?>
	          		<br/><br/>
	          			
	          	</div>
	          	<div class='col-lg-6'>
	          		<br/>
	          		<?php
	          			if ($setting['Setting']['nonfinancial_memo'])
	          				echo $this->Form->postLink('<strong> Enable Non-Financial Memo Access</strong><br><small> Enable access for non-financial memo </small>',array('controller'=>'control','action'=>'enable','nfmemo'),array('escape'=>false,'class'=>'btn btn-danger  full-width'));
	          			else
	          			echo $this->Form->postLink('<strong> Disable Non-Financial Memo Access</strong><br><small> Disable access for non-financial memo </small>',array('controller'=>'control','action'=>'disable','nfmemo'),array('escape'=>false,'class'=>'btn btn-primary  full-width'));
	          		?>
	          		<br/><br/>
	          	</div>
	          </div>
	        </section>
          </section>

    </div>

</section>