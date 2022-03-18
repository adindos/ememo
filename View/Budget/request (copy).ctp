<?php
  $encBudgetID = $this->Mems->encrypt($budgetID);

  $this->Html->addCrumb('Budget', array('controller' => 'budget', 'action' => 'index'));
  $this->Html->addCrumb($budgetDetail['Budget']['year'], array('controller' => 'budget', 'action' => 'dashboard',$encBudgetID));
  $this->Html->addCrumb('Budget Request', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
  
  <div class="row">
    <div class="col-lg-12">
      <section class="panel">
          <header class="panel-heading">
            Budget Details
          </header>
          <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed">
              <tbody>
                <tr><th>Company</th><td><b><?php echo $budgetDetail['Company']['company']?></b></td></tr>
                <tr><th>Description</th><td><b><?php echo nl2br($budgetDetail['Budget']['description'])?></b></td></tr>
                <tr><th>Year</th><td><b><?php echo '01/01/'.$budgetDetail['Budget']['year'].' to '.'31/12/'.$budgetDetail['Budget']['year']; ?></b></td></tr>
              </tbody>
            </table>
          </div>
      </section>
      <section class="panel">
          <header class="panel-heading">
            Revenue 
          </header>
          <div class="panel-body">
             <form action="" method="post">
                  <h4>REVENUE</h4>
                  <button id="btnRevenue" type="button" >Add Revenue</button>
                  <br><br>
                  <div id="revenue">
                  </div>
                  <div id="totalRevenue">
                    
                  </div>
                
                  <h4>NET SALES</h4>
                <!-- <button id="btnGetAllValue1" type="button">Add Revenue</button> -->
                <div id="netSales">
                
                </div>
                <div id="costOfRevenue">
                  <h4>COST OF REVENUE</h4>

                  <button id="btnCostOfRevenue" type="button">Add Cost of Revenue</button>
                  <br><br>
                </div>

                <div id="grossProfitLoss">
                  <h4>GROSS PROFIT/(LOSS)</h4>
                  <!-- <button id="btnGrossProfitLoss" type="button">Add Gross Profit/(Loss)</button> -->
                </div>
                <div id="otherIncome">
                  <h4>OTHER INCOME</h4>

                  <button id="btnOtherIncome" type="button">Add Other Income</button>
                  <br><br>
                </div>
                <div id="expenses">
                  <h4>EXPENSES</h4>

                  <button id="btnExpenses" type="button">Add Expenses</button>
                  <br><br>
                </div>
                <div id="netProfitLoss">
                  <h4>NET PROFIT/(LOSS)</h4>
                  <!-- <button id="btnGrossProfitLoss" type="button">Add Gross Profit/(Loss)</button> -->
                </div>
                <div id="taxation">
                  <h4>TAXATION</h4>

                  <button id="btnTaxation" type="button">Add Taxation</button>
                  <br><br>
                </div>
                <div id="netProfitLossAfterTax">
                  <h4>NET PROFIT/(LOSS) AFTER TAX</h4>
                  <!-- <button id="btnGrossProfitLoss" type="button">Add Gross Profit/(Loss)</button> -->
                </div>
              
            <br />
            <button id="btnGetAllValue1" type="button">
                Demo: getAllValue, Array Mode</button>
            <button id="btnGetAllValue2" type="button">
                Demo: getAllValue, Object Mode</button>
              </form> 
          </div>
      </section>
    </div>
  </div>
  <div class="row">

    <!-- Add Item section -->
    <div class="col-lg-4">
      <section class="panel">
        <header class="panel-heading">
          <span>Add Item to Budget </span>
          <span class="tools pull-right">
              <a href="javascript:;" class="fa fa-chevron-down"></a>
          </span>
        </header>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <?php
                echo $this->Form->create('Budget',array('url'=>array('controller'=>'budget','action'=>'addBudgetItem'),'class'=>'tasi-form','type'=>'file','inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false)));
                echo $this->Form->hidden('BNewAmount.budget_id',array('value'=>$budgetID));
                echo $this->Form->hidden('BNewAmount.department_id',array('value'=>$budgetDetail['Budget']['department_id']));
              ?>
                  <div class="form-group text-center">
                    <h5 class="control-label bold no-margin-bottom">Budget Item</h5>
                    <small> Please select the budget item </small><br><br>
                    <div id="item-select-segment">
                      <?php
                        echo $this->Form->input('BNewAmount.item_id',array('type'=>'select','options'=>$budgetItems,'class'=>'select2 full-width','empty'=>'-- Select the item --'));
                      ?>
                      <br><br>
                      <!-- <div class="row">
                        or  <a id="add-new-item" class="pointer-cursor text-center"><u> add new item </u></a>
                      </div> -->
                    </div>
                    <div class="row" id="new-item-segment" style="display:none">
                        <span class="small col-sm-3"> Add new item (*)</span>
                        <div class="add-new-budget-item col-sm-9">
                          <div class="input-group input-group-sm">
                              <?php
                                echo $this->Form->input('BItem.item',array('type'=>'text','class'=>'form-control','placeholder'=>'Add New Item'));
                              ?>
                              <span class="input-group-addon">+</span>
                          </div>
                        </div>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group text-center">
                    <h5 class="control-label bold no-margin-bottom">Budget Amount (*)</h5>
                    <small> Please enter the budget cost of the item </small><br><br>
                    <div class="">
                        <div class="input-group">
                            <span class="input-group-addon">RM</span>
                            <?php
                              echo $this->Form->input('BNewAmount.amount',array('type'=>'text','class'=>'form-control','required'=>'required'));
                            ?>
                        </div>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <small>(*) Denotes a required field </small>
                  </div>
                  <hr>
                  <div class="form-group" style="text-align:center">      
                    <?php
                      echo $this->Form->button('<i class="fa fa-save"></i> Add To Budget',array('type'=>'submit','escape'=>false,'class'=>'btn btn-success'));
                      echo $this->Form->end();
                    ?>
                  </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Budget -->
    <div class="col-lg-8">
      <section class="panel">
        <header class="panel-heading">
          Budget Details 
          <span class="tools pull-right">
              <a href="javascript:;" class="fa fa-chevron-down"></a>
              <!-- <a href="javascript:;" class="fa fa-times"></a> -->
          </span>
        </header>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12"> 
              <div class="small" style="padding:10px">
                <?php
                  echo "<strong class='bigger-text'> Budget Title : </strong>" . $budgetDetail['Budget']['title'];
                  echo "<br>";
                  echo "<strong class='bigger-text'> Quarter / Year : </strong>" . $budgetDetail['Budget']['quarter'] . " (".$budgetDetail['Budget']['year']. " )";
                  echo "<br>";
                  echo "<strong class='bigger-text'> Department : </strong>".$budgetDetail['Department']['department_name'];
                  echo "<br>";
                ?>
              </div>
            </div>
          </div>
          <br>
          <table class="table table-striped table-advance table-bordered">
            <thead>
              <tr class="">
                <th colspan="2" class="col-lg-9"> Description </th>
                <th class="text-center"> Amount (RM) </th>
                <th class="text-center"> Action </th>
              </tr>
            </thead>
            <tbody>
              <?php
                // debug($budget);
                $categoryID =null;
                $no=1;
                $totalAmount  = 0;
                foreach($budgetData as $b):
                  if($b['BItem']['category_id'] != $categoryID):
                    echo "<tr class='info'>";
                    echo "<td colspan='4'>".$b['BItem']['BCategory']['category'] . "</td>";
                    echo "</tr>";
                    $no=1;
                  endif;

                  //iterate item
                  echo "<tr>";
                  echo "<td class='text-center'>".$no++.".</td>";
                  echo "<td>".$b['BItem']['item']."</td>";
                  echo "<td class='text-center bold'>".number_format($b['BNewAmount']['amount'],2,".",",")."</td>";
                  echo "<td class='text-center'>";
                  echo "<div class='btn-group'>";
                  echo "<a href='#edit-budget' data-toggle='modal' class='btn btn-white btn-xs edit-budget-btn tooltips' data-toggle='tooltips' data-original-title='Edit Budget Amount' data-amount-id='".$b['BNewAmount']['amount_id']."' data-item='".$b['BItem']['item']."' data-amount='".number_format($b['BNewAmount']['amount'],2,".","")."'><i class='fa fa-pencil text-info'></i></a>";
                  echo $this->Form->postlink('<i class="fa fa-trash-o"></i>',array('controller'=>'budget','action'=>'deleteBudgetItem',$b['BNewAmount']['amount_id']),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title="Delete Budget Item"'),"Are you sure you want to delete the item from the budget?");
                  echo "</div>";
                  echo "</td>";
                  echo "</tr>";
                  $categoryID = $b['BItem']['category_id']; 
                  $totalAmount += $b['BNewAmount']['amount'];
                endforeach;
              ?>
              <tr class="success">
                <td colspan="2" class="bold"> Total Amount </td>
                <td class="text-center bold bigger-text"><?php echo number_format($totalAmount,2,".",","); ?></td>
                <td class=""></td>
              </tr>
            </tbody>
          </table>
          <hr>
          <div class="btn-group pull-right">
            <?php 
              $encBudgetID = $this->Mems->encrypt($budgetID);
              echo $this->Form->postLink("<button class='btn btn-success'><i class='fa fa-save'></i> Save </button>",array('controller'=>'budget','action'=>'saveBudget',$budgetID),array('escape'=>false,'class'=>'margin-right')); 

              echo $this->Form->postLink("<button class='btn btn-info'><i class='fa fa-arrow-circle-right'></i> Next </button>",array('controller'=>'budget','action'=>'confirm',$encBudgetID),array('escape'=>false),'You will not be able to add/remove budget item while the budget is in review. Are you sure you want to proceed?'); 
            ?>
          </div>
        </div>
      </section>
      
    
    </div>
  </div>
</section>

<div aria-hidden="true" aria-labelledby="new" role="dialog" tabindex="-1" id="edit-budget" class="modal fade">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
              <h4 class="modal-title">Edit Budget Item</h4>
          </div>
          <div class="modal-body">
            <?php
              echo $this->Form->create('BNewAmount',array('url'=>array('controller'=>'budget','action'=>'editBudgetItem'),'class'=>'form-horizontal','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
              echo $this->Form->hidden('BNewAmount.amount_id',array('id'=>'edit-budget-amount-id'));
            ?>
              <div class="form-group">
                <label class="col-sm-4 control-label">Budget Item (*) </label>
                <div class="col-sm-8">
                    <?php
                      echo $this->Form->input('BNewAmount.item',array('type'=>'text','id'=>'edit-budget-item','disabled'=>'disabled','class'=>'form-control'));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Budget Amount (*)</label>
                <div class="col-sm-4">
                    <div class="input-group">
                      <span class="input-group-addon">RM</span>
                      <?php
                        echo $this->Form->input('BNewAmount.amount',array('type'=>'text','id'=>'edit-budget-amount','class'=>'form-control'));
                      ?>
                    </div>
                </div>
            </div>
            <div class="form-group">
              <div class="col-sm-4">
                  (*) Denotes a required field
              </div>
            </div>
          </div>

          <div class="modal-footer text-center">
    <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
    <?php
      echo $this->Form->button('Save the edit',array('type'=>'submit','class'=>'btn btn-success'));
    ?>
  </div>
  <?php
    echo $this->Form->end();
  ?>
      </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $('.edit-budget-btn').on('click',function(){
      var amount_id = $(this).data('amount-id');
      var item = $(this).data('item');
      var amount = $(this).data('amount');

      $('#edit-budget-amount-id').val(amount_id);
      $('#edit-budget-item').val(item);
      $('#edit-budget-amount').val(amount);
    })

    $('#add-new-item').click(function(){
      $('#new-item-segment').show();
      $('#item-select-segment').hide();
    });

    // $('#select-category').change(function(){
    //   var category = $(this).val();
    //   if(category == -99){
    //     $('#new-category-segment').show();
    //     $('#category-select').hide();
    //   }
    // });
    $('#add-category').click(function(){
      $('#new-category-segment').show();
      $('#category-select').hide();
    });

    $('#list-item-category').click(function(){
      $('#category-select').show();
      $('#new-category-segment').hide();
      $('#new-category-input').val('');
    })
  });
</script>
<script id="jsSource" type="text/javascript">
  $(function () {
      var countRevenue =0;
      var countCostOfRevenue =0;
      var countOtherIncome =0;
      var countExpenses =0;
      var countTaxation =0;
      // Create a jQuery Button
        $('#btnRevenue').button({
            label: 'Add Revenue'
        });
      $('#btnRevenue').button().on('click', function () {
           newTable('revenue',countRevenue);
           countRevenue++;
      });

      $('#btnCostOfRevenue').button().on('click', function () {
           newTable('costOfRevenue',countCostOfRevenue);
           countCostOfRevenue++;
      });

      $('#btnOtherIncome').button().on('click', function () {
           newTable('otherIncome',countOtherIncome);
           countOtherIncome++;
      });

      $('#btnExpenses').button().on('click', function () {
           newTable('expenses',countExpenses);
           countExpenses++;
      });

      $('#btnTaxation').button().on('click', function () {
           newTable('taxation',countTaxation);
           countTaxation++;
      });
      $('#btnGetAllValue1').button().on('click', function () {
          // Get grid values in array mode
          var allData = $('#tblAppendGrid').appendGrid('getAllValue');
          alert(JSON.stringify(allData));
      });
      $('#btnGetAllValue2').button().on('click', function () {
          // Get grid values in object mode
          var allData = $('#tblAppendGrid').appendGrid('getAllValue', true);
          alert(JSON.stringify(allData));
      });

  });
  //function to add new revenue group of items
  function newTable(section,group) {
     //create new table with the section & group id
     $('<table id="'+section+'Group'+group+'"></table><br/>').appendTo( '#'+section );
     $('#'+section+'Group'+group).appendGrid({
       columns: [ { name: 'item', display: 'Item', type: 'select', ctrlOptions: { 0: '{Select Item}', 1: '600-001-00 - COURSE FEE', 2: '600-002-00 - ANNUAL FEE', 3: '600-003-00 - SEMESTER FEE', 4: '600-004-00 - REGISTRATION FEE', 5: '600-005-00 - K-FORCE'} },
          {name: 'sub1',display: 'Sub 1', value: 0,
            onChange: function(evt, rowIndex) {
                doCalculation(evt.target);
              }
            }, 
            {name: 'sub2',display: 'Sub 2', value: 0,
            onChange: function(evt, rowIndex) {
                doCalculation(evt.target);
              }
            }, 
            {
            name: 'sub3',
            display: 'Sub 1 + Sub 2'
          }],
      initRows: 0,

      caption: section+' : Group '+(group+1),
       hideRowNumColumn: true,
       rowButtonsInFront: true,
     
    });

     

  }
  function doCalculation(caller) {
    // Find the information of grid  
    var pattern = caller.id.split('_');
    var uniqueIndex = pattern[pattern.length - 1];
    // Get the subgrid, change it if you specified a different ID
    var gridPrefix = pattern[0];
   // var mainUniqueIndex = pattern[1];
    var $grid = $('#' + gridPrefix );
    // Get sub RowIndex based on unique index
    var rowIndex = $grid.appendGrid('getRowIndex', uniqueIndex);
    // Get the sub1 and sub2 values
    var sub1 = $grid.appendGrid('getCtrlValue', 'sub1', rowIndex);
    var sub2 = $grid.appendGrid('getCtrlValue', 'sub2', rowIndex);
    // Calculate result
    var result = 'Error';
    if ($.isNumeric(sub1) && $.isNumeric(sub2)) {
      result = parseFloat(sub1) + parseFloat(sub2);
    }
    // Set the result
    $grid.appendGrid('setCtrlValue', 'sub3', rowIndex, result);
  }

  </script>