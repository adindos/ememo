<section class="mems-content">
  <div class="row">
    <!-- Navigation -->
    <div class="col-sm-12">
      
      <section class="panel">
        <header class="panel-heading">
          <h4>Budget Year 2015 
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <!-- <a href="javascript:;" class="fa fa-times"></a> -->
            </span>
          </h4>
        </header>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12 info"> 
               <button type="button" class="btn btn-warning btn-sm">UNITAR</button>
               <button type="button" class="btn btn-primary btn-sm">Corp Shared Services</button>
            </div>
          </div>
          <table class="table table-bordered table-striped table-condensed">
            <thead>
           
              <tr>
                <th rowspan="2" colspan="2" style="width:50%">DESCRIPTION</th>
                <th rowspan="2">TOTAL</th>
                <th style="width:80px">Group</th>
                <th colspan="3">Corp Shared Services</th>
              </tr>
              <tr>
                <th>Department</th>
                  <th>Hr/css</th>
                  <th>PMD</th>
                  <th>ICT</th>
              </tr>
             
            </thead>
            <tbody>
              <tr class="info">
                  <td colspan='7'>Internet Access</td>
                                        
              </tr>
              <tr class="">
                  <td>1.</td>
                  <td> TIME Internet leased line - HQ (20 Mbps)</td>
                  <td style="color:#0080FF"> 16,800 </td> 
                  <td></td>  
                  <td> 3,840 </td>   
                  <td> 6,240 </td>  
                  <td> 6,720 </td>

              </tr>
              <tr class="">
                  <td>2.</td>
                  <td> MAXIS (Packnet) - HQ (10 Mbps)</td>
                   <td style="color:#0080FF"> 12,600 </td>  
                    <td></td>  
                  <td> 2,880 </td> 
                  <td> 4,680</td>  
                  <td> 5,040 </td>                          
              </tr>
              <tr class="">
                  <td>3.</td>
                  <td> CELCOM Broadband (18 Unit) </td>
                   <td style="color:#0080FF">  5,600 </td>  
                    <td></td>  
                  <td> 1,280 </td>      
                  <td> 2,080 </td>  
                  <td> 2,240 </td>                     
              </tr>
              <tr class="">
                  <td>4.</td>
                  <td> Streamyx - TLDM Lumut (1.5 Mbps)
                        <small>(Sekolah Hospitaliti KD Pelanduk, Lumut)</small></td>
                   <td style="color:#0080FF">- </td>  
                    <td></td>  
                  <td>-</td>  
                  <td>-</td>  
                  <td>-</td>                         
              </tr>
              <tr class="">
                  <td>5.</td>
                  <td>TM Unify - (HQ) (20 Mbps) </td>
                   <td style="color:#0080FF">503  </td>  
                    <td></td>  
                  <td>  115  </td>    
                  <td>187</td>  
                  <td>201</td>                       
              </tr>

               <tr class="info">
                  <td colspan='7'>Telephone/PABX System</td>
                                        
              </tr>
               <tr class="">
                  <td>1.</td>
                  <td>PABX Maintenance Service</td>
                  <td style="color:#0080FF">4,365</td>  
                   <td></td>  
                  <td>998</td>    
                  <td> 1,621 </td>  
                  <td> 1,746 </td>                       
              </tr>
              <tr class="">
                  <td>2.</td>
                  <td>TIME - PRI</td>
                   <td style="color:#0080FF">12,600</td>  
                    <td></td>  
                  <td>2,880</td>   
                  <td> 4,680</td>  
                  <td>5,040 </td>                        
              </tr>
              <tr class="">
                  <td>3.</td>
                  <td>TM - 2 DID</td>
                  <td style="color:#0080FF">420</td>  
                   <td></td>  
                  <td>96</td>       
                  <td>156</td>  
                  <td> 168 </td>                    
              </tr>

              <tr class="success">
                  <td colspan="2"><b>Total Amount</b></td>     
                                   
                  <td> <b>52,888 </b></td>  
                  <td></td>
                  <td><b> 12,089</b> </td> 
                  <td><b>19,644</b></td>  
                  <td><b>21,155</b></td>                          
              </tr>
            </tbody>
          </table>
          <br/>
          <div class="btn-group pull-right">
            <?php echo $this->Html->link("<button class='btn btn-danger'><i class='fa fa-arrow-circle-right'></i> Confirm Budget</button>",array('controller'=>'Requestor','action'=>'addBudgetReviewer'),array('escape'=>false)); 
            ?>
          </div>
        </div>
      </section>
      <section class="panel">
        <header class="panel-heading">
          <h4>Add Item to Budget 
            <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
                <!-- <a href="javascript:;" class="fa fa-times"></a> -->
            </span>
          </h4>
        </header>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12"> 
              <form class="form-horizontal tasi-form" method="get">
              <!-- <h4>Add Item to Budget</h4><br/><br/> -->
                <!-- <div class="form-group">
                  <label class="col-sm-2 col-sm-2 control-label">Department</label>
                  <div class="col-sm-4">
                      <select class="form-control m-bot15">
                        <option>HR/CSS </option>
                        <option>PMD </option>
                        <option selected="select"> ICT</option>
                        <option>PASD</option>
                      </select>
                  </div>

                  <div class="col-sm-4">                          
                    <label class="control-label col-md-3">No of staff</label>
                    <div class="col-md-4">
                      <div class="spinner">
                          <div class="input-group input-small">
                              <input type="text" class="spinner-input form-control" maxlength="3" readonly>
                              <div class="spinner-buttons input-group-btn btn-group-vertical">
                                  <button type="button" class="btn spinner-up btn-xs btn-default">
                                      <i class="fa fa-angle-up"></i>
                                  </button>
                                  <button type="button" class="btn spinner-down btn-xs btn-default">
                                      <i class="fa fa-angle-down"></i>
                                  </button>
                              </div>
                          </div>
                      </div>
                    </div>
                  </div>
                </div> -->

                  <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Category</label>
                    <div class="col-sm-4">
                        <select class="form-control m-bot15">
                          <option>Category 1</option>
                          <option>Category 2</option>
                          <option selected="select">Category 3 </option>
                        </select>
                    </div>
                    <div class="col-sm-1" style="text-align:center">
                      <h4>or</h4>
                    </div>
                    <div class="col-sm-5">
                        <div class="iconic-input">
                            <i class="fa fa-plus"></i>
                            <input type="text" class="form-control" placeholder="Add new Category">
                        </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Budget Item</label>
                    <div class="col-sm-4">
                        <select class="form-control m-bot15">
                          <option>Item 1</option>
                          <option>Item 2</option>
                          <option selected="select">Item 3 </option>
                        </select>
                    </div>
                    <div class="col-sm-1" style="text-align:center">
                      <h4>or</h4>
                    </div>
                    <div class="col-sm-5">
                        <div class="iconic-input">
                            <i class="fa fa-plus"></i>
                            <input type="text" class="form-control" placeholder="Add new Budget Item">
                        </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Budget Amount</label>
                    <div class="col-sm-4">
                        <div class="input-group m-bot15">
                          <span class="input-group-addon">RM</span>
                          <input type="text" class="form-control" >
                        </div>
                    </div>
                  </div>

                  <div class="form-group" style="text-align:center">      
                    <button id="editable-sample_new" class="btn btn-success" onclick="window.location.href='<?php echo Router::url(array('controller'=>'requestor', 'action'=>'index1'))?>'">  <i class="fa fa-save"></i> Add</button>

                    <!-- <button type="button" class="btn btn-default "><i class="fa fa-times"></i> Cancel  </button> -->
                  </div>
              </form>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</section>