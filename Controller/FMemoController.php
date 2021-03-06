<?php

/*

*	=========================================================================

*	UNITAR MeMs 2015 

*	@vendor GR Tech Sdn Bhd

*	@team Syikin,Aisyah,Nizam

*	=========================================================================

*	

*	[ File ]

*		FMemoController.php

*			- Controller to control the action for Budget

*	[ Description ]

*		< some description here >

*	[ HELP ]

*		App::uses()  - Class to be used in the the controller (eg: Blowfish,etc)

*		$layout - define the layout which is to be used (in View/Layout)

*		$uses - the model to be use inside the controller

*		$this->render - will render specific file which defined. If none,it will take the default according to action name (in View/[ControllerName]/)

*		$components - CakePHP components to be used in the page. (eg: AuthComponent,etc)

*

*	[--(TO DO at the bottom of the page)--]

* additional note: type keyword: ememo2 to find the changes for phase II.

*/

App::uses('AppController', 'Controller');



class FMemoController extends AppController {

	public $uses = array('Setting','Department','Group','FMemo','FMemoBudget','FRemark','FRemarkAssign','FRemarkFeedback','FReviewer','FStatus','FVendorAttachment','FActivities','Budget','BItemAmount','BDepartment','Item','Staff','FMemoTo');

	public $layout = 'mems';


#region "Page Events"
	public function index(){
		
		$user = $this->getAuth();
		// Check user role
		if(!$user['Role']['my_request_financial_memo']){
			throw new ForbiddenException();
		}

		$userid=$user['user_id'];
		//default condition without filter		
		$conditions = array('FMemo.user_id'=>$userid);

        //to pass for submit button othr than the form
        if (!empty($this->request->params['named']['from'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_from']=$this->request->params['named']['from']; 
        }

        if (!empty($this->request->params['named']['to'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_to']=$this->request->params['named']['to']; 
        }


        if(isset($this->request->data['filter']) || isset($this->request->data['all']) ){

            $fromData='';
            $toData='';
            // debug($this->request->data);exit;

            if (isset($this->request->data['all'])){
                
                $this->request->data = null;
            }
            else{
               
                if (!empty($this->request->data['Filter']['date_from']))
                    $fromData = $this->request->data['Filter']['date_from'];
                if (!empty($this->request->data['Filter']['date_to']))
                    $toData = $this->request->data['Filter']['date_to'];
                 
            }

           
            if(!empty($fromData)){
                array_push($conditions,array('FMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
            }
            if(!empty($toData)){
                array_push($conditions,array('FMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
            }

            

           
        }

		$this->FMemo->contain(array(
								'FStatus'=>array('fields'=>array('status','submission_no')),
								'FRemark.FRemarkAssign'=>array('conditions'=>array('FRemarkAssign.user_id'=>$userid),'fields'=>array('FRemarkAssign.user_id')),
								'Department'=>array('fields'=>array('department_name')),
								'Department.Group'=>array('fields'=>array('group_name'))
								));

		$memo=$this->FMemo->find('all',array('conditions'=> $conditions,'order'=>array('FMemo.created'=>'Desc')));
    
        $this->set('memo',$memo);
		
		$setting=$this->Setting->find('first');
		$this->set('setting',$setting);

		

	}

	
	public function myMemo(){
		
		$user = $this->getAuth();
		// Check user role
		if(!$user['Role']['my_memo_financial_memo']){
			throw new ForbiddenException();
		}
		$userid=$user['user_id'];
		//$this->FMemo->contain(array('FStatus'));
		$this->FMemoTo->contain(array(
								'FMemo.FStatus'=>array('fields'=>array('status','submission_no')),
								'FMemo',
								'FMemo.FComment',
								'FMemo.Department'=>array('fields'=>array('department_name')),
								'FMemo.Department.Group'=>array('fields'=>array('group_name'))));
		
		$memo=$this->FMemoTo->find('all',array('conditions'=>array('FMemoTo.user_id'=>$userid,'FMemo.submission_no NOT'=>0),'order'=>array('FMemo.created'=>'DESC')));
		$this->set('memo',$memo);	
		$this->set('roleid',$user['role_id']);
		

	}

	/*
	 * Renders all dashboard information.
	 *
	 * @param () 
	 * @return ()
	 *
	 * latest modified 10/March @ Faridi
	 */	
	public function dashboard($memo_id){
		$user = $this->getAuth();
		$memo_id=$this->decrypt($memo_id);

		if (!$this->FMemo->exists($memo_id)) {

			throw new ForbiddenException();

		}
		$this->FMemo->contain(array('Department','User','User.Department'));
		$memo = $this->FMemo->find('first', array('conditions' => array('memo_id' => $memo_id)));
		$this->set('memo', $memo);

		$this->FMemoBudget->recursive = -1;
		$budgets = $this->FMemoBudget->find('all', array('conditions' => array('memo_id' => $memo_id)));

		$sumBudget = 0;
		foreach ($budgets as $key => $budget) {
			$sumBudget += $budget['FMemoBudget']['amount'];
		}

		$this->set('sumBudget', $sumBudget);

		// reviewer
		$this->FStatus->contain(array(
							'FReviewer',
							'FReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$reviewers = $this->FStatus->find('all',array('conditions'=>array('FStatus.submission_no'=>$memo['FMemo']['submission_no'],'FReviewer.memo_id'=>$memo_id,'FReviewer.approval_type'=>'reviewer'),'order'=>array('sequence ASC')));
		$this->set('reviewers',$reviewers);

		// approver
		$this->FStatus->contain(array(
							'FReviewer',
							'FReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$approvers = $this->FStatus->find('all',array('conditions'=>array('FStatus.submission_no'=>$memo['FMemo']['submission_no'],'FReviewer.memo_id'=>$memo_id,'FReviewer.approval_type'=>'approver'),'order'=>array('sequence ASC')));
		$this->set('approvers',$approvers);

		// recommender
		$this->FStatus->contain(array(
							'FReviewer',
							'FReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$recommenders = $this->FStatus->find('all',array('conditions'=>array('FStatus.submission_no'=>$memo['FMemo']['submission_no'],'FReviewer.memo_id'=>$memo_id,'FReviewer.approval_type'=>'recommender'),'order'=>array('sequence ASC')));
		$this->set('recommenders',$recommenders);


		// finance
		$this->FStatus->contain(array(
							'FReviewer',
							'FReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$finance = $this->FStatus->find('all',array('conditions'=>array('FStatus.submission_no'=>$memo['FMemo']['submission_no'],'FReviewer.memo_id'=>$memo_id,'FReviewer.approval_type'=>'finance'),'order'=>array('sequence ASC')));
		$this->set('finance',$finance);

		// check if there is pending action for current user
		$userid=$user['user_id'];
		$this->FStatus->contain(array('FReviewer'));
		$isMyPending = $this->FStatus->find('first',array('conditions'=>array('FStatus.status'=>'pending',
																	'FReviewer.user_id'=>$userid,
																	'FStatus.memo_id'=>$memo_id,
																	'FStatus.submission_no'=>$memo['FMemo']['submission_no']
																	)));
		if($isMyPending){
			$this->Session->setFlash('<b> This memo is waiting for your review </b><br><small>Please go to review page by clicking the <em><b>View Memo Details</b></em> button below</small>','flash.info');
		}

		// setting 
		$setting = $this->Setting->find('first');
		$this->set('setting',$setting);

	}

	/*

	*	allRequest()

	*	show all financial memo request of the department

	*	@ < author of function editor >

	*/

	public function allRequest(){
		
		$user = $this->getAuth();
		// Check user role
		if(!$user['Role']['all_request_financial_memo']){
			throw new ForbiddenException();

		}

		$department_id=$user['department_id'];
		
		//if superadmin, allow to see requests from all departments Aisyah 28/9/15
		if ($user['role_id']==17){
			$conditions = array('FMemo.submission_no NOT'=>0);
			
		}
		//if financial admin, allow to see approved request from all departments cikin.my 2/3/16
		else if ($user['role_id']==18){
			$conditions = array('FMemo.submission_no NOT'=>0,'FMemo.memo_status'=>'approved');
			
		}
		
		else{
			$conditions = array('FMemo.submission_no NOT'=>0,'FMemo.department_id'=>$department_id);
			
		}

		// $conditions = array('FMemo.submission_no NOT'=>0);
        //to pass for submit button othr than the form
        if (!empty($this->request->params['named']['div'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['division_id']=$this->request->params['named']['div']; 
        }

        if (!empty($this->request->params['named']['dept'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['department_id']=$this->request->params['named']['dept']; 
        }

        if (!empty($this->request->params['named']['user'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['user_id']=$this->request->params['named']['user']; 
        }

        if (!empty($this->request->params['named']['from'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_from']=$this->request->params['named']['from']; 
        }

        if (!empty($this->request->params['named']['to'])){
            $this->request->data['filter']=true;
            $this->request->data['Filter']['date_to']=$this->request->params['named']['to']; 
        }


        if(isset($this->request->data['filter']) || isset($this->request->data['all']) ){

            $groupData='';
            $deptData='';
            $userData='';
            $fromData='';
            $toData='';
            // debug($this->request->data);exit;

            if (isset($this->request->data['all'])){
                
                $this->request->data = null;
            }
            else{
                if (!empty($this->request->data['Filter']['division_id']))
                    $groupData = $this->request->data['Filter']['division_id'];
                if (!empty($this->request->data['Filter']['department_id']))
                    $deptData = $this->request->data['Filter']['department_id'];
                if (!empty($this->request->data['Filter']['user_id']))
                    $userData = $this->request->data['Filter']['user_id'];
                if (!empty($this->request->data['Filter']['date_from']))
                    $fromData = $this->request->data['Filter']['date_from'];
                if (!empty($this->request->data['Filter']['date_to']))
                    $toData = $this->request->data['Filter']['date_to'];
                 
            }

           
            if(!empty($groupData)){
                array_push($conditions,array('Department.group_id'=>$groupData));
            }
            if(!empty($deptData)){
                array_push($conditions,array('FMemo.department_id'=>$deptData));
            }
            if(!empty($userData)){
                array_push($conditions,array('FMemo.user_id'=>$userData));
            }
            if(!empty($fromData)){
                array_push($conditions,array('FMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
            }
            if(!empty($toData)){
                array_push($conditions,array('FMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
            }

             $this->FMemo->contain(array('FStatus','User','Department'=>array('fields'=>array('department_name')),
            'Department.Group'=>array('fields'=>array('group_name'))));
           
            $memo=$this->FMemo->find('all',array('conditions'=> $conditions,'order'=>array('FMemo.created'=>'Desc')));
        
            $this->set('memo',$memo);

           
        }
        if (!isset($memo))
           $this->Session->setFlash('<b> Please select the filter on the financial memo you want to view. </b>','flash.info');

        $this->set('groups',$this->Group->find('list',array('order'=>'group_name Asc')));
        $this->set('departments',$this->Department->find('list',array('order'=>'code_name Asc')));
        $this->set('allUsers',$this->User->find('list',array('order'=>'staff_name Asc')));

		// setting 
		$setting = $this->Setting->find('first');
		$this->set('setting',$setting);	

	}

	/*

	*	myReview()

	*	show all financial memo request that require user's review

	*	@ < author of function editor >

	*/

	public function myReview(){
		
		$user = $this->getAuth();
		// Check user role
		if(!$user['Role']['request_management_financial_memo']){
			throw new ForbiddenException();
		}
		$this->FMemo->contain(array('FStatus'));
		
		//also should display when other previous reviewer has approved
		$this->FStatus->contain(array(
								'FMemo',
								'FMemo.User'=>array('fields'=>array('staff_name')),
								'FMemo.FStatus',
								'FMemo.Department'=>array('fields'=>array('department_name')),
								'FMemo.Department.Group'=>array('fields'=>array('group_name')),
								'FReviewer',
							)
						);
		$pendingMemo = $this->FStatus->find('all',array(
									'conditions'=>array('FStatus.status'=>'pending','FReviewer.user_id'=>$user['user_id'],'FStatus.submission_no = FMemo.submission_no',
														"NOT EXISTS (SELECT * FROM f_statuses WHERE f_statuses.reviewer_id < FReviewer.reviewer_id AND f_statuses.memo_id = FStatus.memo_id AND f_statuses.status ='pending')")
									,'order'=>array('FMemo.created DESC'))); // this is truee laaa -- just need to make sure when equal to same find,use equal like in submission_no
		$this->set('pendingMemo',$pendingMemo);
		// debug($pendingBudget);exit();

		$this->FStatus->contain(array(
								'FMemo',
								'FMemo.User'=>array('fields'=>array('staff_name')),
								'FMemo.FStatus',
								'FMemo.Department'=>array('fields'=>array('department_name')),
								'FMemo.Department.Group'=>array('fields'=>array('group_name')),
								'FReviewer',
							)
						);
		$reviewedMemo = $this->FStatus->find('all',array('conditions'=>array(
													"OR"=>array(array("FStatus.status"=>"approved"),array("FStatus.status"=>"rejected")),
													"FStatus.submission_no = FMemo.submission_no",
													"FReviewer.user_id"=>$user["user_id"]
												),'order'=>array('FMemo.created DESC')));
		$this->set('reviewedMemo',$reviewedMemo);

			// setting 
		$setting = $this->Setting->find('first');
		$this->set('setting',$setting);	
		
	}

	/*

	*	request()

	*	new memo form 

	*	@ Aisyah

	*/

	public function request($memo_id=null,$edit=null){
		$user = $this->getAuth();
		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}
		//check user privilege, by default disable all access
		$editFlag=false;
		$financeFlag=false;
   		//find user list
   		$this->User->recursive = -1;
		$staffs=$this->User->find('list',array('conditions'=>array('User.status'=>'enabled')));
		$this->set('staffs',$staffs);	
		
		$selected=array();
		$years=array();
		$deptAcad=array();
		$deptNonAcad=array();
		$items=array();
		
		//if (!empty($memo_id)){
			
			if (($this->request->is('post')||$this->request->is('put'))&&$memo_id=='new'){
				if($user['requestor']){
					if (!empty($this->request->data['FMemo']['subject'])){
						$this->request->data['FMemo']['user_id']=$user['user_id'];
						$this->request->data['FMemo']['department_id']=$user['department_id'];
						$this->request->data['FMemo']['submission_no']=0;
						if ($this->request->data['FMemo']['date_required']!='')
							$this->request->data['FMemo']['date_required']=date('Y-m-d',strtotime($this->request->data['FMemo']['date_required']));
						$this->FMemo->create();
						$this->FMemo->save($this->request->data);
						$memo_id=$this->FMemo->id;


						//after saving memo, save 2 default users to fmemoto
						$this->User->recursive=-1;
						$defaultTo=$this->User->find('all',array('conditions'=>array('staff_id'=>array('iskandarsham','niksharifidin'))));
						if (!empty($defaultTo)){

							foreach ($defaultTo as $value) {
								$this->request->data['FMemoTo']['user_id']=$value['User']['user_id'];
								$this->request->data['FMemoTo']['memo_id']=$memo_id;
								$this->FMemoTo->create();
								$this->FMemoTo->save($this->request->data);
							}
						}
						
					}

					else{
						
						$this->Session->setFlash(__('<b>Please fill in all required fields.</b>'),'flash.error');
						return $this->redirect($this->referer());
					}
				}
				else{

					$this->Session->setFlash(__('<b>You are not authorized to create/edit the memo.</b>'),'flash.error');
					return $this->redirect(array('controller'=>'User','action'=>'userDashboard'));

				}
			}

			else{
				$memo_id=$this->decrypt($memo_id);
			}

			if (!$this->FMemo->exists($memo_id)) {

				throw new ForbiddenException();

			}
			//else{
			$encMemoID=$this->encrypt($memo_id);

			$this->FMemo->contain(array('FMemoBudget'=>array('BItemAmount'=>array('Item'),'BItemAmountTransfer'=>array('fields'=>''),'BItemAmountTransfer.Item'=>array('fields'=>'item'),'BItemAmountTransfer.BDepartment.Department'=>array('fields'=>'department_shortform'),'Budget'),'FVendorAttachment','Department','User'=>array('fields'=>'staff_name','designation')));
			$memo=$this->FMemo->find('first',array('conditions'=>array('FMemo.memo_id'=>$memo_id)));
			
			$this->FStatus->contain('FReviewer');
			$status=$this->FStatus->find('all',array('conditions'=>array('FStatus.memo_id'=>$memo_id,'FStatus.submission_no'=>$memo['FMemo']['submission_no']),'order'=>array('FReviewer.sequence'=> 'ASC')));
			//if requestor, allow access only if save/any reviewer/recommender/finance reject
			if ($user['user_id']==$memo['FMemo']['user_id']){
				if ($memo['FMemo']['submission_no']==0)
						$editFlag=true;
				else{

					foreach ($status as $value) {

					//if ($value['FStatus']['status']=='rejected'&&$value['FReviewer']['approval_type']!='approver'){
						if ($value['FStatus']['status']=='rejected'&&$value['FReviewer']['approval_type']!='approver'){
							$editFlag=true;
							break;
						}
					}
				}	
			}

			else{//if user is one of the reviewers
				for($i=0;$i<count($status);$i++){

					if($status[$i]['FReviewer']['user_id']==$user['user_id']){
						
						if ($status[$i]['FStatus']['status']=='pending'){
							if ($i==0||$status[$i-1]['FStatus']['status']=='approved'){//first reviewer or status before is approved
								$editFlag=true;
								//phase 2:set financeFlag to true if finance is reviewing
								if ($status[$i]['FReviewer']['approval_type']=='finance')
									$financeFlag=true;

								break;
							}
							
						}
					}
					
				}

			}
			
			if ($editFlag){
				$this->request->data=$memo;
				//datepicker not recognizing the Y-m-d format fix
				$this->request->data['FMemo']['date_required']=date('d-m-Y',strtotime($this->request->data['FMemo']['date_required']));
				$department_id=$memo['FMemo']['department_id'];
				
				//selected staff
				$this->FMemoTo->contain(array('User'=>array('fields'=>array('User.user_id','User.staff_name','User.designation'))));
				$selectedStaff=$this->FMemoTo->find('all',array('conditions'=>array('FMemoTo.memo_id'=>$memo_id)));
				if (!empty($selectedStaff)){
					foreach ($selectedStaff as $value) {
						$selected[]=$value['FMemoTo']['user_id'];
					}
					
				}

				$this->set('department_id',$department_id);	
				

				//find year for budget
				$this->Group->recursive=-1;
				$company=$this->Group->find('first', array('conditions'=>array('Group.group_id'=>$memo['Department']['group_id'])));
		   		 $this->FMemoBudget->recursive = -1;
		   		 //phase 2:if memo budget already exist for this memo, allow only next memo budget from the same year
		   		$memoBudgetExist=$this->FMemoBudget->find('first',array('conditions'=>array('memo_id'=>$memo_id)));
		   		if (!empty($memoBudgetExist)){
		   			$budget_id=$memoBudgetExist['FMemoBudget']['budget_id'];
		   			$years=$this->Budget->find('list',array('conditions'=>array('Budget.budget_id'=>$budget_id)));
		   			//set item list from only that budget
		   			$this->BItemAmount->contain(array('Item'=>array()));
					
					$itemList=$this->BItemAmount->find('all',array('conditions'=>array('BItemAmount.budget_id'=>$budget_id,'BItemAmount.b_dept_id IS NULL'),'fields'=>array('BItemAmount.item_id','Item.item','Item.item_code')));
					if (!empty($itemList)):
						foreach ($itemList as  $v) {
							$items[$v['Item']['item_id']]=$v['Item']['item_code'].' - '.$v['Item']['item'];
						}
					endif;

					
					$this->BDepartment->contain(array('Department'));
					$deptAcad = $this->BDepartment->find('list',array('fields'=>array('BDepartment.b_dept_id','Department.department_name'),'conditions'=>array('BDepartment.department_type'=>'1','BDepartment.budget_id'=>$budget_id),'order'=>'BDepartment.b_dept_id ASC'));
					$this->BDepartment->contain(array('Department'));
					$deptNonAcad = $this->BDepartment->find('list',array('fields'=>array('BDepartment.b_dept_id','Department.department_name'),'conditions'=>array('BDepartment.department_type'=>'2','BDepartment.budget_id'=>$budget_id),'order'=>'BDepartment.b_dept_id ASC'));
								//debug ($items);exit;
		   		}
		   		else//else get all approved budget years
					$years=$this->Budget->find('list',array('conditions'=>array('Budget.company_id'=>$company['Group']['company_id'],'Budget.budget_status'=>'approved'),'order'=>'Budget.year DESC'));

				$this->set('years',$years);	
				$this->set('financeFlag',$financeFlag);	
				$this->set('edit',$edit);	
				$this->set('memo_id',$encMemoID);	
				$this->set('selected',$selected);
				$this->set('selectedStaff',$selectedStaff);
				$this->set('deptNonAcad',$deptNonAcad);
				$this->set('deptAcad',$deptAcad);

				

				$this->set('items',$items);

			}

			else{

				$this->Session->setFlash(__('<b>You are not authorized to create/edit the memo.</b>'),'flash.error');
				return $this->redirect(array('controller'=>'User','action'=>'userDashboard'));
				
				
			}
		//}

		// else{

		// 	$this->Session->setFlash(__('<b>Please contact system administrator.</b>'),'flash.error');
		// 	return $this->redirect($this->referer());

		// }
	}

	public function confirm($memo_id){

		$user = $this->getAuth();

		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$memo_id=$this->decrypt($memo_id);
		//check user privilege, by default disable all buttons
		if (!$this->FMemo->exists($memo_id)) {

			throw new ForbiddenException();

		}
		$this->FMemo->contain(array('Department'=>array('fields'=>array('group_id'))));
		$memo=$this->FMemo->find('first',array('conditions'=>array('FMemo.memo_id'=>$memo_id)));
		//if requestor, allow access only if save/any reviewer/recommender/finance reject
		if ($user['user_id']!=$memo['FMemo']['user_id']||$memo['FMemo']['submission_no']!=0){
			$this->Session->setFlash(__('<b>You are not authorized to create/edit the memo.</b>'),'flash.error');
			return $this->redirect(array('controller'=>'User','action'=>'userDashboard'));
			
		}
					
		$this->set('memo_id',$memo_id);

		$budget=$this->FMemoBudget->find('all',array('conditions'=>array('FMemoBudget.memo_id'=>$memo_id),'fields'=>array('sum(FMemoBudget.amount) AS total' )));
		if(empty($budget[0][0]['total']))
			$budget[0][0]['total']=0;
		
		//select all reviewer under requestor department for filtering reviewer
		//$reviewers = $this->User->find('list',array('conditions'=>array('User.reviewer'=>1,'User.status'=>'enabled','User.department_id'=>$memo['FMemo']['department_id'])));
		$reviewers = $this->User->find('list',array('conditions'=>array('User.reviewer'=>1,'User.status'=>'enabled')));

		$this->set('reviewers',$reviewers);
		//select all recommender under requestor group for filtering recommender
		//$this->Department->recursive=-1;
		// $depts=$this->Department->find('all',array('conditions'=>array('Department.group_id'=>$memo['Department']['group_id']),'fields'=>array('department_id')));
		// $dept=array();
		// foreach ($depts as $value) {
		// 	$dept[]=$value['Department']['department_id'];
		// }
		
		// $recommenders = $this->User->find('list',array('conditions'=>array('User.recommender'=>1,'User.status'=>'enabled','User.department_id'=>$dept)));
		$recommenders = $this->User->find('list',array('conditions'=>array('User.recommender'=>1,'User.status'=>'enabled')));

		$this->set('recommenders',$recommenders);
		$finances = $this->User->find('list',array('conditions'=>array('User.finance'=>1,'User.status'=>'enabled','User.user_id NOT'=>$user['user_id'])));
		$this->set('finances',$finances);
		$approvers = $this->User->find('list',array('conditions'=>array('User.approver'=>1,'User.status'=>'enabled','User.user_id NOT'=>$user['user_id'],'User.loa >='=>$budget[0][0]['total'])));
		$this->set('approvers',$approvers);

	}

	public function prevSubmission($memo_id=null,$submission_no=null){
		$user = $this->getAuth();
		$this->set('memo_id',$memo_id);
		$memo_id=$this->decrypt($memo_id);
		if (!$this->FMemo->exists($memo_id)) {

			throw new ForbiddenException();

		}
		$reviewer=$this->getReviewer($memo_id,'reviewer',$submission_no);
 		$recommender=$this->getReviewer($memo_id,'recommender',$submission_no);
 		$finance=$this->getReviewer($memo_id,'finance',$submission_no);
 		$approver=$this->getReviewer($memo_id,'approver',$submission_no);
		
		$remark_reviewer=array();
		$remark_recommender=array();
		$remark_finance=array();
		$remark_approver=array();
		
		if (!empty($reviewer)){
			foreach ($reviewer as $value) {
				
				$remark_reviewer[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		if (!empty($recommender)){
			foreach ($recommender as $value) {
				
				$remark_recommender[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		if (!empty($finance)){
			foreach ($finance as $value) {
				
				$remark_finance[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		if (!empty($approver)){
			foreach ($approver as $value) {
				
				$remark_approver[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		$this->set('reviewer',$reviewer);
 		$this->set('recommender',$recommender);
 		$this->set('finance',$finance);
 		$this->set('approver',$approver);
 		$this->set('remark_reviewer',$remark_reviewer);
 		$this->set('remark_recommender',$remark_recommender);
 		$this->set('remark_finance',$remark_finance);
 		$this->set('remark_approver',$remark_approver);

	}
	/*

	*	remark()

	*	< description of function >

	*	@ < author of function editor >

	*/

	

	public function review($memo_id=null){
		$user = $this->getAuth();
		$encMemoID=$memo_id;
		$memo_id=$this->decrypt($memo_id);
		if (!$this->FMemo->exists($memo_id)) {

			throw new ForbiddenException();

		}
		$userid = $user['user_id'];
		$memo = $this->FMemo->find('first',array(
			'conditions' => array('FMemo.memo_id' => $memo_id),
			'contain'=>array(
				'User'=>array('fields'=>array('staff_name','designation'),'Department'=>array('fields'=>array('department_name'))
				),
				'FReviewer',
				'FVendorAttachment',
				'FMemoTo'=>array('User'=>array('fields'=>array('User.staff_name','User.staff_id','User.designation'))),
				// 'FMemoBudget'=>array('fields'=>''),
				'FMemoBudget.Budget'=>array('fields'=>'Budget.year'),
				// 'FMemoBudget.BItemAmountTransfer'=>array('fields'=>''),
				'FMemoBudget.BItemAmountTransfer.Item'=>array('fields'=>'item'),
				'FMemoBudget.BItemAmountTransfer.BDepartment.Department'=>array('fields'=>'department_shortform'),
				'FMemoBudget.BItemAmount.Item'=>array('fields'=>'Item.code_item'),
				
			)

		));
		$submission_no=$memo['FMemo']['submission_no'];
		//debug($memo);exit;
		//check user privilege, by default disable all buttons
		$editFlag=false;
		$approvalFlag=false;
		$remarkFlag=false;
		$financeFlag=false;
		$commentFlag=false;

		$this->FStatus->contain('FReviewer');
		$status=$this->FStatus->find('all',array('conditions'=>array('FStatus.memo_id'=>$memo_id,'FStatus.submission_no'=>$submission_no),'order'=>array('FReviewer.sequence'=> 'ASC')));
		//find status before to check if it got rejected by approver
		$approverReject=$this->FStatus->find('first',array('conditions'=>array('FStatus.memo_id'=>$memo_id,'FStatus.submission_no'=>$submission_no-1,'FReviewer.approval_type'=>'approver','FStatus.status'=>'rejected')));
		
		if ($user['user_id']==$memo['FMemo']['user_id']){//if user is the memo requestor,only allow edit if the current submission status if any of reviewer reject
			
			foreach ($status as $value) {
				//if ($value['FStatus']['status']=='rejected'&&$value['FReviewer']['approval_type']!='approver'){
				if ($value['FStatus']['status']=='rejected'&&$value['FReviewer']['approval_type']!='approver'){
					$editFlag=true;
					break;
				}
			}
			
			$remarkFlag=true;
			$commentFlag=true;
		}

		// else{//if user is one of the reviewers  //disabled because user can be reviewer
			for($i=0;$i<count($status);$i++){

				if($status[$i]['FReviewer']['user_id']==$user['user_id']){
					$commentFlag=true;
					$remarkFlag=true;

					

					if ($status[$i]['FStatus']['status']=='pending'){
						if ($i==0||$status[$i-1]['FStatus']['status']=='approved'){//first reviewer or status before is approved
							$editFlag=true;
							$approvalFlag=true;

							if ($status[$i]['FReviewer']['approval_type']=='finance')
								$financeFlag=true;
							

							break;
						}
						// else{
						// 	if($status[$i-1]['FStatus']['status']=='approved'){
						// 		$editFlag=true;
						// 		$approvalFlag=true;
						// 		break;
						// 	}
						// }
					}
				}
				
			}

		// }

		foreach ($memo['FMemoTo'] as $value) {
			//if ($value['FStatus']['status']=='rejected'&&$value['FReviewer']['approval_type']!='approver'){
			if ($value['user_id']==$user['user_id']){
				$commentFlag=true;
				break;
			}
		}
		//phase 2: check if memo is disabled , if yes disable edit/approval
		$setting = $this->Setting->find('first');
		if($setting['Setting']['financial_memo']){
			$editFlag=false;
			$approvalFlag=false;

		}
		//debug($memo);exit;
 		$this->set('memo',$memo);
 		$this->set('approverReject',$approverReject);
 		$this->set('editFlag',$editFlag);
 		$this->set('remarkFlag',$remarkFlag);
 		$this->set('approvalFlag',$approvalFlag);
 		$this->set('financeFlag',$financeFlag);
 		$this->set('commentFlag',$commentFlag);
 		//debug($memo);exit;
 		$reviewer=$this->getReviewer($memo_id,'reviewer',$submission_no);
 		$recommender=$this->getReviewer($memo_id,'recommender',$submission_no);
 		$finance=$this->getReviewer($memo_id,'finance',$submission_no);
 		$approver=$this->getReviewer($memo_id,'approver',$submission_no);
		
		$remark_reviewer=array();
		$remark_recommender=array();
		$remark_finance=array();
		$remark_approver=array();
		
		if (!empty($reviewer)){
			foreach ($reviewer as $value) {
				
				$remark_reviewer[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		if (!empty($recommender)){
			foreach ($recommender as $value) {
				
				$remark_recommender[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		if (!empty($finance)){
			foreach ($finance as $value) {
				
				$remark_finance[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		if (!empty($approver)){
			foreach ($approver as $value) {
				
				$remark_approver[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		$this->set('reviewer',$reviewer);
 		$this->set('recommender',$recommender);
 		$this->set('finance',$finance);
 		$this->set('approver',$approver);
 		$this->set('remark_reviewer',$remark_reviewer);
 		$this->set('remark_recommender',$remark_recommender);
 		$this->set('remark_finance',$remark_finance);
 		$this->set('remark_approver',$remark_approver);
 		$this->set('memo_id',$encMemoID);
 		$this->request->data['FMemo']['budgeted']=$memo['FMemo']['budgeted'];
		
 	
	}

	/*

	*	add()

	*	add and save financial memo data without validation

	*	@ Aisyah

	*/

#endregion

#region "CRUD"

	public function addMemo($memo_id=null,$edit=null){
		
		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$user = $this->getAuth();
		$encMemoID=$memo_id;
		
		$memo_id=$this->decrypt($memo_id);

		//debug($this->request->data);exit;
		if (!$this->FMemo->exists($memo_id)) {

			throw new ForbiddenException();

		}
		if ($this->request->is('post')||$this->request->is('put')){
			
			$this->FMemo->id=$memo_id;
			//$this->request->data['FMemo']['user_id']=$user['user_id'];
			//$this->request->data['FMemo']['submission_no']=0;
			if ($this->request->data['FMemo']['date_required']!='')
				$this->request->data['FMemo']['date_required']=date('Y-m-d',strtotime($this->request->data['FMemo']['date_required']));
			if ($this->FMemo->save($this->request->data)){
				$memo_id=$this->FMemo->id;

				if(!empty($this->request->data['FVendorAttachment']['files'])){
					if (!(count($this->request->data['FVendorAttachment']['files'])==1&&empty($this->request->data['FVendorAttachment']['files'][0]['tmp_name']))){
						// assume filetype is false
						$typeOK = false;
						// list of permitted file types, 
						$permitted = array('application/msword','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf');

						foreach ($this->request->data['FVendorAttachment']['files'] as $value) {
							$file = $value;

							$filename = null;

							// check filetype is ok
							foreach($permitted as $type) {
								if($type == $file['type']) {
									$typeOK = true;
									break;
								}
							}
							
							if ($typeOK){
								//check filesize less than 10mb
								if ($file['size'] <= 10485760){


						            if(!empty($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
						                 $filename = time().'___'.basename($file['name']); 
						                 if(move_uploaded_file($file['tmp_name'], WWW_ROOT.'files'.DS.'fvendor-attachment'.DS.$filename)){

						                 	$this->FVendorAttachment->create();
								            $this->request->data['FVendorAttachment']['filename'] = $filename;
								            $this->request->data['FVendorAttachment']['memo_id'] = $memo_id;
								            $this->FVendorAttachment->save($this->request->data);
						                 }
						            }
						            else{

							        	$this->Session->setFlash(__('<b>'.basename($file['name']).' cannot be uploaded. Please try again.</b>'),'flash.error');
										return $this->redirect(array('action'=>'request',$encMemoID,$edit));
							        }
						        }

						        else{

							        	$this->Session->setFlash(__('<b>'.basename($file['name']).' is too big. Maximum file size is 10Mb.</b>'),'flash.error');
										return $this->redirect(array('action'=>'request',$encMemoID,$edit));
							        }
					        }

					        else{

					        	$this->Session->setFlash(__('<b>'.basename($file['name']).' cannot be uploaded. Acceptable file types: pdf, word, excel.</b>'),'flash.error');
								return $this->redirect(array('action'=>'request',$encMemoID,$edit));
					        }
			        	}
			        }
				}
				$this->set('memo_id',$encMemoID);
				$this->set('edit',$edit);	
				 if (isset($this->request->data['save'])){
				 	$this->Session->setFlash(__('<b>Memo has been saved.</b>'),'flash.success');
					return $this->redirect(array('action'=>'request',$encMemoID,$edit));
				 	//return $this->redirect(array('action'=>'index'));

				 }
				
			}
		}
		return $this->redirect(array('action'=>'request',$encMemoID,$edit));

	}

	/*

	*	validateMemo()

	*	< save memo data and validate no of attachment and staff are filled >

	*	@ < author of function editor >

	*/

	public function validateMemo($memo_id=null,$edit=null){
		
		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$user = $this->getAuth();
		//$memo_id=$this->decrypt($memo_id);

		if (!$this->FMemo->exists($memo_id)) {

			throw new ForbiddenException();

		}
		//debug($this->request->data);exit;
		if ($this->request->is('post')||$this->request->is('put')){
			$this->FMemo->id=$memo_id;
			$encMemoID=$this->encrypt($memo_id);
			if ($this->request->data['FMemo']['date_required']!='')
				$this->request->data['FMemo']['date_required']=date('Y-m-d',strtotime($this->request->data['FMemo']['date_required']));

			if ($this->FMemo->save($this->request->data)){
				$memo_id=$this->FMemo->id;
				$this->set('memo_id',$encMemoID);
				$errorMsg='';
				$errorFlag=false;
				//Upload the files
				
				if(!empty($this->request->data['FVendorAttachment']['files'])){
					if (!(count($this->request->data['FVendorAttachment']['files'])==1&&empty($this->request->data['FVendorAttachment']['files'][0]['tmp_name']))){
						// assume filetype is false
						$typeOK = false;
						// list of permitted file types, 
						$permitted = array('application/msword','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf');

						foreach ($this->request->data['FVendorAttachment']['files'] as $value) {
							$file = $value;

							$filename = null;

							// check filetype is ok
							foreach($permitted as $type) {
								if($type == $file['type']) {
									$typeOK = true;
									break;
								}
							}
							
							if ($typeOK){
								//check filesize less than 10mb
								if ($file['size'] <= 10485760){


						            if(!empty($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
						                 $filename = time().'___'.basename($file['name']); 
						                 if(move_uploaded_file($file['tmp_name'], WWW_ROOT.'files'.DS.'fvendor-attachment'.DS.$filename)){

						                 	$this->FVendorAttachment->create();
								            $this->request->data['FVendorAttachment']['filename'] = $filename;
								            $this->request->data['FVendorAttachment']['memo_id'] = $memo_id;
								            $this->FVendorAttachment->save($this->request->data);
						                 }
						            }
						            else{
						            	$errorMsg=$errorMsg.'<b>'.basename($file['name']).' cannot be uploaded. Please try again.</b><br>';
						            	$errorFlag=true;
							   //      	$this->Session->setFlash(__('<b>'.basename($file['name']).' cannot be uploaded. Please try again.</b>'),'flash.error');
										// return $this->redirect(array('action'=>'request',$encMemoID,$edit));
							        }
						        }

						        else{
						        		$errorMsg=$errorMsg.'<b>'.basename($file['name']).' is too big. Maximum file size is 10Mb.</b><br>';
						            	$errorFlag=true;
							   //      	$this->Session->setFlash(__('<b>'.basename($file['name']).' is too big. Maximum file size is 10Mb.</b>'),'flash.error');
										// return $this->redirect(array('action'=>'request',$encMemoID,$edit));
							        }
					        }

					        else{
					        	$errorMsg=$errorMsg.'<b>'.basename($file['name']).' cannot be uploaded. Acceptable file types: pdf, word, excel.</b><br>';
						        $errorFlag=true;
					   //      	$this->Session->setFlash(__('<b>'.basename($file['name']).' cannot be uploaded. Acceptable file types: pdf, word, excel.</b>'),'flash.error');
								// return $this->redirect(array('action'=>'request',$encMemoID,$edit));
					        }
			        	}
			        }
				}

				//check if at least one staff to send memo to has been added
				$to=$this->FMemoTo->find('all',array('conditions'=>array('FMemoTo.memo_id'=>$memo_id)));
				//check for new vendor has to attach min 3 quotations
				$attachmentCount=$this->FVendorAttachment->find('count',array('conditions'=>array('FVendorAttachment.memo_id'=>$memo_id)));

				if (empty($to)){
					$errorMsg=$errorMsg.'<b>Please select at least one staff to send memo to.</b><br>';
					$errorFlag=true;
					// $this->Session->setFlash(__('<b>Please select at least one staff to send memo to.</b>'),'flash.error');
					// return $this->redirect(array('action'=>'request',$encMemoID,$edit));
				}

				
				if (empty($this->request->data['FMemo']['subject'])){
					$errorMsg=$errorMsg.'<b>Please fill in all required fields.</b><br>';
					$errorFlag=true;
					// $this->Session->setFlash(__('<b>Please select at least one staff to send memo to.</b>'),'flash.error');
					// return $this->redirect(array('action'=>'request',$encMemoID,$edit));
				}

				//check if at least one memo budget has been added
				//to be uncommented later when budget memo is compulsary
				/*$budgetCount=$this->FMemoBudget->find('count',array('conditions'=>array('FMemoBudget.memo_id'=>$memo_id)));

				if($budgetCount<=0){
					$errorMsg=$errorMsg.'<b>Please add at least one (1) budget item for this memo.</b><br>';
					$errorFlag=true;
				}
				*/

	        	if (($this->request->data['FMemo']['vendor']=='0')&&($attachmentCount<3)){
	        		$errorMsg=$errorMsg.'<b>Please attach at least three (3) quotations for new vendor.</b><br>';
					$errorFlag=true;
					// $this->Session->setFlash(__('<b>Please attach at least three (3) quotations for new vendor.</b>'),'flash.error');
					// return $this->redirect(array('action'=>'request',$encMemoID,$edit));
				}
				
				if ($errorFlag){
					$this->Session->setFlash(__($errorMsg),'flash.error');
					return $this->redirect(array('action'=>'request',$encMemoID,$edit));
				}
				else{
					//debug($edit);exit;
					if ($edit){

						//check the relationship of user and memo 
						$this->FMemo->contain(array('FReviewer','Department'));
						$memo=$this->FMemo->findByMemoId($memo_id);

						$this->FStatus->contain('FReviewer');
						$status=$this->FStatus->find('all',array('conditions'=>array('FStatus.memo_id'=>$memo_id,'FStatus.submission_no'=>$memo['FMemo']['submission_no']),'order'=>array('FReviewer.sequence'=> 'ASC')));
						$resubmitFlag=false;
						//if requestor, allow access only if save/any reviewer/recommender/finance reject
						if ($user['user_id']==$memo['FMemo']['user_id']){
							// if ($memo['FMemo']['submission_no']==0)
							// 		$resubmitFlag=true;
							// else{

								foreach ($status as $value) {
								//if ($value['FStatus']['status']=='rejected'&&$value['FReviewer']['approval_type']!='approver'){
									if ($value['FStatus']['status']=='rejected'&&$value['FReviewer']['approval_type']!='approver'){
										$resubmitFlag=true;
										break;
									}
								}
							//}	
						}

						// //else{//if user is one of the reviewers
						// 	for($i=0;$i<count($status);$i++){

						// 		if($status[$i]['FReviewer']['user_id']==$user['user_id']){
									
						// 			if ($status[$i]['FStatus']['status']=='pending'){
						// 				if ($i==0||$status[$i-1]['FStatus']['status']=='approved'){//first reviewer or status before is approved
						// 					$editFlag=true;
						// 					break;
						// 				}
										
						// 			}
						// 		}
								
						// 	}

						if($resubmitFlag){//user is the creator of the memo & there is rejected status which is not from approver, thus update submission no and reinsert status for another round of approval
							$this->FMemo->id=$memo_id;
							$submission_no=$memo['FMemo']['submission_no']+1;
							$this->FMemo->saveField('submission_no',$submission_no);

							foreach ($memo['FReviewer'] as $value) {
								
								//$id = $value['reviewer_id'];
								$status['FStatus']['reviewer_id'] = $value['reviewer_id'];
								$status['FStatus']['status'] = 'pending';
								$status['FStatus']['submission_no'] = $submission_no;
								$status['FStatus']['memo_id'] = $memo_id;
								$this->FStatus->create();
								$this->FStatus->save($status);
					
							}

							// send email to reviewer
							$this->sendReviewEmail($memo_id);

							// add notification to the requestor -- stating the memo has been created
							$notiTo = $memo['FMemo']['user_id'];
							// $notiText = "You have re-submitted a financial memo request";
							$notiText = "<b> Ref. No : </b> ".$memo['FMemo']['ref_no']. "<br>".
										"<b> Subject : </b> ".$memo['FMemo']['subject']."<br>".
										"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
										"<b> Info : </b> Memo resubmitted";

							$notiLink = array('controller'=>'FMemo','action'=>'dashboard',$encMemoID);
							//$this->UserNotification->record($notiTo, $notiText, $notiLink);

							#code update for ememo2
							$notiType = "memo"; 
                            $this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
                            #end code update
										
							$this->Session->setFlash(__('<b>Memo has been re-submitted for review.</b>'),'flash.success');

							return $this->redirect(array('controller'=>'FMemo','action'=>'dashboard',$encMemoID));
						}

						else{//user is one of the reviewer/approver/finance/recommender

							$this->Session->setFlash(__('<b>Memo has been updated successfully.</b>'),'flash.success');

							return $this->redirect(array('controller'=>'FMemo','action'=>'dashboard',$encMemoID));

						}
					}

					else{//first submission so go to add reviewer page
						return $this->redirect(array('action'=>'confirm',$encMemoID));
					}
					
				}
			}

		}

	}

	/*

	*	addStaff()

	*	< description of function >

	*	@ < author of function editor >

	*/

	public function addStaff($memo_id=null,$edit=null){
		
		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$user = $this->getAuth();
		$encMemoID=$memo_id;
		$memo_id=$this->decrypt($memo_id);

		if (!$this->FMemo->exists($memo_id)) {

			$this->Session->setFlash(__('<b>Invalid id.</b>'),'flash.error');

		}
		if ($this->request->is('post')||$this->request->is('put')){
		
				$to=$this->FMemoTo->find('all',array('conditions'=>array('FMemoTo.memo_id'=>$memo_id)));
					
				//delete first all staff before re-add to ensure no redundancy
				if (!empty($to)){

					foreach ($to as $value) {
						$this->FMemoTo->delete($value['FMemoTo']['to_id']);
					}
				}
				//save selected staff 
				if(!empty($this->request->data['FMemoTo']['selectedStaff'])){
					
					foreach ($this->request->data['FMemoTo']['selectedStaff'] as $value) {
						$this->request->data['FMemoTo']['user_id']=$value;
						$this->request->data['FMemoTo']['memo_id']=$memo_id;
						$this->FMemoTo->create();
						$this->FMemoTo->save($this->request->data);

					}

				}

				$this->Session->setFlash('<b>Verifier(s) has been successfully added/removed.</b>','flash.success');

		}

		return $this->redirect(array('action'=>'request',$encMemoID,$edit));
		

	}

	/*

	*	addItem()

	*	add budget item to financial memo  

	*	@ Aisyah

	*/

	public function addBudget($memo_id=null,$edit=null){
		
		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$user = $this->getAuth();
		$encMemoID=$memo_id;
		$memo_id=$this->decrypt($memo_id);
		if (!$this->FMemo->exists($memo_id)) {

			$this->Session->setFlash(__('<b>Invalid id.</b>'),'flash.error');

		}
		if ($this->request->is('post')||$this->request->is('put')){
			
				//add budget item
				if (!empty($this->request->data['FMemoBudget']['budget_id'])&&!empty($this->request->data['FMemoBudget']['item_amount_id'])){
					//phase 2:check first if budget item has been added before for the memo
					$this->FMemoBudget->recursive=-1;
					$itemExist=$this->FMemoBudget->find('first',array('conditions'=>array('FMemoBudget.memo_id'=>$memo_id,'FMemoBudget.item_amount_id'=>$this->request->data['FMemoBudget']['item_amount_id'])));
					if(!empty($itemExist)){
						$this->Session->setFlash(__('<b>Item already exist. Please consider editing the amount instead.</b>'),'flash.error');
						$this->redirect(array('action'=>'request',$encMemoID,$edit));

					}
					$this->FMemoBudget->create();
					$this->request->data['FMemoBudget']['memo_id']=$memo_id;
					if($this->FMemoBudget->save($this->request->data))
						$this->Session->setFlash(__('<b>Budget has been added to memo successfully.</b>'),'flash.success');
				}

				else
					$this->Session->setFlash(__('<b>Please fill in all fields.</b>'),'flash.error');
		}

		return $this->redirect(array('action'=>'request',$encMemoID,$edit));
		 
		
	}

	/*
	*	deleteBudget
	*	< delete budget item from financial memo form >
	*	@ Aisyah
	*/
	public function deleteBudget($memo_budget_id=null){
		
		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$memo_budget_id=$this->decrypt($memo_budget_id);
		
		if (!$this->FMemoBudget->exists($memo_budget_id)) {

			$this->Session->setFlash(__('<b>Invalid id.</b>'),'flash.error');

		}

		if($this->FMemoBudget->delete($memo_budget_id))
			$this->Session->setFlash(__('<b>Budget item has been deleted successfully.</b>'),'flash.success');
		else
			$this->Session->setFlash(__('<b>Failed to delete budget item. Please try again.</b>'),'flash.error');


			$this->redirect($this->referer());	
	}

	/*
	*	deleteMemo
	*	< delete budget item from financial memo form >
	*	@ Aisyah
	*/
	public function deleteMemo($memo_id=null){

		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$memo_id=$this->decrypt($memo_id);
		$memo = $this->FMemo->findByMemoId($memo_id); //debug($memo); exit;
		
		if (!$this->FMemo->exists($memo_id)) {

			$this->Session->setFlash(__('<b>Invalid memo.</b>'),'flash.error');

		}

		// debug($this->request->params); exit();
		#ememo2 :: if memo submitted,  send email first before delete 
		if($memo['FMemo']['submission_no']>0){			
 			$this->sendDeleteEmail($memo_id);
		}

		if($this->FMemo->delete($memo_id)){
				$this->Session->setFlash(__('<b>Memo has been deleted successfully.</b>'),'flash.success');

		}
		else{
			$this->Session->setFlash(__('<b>Fail to delete memo. Please try again.</b>'),'flash.error');
		}
		//retain filter data
		$groupData='';
        $deptData='';
        $userData='';
        $fromData='';
        $toData='';

		 if (!empty($this->request->params['named']['div'])){
            $groupData=$this->request->params['named']['div']; 
        }

        if (!empty($this->request->params['named']['dept'])){
           $deptData=$this->request->params['named']['dept']; 
        }

        if (!empty($this->request->params['named']['user'])){
           $userData=$this->request->params['named']['user']; 
        }

        if (!empty($this->request->params['named']['from'])){
            $fromData=$this->request->params['named']['from']; 
        }

        if (!empty($this->request->params['named']['to'])){
           $toData=$this->request->params['named']['to']; 
        }

        if (!empty($this->request->params['named']['view'])){
            
            $viewData=$this->request->params['named']['view']; 
            $this->redirect(array('action'=>$viewData,'div'=>$groupData,'dept'=>$deptData,'user'=>$userData,'from'=>$fromData,'to'=>$toData));
        }
        else
        	$this->redirect($this->referer());
		
	}
	/*
	*	deleteMemoMulti
	*	< delete budget item from financial memo form >
	*	@ Aisyah
	*/
	public function deleteMemoMulti(){
		// debug($this->request->data);exit;

		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		if (!empty($this->request->data['memoid'])):
			foreach ($this->request->data['memoid'] as $id) {
				$memo_id=$this->decrypt($id);
				$memo = $this->FMemo->findByMemoId($memo_id); //debug($memo); exit;
				#ememo2 :: if memo submitted,  send email first before delete 
				if($memo['FMemo']['submission_no']>0){			
		 			$this->sendDeleteEmail($memo_id);
				}

				if($this->FMemo->delete($memo_id)){
					$this->Session->setFlash(__('<b>Memos have been deleted successfully.</b>'),'flash.success');
				}
				else{
					$this->Session->setFlash(__('<b>Fail to delete memo. Please try again.</b>'),'flash.error');
					break;
				}
			}
		endif;
		
		//retain filter data
		$groupData='';
        $deptData='';
        $userData='';
        $fromData='';
        $toData='';

        if (!empty($this->request->params['named']['div'])){
            $groupData=$this->request->params['named']['div']; 
        }

        if (!empty($this->request->params['named']['dept'])){
           $deptData=$this->request->params['named']['dept']; 
        }

        if (!empty($this->request->params['named']['user'])){
           $userData=$this->request->params['named']['user']; 
        }

        if (!empty($this->request->params['named']['from'])){
            $fromData=$this->request->params['named']['from']; 
        }

        if (!empty($this->request->params['named']['to'])){
           $toData=$this->request->params['named']['to']; 
        }

		if (!empty($this->request->params['named']['view'])){
            
            $viewData=$this->request->params['named']['view']; 
            $this->redirect(array('action'=>$viewData,'div'=>$groupData,'dept'=>$deptData,'user'=>$userData,'from'=>$fromData,'to'=>$toData));
        }
        else
        	$this->redirect($this->referer());
		
	}



	/*
	*	Name: yearChange
	*	Description: Ajax call to get the quarter
	*	Parameters: year
	*	Return: XXX
	*	Author: Aisyah
	*/
	/*public function yearChange($year=null,$department_id=null){
		$this->layout = null;
		$user = $this->getAuth();
 		//$current = date('Y-m-d');
 		$this->Budget->recursive=-1;
		$quarters = $this->Budget->find('all',array('conditions'=>array('Budget.year'=>$year,'Budget.department_id'=>$department_id,'Budget.budget_status'=>'approved'),'group'=>array('quarter'),'fields'=>array('quarter')));
					//add condition for approved budget only

		return new CakeResponse(array('body' => json_encode($quarters)));
	}*/
	/*
	*	Name: deptChange
	*	Description: Ajax call to get the item for selected  dept
	*	Parameters: year, department id
	*	Return: XXX
	*	Author: Aisyah
	*/
	public function deptChange($budgetid,$bdeptid){
		$this->layout = null;
		$user = $this->getAuth();
 		//$current = date('Y-m-d');
 		// $this->BItemAmount->contain(array('Item'));
		
 		$this->BItemAmount->contain(array(
			
			'Item'=>array('fields'=>array('item_code','item')),
			'FMemoBudget'=>array('fields'=>array('amount','transferred_amount','unbudgeted_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array())),
			'FMemoBudgetTransfer'=>array('fields'=>array('amount','transferred_amount'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array())),
		));

		$items=$this->BItemAmount->find('all',array('conditions'=>array('budget_id'=>$budgetid,'b_dept_id'=>$bdeptid),'fields'=>array('amount','item_amount_id','item_id','b_dept_id')));
		
		$availItem=array();
		//deduct approved memo budget/budget transfer/unbudgeted amount for each item
		if (!empty($items)):
			foreach ($items as $key=>$i) {
				//deduct approved fmemobudget from balance
	            if(!empty($i['FMemoBudget'])):
	                foreach($i['FMemoBudget'] as $mb):
	                    //deduct only for approved memo budget
	                    if (!empty($mb['FMemo'])):

	                        //1.deduct approved amount from balance
	                        $i['BItemAmount']['amount']-=$mb['amount'];
	                        //2.add the unbudgeted/transferred amount to the current item amount, if exist
	                         if (!empty($mb['transferred_item_amount_id']))
	                             $i['BItemAmount']['amount']+=$mb['transferred_amount'];

	                        $i['BItemAmount']['amount']+=$mb['unbudgeted_amount'];
	                            
	                        // endif;
	                        
	                    endif;
	                endforeach;
	            endif;

	            //deduct approved fmemobudgettransfer from balance
	            if(!empty($i['FMemoBudgetTransfer'])):
	                foreach($i['FMemoBudgetTransfer'] as $mb):
	                    //deduct only for approved memo budget
	                    if (!empty($mb['FMemo'])):
	                       
	                        //1.deduct approved transfer amount from balance
	                        $i['BItemAmount']['amount']-=$mb['transferred_amount'];
	                        
	                    
	                    endif;
	                endforeach;
	            endif;
	            //set select options item with adjusted amount
	            //set only items with balance > 0
	            // if ($i['BItemAmount']['amount']>0){
		           	$availItem[$key]['item_amount_id']=$i['BItemAmount']['item_amount_id'];
					$availItem[$key]['item']=$i['Item']['item_code'].' - '.$i['Item']['item'];
					$availItem[$key]['balance']=$i['BItemAmount']['amount'];
	             // }                                       
			}
		endif;
		
		return new CakeResponse(array('body' => json_encode($availItem)));
	}

	/*
	*	Name: yearChange
	*	Description: Ajax call to get the item for selected  year
	*	Parameters: year, department id
	*	Return: XXX
	*	Author: Aisyah
	*/
	public function yearChange($budgetid,$department_id){
		$this->layout = null;
		$user = $this->getAuth();
 		//$current = date('Y-m-d');
 		$this->BDepartment->contain();
		$deptBudget=$this->BDepartment->find('first',array('fields'=>('b_dept_id'),'conditions'=>array('budget_id'=>$budgetid,'department_id'=>$department_id)));
 		
 		$bdeptid=0;

		if (!empty($deptBudget))
			$bdeptid=$deptBudget['BDepartment']['b_dept_id'];

 		$this->BItemAmount->contain(array(
			
			'Item'=>array('fields'=>array('item_code','item')),
			'FMemoBudget'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array())),
			'FMemoBudgetTransfer'=>array('fields'=>array('amount','transferred_amount'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array())),
		));

		$items=$this->BItemAmount->find('all',array('conditions'=>array('budget_id'=>$budgetid,'b_dept_id'=>$bdeptid),'fields'=>array('amount','item_amount_id','item_id','b_dept_id')));
		
		$availItem=array();
		//deduct approved memo budget/budget transfer/unbudgeted amount for each item
		if (!empty($items)):
			foreach ($items as $key=>$i) {
				//deduct approved fmemobudget from balance
	            if(!empty($i['FMemoBudget'])):
	                foreach($i['FMemoBudget'] as $mb):
	                    //deduct only for approved memo budget
	                    if (!empty($mb['FMemo'])):

	                        //1.deduct approved amount from balance
	                        $i['BItemAmount']['amount']-=$mb['amount'];
	                        //2.add the unbudgeted & transferred amount to the current item amount, if exist
	                        if (!empty($mb['transferred_item_amount_id']))
	                             $i['BItemAmount']['amount']+=$mb['transferred_amount'];
	                            
	                        $i['BItemAmount']['amount']+=$mb['unbudgeted_amount'];
	                            
	                        // endif;
	                        
	                    endif;
	                endforeach;
	            endif;

	            //deduct approved fmemobudgettransfer from balance
	            if(!empty($i['FMemoBudgetTransfer'])):
	                foreach($i['FMemoBudgetTransfer'] as $mb):
	                    //deduct only for approved memo budget
	                    // if (!empty($mb['FMemo'])):
	                       
	                        //1.deduct approved transfer amount from balance
	                       $i['BItemAmount']['amount']-=$mb['transferred_amount'];
	                       // $i['BItemAmount']['amount']-=$mb['unbudgeted_amount'];
	                        
	                    
	                    // endif;
	                endforeach;
	            endif;
	            //set select options item with adjusted amount
	           	$availItem[$key]['item_amount_id']=$i['BItemAmount']['item_amount_id'];
				$availItem[$key]['item']=$i['Item']['item_code'].' - '.$i['Item']['item'];
				$availItem[$key]['balance']=$i['BItemAmount']['amount'];
	                                                    
			}
		endif;
		// //FIRST GET THE LIST OF ITEMS AVAILABLE FOR THE SELECTED BUDGET
		// $items = $this->BItemAmount->find('all',array('conditions'=>array('BItemAmount.budget_id'=>$budgetid,'BItemAmount.department_id'=>$department_id),'fields'=>array('BItemAmount.item_amount_id','Item.item','Item.item_code','BItemAmount.amount')));
		
		// //next,for each item, calculate  balance from budget that cn be added to financial memo budget
		// $this->FMemoBudget->contain(array('FMemo'));
		
		// //find all memo budget for the department & budget id that hv been approved
		// $memoBudget=$this->FMemoBudget->find('all',array('conditions'=>array('FMemoBudget.budget_id'=>$budgetid,'FMemo.department_id'=>$department_id,'FMemo.memo_status'=>'approved'),'fields'=>array('FMemoBudget.item_amount_id','sum(FMemoBudget.amount) as total'),'group'=>array('FMemoBudget.item_amount_id')));
		
		// $availItem=array();
		// foreach ($items as $key=>$value) {
		// 	$availItem[$key]['item_amount_id']=$value['BItemAmount']['item_amount_id'];
		// 	$availItem[$key]['item']=$value['Item']['item_code'].' - '.$value['Item']['item'];
		// 	$availItem[$key]['balance']=$value['BItemAmount']['amount'];
		// 	foreach ($memoBudget as $val) {//check each budget item against approved memobudget ,if exits deduct balance
		// 		if ($value['BItemAmount']['item_amount_id']==$val['FMemoBudget']['item_amount_id']){
		// 			$availItem[$key]['balance']=$availItem[$key]['balance']-$val[0]['total'];
		// 			break;
		// 		}
		// 	}
 			
		// }
		// debug($availItem);exit;			

		//return new CakeResponse(array('body' => json_encode($items)));
		return new CakeResponse(array('body' => json_encode($availItem)));
	}

	/*

	*	confirm()

	*	< add reviewer/recommender/finance/approver page >

	*	@ < author of function editor >

	*/



	/*

	*	confirmMemo()

	*	< description of function >

	*	@ < author of function editor >

	*/

	public function confirmMemo(){
		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$user = $this->getAuth();
		//$memo_id=$this->decrypt($memo_id);

		if ($this->request->is('post')||$this->request->is('put')){

			$memo_id = $this->request->data['FMemo']['memo_id'];
			$this->FMemo->id = $memo_id;
			$encMemoID=$this->encrypt($memo_id);
			$this->FMemo->saveField('remark',$this->request->data['FMemo']['remark']);
			
			// $this->FMemo->recursive=-1;
			$this->FMemo->contain(array('Department'));
			$memo=$this->FMemo->findByMemoId($memo_id);

			if ($user['user_id']!=$memo['FMemo']['user_id']||$memo['FMemo']['submission_no']!=0){
				
				$this->Session->setFlash(__('<b>You are not authorized to create/edit the memo.</b>'),'flash.error');
				return $this->redirect(array('controller'=>'User','action'=>'userDashboard'));
			}
			
			if (!empty($this->request->data['FReviewer']['reviewer'])&&!empty($this->request->data['FReviewer']['finance'])&&!empty($this->request->data['FReviewer']['approver'])){

					// save the reviewer
				$seq=1;
				foreach($this->request->data['FReviewer']['reviewer'] as $rev):
					$reviewer['FReviewer']['memo_id'] = $memo_id;
					$reviewer['FReviewer']['user_id'] = $rev;
					$reviewer['FReviewer']['sequence'] = $seq++;
					$reviewer['FReviewer']['approval_type'] = 'reviewer';
					$this->FReviewer->create();
					if($this->FReviewer->save($reviewer)){
						$reviewerid = $this->FReviewer->id;
						$status['FStatus']['reviewer_id'] = $reviewerid;
						$status['FStatus']['status'] = 'pending';
						$status['FStatus']['submission_no'] = 1;
						$status['FStatus']['memo_id'] = $memo_id;
						$this->FStatus->create();
						$this->FStatus->save($status);
					}
				endforeach;
				
				if (!empty($this->request->data['FReviewer']['recommender'])){
					// save the recommender
					foreach($this->request->data['FReviewer']['recommender'] as $rec):
						$recommender['FReviewer']['memo_id'] = $memo_id;
						$recommender['FReviewer']['user_id'] = $rec;
						$recommender['FReviewer']['sequence'] = $seq++;
						$recommender['FReviewer']['approval_type'] = 'recommender';
						$this->FReviewer->create();
						if($this->FReviewer->save($recommender)){
							$recommenderid = $this->FReviewer->id;
							$status['FStatus']['reviewer_id'] = $recommenderid;
							$status['FStatus']['status'] = 'pending';
							$status['FStatus']['submission_no'] = 1;
							$status['FStatus']['memo_id'] = $memo_id;
							$this->FStatus->create();
							$this->FStatus->save($status);
						}
					endforeach;
				}

				// save the finance
				foreach($this->request->data['FReviewer']['finance'] as $fin):
					$finance['FReviewer']['memo_id'] = $memo_id;
					$finance['FReviewer']['user_id'] = $fin;
					$finance['FReviewer']['sequence'] = $seq++;
					$finance['FReviewer']['approval_type'] = 'finance';
					$this->FReviewer->create();
					if($this->FReviewer->save($finance)){
						$financeid = $this->FReviewer->id;
						$status['FStatus']['reviewer_id'] = $financeid;
						$status['FStatus']['status'] = 'pending';
						$status['FStatus']['submission_no'] = 1;
						$status['FStatus']['memo_id'] = $memo_id;
						$this->FStatus->create();
						$this->FStatus->save($status);
					}
				endforeach;
				// save the approver
				foreach($this->request->data['FReviewer']['approver'] as $app):
					$approver['FReviewer']['memo_id'] = $memo_id;
					$approver['FReviewer']['user_id'] = $app;
					$approver['FReviewer']['sequence'] = $seq++;
					$approver['FReviewer']['approval_type'] = 'approver';
					$this->FReviewer->create();
					if($this->FReviewer->save($approver)){
						$approverid = $this->FReviewer->id;
						$status['FStatus']['reviewer_id'] = $approverid;
						$status['FStatus']['status'] = 'pending';
						$status['FStatus']['submission_no'] = 1;
						$status['FStatus']['memo_id'] = $memo_id;
						$this->FStatus->create();
						$this->FStatus->save($status);
					}
				endforeach;

				

				//if everything is okay then only generate ref no  and update sumbission no to 1 to indicate form is completed
				
				$paddedId=str_pad($memo_id,10,"0",STR_PAD_LEFT);
				$ref_no=date('m/Y',strtotime($memo['FMemo']['created'])).'/'.$paddedId;
				$this->FMemo->saveField('ref_no',$ref_no);
				$this->FMemo->saveField('submission_no',1);
				$this->FMemo->saveField('created',date('Y-m-d h:i:s'));
				//$submission_no=$memo['FMemo']['submission_no']+1;

				// send email to reviewer
				$this->sendReviewEmail($memo_id);

				// add notification to the requestor -- stating the memo has been created
				$notiTo = $memo['FMemo']['user_id'];
				// $notiText = "You have submitted a financial memo request";
				$notiText = "<b> Ref. No : </b> ".$ref_no. "<br>".
							"<b> Subject : </b> ".$memo['FMemo']['subject']."<br>".
							"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
							"<b> Info : </b> Memo submitted";

				$notiLink = array('controller'=>'FMemo','action'=>'dashboard',$encMemoID);
				//*old*$this->UserNotification->record($notiTo, $notiText, $notiLink);

				#code update for ememo2
				$notiType = "memo"; 
				$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
                #end code update

				//send email to all reviewers except the first one
				$this->FReviewer->recursive = -1;
				$reviewers = $this->FReviewer->find('all',array('conditions'=>array(
														'FReviewer.memo_id'=>$memo_id, //only for this 
														'FReviewer.sequence != 1',
														// 'Budget.submission_no'=>1, //only for first submission
													)));
				
				$this->FMemo->contain(array('User','Department'));
				$memo = $this->FMemo->findByMemoId($memo_id);
				$rev=array();
				foreach($reviewers as $r):
					$rev[$r['FReviewer']['user_id']]=$r['FReviewer']['user_id'];
				endforeach;

				foreach($rev as $uid):
					// add notification to the other reviewers 
					$notiTo = $uid;
					// $notiText = "Your budget request has been approved";
					$notiText = "<b> Ref. No : </b> ".$ref_no. "<br>".
							"<b> Subject : </b> ".$memo['FMemo']['subject']."<br>".
							"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
							"<b> Info : </b> Memo added";

					$notiLink = array('controller'=>'FMemo','action'=>'dashboard',$encMemoID);
					//*old*$this->UserNotification->record($notiTo, $notiText, $notiLink);

					#code update for ememo2
						$notiType = "memo"; 
						$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
	                #end code update

					//send email to other -- requested on 3/7/2015 and cancelled on 14/7/2015 --not going to do it again
                    // $toReviewer= $uid;
                    // $link = $this->goLink($uid,array('controller'=>'FMemo','action'=>'dashboard',$encMemoID));

                    // $email = "This is to inform you that a new memo has been created with the following parameters.<br>";
                    // $email .= $this->requestorMemoTable($memo_id,$memo);
                    // $email .= "You have been added to the memo as a Reviewer/ Recommender/ Finance/ Approver. <br> You will be notified via email when your further action on this memo is required. <br> Thank You";
                    // $email .= "<a href='{$link}' class='btn btn-success'> Go to memo dashboard </a>";
                    // $subject = $memo['FMemo']['subject']." (Memo created)";

                    // $this->emailMe($toReviewer,$subject,$email);

				endforeach;



				$this->Session->setFlash('<b>The memo has been submitted for review.</b> <br><small> You will be notified if the status changed later </small>','flash.success');
				return $this->redirect(array('controller'=>'FMemo','action'=>'dashboard',$encMemoID));

			}

			else {
				$this->Session->setFlash('<b>You must select at least one(1) reviewer/finance/approver</b>','flash.error');
				return $this->redirect(array('controller'=>'FMemo','action'=>'confirm',$encMemoID));
			}
			
		}
	}
	//phase 2:allow edit memo budget

	public function editMemoBudget(){
		//debug($this->request->data);exit;
		$user = $this->getAuth();
		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}
		$memo_budget_id=$this->request->data['FMemoBudget']['memo_budget_id'];
		
		$amount=$this->request->data['FMemoBudget']['amount'];
		
		if (!$this->Budget->exists($memo_budget_id)) {

			$this->Session->setFlash(__('<b>Invalid memo budget.</b>'),'flash.error');

		}
		if ($this->request->is('post')||$this->request->is('put')){
			
				
				if (!empty($memo_budget_id)){
						
					$this->FMemoBudget->id=$memo_budget_id;
					
					if($this->FMemoBudget->save($this->request->data))
						$this->Session->setFlash(__('<b>Amount has been edited successfully.</b>'),'flash.success');
			
				}

				else
					$this->Session->setFlash(__('<b>Please fill in all fields.</b>'),'flash.error');
		}

			$this->redirect($this->referer());
		
		
	}
	//phase 2:allow FC to add unbudgeted to memo budget

	public function addUnbudgetedItem(){
		//debug($this->request->data);exit;
		$user = $this->getAuth();
		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}
		$memo_budget_id=$this->request->data['FMemoBudget']['memo_budget_id'];
		// $transferdeptid=$this->request->data['FMemoBudget']['transfer_dept_id'];
		// $transferitemid=$this->request->data['FMemoBudget']['transfer_item_id'];
		$budgetid=$this->request->data['FMemoBudget']['budget_id'];
		$amount=$this->request->data['FMemoBudget']['unbudgeted_amount'];
		
		if (!$this->Budget->exists($memo_budget_id)) {

			$this->Session->setFlash(__('<b>Invalid memo budget.</b>'),'flash.error');

		}
		if ($this->request->is('post')||$this->request->is('put')){
			
				//add transfer item
				if (!empty($budgetid)&&!empty($memo_budget_id)&&!empty($amount)){
						
					$this->FMemoBudget->id=$memo_budget_id;
					//$this->request->data['FMemoBudget']['transferred_item_amount_id']=null;
					if($this->FMemoBudget->save($this->request->data))
						$this->Session->setFlash(__('<b>Unbudgeted Item has been added/edited successfully.</b>'),'flash.success');
			
				}

				else
					$this->Session->setFlash(__('<b>Please fill in all fields.</b>'),'flash.error');
		}

			$this->redirect($this->referer());
		
		
	}
	//phase 2:allow FC to add unbudgeted to memo budget

	public function addTransferItem(){
		// debug($this->request->data);exit;
		$user = $this->getAuth();
		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}
		$memo_budget_id=$this->request->data['FMemoBudget']['memo_budget_id'];
		$transferdeptid=$this->request->data['FMemoBudget']['b_dept_id'];
		$transferitemid=$this->request->data['FMemoBudget']['transferred_item_amount_id'];
		$budgetid=$this->request->data['FMemoBudget']['budget_id'];
		$amount=$this->request->data['FMemoBudget']['transferred_amount'];
		
		if (!$this->Budget->exists($memo_budget_id)) {

			$this->Session->setFlash(__('<b>Invalid memo budget.</b>'),'flash.error');

		}
		if ($this->request->is('post')||$this->request->is('put')){
				
				//add transfer item
				if (!empty($budgetid)&&!empty($memo_budget_id)&&!empty($transferdeptid)&&!empty($transferitemid)&&!empty($amount)){
					// //$this->BItemAmount->recursive=-1;
					// //$itemAmount=$this->BItemAmount->find('first',array('conditions'=>array('department_id'=>$transferdeptid,'item_id'=>$transferitemid,'budget_id'=>$budgetid)));

					// if(!empty($itemAmount)){
						
						//$this->request->data['FMemoBudget']['transferred_item_amount_id']=$itemAmount['BItemAmount']['item_amount_id'];
						$this->FMemoBudget->id=$memo_budget_id;
						if($this->FMemoBudget->save($this->request->data))
							$this->Session->setFlash(__('<b>Budget Transfer Item has been added/edited successfully.</b>'),'flash.success');
					// }	
						else
							$this->Session->setFlash(__('<b>Problem adding the Budget Transfer Item.</b><br><small>Please contact administrator.</small>'),'flash.error');
					
				}

				else
					$this->Session->setFlash(__('<b>Please fill in all fields.</b>'),'flash.error');
		}

			$this->redirect($this->referer());
		
		
	}

	/*

	*	approveRejectMemo()

	*	< description of function >

	*	@ < author of function editor >

	*/

	public function approveRejectMemo(){
		

		$user = $this->getAuth();
		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['financial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}
		$user_id = $user['user_id'];
		if ($this->request->is('post')||$this->request->is('put')){
			if (empty($this->request->data['FStatus']['status'])){

				$this->Session->setFlash('<b>Please select Approve or Reject before proceeding.</b>','flash.error');
				return $this->redirect($this->referer());
			}
			$encMemoID=$this->request->data['FStatus']['memo_id'];

			$this->request->data['FStatus']['memo_id'] = $this->decrypt($encMemoID);

			$memo_id = $this->request->data['FStatus']['memo_id'];
			// debug($this->request->data);exit();
			$this->FStatus->contain(array('FReviewer','FMemo','FMemo.FMemoBudget'));
			//$FStatus = $this->FStatus->find('first',array('conditions'=>array('FReviewer.memo_id'=>$memo_id),'order'=>array('FStatus.submission_no DESC')));
			$FStatus = $this->FStatus->find('first',array('conditions'=>array('FStatus.status'=>'pending','FReviewer.user_id'=>$user_id,'FReviewer.memo_id'=>$memo_id),'order'=>array('FStatus.submission_no DESC','FReviewer.sequence ASC')));
			// $FStatus = $this->FStatus->find('first',array('conditions'=>array('FReviewer.budget_id'=>$budgetid)));
			//debug($FStatus);exit();
			if(empty($FStatus)){
				$this->Session->setFlash("<b>You don't have the privilege to approve/reject the memo</b> <br><small> Please consult administrator </small>",'flash.error');
				return $this->redirect($this->referer());
			}

			if ($FStatus['FStatus']['submitted']!=0){
				
				$this->Session->setFlash(__('<b>You have already approved/rejected the memo.</b>'),'flash.error');
				return $this->redirect($this->referer());
			}
			//phase 2 : check if memo budget exceed available amount,dont allow finance to approve
			if ($FStatus['FReviewer']['approval_type']=='finance'&&$this->request->data['FStatus']['status']=='approved'){
				foreach ($FStatus['FMemo']['FMemoBudget'] as $fmb) {
					
					$this->BItemAmount->contain(array(
		
					// 'Item'=>array('fields'=>array('item_code','item')),
					'FMemoBudget'=>array('fields'=>array('amount','transferred_amount','unbudgeted_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array())),
					'FMemoBudgetTransfer'=>array('fields'=>array('amount','transferred_amount'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array())),
					));

					$item=$this->BItemAmount->find('first',array('conditions'=>array('item_amount_id'=>$fmb['item_amount_id']),'fields'=>array('amount','item_amount_id')));

					//deduct approved fmemobudget from balance
		            if(!empty($item['FMemoBudget'])):
		                foreach($item['FMemoBudget'] as $mb):
		                    //deduct only for approved memo budget
		                    if (!empty($mb['FMemo'])):

		                        //1.deduct approved amount from balance
		                        $item['BItemAmount']['amount']-=$mb['amount'];
		                        //2.add the unbudgeted/transferred amount to the current item amount, if exist
		                        if (!empty($mb['transferred_item_amount_id']))
		                            $item['BItemAmount']['amount']+=$mb['transferred_amount'];
		                        
		                        $item['BItemAmount']['amount']+=$mb['unbudgeted_amount'];
		                        
		                    endif;
		                endforeach;
		            endif;

		            //deduct approved fmemobudgettransfer from balance
		            if(!empty($item['FMemoBudgetTransfer'])):
		                foreach($item['FMemoBudgetTransfer'] as $mb):
		                    //deduct only for approved memo budget
		                    if (!empty($mb['FMemo'])):
		                       
		                        //1.deduct approved transfer amount from balance
		                        $item['BItemAmount']['amount']-=$mb['transferred_amount'];
		                        
		                    
		                    endif;
		                endforeach;
		            endif;

		            $transferUnbudgeted=$fmb['unbudgeted_amount'];
		            if (!empty($fmb['transferred_item_amount_id']))
		           		 $transferUnbudgeted+=$fmb['transferred_amount'];

		            $finalAmount=$item['BItemAmount']['amount']-$fmb['amount']+$transferUnbudgeted;
		            if ($finalAmount<0){

		            	$this->Session->setFlash(__('<b>The amount requested is exceeding the available budget amount. </b><br><small>Please revise/use unbudgeted or budget transfer to ensure the amount requested does not exceed the available balance.</small>'),'flash.error');

						return $this->redirect($this->referer());
		            }

				}
				
			}



			$statusid = $FStatus['FStatus']['status_id'];
			$submissionNo = $FStatus['FStatus']['submission_no'];
			$approval_type=$FStatus['FReviewer']['approval_type'];
			$remark = $this->request->data['FStatus']['remark'];
			$status = $this->request->data['FStatus']['status'];
			$this->request->data['FStatus']['submitted']=1;
			if ($approval_type=='finance'){
				if(isset($this->request->data['FMemo']['budgeted'])){
					$this->FMemo->id=$memo_id;
					$this->FMemo->saveField('budgeted',$this->request->data['FMemo']['budgeted']);
				}
			}
			if($status == 'rejected'){
			
				$this->FStatus->updateAll(array(
					'FStatus.status'=>"'pending-rejected'",
					),
					array(
						'FStatus.submission_no'=>$submissionNo,
						'FStatus.memo_id'=>$memo_id,
						'FStatus.status'=>'pending',
					)
				);
			
			}

			$this->FStatus->id = $statusid;
			if( $this->FStatus->save($this->request->data)){

				if ($approval_type=='approver'&&$status == 'rejected'){
					//send email to finance saying that he has to review again, then update approver reject column in fmemo
					$this->FMemo->id = $memo_id;
					//$this->FMemo->saveField('approver_reject',1);
					//resubmit memo to finance for review
					$submission_no=$FStatus['FMemo']['submission_no']+1;
					$this->FMemo->saveField('submission_no',$submission_no);
					//insert new status 
					$this->FReviewer->contain(array('FStatus'=>array('conditions'=>array('FStatus.submission_no'=>$submission_no-1))));
					$reviewers = $this->FReviewer->find('all',array('conditions'=>array('FReviewer.memo_id'=>$memo_id),'order'=>array('FReviewer.sequence ASC')));

					// $this->FStatus->contain(array('FReviewer'));
					// $statuses = $this->FStatus->find('all',array('conditions'=>array('FStatus.memo_id'=>$memo_id,'FStatus.submission_no'=>$submission_no-1),'order'=>array('FReviewer.sequence ASC')));
					//debug($reviewers);exit;
					foreach ($reviewers as $value) {
						$data['FStatus']['status'] = 'pending';
						$data['FStatus']['remark'] = null;
						//$id = $value['reviewer_id'];
						if($value['FReviewer']['approval_type']!='finance'&&$value['FReviewer']['approval_type']!='approver'){
							$data['FStatus']['status'] = $value['FStatus'][0]['status'];
							$data['FStatus']['remark'] = $value['FStatus'][0]['remark'];
						}
						

						$data['FStatus']['reviewer_id'] = $value['FReviewer']['reviewer_id'];
						$data['FStatus']['submission_no'] = $submission_no;
						$data['FStatus']['memo_id'] = $memo_id;
						$this->FStatus->create();
						$this->FStatus->save($data);
			
					}
					$this->sendApproverRejectedEmail($memo_id);
					$this->Session->setFlash("<b>You have ".$status." the memo. </b><br><small> The memo has been resubmitted to finance for another round of review. </small>",'flash.success');

				}
				else{
					// only send email after status changed
					if($status == 'approved'){
						$this->sendReviewEmail($memo_id);
					}
					elseif($status == 'rejected'){
						$this->sendRejectedEmail($memo_id);
					}
				$this->Session->setFlash("<b>You have ".$status." the memo. </b><br><small> Thank You </small>",'flash.success');

				}
				

				$this->redirect(array('controller'=>'FMemo','action'=>'dashboard',$encMemoID));
			}
		}
	}

#endregion

#region "Supporting Functions"
	/*

	*	remark()

	*	< description of function >

	*	@ < author of function editor >

	*/
	private function getReviewer($memo_id=null,$reviewer_type=null,$submission_no=null){
		//$memo_id=$this->decrypt($memo_id);
		$reviewer_query = $this->FReviewer->find('all',array(
			'conditions' => array(
				
				'FReviewer.memo_id' => $memo_id,
				'FReviewer.approval_type'=>$reviewer_type,
			),
			'order'=>array(
				'FReviewer.sequence'=>'ASC'
			),
			'contain'=>array(
				'FStatus'=>array(
					'conditions'=>array('FStatus.submission_no'=>$submission_no,'FStatus.memo_id'=>$memo_id)
				),
				'User'=>array(
					'fields'=>array('user_id','staff_name','designation'),
					'Department'=>array('fields'=>array('department_name')),
				),
				
			)

		));

		return($reviewer_query);

	}

	private function getRemark($memo_id=null, $reviewer_id=null){

		$remark_query=$this->FRemark->find('all',array(
			'conditions' => array(
		
				'FRemark.memo_id' => $memo_id,
				'FRemark.reviewer_id'=>$reviewer_id,
			),
			'fields'=>array('subject'),
			'order'=>array('FRemark.created'=>'ASC'),
			'contain'=>array(
				'User'=>array('fields'=>array('staff_name')),
				'FRemarkFeedback'=>array('fields'=>array('feedback','created'),'User'=>array('fields'=>array('staff_name')),'order'=>array('FRemarkFeedback.created'=>'ASC')),
				'FRemarkAssign'=>array('fields'=>array(''),'User'=>array('fields'=>array('staff_name'))),
			)

		));

		return($remark_query);
	}

/*

	*	downloadAttachment()

	*	< description of function >

	*	@ < author of function editor >

	*/

	public function downloadAttachment($attachment_id=null){
		$attachment_id=$this->decrypt($attachment_id);
		if (!$this->FVendorAttachment->exists($attachment_id)) {

			$this->Session->setFlash(__('<b>Invalid attachment.</b>'),'flash.error');

		}

		$this->FVendorAttachment->recursive=-1;	
	    $attachment=$this->FVendorAttachment->find('first',array('conditions'=>array('FVendorAttachment.attachment_id '=>$attachment_id)));
	    $tmpName=explode('___',$attachment['FVendorAttachment']['filename']);
	    if (count($tmpName)>1)
	    	$filename=$tmpName[1];
	    else
	    	$filename=$attachment['FVendorAttachment']['filename'];

	   	$this->response->file(WWW_ROOT.'files'.DS.'fvendor-attachment'.DS.$attachment['FVendorAttachment']['filename'], array('download' => true, 'name' => $filename));
	    // Return response object to prevent controller from trying to render
	    // a view

	    //record activity
		//$this->Activity->record('downloaded an attachment from a conversation',0);

	    return $this->response;

	}

	/*
	*	deleteAttachment
	*	< delete vendor attachment from financial memo form >
	*	@ Aisyah
	*/
	public function deleteAttachment($attachment_id=null){
		$attachment_id=$this->decrypt($attachment_id);

		if (!$this->FVendorAttachment->exists($attachment_id)) {

			$this->Session->setFlash(__('<b>Invalid attachment.</b>'),'flash.error');

		}

		$attachment = $this->FVendorAttachment->find('first',array('conditions'=>array('FVendorAttachment.attachment_id'=>$attachment_id)));
		$filepath = WWW_ROOT.'files'.DS."fvendor-attachment".DS.$attachment['FVendorAttachment']['filename'];
		unlink($filepath);
		if($this->FVendorAttachment->delete($attachment_id))
			$this->Session->setFlash(__('<b>Attachment deleted successfully.</b>'),'flash.success');
		else
			$this->Session->setFlash(__('<b>Failed to delete the attachment. Please try again.</b>'),'flash.error');


			$this->redirect($this->referer());	
	}

	/*

	*	remark()

	*	< description of function >

	*	@ < author of function editor >

	*/

	



	
	public function pdf($memo_id=null){
		$user = $this->getAuth();
		$encMemoID=$memo_id;
		$memo_id=$this->decrypt($memo_id);

		if (!$this->FMemo->exists($memo_id)) {

			throw new ForbiddenException();

		}
		$userid = $user['user_id'];
		$memo = $this->FMemo->find('first',array(
			'conditions' => array('FMemo.memo_id' => $memo_id),
			'contain'=>array(
				'User'=>array('fields'=>array('staff_name','designation'),'Department'=>array('fields'=>array('department_name'))
				),
				'FReviewer',
				'FVendorAttachment',
				'FMemoTo'=>array('User'=>array('fields'=>array('User.staff_name','User.staff_id','User.designation'))),
				// 'FMemoBudget'=>array('fields'=>''),
				'FMemoBudget.Budget'=>array('fields'=>'Budget.year'),
				// 'FMemoBudget.BItemAmountTransfer'=>array('fields'=>''),
				'FMemoBudget.BItemAmountTransfer.Item'=>array('fields'=>'item'),
				'FMemoBudget.BItemAmountTransfer.BDepartment.Department'=>array('fields'=>'department_shortform'),
				'FMemoBudget.BItemAmount.Item'=>array('fields'=>'Item.code_item'),
				
			)

		));
		//changes 20.92016-added pdf print for previous approval/rejection by approver
		$submission_no=(strpos($this->referer(),'prevSubmission')!==false) ? ($memo['FMemo']['submission_no']-1) : $memo['FMemo']['submission_no'] ;

 		$this->set('memo',$memo); //debug($memo); exit;
 		// $this->set('editFlag',$editFlag);
 		// $this->set('remarkFlag',$remarkFlag);
 		// $this->set('approvalFlag',$approvalFlag);
 		// $this->set('financeFlag',$financeFlag);
 		//debug($memo['User']['Department']['department_name']);exit;
 		$reviewer=$this->getReviewer($memo_id,'reviewer',$submission_no);
 		$recommender=$this->getReviewer($memo_id,'recommender',$submission_no);
 		$finance=$this->getReviewer($memo_id,'finance',$submission_no);
 		$approver=$this->getReviewer($memo_id,'approver',$submission_no);
		
		$remark_reviewer=array();
		$remark_recommender=array();
		$remark_finance=array();
		$remark_approver=array();
		
		if (!empty($reviewer)){
			foreach ($reviewer as $value) {
				
				$remark_reviewer[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		if (!empty($recommender)){
			foreach ($recommender as $value) {
				
				$remark_recommender[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		if (!empty($finance)){
			foreach ($finance as $value) {
				
				$remark_finance[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		if (!empty($approver)){
			foreach ($approver as $value) {
				
				$remark_approver[]=$this->getRemark($memo_id,$value['FReviewer']['reviewer_id']);
			}
			
		}

		$this->set('reviewer',$reviewer);
 		$this->set('recommender',$recommender);
 		$this->set('finance',$finance);
 		$this->set('approver',$approver);
 		$this->set('remark_reviewer',$remark_reviewer);
 		$this->set('remark_recommender',$remark_recommender);
 		$this->set('remark_finance',$remark_finance);
 		$this->set('remark_approver',$remark_approver);
 		$this->set('memo_id',$memo_id);

 		$subject=$memo['FMemo']['subject'];
 		$department=$memo['User']['Department']['department_name'];
 		$date=date('d.m.Y', strtotime($memo['FMemo']['created'])) ;
 		$ref_no=$memo['FMemo']['ref_no'];
		//changes 20.92016-added pdf print for previous approval/rejection by approver
 		$pdfName=(strpos($this->referer(),'prevSubmission')!==false) ? ($ref_no.'_'.$subject.'_previous.pdf') : ($ref_no.'_'.$subject.'_'.$date.'.pdf') ;
 		$this->layout = 'mems-pdf-memo';
 		$this->pdfConfig = array(
			'orientation'=>'portrait',
			 //'filename'=>'UNITARMemo'.'_'.$subject.'_'.$department.'_'.$date.'.pdf',
			# ememo changes requested on 27 june 2016 : e-memo reference number + e-memo title + date.
			'filename'=>$pdfName,
			'download'=>true,
		);
		$this->render('pdf');


	}
#endregion	

#region "Generate Email funtion"
	public function sendReviewEmail($memo_id){
		//$memo_id=$this->decrypt($memo_id);
		
		$this->layout = 'mems-email';
		// get current status
		$this->FStatus->contain(array(
								'FReviewer',
								'FReviewer.User',
								'FMemo',
								'FMemo.User'=>array('fields'=>array('staff_name','email_address')),
								'FMemo.Department'));
		$status = $this->FStatus->find('first',array('conditions'=>array('FStatus.memo_id'=>$memo_id,'FStatus.status'=>'pending'),'order'=>array('FReviewer.sequence ASC')));
		// debug($status);exit();

		$this->FMemo->contain(array('User','Department'));
		$memo = $this->FMemo->findByMemoId($memo_id);
		$encMemoID=$this->encrypt($memo_id);
		// if status empty --> means all is approved?
		if(empty($status)){ // send to requestor
			
			// update memo_status in memo table
			$this->FMemo->id  = $memo_id;
			$this->FMemo->saveField('memo_status','approved');			

			$toRequestor= $memo['FMemo']['user_id'];

			// add notification to the requestor -- stating the budget has been fully approved
			$notiTo = $memo['FMemo']['user_id'];
			// $notiText = "Your financial memo request has been approved";
			$notiText = "<b> Ref. No : </b> ".$memo['FMemo']['ref_no']. "<br>".
						"<b> Subject : </b> ".$memo['FMemo']['subject']."<br>".
						"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
						"<b> Info : </b> Memo approved";

			$notiLink = array('controller'=>'FMemo','action'=>'dashboard',$encMemoID);
			//$this->UserNotification->record($notiTo, $notiText, $notiLink);

			#code update for ememo2
			$notiType = "memo"; 
			$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
	        #end code update

			// generate link
			$link = $this->goLink($memo['FMemo']['user_id'],array('controller'=>'FMemo','action'=>'dashboard',$encMemoID));

			$email = "Your Memo request has been approved.<br>";
			$email .= $this->requestorMemoTable($memo_id,$memo);
			$email .= "You may go to the memo dashboard by clicking the button below <br>";

			
			// debug($link);exit();
			$email .= "<a href='{$link}' class='btn btn-success'> Go to memo dashboard </a>";

			$toRequestor = $memo['FMemo']['user_id'];
			$subject = $memo['FMemo']['subject']." (memo request approved)";

			$this->emailMe($toRequestor,$subject,$email);

		}
		else{ // send to reviewer

			// add notification to the requestor -- stating the memo has been reviewed -- only when status is not 1 --coz 1 send review email -- not been reviewed
			if($status['FReviewer']['sequence'] != 1){
				$notiTo = $memo['FMemo']['user_id'];
				// $notiText = "Your financial memo request has been reviewed";
				$notiText = "<b> Ref. No : </b> ".$memo['FMemo']['ref_no']. "<br>".
							"<b> Subject : </b> ".$memo['FMemo']['subject']."<br>".
							"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
							"<b> Info : </b> Memo reviewed";

				$notiLink = array('controller'=>'FMemo','action'=>'dashboard',$encMemoID);
				//$this->UserNotification->record($notiTo, $notiText, $notiLink);

				#code update for ememo2
				$notiType = "memo"; 
				$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
	        	#end code update


				// also send email to requester each time reviewer approve -- requested by unitar on 8/7/2015
				$approvedBy = $this->getAuth();
				$link = $this->goLink($memo['FMemo']['user_id'],array('controller'=>'FMemo','action'=>'dashboard',$encMemoID));

				$email = "Your memo request has been approved by ".$approvedBy['staff_name'] .".<br>";
				$email .= $this->requestorMemoTable($memo_id,$memo);
				$email .= "You may go to the dashboard page by clicking the button below <br>";

				$email .= "<a href='{$link}' class='btn btn-success'> Memo Dashboard </a>";

				$toRequestor = $status['FMemo']['user_id'];
				$subject = $status['FMemo']['subject']." (Memo reviewed)";

				$this->emailMe($toRequestor,$subject,$email);
			}
			
			// add notification to the reviewer -- stating the memo need to be reviewed
			$notiTo = $status['FReviewer']['user_id'];
			// $notiText = "Please review the financial memo request";
			$notiText = "<b> Ref. No : </b> ".$memo['FMemo']['ref_no']. "<br>".
						"<b> Subject : </b> ".$memo['FMemo']['subject']."<br>".
						"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
						"<b> Pending : </b> Review of memo";

			$notiLink = array('controller'=>'FMemo','action'=>'review',$encMemoID);
			//$this->UserNotification->record($notiTo, $notiText, $notiLink);

			#code update for ememo2
			$notiType = "memo"; 
			$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
	        #end code update

			// generate link
			$link = $this->goLink($status['FReviewer']['user_id'],array('controller'=>'FMemo','action'=>'review',$encMemoID));

			$email = "You are required to review the following memo request.<br>";
			$email .= $this->requestorMemoTable($memo_id,$memo);
			$email .= "You may go to the review page by clicking the button below <br>";

			
			// debug($link);exit();
			$email .= "<a href='{$link}' class='btn btn-success'> Review the memo request </a>";

			$toReviewer = $status['FReviewer']['user_id'];
			$subject = $memo['FMemo']['subject']." (Please review the memo request)";

			$this->emailMe($toReviewer,$subject,$email);
		}

		
		// $this->set('email',$email);
		// $this->render('email');
		// debug($status);exit();
	}

	public function sendRejectedEmail($memo_id){
		$this->FMemo->contain(array('User','Department'));
		$memo = $this->FMemo->findByMemoId($memo_id);
		$encMemoID=$this->encrypt($memo_id);

		$toRequestor= $memo['FMemo']['user_id'];
		//generate link
		$link = $this->goLink($toRequestor,array('controller'=>'FMemo','action'=>'dashboard',$encMemoID));

		$email = "Your memo request has been rejected.<br>";
		$email = "Please review it again and resubmit your memo request.<br>";
		$email .= $this->rejectedMemoTable($memo_id,$memo,'');
		$email .= "You may go to the memo request page by clicking the button below <br>";

		
		// debug($link);exit();
		$email .= "<a href='{$link}' class='btn btn-success'> Go to memo request </a>";

		//$toRequestor = $memo['FMemo']['user_id'];
		$subject = $memo['FMemo']['subject']." (Memo request rejected)";

		$this->emailMe($toRequestor,$subject,$email);

		// add notification to the requestor -- stating the memo has been rejected
		$notiTo = $memo['FMemo']['user_id'];
		// $notiText = "Your financial memo request has been rejected";
		$notiText = "<b> Ref. No : </b> ".$memo['FMemo']['ref_no']. "<br>".
						"<b> Subject : </b> ".$memo['FMemo']['subject']."<br>".
						"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
						"<b> Info : </b> Memo rejected";

		$notiLink = array('controller'=>'FMemo','action'=>'dashboard',$encMemoID);
		//$this->UserNotification->record($notiTo, $notiText, $notiLink);

		#code update for ememo2
		$notiType = "memo"; 
		$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
	    #end code update
	}

	public function sendApproverRejectedEmail($memo_id){
		$this->FMemo->contain(array('User','Department'));
		$memo = $this->FMemo->findByMemoId($memo_id);
		$encMemoID=$this->encrypt($memo_id);

		$this->FReviewer->recursive=-1;
		$finance=$this->FReviewer->find('first',array('conditions'=>array('FReviewer.memo_id'=>$memo_id,'FReviewer.approval_type'=>'finance'),'order'=>array('FReviewer.sequence ASC')));
		
		$toFinance= $finance['FReviewer']['user_id'];
		//generate link
		$link = $this->goLink($toFinance,array('controller'=>'FMemo','action'=>'dashboard',$encMemoID));

		$email = "Approver has rejected the financial memo request.<br>";
		$email = "Please review it again.<br>";
		$email .= $this->rejectedMemoTable($memo_id,$memo,'approver');
		$email .= "You may go to the memo request page by clicking the button below <br>";

		
		// debug($link);exit();
		$email .= "<a href='{$link}' class='btn btn-success'> Go to memo request </a>";

		//$toRequestor = $memo['FMemo']['user_id'];
		$subject = $memo['FMemo']['subject']." (Memo request rejected by approver)";

		$this->emailMe($toFinance,$subject,$email);

		// add notification to the finance -- stating the memo has been rejected
		$notiTo = $toFinance;
		// $notiText = "Approver has rejected the financial memo request";
		$notiText = "<b> Ref. No : </b> ".$memo['FMemo']['ref_no']. "<br>".
						"<b> Subject : </b> ".$memo['FMemo']['subject']."<br>".
						"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
						"<b> Pending : </b> Review of memo (rejected by approver)";

		$notiLink = array('controller'=>'FMemo','action'=>'dashboard',$encMemoID);
		//$this->UserNotification->record($notiTo, $notiText, $notiLink);

		#code update for ememo2
		$notiType = "memo"; 
		$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
	    #end code update


	}

	private function requestorMemoTable($memo_id,$memoData = array()){
		//$budgetAmount = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		//$totalBudget = $budgetAmount[0]['totalBudget'];

		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Memo Created </td>
							<td>".date('d M Y',strtotime($memoData['FMemo']['created'])). " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Subject </td>
							<td>".$memoData['FMemo']['subject']. " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Reference No. </td>
							<td>".$memoData['FMemo']['ref_no']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Department </td>
							<td> ".
								$memoData['Department']['department_name']."<br>". 
								"<small>Requestor : ".$memoData['User']['staff_name']."</small>".
							"</td>
						</tr>
						
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Current Submission No. </td>
							<td>".$memoData['FMemo']['submission_no']. " </td>
						</tr>
					</table>";

		return $htmlTable;
	}

	private function memoTable($memo_id,$memoData = array()){
		// $budgetAmount = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		// $totalBudget = $budgetAmount[0]['totalBudget'];

		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Memo Created </td>
							<td>".date('d M Y',strtotime($memoData['created'])). " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Subject </td>
							<td>".$memoData['subject']. " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Reference No. </td>
							<td>".$memoData['ref_no']. " </td>
						</tr>						
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Department </td>
							<td> ".
								$memoData['Department']['department_name']."<br>". 
								"<small>Requestor : ".$memoData['User']['staff_name']."</small>".
							"</td>
						</tr>
						
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Current Submission No. </td>
							<td>".$memoData['submission_no']. " </td>
						</tr>
					</table>";

		return $htmlTable;
	}

	private function rejectedMemoTable($memo_id,$memoData = array(),$approver){
		// $budgetAmount = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		// $totalBudget = $budgetAmount[0]['totalBudget'];

		$this->FStatus->contain(array('FReviewer','FReviewer.User'));
		if($approver=='approver')
			$rejectedStatus = $this->FStatus->findByMemoIdAndStatusAndSubmissionNo($memo_id,'rejected',$memoData['FMemo']['submission_no']-1);
		else
			$rejectedStatus = $this->FStatus->findByMemoIdAndStatusAndSubmissionNo($memo_id,'rejected',$memoData['FMemo']['submission_no']);
		// debug($rejectedStatus);exit();

		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Memo Created </td>
							<td>".date('d M Y',strtotime($memoData['FMemo']['created'])). " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Subject </td>
							<td>".$memoData['FMemo']['subject']. " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Reference No. </td>
							<td>".$memoData['FMemo']['ref_no']. " </td>
						</tr>						
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Current Submission No. </td>
							<td>".$memoData['FMemo']['submission_no']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Rejected By </td>
							<td>".$rejectedStatus['FReviewer']['User']['staff_name']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Remark </td>
							<td>".$rejectedStatus['FStatus']['remark']. " </td>
						</tr>
					</table>";

		return $htmlTable;
	}

	#ememo2 :: email to notify memo is deleted.
	public function sendDeleteEmail($memo_id){
		$this->layout = 'mems-email';
		$this->FMemo->contain(array('User','Department','FReviewer'=>array('User')));
		$memo = $this->FMemo->findByMemoId($memo_id);			
		
	    #1.notify all reviewers on deleted memo
		if(!empty($memo)){
			foreach ($memo['FReviewer'] as $rev) {
			

				#EMAIL
				$email = "Please be informed that this memo has no longer exist. It has been removed from the system.<br>";
				$email .= $this->requestorMemoTable($memo_id,$memo);
				$email .= "For any enquiry, please contact your Administrator. Thank you. <br>";			
			
				$toReviewer = $rev['user_id'];
				$subject = $memo['FMemo']['subject']." (Deletion of memo from the system)";	

				$this->emailMe($toReviewer,$subject,$email);

				#NOTIFICATION
				$notiTo = $toReviewer;			
				$notiText = "<b> Ref. No : </b> ".$memo['FMemo']['ref_no']. "<br>".
							"<b> Subject : </b> ".$memo['FMemo']['subject']."<br>".
							"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
							"<b> Info : </b> Memo removed";
				$notiLink = array('controller'=>'FMemo','action'=>'myReview');
				//$this->UserNotification->record($notiTo, $notiText, $notiLink);


				#code update for ememo2
				$notiType = "memo"; 
				$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
			    #end code update

				
			}

			#2.notify requestor on deleted memo
			#EMAIL
			$email = "Please be informed that this memo has no longer exist. It has been removed from the system.<br>";
			$email .= $this->requestorMemoTable($memo_id,$memo);
			$email .= "For any enquiry, please contact your Administrator. Thank you. <br>";			
		
			$toRequestor = $memo['FMemo']['user_id'];
			$subject = $memo['FMemo']['subject']." (Deletion of memo from the system)";	

			$this->emailMe($toRequestor,$subject,$email);

			#NOTIFICATION
			$notiTo = $toRequestor;			
			$notiText = "<b> Ref. No : </b> ".$memo['FMemo']['ref_no']. "<br>".
						"<b> Subject : </b> ".$memo['FMemo']['subject']."<br>".
						"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
						"<b> Info : </b> Memo removed";
			$notiLink = array('controller'=>'FMemo','action'=>'index');
			//$this->UserNotification->record($notiTo, $notiText, $notiLink);


			#code update for ememo2
			$notiType = "memo"; 
			$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
		    #end code update

		}
		
	}
		



}
