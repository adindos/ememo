<!DOCTYPE html>
<html lang="en">
<head>
	<?php
    echo $this->element('layout.head.essentials');
  ?>

	<!-- Bootstrap core CSS -->
	<?php
		// Bootstrap core CSS
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('bootstrap-reset');

		// External CSS
		echo $this->Html->css('/assets/font-awesome/css/font-awesome');

		// Custom styles for this templates
    echo $this->Html->css('fontface');
		echo $this->Html->css('style');
		echo $this->Html->css('style-responsive');

    // MeMS Custom CSS
    echo $this->Html->css('mems');
	?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    	<?php
			echo $this->Html->script('html5shiv');
			echo $this->Html->script('respond.min');
    	?>
    <![endif]-->
</head>
<body class="login-body">

    <div class="container">
      <?php
        echo $this->fetch('content');
      ?>
    </div>

    <?php
    	// js placed at the end of the document so the pages load faster
    	echo $this->Html->script('jquery');
    	echo $this->Html->script('bootstrap.min');

      // MeMS Init
      echo $this->Html->script('mems.init');
      echo $this->Html->script('mems.script');
    ?>

  </body>
</html