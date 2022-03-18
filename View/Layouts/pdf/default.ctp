<!DOCTYPE html>
	<head>
		<!-- Webfonts -->
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans|Oswald|Open+Sans+Condensed:300italic|Roboto' rel='stylesheet' type='text/css'>
		<?php
			// For all browser css
			echo $this->Html->css('reset');
			echo $this->Html->css('style');
			echo $this->Html->css('colors');
			echo $this->Html->css('print',array('media'=>'print'));

			// Elements
			echo $this->Html->css('styles/table');
			

		    // MeMS Custom CSS
		    echo $this->Html->css('mems');




		?>
	</head>
	<body>
		<div class="with-padding">
			<table class="easy" style='width:100%;color : #000;font-size:16px'>
	        <tr>
	        <td class="align-center"  width="20%" style="vertical-align:middle">
	        <?php
	            echo $this->Html->image('csem-logo-minimal.png',array('width'=>'80%'));
	        ?>
	        </td>
	        <td colspan="4" class="with-padding" style="vertical-align:middle">
	            <h3>  <span style="font-size:14px"> </span> </h3>
	        </td>
	        </tr>
	        <tr>
	            <td width="20%" class="text bigger-text">
	                Document Title
	            </td>
	            <td width="40%" class="text bigger-text uppercase text-bold">
	                <?php
	                	if(isset($documentTitle)){
	                		echo $documentTitle;
	                	}
	                	else{
	                		echo "<em> No document title </em>";
	                	}
	                ?>
	            </td>
	            <td width="20%" class="text bigger-text">
	                Document No.
	            </td>
	            <td width="20%" class="text bigger-text uppercase text-bold">
	                <?php
	                    if(isset($documentNo)){
	                    	echo $documentNo;
	                    }
	                    else{
	                    	echo "<em> No document no. </em>";
	                    }
	                ?>
	            </td>
	        </tr>
	        </tr>
	        </table>
	    </div>
		<?php 
			//Content to be displayed --fetch from Controller & Views
			echo $this->fetch('content');

		?>
	</body>
</html>