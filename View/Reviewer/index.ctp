<section class="mems-content">
	<div class="row">
		<!-- Navigation -->
		<div class="col-sm-12">
			<section class="panel">
				<header class="panel-heading">
					<h4>Budget 101 
		                <span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
	                </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
		              <div class="col-lg-12"> 
						<div class="btn-group pull-right">
		                	<?php echo $this->Html->link("<button class='btn btn-default tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Edit Budget'><i class='fa fa-pencil'></i></button>",array('controller'=>'Reviewer','action'=>'edit'),array('escape'=>false)); 

		                	echo '&nbsp;&nbsp;';

		                	?>
		                	<a href="#approval" data-toggle="modal" class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="Approve/Reject">
		                		<button class="btn btn-success"><i class='fa fa-check'></i></button>
		                		<button class="btn btn-danger"><i class='fa fa-times'></i></button> 
		                	</a>

		                	<?php //echo $this->Html->link("<button class='btn btn-success tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Approve/Reject'> <i class='fa fa-check'></i>/<i class='fa fa-times'></i></button>",array('controller'=>'Reviewer','action'=>'edit'),array('escape'=>false)); 

		                	//echo '&nbsp;&nbsp;&nbsp;';

		                	?>
		                	
		                	<?php echo $this->Html->link("<button class='btn btn-warning tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Other Remark'><i class='fa fa-comment'></i></button>",array('controller'=>'Reviewer','action'=>'remark'),array('escape'=>false)); 
		                	
		                	echo '&nbsp;&nbsp;';

		                	?>
		                	
		                	<?php echo $this->Html->link("<button class='btn btn-primary tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Download PDF'><i class='fa fa-cloud-download'></i></button>",array('controller'=>'Reviewer','action'=>'download'),array('escape'=>false)); ?>
		                </div>
		                <br/><br/>
	                  </div>
	                </div>
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
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading">
					<h4>
						Division/Department's Review/Recommendation (Guided by Division/Department LOA)
						<span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
		              <div class="col-lg-3"> 
		                 <h5><b>Prepared by:</b></h5>
		                 <p>
		                 	Wan Syaza Nur Aaina<br/>
		                 	Programme Management Office<br/>
		                 	Executive<br/>
		                 	13 November 2014<br/>
		                 </p>
		              </div>

		              <div class="col-lg-9">
		              	<table class="table table-bordered table-condensed">
		              		<thead >
		              			<tr class="info">
		              				<th>Remark(s)</th>
		              			</tr>
		              		</thead>
		              		<tbody>
		              			<tr style="text-align:justify">
		              				<td>
		              					Remark for budget 101 
		              				</td>
		              			</tr>
		              		</tbody>
		              	</table>
		              </div>
		            </div>



					<div class="row">
					  <div class="col-lg-3"> 
		                 <h5><b>1st Reviewed by:</b></h5>
		                 <p>
		                 	Wan Syaza Nur Aaina<br/>
		                 	Programme Management Office<br/>
		                 	Executive<br/>
		                 	13 November 2014<br/>
		                 </p>
		              </div>
			          <div class="col-lg-9">
						<table class="table table-bordered table-condensed">
	                      <thead>
	                      	<tr class="info">
		                      <th style="width:5%;text-align:center">/</th><th style="width:25%">Approved</th>
		                      <th style="width:5%;text-align:center"></th><th style="width:25%">Rejected</th>
		                      <th style="width:40%">Other remark(s)</th>
		                    </tr>
	                      </thead>
	                      <tbody>
		                      <tr style="text-align:justify">
		                          <td colspan="2">
		                          	 <h5><b>Remark(s) : </b></h5>
			                         <p style="text-align:justify">
			                         	Remark for budget 101 
			                         </p>
		                          </td>
		                          <td colspan="2"></td>
		                          <td>
		                          	<h5><b>Remarks : Clarification on budget 1</b></h5>
		                          	<p>
		                          		<b>To</b> : Requestor, Reviewer 2
		                          	</p>
		                          	<p style="text-align:justify">
		                          		Remark for budget 101  
		                          	</p> 
		                          	 
		                          	
	                          		<table class="table">
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Requestor (13th April 2013) :</b></h5>
												<p>Remark for budget 101  </p>
	                          				</td>
	                          			</tr>
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Reviewer 2 (15th April 2013) :</b></h5>
												<p style="text-align:justify">
													Remark for budget 101  
												</p>
	                          				</td>
	                          			</tr>
	                          		</table>
		                          	
		                          </td>
		                      </tr>
	                      </tbody>
	                    </table>
	                  </div>
                    </div>
                    <div class="row">
					  <div class="col-lg-3"> 
		                 <h5><b>2nd Reviewed by:</b></h5>
		                 <p>
		                 	Wan Syaza Nur Aaina<br/>
		                 	Programme Management Office<br/>
		                 	Executive<br/>
		                 	13 November 2014<br/>
		                 </p>
		              </div>
			          <div class="col-lg-9">
						<table class="table table-bordered table-condensed">
	                      <thead>
	                      	<tr class="info">
		                      <th style="width:5%;text-align:center">/</th><th style="width:25%">Approved</th>
		                      <th style="width:5%;text-align:center"></th><th style="width:25%">Rejected</th>
		                      <th style="width:40%">Other remark(s)</th>
		                    </tr>
	                      </thead>
	                      <tbody>
		                      <tr style="text-align:justify">
		                          <td colspan="2">
		                          	 <h5><b>Remark(s) : </b></h5>
			                         <p style="text-align:justify">
			                         	Remark for budget 101 
			                         </p>
		                          </td>
		                          <td colspan="2"></td>
		                          <td>
		                          	<h5><b>Remarks : Clarification on budget 1</b></h5>
		                          	<p>
		                          		<b>To</b> : Reviewer 1
		                          	</p>
		                          	<p style="text-align:justify">
		                          		Remark for budget 101  
		                          	</p> 
		                          	 
		                          	
	                          		<table class="table">
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Reviewer 1 (15th April 2013) :</b></h5>
												<p>Remark for budget 101  </p>
	                          				</td>
	                          			</tr>
	                          		</table>
		                          	
		                          </td>
		                      </tr>
	                      </tbody>
	                    </table>
	                  </div>
                    </div>
                    <div class="row">
					  <div class="col-lg-3"> 
		                 <h5><b>3rd Reviewed by:</b></h5>
		                 <p>
		                 	Wan Syaza Nur Aaina<br/>
		                 	Programme Management Office<br/>
		                 	Executive<br/>
		                 	13 November 2014<br/>
		                 </p>
		              </div>
			          <div class="col-lg-9">
						<table class="table table-bordered table-condensed">
	                      <thead>
	                      	<tr class="info">
		                      <th style="width:5%;text-align:center">/</th><th style="width:25%">Approved</th>
		                      <th style="width:5%;text-align:center"></th><th style="width:25%">Rejected</th>
		                      <th style="width:40%">Other remark(s)</th>
		                    </tr>
	                      </thead>
	                      <tbody>
		                      <tr>
		                          <td colspan="2">
		                          	 <h5><b>Remark(s) : </b></h5>
			                         <p style="text-align:justify">
			                         	Remark for budget 101 
			                         </p>
		                          </td>
		                          <td colspan="2"></td>
		                          <td>
		                          	<h5><b>Remarks : Clarification on budget 1</b></h5>
		                          	<p>
		                          		<b>To</b> : Requestor
		                          	</p>
		                          	<p style="text-align:justify">
		                          		Remark for budget 101  
		                          	</p> 
		                          	 
		                          	
	                          		<table class="table">
	                          			<tr>
	                          				<td>
												<h5><b>Feedback from Requestor (13th April 2013) :</b></h5>
												<p style="text-align:justify">Remark for budget 101  </p>
	                          				</td>
	                          			</tr>
	                          		</table>
		                          	
		                          </td>
		                      </tr>
	                      </tbody>
	                    </table>
	                  </div>
                    </div>

                </div>

			</section>
			<section class="panel">
				<header class="panel-heading">
					<h4>
						COO/CFO Approval (Guided by Corporate LOA)
						<span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
					  <div class="col-lg-3"> 
		                 <!-- <h5><b>1st Reviewed by:</b></h5> -->
		                 <p>
		                 	Wan Syaza Nur Aaina<br/>
		                 	Programme Management Office<br/>
		                 	Executive<br/>
		                 	13 November 2014<br/>
		                 </p>
		              </div>
			          <div class="col-lg-9">
						<table class="table table-bordered table-condensed">
	                      <thead>
	                      	<tr class="info">
		                      <th style="width:5%;text-align:center">/</th><th style="width:25%">Approved</th>
		                      <th style="width:5%;text-align:center"></th><th style="width:25%">Rejected</th>
		                      <th style="width:40%">Other remark(s)</th>
		                    </tr>
	                      </thead>
	                      <tbody>
		                      <tr style="text-align:justify">
		                          <td colspan="2">
		                          	 <h5><b>Remark(s) : </b></h5>
			                         <p style="text-align:justify">
			                         	Remark for budget 101 
			                         </p>
		                          </td>
		                          <td colspan="2"></td>
		                          <td>
		                          	<h5><b>Remarks : Clarification on budget 1</b></h5>
		                          	<p>
		                          		<b>To</b> : Requestor, Reviewer 2
		                          	</p>
		                          	<p style="text-align:justify">
		                          		Remark for budget 101  
		                          	</p> 
		                          	 
		                          	
	                          		<table class="table">
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Requestor (13th April 2013) :</b></h5>
												<p>Remark for budget 101  </p>
	                          				</td>
	                          			</tr>
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Reviewer 2 (15th April 2013) :</b></h5>
												<p style="text-align:justify">
													Remark for budget 101  
												</p>
	                          				</td>
	                          			</tr>
	                          		</table>
		                          	
		                          </td>
		                      </tr>
	                      </tbody>
	                    </table>
	                  </div>
                    </div>
                </div>
			</section>
			<section class="panel">
				<header class="panel-heading">
					<h4>
						CEO Approval (Guided by Corporate LOA)
						<span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
					  <div class="col-lg-3"> 
		                 <!-- <h5><b>1st Reviewed by:</b></h5> -->
		                 <p>
		                 	Wan Syaza Nur Aaina<br/>
		                 	Programme Management Office<br/>
		                 	Executive<br/>
		                 	13 November 2014<br/>
		                 </p>
		              </div>
			          <div class="col-lg-9">
						<table class="table table-bordered table-condensed">
	                      <thead>
	                      	<tr class="info">
		                      <th style="width:5%;text-align:center">/</th><th style="width:25%">Approved</th>
		                      <th style="width:5%;text-align:center"></th><th style="width:25%">Rejected</th>
		                      <th style="width:40%">Other remark(s)</th>
		                    </tr>
	                      </thead>
	                      <tbody>
		                      <tr style="text-align:justify">
		                          <td colspan="2">
		                          	 <h5><b>Remark(s) : </b></h5>
			                         <p style="text-align:justify">
			                         	Remark for budget 101 
			                         </p>
		                          </td>
		                          <td colspan="2"></td>
		                          <td>
		                          	<h5><b>Remarks : Clarification on budget 1</b></h5>
		                          	<p>
		                          		<b>To</b> : Requestor, Reviewer 2
		                          	</p>
		                          	<p style="text-align:justify">
		                          		Remark for budget 101  
		                          	</p> 
		                          	 
	                          		<table class="table">
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Requestor (13th April 2013) :</b></h5>
												<p>Remark for budget 101  </p>
	                          				</td>
	                          			</tr>
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Reviewer 2 (15th April 2013) :</b></h5>
												<p style="text-align:justify">
													Remark for budget 101  
												</p>
	                          				</td>
	                          			</tr>
	                          		</table>
		                          </td>
		                      </tr>
	                      </tbody>
	                    </table>
	                  </div>
                    </div>
                </div>
			</section>
		</div>
	</div>
	 <div aria-hidden="true" aria-labelledby="new" role="dialog" tabindex="-1" id="approval" class="modal fade">
	              <div class="modal-dialog">
	                  <div class="modal-content">
	                      <div class="modal-header">
	                          <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
	                          <h4 class="modal-title">Approve / Reject Budget Request</h4>
	                      </div>
	                      <div class="modal-body">

	                          <form role="form" class="form-horizontal">
	                              <div class="form-group">
	                                  <label class="col-lg-2 col-sm-2 control-label">Decision</label>
	                                  <div class="col-lg-10">
		                                  <div class="radio">
                                              <label>
                                                  <input type="radio" name="optionsRadios" id="optionsRadios1" value="Approve" checked="">
                                                  Approve
                                              </label>
                                          </div>
                                          <div class="radio">
                                              <label>
                                                  <input type="radio" name="optionsRadios" id="optionsRadios2" value="Reject" checked="">
                                                  Reject
                                              </label>
                                          </div>
	                                  </div>

	                              </div>
	                              
	                              <div class="form-group">
	                              	<label class="col-lg-2 col-sm-2 control-label">Remark</label>
                                    <div class="col-lg-10">
		                                  <textarea class="wysihtml5 form-control col-lg-5" rows="10"></textarea>
	                                 </div>
	                              </div>
	                              
	                              	<button type="submit" class="btn btn-danger">Submit</button>
	                              
	                              
	                          </form>
	                      </div>
	                  </div>
	              </div>
	          </div>
</section>