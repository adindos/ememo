<?php
  $this->Html->addCrumb('Budget', array('controller' => 'budget', 'action' => 'index'));
  $this->Html->addCrumb('Master Cost List', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
  <div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Master Cost List
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>

            <div class="panel-body">
              <table class="table table-hover dataTable">
                <thead>
                  <tr>
                      <th class="col-lg-1 text-center"><i class="fa fa-bullhorn"></i> No.</th>
                      <th class="col-lg-3 text-center"><i class="fa fa-briefcase"></i> Company</th>
                      <th class="col-lg-2 text-center"><i class="fa fa-bookmark"></i> Year</th>
                      <th class="col-lg-2 text-center"><i class="fa fa-question-circle"></i> Description</th>
                      <th class="col-lg-2 text-center"><i class="fa fa-dollar"></i> Total Budget (RM) </th>
                      <th class="col-lg-1 text-center"><i class="fa fa-edit"></i> Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $i=1;
                    foreach($mcls as $mcl):
                  ?>
                  <tr>
                      <td class="text-center">
                        <?php
                          echo $i++;
                        ?>
                      </td>
                      <td class="text-left">
                        <?php
                          echo $mcl['Budget']['Company']['company'];
                        ?>
                      </td>
                      <td class="text-center">
                        <?php
                            echo $mcl['Budget']['quarter']."<br>";
                            echo "( ".$mcl['Budget']['year']. " )";
                        ?>
                      </td>
                      <td class="text-center">
                        <small>
                        <?php
                            echo "Master cost list of ".$mcl['Budget']['Company']['company'] . " for ".$mcl['Budget']['quarter']." ".$mcl['Budget']['year'];
                        ?>
                        </small>
                      </td>
                      <td class="text-center">
                        <?php
                            echo number_format($mcl[0]['totalBudget'],2,".",",");
                        ?>
                      </td>
                      <td class="text-center">
                        <?php
                          echo $this->Html->Link('<i class="fa fa-eye"></i> View Master Cost List',array('controller'=>'budget','action'=>'mastercostlist',$mcl['Budget']['company_id'],$mcl['Budget']['quarter'],$mcl['Budget']['year']),array('escape'=>false,'class'=>'btn btn-xs bg-primary'));
                        ?>
                      </td>
                    </tr>
                    <?php
                      endforeach;
                    ?>
                  </tbody>
              </table>
            </div>
        </section>
    </div>
  </div>
</section>