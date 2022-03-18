<section class="mems-content">
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
              ?>
                  <div class="form-group text-center">
                    <h5 class="control-label bold no-margin-bottom">Budget Item</h5>
                    <small> Please select the budget item </small><br><br>
                    <div id="item-select-segment">
                      <?php
                        echo $this->Form->input('BNewAmount.item_id',array('type'=>'select','options'=>$budgetItems,'class'=>'select2 full-width','empty'=>'-- Select the item --'));
                      ?>
                      <br><br>
                      <div class="row">
                        or  <a id="add-new-item" class="pointer-cursor text-center"><u> add new item </u></a>
                      </div>
                    </div>
                    <div class="row" id="new-item-segment" style="display:none">
                        <span class="small col-sm-3"> Add new item</span>
                        <div class="add-new-budget-item col-sm-9">
                          <div class="input-group input-group-sm">
                              <?php
                                echo $this->Form->input('BItem.item',array('type'=>'text','class'=>'form-control','placeholder'=>'Add New Item'));
                              ?>
                              <span class="input-group-addon">+</span>
                          </div>
                          <br>
                          <div id="category-select">
                            <?php
                              // array_unshift($budgetItemCategory,array('-99'=>'+ Add New Category'));
                              // $budgetItemCategory = array('-99'=>'+ Add New Category') + $budgetItemCategory;
                              echo $this->Form->input('BItem.category_id',array('type'=>'select','options'=>$budgetItemCategory,'class'=>'select2 full-width','empty'=>'-- Select category for the item --','id'=>'select-category'));
                            ?>
                            <a id="add-category" class="small pull-right pointer-cursor"> Add new category </a>
                          </div>
                          <div id="new-category-segment"  style="display:none">
                            <div class="input-group input-group-sm" id="new-category">
                                <?php
                                  echo $this->Form->input('BCategory.category',array('type'=>'text','class'=>'form-control','placeholder'=>'Add New Category','id'=>'new-category-input'));
                                ?>
                                <span class="input-group-addon">+</span>
                            </div>
                            <a id="list-item-category" class="small pull-right pointer-cursor"> Select from list of category </a>
                          </div>
                        </div>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group text-center">
                    <h5 class="control-label bold no-margin-bottom">Budget Amount</h5>
                    <small> Please enter the budget cost of the item </small><br><br>
                    <div class="">
                        <div class="input-group">
                            <span class="input-group-addon">RM</span>
                            <?php
                              echo $this->Form->input('BNewAmount.amount',array('type'=>'text','class'=>'form-control'));
                            ?>
                        </div>
                    </div>
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
          <h4>
            <?php
              echo $budgetDetail['Budget']['title'] . "(".$budgetDetail['Budget']['year'].")";
            ?>
          </h4>
          <span class="tools pull-right">
              <a href="javascript:;" class="fa fa-chevron-down"></a>
              <!-- <a href="javascript:;" class="fa fa-times"></a> -->
          </span>
        </header>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12"> 
              <div class="">
                <?php
                  echo $budgetDetail['Department']['department_name'];
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
                  echo "<td class='text-center bold'>".$b['BNewAmount']['amount']."</td>";
                  echo "<td class='text-center'>";
                  echo "<div class='btn-group'>";
                  echo "<a href='#requestor-edit-budget' data-toggle='modal' class='btn btn-white btn-xs edit-budget-btn tooltips' data-toggle='tooltips' data-original-title='Edit Budget Amount' data-amount-id='".$b['BNewAmount']['amount_id']."' data-item='".$b['BItem']['item']."' data-amount='".$b['BNewAmount']['amount']."'><i class='fa fa-pencil text-info'></i></a>";
                  echo $this->Form->postlink('<i class="fa fa-trash-o"></i>',array('controller'=>'','action'=>''),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title="Delete Budget Item"'),"Are you sure you want to delete the item from budget?");
                  echo "</div>";
                  echo "</td>";
                  echo "</tr>";
                  $categoryID = $b['BItem']['category_id']; 
                  $totalAmount += $b['BNewAmount']['amount'];
                endforeach;
              ?>
              <tr class="success">
                <td colspan="2" class="bold"> Total Amount </td>
                <td class="text-center bold bigger-text"><?php echo $totalAmount; ?></td>
                <td class="text-center"></td>
              </tr>
            </tbody>
          </table>
          <hr>
          <div class="btn-group pull-right">
            <?php 
              echo $this->Form->postLink("<button class='btn btn-danger'><i class='fa fa-arrow-circle-right'></i> Reconfirm Budget </button>",array('controller'=>'budget','action'=>'reconfirm',$budgetID),array('escape'=>false),'Are you sure you want to confirm this budget?'); 
            ?>
          </div>
        </div>
      </section>
      
    
    </div>
  </div>
</section>

<div aria-hidden="true" aria-labelledby="new" role="dialog" tabindex="-1" id="requestor-edit-budget" class="modal fade">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
              <h4 class="modal-title">Edit Budget Item</h4>
          </div>
          <div class="modal-body">
            <?php
              echo $this->Form->create('BNewAmount',array('url'=>array('controller'=>'budget','action'=>'editBudgetAmount'),'class'=>'form-horizontal','inputDefaults'=>array('label'=>false,'legend'=>false,'div'=>false)));
              echo $this->Form->hidden('BNewAmount.amount_id',array('id'=>'edit-budget-amount-id'));
            ?>
              <div class="form-group">
                <label class="col-sm-4 control-label">Budget Item</label>
                <div class="col-sm-8">
                    <?php
                      echo $this->Form->input('BNewAmount.item',array('type'=>'text','id'=>'edit-budget-item','disabled'=>'disabled','class'=>'form-control'));
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">Budget Amount</label>
                <div class="col-sm-4">
                    <div class="input-group">
                      <span class="input-group-addon">RM</span>
                      <?php
                        echo $this->Form->input('BNewAmount.amount',array('type'=>'text','id'=>'edit-budget-amount','class'=>'form-control'));
                      ?>
                    </div>
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