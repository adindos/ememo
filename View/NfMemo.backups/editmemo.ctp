
 <div class="row">
                <div class="col-sm-12">
                  <section class="panel">
                    <header class="panel-heading">
                       INTERNAL MEMO  
                    </header>
                    <div class="panel-body">
                                      
                      <?php 
                          echo $this->Form->create('NfMemo',array('url'=>array('controller'=>'NfMemo','action'=>'editmemo'),'class'=>'form-horizontal tasi-form','id'=>'firstForm','type'=>'file','inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false,)));

                           echo $this->Form->input('NfMemo.memo_id',array('type'=>'hidden'));

                      ?>
                        <table class="table table-hover">
                          <tr>
                            <td>
                              
                              <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>Reference no. </b></label>
                                <div class="col-sm-8">                                    
                                    <?php
                                      echo $this->Form->input('NfMemo.ref_no',array('type'=>'text','id'=>'autoexpanding','class'=>'form-control'));
                                    ?> 
                                </div>
                              </div>


                              <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>To</b></label>
                                <div class="col-sm-8">
                                    <?php
                                      echo $this->Form->input('NfMemo.to_memo',array('type'=>'text','id'=>'autoexpanding','class'=>'form-control'));
                                    ?>
                                </div>
                              </div>

                             <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>From</b></label>
                                <div class="col-sm-8">                                   
                                  <b><?php echo $memo['User']['Department']['department_name'];
										                        echo $this->Form->input('NfMemo.department_id',array('type'=>'hidden','id'=>'autoexpanding','class'=>'form-control'));
                                  ?></b>
                                </div>
                              </div>

                              <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>Subject</b></label>
                                <div class="col-sm-8">
                                     <?php
                                      echo $this->Form->input('NfMemo.subject',array('type'=>'text','id'=>'autoexpanding','class'=>'form-control'));
                                    ?>
                                </div>
                              </div>

                              <div class="form-group">
                                <label class="col-sm-2 col-sm-2 control-label"><b>Date Required</b></label>
                                <div class="col-sm-8">									 
									
					                <?php
					                echo $this->Form->input('NfMemo.date_required',array('type'=>'text','class'=>'form-control datepicker','style'=>'width:260px'));
					                ?>
		                                               
                                </div>
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
                                            <?php
                                              echo $this->Form->input('NfMemo.introduction',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
                                            ?>
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
                                            <?php
                                              echo $this->Form->input('NfMemo.subject_matters',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
                                            ?>
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
                                            <?php
                                              echo $this->Form->input('NfMemo.recommendation',array('type'=>'textarea','id'=>'autoexpanding','class'=>'wysihtml5 form-control'));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </td>                          
                          </tr>
                        </table>

                         <div class="form-group" style="text-align:center">                              
                          

                           <?php echo $this->Html->link("<button class='btn btn-default' data-toggle='tooltip' data-placement='top' data-original-title='Cancel'><i class='fa fa-times'></i> Cancel</button>",array('controller'=>'NfMemo','action'=>'index'),array('escape'=>false)); ?>

 

                           <?php echo $this->Html->link("<button class='btn btn-success' data-toggle='tooltip' data-placement='top' data-original-title='Proceed'><i class='fa fa-save'></i> Proceed</button>",array('controller'=>'NfMemo','action'=>'confirm'),array('escape'=>false)); ?>

                       </div>
                        <?php
                         echo $this->Form->end();
                        ?>
                     
                       
                      </div>
                         
                      
                     </div>
                   
                      </section>
                    </div>
                  </section>
              </div>
              </div>  