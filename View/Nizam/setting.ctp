<section class="mems-content">
	<div class="row">
		<div style="position:fixed;margin-left:2%" class="col-lg-2">
            <div class="fb-timeliner">
              <h2 class="recent-highlight">General Setting</h2>
              <ul>
                  <li class="none"><a data-scroll href="#setting-user">User</a></li>
                  <li class="none"><a data-scroll href="#setting-role">Role</a></li>
                  <li class="none"><a data-scroll href="#setting-company">Company</a></li>
                  <li class="none"><a data-scroll href="#setting-group">Group</a></li>
                  <li class="none"><a data-scroll href="#setting-department">Department</a></li>
              </ul>
            </div>
            <div class="fb-timeliner">
              <h2>Budget Setting</h2>
              <ul>
                  <li class="none"><a data-scroll href="#setting-budget">Budget</a></li>
              </ul>
            </div>

            <div class="fb-timeliner">
              <h2>Memo Setting</h2>
              <ul>
                  <li class="none"><a data-scroll href="#setting-memo">Memo</a></li>
              </ul>
            </div>
        </div>

        <div style="margin-left: 22%" class="col-lg-9">
            <div id="setting-user">
                <section id="setting-user" class="panel">
                    <header class="panel-heading">
                        <strong> User Management </strong>
                        <a href="#setting-add-user" data-toggle="modal" class="btn btn-xs btn-round btn-white margin-left"><i class="fa fa-plus"></i> Add New User </a>
                        <br/>
                        <!-- <small class="tiny-text"> Manage users of the system </small> -->
                    </header>
                    <div class="panel-body">
                        <table class="table table-striped dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center col-lg-1"> No. </th>
                                    <th class="text-center col-lg-3"> Staff Name </th>
                                    <th class="text-center col-lg-2"> Staff ID </th>
                                    <th class="text-center col-lg-3"> Department </th>
                                    <th class="text-center col-lg-2"> Role </th>
                                    <th class="text-center col-lg-2"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"> 1 </td>
                                    <td>
                                        Muhammad Nizam
                                    </td>
                                    <td class="text-center">
                                        13826478
                                    </td>
                                    <td class="text-center">
                                        Information Technology
                                    </td>
                                    <td class="text-center">
                                        SuperAdmin
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 2 </td>
                                    <td>
                                        Ms Aisyah Ismail
                                    </td>
                                    <td class="text-center">
                                        0129849
                                    </td>
                                    <td class="text-center">
                                        Computer Science
                                    </td>
                                    <td class="text-center">
                                        HOD
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 3 </td>
                                    <td>
                                        Mdm Cikin 
                                    </td>
                                    <td class="text-center">
                                        245464
                                    </td>
                                    <td class="text-center">
                                        Baby Management
                                    </td>
                                    <td class="text-center">
                                        SuperAdmin
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 4 </td>
                                    <td>
                                        Mr Faridi
                                    </td>
                                    <td class="text-center">
                                        39249
                                    </td>
                                    <td class="text-center">
                                        Information Technology
                                    </td>
                                    <td class="text-center">
                                        Normal Staff
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div id="setting-role">
                <section id="setting-role" class="panel">
                    <header class="panel-heading">
                        <strong> Role Management & Setting </strong>
                        <a href="#setting-add-role" data-toggle="modal" class="btn btn-xs btn-round btn-white margin-left"><i class="fa fa-plus"></i> Add New Role </a>
                    </header>
                    <div class="panel-body">
                        <table class="table table-striped dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center col-lg-1"> No. </th>
                                    <th class="text-center col-lg-9"> Role Name </th>
                                    <th class="text-center col-lg-2"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"> 1 </td>
                                    <td class="text-center">
                                        SuperAdmin
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-eye"></i>','#',array('escape'=>false,'class'=>'btn btn-warning btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'View Role'));
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 2 </td>
                                    <td class="text-center">
                                        Head of Department (HOD)
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-eye"></i>','#',array('escape'=>false,'class'=>'btn btn-warning btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'View Role'));
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 3 </td>
                                    <td class="text-center">
                                        Normal Staff
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-eye"></i>','#',array('escape'=>false,'class'=>'btn btn-warning btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'View Role'));
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 4 </td>
                                    <td class="text-center">
                                        CEO
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-eye"></i>','#',array('escape'=>false,'class'=>'btn btn-warning btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'View Role'));
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div id="setting-company">
                <section id="setting-comapny" class="panel">
                    <header class="panel-heading">
                        <strong> Company Management & Setting</strong>
                        <a href="#setting-add-company" data-toggle="modal" class="btn btn-xs btn-round btn-white margin-left"> Add New Company </a>
                    </header>
                    <div class="panel-body">
                        <table class="table table-striped dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center col-lg-1"> No. </th>
                                    <th class="text-center col-lg-3"> Company Name </th>
                                    <th class="text-center col-lg-2"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"> 1 </td>
                                    <td class="text-center">
                                        UNITAR
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 2 </td>
                                    <td class="text-center">
                                        UNIRAZAK
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 3 </td>
                                    <td class="text-center">
                                        UNIVERSITI
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 4 </td>
                                    <td class="text-center">
                                        UITM
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            
            <div id="setting-group">
                <section id="setting-group" class="panel">
                    <header class="panel-heading">
                        <strong> Group Management & Setting </strong>
                        <a href="#setting-add-group" data-toggle="modal" class="btn btn-xs btn-round btn-white margin-left"> Add New Group </a>
                    </header>
                    <div class="panel-body">
                        <table class="table table-striped dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center col-lg-1"> No. </th>
                                    <th class="text-center col-lg-4"> Company Name </th>
                                    <th class="text-center col-lg-5"> Group Name </th>
                                    <th class="text-center col-lg-2"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"> 1 </td>
                                    <td class="text-center">
                                        UNITAR
                                    </td>
                                    <td class="text-center">
                                        Top Management
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 2 </td>
                                    <td class="text-center">
                                        UNITAR
                                    </td>
                                    <td class="text-center">
                                        Financial
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 3 </td>
                                    <td class="text-center">
                                        UNITAR
                                    </td>
                                    <td class="text-center">
                                        VC Office
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 4 </td>
                                    <td class="text-center">
                                        UNITAR
                                    </td>
                                    <td class="text-center">
                                        Corp Shared Services
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div id="setting-department">
                <section id="setting-department" class="panel">
                    <header class="panel-heading">
                        <strong> Department Management & Setting </strong>
                        <a href="#setting-add-department" data-toggle="modal" class="btn btn-xs btn-round btn-white margin-left"> Add New Department </a>
                    </header>
                    <div class="panel-body">
                        <table class="table table-striped dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center col-lg-1"> No. </th>
                                    <th class="text-center col-lg-2"> Company Name </th>
                                    <th class="text-center col-lg-3"> Group Name </th>
                                    <th class="text-center col-lg-4"> Department Name </th>
                                    <th class="text-center col-lg-2"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"> 1 </td>
                                    <td class="text-center">
                                        UNITAR
                                    </td>
                                    <td class="text-center">
                                        Top Management
                                    </td>
                                    <td class="text-center">
                                        CEO
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 2 </td>
                                    <td class="text-center">
                                        UNITAR
                                    </td>
                                    <td class="text-center">
                                        Top Management
                                    </td>
                                    <td class="text-center">
                                        COO
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 3 </td>
                                    <td class="text-center">
                                        UNITAR
                                    </td>
                                    <td class="text-center">
                                        Top Management
                                    </td>
                                    <td class="text-center">
                                        CFO
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center"> 4 </td>
                                    <td class="text-center">
                                        UNIRAZAK
                                    </td>
                                    <td class="text-center">
                                        Corp Shared Service
                                    </td>
                                    <td class="text-center">
                                        IT Department
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                echo $this->Html->link('<i class="fa fa-pencil"></i>','#',array('escape'=>false,'class'=>'btn btn-info btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Edit'));
                                                echo $this->Html->link('<i class="fa fa-times"></i>','#',array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-placement'=>'bottom','data-toggle'=>'tooltip','data-original-title'=>'Delete'));
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <hr>
            <!-- Budget Setting -->
            <div id="setting-budget">
                <section id="setting-budget" class="panel">
                    <header class="panel-heading">
                        <strong> Budget Setting </strong>
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal tasi-form" method="get">
                            <div class="form-group">
                                <label class="col-lg-5 control-label">
                                    <span class="bold">Max no.of days for budget response</span><br/>
                                    <small> Days after this will be considered as delay </small>
                                </label>
                                <div class="col-lg-7">
                                    <div class="input-group width-150px">
                                        <input type="text" class="form-control text-center bold">
                                        <span class="input-group-addon">days</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>

            <hr>
            <!-- Memo Setting -->
            <div id="setting-memo">
                <section id="setting-memo" class="panel">
                    <header class="panel-heading">
                        <strong> Memo Setting </strong>
                    </header>
                    <div class="panel-body">
                        <form class="form-horizontal tasi-form" method="get">
                            <div class="form-group">
                                <label class="col-lg-5 control-label">
                                    <span class="bold">Max no.of days for memo response</span><br/>
                                    <small> Days after this will be considered as delay </small>
                                </label>
                                <div class="col-lg-7">
                                    <div class="input-group width-150px">
                                        <input type="text" class="form-control text-center bold">
                                        <span class="input-group-addon">days</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>

<!-- Modal Add User -->
<div class="modal fade" id="setting-add-user" role="dialog" aria-labelledby="Setting : Add User" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Add User</h4>
                <small> Add user to the system </small>
            </div>
            <?php
                echo $this->Form->create('User',array('url'=>array('controller'=>'','action'=>''),
                                        'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
                                        'class'=>'form-horizontal','type'=>'file'));
            ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Staff ID</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('User.staff_id',array('type'=>'text','class'=>'form-control','placeholder'=>'Staff ID'));
                            ?>
                            <span class="help-block small">The staff information will be retrieved automatically by the staff ID</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Roles</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                $companies = array("CEO","Super Approver","Requestor");
                                echo $this->Form->input('User.company',array('type'=>'select','options'=>$companies,'class'=>'min-200px select2','placeholder'=>'Company'));
                            ?>
                        </div>
                    </div>
                    <hr>
                    <h4> Staff Details </h4>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                $companies = array("UNITAR","UNIRAZAK","UNIVERSITY");
                                echo $this->Form->input('User.company',array('type'=>'select','options'=>$companies,'class'=>'min-200px select2','placeholder'=>'Company'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Group</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                $groups = array("Top Management","Corp Shared Service","Finance");
                                echo $this->Form->input('User.group',array('type'=>'select','options'=>$groups,'class'=>'min-200px select2','placeholder'=>'Group'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Department</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                $departments = array("IT Department","Finance Department","Hello Department","Management Department");
                                echo $this->Form->input('User.department',array('type'=>'select','options'=>$departments,'class'=>'min-200px select2','placeholder'=>'Group'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">HOD?</label>
                        <div class="col-lg-9 col-sm-9">
                            <div class="radios">
                                <label class="label_radio" for="radio-01">
                                    <input name="sample-radio" id="radio-01" value="1" type="radio"  /> Yes, staff is head of department
                                </label>
                                <label class="label_radio" for="radio-02">
                                    <input name="sample-radio" id="radio-02" value="1" type="radio" checked /> No, staff is not head of department
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <!-- <h4> Staff Extra Details </h4> -->
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Extra Detail</label>
                        <div class="col-lg-9 col-sm-9">
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox" id="checkbox-01" value="1" type="checkbox"  /> Requestor
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox" id="checkbox-01" value="1" type="checkbox"  /> Reviewer
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox" id="checkbox-01" value="1" type="checkbox"  /> Approver
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox" id="checkbox-01" value="1" type="checkbox"  /> PMO
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox" id="checkbox-01" value="1" type="checkbox"  /> ICT
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-success" type="button">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- modal add user-->

<!-- Modal Add Role -->
<div class="modal fade" id="setting-add-role" role="dialog" aria-labelledby="Setting : Add Role" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Add Role</h4>
                <small> Add roles to be assigned to users of the system </small>
            </div>
            <?php
                echo $this->Form->create('Role',array('url'=>array('controller'=>'','action'=>''),
                                        'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
                                        'class'=>'form-horizontal','type'=>'file'));
            ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Role Name </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('Role.name',array('type'=>'text','class'=>'form-control','placeholder'=>'Insert role name'));
                            ?>
                            <span class="help-block small">Describe and differentiate role with unique name</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Role Description </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('Role.description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'A little description'));
                            ?>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Permission</label>
                        <div class="col-lg-9 col-sm-9">
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-01" id="checkbox-01" value="1" type="checkbox" checked disabled="disabled" />
                                    Dashboard
                                </label>
                            </div>
                            <h5>Budget</h5>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-01" id="checkbox-01" value="1" type="checkbox" />
                                    Create Request
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-02" id="checkbox-01" value="1" type="checkbox" />
                                    My Request
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-03" id="checkbox-01" value="1" type="checkbox" />
                                    All Request
                                </label>
                            </div>
                           <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-04" id="checkbox-01" value="1" type="checkbox" />
                                    Request Management
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-05" id="checkbox-01" value="1" type="checkbox" />
                                    Budget Archive
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-06" id="checkbox-01" value="1" type="checkbox" />
                                    Master Cost List
                                </label>
                            </div>

                            <h5>Financial Memo</h5>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-07" id="checkbox-01" value="1" type="checkbox" />
                                    Create Request
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-08" id="checkbox-01" value="1" type="checkbox" />
                                    My Request
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-09" id="checkbox-01" value="1" type="checkbox" />
                                    All Request
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-10" id="checkbox-01" value="1" type="checkbox" />
                                    Request Management
                                </label>
                            </div>

                            <h5> Non-Financial Memo </h5>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-11" id="checkbox-01" value="1" type="checkbox" />
                                    Create Request
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-12" id="checkbox-01" value="1" type="checkbox" />
                                    My Request
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-13" id="checkbox-01" value="1" type="checkbox" />
                                    All Request
                                </label>
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-14" id="checkbox-01" value="1" type="checkbox" />
                                    Request Management
                                </label>
                            </div>

                            <h5> Setting </h5>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <input name="sample-checkbox-15" id="checkbox-01" value="1" type="checkbox" />
                                    Settings
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-success" type="button">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- modal add role-->

<!-- Modal Add Comapnies -->
<div class="modal fade" id="setting-add-company" role="dialog" aria-labelledby="Setting : Add Company" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Add Company</h4>
                <small> Add companies to the system </small>
            </div>
            <?php
                echo $this->Form->create('Company',array('url'=>array('controller'=>'','action'=>''),
                                        'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
                                        'class'=>'form-horizontal','type'=>'file'));
            ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company Name </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('Company.name',array('type'=>'text','class'=>'form-control','placeholder'=>'Insert company name'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Year Established</label>
                        <div class="col-lg-9 col-sm-9">   
                        <?php
                                    echo $this->Form->input('Company.year',array('type'=>'text','class'=>'form-control','placeholder'=>'Year established'));
                                ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Description</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('Company.description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'A little detail about the company'));
                            ?>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-success" type="button">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- modal add comapnis-->


<!-- Modal Add Group -->
<div class="modal fade" id="setting-add-group" role="dialog" aria-labelledby="Setting : Add Group" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Add Group</h4>
                <small> Add group to the system </small>
            </div>
            <?php
                echo $this->Form->create('Group',array('url'=>array('controller'=>'','action'=>''),
                                        'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
                                        'class'=>'form-horizontal','type'=>'file'));
            ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Group Name </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                $companies = array("UNITAR","UNIRAZAK","UITM","UIAM","UTAR","KTAR");
                                echo $this->Form->input('Group.company',array('type'=>'select','options'=>$companies,'class'=>'select2 full-width'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Group Name </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('Group.name',array('type'=>'text','class'=>'form-control','placeholder'=>'Insert company name'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Description</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('Group.description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'A little detail about the group'));
                            ?>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-success" type="button">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- modal add group-->

<!-- Modal Add Department -->
<div class="modal fade" id="setting-add-department" role="dialog" aria-labelledby="Setting : Add Department" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Add Department</h4>
                <small> Add department to the system </small>
            </div>
            <?php
                echo $this->Form->create('Department',array('url'=>array('controller'=>'','action'=>''),
                                        'inputDefaults'=>array('label'=>false,'div'=>false,'legend'=>false),
                                        'class'=>'form-horizontal','type'=>'file'));
            ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                $companies = array("UNITAR","UNIRAZAK","UITM","UIAM","UTAR","KTAR");
                                echo $this->Form->input('Department.company',array('type'=>'select','options'=>$companies,'class'=>'select2 full-width'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                $group = array("Top Management","Corp Shared Service"," VC Office");
                                echo $this->Form->input('Deprtment.group',array('type'=>'select','options'=>$group,'class'=>'select2 full-width','empty'=>'-- Please select company first --'));
                                echo "<small> The group will be displayed according to selected company </small>";
                            ?>
                        </div>
                    </div>
                    <hr>
                    <h4> Department Detail </h4>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Department Name </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('Department.name',array('type'=>'text','class'=>'form-control','placeholder'=>'Insert department name'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">No.of staff </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('Department.no_staff',array('type'=>'text','class'=>'form-control','placeholder'=>'Insert no.of staff'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Description</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('Department.description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'A little detail about the group'));
                            ?>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                <button class="btn btn-success" type="button">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- modal add department-->
