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
  ?>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <?php
      echo $this->Html->script('html5shiv');
      echo $this->Html->script('respond.min');
      ?>
    <![endif]-->
</head>
<body class="">
  <div class="container">
    <section class="error-wrapper">
      <div>
        <?php 
          echo $this->Html->Image('unitar_logo.png',array('height' => ''));; 
        ?>
      </div>
        
      <div style="background:rgba(0,0,0,0.75);width: 50%;display:inline-block;padding: 30px 40px;margin-top:40px">
        <p class='timeline-title white' style="color:#FFF !important"> 
          Internal Server Error <br>
          Please contact administrator
        </p>
        <?php
          echo $this->Html->link("<i class='fa fa-chevron-circle-left'></i> Return to dashboard",
                                array('controller'=>'user','action'=>'userDashboard'),
                                array('escape'=>false,'class'=>'btn btn-lg bg-primary bold')
                             );

        ?>
      </div>     
      <?php
        echo $this->fetch('content');
      ?>
    </section>




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