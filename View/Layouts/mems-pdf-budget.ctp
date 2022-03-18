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
		echo $this->Html->css('/assets/jquery-easy-pie-chart/jquery.easy-pie-chart',array('media'=>'screen'));
		echo $this->Html->css('owl.carousel');

    // Dynamic table
    echo $this->Html->css('datatable.pages');
    echo $this->Html->css('datatable.tables');
    echo $this->Html->css('/assets/data-tables/DT_bootstrap');

    // bootstrap-fileupload
    echo $this->Html->css('/assets/bootstrap-fileupload/bootstrap-fileupload');

    // wysihtml5
    echo $this->Html->css('/assets/bootstrap-wysihtml5/bootstrap-wysihtml5');
    echo $this->Html->css('/assets/bootstrap-wysihtml5/wysiwyg-color');

    //select2
    echo $this->Html->css('select2.min');

    // datepicker
    echo $this->Html->css('/assets/bootstrap-datepicker/css/datepicker');

		// Right slidebar
		echo $this->Html->css('slidebars');

    // mCustomScrollBar
    echo $this->Html->css('jquery.mCustomScrollbar.min');

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
<body style="background:none" class="text-center">
<?php
    echo $this->Html->image('unitar-logo-head.jpg',array('width'=>'300px'));
    echo "<h4 class='pdf-title bold'> INTERNAL BUDGET </h4>";

    echo "<div class='text-left'>";
        echo $this->fetch('content');
    echo "</div>";
?>
  </body>
</html>