<?php
  $encBudgetID = $this->Mems->encrypt($budgetID);

  $this->Html->addCrumb('Budget', array('controller' => 'budget', 'action' => 'index'));
  $this->Html->addCrumb($budgetDetail['Budget']['year'], array('controller' => 'budget', 'action' => 'dashboard',$encBudgetID));
  $this->Html->addCrumb('Budget Request', $this->here,array('class'=>'active'));
?>

<!-- Modal add department-->
<div class="modal fade" id="modal-add-dept" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Add Department</h4>
      </div>
      <?php
        echo $this->Form->create('BDepartment',array('url'=>array('controller'=>'budget','action'=>'addDepartment'),
                    'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
                    'class'=>'form-horizontal'));
        echo  $this->Form->input('BDepartment.budget_id',array('type'=>'hidden','value'=>$encBudgetID));

      ?>
      <div class="modal-body ">

        <div class="form-group">
          <label class="col-lg-3 col-sm-3 control-label"> Academic Dept.</label>
          <div class="col-sm-9">
              <?php
                
                echo $this->Form->input('BDepartment.acad_id.',array('type'=>'select','options'=>$acadList,'class'=>'select2-sortable full-width','multiple','placeholder'=>'-- Select academic departments --'));
               
              ?>
              <small><i class="fa fa-exclamation-circle"></i> Select according to the sequence you want in budget</small>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 col-sm-3 control-label"> Non-Academic Dept.</label>
          <div class="col-sm-9">
              <?php
                
                echo $this->Form->input('BDepartment.nonacad_id.',array('type'=>'select','options'=>$nonacadList,'class'=>'select2-sortable full-width','multiple','placeholder'=>'-- Select non-academic departments --'));
              ?>
              <small><i class="fa fa-exclamation-circle"></i> Select according to the sequence you want in budget</small>
          </div>
        </div>
        
      </div>
        
      <div class="modal-footer text-center">
        <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
        <?php
          echo $this->Form->button('Add Department',array('type'=>'submit','class'=>'btn btn-success'));
        ?>
      </div>

      <?php
        echo $this->Form->end();
      ?>
    </div>
  </div>
</div>
  <!-- modal -->

  <!-- Modal -->
<div class="modal fade" id="modal-del-dept" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Remove Department</h4>
      </div>
      <?php
        echo $this->Form->create('BDepartment',array('url'=>array('controller'=>'budget','action'=>'removeDepartment'),
                    'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
                    'class'=>'form-horizontal','type'=>'file'));
        echo  $this->Form->input('BDepartment.budget_id',array('type'=>'hidden','value'=>$encBudgetID));

      ?>
      <div class="modal-body ">

        <div class="form-group">
          <label class="col-lg-3 col-sm-3 control-label"> Department</label>
          <div class="col-sm-9">
              <?php
                echo $this->Form->input('BDepartment.dept_id',array('type'=>'select','options'=>$selectedAcad+$selectedNonAcad,'class'=>'select2-sortable full-width','required','multiple','placeholder'=>'-- Select departments --'));
               
              ?>
              <small><i class="fa fa-exclamation-circle"></i> Select the department(s) you want to remove</small>
          </div>
        </div>
        
        
      </div>
        
      <div class="modal-footer text-center">
        <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
        <?php
          echo $this->Form->button('Remove Department',array('type'=>'submit','class'=>'btn btn-success'));
        ?>
      </div>

      <?php
        echo $this->Form->end();
      ?>
    </div>
  </div>
</div>
  <!-- modal -->
    <!-- Modal -->
<div class="modal fade" id="modal-add-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Add Item</h4>
      </div>
      <?php
        echo $this->Form->create('',array('url'=>array('controller'=>'budget','action'=>'removeDepartment'),
                    'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
                    'class'=>'form-horizontal','type'=>'file'));
        echo  $this->Form->input('BDepartment.budget_id',array('type'=>'hidden','value'=>$encBudgetID));

      ?>
      <div class="modal-body ">

        <div class="form-group">
          <label class="col-lg-3 col-sm-3 control-label"> Department</label>
          <div class="col-sm-9">
              <?php
                echo $this->Form->input('BDepartment.dept_id',array('type'=>'select','options'=>$selectedAcad+$selectedNonAcad,'class'=>'select2-sortable full-width','required','multiple','placeholder'=>'-- Select departments --'));
               
              ?>
              <small><i class="fa fa-exclamation-circle"></i> Select the department(s) you want to remove</small>
          </div>
        </div>
        
        
      </div>
        
      <div class="modal-footer text-center">
        <button data-dismiss="modal" class="btn btn-danger" type="button">Close</button>
        <?php
          echo $this->Form->button('Remove Department',array('type'=>'submit','class'=>'btn btn-success'));
        ?>
      </div>

      <?php
        echo $this->Form->end();
      ?>
    </div>
  </div>
