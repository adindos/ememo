<?php


App::uses('AppController', 'Controller');
require_once(APP . 'Vendor' . DS . 'PHPExcel' . DS . 'Classes'. DS . 'PHPExcel.php');

class BudgetController extends AppController{

	public $uses = array('Setting','UserNotification','Item','BRemark','Budget','BOldItemGroup','BOldItemAmount','BItemGroup','BItemAmount','User','BReviewer','BStatus','Department','Group','Company','FMemoBudget','BDepartment','BOldDepartment');

	public function index(){
		$user = $this->getAuth();

		// Check user role
		if(!$user['Role']['my_request_budget']){
			throw new ForbiddenException();
		}

		$this->Budget->contain(array(
								'Company',
								'BStatus'=>array('fields'=>array('status','submission_no')),
								'BRemark.BRemarkAssign'=>array('conditions'=>array('BRemarkAssign.user_id'=>$user['user_id']),'fields'=>array('BRemarkAssign.user_id')),
								));
		$budget = $this->Budget->find('all',array('conditions'=>array('Budget.user_id'=>$user['user_id']),'order'=>array('Budget.created DESC')));
		// debug($budget);exit();
		$this->set('budget',$budget);
		

		// $this->Department->contain(array('Group'=>array('fields'=>array('group_name')),'Group.Company'=>array('fields'=>array('company'))));
		// $userDepartment = $this->Department->find('first',array('conditions'=>array('Department.department_id'=>$user['department_id'])));
		// $strDepartment = $userDepartment['Group']['Company']['company'] . " > " . $userDepartment['Group']['group_name'] ." > ".$userDepartment['Department']['department_name'];
		// $this->set('strDepartment',$strDepartment);
		$compList=$this->Company->find('list');
		$this->set('compList',$compList);

	}
	/*
	*	Name: deptData
	*	Description: Ajax call to get the dept for selected company
	*	Parameters: company id
	*	Return: XXX
	*	Author: Aisyah
	*/
	public function deptData($company_id){
		$this->layout = null;
		$user = $this->getAuth();

		$this->Department->contain(array('Group'));
		
		$deptAcad = $this->Department->find('list',array('conditions'=>array('department_type'=>'1','Group.company_id'=>$company_id),'order'=>'department_id ASC'));
		$this->Department->contain(array('Group'));
		$deptNonAcad = $this->Department->find('list',array('conditions'=>array('department_type'=>'2','Group.company_id'=>$company_id),'order'=>'department_id ASC'));
		$depts=array($deptAcad,$deptNonAcad);

		return new CakeResponse(array('body' => json_encode($depts)));
	}

	public function dashboard($budgetid){

		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);
		// debug($budgetid);exit();
		$user = $this->getAuth();
		if (!$this->Budget->exists($budgetid)) {
			throw new ForbiddenException();
		}

		$userid = $user['user_id'];

		// check if budget is confirm
		$isConfirm = $this->Budget->findByBudgetId($budgetid);
		if($isConfirm['Budget']['submission_no'] == 0){
			$this->Session->setFlash('This budget is not yet finalized <br><small>Please click the <strong><em> Save & Preview </em></strong> button to proceed </small>','flash.error');
			$this->redirect(array('controller'=>'budget','action'=>'request',$encBudgetID)); //redirect using the encrypted budget id
		}

		// // check if the budget is finished -- have reviewer and status -- if one exist is ok -- reduce memory
		// $isFinished = $this->BStatus->findByBudgetId($budgetid);
		// if(!$isFinished){
		// 	$this->Session->setFlash('This budget is not yet finished <br><small>Please fill the form to finish the budget </small>','flash.error');
		// 	$this->redirect(array('controller'=>'budget','action'=>'confirm',$encBudgetID)); //redirect using the encrypted budget id
		// }

		//if not requestor / reviewer / approver -> cannot view dashboard
		// $this->BReviewer->recursive = -1;
		// $isReviewer = $this->BReviewer->find('first',array('conditions'=>array('BReviewer.budget_id'=>$budgetid,'BReviewer.user_id'=>$userid)));
		// $this->Budget->recursive = -1;
		// $isRequestor = $this->Budget->find('first',array('conditions'=>array('Budget.user_id'=>$userid,'Budget.budget_id'=>$budgetid)));
		
		// if(empty($isRequestor) && empty($isReviewer)){
		// 	$this->Session->setFlash("You don't have the privileges to view the budget <br><small> Please consult administrator for help </small>","flash.error");
		// 	$this->redirect(array('controller'=>'user','action'=>'userDashboard'));
		// }

		// // calculate the total budget
		// $totalBudget = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		// $this->set('totalBudget',$totalBudget[0]['totalBudget']);
		// debug($totalBudget);exit();

		// the budget data
		$this->Budget->contain(array(
							'User'=>array('fields'=>array('staff_name','designation')),
							'User.Department'=>array('fields'=>array('department_name')),
							'Company',
						));
		$budget = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		$this->set('budget',$budget);
		// debug($budget);exit();

		// check if there is pending action for current user
		$this->BStatus->contain(array('BReviewer'));
		$isMyPending = $this->BStatus->find('first',array('conditions'=>array('BStatus.status'=>'pending',
																	'BReviewer.user_id'=>$userid,
																	'BStatus.budget_id'=>$budget['Budget']['budget_id'],
																	'BStatus.submission_no'=>$budget['Budget']['submission_no']
																	)));
		if($isMyPending){
			$this->Session->setFlash('<b> This budget is waiting for your review </b><br><small>Please go to review page by clicking the <em><b>Budget Details</b></em> button below</small>','flash.info');
		}

