
<?php
/*
*   -------------------------------
*   layout.body.menu-admin
*   ------------------------------
*       - Menu for admin
*       - Check if the person is admin,then display
*   Created : 23/03/2014
*   Revision : 23/03/2014
*


    ================================================
        SIDEBAR WILL ONLY SHOW ACCORDING TO ROLES
        ( Need to differentiate between each staff role )
        ACCESSION : $activeUser['StaffRole']['var']
    ================================================
*/

    ?>



    <!--sidebar start-->
    <aside>
        <div id="sidebar"  class="nav-collapse ">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu" id="nav-accordion">
                <!-- Dashboard -->
                <li>

                    <?php 
                    $roles = $activeUser['Role'];
                    ?>

                    <?php
                    //debug($roles['role_id']); exit;
             // if($roles['role_id']!='18'){                   
                    if($roles['dashboard'] == 1){

                        if($this->params['action'] == 'userDashboard') {

                            echo $this->Html->link("<i class='fa fa-dashboard'></i><span> Dashboard </span>",
                                array('controller'=>'user','action'=>'userDashboard'),
                                array('escape'=>false,'class'=>'active')
                                );
                        }else{

                            echo $this->Html->link("<i class='fa fa-dashboard'></i><span> Dashboard </span>",
                                array('controller'=>'user','action'=>'userDashboard'),
                                array('escape'=>false)
                                );

                        }

                    }
                 // }


                    ?>
                </li>
                <li>
                    <?php
                    if($roles['dashboard'] == 1){
                        if($this->params['action'] == 'statistic') {

                         echo $this->Html->link("<i class='fa fa-bar-chart-o'></i><span> General Statistics </span>",
                            array('controller'=>'user','action'=>'statistic'),
                            array('escape'=>false,'class' => 'active')
                            );
                     }else{

                        echo $this->Html->link("<i class='fa fa-bar-chart-o'></i><span> General Statistics </span>",
                           array('controller'=>'user','action'=>'statistic'),
                           array('escape'=>false)
                           );

                    }

                }
                ?>
            </li>


            <?php
            // if($roles['role_id']=='18'){ #ememo2 only adminFinance,FC,COO 
            if ($roles['my_request_budget'] == 1 || $roles['all_request_budget'] == 1 || $roles['request_management_budget'] == 1 || $roles['budget_archive'] == 1 || $roles['report_budget'] == 1){ 
             ?>
             <!-- Budget -->
             <li class="sub-menu ">

                <?php if($this->params['controller'] == 'budget' || $this->params['controller'] == 'archive'|| ($this->params['controller'] == 'report'&&($this->params['action'] == 'budgetUtilizationReport'||$this->params['action'] == 'budgetTransferReport'||$this->params['action'] == 'unbudgetedReport'))) { ?>
                <a href='javascript:;' class='active'>
                    <?php }else{ ?>
                    <a href='javascript:;'>
                        <?php } ?>

                        <i class="fa fa-table"></i>
                        <span> Budget </span>
                    </a>
                    <ul class="sub">
                        <li>
                            <?php
                            // my request

                            if($roles['my_request_budget'] == 1){
                                echo $this->Html->link("My Requests",
                                    array('controller'=>'budget','action'=>'index'),
                                    array('escape'=>false)
                                    );
                            }
                            ?>
                        </li>
                        <li>
                            <?php
                            // all request
                            if($roles['all_request_budget'] == 1){

                                echo $this->Html->link("Budget List",
                                    array('controller'=>'budget','action'=>'allrequest'),
                                    array('escape'=>false)
                                    );
                            }
                            ?>
                        </li>
                        <li>
                            <?php
                            // request management
                            if($roles['request_management_budget'] == 1){

                                echo $this->Html->link("Request Management",
                                    array('controller'=>'budget','action'=>'myreview'),
                                    array('escape'=>false)
                                    );
                            }
                            ?>
                        </li>
                        <li>
                            <?php
                            // budget archive
                            if($roles['budget_archive'] == 1){
                                echo $this->Html->link("Budget Archive",
                                    array('controller'=>'archive','action'=>'budgetArchive'),
                                    array('escape'=>false)
                                    );
                            }
                            ?>
                        </li>
                        <li>
                            <?php
                            // budget report
                            if($roles['report_budget'] == 1){
                                echo $this->Html->link("Budget Report",
                                    array('controller'=>'report','action'=>'budgetUtilizationReport'),
                                    array('escape'=>false)
                                    );
                            }
                            ?>
                        </li>
                       
                    </ul>
                </li>

                <?php 
                } 
            // }#end of check for role
            ?>


            <?php
            // if($roles['role_id']!='18'){#ememo2 other than adminFinance
            if ($roles['my_request_financial_memo'] == 1 || $roles['all_request_financial_memo'] == 1 || $roles['request_management_financial_memo'] == 1 || $roles['report_financial_memo'] == 1 ){ 
              ?>
              <!-- Financial Memo -->
              <li class="sub-menu">
                <?php if($this->params['controller'] == 'fMemo'||($this->params['controller'] == 'report'&&$this->params['action'] == 'financialMemoReport')) { ?>
                <a href='javascript:;' class='active'>
                   <?php }else{ ?>
                   <a href='javascript:;'>
                       <?php } ?>
                       <i class="fa fa-book"></i>
                       <span>Financial Memo</span>
                   </a>
                   <ul class="sub">
                    <li>
                        <?php
                            // my request
                        if($roles['my_request_financial_memo'] == 1){

                            echo $this->Html->link("My Requests",
                                array('controller'=>'fMemo','action'=>'index'),
                                array('escape'=>false)
                                );
                        }
                        ?>
                    </li>
                    <li>
                        <?php
                            // All Request
                        if($roles['all_request_financial_memo'] == 1){

                            echo $this->Html->link("All Request",
                                array('controller'=>'fMemo','action'=>'allRequest'),
                                array('escape'=>false)
                                );
                        }
                        ?>
                    </li>
                    <li>
                        <?php
                            // request management
                        if($roles['request_management_financial_memo'] == 1){

                            echo $this->Html->link("Request Management",
                                array('controller'=>'fMemo','action'=>'myReview'),
                                array('escape'=>false)
                                );
                        }
                        ?>
                    </li>
                    <li>
                        <?php
                            // my memo
                        if($roles['my_memo_financial_memo'] == 1){

                            echo $this->Html->link("My Memo",
                                array('controller'=>'fMemo','action'=>'myMemo'),
                                array('escape'=>false)
                                );
                       }
                        ?>
                    </li>
                    <li>
                        <?php
                            // my memo
                        if($roles['report_financial_memo'] == 1){

                            echo $this->Html->link("Memo Report",
                                array('controller'=>'report','action'=>'financialMemoReport'),
                                array('escape'=>false)
                                );
                       }
                        ?>
                    </li>
                </ul>
            </li>

            <?php } 
            // }#end of check for role?>


            <?php
            // if($roles['role_id']!='18'){#ememo2 other than adminFinance
            if ($roles['my_request_memo'] == 1 || $roles['all_request_memo'] == 1 || $roles['request_management_memo'] == 1|| $roles['report_memo'] == 1){ 
              ?>

              <!-- Non-Financial Memo -->
              <li class="sub-menu">
                <?php if($this->params['controller'] == 'NfMemo2'||($this->params['controller'] == 'report'&&$this->params['action'] == 'memoReport')) { ?>
                <a href='javascript:;' class='active'>
                    <?php }else{ ?>
                    <a href='javascript:;'>
                        <?php } ?>
                        <i class="fa fa-book"></i>
                        <span>Non-Financial Memo</span>
                    </a>
                    <ul class="sub">
                        <li>
                            <?php
                            // my request
                            if($roles['my_request_memo'] == 1){

                                echo $this->Html->link("My Requests",
                                    array('controller'=>'NfMemo2','action'=>'index'),
                                    array('escape'=>false)
                                    );
                            }
                            ?>
                        </li>
                        <li>
                            <?php
                            // All Request
                            if($roles['all_request_memo'] == 1){

                                echo $this->Html->link("All Request",
                                    array('controller'=>'NfMemo2','action'=>'allRequest'),
                                    array('escape'=>false)
                                    );
                            }
                            ?>
                        </li>
                        <li>
                            <?php
                            // request management
                            if($roles['request_management_memo'] == 1){

                                echo $this->Html->link("Request Management",
                                    array('controller'=>'NfMemo2','action'=>'MyReview'),
                                    array('escape'=>false)
                                    );
                            }
                            ?>
                        </li>
                        <li>
                        <?php
                            // my memo
                        if($roles['my_memo_memo'] == 1){

                            echo $this->Html->link("My Memo",
                                array('controller'=>'NfMemo2','action'=>'myMemo'),
                                array('escape'=>false)
                                );
                       }
                        ?>
                    </li>
                    <li>
                        <?php
                            // my memo
                        if($roles['report_memo'] == 1){

                            echo $this->Html->link("Memo Report",
                                array('controller'=>'report','action'=>'memoReport'),
                                array('escape'=>false)
                                );
                       }
                        ?>
                    </li>
                    </ul>
                </li>
                <?php }
                // }#end of check for role ?>

                <?php   if($roles['settings'] == 1){
                   ?>
                   <!-- Setting -->
                   <li>
                    <?php
                    if($this->params['controller'] == 'setting') {

                        echo $this->Html->link("<i class='fa fa-gear'></i><span> Setting </span>",
                            array('controller'=>'setting','action'=>'index'),
                            array('escape'=>false,'class'=>'active')
                            );
                    }else{

                        echo $this->Html->link("<i class='fa fa-gear'></i><span> Setting </span>",
                            array('controller'=>'setting','action'=>'index'),
                            array('escape'=>false,'class'=>'')
                            );

                    }
                    ?>
                </li>
                <?php } ?>
                <?php  if($activeUser['role_id']==17||$activeUser['finance']){#ememo2 memo control : visible only to adminFinance
                   ?>
                   <!-- Setting -->
                   <li>
                    <?php
                    if($this->params['controller'] == 'control') {

                        echo $this->Html->link("<i class='fa fa-gear'></i><span> Enable/Disable Memo </span>",
                            array('controller'=>'control','action'=>'index'),
                            array('escape'=>false,'class'=>'active')
                            );
                    }else{

                        echo $this->Html->link("<i class='fa fa-gear'></i><span> Enable/Disable Memo </span>",
                            array('controller'=>'control','action'=>'index'),
                            array('escape'=>false,'class'=>'')
                            );

                    }
                    ?>
                </li>
                <?php } ?>


            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
<!--sidebar end-->