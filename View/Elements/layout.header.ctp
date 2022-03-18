<!--header start-->
<header class="header white-bg">
  <div class="sidebar-toggle-box">
      <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Navigation"></div>
  </div>
    <?php
        $logoHref = $this->Html->Image('mems-logo.png',array('height'=>'35px'));
        echo $this->Html->link($logoHref,
                                array('controller'=>'user','action'=>'userDashboard'),
                                array('escape'=>false,'class'=>'logo mems-logo')
                            );
    ?>
    <!--logo end-->

    <a class="new-top-nav pull-right sb-toggle-right">
        <?php
            echo $this->Html->Image('unitar-logo-only.png',array('style'=>'height:35px;width:auto','alt'=>'Profile Picture','id'=>'profile-pic-top'));
        ?>
        <span class="username"><?php echo $activeUser['staff_name'] ?></span>
        <span class="badge bg-noti">
            <span class="num-noti">
                <?php
                    echo count($notifications);
                ?>
            </span>
        </span>
        <b class="caret"></b>
    </a>
    
</header>
<!--header end