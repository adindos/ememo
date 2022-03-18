<?php
    $this->Html->addCrumb('Setting', $this->here,array('class'=>'active'));
?>

<section class="mems-content">
    <div class="row">
        <div style="position:fixed;margin-left:2%" class="col-lg-2">
            <div class="fb-timeliner">
              <h2>General Settings</h2>
              <ul>
                  <li class="none"><a data-scroll href="#setting-user">User</a></li>
                  <li class="none"><a data-scroll href="#setting-role">Role</a></li>
                  <li class="none"><a data-scroll href="#setting-company">Company</a></li>
                  <li class="none"><a data-scroll href="#setting-group">Division</a></li>
                  <li class="none"><a data-scroll href="#setting-department">Department</a></li>
              </ul>
            </div>
            <div class="fb-timeliner">
              <h2>Budget & Memo Settings</h2>
              <ul>
                  <li class="none"><a data-scroll href="#setting-items">Items</a></li>
                  <!-- <li class="none"><a data-scroll href="#setting-category">Categories</a></li> -->
                  <li class="none"><a data-scroll href="#setting-budget">Response Time</a></li>
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
                                    <th class="text-center col-lg-2"> Status </th>
                                    <th class="text-center col-lg-2"> Action </th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php 
                                $counter = 0;
                                    foreach ($allusers as $key => $user) {

                                 ?>
                                <tr>
                                    <td class="text-center"> 
                                        <?php echo ++$counter; ?> 
                                    </td>
                                    <td class="text-center">
                                        <?php echo $user['User']['staff_name'] ?>                   
                                    </td>
                                    <td class="text-center">
                                        <?php echo $user['User']['staff_id'] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $user['Department']['department_name'] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $user['Role']['role_name'] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $user['User']['status'] ?>
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php

                                                echo "<a href='#setting-edit-user' data-toggle='modal' class='btn btn-info btn-xs edit-user-btn tooltips' data-placement='bottom', data-toggle = 'tooltips', data-original-title='Edit' 
                                                    data-user-id='".$user['User']['user_id']."' 
                                                    data-staff-name='".$user['User']['staff_name']."'
                                                    data-staff-id='".$user['User']['staff_id']."' 
                                                    data-email = '".$user['User']['email_address']."' 
                                                    data-role-id='".$user['User']['role_id']."'   
                                                    data-company-id='".$user['Department']['Group']['Company']['company_id']."'
                                                    data-group-id='".$user['Department']['Group']['group_id']."'
                                                    data-department-id='".$user['User']['department_id']."'
                                                    data-designation='".$user['User']['designation']."'
                                                    data-loa='".$user['User']['loa']."'
                                                    data-hod='".$user['User']['hod']."'
                                                    data-pmo='".$user['User']['pmo']."'
                                                    data-ict='".$user['User']['ict']."'
                                                    data-finance='".$user['User']['finance']."'
                                                    data-requestor='".$user['User']['requestor']."'
                                                    data-reviewer='".$user['User']['reviewer']."'
                                                    data-approver='".$user['User']['approver']."'
                                                    data-recommender='".$user['User']['recommender']."'
                                                    > <i class='fa fa-pencil'></i></a>";


                                                    if($user['User']['status'] == "enabled"){

                                                         echo $this->Form->postlink('<i class="fa fa-eye-slash"></i>',array('controller'=>'setting','action'=>'disableUser',$user['User']['user_id']),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title="Disable User"'),"Are you sure you want to disable this user?");

                                                    }else {

                                                        echo $this->Form->postlink('<i class="fa fa-eye"></i>',array('controller'=>'setting','action'=>'enableUser',$user['User']['user_id']),array('escape'=>false,'class'=>'btn btn-success btn-xs tooltips','data-original-title="Enable User"'),"Are you sure you want to enable this user?");

                                                    }


                                                
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div id="setting-role">
                <section id="setting-role" class="panel">
                    <header class="panel-heading">
                        <strong> Role Management </strong>
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
                                <?php

                                $counter = 0; 
                                foreach ($allroles as $key => $role) {
                                
                                ?>
                                <tr>
                                    <td class="text-center"> <?php echo ++$counter; ?> </td>
                                    <td class="text-center">
                                        <?php echo $role['Role']['role_name'] ?>
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php

                                       
                                                echo "<a href='#setting-view-role' data-toggle='modal' class='btn btn-warning btn-xs view-role-btn tooltips' data-placement='bottom', data-toggle = 'tooltips', data-original-title='View Role' 
                                                    data-role-id='".$role['Role']['role_id']."' 
                                                    data-role-name='".$role['Role']['role_name']."' 
                                                    data-description = '".$role['Role']['description']."' 
                                                    data-dashboard='".$role['Role']['dashboard']."'   
                                                     data-all-request-budget='".$role['Role']['all_request_budget']."'
                                                    data-my-request-budget='".$role['Role']['my_request_budget']."'
                                                    data-request-management-budget='".$role['Role']['request_management_budget']."'
                                                    data-budget-archive='".$role['Role']['budget_archive']."'
                                                    data-report-budget='".$role['Role']['report_budget']."'
                                                     data-all-request-memo='".$role['Role']['all_request_memo']."'
                                                    data-my-request-memo='".$role['Role']['my_request_memo']."'
                                                    data-request-management-memo='".$role['Role']['request_management_memo']."'
                                                    data-my-memo-memo='".$role['Role']['my_memo_memo']."'
                                                    data-report-memo='".$role['Role']['report_memo']."'
                                                     data-my-request-financial-memo='".$role['Role']['my_request_financial_memo']."'
                                                    data-all-request-financial-memo='".$role['Role']['all_request_financial_memo']."'
                                                    data-request-management-financial-memo='".$role['Role']['request_management_financial_memo']."'
                                                    data-my-memo-financial-memo='".$role['Role']['my_memo_financial_memo']."'
                                                    data-report-financial-memo='".$role['Role']['report_financial_memo']."'
                                                    data-settings='".$role['Role']['settings']."' > 
                                                    <i class='fa fa-book'></i></a>";


                                                    ?> 




                                                <?php  

                                                if($role['Role']['role_name'] != "Superadmin"){

                                                echo "<a href='#setting-edit-role' data-toggle='modal' class='btn btn-info btn-xs edit-role-btn tooltips' data-placement='bottom', data-toggle = 'tooltips', data-original-title='Edit' 
                                                    data-role-id='".$role['Role']['role_id']."' 
                                                    data-role-name='".$role['Role']['role_name']."' 
                                                    data-description = '".$role['Role']['description']."' 
                                                    data-dashboard='".$role['Role']['dashboard']."'   
                                                     data-all-request-budget='".$role['Role']['all_request_budget']."'
                                                    data-my-request-budget='".$role['Role']['my_request_budget']."'
                                                    data-request-management-budget='".$role['Role']['request_management_budget']."'
                                                    data-budget-archive='".$role['Role']['budget_archive']."'
                                                    data-report-budget='".$role['Role']['report_budget']."'
                                                     data-all-request-memo='".$role['Role']['all_request_memo']."'
                                                    data-my-request-memo='".$role['Role']['my_request_memo']."'
                                                    data-request-management-memo='".$role['Role']['request_management_memo']."'
                                                    data-my-memo-memo='".$role['Role']['my_memo_memo']."'
                                                    data-report-memo='".$role['Role']['report_memo']."'
                                                     data-my-request-financial-memo='".$role['Role']['my_request_financial_memo']."'
                                                    data-all-request-financial-memo='".$role['Role']['all_request_financial_memo']."'
                                                    data-request-management-financial-memo='".$role['Role']['request_management_financial_memo']."'
                                                    data-my-memo-financial-memo='".$role['Role']['my_memo_financial_memo']."'
                                                   data-report-financial-memo='".$role['Role']['report_financial_memo']."'
                                                    data-settings='".$role['Role']['settings']."' > 
                                                    <i class='fa fa-pencil'></i></a>";



                                                echo $this->Form->postlink('<i class="fa fa-times"></i>',array('controller'=>'setting','action'=>'deleteRole',$role['Role']['role_id']),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title="Delete Role"'),"Are you sure you want to delete the role?");


                                                }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div id="setting-company">
                <section id="setting-comapny" class="panel">
                    <header class="panel-heading">
                        <strong> Company Management </strong>
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

                                <?php 
                                    $counter = 0;
                                    foreach ($allcompanies as $key => $company) {
                                ?>

                                <tr>
                                    <td class="text-center"> <?php echo(++$counter); ?> </td>
                                    <td class="text-center">
                                        <?php echo($company['Company']['company']) ?>
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                             
                                                 echo "<a href='#setting-edit-company' data-toggle='modal' class='btn btn-info btn-xs edit-company-btn tooltips' data-placement='bottom', data-toggle = 'tooltips', data-original-title='Edit' data-company-id='".$company['Company']['company_id']."' data-company-name='".$company['Company']['company']."' data-year='".$company ['Company']['year_established']."' data-description = '".$company['Company']['description']."'> <i class='fa fa-pencil'></i>  </a>";


                                                 echo $this->Form->postlink('<i class="fa fa-times"></i>',array('controller'=>'setting','action'=>'deleteCompany',$company['Company']['company_id']),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title="Delete Company"'),"Are you sure you want to delete the company?");

                                            ?>


                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            
            <div id="setting-group">
                <section id="setting-group" class="panel">
                    <header class="panel-heading">
                        <strong> Division Management </strong>
                        <a href="#setting-add-group" data-toggle="modal" class="btn btn-xs btn-round btn-white margin-left"> Add New Division </a>
                    </header>
                    <div class="panel-body">

                        <table class="table table-striped dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center col-lg-1"> No. </th>
                                    <th class="text-center col-lg-4"> Company Name </th>
                                    <th class="text-center col-lg-5"> Division Name </th>
                                    <th class="text-center col-lg-2"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $counter = 0; 
                                foreach ($allgroups as $key => $group) {
                                
                                ?>
                                <tr>
                                    <td class="text-center"> <?php echo ++$counter; ?> </td>
                                    <td class="text-center">
                                        <?php echo $group['Company']['company'] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $group['Group']['group_name'] ?>
                                    </td>
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                
                                            echo "<a href='#setting-edit-group' data-toggle='modal' class='btn btn-info btn-xs edit-group-btn tooltips' data-placement='bottom', data-toggle = 'tooltips', data-original-title='Edit' data-group-id='".$group['Group']['group_id']."' data-company-id='".$group['Company']['company_id']."' data-group-name='".$group['Group']['group_name']."' data-description = '".$group['Group']['description']."'  > <i class='fa fa-pencil'></i></a>";



                                            echo $this->Form->postlink('<i class="fa fa-times"></i>',array('controller'=>'setting','action'=>'deleteGroup',$group['Group']['group_id']),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title="Delete Role"'),"Are you sure you want to delete the group?");
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <div id="setting-department">
                <section id="setting-department" class="panel">
                    <header class="panel-heading">
                        <strong> Department Management </strong>
                        <a href="#setting-add-department" data-toggle="modal" class="btn btn-xs add-department-btn btn-round btn-white margin-left"> Add New Department </a>
                    </header>
                    <div class="panel-body">
                        <table class="table table-striped dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center col-lg-1"> No. </th>
                                    <th class="text-center col-lg-4"> Department Name </th>
                                    <th class="text-center col-lg-3"> Department Type </th>
                                    <th class="text-center col-lg-2"> Company Name </th>
                                    <th class="text-center col-lg-3"> Division Name </th>
                                    <th class="text-center col-lg-2"> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $counter = 0; 
                                foreach ($alldepartments as $key => $department) {
                                
                                ?>
                                <tr>
                                    <td class="text-center"> <?php echo ++$counter ?> </td>

                                    <td class="text-center">
                                        <?php echo $department['Department']['code_name']; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo ($department['Department']['department_type']==1)? 'Academic' :(($department['Department']['department_type']==2) ? 'Non-Academic' :'');  ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $department['Group']['Company']['company'] ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $department['Group']['group_name'] ?>
                                    </td>
                                    
                                    <td class="text-center"> 
                                        <div class="btn-group btn-group-xs">
                                            <?php
                                                
                                                echo "<a href='#setting-edit-department' data-toggle='modal' class='btn btn-info btn-xs edit-department-btn tooltips' data-placement='bottom', data-toggle = 'tooltips', data-original-title='Edit' data-department-id='".$department['Department']['department_id']."' data-group-id='".$department['Group']['group_id']."' data-company-id='".$department['Group']['Company']['company_id']."' data-department-name='".$department['Department']['department_name']."' data-department-shortform='".$department['Department']['department_shortform']."' data-department-type='".$department['Department']['department_type']."' data-description = '".$department['Department']['description']."' data-total-staff = '".$department['Department']['total_staff']."'  > <i class='fa fa-pencil'></i></a>";

                                                
                                                echo $this->Form->postlink('<i class="fa fa-times"></i>',array('controller'=>'setting','action'=>'deleteDepartment',$department['Department']['department_id']),array('escape'=>false,'class'=>'btn btn-danger btn-xs tooltips','data-original-title="Delete Department"'),"Are you sure you want to delete the department?");
                                            ?>
                                        </div>
                                    </td>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>


            <?php
                
                $model = 'Setting';
                $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'editReviewSettings'));

                echo $this->Form->create($model, $options);

            ?>

            <hr>

            <!-- items settings -->
            <div id="setting-items">
                <section id="setting-items" class="panel">
                    <header class="panel-heading">
                        <strong> Item Management </strong>
                        <a href="#setting-add-item" data-toggle="modal" class="btn btn-xs btn-round btn-white margin-left"> Add New Item </a>
                    </header>
                    <div class="panel-body">
                        <table class="table table-striped dataTable">
                            <thead>
                                <tr>
                                    <th class="text-center col-lg-1"> No. </th>
                                    <th class="text-center col-lg-2"> Item </th>
                                    <!-- <th class="text-center col-lg-2"> Item Category (If any)</th> -->
                                 </tr>
                            </thead>
                            <tbody>
                                <?php

                                $counter = 0; 
                                foreach ($allitems as $key => $item) {
                                
                                ?>
                                <tr>
                                    <td class="text-center"> <?php echo ++$counter ?> </td>
                                    <td class="text-center">
                                        <?php echo $item['Item']['code_item'] ?>
                                    </td>
                                    <!-- <td class="text-center">
                                        <?php echo $item['Item']['parent_item_id'] ?>
                                        
                                    </td> -->

                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>


            <!-- Response Time Setting -->
            <div id="setting-budget">
                <section id="setting-budget" class="panel">
                    <header class="panel-heading">
                        <strong> Response Time Settings </strong>
                    </header>
                    <div class="panel-body">
                          <div class="form-group">
                                <label class="col-lg-5 control-label">
                                    <span class="bold">Max no. of days for budget response (*)</span><br/>
                                    <small> Days after this will be considered as delay </small>
                                </label>
                                <div class="col-lg-7">
                                    <div class="input-group width-150px">
                                        <?php
                                            echo $this->Form->input('max_review_day_budget',array('type'=>'number','min' => '0','max' =>'9999','class'=>'form-control text-center bold','placeholder'=>'','required','value' => $reviewsettings['Setting']['max_review_day_budget']));
                                        ?>
                                        <span class="input-group-addon">days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-5 control-label">
                                    <span class="bold">Max no. of days for memo response</span><br/>
                                    <small> Days after this will be considered as delay </small>
                                </label>
                                <div class="col-lg-7">
                                    <div class="input-group width-150px">
                                        <?php
                                            echo $this->Form->input('max_review_day_memo',array('type'=>'number','min' => '0','max' =>'9999','class'=>'form-control text-center bold','placeholder'=>'','required','value' => $reviewsettings['Setting']['max_review_day_memo']));
                                        ?>
                                        <span class="input-group-addon">days</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">

                                <?php 

                                echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                                echo $this->Form->end();
                            
                                ?>
                            </div>
                        </div>
                </section>
            </div>
            <hr>
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

                $model = 'User';
                $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'addUser'));

                echo $this->Form->create($model, $options);
             
            ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Staff Username(*)</label>
                        <div class="col-lg-6 col-sm-6">
                            <?php

                                echo $this->Form->input('staff_id',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert staff ID','required', 'id' => 'add-user-id'));
                            ?>

                            <span class="help-block small">The staff information will be retrieved automatically by the staff ID</span>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <?php echo $this->Form->button('Search', $options = array('class' => 'btn btn-primary', 'id' => 'add-user-search','type' => 'button'));  ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Staff Display Name (*)</label>
                        <div class="col-lg-6 col-sm-6">
                            <?php

                                echo $this->Form->input('staff_name',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert staff ID','required', 'id' => 'add-staffname','readonly'));
                            ?>

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Email ID (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php

                                echo $this->Form->input('email_address',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert email ID','required', 'id' => 'add-user-email', 'readonly'));
                                
                            ?>
                            <span class="help-block small">To be removed later when AD is used</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Roles (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                           
                                $roles_list = array();
                                foreach ($allroles as $key => $role) {
                                    $roles_list[$role['Role']['role_id']] = $role['Role']['role_name'];
                                }

                                echo $this->Form->input('role_id',array('type'=>'select','options'=>$roles_list, 'empty'=>'','class'=>'select full-width','required'));
                            ?>
                            
                        </div>
                    </div>
                    <hr>
                    <h4> Staff Details </h4>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company(*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                            
                                $company_list = array();
                                foreach ($allcompanies as $key => $company) {
                                    $company_list[$company['Company']['company_id']] = $company['Company']['company'];
                                }

                                echo $this->Form->input('company_id',array('type'=>'select','options'=>$company_list,'empty'=>'','class'=>'select full-width','required','id'=>'add-usercompany-id'));
                            ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Group (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                            
                                echo $this->Form->input('group_id',array('type'=>'select','class'=>'select full-width','empty'=>'','required','id' => 'add-usergroup-id'));
                            ?>                   
                         </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Department (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                $department_list = array();
                                foreach ($alldepartments as $key => $department) {
                                    $department_list[$department['Department']['department_id']] = $department['Department']['department_name'];
                                }

                                echo $this->Form->input('department_id',array('type'=>'select','class'=>'select full-width', 'required','id' => 'add-userdepartment-id', 'empty'=>''));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Designation (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php

                                echo $this->Form->input('designation',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert designation','required'));
                                
                            ?>
                            <span class="help-block small">The staff information will be retrieved automatically by the staff ID</span>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">LOA value (RM)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php

                                echo $this->Form->input('loa',array('type'=>'number', 'min' => '0','class'=>'form-control','placeholder'=>'Insert LOA Value', 'value' => '0'));
                                
                            ?>
                            <span class="help-block small">Insert 0 if LOA is not applicable</span>
                        </div>
                    </div>
                    
                    <hr>
                    <!-- <h4> Staff Extra Details </h4> -->
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Extra Details</label>
                        <div class="col-lg-9 col-sm-9">
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('hod',array('type'=>'checkbox','after' => ' HOD'));
                                    ?>
                                </label>
                            </div>

                            <!-- <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                      //  echo $this->Form->input('pmo',array('type'=>'checkbox','after' => ' PMO'));
                                    ?>
                                </label>
                            </div> -->
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('ict',array('type'=>'checkbox','after' => ' ICT'));
                                    ?>
                                </label>
                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('finance',array('type'=>'checkbox','after' => ' Finance'));
                                    ?>
                                </label>
                            </div>

                            <div class="checkboxes">
                                     <label class="label_check" for="checkbox-01">
                                        <?php
                                            echo $this->Form->input('requestor',array('type'=>'checkbox','after' => ' Requestor'));
                                        ?>
                                    </label>
                                
                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('reviewer',array('type'=>'checkbox','after' => ' Reviewer'));
                                    ?>
                                </label>
                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('approver',array('type'=>'checkbox','after' => ' Approver'));
                                    ?>
                                </label>
                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('recommender',array('type'=>'checkbox','after' => ' Recommender'));
                                    ?>
                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 col-sm-3 control-label"> <?php  echo "<small> (*) Denotes a required field </small>";  ?></label>
                    </div>

                </div>

                   <div class="modal-footer">
                       <?php 

                       echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                       echo $this->Form->end();
                       echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

                       ?>
               
               </div>    
            
            </div>
    </div>
</div>
<!-- modal add user-->


<!-- Modal edit User -->
<div class="modal fade" id="setting-edit-user" role="dialog" aria-labelledby="Setting : Add User" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Add User</h4>
                <small> Add user to the system </small>
            </div>
            <?php

                $model = 'User';
                $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'editUser'));

                echo $this->Form->create($model, $options);
                echo $this->Form->hidden('user_id', array('id' => 'edit-user-id'));
             
            ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Staff Username (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php

                                echo $this->Form->input('staff_id',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert staff ID','required', 'id' => 'edit-staff-id','readonly'));
                                
                            ?>
                            <span class="help-block small">The staff information will be retrieved automatically by the staff ID</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Staff Display Name (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php

                                echo $this->Form->input('staff_name',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert staff Name','required', 'id' => 'edit-staff-name','readonly'));
                                
                            ?>
                            <span class="help-block small">The staff information will be retrieved automatically by the staff ID</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Email ID (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php

                                echo $this->Form->input('email_address',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert email ID','required','id' => 'edit-email','readonly'));
                                
                            ?>
                            <span class="help-block small">To be removed later when AD is used</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Roles (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                           
                                $roles_list = array();
                                foreach ($allroles as $key => $role) {
                                    $roles_list[$role['Role']['role_id']] = $role['Role']['role_name'];
                                }

                                echo $this->Form->input('role_id',array('type'=>'select','options'=>$roles_list,'class'=>'select full-width','required', 'id'=>'edit-userrole-id',  'empty'=>''));
                            ?>
                            
                        </div>
                    </div>
                    <hr>
                    <h4> Staff Details </h4>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                            
                                $company_list = array();
                                foreach ($allcompanies as $key => $company) {
                                    $company_list[$company['Company']['company_id']] = $company['Company']['company'];
                                }

                                echo $this->Form->input('company_id',array('type'=>'select','options'=>$company_list,'class'=>'select full-width','required','id' => 'edit-usercompany-id',  'empty'=>''));
                            ?>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Group (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                            
                                $groups_list = array();
                                foreach ($allgroups as $key => $group) {
                                    $group_list[$group['Group']['group_id']] = $group['Group']['group_name'];
                                }

                                echo $this->Form->input('group_id',array('type'=>'select','options'=>$group_list,'class'=>'select full-width','required', 'id' => 'edit-usergroup-id',  'empty'=>''));
                            ?>                   
                         </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Department (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                $department_list = array();
                                foreach ($alldepartments as $key => $department) {
                                    $department_list[$department['Department']['department_id']] = $department['Department']['department_name'];
                                }

                                echo $this->Form->input('department_id',array('type'=>'select','options'=>$department_list,'class'=>'select full-width','required', 'id' => 'edit-userdepartment-id',  'empty'=>''));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Designation (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php

                                echo $this->Form->input('designation',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert designation','required', 'id' => 'edit-designation'));
                                
                            ?>
                            <span class="help-block small">The staff information will be retrieved automatically by the staff ID</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">LOA value (RM) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php

                                echo $this->Form->input('loa',array('type'=>'number', 'min' => '0','class'=>'form-control','placeholder'=>'Insert LOA Value', 'id' => 'edit-loa'));
                                
                            ?>
                            <span class="help-block small">Insert 0 if LOA is not applicable</span>
                        </div>
                    </div>
                    
                    <hr>
                    <!-- <h4> Staff Extra Details </h4> -->
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Extra Details</label>
                        <div class="col-lg-9 col-sm-9">
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('hod',array('type'=>'checkbox','after' => ' HOD', 'id' => 'edit-hod'));
                                    ?>
                                </label>
                            </div>

                            <div class="checkboxes">
                                 <!-- <label class="label_check" for="checkbox-01">
                                    <?php
                                      //  echo $this->Form->input('pmo',array('type'=>'checkbox','after' => ' PMO', 'id' => 'edit-pmo'));
                                    ?>
                                </label> -->
                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('ict',array('type'=>'checkbox','after' => ' ICT', 'id' => 'edit-ict'));
                                    ?>
                                </label>
                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('finance',array('type'=>'checkbox','after' => ' Finance', 'id' => 'edit-finance'));
                                    ?>
                                </label>
                            </div>

                            <div class="checkboxes">
                                     <label class="label_check" for="checkbox-01">
                                        <?php
                                            echo $this->Form->input('requestor',array('type'=>'checkbox','after' => ' Requestor', 'id'=>'edit-requestor'));
                                        ?>
                                    </label>
                                
                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('reviewer',array('type'=>'checkbox','after' => ' Reviewer', 'id' => 'edit-reviewer'));
                                    ?>
                                </label>
                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('approver',array('type'=>'checkbox','after' => ' Approver','id' => 'edit-approver'));
                                    ?>
                                </label>
                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('recommender',array('type'=>'checkbox','after' => ' Recommender', 'id' => 'edit-recommender'));
                                    ?>
                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 col-sm-3 control-label"> <?php  echo "<small> (*) Denotes a required field </small>";  ?></label>
                    </div>

                </div>

                   <div class="modal-footer">
                       <?php 

                       echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                       echo $this->Form->end();
                       echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

                       ?>
               
               </div>    
            
            </div>
    </div>
</div>
<!-- modal edit user-->

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

              $model = 'Role';
              $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'addRole'));

              echo $this->Form->create($model, $options);
              
              ?>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Role Name (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('role_name',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert role name','required'));
                            ?>
                            <span class="help-block small">Describe and differentiate role with unique name</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Role Description </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'Details about the company'));
                                echo "<small> (*) Denotes a required field </small>";

                            ?>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Permission</label>
                        <div class="col-lg-9 col-sm-9">

                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('dashboard',array('type'=>'checkbox','after' => ' Dashboard'));
                                    ?>
                                </label>

                            </div>
                            <h5>Budget</h5>
                            <!-- <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        //echo $this->Form->input('create_request_budget',array('type'=>'checkbox','after' => ' Create Request'));
                                    ?>
                                </label>

                            </div> -->
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_request_budget',array('type'=>'checkbox','after' => ' My Requests'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('all_request_budget',array('type'=>'checkbox','after' => ' Budget List'));
                                    ?>
                                </label>

                            </div>
                           <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('request_management_budget',array('type'=>'checkbox','after' => ' Request Management'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('budget_archive',array('type'=>'checkbox','after' => ' Budget Archive'));
                                    ?>
                                </label>

                            </div>
                           <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('report_budget',array('type'=>'checkbox','after' => ' Budget Report'));
                                    ?>
                                </label>

                            </div>

                            <h5>Non-Financial Memo</h5>
                            <!-- <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        //echo $this->Form->input('create_request_memo',array('type'=>'checkbox','after' => ' Create Request'));
                                    ?>
                                </label>

                            </div> -->
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_request_memo',array('type'=>'checkbox','after' => ' My Requests'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('all_request_memo',array('type'=>'checkbox','after' => ' All Requests'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('request_management_memo',array('type'=>'checkbox','after' => ' Request Management'));
                                    ?>
                                </label>

                            </div>

                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_memo_memo',array('type'=>'checkbox','after' => ' My Memo'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('report_memo',array('type'=>'checkbox','after' => ' Memo Report'));
                                    ?>
                                </label>

                            </div>

                            <h5> Financial Memo </h5>
                            <!-- <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        //echo $this->Form->input('create_request_financial_memo',array('type'=>'checkbox','after' => ' Create Request'));
                                    ?>
                                </label>

                            </div> -->
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_request_financial_memo',array('type'=>'checkbox','after' => ' My Requests'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('all_request_financial_memo',array('type'=>'checkbox','after' => ' All Requests'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('request_management_financial_memo',array('type'=>'checkbox','after' => ' Request Management'));
                                    ?>
                                </label>

                            </div>

                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_memo_financial_memo',array('type'=>'checkbox','after' => ' My Memo'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('report_financial_memo',array('type'=>'checkbox','after' => ' Memo Report'));
                                    ?>
                                </label>

                            </div>

                            <h5> Settings </h5>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('settings',array('type'=>'checkbox','after' => ' Settings'));
                                    ?>

                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 col-sm-3 control-label"> <?php  echo "<small> (*) Denotes a required field </small>";  ?></label>
                    </div>

                </div>

               
               
                 <div class="modal-footer">
                 <?php 

                 echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                 echo $this->Form->end();
                 echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

                 ?>
             
             </div>    
         </div>
    </div>
</div>
<!-- modal add role-->



<!-- Modal Edit Role -->
<div class="modal fade" id="setting-edit-role" role="dialog" aria-labelledby="Setting : Edit Role" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Edit Role</h4>
                <small> Edit role to be assigned to users of the system </small>
            </div>
           
           <?php             

              $model = 'Role';
              $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'editRole'));

              echo $this->Form->create($model, $options);
              echo $this->Form->hidden('role_id', array('id'=>'edit-role-id'));

              
              ?>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Role Name (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('role_name',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert role name','required', 'id' => 'edit-role-name'));
                            ?>
                            <span class="help-block small">Describe and differentiate role with unique name</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Role Description </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'Details about the company', 'id' => 'edit-role-description'));
                                echo "<small> (*) Denotes a required field </small>";

                            ?>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Permission</label>
                        <div class="col-lg-9 col-sm-9">

                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('dashboard',array('type'=>'checkbox','after' => ' Dashboard', 'id' => 'edit-dashboard'));
                                    ?>
                                </label>

                            </div>
                            <h5>Budget</h5>
                            <!-- <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        //echo $this->Form->input('create_request_budget',array('type'=>'checkbox','after' => ' Create Request','id'=>'edit-create-request-budget'));
                                    ?>
                                </label>

                            </div> -->
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_request_budget',array('type'=>'checkbox','after' => ' My Requests','id'=> 'edit-my-request-budget'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('all_request_budget',array('type'=>'checkbox','after' => ' Budget List','id' => 'edit-all-request-budget'));
                                    ?>
                                </label>

                            </div>
                           <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('request_management_budget',array('type'=>'checkbox','after' => ' Request Management','id'=>'edit-request-management-budget'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('budget_archive',array('type'=>'checkbox','after' => ' Budget Archive','id'=>'edit-budget-archive'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('report_budget',array('type'=>'checkbox','after' => 'Budget Report','id'=>'edit-report-budget'));
                                    ?>
                                </label>

                            </div>

                            <h5>Non-Financial Memo</h5>
                            <!-- <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        //echo $this->Form->input('create_request_memo',array('type'=>'checkbox','after' => ' Create Request','id' => 'edit-create-request-memo'));
                                    ?>
                                </label>

                            </div> -->
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_request_memo',array('type'=>'checkbox','after' => ' My Requests','id'=>'edit-my-request-memo'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('all_request_memo',array('type'=>'checkbox','after' => ' All Requests','id' => 'edit-all-request-memo'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('request_management_memo',array('type'=>'checkbox','after' => ' Request Management', 'id' => 'edit-request-management-memo'));
                                    ?>
                                </label>

                            </div>

                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_memo_memo',array('type'=>'checkbox','after' => ' My Memo', 'id' => 'edit-my-memo-memo'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('report_memo',array('type'=>'checkbox','after' => ' Memo Report', 'id' => 'edit-report-memo'));
                                    ?>
                                </label>

                            </div>

                            <h5> Financial Memo </h5>
                            <!-- <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        //echo $this->Form->input('create_request_financial_memo',array('type'=>'checkbox','after' => ' Create Request', 'id' => 'edit-create-request-financial-memo'));
                                    ?>
                                </label>

                            </div> -->
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_request_financial_memo',array('type'=>'checkbox','after' => ' My Requests', 'id' => 'edit-my-request-financial-memo'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('all_request_financial_memo',array('type'=>'checkbox','after' => ' All Requests', 'id' => 'edit-all-request-financial-memo'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('request_management_financial_memo',array('type'=>'checkbox','after' => ' Request Management', 'id' => 'edit-request-management-financial-memo'));
                                    ?>
                                </label>

                            </div>

                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_memo_financial_memo',array('type'=>'checkbox','after' => ' My Memo', 'id' => 'edit-my-memo-financial-memo'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('report_financial_memo',array('type'=>'checkbox','after' => ' Memo Report', 'id' => 'edit-report-financial-memo'));
                                    ?>
                                </label>

                            </div>

                            <h5> Settings </h5>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('settings',array('type'=>'checkbox','after' => ' Settings','id' => 'edit-settings'));
                                    ?>
                                </label>

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 col-sm-3 control-label"> <?php  echo "<small> (*) Denotes a required field </small>";  ?></label>
                    </div>

                </div>
                 <div class="modal-footer">
                 <?php 

                 echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                 echo $this->Form->end();
                 echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

                 ?>
             
             </div>    
         </div>
    </div>
</div>
<!-- modal edit role-->


<!-- Modal View Role -->
<div class="modal fade" id="setting-view-role" role="dialog" aria-labelledby="Setting : View Role" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : View Role</h4>
                <small> View role to be assigned to users of the system </small>
            </div>
           
           <?php             

              $model = 'Role';
              $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'editRole'));

              echo $this->Form->create($model, $options);
              echo $this->Form->hidden('role_id', array('id'=>'view-role-id'));

              
              ?>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Role Name </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('role_name',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'','required', 'id' => 'view-role-name', 'readonly'));
                            ?>
                            <span class="help-block small">Describe and differentiate role with unique name</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Role Description </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'', 'id' => 'view-role-description', 'readonly'));
 
                            ?>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Permission</label>
                        <div class="col-lg-9 col-sm-9">

                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('dashboard',array('type'=>'checkbox','after' => ' Dashboard', 'id' => 'view-dashboard', 'disabled'));
                                    ?>
                                </label>

                            </div>
                            <h5>Budget</h5>
                            <div class="checkboxes">
                                <!--  <label class="label_check" for="checkbox-01">
                                    <?php
                                       // echo $this->Form->input('create_request_budget',array('type'=>'checkbox','after' => ' Create Request','id'=>'view-create-request-budget','disabled'));
                                    ?>
                                </label>
 -->
                            </div>
                            <div class="checkboxes">
                                <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_request_budget',array('type'=>'checkbox','after' => ' My Requests','id'=> 'view-my-request-budget','disabled'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('all_request_budget',array('type'=>'checkbox','after' => ' Budget List','id' => 'view-all-request-budget','disabled'));
                                    ?>
                                </label>

                            </div>
                           <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('request_management_budget',array('type'=>'checkbox','after' => ' Request Management','id'=>'view-request-management-budget','disabled'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('budget_archive',array('type'=>'checkbox','after' => ' Budget Archive','id'=>'view-budget-archive','disabled'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('report_budget',array('type'=>'checkbox','after' => ' Budget Report','id'=>'view-report-budget','disabled'));
                                    ?>
                                </label>

                            </div>

                            <h5>Non-Financial Memo</h5>
                            <div class="checkboxes">
                                 <!-- <label class="label_check" for="checkbox-01">
                                    <?php
                                        // echo $this->Form->input('create_request_memo',array('type'=>'checkbox','after' => ' Create Request','id' => 'view-create-request-memo','disabled'));
                                    ?>
                                </label> -->

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_request_memo',array('type'=>'checkbox','after' => ' My Requests','id'=>'view-my-request-memo','disabled'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('all_request_memo',array('type'=>'checkbox','after' => ' All Requests','id' => 'view-all-request-memo','disabled'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('request_management_memo',array('type'=>'checkbox','after' => ' Request Management', 'id' => 'view-request-management-memo','disabled'));
                                    ?>
                                </label>

                            </div>

                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_memo_memo',array('type'=>'checkbox','after' => ' My Memo', 'id' => 'view-my-memo-memo','disabled'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('report_memo',array('type'=>'checkbox','after' => ' Memo Report', 'id' => 'view-report-memo','disabled'));
                                    ?>
                                </label>

                            </div>

                            <h5> Financial Memo </h5>
                            <div class="checkboxes">
                                 <!-- <label class="label_check" for="checkbox-01">
                                    <?php
                                        // echo $this->Form->input('create_request_financial_memo',array('type'=>'checkbox','after' => ' Create Request', 'id' => 'view-create-request-financial-memo','disabeld'));
                                    ?>
                                </label> -->

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_request_financial_memo',array('type'=>'checkbox','after' => ' My Requests', 'id' => 'view-my-request-financial-memo','disabled'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('all_request_financial_memo',array('type'=>'checkbox','after' => ' All Requests', 'id' => 'view-all-request-financial-memo','disabled'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('request_management_financial_memo',array('type'=>'checkbox','after' => ' Request Management', 'id' => 'view-request-management-financial-memo','disabled'));
                                    ?>
                                </label>

                            </div>

                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('my_memo_financial_memo',array('type'=>'checkbox','after' => ' My Memo', 'id' => 'view-my-memo-financial-memo','disabled'));
                                    ?>
                                </label>

                            </div>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('report_financial_memo',array('type'=>'checkbox','after' => ' Memo Report' , 'id' => 'view-report-financial-memo','disabled'));
                                    ?>
                                </label>

                            </div>

                            <h5> Settings </h5>
                            <div class="checkboxes">
                                 <label class="label_check" for="checkbox-01">
                                    <?php
                                        echo $this->Form->input('settings',array('type'=>'checkbox','after' => ' Settings','id' => 'view-settings','disabled'));
                                    ?>
                                </label>

                            </div>
                        </div>
                    </div>
                   
                </div>
                 <div class="modal-footer">
                 <?php 

                  echo $this->Form->end();
 
                 ?>
             
             </div>    
         </div>
    </div>
</div>
<!-- modal view role-->




<!-- Modal Add Comapnies -->
<div class="modal fade" id="setting-add-company" role="dialog" aria-labelledby="Setting : Add Company" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Add Company</h4>
                <small> Add companies to the system </small>
            </div>
                <div class="modal-body">

                    <?php             

                       $model = 'Company';
                       $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'addCompany'));

                       echo $this->Form->create($model, $options);

                       
                       ?>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company Name (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('company',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert company name','required'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Year Established</label>
                        <div class="col-lg-9 col-sm-9">   
                        <?php
                                    echo $this->Form->input('year_established',array('type'=>'number','max'=> '2500', 'min' => '1900','class'=>'form-control','placeholder'=>'Year established e.g. 2004 '));

                                ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Description</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'Details about the company'));

                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 col-sm-3 control-label"> <?php  echo "<small> (*) Denotes a required field </small>";  ?></label>
                    </div>

                </div>
                <div class="modal-footer">
                <?php 

                echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                echo $this->Form->end();

                echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

                ?>
    
            </div>
        </div>
    </div>
</div>
<!-- modal add comapnis-->


<!-- Modal Edit Comapnies -->
<div class="modal fade" id="setting-edit-company" role="dialog" aria-labelledby="Setting : Edit Company" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Edit Company</h4>
                <small> Edit companies to the system </small>
            </div>
                <div class="modal-body">

                    <?php             

                       $model = 'Company';
                       $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'editCompany'));

                       echo $this->Form->create($model, $options);
                       echo $this->Form->hidden('Company.id',array('id'=>'edit-company-id'));

                       
                       ?>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company Name (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('company',array('type'=>'text','maxlength' => '100','class'=>'form-control', 'id' => 'edit-company-name','placeholder'=>'Insert company name','required'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Year Established</label>
                        <div class="col-lg-9 col-sm-9">   
                        <?php
                                    echo $this->Form->input('year_established',array('type'=>'number','max'=> '2500', 'min' => '1900','class'=>'form-control','id' => 'edit-company-year','placeholder'=>'Year established'));
                                ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Description</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control','id' => 'edit-company-description','placeholder'=>'Details about the company'));


                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 col-sm-3 control-label"> <?php  echo "<small> (*) Denotes a required field </small>";  ?></label>
                    </div>

                </div>
                <div class="modal-footer">
                <?php 

                echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                echo $this->Form->end();
                echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

                ?>

            </div>
        </div>
    </div>
</div>
<!-- modal edit comapnis-->


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

               $model = 'Group';
               $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'addGroup'));
               echo $this->Form->create($model, $options);
                
               ?>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company Name </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php

                                $companies_list = array();
                                foreach ($allcompanies as $key => $company) {
                                    $companies_list[$company['Company']['company_id']] = $company['Company']['company'];
                                }

                                echo $this->Form->input('company_id',array('type'=>'select','options'=>$companies_list,'class'=>'select full-width','required', 'empty'=>'-- Please select a company --'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Group Name (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('group_name',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert group name', 'required'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Description </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'Details about the group'));


                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 col-sm-3 control-label"> <?php  echo "<small> (*) Denotes a required field </small>";  ?></label>
                    </div>

                </div>
            <div class="modal-footer">
                <?php 

                echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                echo $this->Form->end();
                echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

                ?>
            </div>
        </div>
    </div>
</div>
<!-- modal add group-->


<!-- Modal Edit Group -->
<div class="modal fade" id="setting-edit-group" role="dialog" aria-labelledby="Setting : Edit Group" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Edit Group</h4>
                <small> Edit a group in the system </small>
            </div>
            <?php             

               $model = 'Group';
               $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'editGroup'));

               echo $this->Form->create($model, $options);
               echo $this->Form->hidden('group_id',array('id'=>'edit-group-id'));

                
               ?>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company Name (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php

                                $companies_list = array();
                                foreach ($allcompanies as $key => $company) {
                                    $companies_list[$company['Company']['company_id']] = $company['Company']['company'];
                                }

                                echo $this->Form->input('company_id',array('type'=>'select','options'=>$companies_list,'class'=>'select full-width','id' => 'edit-groupcompany-id','required', 'empty'=>'-- Please select a company --'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Group Name (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('group_name',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert group name', 'id' => 'edit-group-name','required'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Description</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'Details about the group', 'id' => 'edit-group-description'));


                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 col-sm-3 control-label"> <?php  echo "<small> (*) Denotes a required field </small>";  ?></label>
                    </div>

                </div>
            <div class="modal-footer">
                <?php 

                echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                echo $this->Form->end();

                echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

                ?>
            </div>
        </div>
    </div>
</div>
<!-- modal edit group-->

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
                
                $model = 'Department';
                $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'addDepartment'));

                echo $this->Form->create($model, $options);


            ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                
                                $companies_list = array();
                                foreach ($allcompanies as $key => $company) {
                                    $companies_list[$company['Company']['company_id']] = $company['Company']['company'];
                                }

                                echo $this->Form->input('company_id',array('type'=>'select','options'=>$companies_list,'class'=>'select full-width','required','empty'=>'-- Please select company first --','id' => 'add-departmentcompany-id'));


                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Group (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                            
                                // $groups_list = array();
                                // foreach ($allgroups as $key => $group) {
                                //     $groups_list[$group['Group']['group_id']] = $group['Group']['group_name'];
                                // }

                                echo $this->Form->input('group_id',array('type'=>'select','class'=>'select full-width','required','empty'=>'-- Please select company first --', 'id' => 'add-departmentgroup-id'));

                                echo "<small> The group will be displayed according to selected company </small>";
                            ?>
                        </div>
                    </div>
                   
                    <hr>
                    <h4> Department Details </h4>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Full Name (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('department_name',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert department name', 'required'));
                            ?>
                        </div>
                    </div>
                     <!-- Phase 2 : Additional info for budget -->
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Shortform (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('department_shortform',array('type'=>'text','maxlength' => '20','class'=>'form-control','placeholder'=>'Insert department shortform (eg: ICT)', 'required'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Type (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                            
                                $dept_type = array('1'=>'Academic','2'=>'Non-Academic');
                               

                                echo $this->Form->input('department_type',array('type'=>'select','options'=>$dept_type,'class'=>'select full-width','required','empty'=>'-- Please select department type --'));

                               
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">No.of staff (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('total_staff',array('type'=>'number','min' => '0', 'max' => '9999','class'=>'form-control','placeholder'=>'Insert no.of staff','required'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Description</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'Details about the group'));


                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 col-sm-3 control-label"> <?php  echo "<small> (*) Denotes a required field </small>";  ?></label>
                    </div>

                </div>
            <div class="modal-footer">
                <?php 

                echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                echo $this->Form->end();
                echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

                ?>
            </div>
        </div>
    </div>
</div>
<!-- modal add department-->

<!-- Modal Edit Department -->
<div class="modal fade" id="setting-edit-department" role="dialog" aria-labelledby="Setting : Edit Department" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Edit Department</h4>
                <small> Edit department to the system </small>
            </div>
            <?php
                
                $model = 'Department';
                $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'editDepartment'));

                echo $this->Form->create($model, $options);
                echo $this->Form->hidden('department_id',array('id'=>'edit-department-id'));


            ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Company (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                
                                $companies_list = array();
                                foreach ($allcompanies as $key => $company) {
                                    $companies_list[$company['Company']['company_id']] = $company['Company']['company'];
                                }

                                echo $this->Form->input('company_id',array('type'=>'select','options'=>$companies_list,'class'=>'select full-width','required','empty'=>'-- Please select company first --', 'id'=>'edit-departmentcompany-id'));


                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Group (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                     
                                $groups_list = array();
                                foreach ($allgroups as $key => $group) {
                                    $groups_list[$group['Group']['group_id']] = $group['Group']['group_name'];
                                }

                                echo $this->Form->input('group_id',array('type'=>'select','options'=>$groups_list,'class'=>'select full-width','required','empty'=>'-- Please select company first --','id'=>'edit-departmentgroup-id'));

                                echo "<small> The group will be displayed according to selected company </small>";
                            ?>
                        </div>
                    </div>
                    
                    <hr>
                    <h4> Department Details </h4>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Full Name (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('department_name',array('type'=>'text','maxlength' => '100','class'=>'form-control','placeholder'=>'Insert department name', 'required','id'=>'edit-department-name'));
                            ?>
                        </div>
                    </div>
                    <!-- Phase 2 : Additional info for budget -->
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Shortform (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('department_shortform',array('type'=>'text','maxlength' => '20','class'=>'form-control','placeholder'=>'Insert department shortform (eg: ICT)', 'required','id'=>'edit-department-shortform'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Type (*)</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                            
                                $dept_type = array('1'=>'Academic','2'=>'Non-Academic');
                               

                                echo $this->Form->input('department_type',array('type'=>'select','options'=>$dept_type,'class'=>'select full-width','required','empty'=>'-- Please select department type --','id'=>'edit-department-type'));

                               
                            ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">No.of staff (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('total_staff',array('type'=>'number','min' => '0', 'max' => '9999','class'=>'form-control','placeholder'=>'Insert no.of staff','required','id'=>'edit-department-total-staff'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Description</label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('description',array('type'=>'textarea','class'=>'form-control','placeholder'=>'Details about the group','id'=>'edit-department-description'));


                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-5 col-sm-3 control-label"> <?php  echo "<small> (*) Denotes a required field </small>";  ?></label>
                    </div>

                </div>
            <div class="modal-footer">
                <?php 

                echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                echo $this->Form->end();
                echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

                ?>
            </div>
        </div>

    </div>
</div>
<!-- modal edit department-->




<!-- Modal Add Items -->
<div class="modal fade" id="setting-add-item" role="dialog" aria-labelledby="Setting : Add Item" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Setting : Add Budget Item</h4>
                <small> Add budget item to the system </small>
            </div>
            <?php
                
                $model = 'Item';
                $options = array('class'=>'form-horizontal','inputDefaults' => array('label' => false),'url' =>  array('controller'=>'setting','action'=>'addItem'));

                echo $this->Form->create($model, $options);


            ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label"> Item Category </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('parent_item_id',array('type'=>'select','options'=>$itemList,'class'=>'select full-width','empty'=>'-- Select item category (If sub item) --'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Item Code (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('item_code',array('type'=>'text','maxlength' => '32','class'=>'form-control','placeholder'=>'Insert new item code', 'required'));
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-sm-3 control-label">Item Name (*) </label>
                        <div class="col-lg-9 col-sm-9">
                            <?php
                                echo $this->Form->input('item',array('type'=>'text','maxlength' => '128','class'=>'form-control','placeholder'=>'Insert new item name', 'required'));
                            ?>
                        </div>
                    </div>

                    
                    <div class="form-group">
                        <label class="col-lg-5 col-sm-3 control-label"> <?php  echo "<small> (*) Denotes a required field </small>";  ?></label>
                    </div>

                </div>
            <div class="modal-footer">
                <?php 

                echo $this->Form->button('Save changes', $options = array('class' =>'btn btn-success'));
                echo $this->Form->end();
                echo $this->Form->button('Close', $options = array('class' => 'btn btn-primary', 'data-dismiss' => 'modal')); 

                ?>
            </div>
        </div>
    </div>
</div>
<!-- modal add Items-->




<!-- JavaScript -->

<script>

// JQuery + Ajax 


document.getElementById('add-user-search').addEventListener('click',function(){
    var username = $('#add-user-id').val();


    $.ajax({

        dataType: "html",
        type: "POST",
        url: "<?php echo Router::url(array('controller'=>'ldap','action'=>'searchUser' ));?>",
        data: "data[username] = " + username,
        success : function(response){

            try{

                var USER = JSON.parse(response);
                document.getElementById('add-user-email').value = USER["email"];
                document.getElementById('add-staffname').value = USER["staff_name"];
            }catch(err){

                document.getElementById('add-user-email').value = '';
                document.getElementById('add-staffname').value = '';


                alert("User not found or user already added, please check the user list");


            }


        }

    });
});



document.getElementById('add-departmentcompany-id').addEventListener('change',function(){

    var val = $('#add-departmentcompany-id').val();

    $.ajax({
        dataType: "html",
        type: "POST",
        url: "<?php echo Router::url(array('controller'=>'setting','action'=>'getGroupList' ));?>",
        data : "data[Company] = " + val,
        success : function(response){

            var groups = JSON.parse(response);


            var select= '<select>';
            var option = '';

             $.each(groups, function(index, value) {
                 option += '<option value = '+ index +'>' + value + '</option>';
             });

            select = select+option + '</select>';
            $('#add-departmentgroup-id').html(select);

 
        }
    });

});


document.getElementById('edit-departmentcompany-id').addEventListener('change',function(){

    var val = $('#edit-departmentcompany-id').val();

    $.ajax({
        dataType: "html",
        type: "POST",
        url: "<?php echo Router::url(array('controller'=>'setting','action'=>'getGroupList' ));?>",
        data : "data[Company] = " + val,
        success : function(response){

            var groups = JSON.parse(response);

            var select= '<select>';
            var option = '';

             $.each(groups, function(index, value) {
                 option += '<option value = '+ index +'>' + value + '</option>';
             });

            select = select+option + '</select>';
            $('#edit-departmentgroup-id').html(select);

 
        }
    });
});


 document.getElementById('add-usercompany-id').addEventListener('change',function(){

    var val = $('#add-usercompany-id').val();

    $.ajax({
        dataType: "html",
        type: "POST",
        url: "<?php echo Router::url(array('controller'=>'setting','action'=>'getGroupList' ));?>",
        data : "data[Company] = " + val,
        success : function(response){

            var groups = JSON.parse(response);


            var select= '<select>';
            var option = '<option>' + '' + '</option>';

             $.each(groups, function(index, value) {
                 option += '<option value = '+ index +'>' + value + '</option>';
             });

            select = select+option + '</select>';
            $('#add-usergroup-id').html(select);



            var select= '<select>';
            var option = '<option>' + '' + '</option>';
            select = select+option + '</select>';
            $('#add-userdepartment-id').html(select);

 
        }
    });
});


  document.getElementById('edit-usercompany-id').addEventListener('change',function(){

     var val = $('#edit-usercompany-id').val();

     $.ajax({
         dataType: "html",
         type: "POST",
         url: "<?php echo Router::url(array('controller'=>'setting','action'=>'getGroupList' ));?>",
         data : "data[Company] = " + val,
         success : function(response){

             var groups = JSON.parse(response);


             var select= '<select>';
             var option = '<option>' + '' + '</option>';

              $.each(groups, function(index, value) {
                  option += '<option value = '+ index +'>' + value + '</option>';
              });

             select = select+option + '</select>';
             $('#edit-usergroup-id').html(select);



             var select= '<select>';
             var option = '<option>' + '' + '</option>';

              $.each(groups, function(index, value) {
                  option += '<option value = '+ index +'>' + value + '</option>';
              });

             select = select+option + '</select>';
             $('#edit-userdepartment-id').html(select);

  
         }
     });
 });



document.getElementById('add-usergroup-id').addEventListener('change',function(){

      var val = $('#add-usergroup-id').val();

      $.ajax({
          dataType: "html",
          type: "POST",
          url: "<?php echo Router::url(array('controller'=>'setting','action'=>'getDepartmentList' ));?>",
          data : "data[Group] = " + val,
          success : function(response){

              var groups = JSON.parse(response);

              var select= '<select>';
              var option = '<option>' + '' + '</option>';

               $.each(groups, function(index, value) {
                   option += '<option value = '+ index +'>' + value + '</option>';
               });

              select = select+option + '</select>';
              $('#add-userdepartment-id').html(select);

   
          }
      });
  });


document.getElementById('edit-usergroup-id').addEventListener('change',function(){

      var val = $('#edit-usergroup-id').val();

      $.ajax({
          dataType: "html",
          type: "POST",
          url: "<?php echo Router::url(array('controller'=>'setting','action'=>'getDepartmentList' ));?>",
          data : "data[Group] = " + val,
          success : function(response){

              var groups = JSON.parse(response);

              var select= '<select>';

              var option = '<option>' + '-- Please select a Department --' + '</option>';

               $.each(groups, function(index, value) {
                   option += '<option value = '+ index +'>' + value + '</option>';
               });

              select = select+option + '</select>';
              $('#edit-userdepartment-id').html(select);

   
          }
      });
  });



$(document).ready(function () {


    $('.view-role-btn').on('click',function(){
        var role_id = $(this).data('role-id');
        var role_name = $(this).data('role-name');
        var description = $(this).data('description');
        var dashboard = $(this).data('dashboard');
        var create_request_budget = $(this).data('create-request-budget');
        var my_request_budget = $(this).data('my-request-budget');
        var all_request_budget = $(this).data('all-request-budget')
        var request_management_budget = $(this).data('request-management-budget');
        var budget_archive = $(this).data('budget-archive');
        var report_budget = $(this).data('report-budget');
        var create_request_memo = $(this).data('create-request-memo');
        var my_request_memo = $(this).data('my-request-memo');
        var all_request_memo = $(this).data('all-request-memo');
        var request_management_memo = $(this).data('request-management-memo');
        var my_memo_memo = $(this).data('my-memo-memo');
        var report_memo = $(this).data('report-memo');
        var create_request_financial_memo = $(this).data('create-request-financial-memo');
        var my_request_financial_memo = $(this).data('my-request-financial-memo');
        var all_request_financial_memo = $(this).data('all-request-financial-memo');
        var request_management_financial_memo = $(this).data('request-management-financial-memo');
        var my_memo_financial_memo = $(this).data('my-memo-financial-memo');
        var report_financial_memo = $(this).data('report-financial-memo');

        var settings = $(this).data('settings');
 

        
        $('#view-role-id').val(role_id);
        $('#view-role-name').val(role_name);
        $('#view-role-description').val(description);


        if(dashboard)
            $('#view-dashboard').attr({'checked': true});
        else
            $('#view-dashboard').attr({'checked': false});

        if(create_request_budget)
            $('#view-create-request-budget').attr({'checked': true });
        else
            $('#view-create-request-budget').attr({'checked': false });

        if(my_request_budget)
            $('#view-my-request-budget').attr({'checked': true});
        else
            $('#view-my-request-budget').attr({'checked': false});

        if(all_request_budget)
            $('#view-all-request-budget').attr({'checked': true});
        else
            $('#view-all-request-budget').attr({'checked': false});

        if(request_management_budget)
            $('#view-request-management-budget').attr({'checked': true});
        else
            $('#view-request-management-budget').attr({'checked': false});


        if(budget_archive)
            $('#view-budget-archive').attr({'checked': true});
        else
            $('#view-budget-archive').attr({'checked': false});


        if(report_budget)
            $('#view-report-budget').attr({'checked': true});
        else
            $('#view-report-budget').attr({'checked': false});


        if(create_request_memo)
            $('#view-create-request-memo').attr({'checked': true});
        else
            $('#view-create-request-memo').attr({'checked': false});



        if(all_request_memo)
            $('#view-all-request-memo').attr({'checked': true});
        else
            $('#view-all-request-memo').attr({'checked': false});



        if(my_request_memo)
            $('#view-my-request-memo').attr({'checked': true});
        else
            $('#view-my-request-memo').attr({'checked': false});



        if(request_management_memo)
            $('#view-request-management-memo').attr({'checked': true});
        else
            $('#view-request-management-memo').attr({'checked': false});


        if(my_memo_memo)
            $('#view-my-memo-memo').attr({'checked': true});
        else
            $('#view-my-memo-memo').attr({'checked': false});

        if(report_memo)
            $('#view-report-memo').attr({'checked': true});
        else
            $('#view-report-memo').attr({'checked': false});

        if(create_request_financial_memo)
            $('#view-create-request-financial-memo').attr({'checked': true});
        else
            $('#view-create-request-financial-memo').attr({'checked': false});



        if(my_request_financial_memo)
            $('#view-my-request-financial-memo').attr({'checked': true});
        else
            $('#view-my-request-financial-memo').attr({'checked': false});



        if(all_request_financial_memo)
            $('#view-all-request-financial-memo').attr({'checked': true});
        else
            $('#view-all-request-financial-memo').attr({'checked': false});



        if(request_management_financial_memo)
            $('#view-request-management-financial-memo').attr({'checked': true});
        else
            $('#view-request-management-financial-memo').attr({'checked': false});

        if(my_memo_financial_memo)
            $('#view-my-memo-financial-memo').attr({'checked': true});
        else
            $('#view-my-memo-financial-memo').attr({'checked': false});

         if(report_financial_memo)
            $('#view-report-financial-memo').attr({'checked': true});
        else
            $('#view-report-financial-memo').attr({'checked': false});

        if(settings)
            $('#view-settings').attr({'checked': true});
        else
            $('#view-settings').attr({'checked': false});



    })




    $('.edit-role-btn').on('click',function(){
        var role_id = $(this).data('role-id');
        var role_name = $(this).data('role-name');
        var description = $(this).data('description');
        var dashboard = $(this).data('dashboard');
        //var create_request_budget = $(this).data('create-request-budget');
        var my_request_budget = $(this).data('my-request-budget');
        var all_request_budget = $(this).data('all-request-budget')
        var request_management_budget = $(this).data('request-management-budget');
        var budget_archive = $(this).data('budget-archive');
        var report_budget = $(this).data('report-budget');
        //var create_request_memo = $(this).data('create-request-memo');
        var my_request_memo = $(this).data('my-request-memo');
        var all_request_memo = $(this).data('all-request-memo');
        var request_management_memo = $(this).data('request-management-memo');
        var report_memo = $(this).data('report-memo');
        var my_request_financial_memo = $(this).data('my-request-financial-memo');
        var all_request_financial_memo = $(this).data('all-request-financial-memo');
        var request_management_financial_memo = $(this).data('request-management-financial-memo');
        var my_memo_memo = $(this).data('my-memo-memo');
        var my_memo_financial_memo = $(this).data('my-memo-financial-memo');
        var report_financial_memo = $(this).data('report-financial-memo');
        var settings = $(this).data('settings');


        
        $('#edit-role-id').val(role_id);
        $('#edit-role-name').val(role_name);
        $('#edit-role-description').val(description);


        if(dashboard)
            $('#edit-dashboard').attr({'checked': true});
        else
            $('#edit-dashboard').attr({'checked': false});


        if(my_request_budget)
            $('#edit-my-request-budget').attr({'checked': true});
        else
            $('#edit-my-request-budget').attr({'checked': false});

        if(all_request_budget)
            $('#edit-all-request-budget').attr({'checked': true});
        else
            $('#edit-all-request-budget').attr({'checked': false});

        if(request_management_budget)
            $('#edit-request-management-budget').attr({'checked': true});
        else
            $('#edit-request-management-budget').attr({'checked': false});


        if(budget_archive)
            $('#edit-budget-archive').attr({'checked': true});
        else
            $('#edit-budget-archive').attr({'checked': false});


        if(report_budget)
            $('#edit-report-budget').attr({'checked': true});
        else
            $('#edit-report-budget').attr({'checked': false});


        if(my_memo_memo)
            $('#edit-my-memo-memo').attr({'checked': true});
        else
            $('#edit-my-memo-memo').attr({'checked': false});




        if(all_request_memo)
            $('#edit-all-request-memo').attr({'checked': true});
        else
            $('#edit-all-request-memo').attr({'checked': false});



        if(my_request_memo)
            $('#edit-my-request-memo').attr({'checked': true});
        else
            $('#edit-my-request-memo').attr({'checked': false});



        if(request_management_memo)
            $('#edit-request-management-memo').attr({'checked': true});
        else
            $('#edit-request-management-memo').attr({'checked': false});

        if(report_memo)
            $('#edit-report-memo').attr({'checked': true});
        else
            $('#edit-report-memo').attr({'checked': false});

        if(my_memo_financial_memo)
            $('#edit-my-memo-financial-memo').attr({'checked': true});
        else
            $('#edit-my-memo-financial-memo').attr({'checked': false});




        // if(create_request_financial_memo)
        //     $('#edit-create-request-financial-memo').attr({'checked': true});
        // else
        //     $('#edit-create-request-financial-memo').attr({'checked': false});



        if(my_request_financial_memo)
            $('#edit-my-request-financial-memo').attr({'checked': true});
        else
            $('#edit-my-request-financial-memo').attr({'checked': false});



        if(all_request_financial_memo)
            $('#edit-all-request-financial-memo').attr({'checked': true});
        else
            $('#edit-all-request-financial-memo').attr({'checked': false});



        if(request_management_financial_memo)
            $('#edit-request-management-financial-memo').attr({'checked': true});
        else
            $('#edit-request-management-financial-memo').attr({'checked': false});


        if(report_financial_memo)
            $('#edit-report-financial-memo').attr({'checked': true});
        else
            $('#edit-report-financial-memo').attr({'checked': false});

        if(settings)
            $('#edit-settings').attr({'checked': true});
        else
            $('#edit-settings').attr({'checked': false});



    })




$('.edit-user-btn').on('click',function(){
    var user_id = $(this).data('user-id');
    var staff_name = $(this).data('staff-name');
    var staff_id = $(this).data('staff-id');
    var email = $(this).data('email');
    var role_id = $(this).data('role-id');
    var company_id = $(this).data('company-id');
    var group_id = $(this).data('group-id');
    var department_id = $(this).data('department-id')
    var designation = $(this).data('designation');
    var loa = $(this).data('loa');
    var hod = $(this).data('hod');
    var pmo = $(this).data('pmo');
    var ict = $(this).data('ict');
    var finance = $(this).data('finance');
    var requestor = $(this).data('requestor');
    var reviewer = $(this).data('reviewer');
    var approver = $(this).data('approver');
    var recommender = $(this).data('recommender');



    $('#edit-user-id').val(user_id);
    $('#edit-staff-name').val(staff_name);
    $('#edit-staff-id').val(staff_id);
    $('#edit-email').val(email);  
    $('#edit-userrole-id').val(role_id);
    $('#edit-usercompany-id').val(company_id);
    $('#edit-usergroup-id').val(group_id);
    $('#edit-userdepartment-id').val(department_id);
    $('#edit-designation').val(designation);
    $('#edit-loa').val(loa);



    if(hod)
        $('#edit-hod').attr({'checked': true});
    else
        $('#edit-hod').attr({'checked': false});

    if(pmo)
        $('#edit-pmo').attr({'checked': true });
    else
        $('#edit-pmo').attr({'checked': false });

    if(ict)
        $('#edit-ict').attr({'checked': true});
    else
        $('#edit-ict').attr({'checked': false});

    if(finance)
        $('#edit-finance').attr({'checked': true});
    else
        $('#edit-finance').attr({'checked': false});

    if(requestor)
        $('#edit-requestor').attr({'checked': true});
    else
        $('#edit-requestor').attr({'checked': false});

    if(reviewer)
        $('#edit-reviewer').attr({'checked': true});
    else
        $('#edit-reviewer').attr({'checked': false});

    if(approver)
        $('#edit-approver').attr({'checked': true});
    else
        $('#edit-approver').attr({'checked': false});

    if(recommender)
        $('#edit-recommender').attr({'checked': true});
    else
        $('#edit-recommender').attr({'checked': false});



})



    $('.edit-company-btn').on('click',function(){
        var company_id = $(this).data('company-id');
        var company_name = $(this).data('company-name');
        var year_established = $(this).data('year');
        var description = $(this).data('description');

        $('#edit-company-id').val(company_id);
        $('#edit-company-name').val(company_name);
        $('#edit-company-description').val(description);
        $('#edit-company-year').val(year_established);
    })

    $('.delete-company-btn').on('click',function(){
        var company_id = $(this).data('company-id');
        var company_name = $(this).data('company-name');

        $('#delete-company-id').val(company_id);
        $('#delete-company-name').val(company_name);


    })





    $('.edit-group-btn').on('click',function(){
        var group_id = $(this).data('group-id');
        var group_name = $(this).data('group-name');
        var company_id = $(this).data('company-id');
        var description = $(this).data('description');

        $('#edit-group-id').val(group_id);
        $('#edit-group-name').val(group_name);
        $('#edit-group-description').val(description);
        $('#edit-groupcompany-id').val(company_id);


    })





    $('.edit-department-btn').on('click',function(){
        var department_id = $(this).data('department-id');
        var group_id = $(this).data('group-id');
        var company_id = $(this).data('company-id');
        var department_name = $(this).data('department-name');
        var department_shortform = $(this).data('department-shortform');
        var department_type = $(this).data('department-type');
        var total_staff = $(this).data('total-staff')
        var description = $(this).data('description');

        $('#edit-department-id').val(department_id);
        $('#edit-departmentcompany-id').val(company_id);
        $('#edit-departmentgroup-id').val(group_id);
        $('#edit-department-name').val(department_name);
        $('#edit-department-shortform').val(department_shortform);
        $('#edit-department-type').val(department_type);
        $('#edit-department-total-staff').val(total_staff);
        $('#edit-department-description').val(description);


    })

    // remove spaces of ad
    $('#add-user-id').keyup(function(){
        var ori = $(this).val();
        var stripped = ori.replace(/\s+/g,'');
        $(this).val(stripped);
    })

});
</script>

