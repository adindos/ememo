<section class="mems-content pdf-text-overwrite">
    <hr class="pdf-desc">
        <table class='pdf table bigger-text'>
            <tr>
                <td class='bold noborder' style="width:14%">
                    Budget Title
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                    <?php
                        echo $budgetDetail['Budget']['title'];
                    ?>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    Department
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                    <?php
                        echo $budgetDetail['Department']['department_name'];
                    ?>
                </td>
            </tr>
            <tr>
                <td class='bold noborder' style="width:14%">
                    Quarter / Year
                </td>
                <td class="bold noborder" style="width:1%">
                    :
                </td>
                <td class="noborder" style="width:85%">
                    <?php
                        echo $budgetDetail['Budget']['quarter'] . " ( ".$budgetDetail['Budget']['year']. " )";
                    ?>
                </td>
            </tr>
        </table>  
    <hr class="pdf-desc">
	<!-- Budget -->
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading noborder">
				<strong>
					1. Budget Review
				</strong>
			</header>
			<div class="panel-body" style="padding:0 30px">
	            <table class="table table-advance table-bordered">
		            <thead>
		              <tr class="">
		                <th colspan="2" class="col-lg-10" style="width:80%"> Description </th>
		                <th class="text-center col-lg-2" style="width:20%"> Amount (RM) </th>
		              </tr>
		            </thead>
		            <tbody>
		              <?php
		                $categoryID =null;
		                $no=1;
		                $totalAmount  = 0;
		                foreach($budgetItem as $b):
		                  if($b['BItem']['category_id'] != $categoryID):
		                    echo "<tr class='medium-grey-bg bold'>";
		                    echo "<td colspan='3'>".$b['BItem']['BCategory']['category'] . "</td>";
		                    echo "</tr>";
		                    $no=1;
		                  endif;

		                  //iterate item
		                  echo "<tr>";
		                  echo "<td class='text-center col-lg-1' style='width:8%'>".$no++.".</td>";
		                  echo "<td style='width:72%'>".$b['BItem']['item']."</td>";
		                  echo "<td class='text-center' style='width:20%'><b>".number_format($b['BNewAmount']['amount'],0,".",",")."</b></td>";
		                  echo "</tr>";

		                  $categoryID = $b['BItem']['category_id']; 
		                  $totalAmount += $b['BNewAmount']['amount'];
		                endforeach;
		              ?>
		              <tr class="unitar-grey-bg">
		                <td colspan="2" class="bold text-right bigger-text" style='width:80%'> Total Amount </td>
		                <td class="text-center bold bigger-text" style='width:20%'><?php echo number_format($totalAmount,0,".",","); ?></td>
		              </tr>
		            </tbody>
		          </table>
            </div>
		</section>
	</div>

    

	<div class="col-lg-12">

        <!-- <div class="pdf-page-break"></div> -->

		<section class="panel">
			<header class="panel-heading noborder">
				<strong>
					2. Division/Department's Review/Recommendation
				</strong>
				<small> ( Guided by Division/Department LOA ) </small>
			</header>
			
			<div class="panel-body" style="padding:0 30px">

                <table class="pdf table">
                    <tr>
                        <td class="col-lg-3 noborder" style="width:25%">    
                            <h5 class="bold no-margin-bottom"> Prepared By : </h5>
                            <p>
                            <?php
                                echo "<small>";
                                echo $budgetDetail['User']['staff_name'];
                                echo "<br>";
                                echo $budgetDetail['Department']['department_name'];
                                echo "<br>";
                                echo $budgetDetail['User']['designation'];
                                echo "<br>";
                                echo date('d F Y',strtotime($budgetDetail['Budget']['created'])); 
                                echo "</small>";
                            ?>
                            </p>
                        </td>
                        <td class="col-lg-9 noborder" style="width:75%">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th class="noborder"> Remark(s)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php 
                                                if (!empty($budgetDetail['Budget']['remark'])) 
                                                    echo nl2br($budgetDetail['Budget']['remark']); 
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Reviewer remark -->
                <?php 
                    if (!empty($reviewers)):
                        $counter=0;
                        foreach ($reviewers as $reviewer):
                ?>
                        <table class="pdf table">
                            <tr>
                                <td class="col-lg-3 noborder" style="width:25%">
                                    <h5 class="bold no-margin-bottom">
                                        <?php
                                            echo $this->Mems->ordinal(($counter+1));
                                        ?>
                                        Reviewed By : 
                                    </h5>
                                    <p>
                                        <?php
                                            echo "<small>";
                                            echo $reviewer['User']['staff_name'];
                                            echo "<br>";
                                            echo $reviewer['User']['Department']['department_name'];
                                            echo "<br>";
                                            echo $reviewer['User']['designation'];
                                            echo "<br>";
                                            echo date('d F Y',strtotime($reviewer['BStatus'][0]['modified'])); 
                                            echo "</small>";
                                        ?>
                                    </p>
                                </td>
                                <td class="col-lg-9 noborder" style="width:75%">
                                    <table class="table table-bordered table-condensed">
                                        <thead>
                                            <tr>
                                                <th class="light-grey-bg" style="width:5%;text-align:center">
                                                    <?php 
                                                        if ($reviewer['BStatus'][0]['status']=='approved') 
                                                            echo '<i class="fa fa-check"></i>' ;
                                                    ?>
                                                </th>
                                                <th class="" style="width:25%">Approved</th>
                                                <th class="light-grey-bg" style="width:5%;text-align:center">
                                                    <?php 
                                                        if ($reviewer['BStatus'][0]['status']=='rejected') 
                                                            echo '<i class="fa fa-check"></i>' ;
                                                    ?>
                                                </th>
                                                <th class="" style="width:25%">Rejected</th>
                                                <th class="" style="width:40%">Other remark(s)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr style="text-align:justify">
                                                <td colspan="2">
                                                    <p style="text-align:justify">
                                                    <?php 
                                                        if (!empty($reviewer['BStatus'][0]['remark']) && $reviewer['BStatus'][0]['status']=='approved') 
                                                            echo '<h5><b>Remark(s) : </b></h5>'. nl2br($reviewer['BStatus'][0]['remark']);
                                                        ?>
                                                    </p>
                                                </td>
                                                <td colspan="2">
                                                    <p style="text-align:justify">
                                                    <?php 
                                                        if (!empty($reviewer['BStatus'][0]['remark']) && $reviewer['BStatus'][0]['status']=='rejected') 
                                                            echo '<h5><b>Remark(s) : </b></h5>'.nl2br($reviewer['BStatus'][0]['remark']); 
                                                    ?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if (!empty($remark_reviewer[$counter])):
                                                            foreach ($remark_reviewer[$counter] as $val):
                                                    ?>
                                                            <h5><b>Remarks : 
                                                            <?php 
                                                                if (!empty($val['BRemark']['subject'])) 
                                                                    echo $val['BRemark']['subject']; 
                                                            ?>
                                                            </b></h5>
                                                            <p>
                                                                <b>To</b> : 
                                                                <?php 
                                                                    if (!empty($val['BRemarkAssign'])):
                                                                        foreach ($val['BRemarkAssign'] as $v) {
                                                                            if ($val['User']['staff_name']!=$v['User']['staff_name'])
                                                                                echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
                                                                        }
                                                                    endif;
                                                                ?>
                                                            </p>
                                                            <p style="text-align:justify">
                                                                <?php 
                                                                    if (!empty($val['BRemarkFeedback'][0]['feedback'])) 
                                                                        echo $val['BRemarkFeedback'][0]['feedback']; ?>
                                                            </p> 


                                                            <table class="table">
                                                                <?php 
                                                                    if (count($val['BRemarkFeedback'])>1):
                                                                        for ($i=1;$i<count($val['BRemarkFeedback']);$i++):
                                                                ?>
                                                                    <tr style="text-align:justify">
                                                                        <td>
                                                                            <h5><b>Feedback from 
                                                                            <?php 
                                                                                echo $val['BRemarkFeedback'][$i]['User']['staff_name'];
                                                                                echo " ( "; 
                                                                                echo date('d M Y',strtotime($val['BRemarkFeedback'][$i]['created']));
                                                                                echo " ) ";
                                                                            ?>
                                                                            :</b></h5>
                                                                            <p>
                                                                                <?php 
                                                                                    if (!empty($val['BRemarkFeedback'][$i]['feedback'])) echo $val['BRemarkFeedback'][$i]['feedback']; 
                                                                                ?>
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                <?php 
                                                                        endfor;
                                                                    endif; 
                                                                ?>
                                                            </table>

                                                    <?php 
                                                            endforeach;
                                                        endif; 
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                            
                <?php 
                        $counter++;
                        endforeach;
                    endif; 
                ?>
            </div>
		</section>

        <!-- <div class="pdf-page-break"></div> -->

		<section class="panel">
			<header class="panel-heading noborder">
				<strong> 
					3. Approval 
				</strong>
				<small> ( Guided by Corporate LOA ) </small>
			</header>
			
			<div class="panel-body"  style="padding:0 30px">
				<!-- Reviewer remark -->
				<?php 
					if (!empty($approvers)):
	            		$counter=0;
                        foreach ($approvers as $approver):
                ?>
                        <table class="pdf table">
                            <tr>
                                <td class='col-lg-3 noborder' style="width:25%">
                                    <h5 class="bold no-margin-bottom">
                                        <?php
                                            echo $this->Mems->ordinal(($counter+1));
                                        ?>
                                        Approved By : 
                                    </h5>
                                    <p>
                                        <?php
                                            echo "<small>";
                                            echo $approver['User']['staff_name'] ;
                                            echo "<br>";
                                            echo $approver['User']['Department']['department_name'];
                                            echo "<br>";
                                            echo $approver['User']['designation'];
                                            echo "<br>";
                                            echo date('d F Y',strtotime($approver['BStatus'][0]['modified'])); 
                                            echo "</small>";
                                        ?>
                                    </p>
                                </td>
                                <td class="col-lg-9 noborder" style="width:75%">
                                    <table class="table table-bordered table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="light-grey-bg" style="width:5%;text-align:center">
                                                <?php 
                                                    if ($approver['BStatus'][0]['status']=='approved') 
                                                        echo '<i class="fa fa-check"></i>' ;
                                                ?>
                                            </th>
                                            <th class="" style="width:20%">Approved</th>
                                            <th class="light-grey-bg" style="width:5%;text-align:center">
                                                <?php 
                                                    if ($approver['BStatus'][0]['status']=='rejected') 
                                                        echo '<i class="fa fa-check"></i>' ;
                                                ?>
                                            </th>
                                            <th class="" style="width:20%">Rejected</th>
                                            <th class="" style="width:40%">Other remark(s)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr style="text-align:justify">
                                        <td colspan="2">
                                            <!-- <h5><b>Remark(s) : </b></h5> -->
                                            <p style="text-align:justify">
                                                <?php 
                                                    if (!empty($approver['BStatus'][0]['remark']) && $approver['BStatus'][0]['status']=='approved') 
                                                        echo '<h5><b>Remark(s) : </b></h5>'. nl2br($approver['BStatus'][0]['remark']);
                                                    ?>
                                                </p>
                                            </td>
                                            <td colspan="2">
                                                <!-- <h5><b>Remark(s) : </b></h5> -->
                                                <p style="text-align:justify">
                                                    <?php 
                                                        if (!empty($approver['BStatus'][0]['remark']) && $approver['BStatus'][0]['status']=='rejected') 
                                                            echo '<h5><b>Remark(s) : </b></h5>'.nl2br($approver['BStatus'][0]['remark']); 
                                                    ?>
                                                    </p>
                                                </td>
                                                <td>
                                                    <?php 
                                                        if (!empty($remark_approver[$counter])):
                                                            foreach ($remark_approver[$counter] as $val):
                                                    ?>
                                                        <h5><b>Remarks : 
                                                        <?php 
                                                            if (!empty($val['BRemark']['subject'])) 
                                                                echo $val['BRemark']['subject']; 
                                                        ?>
                                                        </b></h5>
                                                        <p>
                                                            <b>To</b> : 
                                                            <?php 
                                                                if (!empty($val['BRemarkAssign'])):
                                                                    foreach ($val['BRemarkAssign'] as $v) {
                                                                        if ($val['User']['staff_name']!=$v['User']['staff_name'])
                                                                            echo '<i class="fa fa-check"></i>'.$v['User']['staff_name'].' ';
                                                                    }
                                                                endif;
                                                            ?>
                                                        </p>
                                                        <p style="text-align:justify">
                                                            <?php 
                                                                if (!empty($val['BRemarkFeedback'][0]['feedback'])) 
                                                                    echo $val['BRemarkFeedback'][0]['feedback']; ?>
                                                        </p> 


                                                        <table class="table">
                                                            <?php 
                                                                if (count($val['BRemarkFeedback'])>1):
                                                                    for ($i=1;$i<count($val['BRemarkFeedback']);$i++):
                                                            ?>
                                                                <tr style="text-align:justify">
                                                                    <td>
                                                                        <h5><b>Feedback from 
                                                                        <?php 
                                                                            echo $val['BRemarkFeedback'][$i]['User']['staff_name'];
                                                                            echo " ( "; 
                                                                            echo date('d M Y',strtotime($val['BRemarkFeedback'][$i]['created']));
                                                                            echo " ) ";
                                                                        ?>
                                                                        :</b></h5>
                                                                        <p>
                                                                            <?php 
                                                                                if (!empty($val['BRemarkFeedback'][$i]['feedback'])) echo $val['BRemarkFeedback'][$i]['feedback']; 
                                                                            ?>
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            <?php 
                                                                    endfor;
                                                                endif; 
                                                            ?>
                                                        </table>

                                                        <?php 
                                                                endforeach;
                                                            endif; 
                                                        ?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                </td>
                            </tr>
                        </table>
            		        
                <?php 
            			$counter++;
            			endforeach;
                	endif; 
                ?>
            </div>
		</section>
	</div>
</section>