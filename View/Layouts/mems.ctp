<!DOCTYPE html>
<html lang="en">
<head>
  <style type="text/css">
      #main-content {display:none;}
      .preload { 
          /*width:100px;
          height: 100px;*/
          position: fixed;
          top: 50%;
          left: 50%;
      }
  </style>
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
    // echo $this->Html->css('/assets/bootstrap-wysihtml5/bootstrap-wysihtml5');
    // echo $this->Html->css('/assets/bootstrap-wysihtml5/wysiwyg-color');
    // new wysihtml5
    // echo $this->Html->css('bootstrap3-wysihtml5.min');
    // echo $this->Html->css('bootstrap3-wysihtml5-editor.min');

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



    // JQuery at top
      echo $this->Html->script('/assets/jquery.appendGrid/jquery-1.11.1.min');
      // echo $this->Html->script('jquery'); 
     if($this->params['controller'] == 'budget'&& $this->params['action'] == 'request') {
      //appendGrid style
      echo $this->Html->css('/assets/jquery.appendGrid/jquery-ui.structure.min');
      echo $this->Html->css('/assets/jquery.appendGrid/jquery-ui.theme.min');

      echo $this->Html->css('/assets/jquery.appendGrid/jquery.appendGrid-1.6.2');
      


    }
  ?>
      
      
  
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <?php
      echo $this->Html->script('html5shiv');
      echo $this->Html->script('respond.min');
      ?>
    <![endif]-->


</head>
<body>
    <section id="container" >
        <?php
          echo $this->element('layout.header');
        ?>

        <?php
          // Left Sidebar element
          echo $this->element('layout.sidebar-left');

          // preloader
          echo $this->element('layout.preloader');
        ?>
        <!--main content start-->
        <section id="main-content">
          <section class="wrapper site-min-height">
            <?php
              echo $this->Session->flash();
              echo $this->element('layout.breadcrumbs');
              echo $this->fetch('content');
            ?>
          </section>
        </section>
        <!--main content end-->

        

        <?php
          // Footer
          echo $this->element('layout.footer');
        ?>
    </section>

  <?php
    // Right sidebar
    echo $this->element('layout.notification-right');
  ?>

    <!-- js placed at the end of the document so the pages load faster -->
    <?php
      // js placed at the end of the document so the pages load faster
      
      echo $this->Html->script('jquery-ui-1.9.2.custom.min');
      echo $this->Html->script('jquery-migrate-1.2.1.min');

      echo $this->Html->script('bootstrap.min');
      ?>
      <!-- <script>
          var btn = $.fn.button.noConflict(); // reverts $.fn.button to jqueryui btn
          $.fn.btn = btn; // assigns bootstrap button functionality to $.fn.btn

      </script> -->
      <?php




      echo $this->Html->script('jquery.dcjqaccordion.2.7',array('class'=>'include'));
      echo $this->Html->script('jquery.scrollTo.min');
      echo $this->Html->script('jquery.sparkline');
      echo $this->Html->script('/assets/jquery-easy-pie-chart/jquery.easy-pie-chart');
      echo $this->Html->script('owl.carousel');
      echo $this->Html->script('jquery.customSelect.min');
      echo $this->Html->script('respond.min');
      echo $this->Html->script('jquery.nicescroll');

      // JQuery Timeago
      echo $this->Html->script('jquery.timeago');

      //ckeditor
      // echo $this->Html->script('/assets/ckeditor/ckeditor');      

      // datatables
      echo $this->Html->script('/assets/advanced-datatable/media/js/jquery.dataTables');
      echo $this->Html->script('/assets/data-tables/DT_bootstrap');
      echo $this->Html->script('dynamic_table_init');

      // bootstrap fileupload
      echo $this->Html->script('/assets/bootstrap-fileupload/bootstrap-fileupload');

      // wysihtml5
      // echo $this->Html->script('/assets/bootstrap-wysihtml5/wysihtml5-0.3.0');
      // echo $this->Html->script('/assets/bootstrap-wysihtml5/bootstrap-wysihtml5');
      
      // new wysihtml5
      // echo $this->Html->script('wysihtml5x-toolbar.min');
      // echo $this->Html->script('bootstrap3-wysihtml5');

      // tinymce
      echo $this->Html->script('/assets/tinymce/tinymce.min.js');
      

      // knob
      echo $this->Html->script('/assets/jquery-knob/js/jquery.knob');

      //spinner
      echo $this->Html->script('/assets/fuelux/js/spinner.min');

      //datepicker
      echo $this->Html->script('/assets/bootstrap-datepicker/js/bootstrap-datepicker');

      //input mask
      echo $this->Html->script('/assets/bootstrap-inputmask/bootstrap-inputmask.min');

      // right slidebars
      echo $this->Html->script('slidebars.min');

      // common script for all pages
      echo $this->Html->script('common-scripts');

      // script for this page
      echo $this->Html->script('sparkline-chart');
      echo $this->Html->script('easy-pie-chart');
      echo $this->Html->script('count');

      //select2
      echo $this->Html->script('select2.min');

      //select2 sortable
      echo $this->Html->script('select2_sortable');

      //smoothscroll
      echo $this->Html->script('smooth-scroll.min');

      // mCustomScrollbar
      echo $this->Html->script('jquery.mCustomScrollbar.concat.min');

      // custom checkbox and radios
      echo $this->Html->script('ga');

      // stepy -- form wizard
      echo $this->Html->script('jquery.stepy');

      // MeMS Init
      echo $this->Html->script('mems.init');
      echo $this->Html->script('mems.script');
    
    //appendGrid

    if($this->params['controller'] == 'budget'&& $this->params['action'] == 'request') {
      echo $this->Html->script('/assets/jquery.appendGrid/jquery-ui-1.11.1.min'); 
      echo $this->Html->script('/assets/jquery.appendGrid/jquery.appendGrid-1.6.2');
               
    }
      
     //Sticky table header
      echo $this->Html->script('jquery.stickytableheaders.min');



    ?>

  <script>
      //custom select box

      $(function(){
          $('select.styled').customSelect();
      });

  </script>


  <script>
      //custom select box
      $(function(){
        $('.preload').fadeOut(0,function(){
          $('#main-content').fadeIn(0);
          // alert('test');
        })
      })

  </script>
  </body>
</html>