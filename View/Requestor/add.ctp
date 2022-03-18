 <!-- page start-->
              <div class="row">
                <div class="col-sm-12">
                  <section class="panel">
                    <header class="panel-heading">
                       Add new Budget Item
                    </header>
                    <div class="panel-body">
                         <form class="form-horizontal tasi-form" method="get">

                         <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Year</label>
                              <div class="col-sm-10">
                                   <input type="text" class="form-control" style="width:100px">
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Company</label>
                              <div class="col-sm-10">
                                  <b><label class="col-sm-2 col-sm-2 control-label">UNITAR</label></b>
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label">Group</label>
                              <div class="col-sm-10">
                               <b><label class="col-sm-2 col-sm-2 control-label">Corp Shared Services</label></b>                                
                              </div>
                            </div>

                            <div class="form-group">
                            
                              <label class="col-sm-2 col-sm-2 control-label">Department</label>
                              <div class="col-sm-4">
                                   <b><label class="col-sm-2 col-sm-2 control-label">ICT</label></b>        
                              </div>

                              <div class="col-sm-4">                          
                                <div class="form-group">
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
                              </div>
                              </div>

                              <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label">Category</label>
                                <div class="col-sm-4">
                                    <select class="form-control m-bot15">
                                      <option>Category 1</option>
                                      <option>Category 2</option>
                                      <option selected="select">Category 3 </option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                <div class="form-group">
                                      <div class="iconic-input">
                                          <i class="fa fa-plus"></i>
                                          <input type="text" class="form-control" placeholder="Add new Category">
                                      </div>
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
                                <div class="col-sm-6">
                                <div class="form-group">
                                      <div class="iconic-input">
                                          <i class="fa fa-plus"></i>
                                          <input type="text" class="form-control" placeholder="Add new Budget Item">
                                      </div>
                                    </div>

                                  </div>
                                  </div>

                            <div class="form-group" style="text-align:center">      

                              <button id="editable-sample_new" class="btn btn-success" onclick="window.location.href='<?php echo Router::url(array('controller'=>'Budget', 'action'=>'index'))?>'">  <i class="fa fa-save"></i>  Save</button>

                              <button type="button" class="btn btn-default "><i class="fa fa-times"></i> Cancel  </button>
                            </div>


                          
                             

                            </div>
                         </form>
                      
                       
                     </div>
                   
                      </section>
                    </div>
                  </section>
              </div>
              </div>             
              <!-- page end-->