		// reviewer
		$this->BStatus->contain(array(
							'BReviewer',
							'BReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$finances = $this->BStatus->find('all',array('conditions'=>array('BStatus.submission_no'=>$budget['Budget']['submission_no'],'BReviewer.budget_id'=>$budgetid,'BReviewer.approval_type'=>'finance'),'order'=>array('sequence ASC')));
		$this->set('finances',$finances);
		// debug($reviewers);exit();

		// approver
		$this->BStatus->contain(array(
							'BReviewer',
							'BReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$approvers = $this->BStatus->find('all',array('conditions'=>array('BStatus.submission_no'=>$budget['Budget']['submission_no'],'BReviewer.budget_id'=>$budgetid,'BReviewer.approval_type'=>'approver'),'order'=>array('sequence ASC')));
		$this->set('approvers',$approvers);

		// setting max budget time
		$setting = $this->Setting->find('first');
		$this->set('setting',$setting);
	}

	public function allrequest(){
		$user = $this->getAuth();

		// Check user role
		if(!$user['Role']['all_request_budget']){
			throw new ForbiddenException();
		}

		$this->Budget->contain(array(
								'Company',
								'BStatus'=>array('fields'=>array('status','submission_no')),
								));
		if ($user['role_id']==17||$user['finance']){//show all budget regardless of status to admin
			$budget = $this->Budget->find('all',array(
								'conditions'=>array('Budget.submission_no NOT'=>0),
								'order'=>array('Budget.created DESC')));
		}
		else{//show only approved budget to other users,for users with dept not listed just show 0 for all
			$budget = $this->Budget->find('all',array(
								'conditions'=>array('Budget.submission_no NOT'=>0,'Budget.budget_status'=>'approved'),
								'order'=>array('Budget.created DESC')));
		 }
		
		$this->set('budget',$budget);
	}

	public function myreview(){
		$user = $this->getAuth();

		// Check user role
		if(!$user['Role']['request_management_budget']){
			throw new ForbiddenException();
		}

		//also should display when other previous reviewer has approved
		$this->BStatus->contain(array(
								'Budget',
								'Budget.User'=>array('Department','fields'=>array('staff_name')),
								'Budget.BStatus',
								'Budget.Company'=>array('fields'=>array('company')),
								// 'Budget.Department'=>array('fields'=>array('department_name')),
								'BReviewer',
							)
						);
		$pendingBudget = $this->BStatus->find('all',array(
									'conditions'=>array('BStatus.status'=>'pending','BReviewer.user_id'=>$user['user_id'],'BStatus.submission_no = Budget.submission_no',
														"NOT EXISTS (SELECT * FROM b_statuses WHERE b_statuses.reviewer_id < BReviewer.reviewer_id AND b_statuses.budget_id = BStatus.budget_id AND b_statuses.status ='pending')")
									)); // this is truee laaa -- just need to make sure when equal to same find,use equal like in submission_no
		$this->set('pendingBudget',$pendingBudget);
		// debug($pendingBudget);exit();

		$this->BStatus->contain(array(
								'Budget',
								'Budget.User'=>array('fields'=>array('staff_name')),
								'Budget.User.Department'=>array('fields'=>array('department_name')),
								'Budget.BStatus',
								'Budget.Company'=>array('fields'=>array('company')),
								// 'Budget.Department'=>array('fields'=>array('department_name')),
								'BReviewer',
							)
						);
		$reviewedBudget = $this->BStatus->find('all',array('conditions'=>array(
													"OR"=>array(array("BStatus.status"=>"approved"),array("BStatus.status"=>"rejected")),
													"BStatus.submission_no = Budget.submission_no",
													"BReviewer.user_id"=>$user["user_id"]
												)));
		$this->set('reviewedBudget',$reviewedBudget);
		// debug($reviewedBudget);exit();
	}

	public function mastercostlist($company=null,$quarter =null, $year=null,$action=null,$extra=null){
		$user = $this->getAuth();

		// Check user role
		if(!$user['Role']['master_cost_list']){
			throw new ForbiddenException();
		}

		if(empty($company) && empty($year) && empty($year)){
			$this->BNewAmount->contain(array(
								'Budget',
								'Budget.Company'=>array('fields'=>array('company')),
							));
			$mcls = $this->BNewAmount->find('all',array(
										'conditions'=>array("Budget.budget_status"=>"approved"),
										'fields'=>array('SUM(BNewAmount.amount) as totalBudget','Budget.company_id','Budget.year','Budget.quarter'),
										'group'=>array('Budget.company_id','Budget.year','Budget.quarter'),
										'order'=>array('Budget.year DESC','Budget.company_id ASC')
									));
			// debug($mcls);exit();
			$this->set('mcls',$mcls);
			$this->render('mastercostlist');
		}
		elseif(isset($action) && $action == 'pdf'){
			// pdf
			$this->Group->contain(array(
								'Department'=>array('fields'=>array('department_id','department_name')),
							));
			$groups = $this->Group->find('all',array('conditions'=>array('Group.company_id'=>$company,'Group.group_id IN (SELECT group_id FROM departments)'),
													'fields'=>array('Group.group_name','Group.company_id')));
			$this->set('groups',$groups);
			// debug($groups);exit();

			$this->BNewAmount->contain(array(
								'Budget',
								'BItem',
								'BItem.BCategory'=>array('fields'=>array('category')),
								'Department'
							));
			$budgets = $this->BNewAmount->find('all',array(
										'conditions'=>array('Budget.company_id'=>$company,'Budget.year'=>$year,"Budget.quarter"=>$quarter,"Budget.budget_status"=>"approved"),
										'fields'=>array('SUM(BNewAmount.amount) as totalAmount','BNewAmount.department_id','BNewAmount.item_id','BItem.item','BItem.category_id','Department.department_name'),
										'group'=>array('BNewAmount.item_id','BNewAmount.department_id'),
										'order'=>array('BItem.category_id','BNewAmount.item_id','BNewAmount.department_id')
									));
			$this->set('budgets',$budgets);
			// debug($budgets);exit();

			$this->Company->recursive = -1;
			$companyData = $this->Company->findByCompanyId($company);
			$this->set('mclsCompany',$companyData);

			// set the quarter
			$this->set('mclsQuarter',$quarter);
			//also set year
			$this->set('mclsYear',$year);
		

			$pdfSize = ucfirst(substr($extra,0,2)); // only get first 2 - a4,a3,a2,a1
			$this->layout = 'mems-pdf-mastercostlist';
	 		$this->pdfConfig = array(
				'orientation'=>'landscape',
				'pageSize'=>$pdfSize,
				'filename'=>$pdfSize."_".$companyData['Company']['company']."_".$quarter."_".$year.".pdf",
				'download'=>true,
			);
			$this->render('mastercostlist_detail_pdf');
		}
		elseif(isset($action) && $action == 'excel'){
			$this->Group->contain(array(
								'Department'=>array('fields'=>array('department_id','department_name')),
							));
			$groups = $this->Group->find('all',array('conditions'=>array('Group.company_id'=>$company,'Group.group_id IN (SELECT group_id FROM departments)'),
													'fields'=>array('Group.group_name','Group.company_id')));
			$this->set('groups',$groups);
			// debug($groups);exit();

			$this->BNewAmount->contain(array(
								'Budget',
								'BItem',
								'BItem.BCategory'=>array('fields'=>array('category')),
								'Department'
							));
			$budgets = $this->BNewAmount->find('all',array(
										'conditions'=>array('Budget.company_id'=>$company,'Budget.year'=>$year,"Budget.quarter"=>$quarter,"Budget.budget_status"=>"approved"),
										'fields'=>array('SUM(BNewAmount.amount) as totalAmount','BNewAmount.department_id','BNewAmount.item_id','BItem.item','BItem.category_id','Department.department_name'),
										'group'=>array('BNewAmount.item_id','BNewAmount.department_id'),
										'order'=>array('BItem.category_id','BNewAmount.item_id','BNewAmount.department_id')
									));
			$this->set('budgets',$budgets);

			$this->Company->recursive = -1;
			$companyData = $this->Company->findByCompanyId($company);

			$this->layout = 'mems-excel';
			$this->render('mastercostlist_excel');
	        $this->excelConfig =  array(
	            'filename' => $companyData['Company']['company'].'_'.$year.'_'.$quarter.'.xlsx'
	        );
		}
		else{
			$this->Group->contain(array(
								'Department'=>array('fields'=>array('department_id','department_name')),
							));
			$groups = $this->Group->find('all',array('conditions'=>array('Group.company_id'=>$company,'Group.group_id IN (SELECT group_id FROM departments)'),
													'fields'=>array('Group.group_name','Group.company_id')));
			$this->set('groups',$groups);
			// debug($groups);exit();

			$this->BNewAmount->contain(array(
								'Budget',
								'BItem',
								'BItem.BCategory'=>array('fields'=>array('category')),
								'Department'
							));
			$budgets = $this->BNewAmount->find('all',array(
										'fields'=>array('SUM(BNewAmount.amount) as totalAmount','BNewAmount.department_id','BNewAmount.item_id','BItem.item','BItem.category_id','Department.department_name'),
										'conditions'=>array('Budget.company_id'=>$company,'Budget.year'=>$year,"Budget.quarter"=>$quarter,
														"Budget.budget_status"=>"approved"),
										'group'=>array('BNewAmount.item_id','BNewAmount.department_id'),
										'order'=>array('BItem.category_id','BNewAmount.item_id','BNewAmount.department_id')
									));
			$this->set('budgets',$budgets);
			// debug($budgets);exit();

			$this->Company->recursive = -1;
			$companyData = $this->Company->findByCompanyId($company);
			$this->set('mclsCompany',$companyData);

			// set the quarter
			$this->set('mclsQuarter',$quarter);
			//also set year
			$this->set('mclsYear',$year);
			
			$this->render('mastercostlist_detail');
		}
		// $mastercostlist = $this->BNewAmount->find()
	}

	//phase 2: budget data retrieval
	public function budgetReflection($item_amount_id){
		$this->layout = 'mems-empty';
		$this->BItemAmount->contain(array(
			'Item'=>array('fields'=>array('item_code','item')),
			'BDepartment.Department'=>array('fields'=>array('Department.department_name','Department.department_shortform')),
			'FMemoBudget'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no','subject')),'BItemAmountTransfer'=>array('fields'=>''),'BItemAmountTransfer.Item'=>array('fields'=>'item'),'BItemAmountTransfer.BDepartment.Department'=>array('fields'=>'department_shortform')),
			'FMemoBudgetTransfer'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no','subject')),'BItemAmountT'=>array('fields'=>array()),'BItemAmountT.Item'=>array('fields'=>'item'),'BItemAmountT.BDepartment.Department'=>array('fields'=>'department_shortform')),
			));
		$reflection=$this->BItemAmount->find('first',array('conditions'=>array('item_amount_id'=>$item_amount_id),'fields'=>array('amount','item_amount_id','item_id','b_dept_id','BDepartment.department_id')));
		// debug($reflection);exit;
		$this->set('reflection',$reflection);

	}

	//phase 2: budget data retrieval
	public function budgetData($budgetid,$deptid){
		$this->Budget->recursive=-1;
		$budgetDetail=$this->Budget->findByBudgetId($budgetid);
		$this->BDepartment->contain(array('Department'));
		
		$deptAcad = $this->BDepartment->find('list',array('fields'=>array('BDepartment.b_dept_id','Department.department_shortform'),'conditions'=>array('BDepartment.department_type'=>'1','BDepartment.budget_id'=>$budgetid),'order'=>'BDepartment.sequence ASC'));
		
		$this->BDepartment->contain(array('Department'));
		
		$deptNonAcad = $this->BDepartment->find('list',array('fields'=>array('BDepartment.b_dept_id','Department.department_shortform'),'conditions'=>array('BDepartment.department_type'=>'2','BDepartment.budget_id'=>$budgetid),'order'=>'BDepartment.sequence ASC'));
		// $this->set('itemList',$itemList);
		
		$cond=array();
		// $condUnbudgeted=array('BUnbudgetedItem.budget_id'=>$budgetid,'FMemo.memo_status'=>'approved');
		//dept specific budget retrieval (get ytd n dept data only)
		if (!empty($deptid)){
			$this->BDepartment->recursive=-1;
			$deptBudget=$this->BDepartment->find('first',array('fields'=>('b_dept_id'),'conditions'=>array('budget_id'=>$budgetid,'department_id'=>$deptid)));
			$bdeptid=0;
			if (!empty($deptBudget))
				$bdeptid=$deptBudget['BDepartment']['b_dept_id'];

			$cond['OR']=array('BItemAmount.b_dept_id IS NULL','BItemAmount.b_dept_id'=>$bdeptid);
			// $condUnbudgeted['BItemAmount.department_id']=$deptid;

		}
		
		$this->BItemGroup->contain(array('BItemAmount'=>array(
			'fields'=>array('BItemAmount.item_amount_id','BItemAmount.item_id','BItemAmount.amount','BItemAmount.b_dept_id'),
			'conditions'=>$cond,
			'Item'=>array('fields'=>array('Item.code_item')),
			'BDepartment'=>array('fields'=>array('department_type')),
			'FMemoBudget'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no'))),
			'FMemoBudgetTransfer'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no'))),
			'order'=>'item_amount_id ASC'
		)));

		$budgetR = $this->BItemGroup->find('all',array('fields'=>array('BItemGroup.group_type'),'conditions'=>array('BItemGroup.budget_id'=>$budgetid,'BItemGroup.group_type'=>'Revenue'),'order'=>'BItemGroup.sequence ASC'));
		
		$this->BItemGroup->contain(array('BItemAmount'=>array(
			'fields'=>array('BItemAmount.item_amount_id','BItemAmount.item_id','BItemAmount.amount','BItemAmount.b_dept_id'),
			'conditions'=>$cond,
			'Item'=>array('fields'=>array('Item.code_item')),
			'BDepartment'=>array('fields'=>array('department_type')),
			'FMemoBudget'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no'))),
			'FMemoBudgetTransfer'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no'))),
			'order'=>'item_amount_id ASC'

		)));
		

		$budgetCOR = $this->BItemGroup->find('all',array('fields'=>array('BItemGroup.group_type'),'conditions'=>array('BItemGroup.budget_id'=>$budgetid,'BItemGroup.group_type'=>'Cost of Revenue'),'order'=>'BItemGroup.sequence ASC'));

		$this->BItemGroup->contain(array('BItemAmount'=>array(
			'fields'=>array('BItemAmount.item_amount_id','BItemAmount.item_id','BItemAmount.amount','BItemAmount.b_dept_id'),
			'conditions'=>$cond,
			'Item'=>array('fields'=>array('Item.code_item')),
			'BDepartment'=>array('fields'=>array('department_type')),
			'FMemoBudget'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no'))),
			'FMemoBudgetTransfer'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no'))),
			'order'=>'item_amount_id ASC'

		)));

		$budgetOI = $this->BItemGroup->find('all',array('fields'=>array('BItemGroup.group_type'),'conditions'=>array('BItemGroup.budget_id'=>$budgetid,'BItemGroup.group_type'=>'Other Income'),'order'=>'BItemGroup.sequence ASC'));

		$this->BItemGroup->contain(array('BItemAmount'=>array(
			'fields'=>array('BItemAmount.item_amount_id','BItemAmount.item_id','BItemAmount.amount','BItemAmount.b_dept_id'),
			'conditions'=>$cond,
			'Item'=>array('fields'=>array('Item.code_item')),
			'BDepartment'=>array('fields'=>array('department_type')),
			'FMemoBudget'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no'))),
			'FMemoBudgetTransfer'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no'))),
			'order'=>'item_amount_id ASC'

		)));

		$budgetE = $this->BItemGroup->find('all',array('fields'=>array('BItemGroup.group_type'),'conditions'=>array('BItemGroup.budget_id'=>$budgetid,'BItemGroup.group_type'=>'Expenses'),'order'=>'BItemGroup.sequence ASC'));

		$this->BItemGroup->contain(array('BItemAmount'=>array(
			'fields'=>array('BItemAmount.item_amount_id','BItemAmount.item_id','BItemAmount.amount','BItemAmount.b_dept_id'),
			'conditions'=>$cond,
			'Item'=>array('fields'=>array('Item.code_item')),
			'BDepartment'=>array('fields'=>array('department_type')),
			'FMemoBudget'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no'))),
			'FMemoBudgetTransfer'=>array('fields'=>array('amount','unbudgeted_amount','transferred_amount','transferred_item_amount_id'),'FMemo'=>array('conditions'=>array('memo_status'=>'approved'),'fields'=>array('ref_no'))),
			'order'=>'item_amount_id ASC'

		)));

		$budgetT = $this->BItemGroup->find('all',array('fields'=>array('BItemGroup.group_type'),'conditions'=>array('BItemGroup.budget_id'=>$budgetid,'BItemGroup.group_type'=>'Taxation'),'order'=>'BItemGroup.sequence ASC'));

		// debug($budgetR);exit;
		return array('deptAcad'=>$deptAcad,'deptNonAcad'=>$deptNonAcad,'budgetR'=>$budgetR,'budgetCOR'=>$budgetCOR,'budgetOI'=>$budgetOI,'budgetE'=>$budgetE,'budgetT'=>$budgetT);


	}
	
	//phase2: preview page
	public function viewOnly($budgetid){

	}

	//phase2: preview page
	public function preview($budgetid){
		#ememo2
		$user = $this->getAuth();
		// var_dump($budgetid);exit();
		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);
		
		$this->Budget->recursive = -1;
		$this->Budget->contain(array('Company'));
		$budgetDetail = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		$this->set('budgetDetail',$budgetDetail);
		$this->set('budgetID',$budgetid);
		//check accessing user type
		// only requestor/reviewer can access this page
		if( !(($isRequestor=$this->isRequestor($budgetid))||($isReviewer=$this->isReviewer($budgetid)))){
			$this->Session->setFlash('You are not the requestor of this budget <br><small> <em> Only the requestor/reviewer is allowed to view this page </em></small>','flash.error');
			$this->redirect($this->referer());
		}

		//check for item that has been used in memo regardless of the memo status
		$this->FMemoBudget->contain(array('BItemAmount'));
		$bMemoExist=$this->FMemoBudget->find('all',array('fields'=>array('FMemoBudget.item_amount_id'),'conditions'=>array('BItemAmount.budget_id'=>$budgetid)));

		$this->set('bMemoExist',$bMemoExist);
		//get the item data for budget
		$budgetData=$this->budgetData($budgetid,null);
		// $this->BUnbudgetedItem->recursive=-1;
		// $unbudgeted=$this->BUnbudgetedItem->find('all',array('fields'=>array('item_id','amount','department_id'),'conditions'=>array('BUnbudgetedItem.budget_id'=>$budgetid)));

		$items=array();
		$this->BItemAmount->contain(array('Item'=>array()));
		// $itemList=$this->BItemAmount->find('all',array('conditions'=>array('BItemAmount.budget_id'=>$budgetid,'BItemAmount.department_id IS NULL','BItemAmount.item_id NOT'=>'-1'),'fields'=>array('BItemAmount.item_id','Item.item','Item.item_code')));
		$itemList=$this->BItemAmount->find('all',array('conditions'=>array('BItemAmount.budget_id'=>$budgetid,'BItemAmount.b_dept_id IS NULL'),'fields'=>array('BItemAmount.item_id','Item.item','Item.item_code')));
		if (!empty($itemList)):
			foreach ($itemList as  $value) {
				$items[$value['Item']['item_id']]=$value['Item']['item_code'].' - '.$value['Item']['item'];
			}
		endif;
		//debug ($items);exit;

		$this->set('items',$items);
		$this->set('deptAcad',$budgetData['deptAcad']);
		$this->set('deptNonAcad',$budgetData['deptNonAcad']);
		
		$this->set('budgetR',$budgetData['budgetR']);
		$this->set('budgetCOR',$budgetData['budgetCOR']);
		$this->set('budgetOI',$budgetData['budgetOI']);
		$this->set('budgetE',$budgetData['budgetE']);
		$this->set('budgetT',$budgetData['budgetT']);
		//debug($budgetData['budgetR']);exit;
		// $this->set('budgetU',$budgetData['budgetU']);
		//$this->set('unbudgetedData',$budgetData['unbudgetedData']);
		// debug ($budgetData['budgetR']);exit;
	}


	public function request($budgetid){
		#ememo2
		$user = $this->getAuth();
		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);
		
		$this->Budget->recursive = -1;
		$this->Budget->contain(array('Company'));
		$budgetDetail = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		//first check if the user can access this page to edit (only resubmit/approved not yet used/incomplete cn edit thru this)
		$this->FMemoBudget->contain(array('BItemAmount')) ;
		$bMemoExist=$this->FMemoBudget->find('first',array('conditions'=>array('BItemAmount.budget_id'=>$budgetid)));
		//debug ($bMemoExist);exit;
		// only requestor/reviewer can access this page
		if( !($this->isRequestor($budgetid)||$this->isReviewer($budgetid))){
			$this->Session->setFlash('You are not the requestor of this budget <br><small> <em> Only the creator/reviewer is allowed to edit this budget </em></small>','flash.error');
			$this->redirect($this->referer());
		}
		// accessible up to approved but unused budget by financial memo,else redirect to per item edit page
		if( !empty($bMemoExist)){
			$this->redirect(array('controller'=>'budget','action'=>'preview',$encBudgetID));
			
		}

		$this->set('budgetDetail',$budgetDetail);

		$compList=$this->Company->find('list');
		$this->set('compList',$compList);
		$this->BDepartment->contain(array('Department'=>array('fields'=>'code_name')));
		$selectedAcad=$this->BDepartment->find('list',array('fields'=>array('Department.department_id','CONCAT(Department.department_shortform, " - ", Department.department_name)'),'conditions'=>array('BDepartment.budget_id'=>$budgetDetail['Budget']['budget_id'],'BDepartment.department_type'=>'1'),'order'=>'b_dept_id ASC'));
		$this->set('selectedAcad',$selectedAcad);
		// debug ($selectedAcad);exit;
		$this->BDepartment->contain(array('Department'=>array('fields'=>'code_name')));
		// $this->BDepartment->contain(array('Department'));
		$selectedNonAcad=$this->BDepartment->find('list',array('fields'=>array('Department.department_id','CONCAT(Department.department_shortform, " - ", Department.department_name)'),'conditions'=>array('BDepartment.budget_id'=>$budgetDetail['Budget']['budget_id'],'BDepartment.department_type'=>'2'),'order'=>'b_dept_id ASC'));

		$this->set('selectedNonAcad',$selectedNonAcad);
		//list of acad depts of the selected company
		$this->Department->contain(array('Group'));
		$acadList=$this->Department->find('list',array('conditions'=>array('Group.company_id'=>$budgetDetail['Budget']['company_id'],'department_type'=>'1','department_id NOT'=>array_keys($selectedAcad)),'order'=>'department_id ASC'));
		$this->set('acadList',$acadList);

		//list of nonacad depts of the selected company
		$this->Department->contain(array('Group'));
		$nonacadList=$this->Department->find('list',array('conditions'=>array('Group.company_id'=>$budgetDetail['Budget']['company_id'],'department_type'=>'2','department_id NOT'=>array_keys($selectedNonAcad)),'order'=>'department_id ASC'));
		$this->set('nonacadList',$nonacadList);

		// $itemList = $this->Item->find('list',array('conditions'=>array('item_id NOT'=>'-1'),'order'=>'item_code ASC'));
		$itemList = $this->Item->find('list',array('order'=>'item_code ASC'));
		
		$this->set('itemList',$itemList);

		//get the item data for budget
		$budgetData=$this->budgetData($budgetid,null);

		$deptAcad=$budgetData['deptAcad'];
		$deptNonAcad =$budgetData['deptNonAcad'];
		$this->set('deptAcad',$deptAcad);
		$this->set('deptNonAcad',$deptNonAcad);
		
		$budgetR = $budgetData['budgetR'];
		$budgetCOR = $budgetData['budgetCOR'];
		$budgetOI = $budgetData['budgetOI'];
		$budgetE = $budgetData['budgetE'];
		$budgetT = $budgetData['budgetT'];
		// $budgetU = $budgetData['budgetU'];
		
		
		$revenueGroup = array();
        $costOfRevenueGroup = array();
        $otherIncomeGroup = array();
        $expensesGroup = array();
        $taxationGroup = array();
        $unbudgetedGroup = array();

        if (!empty($budgetR)):
	       $revenueGroup=$this->getEditData($budgetR,$deptAcad,$deptNonAcad);// debug ($revenueGroup);exit;
        endif;
		if (!empty($budgetCOR)):
	       $costOfRevenueGroup=$this->getEditData($budgetCOR,$deptAcad,$deptNonAcad);
        endif;
        if (!empty($budgetOI)):
	       $otherIncomeGroup=$this->getEditData($budgetOI,$deptAcad,$deptNonAcad);
	    endif;
        if (!empty($budgetE)):
	       $expensesGroup=$this->getEditData($budgetE,$deptAcad,$deptNonAcad);
	    endif;
        if (!empty($budgetT)):
	       $taxationGroup=$this->getEditData($budgetT,$deptAcad,$deptNonAcad);
        endif;
        // if (!empty($budgetU)):
	       // $unbudgetedGroup=$this->getEditData($budgetU,$deptAcad,$deptNonAcad);//because there will alway be only one unbudgeted item per dept
        // endif;
     
		$this->set('revenueGroup',$revenueGroup);
		$this->set('costOfRevenueGroup',$costOfRevenueGroup);
		$this->set('otherIncomeGroup',$otherIncomeGroup);
		$this->set('expensesGroup',$expensesGroup);
		$this->set('taxationGroup',$taxationGroup);
		// $this->set('unbudgetedGroup',$unbudgetedGroup);
		
		// $this->set('budgetItems',$budgetItems);

		$this->set('budgetID',$budgetid);
	}

	private function getEditData($budgetGroup,$deptAcad,$deptNonAcad){
		$groupAll=array();
		foreach ($budgetGroup as $id=>$b){
	        $item=null;
	    	$group=array();

	        for( $i=0; $i < count($b['BItemAmount']); ):
	           	$bi=$b['BItemAmount'];
	            if ( empty($bi['b_dept_id']) ){//new row
	            	$row=array();
	            	$row['item']= $bi[$i]['item_id'];
	            	$row['budgetYTD'] = $bi[$i]['amount'];
					$item=$bi[$i]['item_id'];
					$totalByItem = 0; //init subtotal for each item
					$totalByAcad = 0;
					$totalByNonAcad = 0;
					$i++;
	            }
	        	//iterate thru acad dept
	        	foreach ($deptAcad as $key => $dept) :
	        		
	        		if(isset($bi[$i])):
						if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
							$row[$key]=0;
						}
						else{	

							$totalByItem += $bi[$i]['amount']; //total by item
							$totalByAcad += $bi[$i]['amount']; //total by item for acad
							$row[$key]=$bi[$i]['amount'];
							
							$i++; //update the index to next element
							if ($bi[$i]['BDepartment']['department_type']==2)
								break;
						}
					
					endif;
					

				endforeach;
				
				$row['totAcad']=$totalByAcad;

				//iterate thru acad dept first n set the val
				
				foreach($deptNonAcad as $key => $dept):
					
					if(isset($bi[$i])):
						if (!($key==$bi[$i]['b_dept_id']&&$item==$bi[$i]['item_id'])){
							$row[$key]=0;
						}
						else{	

							$totalByItem += $bi[$i]['amount']; //total by item
							$totalByNonAcad += $bi[$i]['amount']; //total by item for acad
							$row[$key]=$bi[$i]['amount'];
							
							$i++; //update the index to next element
							
						}
					
					endif;
					
				endforeach;
				$row['totNonAcad']=$totalByNonAcad;
				$row['totAll']=$totalByItem;
				
				$group[]=$row;
			endfor;//end foritem 
			$groupAll[]=$group;

		}
		return $groupAll; 

	}
	
