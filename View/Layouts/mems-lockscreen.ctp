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
<body class="lock-screen" onload="startTime()">

    <div class="lock-wrapper">

        <div id="time" style="margin-bottom:20px;"></div>

        <?php
          echo $this->Session->flash();
        ?>
        <br><br>
        <div class="lock-box text-center">
            <img src="img/unitar-logo-only.png" alt="lock avatar"/>

            <h1>
              <?php
                echo $user['User']['staff_name'];
              ?>
            </h1>
            <span class="locked">Locked</span>
            <?php
              echo $this->fetch('content');
            ?>
        </div>        
    </div>

    <?php
      // js placed at the end of the document so the pages load faster
      echo $this->Html->script('jquery');
      echo $this->Html->script('bootstrap.min');
    ?>

    <script>
        function startTime()
        {
            var today=new Date();
            var h=today.getHours();
            var m=today.getMinutes();
            var s=today.getSeconds();
            // add a zero in front of numbers<10
            m=checkTime(m);
            s=checkTime(s);
            document.getElementById('time').innerHTML=h+":"+m+":"+s;
            t=setTimeout(function(){startTime()},500);
        }

        function checkTime(i)
        {
            if (i<10)
            {
                i="0" + i;
            }
            return i;
        }
    </script>
</body>
</html