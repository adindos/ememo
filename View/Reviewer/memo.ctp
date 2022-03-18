<section class="mems-content">
	<div class="row">
		<!-- Navigation -->
		<div class="col-sm-12">
			<section class="panel">
				<header class="panel-heading">
					<h4>Internal Memo Financial 101
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
		                	<?php echo $this->Html->link("<button class='btn btn-default tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Edit Memo'><i class='fa fa-pencil'></i> Edit</button>",array('controller'=>'Requestor','action'=>'addMemo'),array('escape'=>false)); 

		                	echo '&nbsp;&nbsp;';

		                	?>
		                	<a href="#approval" data-toggle="modal" class="tooltips" data-toggle="tooltip" data-placement="top" data-original-title="Approve/Reject Memo">
		                		<button class="btn btn-success"><i class='fa fa-check'></i> Approve</button>
		                		<button class="btn btn-danger"><i class='fa fa-times'></i> Reject</button> 
		                	</a>

		                	<?php echo $this->Html->link("<button class='btn btn-warning tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Remark'><i class='fa fa-comment'></i> Remarks</button>",array('controller'=>'Reviewer','action'=>'remark'),array('escape'=>false)); 
		                	
		                	echo '&nbsp;&nbsp;';

		                	?>
		                	
		                	<?php echo $this->Html->link("<button class='btn btn-primary tooltips' data-toggle='tooltip' data-placement='top' data-original-title='Print Memo'><i class='fa fa-cloud-download'></i> Print</button>",array('controller'=>'Reviewer','action'=>'download'),array('escape'=>false)); ?>
		                </div>
		                <br/><br/>
	                  </div>
	                </div>
					<div class="row">
					  <br/>
		              <div class="col-lg-12"> 
		              	<table class="table table-bordered table-striped table-condensed">
		              		<tbody>
		              			<tr><th>Reference no.</th><td><b>(05/2015/zaq12wsx)</b></td></tr>
		              			<tr><th>To</th><td><b>Ali bin Abu</b></td></tr>
		              			<tr><th>From</th><td><b>Department of ICT</b></td></tr>
		              			<tr><th>Subject</th><td><b>BUSINESS TRIP & PARTICIPATE IN 2ND EXCELLENCE EXHIBITION FOR MALAYSIAN UNIVERSITIES ON 15TH-17TH MAY 2013</b></td></tr>
		              		</tbody>
		              	</table>
		              </div>
		            </div>
		           <div class="row">
		           	   <br/>
			           <div class="col-md-1">
			           	  <b>Prerequisites :</b>
		                </div>
		                <div class="col-md-3">
			           	  <div class="checkboxes">
		                      <label class="label_check" for="checkbox-01">
		                          <input type="checkbox" id="checkbox-01" checked="true"> Financial
		                      </label>
		                      <label class="label_check" for="checkbox-02">
		                          <input type="checkbox" id="checkbox-02" > Non-Financial
		                      </label>
		                  </div>
		                </div>
		                <div class="col-md-3">
			           	  <div class="checkboxes">
		                      <label class="label_check" for="checkbox-01">
		                          <input type="checkbox" id="checkbox-01"> Budgeted
		                      </label>
		                      <label class="label_check" for="checkbox-02">
		                          <input type="checkbox" id="checkbox-02"> Unbudgeted
		                      </label>
		                  </div>
		                </div>
		                <div class="col-md-3">
		                	<div class="checkboxes">
		                      <label class="label_check" for="checkbox-01">
		                          <input type="checkbox" id="checkbox-01" checked="true"> Approved vendor
		                      </label>
		                      <label class="label_check" for="checkbox-02">
		                          <input type="checkbox" id="checkbox-02"> New Vendor
		                      </label>
		                  	</div>
		                </div>
		                <div class="col-md-2 pull-right">
		                
			           	  <b>Date required :</b> 12/12/2015 
		                </div>
		           </div>
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading">
					<h4>1. Introduction
		                <span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
		              <div class="col-lg-12"> 
						In our effort to promote UNITAR, we would like to participate in <b>2nd Excellence Exhibition For Malaysian Universities</b> in Yemen.  UNITAR staff who will be going are:
						<ul>
							<li class="disc">En. Wan Ahmad Saifuddin Wan Ahmad Radzi, CEO
							<li class="disc">En. Sheikh Fahmy Sheikh Mohamed, General Manager (SACO)
						</ul>
	                  </div>
	                </div>
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading">
					<h4>2. Subject Matters
		                <span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
		              <div class="col-lg-12"> 
						Details of the event as below:<br/><br/>
						&nbsp;&nbsp;&nbsp;&nbsp;<b>Date: 14 – 18 May 2014</b> <br/>
						&nbsp;&nbsp;&nbsp;&nbsp;<b>Venue: Yemen </b><br/><br/>


						The trip will conclude 5 days/4 nights leaving on 14 May and returning on 18 May 2014. Summary of the costing for the event as below. <br/><br/>

						<table class="table table-bordered table-condensed">
						<thead>
							<th>No</th>
							<th>Items</th>
							<th>Unit</th>
							<th>Cost (RM)</th>
							<th>Remarks</th>
						</thead>
						<tbody>
							<tr><td>1.</td><td>Flight </td><td>2</td><td>XXX</td><td></td></tr>
							<tr><td>2.</td><td>Accommodation </td><td>1</td><td>XXX</td><td>Twin sharing</td></tr>
							<tr><td>3.</td><td>Ground transport </td><td>2</td><td>XXX</td><td></td></tr>
							<tr><td></td><th>Total </th><td></td><th>XXX</th><td></td></tr>
						</tbody>
						</table>

	                  </div>
	                </div>
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading">
					<h4>3. Recommendation/Conclusion
		                <span class="tools pull-right">
		                    <a href="javascript:;" class="fa fa-chevron-down"></a>
		                    <!-- <a href="javascript:;" class="fa fa-times"></a> -->
		                </span>
		            </h4>
				</header>
				
				<div class="panel-body">
					<div class="row">
		              <div class="col-lg-12 text"> 
						Upon approval, we would like to seek for Finance Division to issue the cheque with the payees name, <b>EXCELLENCE INTERNATIONAL FOR EDUCATIONAL & PROMOTIONAL SERVICES</b> amounting <b>RM11, 445.60</b> or <b>USD 3800</b>.
	                  </div>
	                </div>
                </div>
			</section>

			<section class="panel">
				<header class="panel-heading">
					<h4>
						4. Division/Department's Review/Recommendation (Guided by Division/Department LOA)
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
		              					Remark for memo 101 
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
			                         	Remark for memo 101 
			                         </p>
		                          </td>
		                          <td colspan="2"></td>
		                          <td>
		                          	<h5><b>Remarks : Clarification on budget 1</b></h5>
		                          	<p>
		                          		<b>To</b> : Requestor, Reviewer 2
		                          	</p>
		                          	<p style="text-align:justify">
		                          		Remark for memo 101  
		                          	</p> 
		                          	 
		                          	
	                          		<table class="table">
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Requestor (13th April 2013) :</b></h5>
												<p>Remark for memo 101  </p>
	                          				</td>
	                          			</tr>
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Reviewer 2 (15th April 2013) :</b></h5>
												<p style="text-align:justify">
													Remark for memo 101  
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
			                         	Remark for memo 101 
			                         </p>
		                          </td>
		                          <td colspan="2"></td>
		                          <td>
		                          	<h5><b>Remarks : Clarification on budget 1</b></h5>
		                          	<p>
		                          		<b>To</b> : Reviewer 1
		                          	</p>
		                          	<p style="text-align:justify">
		                          		Remark for memo 101  
		                          	</p> 
		                          	 
		                          	
	                          		<table class="table">
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Reviewer 1 (15th April 2013) :</b></h5>
												<p>Remark for memo 101  </p>
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
			                         	Remark for memo 101 
			                         </p>
		                          </td>
		                          <td colspan="2"></td>
		                          <td>
		                          	<h5><b>Remarks : Clarification on budget 1</b></h5>
		                          	<p>
		                          		<b>To</b> : Requestor
		                          	</p>
		                          	<p style="text-align:justify">
		                          		Remark for memo 101  
		                          	</p> 
		                          	 
		                          	
	                          		<table class="table">
	                          			<tr>
	                          				<td>
												<h5><b>Feedback from Requestor (13th April 2013) :</b></h5>
												<p style="text-align:justify">Remark for memo 101  </p>
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
						5. COO/CFO Approval (Guided by Corporate LOA)
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
			                         	Remark for memo 101 
			                         </p>
		                          </td>
		                          <td colspan="2"></td>
		                          <td>
		                          	<h5><b>Remarks : Clarification on budget 1</b></h5>
		                          	<p>
		                          		<b>To</b> : Requestor, Reviewer 2
		                          	</p>
		                          	<p style="text-align:justify">
		                          		Remark for memo 101  
		                          	</p> 
		                          	 
		                          	
	                          		<table class="table">
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Requestor (13th April 2013) :</b></h5>
												<p>Remark for memo 101  </p>
	                          				</td>
	                          			</tr>
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Reviewer 2 (15th April 2013) :</b></h5>
												<p style="text-align:justify">
													Remark for memo 101  
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
						6. CEO Approval (Guided by Corporate LOA)
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
			                         	Remark for memo 101 
			                         </p>
		                          </td>
		                          <td colspan="2"></td>
		                          <td>
		                          	<h5><b>Remarks : Clarification on budget 1</b></h5>
		                          	<p>
		                          		<b>To</b> : Requestor, Reviewer 2
		                          	</p>
		                          	<p style="text-align:justify">
		                          		Remark for memo 101  
		                          	</p> 
		                          	 
	                          		<table class="table">
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Requestor (13th April 2013) :</b></h5>
												<p>Remark for memo 101  </p>
	                          				</td>
	                          			</tr>
	                          			<tr style="text-align:justify">
	                          				<td>
												<h5><b>Feedback from Reviewer 2 (15th April 2013) :</b></h5>
												<p style="text-align:justify">
													Remark for memo 101  
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
                  <h4 class="modal-title">Approve / Reject Memo Request</h4>
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
                          <label class="col-lg-2 col-sm-2 control-label">Budgeted / Unbudgeted (Finance for financial memo only)</label>
                          <div class="col-lg-10">
                              <div class="radios">
                                  <label class="label_radio" for="radio-01">
                                      <input type="radio" id="radio-01" value="Budgeted" checked="">
                                      Budgeted
                                  </label>
                             
                                  <label class="label_radio" for="radio-02">
                                      <input type="radio" id="radio-02" value="Unbudgeted">
                                      Unbudgeted
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