	public function confirm($budgetid){

		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);

		$user = $this->getAuth();

		$this->Budget->contain(array(
								'BReviewer',
								'Company',
			));
		$budget = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		
		$this->set('budget',$budget);
		
		$this->set('budgetID',$budgetid);
		//if not 0 submission, check budget status
		if ($budget['Budget']['submission_no']!=0){

			//check the relationship of user and budget 

			$this->BStatus->contain('BReviewer');
			$status=$this->BStatus->find('all',array('conditions'=>array('BStatus.budget_id'=>$budgetid,'BStatus.submission_no'=>$budget['Budget']['submission_no']),'order'=>array('BReviewer.sequence'=> 'ASC')));
			$resubmitFlag=false;
			//if requestor, allow access only if save/any reviewer/recommender/finance reject
			if ($user['user_id']==$budget['Budget']['user_id']){
				// if ($memo['FMemo']['submission_no']==0)
				// 		$resubmitFlag=true;
				// else{

					foreach ($status as $value) {
					//if ($value['FStatus']['status']=='rejected'&&$value['FReviewer']['approval_type']!='approver'){
						if ($value['BStatus']['status']=='rejected'){
							$resubmitFlag=true;
							break;
						}
					}
				//}	
			}

			
			if($resubmitFlag||$budget['Budget']['budget_status']=='approved'){//user is the creator of the budget & there is rejected status or status approved , thus update submission no and reinsert status for another round of approval
				
				$this->Budget->id=$budgetid;
				$submission_no=$budget['Budget']['submission_no']+1;
				$this->Budget->saveField('submission_no',$submission_no);
				$this->Budget->saveField('budget_status','');//reset budget status to empty

				foreach ($budget['BReviewer'] as $value) {
					
					//$id = $value['reviewer_id'];
					$status['BStatus']['reviewer_id'] = $value['reviewer_id'];
					$status['BStatus']['status'] = 'pending';
					$status['BStatus']['submission_no'] = $submission_no;
					$status['BStatus']['budget_id'] = $budgetid;
					$this->BStatus->create();
					$this->BStatus->save($status);
		
				}

				// send review email if resubmit --> does not go to confirmBudget
				$this->sendReviewEmail($budgetid);

				// add notification to the requestor -- stating the budget has been resubmit
				$notiTo = $budget['Budget']['user_id'];
				// $notiText = "You have resubmitted a budget request";
				$notiText = "<b> Budget : </b> ".$budget['Budget']['year']. "<br>".
							"<b> Company : </b> ".$budget['Company']['company']."<br>".
							"<b> Info : </b> Budget resubmitted ";

				$encBudgetID = $this->encrypt($budgetid);
				$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
				//$this->UserNotification->record($notiTo, $notiText, $notiLink);

				#ememo2:
				$notiType="budget-submitted";
				$this->UserNotification->record($notiTo, $notiText, $notiLink,$notiType);


				$this->Session->setFlash("<b> The budget has been resubmitted </b><br/><small>The budget submission no. is now updated </small>",'flash.success');
				
			}
			else
				$this->Session->setFlash("<b> The budget has been updated </b><br/><small>The changes made to the budget have been saved</small>",'flash.success');

			$this->redirect(array('controller'=>'budget','action'=>'dashboard',$encBudgetID));

		}

		else{//first submission so go to add reviewer page
			// $this->Session->setFlash('<b> The budget has been created </b><br/><small>Please complete the step below to finalize the budget</small>','flash.success');

			$finances = $this->User->find('list',array('conditions'=>array('User.finance'=>1,'User.status'=>'enabled')));
			$this->set('finances',$finances);
			$approvers = $this->User->find('list',array('conditions'=>array('User.approver'=>1,'User.status'=>'enabled')));
			$this->set('approvers',$approvers);
		}
		// only requestor can access page
		// if(!$this->isRequestor($budgetid)||$budget['Budget']['submission_no']!=0){
		// 	$this->Session->setFlash('You are not authorized to view this page <br><small> <em> You are not the requestor of this budget/Budget has been submitted </em></small>','flash.error');
		// 	$this->redirect(array('controller'=>'budget','action'=>'index'));
		// }
		// if from resubmit, update submission
		// if($this->request->is('post')){
		// 	$this->Budget->id = $budgetid;
		// 	$this->Budget->saveField('submission_no',$budget['Budget']['submission_no']+1); // will be updated everytime user request

		// 	// // copy budget item to old table
		// 	// $this->copyNewToOld($budgetid);

