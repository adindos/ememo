<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
	$('.editBudget').hide();
    $('.editButton').on('click', function () {
        $('.editBudget').show();
    });
});
</script>

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
		              <div class="col-lg-12"> 
						<div class="btn-group pull-right">
		                	<button id='editButton1' class='btn btn-default tooltips editButton' data-toggle='tooltip' data-placement='top' data-original-title='Edit Budget'><i class='fa fa-pencil'></i> Edit</button>

		                	&nbsp;
		                	
		                	<a href="#approval" data-toggle="modal" class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="Approve/Reject Budget">
		                		<button class="btn btn-success"><i class='fa fa-check'></i> Approve</button>
		                		<button class="btn btn-danger"><i class='fa fa-times'></i> Reject</button> 
		                	</a>

		                	<?php echo $this->Html->link("<button class='btn btn-warning tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Remark'><i class='fa fa-comment'></i> Remarks</button>",array('controller'=>'Reviewer','action'=>'remark'),array('escape'=>false)); 
		                	
		                	echo '&nbsp;&nbsp;';

		                	?>
		                	
		                	<?php echo $this->Html->link("<button class='btn btn-primary tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Print Budget'><i class='fa fa-cloud-download'></i> Print</button>",array('controller'=>'Reviewer','action'=>'download'),array('escape'=>false)); ?>
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
                          <!-- <th id='editBudget'></th> -->

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
                          <td>
                          	<div class="btn-group pull-left editBudget">
                          		<a href="#edit" data-toggle="modal" class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="Edit">
		                			<button class="btn btn-default"><i class='fa fa-pencil'></i></button>
		                		</a>
		                		<?php echo $this->Html->link("<button class='btn btn-danger tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Delete'><i class='fa fa-times'></i></button>",array('controller'=>'Reviewer','action'=>'delete'),array('escape'=>false)); ?>
		                	</div>
                          </td>  
                          <td> 3,840 </td>   
                          <td> 6,240 </td>  
                          <td> 6,720 </td>
                      </tr>
                      <tr class="">
                          <td>2.</td>
                          <td> MAXIS (Packnet) - HQ (10 Mbps)</td>
                           <td style="color:#0080FF"> 12,600 </td>  
                           <td>
                          	<div class="btn-group pull-left editBudget">
                          		<a href="#edit" data-toggle="modal" class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="Edit">
		                			<button class="btn btn-default"><i class='fa fa-pencil'></i></button>
		                		</a>
		                		<?php echo $this->Html->link("<button class='btn btn-danger tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Delete'><i class='fa fa-times'></i></button>",array('controller'=>'Reviewer','action'=>'delete'),array('escape'=>false)); ?>
		                	</div>
                          </td>    
                          <td> 2,880 </td> 
                          <td> 4,680</td>  
                          <td> 5,040 </td>                          
                      </tr>
                      <tr class="">
                          <td>3.</td>
                          <td> CELCOM Broadband (18 Unit) </td>
                           <td style="color:#0080FF">  5,600 </td>  
                           <td>
                          	<div class="btn-group pull-left editBudget">
                          		<a href="#edit" data-toggle="modal" class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="Edit">
		                			<button class="btn btn-default"><i class='fa fa-pencil'></i></button>
		                		</a>
		                		<?php echo $this->Html->link("<button class='btn btn-danger tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Delete'><i class='fa fa-times'></i></button>",array('controller'=>'Reviewer','action'=>'delete'),array('escape'=>false)); ?>
		                	</div>
                          </td>  
                          <td> 1,280 </td>      
                          <td> 2,080 </td>  
                          <td> 2,240 </td>                     
                      </tr>
                      <tr class="">
                          <td>4.</td>
                          <td> Streamyx - TLDM Lumut (1.5 Mbps)
                                <small>(Sekolah Hospitaliti KD Pelanduk, Lumut)</small></td>
                           <td style="color:#0080FF">- </td>  
                           <td>
                          	<div class="btn-group pull-left editBudget">
                          		<a href="#edit" data-toggle="modal" class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="Edit">
		                			<button class="btn btn-default"><i class='fa fa-pencil'></i></button>
		                		</a>
		                		<?php echo $this->Html->link("<button class='btn btn-danger tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Delete'><i class='fa fa-times'></i></button>",array('controller'=>'Reviewer','action'=>'delete'),array('escape'=>false)); ?>
		                	</div>
                          </td>    
                          <td>-</td>  
                          <td>-</td>  
                          <td>-</td>                         
                      </tr>
                      <tr class="">
                          <td>5.</td>
                          <td>TM Unify - (HQ) (20 Mbps) </td>
                           <td style="color:#0080FF">503  </td>  
                           <td>
                          	<div class="btn-group pull-left editBudget">
                          		<a href="#edit" data-toggle="modal" class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="Edit">
		                			<button class="btn btn-default"><i class='fa fa-pencil'></i></button>
		                		</a>
		                		<?php echo $this->Html->link("<button class='btn btn-danger tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Delete'><i class='fa fa-times'></i></button>",array('controller'=>'Reviewer','action'=>'delete'),array('escape'=>false)); ?>
		                	</div>
                          </td>   
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
                          <td>
                          	<div class="btn-group pull-left editBudget">
                          		<a href="#edit" data-toggle="modal" class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="Edit">
		                			<button class="btn btn-default"><i class='fa fa-pencil'></i></button>
		                		</a>
		                		<?php echo $this->Html->link("<button class='btn btn-danger tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Delete'><i class='fa fa-times'></i></button>",array('controller'=>'Reviewer','action'=>'delete'),array('escape'=>false)); ?>
		                	</div>
                          </td>    
                          <td>998</td>    
                          <td> 1,621 </td>  
                          <td> 1,746 </td>                       
                      </tr>
                      <tr class="">
                          <td>2.</td>
                          <td>TIME - PRI</td>
                           <td style="color:#0080FF">12,600</td>  
                           <td>
                          	<div class="btn-group pull-left editBudget">
                          		<a href="#edit" data-toggle="modal" class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="Edit">
		                			<button class="btn btn-default"><i class='fa fa-pencil'></i></button>
		                		</a>
		                		<?php echo $this->Html->link("<button class='btn btn-danger tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Delete'><i class='fa fa-times'></i></button>",array('controller'=>'Reviewer','action'=>'delete'),array('escape'=>false)); ?>
		                	</div>
                          </td>    
                          <td>2,880</td>   
                          <td> 4,680</td>  
                          <td>5,040 </td>                        
                      </tr>
                      <tr class="">
                          <td>3.</td>
                          <td>TM - 2 DID</td>
                          <td style="color:#0080FF">420</td>  
                          <td>
                          	<div class="btn-group pull-left editBudget">
                          		<a href="#edit" data-toggle="modal" class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="Edit">
		                			<button class="btn btn-default"><i class='fa fa-pencil'></i></button>
		                		</a>
		                		<?php echo $this->Html->link("<button class='btn btn-danger tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Delete'><i class='fa fa-times'></i></button>",array('controller'=>'Reviewer','action'=>'delete'),array('escape'=>false)); ?>
		                	</div>
                          </td>    
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

			<section class="panel editBudget" >
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
                              <div class="radios">
                                  <label class="label_radio" for="radio-01">
                                      <input type="radio" id="radio-01" value="Approve" checked="">
                                      Approve
                                  </label>
                             
                                  <label class="label_radio" for="radio-02">
                                      <input type="radio" id="radio-02" value="Reject">
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

    <div aria-hidden="true" aria-labelledby="new" role="dialog" tabindex="-1" id="edit" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                  <h4 class="modal-title">Edit Budget Item</h4>
              </div>
              <div class="modal-body">

                  <form role="form" class="form-horizontal">
                  	  <div class="form-group">
	                    <label class="col-sm-4 control-label" >Category</label>
	                    <div class="col-sm-4">
	                        <select class="form-control m-bot15" disabled="">
	                          <option>Category 1</option>
	                          <option>Category 2</option>
	                          <option selected="select">Category 3 </option>
	                        </select>
	                    </div>
	                  </div>

	                  <div class="form-group">
	                    <label class="col-sm-4 control-label">Budget Item</label>
	                    <div class="col-sm-4">
	                        <select class="form-control m-bot15" disabled="">
	                          <option>Item 1</option>
	                          <option>Item 2</option>
	                          <option selected="select">Item 3 </option>
	                        </select>
	                    </div>
	                  </div>

	                  <div class="form-group">
	                    <label class="col-sm-4 control-label">Budget Amount</label>
	                    <div class="col-sm-4">
	                        <div class="input-group m-bot15">
	                          <span class="input-group-addon">RM</span>
	                          <input type="text" class="form-control" value="3840.00" >
	                        </div>
	                    </div>
	                  </div>

                  	<button type="submit" class="btn btn-danger">Save</button>
                  </form>
              </div>
          </div>
      </div>
    </div>
</section>