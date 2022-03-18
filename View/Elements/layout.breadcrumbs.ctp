<?php
	if($this->params['controller'] == 'user' && $this->params['action'] == 'userDashboard'):
		echo "";

	
	elseif($this->params['controller'] == 'setting' || ($this->params['controller'] == 'user' && $this->params['action'] == 'statistic')):
?>
	<ol class="breadcrumb breadcrumb-empty">
	  <?php
	  	echo $this->Html->getCrumbs("<li class='crumb-separator'> <i class='fa fa-angle-double-right'></i> </li>", array(
		    'text' => $this->Html->image('home.png',array('height'=>'16px','class'=>'tooltips','data-original-title'=>'Home')),
		    'url' => array('controller' => 'user', 'action' => 'userDashboard'),
		    'escape' => false,
		));
	  ?>
	</ol>
<?php
	else:
?>
	<ol class="breadcrumb">
	  <?php
	  	echo $this->Html->getCrumbs("<li class='crumb-separator'> <i class='fa fa-angle-double-right'></i> </li>", array(
		    'text' => $this->Html->image('home.png',array('height'=>'16px','class'=>'tooltips','data-original-title'=>'Home')),
		    'url' => array('controller' => 'user', 'action' => 'userDashboard'),
		    'escape' => false,
		));
	  ?>
	</ol>
<?php
	endif;
?>