</div>
  <!-- modal -->
<section class="mems-content" >

  <?php // debug (json_encode($deptAcad));exit;
    echo $this->Form->create('Budget',array('url'=>array('controller'=>'Budget','action'=>'saveBudget',$encBudgetID),'id'=>'budgetForm','inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false)));

    ?>
  <div class="clear"></div>
  <div class="row" >
    <br/>

    <div class="col-lg-12 text-center">
              
      <?php 

        // echo $this->Html->link('<i class="fa fa-plus"></i> Add Department ','#modal-add-dept',array('escape'=>false,'class'=>'btn btn-success','data-original-title'=>'Add Department','data-toggle'=>'modal'));
        
        // echo '&nbsp;&nbsp;';

        // echo $this->Html->link('<i class="fa fa-minus"></i> Remove Department ','#modal-del-dept',array('escape'=>false,'class'=>'btn btn-danger','data-original-title'=>'Remove Department','data-toggle'=>'modal'));
        
        // echo '&nbsp;&nbsp;';

        // echo $this->Html->link('<i class="fa fa-plus"></i> Add Item ','#modal-add-item',array('escape'=>false,'class'=>'btn btn-success','data-original-title'=>'Add Item','data-toggle'=>'modal'));

        
        // echo '&nbsp;&nbsp;';

        // echo $this->Form->button("<i class='fa fa-save'></i> Save & Preview ",array('type'=>'submit','class'=>'btn btn-primary','name'=>'save'));
       
      ?>
    <!--  <div style="color:red"><small><strong>Please ensure every item is selected only once per budget before proceeding</strong></small></div> -->
    </div>
    <div class="col-lg-12">
      <div class="scrollable-area" style="height: 68vh; padding : 10px 20px; width:auto">
        <section class="panel" >
            <header class="panel-heading">
              Budget Details
            </header>
            <div class="panel-body">
              <table class="table table-bordered table-striped table-condensed" style="width:100%">
                <tbody>
                  <tr><th>Company</th><td><b><?php echo $budgetDetail['Company']['company']?></b></td></tr>
                  <tr><th>Description</th><td><b><?php echo nl2br($budgetDetail['Budget']['description'])?></b></td></tr>
                  <tr><th>Year</th><td><b><?php echo '01/01/'.$budgetDetail['Budget']['year'].' to '.'31/12/'.$budgetDetail['Budget']['year']; ?></b></td></tr>
                </tbody>
              </table>
              <?php 
                // echo $this->Form->button("<i class='fa fa-save'></i> Save & Preview ",array('type'=>'submit','class'=>'btn btn-primary','name'=>'save'));
               
              ?>
            </div>
        </section>

        <div style="display: table; width:100%;height:100%">
           <!-- <form action="" method="post"> -->
            <section class="panel">
                <header class="panel-heading">
                  <button id="btnRevenue" type="button" ><i class="fa fa-plus"></i> Revenue</button>
                  <?php 
                    if (!empty($revenueGroup)):
                      echo "&nbsp;&nbsp;";
                      echo $this->Form->input('toggleDept',array('type'=>'select','options'=>$deptAcad+$deptNonAcad,'class'=>'deptSelect select2-sortable ','empty'=>'Show/Hide Dept.','style'=>'max-width:200px'));
                    endif;
                  ?>
                </header>
                <div class="panel-body">
                    
                    <div id="revenue">
                    </div>
                   
                </div>
            </section>
            <section class="panel">
                <header class="panel-heading">
                  <button id="btnCostOfRevenue" type="button"><i class="fa fa-plus"></i> Cost of Revenue</button>
                  <?php 
                    if (!empty($costOfRevenueGroup)):
                      echo "&nbsp;&nbsp;";
                      echo $this->Form->input('toggleDept',array('type'=>'select','options'=>$deptAcad+$deptNonAcad,'class'=>'deptSelect select2-sortable ','empty'=>'Show/Hide Dept.','style'=>'max-width:200px'));
                    endif;
                  ?>
                </header>
                <div class="panel-body">
                    <div id="costOfRevenue">
                    </div>
               </div>
            </section>
            <section class="panel">
              <header class="panel-heading">
                <button id="btnOtherIncome" type="button"><i class="fa fa-plus"></i> Other Income</button>
                <?php 
                    if (!empty($otherIncomeGroup)):
                      echo "&nbsp;&nbsp;";
                      echo $this->Form->input('toggleDept',array('type'=>'select','options'=>$deptAcad+$deptNonAcad,'class'=>'deptSelect select2-sortable ','empty'=>'Show/Hide Dept.','style'=>'max-width:200px'));
                    endif;
                  ?>
              </header>
              <div class="panel-body">
                  
                  <div id="otherIncome">
                  </div>
                  
              </div>
         </section>
         <section class="panel">
            <header class="panel-heading">
              <button id="btnExpenses" type="button"><i class="fa fa-plus"></i> Expenses</button>
              <?php 
                    if (!empty($expensesGroup)):
                      echo "&nbsp;&nbsp;";
                      echo $this->Form->input('toggleDept',array('type'=>'select','options'=>$deptAcad+$deptNonAcad,'class'=>'deptSelect select2-sortable ','empty'=>'Show/Hide Dept.','style'=>'max-width:200px'));
                    endif;
                  ?>
            </header>
            <div class="panel-body">
                
                <div id="expenses">
                </div>
                
          </div>
        </section>
        <section class="panel">
            <header class="panel-heading">
              <button id="btnTaxation" type="button"><i class="fa fa-plus"></i> Taxation</button>
              <?php 
                    if (!empty($taxationGroup)):
                      echo "&nbsp;&nbsp;";
                      echo $this->Form->input('toggleDept',array('type'=>'select','options'=>$deptAcad+$deptNonAcad,'class'=>'deptSelect select2-sortable ','empty'=>'Show/Hide Dept.','style'=>'max-width:200px'));
                    endif;
                  ?>
            </header>
            <div class="panel-body">
                
                <div id="taxation">
                </div>
                
          </div>
        </section>
        <!-- <section class="panel">
            <header class="panel-heading">
            Unbudgeted Item
            </header>
            <div class="panel-body">
                
                <div id="unbudgeted">
                </div>
                
          </div>
        </section> -->
       <!-- </form>  -->

      </div>
      </div>
    </div>
    <div class="row">
     <div class="col-lg-12 text-center">
      <!-- <div class="btn-group "> -->
        <?php 
                echo $this->Form->button("<i class='fa fa-save'></i> Save & Preview ",array('type'=>'submit','class'=>'btn btn-primary','name'=>'save'));
                // echo '&nbsp;&nbsp;&nbsp;';
                // if ($budgetDetail['Budget']['budget_status']==0)
                //   echo $this->Form->button("<i class='fa fa-arrow-circle-right'></i> Next",array('type'=>'submit','class'=>'btn btn-success','name'=>'next')); 
                // else
                //   echo $this->Form->button("<i class='fa fa-arrow-circle-right'></i> Resubmit",array('type'=>'submit','class'=>'btn btn-success','name'=>'resubmit')); 

          ?>
        <br/>
        <div style="color:red"><small><strong>Please ensure every item is selected once only per budget before proceeding</strong></small></div>
      <!-- </div> -->
      </div>
    </div>
  </div>
  <?php
   echo $this->Form->end();//debug($revenueGroup);exit;
  ?>
</section>
<script type="text/javascript">

  $(document).ready(function() {
      $(".scrollable-area").niceScroll();
     
      //  $(document).on('click', '#saveButton', function () {
        
      //   $.post($("#budgetForm").attr("action"), $("#budgetForm").serialize(),
      //     function() {
      //       console.log($("#budgetForm").serialize());
      //     });
      // });
  });

</script>




<script id="jsSource" type="text/javascript">
  //to make sure nice scroll resizes dynamically according to content
  $("html").mouseover(function() {
        $("html").getNiceScroll().resize();
  });
  $(function () {
      var countRevenue =0;
      var countCostOfRevenue =0;
      var countOtherIncome =0;
      var countExpenses =0;
      var countTaxation =0;
      //list depts
      var deptAcad='<?php echo json_encode($deptAcad); ?>';
      var deptNonAcad='<?php echo json_encode($deptNonAcad); ?>';
      deptAcad=JSON.parse(deptAcad);
      deptNonAcad=JSON.parse(deptNonAcad);

      //list saved data
      var revenueGroup='<?php echo json_encode($revenueGroup); ?>';
      var costOfRevenueGroup='<?php echo json_encode($costOfRevenueGroup); ?>';
      var otherIncomeGroup='<?php echo json_encode($otherIncomeGroup); ?>';
      var expensesGroup='<?php echo json_encode($expensesGroup); ?>';
      var taxationGroup='<?php echo json_encode($taxationGroup); ?>';
      // var unbudgetedGroup='<?php //echo json_encode($unbudgetedGroup); ?>';
     // console.log(revenueGroup);

      revenueGroup=JSON.parse(revenueGroup);
      costOfRevenueGroup=JSON.parse(costOfRevenueGroup);
      otherIncomeGroup=JSON.parse(otherIncomeGroup);
      expensesGroup=JSON.parse(expensesGroup);
      taxationGroup=JSON.parse(taxationGroup);
      // unbudgetedGroup=JSON.parse(unbudgetedGroup);

      //preload table for edit,if exist
      if (revenueGroup.length >0){
        for(var i=0; i<revenueGroup.length;i++){

          newTable('revenue',countRevenue,deptAcad,deptNonAcad);
          $('#revenueGroup'+countRevenue).appendGrid('load', revenueGroup[i]);
          countRevenue++;

        }
      }
      if (costOfRevenueGroup.length >0){
        for(var i=0; i<costOfRevenueGroup.length;i++){

          newTable('costOfRevenue',countCostOfRevenue,deptAcad,deptNonAcad);
          $('#costOfRevenueGroup'+countCostOfRevenue).appendGrid('load', costOfRevenueGroup[i]);
          countCostOfRevenue++;
           
        }
      }
      if (otherIncomeGroup.length >0){
        for(var i=0; i<otherIncomeGroup.length;i++){

          newTable('otherIncome',countOtherIncome,deptAcad,deptNonAcad);
          $('#otherIncomeGroup'+countOtherIncome).appendGrid('load', otherIncomeGroup[i]);
          countOtherIncome++;
           
        }
      }

       if (expensesGroup.length >0){
        for(var i=0; i<expensesGroup.length;i++){

          newTable('expenses',countExpenses,deptAcad,deptNonAcad);
          $('#expensesGroup'+countExpenses).appendGrid('load', expensesGroup[i]);
          countExpenses++;
           
        }
      }

       if (taxationGroup.length >0){
        for(var i=0; i<taxationGroup.length;i++){

          newTable('taxation',countTaxation,deptAcad,deptNonAcad);
          $('#taxationGroup'+countTaxation).appendGrid('load', taxationGroup[i]);
          countTaxation++;
           
        }
      }

      // if (unbudgetedItem.length >0){
        // for(var i=0; i<unbudgetedItem.length;i++){

          // createUnbudgeted(deptAcad,deptNonAcad);
          //  if (unbudgetedGroup.length >0){ console.log(unbudgetedGroup);
          //     $('#unbudgetedGroup0').appendGrid('load', unbudgetedGroup);
          //  }
        // }
      // }
     
      $('#btnRevenue').button().on('click', function () {
           newTable('revenue',countRevenue,deptAcad,deptNonAcad);
           countRevenue++;
      });

      $('#btnCostOfRevenue').button().on('click', function () {
           newTable('costOfRevenue',countCostOfRevenue,deptAcad,deptNonAcad);
           countCostOfRevenue++;
      });

      $('#btnOtherIncome').button().on('click', function () {
           newTable('otherIncome',countOtherIncome,deptAcad,deptNonAcad);
           countOtherIncome++;
      });

      $('#btnExpenses').button().on('click', function () {
           newTable('expenses',countExpenses,deptAcad,deptNonAcad);
           countExpenses++;
      });

      $('#btnTaxation').button().on('click', function () {
           newTable('taxation',countTaxation,deptAcad,deptNonAcad);
           countTaxation++;
      });


      $('.deptSelect').on('change',function(){
        var deptVal = $(this).val();
        var invisible;
        if (revenueGroup.length >0){
        for(var i=0; i<revenueGroup.length;i++){

          invisible = $('#revenueGroup'+i).appendGrid('isColumnInvisible', deptVal);
          if (invisible) {
              $('#revenueGroup'+i).appendGrid('showColumn', deptVal);
          } else {
              $('#revenueGroup'+i).appendGrid('hideColumn', deptVal);
          }

        }
      }
      if (costOfRevenueGroup.length >0){
        for(var i=0; i<costOfRevenueGroup.length;i++){

          invisible = $('#costOfRevenueGroup'+i).appendGrid('isColumnInvisible', deptVal);
          if (invisible) {
              $('#costOfRevenueGroup'+i).appendGrid('showColumn', deptVal);
          } else {
              $('#costOfRevenueGroup'+i).appendGrid('hideColumn', deptVal);
          }
           
        }
      }
      if (otherIncomeGroup.length >0){
        for(var i=0; i<otherIncomeGroup.length;i++){

          invisible = $('#otherIncomeGroup'+i).appendGrid('isColumnInvisible', deptVal);
          if (invisible) {
              $('#otherIncomeGroup'+i).appendGrid('showColumn', deptVal);
          } else {
              $('#otherIncomeGroup'+i).appendGrid('hideColumn', deptVal);
          }
           
        }
      }

       if (expensesGroup.length >0){
        for(var i=0; i<expensesGroup.length;i++){

          invisible = $('#expensesGroup'+i).appendGrid('isColumnInvisible', deptVal);
          if (invisible) {
              $('#expensesGroup'+i).appendGrid('showColumn', deptVal);
          } else {
              $('#expensesGroup'+i).appendGrid('hideColumn', deptVal);
          }
           
        }
      }

       if (taxationGroup.length >0){
        for(var i=0; i<taxationGroup.length;i++){

          invisible = $('#taxationGroup'+i).appendGrid('isColumnInvisible', deptVal);
          if (invisible) {
              $('#taxationGroup'+i).appendGrid('showColumn', deptVal);
          } else {
              $('#taxationGroup'+i).appendGrid('hideColumn', deptVal);
          }
           
        }
      }

    });
     

  });
 
  function getItem(){

    var items='<?php echo json_encode($itemList); ?>';
              
     return JSON.parse(items);
             
  }

  function getColumn(deptAcad,deptNonAcad){

    var cols=[{name: 'item', display: 'Item', type: 'select',ctrlOptions:getItem()},
              {name: 'budgetYTD',display: ' Budget YTD ',ctrlCss: { width: '100px','font-weight': '900','background-color':'#e0ccff' }, displayCss: { 'color': '#6600ff'}}
              ];
    
    //define list of columns for acad
    for (bDeptId in deptAcad){
        cols.push({name:bDeptId ,display: deptAcad[bDeptId],ctrlCss: {width: '80px'},
            onChange: function(evt, rowIndex) {
                calcAcad(evt.target,deptAcad);
                calcAll(evt.target);

            }
        });
   
    }
    cols.push({name: 'totAcad',display: ' Tot. Academic ',ctrlCss: { 'font-weight': '900','background-color':'#ffcccc' }, displayCss: { 'color': '#ff3300'}});

    //define list of columns for non acad

    for (bDeptId in deptNonAcad){
        cols.push({name:bDeptId ,display: deptNonAcad[bDeptId],ctrlCss: { width: '80px' },
            onChange: function(evt, rowIndex) {
                calcNonAcad(evt.target,deptNonAcad);
                calcAll(evt.target);
            }
        });
   
    }
    cols.push({name: 'totNonAcad',display: ' Tot. Non-Academic ',ctrlCss: { 'font-weight': '900','background-color':'#e6ffcc' }, displayCss: { 'color': '#008000'}});
    cols.push({name: 'totAll',display: ' Grand Total ',ctrlCss: { width: '100px','font-weight': '900','background-color':'#ccf5ff' }, displayCss: { 'color': '#005ce6'}});

    return cols;
    // console.log(cols);
    //console.log(deptNonAcad);
  }
  //function to add new revenue group of items
  function newTable(section,group,deptAcad,deptNonAcad) {
        //getDept();

     //create new table with the section & group id
     $('<table id="'+section+'Group'+group+'" ></table><br/>').appendTo( '#'+section );
     
     if (section=='revenue')
      captionName='Revenue';
     else if (section=='costOfRevenue')
      captionName='Cost of Revenue';
    else if (section=='otherIncome')
      captionName='Other Income';
    else if (section=='expenses')
      captionName='Expenses';
    else if (section=='taxation')
      captionName='Taxation';

     $('#'+section+'Group'+group).appendGrid({
      columns: getColumn(deptAcad,deptNonAcad),
      initRows: 0,
      caption: captionName+' : Group '+(group+1),
      hideRowNumColumn: true,
      rowButtonsInFront: true,
      rowDragging:true
     
    });

  }

  function calcAcad(caller,deptAcad) {
    // Find the information of grid  
    var pattern = caller.id.split('_');
    var uniqueIndex = pattern[pattern.length - 1];
    // Get the subgrid, change it if you specified a different ID
    var gridPrefix = pattern[0];
   // var mainUniqueIndex = pattern[1];
    var $grid = $('#' + gridPrefix );
    // Get sub RowIndex based on unique index
    var rowIndex = $grid.appendGrid('getRowIndex', uniqueIndex);
    // Get the values
    var result =0;
    for (bDeptId in deptAcad){
       //if not number only add the value
       if (!$.isNumeric($grid.appendGrid('getCtrlValue', bDeptId, rowIndex)))
          continue;

        result = result+parseFloat($grid.appendGrid('getCtrlValue', bDeptId, rowIndex));

    }
    
    // Set the result
    $grid.appendGrid('setCtrlValue', 'totAcad', rowIndex, result);
  }

  function calcNonAcad(caller,deptNonAcad) {
    // Find the information of grid  
    var pattern = caller.id.split('_');
    var uniqueIndex = pattern[pattern.length - 1];
    // Get the subgrid, change it if you specified a different ID
    var gridPrefix = pattern[0];
   // var mainUniqueIndex = pattern[1];
    var $grid = $('#' + gridPrefix );
    // Get sub RowIndex based on unique index
    var rowIndex = $grid.appendGrid('getRowIndex', uniqueIndex);
    // Get the values
    var result =0;
    for (bDeptId in deptNonAcad){
       //if number only add the value
       if (!$.isNumeric($grid.appendGrid('getCtrlValue', bDeptId, rowIndex)))
          continue;

       result = result+parseFloat($grid.appendGrid('getCtrlValue', bDeptId, rowIndex));

    }
    
    // Set the result
    $grid.appendGrid('setCtrlValue', 'totNonAcad', rowIndex, result);
  }

  function calcAll(caller,deptNonAcad) {
    // Find the information of grid  
    var pattern = caller.id.split('_');
    var uniqueIndex = pattern[pattern.length - 1];
    // Get the subgrid, change it if you specified a different ID
    var gridPrefix = pattern[0];
   // var mainUniqueIndex = pattern[1];
    var $grid = $('#' + gridPrefix );
    // Get sub RowIndex based on unique index
    var rowIndex = $grid.appendGrid('getRowIndex', uniqueIndex);
    // Get the values
    var result =0;
   
     //if number only add the value
    var totAcad=0;
    if ($.isNumeric($grid.appendGrid('getCtrlValue', 'totAcad', rowIndex)))
        var totAcad=$grid.appendGrid('getCtrlValue', 'totAcad', rowIndex);
    
    var totNonAcad=0;
    if ($.isNumeric($grid.appendGrid('getCtrlValue', 'totNonAcad', rowIndex)))
      var totNonAcad=$grid.appendGrid('getCtrlValue', 'totNonAcad', rowIndex);

     
    result = parseFloat(totAcad)+parseFloat(totNonAcad);

      
      // Set the result
    $grid.appendGrid('setCtrlValue', 'totAll', rowIndex, result);
  }
 

  </script>