		// 	// if budget is resubmit -- don't have to go to confirm page --> redirect to dashboard (he/she cannot edit the approver/reviewer list)
		// 	if($budget['Budget']['submission_no'] >=1 ){
		// 		// repopulate bstatus
		// 		$this->BStatus->recursive = -1;
		// 		$bstatus = $this->BStatus->find('all',array('conditions'=>array('BStatus.budget_id'=>$budgetid,'BStatus.submission_no'=>$budget['Budget']['submission_no'])));
		// 		// debug($bstatus);exit();
		// 		foreach($bstatus as $bs):
		// 			$data['BStatus']['reviewer_id'] = $bs['BStatus']['reviewer_id'];
		// 			$data['BStatus']['budget_id'] = $bs['BStatus']['budget_id'];
		// 			$data['BStatus']['status'] = 'pending';
		// 			$data['BStatus']['submission_no'] = $budget['Budget']['submission_no']+1;
		// 			$this->BStatus->create();
		// 			$this->BStatus->save($data);
		// 		endforeach;

		// 		// send review email if resubmit --> does not go to confirmBudget
		// 		$this->sendReviewEmail($budgetid);

		// 		// add notification to the requestor -- stating the budget has been resubmit
		// 		$notiTo = $budget['Budget']['user_id'];
		// 		// $notiText = "You have resubmitted a budget request";
		// 		$notiText = "<b> Budget : </b> ".$budget['Budget']['title']. "<br>".
		// 					"<b> Dept : </b> ".$budget['Department']['department_name']."<br>".
		// 					"<b> Action : </b> Submitted budget";

		// 		$encBudgetID = $this->encrypt($budgetid);
		// 		$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
		// 		$this->UserNotification->record($notiTo, $notiText, $notiLink);

		// 		$this->Session->setFlash("<b> The budget has been resubmitted </b><br/><small>The budget submission no is updated </small>",'flash.success');
		// 		$this->redirect(array('controller'=>'budget','action'=>'dashboard',$encBudgetID));
		// 	}
		// 	else{
		// 		$this->Session->setFlash('<b> The budget has been created </b><br/><small>Please complete the step below to finalize the budget</small>','flash.success');
		// 		$this->redirect(array('controller'=>'budget','action'=>'confirm',$encBudgetID));
		// 	}
			
		// }


		// $totalBudget = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		// debug($totalBudget);exit();
		

		// $selectedFinances = Set::extract('/BReviewer[approval_type=finance]/user_id',$budget);
		// $this->set('selectedFinances',$selectedFinances);
		// // debug($selectedReviewers);exit();

		// $selectedApprovers = Set::extract('/BReviewer[approval_type=approver]/user_id',$budget);
		// $this->set('selectedApprovers',$selectedApprovers);
		// debug($selectedReviewers);exit();

		// $this->request->data = $budget;
	}

	public function review($budgetid){
		
		$user = $this->getAuth();
		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);
		
		$this->Budget->recursive = -1;
		$this->Budget->contain(array('Company','User'=>'Department'));
		$budgetDetail = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		$this->set('budgetDetail',$budgetDetail);
		$this->set('budgetID',$budgetid);
		//check accessing user type

		$setting=$this->Setting->find('first');
		
		
		
		//check user privilege, by default disable all buttons
		$editFlag=false;
		$approvalFlag=false;
		$remarkFlag=false;
		// $commentFlag=false;
		$this->BStatus->contain('BReviewer');
		$status=$this->BStatus->find('all',array('conditions'=>array('BStatus.budget_id'=>$budgetid,'BStatus.submission_no'=>$budgetDetail['Budget']['submission_no']),'order'=>array('BReviewer.sequence'=> 'ASC')));
		//phase 2:allow requestor to edit at any time
		if($user['user_id']==$budgetDetail['Budget']['user_id']){
			if(empty($budgetDetail['Budget']['budget_status'])||($budgetDetail['Budget']['budget_status']=='approved'&&$setting['Setting']['financial_memo']))
				$editFlag=true;

			$remarkFlag=true;
		}

		// else{	//if user is one of the reviewers //disabled because user can be reviewer
			for($i=0;$i<count($status);$i++){

				if($status[$i]['BReviewer']['user_id']==$user['user_id']){					
					// $commentFlag=true;
					$remarkFlag=true;

					
					if ($status[$i]['BStatus']['status']=='pending'){
						if ($i==0||$status[$i-1]['BStatus']['status']=='approved'){//first reviewer or status before is approved
							$editFlag=true;
							$approvalFlag=true;

							break;
						}
					}
				}
				
			}
		// }

		$this->set('editFlag',$editFlag);
 		$this->set('remarkFlag',$remarkFlag);
 		$this->set('approvalFlag',$approvalFlag);
		
		$this->set('budgetID',$budgetid);

		/// for lower remark thing
		$reviewers=$this->getReviewer($budgetid,'finance',$budgetDetail['Budget']['submission_no']); 
		// debug($reviewers); exit();
 		$approvers=$this->getReviewer($budgetid,'approver',$budgetDetail['Budget']['submission_no']); 
 		// debug($approvers);exit();
 		$budgetPpl=array($budgetDetail['Budget']['user_id']);
 		
 		$remark_reviewer=array();
		$remark_approver=array();
		
		if (!empty($reviewers)){
			foreach ($reviewers as $reviewer) {
				$budgetPpl[]=$reviewer['BReviewer']['user_id'];

				$remark_reviewer[]=$this->getRemark($budgetid,$reviewer['BReviewer']['reviewer_id']);
			}
		}

		if (!empty($approvers)){
			foreach ($approvers as $approver) {
				$budgetPpl[]=$approver['BReviewer']['user_id'];

				$remark_approver[]=$this->getRemark($budgetid,$approver['BReviewer']['reviewer_id']);
			}
		}
		//get the item data for budget
		$deptSpecific=false;
		$bdeptid=0;

		// debug($budgetPpl);exit();
// if ($deptSpecific&&!in_array($activeUser['Role']['role_id'], array(17,18)))
		if (in_array($user['user_id'], $budgetPpl)||$user['role_id']==17||$user['finance']){
			$budgetData=$this->budgetData($budgetid,null);
			
		}
		else{//if not creator/reviewer, show only dept specific budget
			$budgetData=$this->budgetData($budgetid,$user['department_id']);
			$deptSpecific=true;
			//find b_dept_id
			$deptBudget=$this->BDepartment->find('first',array('fields'=>('b_dept_id'),'conditions'=>array('budget_id'=>$budgetid,'department_id'=>$user['department_id'])));
			if (!empty($deptBudget))
				$bdeptid=$deptBudget['BDepartment']['b_dept_id'];
		}
		$this->set('deptid',$bdeptid);

		$deptAcad=$budgetData['deptAcad'];
		$deptNonAcad =$budgetData['deptNonAcad'];
		$this->set('deptAcad',$deptAcad);
		$this->set('deptNonAcad',$deptNonAcad);
		
		$this->set('budgetR',$budgetData['budgetR']);
		// debug($budgetData['budgetOI']);exit;
		$this->set('budgetCOR',$budgetData['budgetCOR']);
		$this->set('budgetOI',$budgetData['budgetOI']);
		$this->set('budgetE',$budgetData['budgetE']);
		$this->set('budgetT',$budgetData['budgetT']);
		// $this->set('budgetU',$budgetData['budgetU']);
		$this->set('reviewers',$reviewers);
 		$this->set('approvers',$approvers);
 		$this->set('remark_reviewer',$remark_reviewer);
 		$this->set('remark_approver',$remark_approver);
 		// $this->set('user',$user);
 		$this->set('deptSpecific',$deptSpecific);
		//$this->set('unbudgetedData',$budgetData['unbudgetedData']);
 		
	}

	public function pdf($budgetid,$extra){

		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);

		$user = $this->getAuth();

		$this->Budget->recursive = -1;
		$this->Budget->contain(array('Company','User'=>'Department'));
		$budgetDetail = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		$this->set('budgetDetail',$budgetDetail);
		$this->set('budgetID',$budgetid);
		//check accessing user type

		/// for lower remark thing
		$reviewers=$this->getReviewer($budgetid,'finance',$budgetDetail['Budget']['submission_no']); 
 		$approvers=$this->getReviewer($budgetid,'approver',$budgetDetail['Budget']['submission_no']); 

 		$remark_reviewer=array();
		$remark_approver=array();
 		$budgetPpl=array($budgetDetail['Budget']['user_id']);
		
		if (!empty($reviewers)){
			foreach ($reviewers as $reviewer) {
				$budgetPpl[]=$reviewer['BReviewer']['user_id'];
				
				$remark_reviewer[]=$this->getRemark($budgetid,$reviewer['BReviewer']['reviewer_id']);

			}
		}
		// debug($remark_reviewer);exit();

		if (!empty($approvers)){
			foreach ($approvers as $approver) {
				$budgetPpl[]=$approver['BReviewer']['user_id'];
				
				$remark_approver[]=$this->getRemark($budgetid,$approver['BReviewer']['reviewer_id']);
			}
		}
		//get the item data for budget
		$deptSpecific=false;
		$bdeptid=0;

		// debug($budgetPpl);exit();
