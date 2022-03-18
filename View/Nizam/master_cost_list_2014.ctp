<section class="mems-content">
    <div class="row col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Master Cost List
                <span class="tools pull-right">
                    <a class="fa fa-chevron-down" href="javascript:;"></a>
                </span>
            </header>

            <div class="panel-body">
            	<div class="margin-bottom pull-left">
            		<h4> UNITAR Master Cost List 2014 </h4>
            	</div>
            	<div class="margin-bottom pull-right">
            		<?php
            			echo $this->Html->link('<i class="fa fa-save"></i> Save in Computer',array('controller'=>'','action'=>''),array('escape'=>false,'class'=>'btn btn-sm btn-round btn-success margin-right'));
            			echo $this->Html->link('<i class="fa fa-download"></i> Download as PDF',array('controller'=>'','action'=>''),array('escape'=>false,'class'=>'btn btn-sm btn-round btn-info'));
            		?>
            	</div>
            	<div class="clear"></div>
            	<div class="mCustomScrollbar">
	                <table class="table table-bordered table-striped">
	                <!-- <table style="width:auto" class="table table-bordered table-striped"> -->
	                    <thead>
	                        <tr class="">
	                            <th colspan="2" class="col-lg-6 text-center light-grey-bg">Department</th>
	                            <th></th>
	                            <th colspan="9" class="text-center">VC Office</th>
	                            <th colspan="2" class="text-center">CEO Office</th>
	                            <th colspan="3" class="text-center">Corp Shared Services</th>
	                            <th colspan="2" class="text-center">Corp Finance</th>
	                        </tr>
	                        <tr>
	                            <th colspan="2" class="text-center light-grey-bg"> Description of Income Statement Item </th>
	                            <th class="text-center">TOTAL</th>
	                            <!-- VC Office -->
	                            <th class="text-center">VC & DVC Office</th>
	                            <th class="text-center">FECS</th>
	                            <th class="text-center">FESS</th>
	                            <th class="text-center">FBA</th>
	                            <th class="text-center">FHCM</th>
	                            <th class="text-center">FIT</th>
	                            <th class="text-center">SFGS</th>
	                            <th class="text-center">GS</th>
	                            <th class="text-center">CPE</th>
	                            <!-- CEO Office -->
	                            <th class="text-center">KMC</th>
	                            <th class="text-center">URC</th>
	                            <!-- Shared Service -->
	                            <th class="text-center">HR/CSS</th>
	                            <th class="text-center">PMD</th>
	                            <th class="text-center">ICT</th>
	                            <!-- Finance -->
	                            <th class="text-center">FA/Corp Finance</th>
	                            <th class="text-center">SAM</th>
	                        </tr>
	                    </thead>
	                    <tbody>
	                    <tr class="info">
	                        <td colspan='10000'>Internet Access</td>
	                    </tr>
	                    <tr class="">
	                        <td class="text-center">1.</td>
	                        <td> TIME Internet leased line - HQ (20 Mbps)</td>
	                        <!-- Total -->
	                        <td class="blue bold text-center"> 16,800 </td> 
	                        <!-- VC Office -->
	                        <td class="text-center"> 1,200 </td>
	                        <td class="text-center"> 6,300 </td>
	                        <td class="text-center"> 6,400 </td>
	                        <td class="text-center"> 5,500 </td>
	                        <td class="text-center"> 7,600 </td>
	                        <td class="text-center"> 5,700 </td>
	                        <td class="text-center"> 4,800 </td>
	                        <td class="text-center"> 5,300 </td>
	                        <td class="text-center"> 11,700 </td>
	                        <!-- CEO Office -->
	                        <td class="text-center"> 5,040 </td> 
	                        <td class="text-center"> 2,940 </td> 
	                        <!-- Shared Service -->
	                        <td class="text-center"> 3,840 </td>   
	                        <td class="text-center"> 6,240 </td>  
	                        <td class="text-center"> 6,720 </td>
	                        <!-- Finance -->
	                        <td class="text-center"> 2,400 </td>   
	                        <td class="text-center"> 4,800 </td>   
	                    </tr>
	                    <tr class="">
	                        <td class="text-center">2.</td>
	                        <td> MAXIS (Packnet) - HQ (10 Mbps)</td>
	                        <!-- Total -->
	                        <td class="blue bold text-center"> 12,600 </td> 
	                        <!-- VC Office -->
	                        <td class="text-center"> 4,242 </td>
	                        <td class="text-center"> 1,200 </td>
	                        <td class="text-center"> 2,555 </td>
	                        <td class="text-center"> 2,220 </td>
	                        <td class="text-center"> 2,300 </td>
	                        <td class="text-center"> 5,412 </td>
	                        <td class="text-center"> 5,400 </td>
	                        <td class="text-center"> 6,660 </td>
	                        <td class="text-center"> 1,800 </td>
	                        <!-- CEO Office -->
	                        <td class="text-center"> 3,660 </td> 
	                        <td class="text-center"> 3,000 </td> 
	                        <!-- Shared Service --> 
	                        <td class="text-center"> 2,880 </td> 
	                        <td class="text-center"> 4,680</td>  
	                        <td class="text-center"> 5,040 </td> 
	                        <!-- Finance -->
	                        <td class="text-center"> 1,800 </td>   
	                        <td class="text-center"> 2,800 </td>                            
	                    </tr>
	                    <tr class="">
	                        <td class="text-center">3.</td>
	                        <td> CELCOM Broadband (18 Unit) </td>
	                        <!-- Total -->
	                        <td class="blue bold text-center">  5,600 </td>
	                        <!-- VC Office -->
	                        <td class="text-center"> 5,232 </td>
	                        <td class="text-center"> 2,666 </td>
	                        <td class="text-center"> 6,905 </td>
	                        <td class="text-center"> 5,644 </td>
	                        <td class="text-center"> 2,342 </td>
	                        <td class="text-center"> 1,111 </td>
	                        <td class="text-center"> 2,242 </td>
	                        <td class="text-center"> 5,116 </td>
	                        <td class="text-center"> 2,125 </td> 
	                        <!-- CEO Office -->
	                        <td class="text-center"> 840 </td> 
	                        <td class="text-center"> 2,840 </td> 
	                        <!-- Shared Service -->
	                        <td class="text-center"> 1,280 </td>      
	                        <td class="text-center"> 2,080 </td>  
	                        <td class="text-center"> 2,240 </td>    
	                        <!-- Finance -->
	                        <td class="text-center"> 8,400 </td>   
	                        <td class="text-center"> 4,800 </td>                    
	                    </tr>
	                    <tr class="">
	                        <td class="text-center">4.</td>
	                        <td> 
	                            Streamyx - TLDM Lumut (1.5 Mbps)
	                            <small>(Sekolah Hospitaliti KD Pelanduk, Lumut)</small>
	                        </td>
	                        <!-- Total -->
	                        <td class="blue bold text-center">- </td> 
	                        <!-- VC Office -->
	                        <td class="text-center"> 2,345 </td>
	                        <td class="text-center"> 5,755 </td>
	                        <td class="text-center"> 2,700 </td>
	                        <td class="text-center"> 2,090 </td>
	                        <td class="text-center"> 3,900 </td>
	                        <td class="text-center"> 5,221 </td>
	                        <td class="text-center"> 6,666 </td>
	                        <td class="text-center"> 8,656 </td>
	                        <td class="text-center"> 5,343 </td>
	                        <!-- CEO Office -->
	                        <td class="text-center"> - </td> 
	                        <td class="text-center"> - </td>  
	                        <!-- Shared Service -->
	                        <td class="text-center">-</td>  
	                        <td class="text-center">-</td>  
	                        <td class="text-center">-</td>  
	                        <!-- Finance -->
	                        <td class="text-center"> - </td>   
	                        <td class="text-center"> - </td>                          
	                    </tr>
	                    <tr class="">
	                        <td class="text-center">5.</td>
	                        <td>TM Unify - (HQ) (20 Mbps) </td>
	                        <!-- Total -->
	                        <td class="blue bold text-center">503  </td> 
	                        <!-- VC Office -->
	                        <td class="text-center"> 100 </td>
	                        <td class="text-center"> 450 </td>
	                        <td class="text-center"> 40 </td>
	                        <td class="text-center"> 240 </td>
	                        <td class="text-center"> 250 </td>
	                        <td class="text-center"> 222 </td>
	                        <td class="text-center"> 240 </td>
	                        <td class="text-center"> 140 </td>
	                        <td class="text-center"> 40 </td> 
	                        <!-- CEO Office -->
	                        <td class="text-center"> 40 </td> 
	                        <td class="text-center"> 240 </td> 
	                        <!-- Shared Service -->
	                        <td class="text-center">  115  </td>    
	                        <td class="text-center">187</td>  
	                        <td class="text-center">201</td>    
	                        <!-- Finance -->
	                        <td class="text-center"> 3,000 </td>   
	                        <td class="text-center"> 1,800 </td>                      
	                    </tr>
	                    <tr class="info">
	                        <td colspan='10000'>Telephone/PABX System</td>
	                    </tr>
	                    <tr class="">
	                        <td class="text-center">1.</td>
	                        <td>PABX Maintenance Service</td>
	                        <!-- Total -->
	                        <td class="blue bold text-center">4,365</td> 
	                        <!-- VC Office -->
	                        <td class="text-center"> 2,800 </td>
	                        <td class="text-center"> 5,455 </td>
	                        <td class="text-center"> 3,900 </td>
	                        <td class="text-center"> 5,455 </td>
	                        <td class="text-center"> 5,255 </td>
	                        <td class="text-center"> 2,233 </td>
	                        <td class="text-center"> 5,040 </td>
	                        <td class="text-center"> 8,240 </td>
	                        <td class="text-center"> 5,580 </td> 
	                        <!-- CEO Office -->
	                        <td class="text-center"> 840 </td> 
	                        <td class="text-center"> 1,840 </td>  
	                        <!-- Shared Service -->
	                        <td class="text-center">998</td>    
	                        <td class="text-center"> 1,621 </td>  
	                        <td class="text-center"> 1,746 </td> 
	                        <!-- Finance -->
	                        <td class="text-center"> 1,400 </td>   
	                        <td class="text-center"> 800 </td>                         
	                    </tr>
	                    <tr class="">
	                        <td class="text-center">2.</td>
	                        <td>TIME - PRI</td>
	                        <!-- Total -->
	                        <td class="blue bold text-center">12,600</td>
	                        <!-- VC Office -->
	                        <td class="text-center"> 5,600 </td>
	                        <td class="text-center"> 1,889 </td>
	                        <td class="text-center"> 2,443 </td>
	                        <td class="text-center"> 2,423 </td>
	                        <td class="text-center"> 1,990 </td>
	                        <td class="text-center"> 2,355 </td>
	                        <td class="text-center"> 2,545 </td>
	                        <td class="text-center"> 5,444 </td>
	                        <td class="text-center"> 5,500 </td>
	                        <!-- CEO Office -->
	                        <td class="text-center"> 3,840 </td> 
	                        <td class="text-center"> 3,540 </td> 
	                        <!-- Shared Service -->   
	                        <td class="text-center">2,880</td>   
	                        <td class="text-center"> 4,680</td>  
	                        <td class="text-center">5,040 </td> 
	                        <!-- Finance -->
	                        <td class="text-center"> 1,500 </td>   
	                        <td class="text-center"> 1,200 </td>                          
	                    </tr>
	                    <tr class="">
	                        <td class="text-center">3.</td>
	                        <td>TM - 2 DID</td>
	                        <!-- Total -->
	                        <td class="blue bold text-center">420</td> 
	                        <!-- VC Office -->
	                        <td class="text-center"> 1,040 </td>
	                        <td class="text-center"> 2,122 </td>
	                        <td class="text-center"> 3,600 </td>
	                        <td class="text-center"> 4,900 </td>
	                        <td class="text-center"> 8,000 </td>
	                        <td class="text-center"> 2,242 </td>
	                        <td class="text-center"> 4,524 </td>
	                        <td class="text-center"> 2,135 </td>
	                        <td class="text-center"> 5,200 </td>
	                        <!-- CEO Office -->
	                        <td class="text-center"> 40 </td> 
	                        <td class="text-center"> 240 </td>  
	                        <!-- Shared Service -->
	                        <td class="text-center">96</td>       
	                        <td class="text-center">156</td>  
	                        <td class="text-center"> 168 </td>   
	                        <!-- Finance -->
	                        <td class="text-center"> 400 </td>   
	                        <td class="text-center"> 100 </td>                    
	                    </tr>
	                    <tr class="success">
	                        <td colspan="2"><b>Total Amount</td></td>  
	                        <!-- Total -->   
	                        <td class="text-center"> <b>52,888 </b></td>  
	                        <!-- VC Office -->
	                        <td class="text-center"> 15,300 </td>
	                        <td class="text-center"> 2,800 </td>
	                        <td class="text-center"> 5,000 </td>
	                        <td class="text-center"> 5,300 </td>
	                        <td class="text-center"> 8,040 </td>
	                        <td class="text-center"> 5,040 </td>
	                        <td class="text-center"> 10,040 </td>
	                        <td class="text-center"> 12,000 </td>
	                        <td class="text-center"> 10,000 </td>
	                        <!-- CEO Office -->
	                        <td class="text-center"> 11,000 </td> 
	                        <td class="text-center"> 12,000 </td> 
	                        <!-- Shared Service -->
	                        <td class="text-center"><b> 12,089</b> </td> 
	                        <td class="text-center"><b>19,644</b></td>  
	                        <td class="text-center"><b>21,155</b></td> 
	                        <!-- Finance -->
	                        <td class="text-center"> 12,400 </td>   
	                        <td class="text-center"> 14,800 </td>                            
	                    </tr>
	                	</tbody>
	            	</table>
	            </div>
            </div>
        </section>
    </div>
</section>