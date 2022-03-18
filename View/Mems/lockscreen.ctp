<?php
	// echo $this->Form->create('User',array('url'=>array('controller'=>'mems','action'=>'go','?'=>array('token'=>$token))));
	echo $this->Form->create('User',array('inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false),'class'=>'form-inline'));
	echo "<div class='form-group col-lg-12'>";
	echo $this->Form->hidden('User.email',array('type'=>'text','value'=>$user['User']['email']));
	echo $this->Form->input('User.password',array('type'=>'password','placeholder'=>'Password','class'=>'form-control lock-input'));
	echo $this->Form->button('<i class="fa fa-arrow-right"></i>',array('type'=>'submit','escape'=>false,'class'=>'btn btn-lock'));
	echo "</div>";
	echo $this->Form->end();
?>