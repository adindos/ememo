 <div class="row">
                <div class="col-sm-12">
                  <section class="panel">
                    <header class="panel-heading">
                       INTERNAL MEMO  
                    </header>
                    <div class="panel-body">
                    <form class="form-horizontal tasi-form" method="get">   
                        <table class="table table-hover">
                        <tr>
                          <td>
                            <div class="form-group">
                             <label class="col-sm-2 col-sm-2 control-label"><b>Remarks</b></label>                                                   
                                      <div class="col-md-8">
                                          <textarea d="Subject" class="wysihtml5 form-control" rows="10"></textarea>
                                      </div>
                                  </div>
                          </td>
                        </tr>
                         <tr>
                            <td>
                             <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>Select Reviewer</b></label>
                                <div class="col-sm-8">
                                    <select class="select2-multiple full-width" multiple="" id="my_multi_select3">
                                      <option>Reviewer 1</option>
                                      <option>Reviewer 2</option>
                                      <option>Reviewer 3 </option>
                                    </select>
                                </div>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                             <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>Select Approval</b></label>
                                <div class="col-sm-8">
                                     <select class="select2-multiple full-width" multiple="" id="my_multi_select3">
                                      <option>CEO </option>
                                      <option>CFO </option>
                                      <option>COO  </option>
                                    </select>
                                </div>
                              </div>
                            </td>
                          </tr>
                        </table>
                     </form>

                    <div class="form-group" style="text-align:center"> 
                         <button type="button" class="btn btn-default "><i class="fa fa-times"></i> Cancel  </button>     
                         <button id="editable-sample_new" class="btn btn-success" onclick="window.location.href='<?php echo Router::url(array('controller'=>'Reviewer', 'action'=>'budget'))?>'">  <i class="fa fa-save"></i>  Preview</button>
                    </div>
                    </div> 
                    </section>
                    </div>
                  </section>
              </div>
              </div>  