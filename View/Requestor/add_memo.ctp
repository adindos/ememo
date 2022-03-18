
 <div class="row">
                <div class="col-sm-12">
                  <section class="panel">
                    <header class="panel-heading">
                       INTERNAL MEMO  
                    </header>
                    <div class="panel-body">
                    <form class="form-horizontal tasi-form" method="get" id="myform">   
                        <table class="table table-hover">
                        <tr>
                          <td>
                            
                            <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label"><b>Reference no. </b></label>
                              <div class="col-sm-8">
                                  <input type="text" class="form-control">
                              </div>
                            </div>


                            <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label"><b>To</b></label>
                              <div class="col-sm-8">
                                  <input type="text" class="form-control">
                              </div>
                            </div>

                           <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label"><b>From</b></label>
                              <div class="col-sm-8">
                                  <input type="text" class="form-control">
                              </div>
                            </div>

                            <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label"><b>Subject</b></label>
                              <div class="col-sm-8">
                                   <input type="text" class="form-control">
                              </div>
                            </div>
                          
                          </td>                         
                        </tr>
                       
                        <tr>
                          <td>
                             <div class="form-group">
                              <label class="col-sm-2 col-sm-2 control-label"><b>Prerequisites</b></label>                              
                            </div>
                        
                            <div class="radios">
                             <label class="label_radio" for="radio-01">
                                <input name="sample-radio" id="radio-01" value="1" type="radio" checked  /> Approved vendor
                              </label>

                               <label class="label_radio" for="radio-02">
                                <input name="sample-radio" id="radio-02" value="2" type="radio"/> Approved vendor
                              </label>
                           
                            </div>
                            </td>
                              
                        </tr>
                        <tr>
                          <td>
                              <div class="form-group">
                              <label class="col-sm-3 col-sm-3 control-label"><b>1. Introduction</b></label>
                              <div class="col-sm-10">
                                   <div class="form-group">                                                   
                                      <div class="col-md-12">
                                          <textarea id="introduction" class="wysihtml5 form-control" rows="10"></textarea>
                                      </div>
                                  </div>
                              </div>
                            </div>
                          </td>                          
                        </tr>
                        <tr>
                          <td>
                            <div class="form-group">
                              <label class="col-sm-3 col-sm-3 control-label"><b>2. Subject Matters</b></label>
                              <div class="col-sm-10">
                                   <div class="form-group">                                                   
                                      <div class="col-md-12">
                                          <textarea d="Subject" class="wysihtml5 form-control" rows="10"></textarea>
                                      </div>
                                  </div>
                              </div>
                            </div>
                          </td>                          
                        </tr>
                        <tr>
                          <td>
                            <div class="form-group">
                              <label class="col-sm-3 col-sm-3 control-label"><b>3. Recommendation/Conclusion</b></label>
                              <div class="col-sm-10">
                                   <div class="form-group">                                                   
                                      <div class="col-md-12">
                                          <textarea id="recon" class="wysihtml5 form-control" rows="10"></textarea>
                                      </div>
                                  </div>
                              </div>
                            </div>
                          </td>                          
                        </tr>

                        </table>


                          </form>
                            <div class="form-group" style="text-align:center"> 
                                 <button type="button" class="btn btn-default "><i class="fa fa-times"></i> Cancel  </button>     
                                <button id="editable-sample_new" class="btn btn-success" onclick="window.location.href='<?php echo Router::url(array('controller'=>'requestor', 'action'=>'addMemoReviewer'))?>'">  <i class="fa fa-save"></i>  Proceed</button>
 
                            </div>
                        </div>
                         
                      
                     </div>
                   
                      </section>
                    </div>
                  </section>
              </div>
              </div>  