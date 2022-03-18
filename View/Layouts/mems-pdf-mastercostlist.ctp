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
<body style="background:none" class="text-left">
    <?php
        echo $this->Html->image('unitar-logo-head.jpg',array('max-width'=>'400px','style'=>'display:inline-block;vertical-align:top;margin-left:50px'));
    ?>
    <table class="pdf table text-left" style="width:auto;display:inline-block;margin-left:50px">
        <tr>
            <td colspan="3" class="noborder">
                <?php
                    echo "<h4 class='pdf-title bold no-margin-bottom'> MASTER COSTLIST </h4>";
                ?>
            </td>
            
        </tr>
        <tr>
            <td class="noborder" style="width:120px">
                <strong> Company </strong>
            </td>
            <td class="noborder" style="width: 10px">
                <strong> : </strong>
            </td>
            <td class="noborder">
                <?php
                    echo "<em>".$mclsCompany['Company']['company']."</em>";
                ?>
            </td>
        </tr>
        <tr>
            <td class="noborder" style="width:120px">
                <strong> Quarter </strong>
            </td>
            <td class="noborder" style="width: 10px">
                <strong> : </strong>
            </td>
            <td class="noborder">
                <?php
                    echo "<em>".$mclsQuarter . "</em>";
                ?>
            </td>
        </tr>
        <tr>
            <td class="noborder" style="width:120px">
                <strong> Year </strong>
            </td>
            <td class="noborder" style="width: 10px">
                <strong> : </strong>
            </td>
            <td class="noborder">
                <?php
                    echo "<em>".$mclsYear . "</em>";
                ?> 
            </td>
        </tr>
    </table>
<?php
    // echo $this->Html->image('unitar-logo-head.jpg',array('width'=>'300px'));
    // echo "<h4 class='pdf-title bold no-margin-bottom'> MASTER COSTLIST </h4>";
    // echo "<h5 class='bold'>";
    //     echo $mclsQuarter ;
    //     echo "<br>";
    //     echo "( ".$mclsYear. " )";
    // echo "</h5>";

    echo "<div class='text-left'>";
        echo $this->fetch('content');
    echo "</div>";
?>
  </body>
</html