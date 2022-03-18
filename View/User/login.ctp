<div class="login-logo text-center" style="margin-top:70px">
	<?php
		echo $this->Html->Image('mems-logo-login.png',array('style'=>'margin: 0 auto'));
	?>
</div>

<?php
	// echo $this->Html->image('mems-logo-2.png');
	echo $this->Form->create('User',
			array('url'=>array('controller'=>'user','action'=>'login'),
				'class'=>'form-signin','style'=>'margin-top:30px',
				'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false)
			)
		);
?>

	<h2 class="form-signin-heading" style="background : rgba(66,139,202,0.8)">sign in now</h2>
	<div class="login-wrap">
		<?php
			echo $this->Session->flash();
			echo $this->Form->input('User.email',array('type'=>'text','class'=>'form-control','placeholder'=>'Staff Login','autofocus'));
			echo $this->Form->input('User.password',array('type'=>'password','class'=>'form-control','placeholder'=>'Password'));
			echo $this->Form->button('Sign In',array('type'=>'submit','class'=>'btn btn-lg btn-login btn-block'));
		?>
	</div>
<?php
	echo $this->Form->end();
?>

<div class="unitar-logo-login text-center">
	<?php
		echo $this->Html->Image('unitar-logo-login.png',array('style'=>'width:200px;margin: 20px auto'));
	?>
</div>