// if ($deptSpecific&&!in_array($activeUser['Role']['role_id'], array(17,18)))
		if (in_array($user['user_id'], $budgetPpl)||$user['role_id']==17||$user['finance']){
			$budgetData=$this->budgetData($budgetid,null);
			
		}
		else{//if not creator/reviewer, show only dept specific budget
			$budgetData=$this->budgetData($budgetid,$user['department_id']);
			$deptSpecific=true;
			//find b_dept_id
			$deptBudget=$this->BDepartment->find('first',array('fields'=>('b_dept_id'),'conditions'=>array('budget_id'=>$budgetid,'department_id'=>$user['department_id'])));
			if (!empty($deptBudget))
				$bdeptid=$deptBudget['BDepartment']['b_dept_id'];
		}
		$this->set('deptid',$bdeptid);

		$deptAcad=$budgetData['deptAcad'];
		$deptNonAcad =$budgetData['deptNonAcad'];
		$this->set('deptAcad',$deptAcad);
		$this->set('deptNonAcad',$deptNonAcad);
		
		$this->set('budgetR',$budgetData['budgetR']);
		//debug($budgetData['budgetR']);
		$this->set('budgetCOR',$budgetData['budgetCOR']);
		$this->set('budgetOI',$budgetData['budgetOI']);
		$this->set('budgetE',$budgetData['budgetE']);
		$this->set('budgetT',$budgetData['budgetT']);
		//$this->set('unbudgetedData',$budgetData['unbudgetedData']);
		// $this->set('budgetU',$budgetData['budgetU']);
		$this->set('reviewers',$reviewers);
 		$this->set('approvers',$approvers);
 		$this->set('remark_reviewer',$remark_reviewer);
 		$this->set('remark_approver',$remark_approver);
 		// $this->set('user',$user);
 		$this->set('deptSpecific',$deptSpecific);

 	// 	$this->layout = 'mems-pdf-budget';
 	// 	$this->pdfConfig = array(
		// 	'orientation'=>'portrait',
		// 	'filename'=>$budgetDetail['Budget']['title'].'.pdf',
		// 	'download'=>true,
		// );
		// $this->render('pdf');

		$pdfSize = ucfirst(substr($extra,0,2)); // only get first 2 - a4,a3,a2,a1,a0
			$this->layout = 'mems-pdf-budget';
	 		$this->pdfConfig = array(
				'orientation'=>'landscape',
				'pageSize'=>$pdfSize,
				'filename'=>$pdfSize."_".$budgetDetail['Company']['company']."_".$budgetDetail['Budget']['year'].".pdf",
				'download'=>true,
			);
		$this->render('pdf');

	}

	private function getReviewer($budgetid,$reviewer_type,$submission_no){ //query1

		$reviewer_query = $this->BReviewer->find('all',array(
			'conditions' => array(
				
				'BReviewer.budget_id' => $budgetid,
				'BReviewer.approval_type'=>$reviewer_type,
			),
			'order'=>array(
				'BReviewer.sequence'=>'ASC'
			),
			'contain'=>array(
				'BStatus'=>array(
					'conditions'=>array('BStatus.submission_no'=>$submission_no,'BStatus.budget_id'=>$budgetid)
				),
				'User'=>array(
					'fields'=>array('user_id','staff_name','designation'),
					'Department'=>array('fields'=>array('department_name')),
				),
			)

		));

		return($reviewer_query);

	}

	private function getRemark($budgetid,$reviewer_uid){ //query2

		$remark_query=$this->BRemark->find('all',array(
			'conditions' => array(
		
				'BRemark.budget_id' => $budgetid,
				'BRemark.reviewer_id'=>$reviewer_uid,
			),
			'fields'=>array('subject'),
			'order'=>array('BRemark.created'=>'ASC'),
			'contain'=>array(
				'User'=>array('fields'=>array('staff_name')),
				'BRemarkFeedback'=>array('fields'=>array('feedback','created'),'User'=>array('fields'=>array('staff_name')),'order'=>array('BRemarkFeedback.created'=>'ASC')),
				'BRemarkAssign'=>array('fields'=>array(''),'User'=>array('fields'=>array('staff_name'))),
			)

		));

		return($remark_query);

	}

	public function compare($budgetid,$deptSpecific){
		$this->layout = 'mems-empty';

		$user = $this->getAuth();

		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);

		$this->Budget->contain(array('Company'));
		$budgetDetail = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		$this->set('budgetDetail',$budgetDetail);
		$this->set('budgetID',$budgetid);

		// Budget item data
		//get the item data for budget
		$bdeptid=0;
		
		if ($deptSpecific&&!($user['role_id']==17||$user['finance'])){//if not creator/reviewer, show only dept specific budget
			$budgetData=$this->budgetData($budgetid,$user['department_id']);
			//find b_dept_id
			$deptBudget=$this->BDepartment->find('first',array('fields'=>('b_dept_id'),'conditions'=>array('budget_id'=>$budgetid,'department_id'=>$user['department_id'])));
			if (!empty($deptBudget))
				$bdeptid=$deptBudget['BDepartment']['b_dept_id'];
		}
		else
			$budgetData=$this->budgetData($budgetid,null);

		$this->set('deptid',$bdeptid);

		$deptAcad=$budgetData['deptAcad'];
		$deptNonAcad =$budgetData['deptNonAcad'];
		$this->set('deptAcad',$deptAcad);
		$this->set('deptNonAcad',$deptNonAcad);
		$this->set('deptSpecific',$deptSpecific);
		
		$this->set('budgetR',$budgetData['budgetR']);
		//debug($budgetData['budgetR']);
		$this->set('budgetCOR',$budgetData['budgetCOR']);
		$this->set('budgetOI',$budgetData['budgetOI']);
		$this->set('budgetE',$budgetData['budgetE']);
		$this->set('budgetT',$budgetData['budgetT']);
		// $this->set('budgetU',$budgetData['budgetU']);
		// $this->set('unbudgetedData',$budgetData['unbudgetedData']);

	}

	
	
	/* Action Helper */
	public function createBudget(){
		$user = $this->getAuth();
		//phase 2-check only adminFinance/admin can enter budget
		if (!($user['role_id']==17||$user['finance'])){
			$this->Session->setFlash('<strong> Error creating the budget </strong><br><small> Only admin/Finance Admin is authorized to create budget </small>','flash.error');
				$this->redirect($this->referer());
		}

		if($this->request->is('post')){

			// manual form validation
			$valid = true;
			$data = $this->request->data;
			$this->Budget->set($data);
			if( !$this->Budget->validates()||empty($data['Budget']['year'])||empty($data['Budget']['company_id'])||empty($data['Budget']['acad_id'])|| empty($data['Budget']['nonacad_id'])){
				$this->Session->setFlash('<strong> Error creating the budget </strong><br><small> Please fill in all required fields denoted with * </small>','flash.error');
				$this->redirect($this->referer());
			}
			$this->Budget->recursive=-1;
			$budgetExist=$this->Budget->find('first',array('conditions'=>array('company_id'=>$data['Budget']['company_id'],'year'=>$data['Budget']['year'])));
			//only one budget per year per company
			if( !empty($budgetExist)) {
				$this->Session->setFlash('<strong> Budget for '.$data['Budget']['year'].' has already existed for the selected company and year </strong><br><small> Please edit the existing budget instead of creating a new one </small>','flash.error');
				$this->redirect($this->referer());
			}
			//debug($data);exit;
			//end manual form validation

			$this->Budget->create();
			$this->request->data['Budget']['user_id'] = $user['user_id'];
			
			$this->request->data['Budget']['submission_no'] = 0;
			if($this->Budget->save($this->request->data)){
				$seqDept=1;
				$budgetid = $this->Budget->id;
				//add selected acad depts, if any
				if (!empty($data['Budget']['acad_id'])):
					$dataAcad=array();
					$dataAcad['BDepartment']['budget_id'] = $budgetid;
					$dataAcad['BDepartment']['department_type'] =1;

					foreach ($data['Budget']['acad_id'] as $aid) {
						$this->BDepartment->create();
						
						$dataAcad['BDepartment']['department_id'] = $aid;
						$dataAcad['BDepartment']['sequence'] = $seqDept++;

						$this->BDepartment->save($dataAcad);
					}
				endif;

				//add selected non-acad depts, if any
				if (!empty($data['Budget']['nonacad_id'])):
					
					$dataNonAcad=array();
					$dataNonAcad['BDepartment']['budget_id'] = $budgetid;
					$dataNonAcad['BDepartment']['department_type'] =2;
					foreach ($data['Budget']['nonacad_id'] as $nid) {
						$this->BDepartment->create();
						
						$dataNonAcad['BDepartment']['department_id'] = $nid;
						$dataNonAcad['BDepartment']['sequence'] = $seqDept++;

						$this->BDepartment->save($dataNonAcad);
					}
				endif;

				$encBudgetID = $this->encrypt($budgetid);

				$this->Session->setFlash('<strong>The budget has been successfully created</strong> <br><small> Please proceed by adding item to the budget </small>','flash.success');
				$this->redirect(array('controller'=>'budget','action'=>'request',$encBudgetID));
			}
		}
	}

	/*
	*	Add the item to the budget
	*	@ Nizam
	*/
	public function addDepartment(){
		$user = $this->getAuth();
		//phase 2-check only adminFinance/admin can enter budget
		if (!($user['role_id']==17||$user['finance'])){
			$this->Session->setFlash('<strong> Error editing the budget </strong><br><small> Only admin/Finance Admin is authorized to edit budget </small>','flash.error');
				$this->redirect($this->referer());
		}
		if($this->request->is('post')){
			$budgetid = $this->decrypt($this->request->data['BDepartment']['budget_id']);
			//get the list of items in the budget
			$this->BItemAmount->contain();
			$items=$this->BItemAmount->find('all',array('conditions'=>array('budget_id'=>$budgetid),'group'=>array('item_group_id','item_id')));
			// if add new dept
			if(!empty($this->request->data['BDepartment']['acad_id'])){
				
				$dataAcad=array();
				$dataAcad['BDepartment']['budget_id'] = $budgetid;
				$dataAcad['BDepartment']['department_type'] =1;
				//get the last sequence of acad id for the budget
				$this->BDepartment->contain();
				$maxSeq=$this->BDepartment->find('first',array('fields'=>array('MAX(sequence) AS seq'),'conditions'=>array('budget_id'=>$budgetid,'department_type'=>1)));
				$seq=$maxSeq[0]['seq'];
				$this->BDepartment->contain();
				//get the nonacad list to reassign the sequence after adding acad dept
				$tempNonAcad=$this->BDepartment->find('all',array('conditions'=>array('budget_id'=>$budgetid,'department_type'=>2), 'order'=>'sequence ASC'));
				

				// debug ($items);exit;
				foreach ($this->request->data['BDepartment']['acad_id'] as $acad) {
					
						$this->BDepartment->create();
						
						$dataAcad['BDepartment']['department_id'] = $acad;
						$dataAcad['BDepartment']['sequence'] = ++$seq;

						$this->BDepartment->save($dataAcad);
						$bdeptid=$this->BDepartment->id;
						//add item amount for this dept
						foreach ($items as $itm) {
							$itemInfo=array('sequence'=>0,'item_group_id'=>$itm['BItemAmount']['item_group_id'],'budget_id'=>$budgetid,'item_id'=>$itm['BItemAmount']['item_id'],'b_dept_id'=>$bdeptid,'amount'=>'0.00');
							$this->BItemAmount->create();
							$this->BItemAmount->save ($itemInfo);
						}

				}

				//reassign the sequence of non acad dept
				foreach ($tempNonAcad as $tna) {
					$this->BDepartment->id=$tna['BDepartment']['b_dept_id'];
					$this->BDepartment->saveField('sequence',++$seq);
				}
				
			}
			//add non acad, if exist
			if(!empty($this->request->data['BDepartment']['nonacad_id'])){
				
				$dataNonAcad=array();
				$dataNonAcad['BDepartment']['budget_id'] = $budgetid;
				$dataNonAcad['BDepartment']['department_type'] =2;
				//get the last sequence of nonacad id for the budget
				$this->BDepartment->contain();
				$maxSeq=$this->BDepartment->find('first',array('fields'=>array('MAX(sequence) AS seq'),'conditions'=>array('budget_id'=>$budgetid,'department_type'=>2)));
				$seq=$maxSeq[0]['seq'];
				$this->BDepartment->contain();

				// debug ($items);exit;
				foreach ($this->request->data['BDepartment']['nonacad_id'] as $nonacad) {
					
						$this->BDepartment->create();
						
						$dataNonAcad['BDepartment']['department_id'] = $nonacad;
						$dataNonAcad['BDepartment']['sequence'] = ++$seq;

						$this->BDepartment->save($dataNonAcad);
						$bdeptid=$this->BDepartment->id;
						//add item amount for this dept
						foreach ($items as $itm) {
							$itemInfo=array('sequence'=>0,'item_group_id'=>$itm['BItemAmount']['item_group_id'],'budget_id'=>$budgetid,'item_id'=>$itm['BItemAmount']['item_id'],'b_dept_id'=>$bdeptid,'amount'=>'0.00');
							$this->BItemAmount->create();
							$this->BItemAmount->save ($itemInfo);
						}

				}

			}
			$this->Session->setFlash('<strong> Changes have been saved. </strong>','flash.success');
		}
		$this->redirect($this->referer());

	}
	/*
	*	Add the item to the budget
	*	@ Nizam
	*/
	public function addBudgetItem(){
		$user = $this->getAuth();
		if($this->request->is('post')){

			// manual form validation
			$this->BNewAmount->set($this->request->data);
			if( !$this->BNewAmount->validates() ){
				$this->Session->setFlash('<strong> Error adding item to budget </strong><br><small> Please fill in all required field with the correct format </small>','flash.error');
				$this->redirect($this->referer()); //bring back to form
			}
			// end manual form validation

			// if add new item
			if(!empty($this->request->data['BItem']['item'])){
				// if add new category -- save category first
				if(!empty($this->request->data['BCategory']['category'])){
					if($this->BCategory->save($this->request->data['BCategory'])){
						$categoryID = $this->BCategory->id;
						$this->request->data['BItem']['category_id'] = $categoryID;
					}
				}

				// if save category success - save the item
				if($this->BItem->save($this->request->data['BItem'])){
					$itemID = $this->BItem->id;
					$this->request->data['BNewAmount']['item_id'] = $itemID;
				}
			}
			// debug($this->request->data);exit();
			// check if item already added to budget
			$itemExist = $this->BNewAmount->find('first',array('conditions'=>array(
									'BNewAmount.item_id'=>$this->request->data['BNewAmount']['item_id'],
									'BNewAmount.budget_id'=>$this->request->data['BNewAmount']['budget_id']
								)));
			if($itemExist){
				$this->Session->setFlash('<b> The item is already in the budget </b><br><small> Please use the edit function to change the amount </small>','flash.error');
				$this->redirect($this->referer());
			}

			// save the whole budget
			// $this->request->data['BNewAmount']['department_id'] = $user['department_id'];
			$this->request->data['BNewAmount']['user_id'] = $user['user_id']; // who add the item

			// save the budget
			if($this->BNewAmount->save($this->request->data['BNewAmount'])){
				$this->Session->setFlash('<b> The item has been added to the budget </b><br><small> You may add another item or confirm the budget</small>','flash.success');
				$this->redirect($this->referer());
			}
		}
	}

	//phase 2: get list of item for budget creation
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
	*	Edit the budget amount of item before confirm the budget by requestor
	*	@ Nizam
	*/
	public function editBudgetItem(){
		if($this->request->is('post')){
			$user = $this->getAuth();

			$itemid = $this->request->data['BItemAmount']['item_amount_id'];
			$amount = $this->request->data['BItemAmount']['amount'];

			if (!$this->BItemAmount->exists($itemid)) {

				throw new ForbiddenException();

			}

			if (!is_numeric($amount))
				$amount=0;
			// now update the new amount
			$this->BItemAmount->id = $itemid;
			if($this->BItemAmount->saveField('amount',$amount)){

				#update the status to pending, resubmission no +1,
				$this->Session->setFlash('<b> The item amount has been successfully updated </b><br><small> Thank You </small>','flash.success');
				$this->redirect($this->referer());
			}
			else{
				$this->Session->setFlash('<b> Error updating the budget amount </b><br><small> Please contact administrator </small>','flash.error');
				$this->redirect($this->referer());
			}
		}
	}

	/*
	*	Edit the budget amount of item by reviewer / approver
	*	@ Nizam
	*/
	public function editBudgetAmount(){
		if($this->request->is('post')){
			// manual form validation
			$this->BNewAmount->set($this->request->data);
			if( !$this->BNewAmount->validates() ){
				$this->Session->setFlash('<strong> Error updating budget </strong><br><small> Please fill in all required field with the correct format </small>','flash.error');
				$this->redirect($this->referer()); //bring back to form
			}

			$user = $this->getAuth();
			// debug($this->request->data);exit();
			$amountid = $this->request->data['BNewAmount']['amount_id'];
			$userid = $user['user_id'];
			// save the old amount ( keep track of changed value )
			$amount = $this->BNewAmount->find('first',array('conditions'=>array('BNewAmount.amount_id'=>$amountid)));
			
			// only copy if its not from requestor -- requestor will only able to have original budget or resubmit budget
			if($amount['BNewAmount']['user_id'] != $amount['Budget']['user_id']){
				$oldAmount['BOldAmount']['budget_id'] = $amount['BNewAmount']['budget_id'];
				$oldAmount['BOldAmount']['department_id'] = $amount['BNewAmount']['department_id'];
				$oldAmount['BOldAmount']['item_id'] = $amount['BNewAmount']['item_id'];
				$oldAmount['BOldAmount']['user_id'] = $amount['BNewAmount']['user_id']; // previous budget editor
				$oldAmount['BOldAmount']['amount'] = $amount['BNewAmount']['amount'];
				$oldAmount['BOldAmount']['submission_no'] = $amount['Budget']['submission_no'];
				$this->BOldAmount->create();
				$this->BOldAmount->save($oldAmount);
			}

			// now update the new amount
			$this->request->data['BNewAmount']['user_id'] = $userid;
			$this->BNewAmount->id = $amountid;
			if($this->BNewAmount->save($this->request->data)){
				$this->Session->setFlash('The budget item has been successfully edited <br><small> You can approve / reject the budget </small>','flash.success');
				$this->redirect($this->referer());
			}
		}
	}

	/*
	*	Delete the budget item
	*	@ Nizam
	*/
	public function deleteBudgetItem($amountid){
		if($this->request->is('post')){
			if( $this->BNewAmount->delete($amountid) ){
				$this->Session->setFlash('You have successfully delete the budget item <br><small> Thank You </small>','flash.success');
				$this->redirect($this->referer());
			}
		}
	}

	public function confirmBudget(){
		// debug($this->request->data);exit();
		if($this->request->is(array('post','put'))){

			// check if form is already submitted
			$budgetid = $this->request->data['Budget']['budget_id'];
			$this->BReviewer->recursive = -1;
			$checkR = $this->BReviewer->find('all',array('conditions'=>array('BReviewer.budget_id'=>$budgetid)));
			if(!empty($checkR)){
				$this->Session->setFlash('<b> This budget has been confirmed / submitted before </b><br><small> Please consult administrator </small>','flash.error');
				$this->redirect(array('controller'=>'budget','action'=>'index'));
			}

			// manual form validation
			if( empty($this->request->data['BReviewer']['finance']) || empty($this->request->data['BReviewer']['approver']) ){
				$this->Session->setFlash('<b> Error confirming the budget </b><br><small>You must select the finance reviewer and approver</small>','flash.error');
				$this->redirect($this->referer());
			}
			// end manual form validation

			// cannot have same reviewer and approver
			$intersected = array_intersect($this->request->data['BReviewer']['finance'], $this->request->data['BReviewer']['approver']);
			if(!empty($intersected)){
				$this->Session->setFlash('<b> A finance reviewer cannot be an approver for the same budget </b><br><small>Please reselect the finance reviewer and approver</small>','flash.error');
				$this->redirect($this->referer());
			}		

			// debug($this->request->data);exit();
			$budgetid = $this->request->data['Budget']['budget_id'];
			$this->Budget->id = $budgetid;
			$this->Budget->saveField('remark',$this->request->data['Budget']['remark']);
			$this->Budget->saveField('submission_no',1);

			// $this->Budget->recursive = -1;
			$this->Budget->contain(array('Company'));
			$budget = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));

			// save the finance reviewer
			$seq=1;
			foreach($this->request->data['BReviewer']['finance'] as $f):
				$finance['BReviewer']['budget_id'] = $budgetid;
				$finance['BReviewer']['user_id'] = $f;
				$finance['BReviewer']['sequence'] = $seq++;
				$finance['BReviewer']['approval_type'] = 'finance';
				$this->BReviewer->create();
				if($this->BReviewer->save($finance)){
					$financeid = $this->BReviewer->id;
					$status['BStatus']['reviewer_id'] = $financeid;
					$status['BStatus']['status'] = 'pending';
					$status['BStatus']['submission_no'] = 1;
					$status['BStatus']['budget_id'] = $budgetid;
					$this->BStatus->create();
					$this->BStatus->save($status);
				}
			endforeach;
			// save the approver
			foreach($this->request->data['BReviewer']['approver'] as $a):
				$approver['BReviewer']['budget_id'] = $budgetid;
				$approver['BReviewer']['user_id'] = $a;
				$approver['BReviewer']['sequence'] = $seq++;
				$approver['BReviewer']['approval_type'] = 'approver';
				$this->BReviewer->create();
				if($this->BReviewer->save($approver)){
					$approverid = $this->BReviewer->id;
					$status['BStatus']['reviewer_id'] = $approverid;
					$status['BStatus']['status'] = 'pending';
					$status['BStatus']['submission_no'] = 1;
					$status['BStatus']['budget_id'] = $budgetid;
					$this->BStatus->create();
					$this->BStatus->save($status);
				}
			endforeach;

			// send email to first finance reviewer
			$this->sendReviewEmail($budgetid);

			// add notification to the requestor -- stating the budget has been created
			$notiTo = $budget['Budget']['user_id'];
			// $notiText = "You have submitted a budget request";
			$notiText = "<b> Budget : </b> ".$budget['Budget']['year']. "<br>".
							"<b> Company : </b> ".$budget['Company']['company']."<br>".
							"<b> Info : </b> Budget submitted";

			$encBudgetID = $this->encrypt($budgetid);
			$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
			//$this->UserNotification->record($notiTo, $notiText, $notiLink);
			#ememo2:
			$notiType="budget-submitted";
			$this->UserNotification->record($notiTo, $notiText, $notiLink,$notiType);


			//send email to all reviewers except the first one
			// $this->BReviewer->contain(array('Budget'));
			$this->BReviewer->recursive = -1;
			$reviewers = $this->BReviewer->find('all',array('conditions'=>array(
													'BReviewer.budget_id'=>$budgetid, //only for this 
													'BReviewer.sequence != 1',
													// 'Budget.submission_no'=>1, //only for first submission
												)));
			$this->Budget->contain(array('User','Company'));
			$budget = $this->Budget->findByBudgetId($budgetid);
			$budget['Budget']['User'] = $budget['User'];
			$budget['Budget']['Company'] = $budget['Company'];
			// unset($budget['User']);
			// unset($budget['Department']);
			$rev=array();
			foreach($reviewers as $r):
				$rev[$r['BReviewer']['user_id']]=$r['BReviewer']['user_id'];
			endforeach;

			foreach($rev as $uid):
				// add notification to the other reviewers that budget has been created 
				$notiTo = $uid;
				// $notiText = "Your budget request has been approved";
				$notiText = "<b> Budget : </b> ".$budget['Budget']['year']. "<br>".
							"<b> Company : </b> ".$budget['Company']['company']."<br>".
							"<b> Info : </b> Budget added";

				$encBudgetID = $this->encrypt($budget['Budget']['budget_id']);
				$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
				// $this->UserNotification->record($notiTo, $notiText, $notiLink);
				#ememo2:
				$notiType="budget-added";
				$this->UserNotification->record($notiTo, $notiText, $notiLink,$notiType);


				//send email to other -- requested on 3/7/2015 and cancelled on 14/7/2015 --not going to do it again
				// $toreviewer= $uid;
				// $encBudgetID = $this->encrypt($budgetid);
				// $link = $this->goLink($uid,array('controller'=>'budget','action'=>'dashboard',$encBudgetID));

				// $email = "This is to inform you that a new memo has been created with the following parameters.<br>";
				// $email .= $this->budgetTable($budgetid,$budget['Budget']);
				// $email .= "You have been added to the memo as a Reviewer/ Recommender/ Approver. <br> You will be notified via email when your further action on this memo is required. <br> Thank You";
				// $email .= "<a href='{$link}' class='btn btn-success'> Go to budget dashboard </a>";
				// $subject = $budget['Budget']['title']." (Budget created)";

				// $this->emailMe($toreviewer,$subject,$email);

			endforeach;

			$this->Session->setFlash('The budget has been submitted for review <br><small> You will be notified if the status changed later </small>','flash.success');
			$this->redirect(array('controller'=>'budget','action'=>'index'));
		}

		

	}

	/*
	*	copy new budget to old budget everytime budget is fully approved (phase 2:budget cn be submitted many times, after fully approved)
	*	@ Nizam 
	*	(tested working on 16/2/2015)
	*/
	private function copyNewToOld($budgetid){

		// get the budget
		$this->Budget->contain(array('BItemAmount',"BItemGroup","BDepartment"));
		$budget = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));

		//delete the budget previously approved first, if exist
		$this->BOldDepartment->deleteAll(array('BOldDepartment.budget_id'=>$budgetid));
		$this->BOldItemGroup->deleteAll(array('BOldItemGroup.budget_id'=>$budgetid));
		$this->BOldItemAmount->deleteAll(array('BOldItemAmount.budget_id'=>$budgetid));
		
		//copy the budget to old record to keep track of older data (for comparison)
		foreach($budget['BDepartment'] as $new):
			$old['BOldDepartment']['b_dept_id'] = $new['b_dept_id'];
			$old['BOldDepartment']['budget_id'] = $new['budget_id'];
			$old['BOldDepartment']['department_id'] = $new['department_id'];
			$old['BOldDepartment']['department_type'] = $new['department_type'];
			

			$this->BOldDepartment->create();
			$this->BOldDepartment->save($old);
		endforeach;

		//copy the budget to old record to keep track of older data (for comparison)
		foreach($budget['BItemGroup'] as $new):
			$old['BOldItemGroup']['item_group_id'] = $new['item_group_id'];
			$old['BOldItemGroup']['budget_id'] = $new['budget_id'];
			$old['BOldItemGroup']['group_type'] = $new['group_type'];
			

			$this->BOldItemGroup->create();
			$this->BOldItemGroup->save($old);
		endforeach;

		//copy the budget to old record to keep track of older data (for comparison)
		foreach($budget['BItemAmount'] as $new):
			$old['BOldItemAmount']['item_amount_id'] = $new['item_amount_id'];
			$old['BOldItemAmount']['item_group_id'] = $new['item_group_id'];
			$old['BOldItemAmount']['budget_id'] = $new['budget_id'];
			$old['BOldItemAmount']['item_id'] = $new['item_id'];
			$old['BOldItemAmount']['b_dept_id'] = $new['b_dept_id'];
			$old['BOldItemAmount']['amount'] = $new['amount'];

			$this->BOldItemAmount->create();
			$this->BOldItemAmount->save($old);
		endforeach;


	}

	public function approveRejectBudget(){
		// manual form validation
		if(empty($this->request->data['BStatus']['status'])){
			$this->Session->setFlash('<b> You must select a decision </b><br><small> Please select whether to approve or reject the budget</small>','flash.error');
			$this->redirect($this->referer());
		}
		// end form validation

		$user = $this->getAuth();
		$userid = $user['user_id'];

		$budgetid = $this->request->data['BStatus']['budget_id'];
		// debug($this->request->data);exit();
		$this->BStatus->contain(array('BReviewer'));
		$BStatus = $this->BStatus->find('first',array('conditions'=>array('BReviewer.user_id'=>$userid,'BReviewer.budget_id'=>$budgetid),'order'=>array('BStatus.submission_no DESC')));
		// $BStatus = $this->BStatus->find('first',array('conditions'=>array('BReviewer.budget_id'=>$budgetid)));
		// debug($BStatus);exit();
		$statusid = $BStatus['BStatus']['status_id'];
		$submissionNo = $BStatus['BStatus']['submission_no'];

		if(empty($BStatus)){
			$this->Session->setFlash("<b> You don't have the privilege to approve/reject the budget <b><br><small> Please consult the administrator </small>",'flash.error');
			$this->redirect($this->referer());
		}

		if ($BStatus['BStatus']['submitted'] != 0){
			
			$this->Session->setFlash(__('<b>You have already approved/rejected the budget.</b>'),'flash.error');
			return $this->redirect($this->referer());
		}

		$remark = $this->request->data['BStatus']['remark'];
		$status = $this->request->data['BStatus']['status'];

		if($status == 'rejected'){
			$this->BStatus->updateAll(array(
				'BStatus.status'=>"'pending-rejected'",
				),
				array(
					'BStatus.submission_no'=>$submissionNo,
					'BStatus.budget_id'=>$budgetid,
					'BStatus.status'=>'pending',
				)
			);
		}

		$this->BStatus->id = $statusid;
		$data['BStatus']['remark'] = $remark;
		$data['BStatus']['status'] = $status;
		$data['BStatus']['submitted'] = 1;
		if( $this->BStatus->save($data)	){
			// only send email after status changed
			if($status == 'approved'){
				$this->sendReviewEmail($budgetid);
			}
			elseif($status == 'rejected'){
				$this->sendRejectedEmail($budgetid);
			}

			$encBudgetID = $this->encrypt($budgetid);
			$this->Session->setFlash("<b> You have ".$status." the budget </b><br><small> Thank You </small>",'flash.success');
			$this->redirect(array('controller'=>'budget','action'=>'dashboard',$encBudgetID));
		}
	}

	public function saveBudget($encBudgetID){//for submission =0 only (incomplete or not yet submitted)

		$user = $this->getAuth();
		//$encBudgetID=$budgetid;
		
		$budget_id=$this->decrypt($encBudgetID);

		//debug($this->request->data);exit;
		$data=$this->request->data;
		if (!$this->Budget->exists($budget_id)) {

			throw new ForbiddenException();

		}

		if ($this->request->is('post')||$this->request->is('put')){
			
			//first check in b_item_group if data for that budget already exist, if yes remove first the old one (this will also delete b_item_amount)

			$this->BItemGroup->deleteAll(array('BItemGroup.budget_id'=>$budget_id));
			$existFlag=false;

			//start saving data to db
			$row=array(null);
			$seqGroup=1;
			$seqItem=1;

			foreach ($data as $key => $value) {
				$prevRow=$row[0];//debug ($prevRow);exit;
				$row=explode('_',$key);
				//skip if item is not relevant
				if (count($row)!=3)
					continue;

				//if diff table group & item,add new group instance first before adding the item
				if(($row[0]!=$prevRow) && $row[1]=='item'):
					//1. check the group-type and add the group in b_item_group
					$groupType=explode('Group', $row[0]);
					if ($groupType[0]=='revenue')
						$groupInfo=array('sequence'=>$seqGroup++,'budget_id'=>$budget_id,'group_type'=>'Revenue');
					else if ($groupType[0]=='costOfRevenue')
						$groupInfo=array('sequence'=>$seqGroup++,'budget_id'=>$budget_id,'group_type'=>'Cost of Revenue');
					else if ($groupType[0]=='otherIncome')
						$groupInfo=array('sequence'=>$seqGroup++,'budget_id'=>$budget_id,'group_type'=>'Other Income');
					else if ($groupType[0]=='expenses')
						$groupInfo=array('sequence'=>$seqGroup++,'budget_id'=>$budget_id,'group_type'=>'Expenses');
					else if ($groupType[0]=='taxation')
						$groupInfo=array('sequence'=>$seqGroup++,'budget_id'=>$budget_id,'group_type'=>'Taxation');
					
					$this->BItemGroup->create();
					$this->BItemGroup->save ($groupInfo);
					$groupid=$this->BItemGroup->id;

				endif;
				//2. if item, save the itemid for future use
				if ($row[1]=='item'){
					
					$itemid=$value;
					
					//first check if the item has been selected in the budget, coz budget can contain only 1 item instance
					$this->BItemAmount->recursive=-1;
					$itemExist=$this->BItemAmount->find('first',array('conditions'=>array('BItemAmount.budget_id'=>$budget_id,'BItemAmount.item_id'=>$itemid,'BItemAmount.b_dept_id IS NULL')));
					//if item already added,error 
					if(!empty($itemExist)){
						//update flag to true
						$existFlag=true;
					}
				}
				//3. if budget ytd, save the val without dept id
				elseif($row[1]=='budgetYTD'){
					$value = floatval(str_replace(',', '', $value));

					$itemInfo=array('sequence'=>++$seqItem,'item_group_id'=>$groupid,'budget_id'=>$budget_id,'item_id'=>$itemid,'amount'=>$value);
					$this->BItemAmount->create();
					$this->BItemAmount->save ($itemInfo);
				}//4. if dept based budget,save the val with dept id

				elseif (is_numeric($row[1])){
					$value = floatval(str_replace(',', '', $value));

					$itemInfo=array('sequence'=>$seqItem,'item_group_id'=>$groupid,'budget_id'=>$budget_id,'item_id'=>$itemid,'b_dept_id'=>$row[1],'amount'=>$value);
					$this->BItemAmount->create();
					$this->BItemAmount->save ($itemInfo);
				}
				else
					continue;

			} 

			if(isset($data['save'])){//save & preview
				if ($existFlag){
					
					$this->Session->setFlash('<strong> Please ensure every item is selected only once in the budget</strong><br><small>  Item must be unique per budget </small>','flash.error');
					
					$this->redirect(array('controller'=>'budget','action'=>'request',$encBudgetID));
				}
				else{
					$this->Session->setFlash('<strong> Budget preview </strong><br><small> This is how the budget will look like after the changes </small>','flash.success');
					$this->redirect(array('controller'=>'budget','action'=>'preview',$encBudgetID));
				}
			}

			else{
					$this->Session->setFlash('<strong> Error saving the budget </strong><br><small> Please contact Administrator </small>','flash.error');
					$this->redirect($this->referer());	
					
				}
			
		}
	}

	public function deleteBudget($budgetid){
		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);

		#ememo2 :: if budget submitted,  send email first before delete 
		$budget = $this->Budget->findByBudgetId($budgetid); //debug($memo); exit;
		if($budget['Budget']['submission_no']>0){			
 			$this->sendDeleteEmail($budgetid);
		}
		if($this->Budget->delete($budgetid)){
			$this->Session->setFlash('<b> The budget has been successfully deleted </b><br><small> Thank you </small>','flash.success');
		}
		else{
			$this->Session->setFlash(__('<b>Fail to delete budget. Please try again.</b>'),'flash.error');
		}


		$this->redirect($this->referer());	
	}
 
	public function deleteBudgetMulti(){
		
		if (!empty($this->request->data['budgetid'])):
			foreach ($this->request->data['budgetid'] as $id) {
				// $budget_id=$this->decrypt($id);

				#ememo2 :: if budget submitted,  send email first before delete 
				$budget = $this->Budget->findByBudgetId($id); //debug($memo); exit;
				if($budget['Budget']['submission_no']>0){			
		 			$this->sendDeleteEmail($id);
				}
				if($this->Budget->delete($id)){
					$this->Session->setFlash(__('<b>Budgets have been deleted successfully.</b>'),'flash.success');
				}
				else{
					$this->Session->setFlash(__('<b>Fail to delete budget. Please try again.</b>'),'flash.error');
					break;
				}
			}
		endif;
		$this->redirect($this->referer());	
	}


	public function sendReviewEmail($budgetid,$approvedBy=null){
		$this->layout = 'mems-email';
		// get current status
		$this->BStatus->contain(array(
								'BReviewer',
								'BReviewer.User',
								'Budget',
								'Budget.User'=>array('fields'=>array('staff_name','email_address')),
								'Budget.Company'));
		$status = $this->BStatus->find('first',array('conditions'=>array('BStatus.budget_id'=>$budgetid,'BStatus.status'=>'pending'),'order'=>array('BReviewer.sequence ASC')));
		// debug($status);exit();
		$this->Budget->contain(array('User','Company'));
		$budget = $this->Budget->findByBudgetId($budgetid);

		// if status empty --> means all is approved?
		if(empty($status)){ // send to requestor

			// add notification to the requestor -- stating the budget has been fully approved
			$notiTo = $budget['Budget']['user_id'];
			// $notiText = "Your budget request has been approved";
			$notiText = "<b> Budget : </b> ".$budget['Budget']['year']. "<br>".
							"<b> Company : </b> ".$budget['Company']['company']."<br>".
							"<b> Info : </b> Budget approved";

			$encBudgetID = $this->encrypt($budget['Budget']['budget_id']);
			$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
			// $this->UserNotification->record($notiTo, $notiText, $notiLink);

			#ememo2:
			$notiType="budget-approved";
			$this->UserNotification->record($notiTo, $notiText, $notiLink,$notiType);

			
			// update budget_status in budget table
			$this->Budget->id  = $budgetid;
			$this->Budget->saveField('budget_status','approved');			

			//phase 2: copy data to old table after budget is fully approved,for next round of approval comparison
			$this->copyNewToOld($budgetid);

			$toRequestor= $budget['Budget']['user_id'];
			// generate link
			$encBudgetID = $this->encrypt($budgetid);
			$link = $this->goLink($budget['Budget']['user_id'],array('controller'=>'budget','action'=>'dashboard',$encBudgetID));

			$email = "Your budget request has been approved.<br>";
			$email .= $this->requestorBudgetTable($budgetid,$budget);
			$email .= "You may go to the budget dashboard by clicking the button below <br>";

			
			// debug($link);exit();
			$email .= "<a href='{$link}' class='btn btn-success'> Go to budget dashboard </a>";

			$toRequestor = $budget['Budget']['user_id'];
			$subject = $budget['Budget']['year']." (Budget request approved)";

			$this->emailMe($toRequestor,$subject,$email);

			

		}
		else{ // send to reviewer

			// add notification to the requestor -- stating the budget has been reviewed -- only when status is not 1 --coz 1 send review email -- not been reviewed yet
			if($status['BReviewer']['sequence'] != 1){
				$notiTo = $budget['Budget']['user_id'];
				// $notiText = "Your budget request has been reviewed";
				$notiText = "<b> Budget : </b> ".$budget['Budget']['year']. "<br>".
							"<b> Company : </b> ".$budget['Company']['company']."<br>".
							"<b> Info : </b> Budget reviewed";

				$encBudgetID = $this->encrypt($budgetid);
				$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
				//$this->UserNotification->record($notiTo, $notiText, $notiLink);
				#ememo2:
				$notiType="budget-reviewed";
				$this->UserNotification->record($notiTo, $notiText, $notiLink,$notiType);

				// also send email to requestor each time reviewer approve -- requested by unitar on 8/7/2015
				$approvedBy = $this->getAuth();
				$encBudgetID = $this->encrypt($budgetid);
				$link = $this->goLink($budget['Budget']['user_id'],array('controller'=>'budget','action'=>'dashboard',$encBudgetID));

				$email = "Your budget request has been approved by ".$approvedBy['staff_name'] .".<br>";
				$email .= $this->budgetTable($budgetid,$status['Budget']);
				$email .= "You may go to the dashboard page by clicking the button below <br>";

				$email .= "<a href='{$link}' class='btn btn-success'> Budget Dashboard </a>";

				$toRequestor = $status['Budget']['user_id'];
				$subject = $status['Budget']['year']." (Budget reviewed)";

				$this->emailMe($toRequestor,$subject,$email);
			}
			
			// add notification to the reviewer -- stating the budget need to be reviewed
			$notiTo = $status['BReviewer']['user_id'];
			// $notiText = "Please review the budget request";
			$notiText = "<b> Budget : </b> ".$budget['Budget']['year']. "<br>".
							"<b> Company : </b> ".$budget['Company']['company']."<br>".
							"<b> Pending : </b> Review budget";

			$encBudgetID = $this->encrypt($budgetid);
			$notiLink = array('controller'=>'budget','action'=>'review',$encBudgetID);
			// $this->UserNotification->record($notiTo, $notiText, $notiLink);
			#ememo2:
			$notiType="budget-to review";
			$this->UserNotification->record($notiTo, $notiText, $notiLink,$notiType);


			// generate link
			$encBudgetID = $this->encrypt($budgetid);
			$link = $this->goLink($status['BReviewer']['user_id'],array('controller'=>'budget','action'=>'review',$encBudgetID));

			$email = "You are required to review the following budget request.<br>";
			$email .= $this->budgetTable($budgetid,$status['Budget']);
			$email .= "You may go to the review page by clicking the button below <br>";

			
			// debug($link);exit();
			$email .= "<a href='{$link}' class='btn btn-success'> Review the budget request </a>";

			$toReviewer = $status['BReviewer']['user_id'];
			$subject = $budget['Budget']['year']." (Please review the budget request)";

			$this->emailMe($toReviewer,$subject,$email);

			
		}

		
		// $this->set('email',$email);
		// $this->render('email');
		// debug($status);exit();
	}

	public function sendRejectedEmail($budgetid){
		$this->Budget->contain(array('User','Company'));
		$budget = $this->Budget->findByBudgetId($budgetid);
		$toRequestor= $budget['Budget']['user_id'];
		//generate link
		$encBudgetID = $this->encrypt($budgetid);
		$link = $this->goLink($budget['Budget']['user_id'],array('controller'=>'budget','action'=>'request',$encBudgetID));

		$email = "Your budget request has been rejected.<br>";
		$email = "Please review it again and resubmit your budget request.<br>";
		$email .= $this->rejectedBudgetTable($budgetid,$budget);
		$email .= "You may go to the budget request page by clicking the button below <br>";

		
		// debug($link);exit();
		$email .= "<a href='{$link}' class='btn btn-success'> Go to budget request </a>";

		$toRequestor = $budget['Budget']['user_id'];
		$subject = $budget['Budget']['year']." (Budget request rejected)";

		$this->emailMe($toRequestor,$subject,$email);

		// add notification to the requestor -- stating the budget has been rejected
		$notiTo = $budget['Budget']['user_id'];
		// $notiText = "Your budget request has been rejected";
		$notiText = "<b> Budget : </b> ".$budget['Budget']['year']. "<br>".
							"<b> Company : </b> ".$budget['Company']['company']."<br>".
							"<b> Info : </b> Budget rejected";

		$encBudgetID = $this->encrypt($budgetid);
		$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
		//$this->UserNotification->record($notiTo, $notiText, $notiLink);
		#ememo2:
		$notiType="budget-rejected";
		$this->UserNotification->record($notiTo, $notiText, $notiLink,$notiType);
	}

	private function budgetTable($budgetid,$budgetData = array()){
		// $budgetAmount = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		// $totalBudget = $budgetAmount[0]['totalBudget'];

		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Budget Created </td>
							<td>".date('d M Y',strtotime($budgetData['created'])). " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Budget Year </td>
							<td>".$budgetData['year']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Company </td>
							<td> ".
								$budgetData['Company']['company']."<br>". 
								"<small>Requestor : ".$budgetData['User']['staff_name']."</small>".
							"</td>
						</tr>
						
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Current Submission No. </td>
							<td>".$budgetData['submission_no']. " </td>
						</tr>
					</table>";

		return $htmlTable;
	}

	private function requestorBudgetTable($budgetid,$budgetData = array()){
		// $budgetAmount = $this->BItemGroup->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		// $totalBudget = $budgetAmount[0]['totalBudget'];

		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Budget Created </td>
							<td>".date('d M Y',strtotime($budgetData['Budget']['created'])). " </td>
						</tr>
						
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Budget Year</td>
							<td>".$budgetData['Budget']['year']." </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Company </td>
							<td> ".
								$budgetData['Company']['company']."<br>". 
								"<small>Requestor : ".$budgetData['User']['staff_name']."</small>".
							"</td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Current Submission No. </td>
							<td>".$budgetData['Budget']['submission_no']. " </td>
						</tr>
					</table>";

		return $htmlTable;
	}

	private function rejectedBudgetTable($budgetid,$budgetData = array()){
		// $budgetAmount = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		// $totalBudget = $budgetAmount[0]['totalBudget'];

		$this->BStatus->contain(array('BReviewer','BReviewer.User'));
		$rejectedStatus = $this->BStatus->findByBudgetIdAndStatusAndSubmissionNo($budgetid,'rejected',$budgetData['Budget']['submission_no']);
		// debug($rejectedStatus);exit();

		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Budget Created </td>
							<td>".date('d M Y',strtotime($budgetData['Budget']['created'])). " </td>
						</tr>
						
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Budget Year </td>
							<td>".$budgetData['Budget']['year']." </td>
						</tr>
						
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Current Submission No. </td>
							<td>".$budgetData['Budget']['submission_no']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Rejected By </td>
							<td>".$rejectedStatus['BReviewer']['User']['staff_name']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Remark </td>
							<td>".$rejectedStatus['BStatus']['remark']. " </td>
						</tr>
					</table>";

		return $htmlTable;
	}

	#requestor here will only be adminFinance: role_id = 18
	public function isRequestor($budgetid){
		$user = $this->getAuth();
		$this->Budget->recursive = -1;

		// return $this->Budget->find('count',array('conditions'=>array('Budget.budget_id'=>$budgetid,'Budget.user_id'=>$user['user_id'])));
		$b =  $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		// debug($b);exit();
		return ($b['Budget']['user_id'] == $user['user_id']);
	}

	public function isReviewer($budgetid){
		$user = $this->getAuth();

		$this->BReviewer->recursive = -1;
		// return $this->BReviewer->find('count',array('conditions'=>array('BReviewer.user_id'=>$user['user_id'],'BReviewer.budget_id'=>$budgetid)));
		$r = $this->BReviewer->find('first',array('conditions'=>array('BReviewer.user_id'=>$user['user_id'],'BReviewer.budget_id'=>$budgetid)));
		return ( !empty($r) ); // if empty = return false, if !empty = return true
	}

	public function isInBudget($budgetid){
		#ID is in budget as reviewer/requestor
		// $user = $this->getAuth();

		// $this->BReviewer->recursive = -1;;
		// $reviewer = $this->BReviewer->find('first',array('conditions'=>array('BReviewer.user_id'=>$user['user_id'],'BReviewer.budget_id'=>$budgetid)));
		// // get budget detail
		// $this->Budget->recursive = -1;
		// $budget = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));

		// return ((!empty($reviewer)) || ($user['user_id'] ==  $budget['Budget']['user_id']));

		$isRequestor = $this->isRequestor($budgetid);
		$isReviewer = $this->isReviewer($budgetid);


		return ($isRequestor || $isReviewer);
		// return true; // to allow all to view, but control button
	}

	public function budgetExcel($budgetid,$deptSpecific){
		$user = $this->getAuth();
		$this->Budget->contain(array('Company'));
		$budgetDetail = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		$this->set('budgetDetail',$budgetDetail);
		$this->set('budgetID',$budgetid);

		// Budget item data
		//get the item data for budget
		$bdeptid=0;

		


		if ($deptSpecific&&!($user['role_id']==17||$user['finance'])){//if not creator/reviewer, show only dept specific budget
			$budgetData=$this->budgetData($budgetid,$user['department_id']);
			$deptBudget=$this->BDepartment->find('first',array('fields'=>('b_dept_id'),'conditions'=>array('budget_id'=>$budgetid,'department_id'=>$user['department_id'])));

			if (!empty($deptBudget))
				$bdeptid=$deptBudget['BDepartment']['b_dept_id'];
		}
		else
			$budgetData=$this->budgetData($budgetid,null);
		$this->set('deptid',$bdeptid);

		$deptAcad=$budgetData['deptAcad'];
		$deptNonAcad =$budgetData['deptNonAcad'];
		$this->set('deptAcad',$deptAcad);
		$this->set('deptNonAcad',$deptNonAcad);
		$this->set('deptSpecific',$deptSpecific);
		
		$this->set('budgetR',$budgetData['budgetR']);
		//debug($budgetData['budgetR']);
		$this->set('budgetCOR',$budgetData['budgetCOR']);
		$this->set('budgetOI',$budgetData['budgetOI']);
		$this->set('budgetE',$budgetData['budgetE']);
		$this->set('budgetT',$budgetData['budgetT']);
		// $this->set('budgetU',$budgetData['budgetU']);
		//$this->set('unbudgetedData',$budgetData['unbudgetedData']);

		$this->layout = 'mems-excel';
        $this->excelConfig =  array(
            'filename' => $budgetDetail['Company']['company'].'_'.$budgetDetail['Budget']['year'].'.xlsx'
        );
	}
	#ememo2 :: email to notify budget is deleted.
	public function sendDeleteEmail($budgetid){
		$this->layout = 'mems-email';
		$this->Budget->contain(array('User','Company','BReviewer'=>array('User')));
		$budget = $this->Budget->findByBudgetId($budgetid);			
		
	    #1.notify all reviewers on deleted budget
		if(!empty($budget)){
			foreach ($budget['BReviewer'] as $rev) {
			

				#EMAIL
				$email = "Please be informed that this budget has no longer exist. It has been removed from the system.<br>";
				$email .= $this->requestorBudgetTable($budgetid,$budget);
				$email .= "For any enquiry, please contact your Administrator. Thank you. <br>";			
			
				$toReviewer = $rev['user_id'];
				$subject = 'Budget '.$budget['Budget']['year']." (Budget removal from the system)";	

				$this->emailMe($toReviewer,$subject,$email);

				#NOTIFICATION
				$notiTo = $toReviewer;			
				$notiText = "<b> Budget : </b> ".$budget['Budget']['year']. "<br>".
							"<b> Company : </b> ".$budget['Company']['company']."<br>".
							"<b> Info : </b> Budget removed";
				$notiLink = array('controller'=>'Budget','action'=>'myReview');
				//$this->UserNotification->record($notiTo, $notiText, $notiLink);


				#code update for ememo2
				$notiType = "budget"; 
				$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
			    #end code update

				
			}

			#2.notify requestor on deleted budget
			#EMAIL
			$email = "Please be informed that this budget has no longer exist. It has been removed from the system.<br>";
			$email .= $this->requestorBudgetTable($budgetid,$budget);
			$email .= "For any enquiry, please contact your Administrator. Thank you. <br>";			
		
			$toRequestor = $budget['Budget']['user_id'];
			$subject = 'Budget '.$budget['Budget']['year']." (Budget removal from the system)";	

			$this->emailMe($toRequestor,$subject,$email);

			#NOTIFICATION
			$notiTo = $toRequestor;			
			$notiText = "<b> Budget : </b> ".$budget['Budget']['year']. "<br>".
							"<b> Company : </b> ".$budget['Company']['company']."<br>".
							"<b> Info : </b> Budget removed";

			$notiLink = array('controller'=>'Budget','action'=>'index');
			//$this->UserNotification->record($notiTo, $notiText, $notiLink);


			#code update for ememo2
			$notiType = "budget"; 
			$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
		    #end code update

		}
		
	}
	public function test() {
		$this->layout = 'test';
        $this->excelConfig =  array(
            'filename' => 'test.xlsx'
        );
    }

    public function testNoti(){
    	$this->UserNotification->record(1,"New budget",array('controller'=>'budget','action'=>'index'));
    }

    public function hello(){
    	$this->layout = 'hello-empty';
    	$this->pdfConfig = array(
			'orientation'=>'portrait',
			// 'filename'=>'testpdf',
			// 'download'=>false,
		);
		$this->render('hello');
    }

  	/* public function preloader($gif=null){
		  //   	$this->layout = 'mems-preloader';
		  //   	if($gif == null)
		  //   		$gif = 1;

		  //   	$this->set('gif',$gif);
		    	
		  //   	//temp data

				// $budgetid = 64;
				// $user = $this->getAuth();

				// if(!$this->isInBudget($budgetid)){
				// 	$this->Session->setFlash('<b> Not authorized </b><br><small> You are not authorized to view the budget. </small>','flash.error');
				// 	$this->redirect(array('controller'=>'budget','action'=>'index'));
				// }

				// // get budget detail
				// $this->Budget->contain(array('User','Department'));
				// $budgetDetail = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
				// // debug($budgetDetail);exit();
				// $this->set('budgetDetail',$budgetDetail);

				// // Budget item data
				// $this->BNewAmount->contain(array(
				// 							'BItem',
				// 							'BItem.BCategory'
				// 						));
				// $budgetItem = $this->BNewAmount->find('all',array('conditions'=>array('BNewAmount.budget_id'=>$budgetid),'order'=>array('BItem.category_id ASC')));
				// $this->set('budgetItem',$budgetItem);

				// $this->set('budgetID',$budgetid);

				// /// for lower remark thing
				// $reviewers=$this->getReviewer($budgetid,'reviewer',$budgetDetail['Budget']['submission_no']); 
		 	// 	$approvers=$this->getReviewer($budgetid,'approver',$budgetDetail['Budget']['submission_no']); 

		 	// 	$remark_reviewer=array();
				// $remark_approver=array();
				
				// if (!empty($reviewers)){
				// 	foreach ($reviewers as $reviewer) {
				// 		$remark_reviewer[]=$this->getRemark($budgetid,$reviewer['BReviewer']['reviewer_id']);
				// 	}
				// }
				// // debug($remark_reviewer);exit();

				// if (!empty($approvers)){
				// 	foreach ($approvers as $approver) {
				// 		$remark_approver[]=$this->getRemark($budgetid,$approver['BReviewer']['reviewer_id']);
				// 	}
				// }
				// $this->set('reviewers',$reviewers);
		 	// 	$this->set('approvers',$approvers);
		 	// 	$this->set('remark_reviewer',$remark_reviewer);
		 	// 	$this->set('remark_approver',$remark_approver);

		  //   }

			// public function email(){
			// 	$this->layout = 'mems-email';
			// }

			// public function testMail(){
			// 	$to = 'nizam27391@gmail.com';
			// 	// $to = 'nizam_bestarianz@yahoo.com';
			// 	$subject = "Pending for your review";
			// 	$message = "<strong class='big'> Hi, Nizam </strong><br><br>
			// 				You are required to take action for the following budget request. Kindly do so by clicking the button below. <br><br>
			// 				<a href='http://google.com' class='btn btn-success'> 
			// 					Take Action 
			// 				</a>";
			// 	if($this->emailMe($to,$subject,$message)){
			// 		$this->redirect('http://google.com');
			// 	}
			// 	else{
			// 		$this->redirect('http://yahoo.com');
			// 	}

	 }*/

}