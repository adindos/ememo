<?php

App::uses('AppController', 'Controller');
require_once(APP . 'Vendor' . DS . 'PHPExcel' . DS . 'Classes'. DS . 'PHPExcel.php');

class BudgetController extends AppController{

	public $uses = array('Setting','UserNotification','BCategory','BItem','BRemark','Budget','BNewAmount','BOldAmount','User','BReviewer','BStatus','Department','Group','Company');

	public function index(){
		$user = $this->getAuth();

		// Check user role
		if(!$user['Role']['my_request_budget']){
			throw new ForbiddenException();
		}

		$this->Budget->contain(array(
								'Department',
								'Department.Group',
								'BStatus'=>array('fields'=>array('status','submission_no')),
								'BRemark.BRemarkAssign'=>array('conditions'=>array('BRemarkAssign.user_id'=>$user['user_id']),'fields'=>array('BRemarkAssign.user_id')),
								));
		$budget = $this->Budget->find('all',array('conditions'=>array('Budget.user_id'=>$user['user_id']),'order'=>array('Budget.created DESC')));
		// debug($budget);exit();
		$this->set('budget',$budget);

		$this->Department->contain(array('Group'=>array('fields'=>array('group_name')),'Group.Company'=>array('fields'=>array('company'))));
		$userDepartment = $this->Department->find('first',array('conditions'=>array('Department.department_id'=>$user['department_id'])));
		$strDepartment = $userDepartment['Group']['Company']['company'] . " > " . $userDepartment['Group']['group_name'] ." > ".$userDepartment['Department']['department_name'];
		$this->set('strDepartment',$strDepartment);
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
			$this->Session->setFlash('This budget is not yet confirmed <br><small>Please click the <strong><em>confirm budget</em></strong> button to confirm the budget </small>','flash.error');
			$this->redirect(array('controller'=>'budget','action'=>'request',$encBudgetID)); //redirect using the encrypted budget id
		}

		// check if the budget is finished -- have reviewer and status -- if one exist is ok -- reduce memory
		$isFinished = $this->BStatus->findByBudgetId($budgetid);
		if(!$isFinished){
			$this->Session->setFlash('This budget is not yet finished <br><small>Please fill the form to finish the budget </small>','flash.error');
			$this->redirect(array('controller'=>'budget','action'=>'confirm',$encBudgetID)); //redirect using the encrypted budget id
		}

		//if not requestor / reviewer / approver -> cannot view dashboard
		$this->BReviewer->recursive = -1;
		$isReviewer = $this->BReviewer->find('first',array('conditions'=>array('BReviewer.budget_id'=>$budgetid,'BReviewer.user_id'=>$userid)));
		$this->Budget->recursive = -1;
		$isRequestor = $this->Budget->find('first',array('conditions'=>array('Budget.user_id'=>$userid,'Budget.budget_id'=>$budgetid)));
		
		// if(empty($isRequestor) && empty($isReviewer)){
		// 	$this->Session->setFlash("You don't have the privileges to view the budget <br><small> Please consult administrator for help </small>","flash.error");
		// 	$this->redirect(array('controller'=>'user','action'=>'userDashboard'));
		// }

		// calculate the total budget
		$totalBudget = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		$this->set('totalBudget',$totalBudget[0]['totalBudget']);
		// debug($totalBudget);exit();

		// the budget data
		$this->Budget->contain(array(
							'User'=>array('fields'=>array('staff_name','designation')),
							'User.Department'=>array('fields'=>array('department_name')),
							'Department',
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
		// if($isMyPending){
		// 	$this->Session->setFlash('<b> This budget is waiting for your review </b><br><small>Please go to review page by clicking the <em><b>Budget Details</b></em> button below</small>','flash.info');
		// }

		// reviewer
		$this->BStatus->contain(array(
							'BReviewer',
							'BReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$reviewers = $this->BStatus->find('all',array('conditions'=>array('BStatus.submission_no'=>$budget['Budget']['submission_no'],'BReviewer.budget_id'=>$budgetid,'BReviewer.approval_type'=>'reviewer'),'order'=>array('sequence ASC')));
		$this->set('reviewers',$reviewers);
		// debug($reviewers);exit();

		// approver
		$this->BStatus->contain(array(
							'BReviewer',
							'BReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$approvers = $this->BStatus->find('all',array('conditions'=>array('BStatus.submission_no'=>$budget['Budget']['submission_no'],'BReviewer.budget_id'=>$budgetid,'BReviewer.approval_type'=>'approver'),'order'=>array('sequence ASC')));
		$this->set('approvers',$approvers);

		// setting max budget time
		$setting = $this->Setting->find('first',array('fields'=>array('Setting.max_review_day_budget')));
		$this->set('setting',$setting);
	}

	public function allrequest(){
		$user = $this->getAuth();

		// Check user role
		if(!$user['Role']['all_request_budget']){
			throw new ForbiddenException();
		}

		$this->Budget->contain(array(
								'Department',
								'Department.Group',
								'BStatus'=>array('fields'=>array('status','submission_no')),
								));
		$budget = $this->Budget->find('all',array(
								'conditions'=>array('Budget.department_id'=>$user['department_id']),
								'order'=>array('Budget.created DESC')));
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
								'Budget.User'=>array('fields'=>array('staff_name')),
								'Budget.BStatus',
								'Budget.Company'=>array('fields'=>array('company')),
								'Budget.Department'=>array('fields'=>array('department_name')),
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
								'Budget.BStatus',
								'Budget.Company'=>array('fields'=>array('company')),
								'Budget.Department'=>array('fields'=>array('department_name')),
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

	public function request($budgetid){
		// var_dump($budgetid);exit();
		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);
		// debug($budgetid);exit();
		
		// only requestor can access page
		if(!$this->isRequestor($budgetid)){
			$this->Session->setFlash('You are not authorized to view this budget <br><small> <em> You are not the requester of this budget </em></small>','flash.error');
			$this->redirect(array('controller'=>'budget','action'=>'index'));
		}

		$this->Budget->recursive = -1;
		$this->Budget->contain(array('Department'));
		$budgetDetail = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		$this->set('budgetDetail',$budgetDetail);
		// debug($budgetDetail);exit();

		// can only edit if status rejected exist or submission is 0
		$this->BStatus->recursive = -1;
		$isRejected = $this->BStatus->find('first',array('conditions'=>array('BStatus.budget_id'=>$budgetid,'BStatus.submission_no'=>$budgetDetail['Budget']['submission_no'],"BStatus.status"=>"rejected")));
		// debug($isRejected);exit();
		if(!$isRejected && $budgetDetail['Budget']['submission_no'] > 0){
			$this->Session->setFlash('You cannot make changes to the budget that are still in review / approved budget <br><small> You may view budget details below </small>','flash.error');
			$this->redirect(array('controller'=>'budget','action'=>'dashboard',$encBudgetID));
		}

		// Budget data
		$this->BNewAmount->contain(array(
									'BItem',
									'BItem.BCategory'
								));
		$budgetData = $this->BNewAmount->find('all',array('conditions'=>array('BNewAmount.budget_id'=>$budgetid),'order'=>array('BItem.category_id ASC')));
		$this->set('budgetData',$budgetData);
		// debug($budgetData);exit();

		$budgetItemCategory = $this->BCategory->find('list');
		$this->BItem->contain(array('BCategory'));
		$budgetItems = $this->BItem->find('list',array('fields'=>array('BItem.item_id','BItem.item','BCategory.category'),'order'=>'BItem.category_id ASC'));
		$this->set('budgetItemCategory',$budgetItemCategory);
		$this->set('budgetItems',$budgetItems);
		$this->set('budgetID',$budgetid);
	}

	public function confirm($budgetid){

		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);

		$user = $this->getAuth();
		// debug($budgetid);exit();

		$this->Budget->contain(array(
								'BReviewer',
								'Department',
			));
		$budget = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		$this->set('budget',$budget);
		// debug($budget);

		// only requestor can access page
		if(!$this->isRequestor($budgetid)){
			// debug("WTH");exit();
			$this->Session->setFlash('You are not authorized to view this budget <br><small> <em> You are not the requester of this budget </em></small>','flash.error');
			$this->redirect(array('controller'=>'budget','action'=>'index'));
		}

		// if from post, update submission
		if($this->request->is('post')){
			$this->Budget->id = $budgetid;
			$this->Budget->saveField('submission_no',$budget['Budget']['submission_no']+1); // will be updated everytime user request

			// copy budget item to old table
			$this->copyNewToOld($budgetid);

			// if budget is resubmit -- don't have to go to confirm page --> redirect to dashboard (he/she cannot edit the approver/reviewer list)
			if($budget['Budget']['submission_no'] >=1 ){
				// repopulate bstatus
				$this->BStatus->recursive = -1;
				$bstatus = $this->BStatus->find('all',array('conditions'=>array('BStatus.budget_id'=>$budgetid,'BStatus.submission_no'=>$budget['Budget']['submission_no'])));
				// debug($bstatus);exit();
				foreach($bstatus as $bs):
					$data['BStatus']['reviewer_id'] = $bs['BStatus']['reviewer_id'];
					$data['BStatus']['budget_id'] = $bs['BStatus']['budget_id'];
					$data['BStatus']['status'] = 'pending';
					$data['BStatus']['submission_no'] = $budget['Budget']['submission_no']+1;
					$this->BStatus->create();
					$this->BStatus->save($data);
				endforeach;

				// send review email if resubmit --> does not go to confirmBudget
				$this->sendReviewEmail($budgetid);

				// add notification to the requestor -- stating the budget has been resubmit
				$notiTo = $budget['Budget']['user_id'];
				// $notiText = "You have resubmitted a budget request";
				$notiText = "<b> Budget : </b> ".$budget['Budget']['title']. "<br>".
							"<b> Dept : </b> ".$budget['Department']['department_name']."<br>".
							"<b> Action : </b> Submitted budget";

				$encBudgetID = $this->encrypt($budgetid);
				$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
				$this->UserNotification->record($notiTo, $notiText, $notiLink);

				$this->Session->setFlash("<b> The budget has been resubmitted </b><br/><small>The budget submission no is updated </small>",'flash.success');
				$this->redirect(array('controller'=>'budget','action'=>'dashboard',$encBudgetID));
			}
			else{
				$this->Session->setFlash('<b> The budget has been created </b><br/><small>Please complete the step below to finalize the budget</small>','flash.success');
				$this->redirect(array('controller'=>'budget','action'=>'confirm',$encBudgetID));
			}
			
		}

		$this->set('budgetID',$budgetid);

		$totalBudget = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		// debug($totalBudget);exit();
		$reviewers = $this->User->find('list',array('conditions'=>array('User.reviewer'=>1,'User.status'=>'enabled','User.department_id'=>$budget['Budget']['department_id'])));
		$this->set('reviewers',$reviewers);
		$approvers = $this->User->find('list',array('conditions'=>array('User.finance'=>1,'User.status'=>'enabled','User.loa >= '=>$totalBudget[0]['totalBudget'])));
		$this->set('approvers',$approvers);

		$selectedReviewers = Set::extract('/BReviewer[approval_type=reviewer]/user_id',$budget);
		$this->set('selectedReviewers',$selectedReviewers);
		// debug($selectedReviewers);exit();

		$selectedApprovers = Set::extract('/BReviewer[approval_type=approver]/user_id',$budget);
		$this->set('selectedApprovers',$selectedApprovers);
		// debug($selectedReviewers);exit();

		$this->request->data = $budget;
	}

	public function review($budgetid){
		// debug($this->encrypt($budgetid));exit();
		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);
		// debug($budgetid);exit();
		
		$user = $this->getAuth();

		$showAllBtn = true;
		if(!$this->isInBudget($budgetid)){
			// $this->Session->setFlash('<b> Not authorized </b><br><small> You are not authorized to view the budget. </small>','flash.error');
			// $this->redirect(array('controller'=>'budget','action'=>'index'));
			$showAllBtn = false;
		}
		$this->set('showAllBtn',$showAllBtn);

		// get budget detail
		$this->Budget->contain(array('User','Department'));
		$budgetDetail = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		// debug($budgetDetail);exit();
		$this->set('budgetDetail',$budgetDetail);

		// check if user already approve / reject budget
		$this->BStatus->contain(array('BReviewer','Budget'));
		$userStatus = $this->BStatus->find('first',array('conditions'=>array('BStatus.submission_no'=>$budgetDetail['Budget']['submission_no'],'BStatus.budget_id'=>$budgetid,'BStatus.status'=>'pending','BReviewer.user_id'=>$user['user_id'])));
		// debug($userStatus);exit();

		$previousPending = false;
		if(!empty($userStatus)){
			$this->BStatus->contain(array('BReviewer','Budget'));
			$previousPending = $this->BStatus->find('count',array('conditions'=>array('BStatus.submission_no'=>$budgetDetail['Budget']['submission_no'],'BStatus.budget_id'=>$budgetid,'BStatus.status'=>'pending','BReviewer.sequence <'=>$userStatus['BReviewer']['sequence'])));
		}
		// debug($previousPending);exit();

		$showButton = true;
		if(empty($userStatus) || $previousPending){
			$showButton = false;
		}
		// debug($user);exit();
		// if($user['user_id'] ==  $budgetDetail['Budget']['user_id']){
		// 	$showButton = false;
		// }
		// debug($showButton);exit();
		$this->set('showButton',$showButton);

		// Budget item data
		$this->BNewAmount->contain(array(
									'BItem',
									'BItem.BCategory'
								));
		$budgetItem = $this->BNewAmount->find('all',array('conditions'=>array('BNewAmount.budget_id'=>$budgetid),'order'=>array('BItem.category_id ASC')));
		$this->set('budgetItem',$budgetItem);

		$budgetItemCategory = $this->BCategory->find('list');
		$this->BItem->contain(array('BCategory'));
		$budgetItems = $this->BItem->find('list',array('fields'=>array('BItem.item_id','BItem.item','BCategory.category'),'order'=>'BItem.category_id ASC'));
		$this->set('budgetItemCategory',$budgetItemCategory);
		$this->set('budgetItems',$budgetItems);

		$this->set('budgetID',$budgetid);

		/// for lower remark thing
		$reviewers=$this->getReviewer($budgetid,'reviewer',$budgetDetail['Budget']['submission_no']); 
		// debug($reviewers); exit();
 		$approvers=$this->getReviewer($budgetid,'approver',$budgetDetail['Budget']['submission_no']); 
 		// debug($approvers);exit();

 		$remark_reviewer=array();
		$remark_approver=array();
		
		if (!empty($reviewers)){
			foreach ($reviewers as $reviewer) {
				$remark_reviewer[]=$this->getRemark($budgetid,$reviewer['BReviewer']['reviewer_id']);
			}
		}
		// debug($remark_reviewer);exit();

		if (!empty($approvers)){
			foreach ($approvers as $approver) {
				$remark_approver[]=$this->getRemark($budgetid,$approver['BReviewer']['reviewer_id']);
			}
		}
		$this->set('reviewers',$reviewers);
 		$this->set('approvers',$approvers);
 		$this->set('remark_reviewer',$remark_reviewer);
 		$this->set('remark_approver',$remark_approver);

	}

	public function pdf($budgetid){

		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);

		$user = $this->getAuth();

		// if(!$this->isInBudget($budgetid)){
		// 	$this->Session->setFlash('<b> Not authorized </b><br><small> You are not authorized to view the budget. </small>','flash.error');
		// 	$this->redirect(array('controller'=>'budget','action'=>'index'));
		// }

		// get budget detail
		$this->Budget->contain(array('User','Department'));
		$budgetDetail = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		// debug($budgetDetail);exit();
		$this->set('budgetDetail',$budgetDetail);

		// Budget item data
		$this->BNewAmount->contain(array(
									'BItem',
									'BItem.BCategory'
								));
		$budgetItem = $this->BNewAmount->find('all',array('conditions'=>array('BNewAmount.budget_id'=>$budgetid),'order'=>array('BItem.category_id ASC')));
		$this->set('budgetItem',$budgetItem);

		$this->set('budgetID',$budgetid);

		/// for lower remark thing
		$reviewers=$this->getReviewer($budgetid,'reviewer',$budgetDetail['Budget']['submission_no']); 
 		$approvers=$this->getReviewer($budgetid,'approver',$budgetDetail['Budget']['submission_no']); 

 		$remark_reviewer=array();
		$remark_approver=array();
		
		if (!empty($reviewers)){
			foreach ($reviewers as $reviewer) {
				$remark_reviewer[]=$this->getRemark($budgetid,$reviewer['BReviewer']['reviewer_id']);
			}
		}
		// debug($remark_reviewer);exit();

		if (!empty($approvers)){
			foreach ($approvers as $approver) {
				$remark_approver[]=$this->getRemark($budgetid,$approver['BReviewer']['reviewer_id']);
			}
		}
		$this->set('reviewers',$reviewers);
 		$this->set('approvers',$approvers);
 		$this->set('remark_reviewer',$remark_reviewer);
 		$this->set('remark_approver',$remark_approver);

 		$this->layout = 'mems-pdf-budget';
 		$this->pdfConfig = array(
			'orientation'=>'portrait',
			// 'filename'=>'testpdf',
			'download'=>false,
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

	public function compare($budgetid){

		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);

		// if(!$this->isInBudget($budgetid)){
		// 	$this->Session->setFlash('<b> Not authorized </b><br><small> You are not authorized to view the budget. </small>','flash.error');
		// 	$this->redirect(array('controller'=>'budget','action'=>'index'));
		// }
		
		// get budget data
		$this->Budget->contain(array('Department'));
		$budget = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));
		$this->set('budget',$budget);
		// debug($budget);exit();

		// current budget
		$this->BNewAmount->contain(array(
									'BItem',
									'BItem.BCategory'
								));
		$currentBudget = $this->BNewAmount->find('all',array('conditions'=>array('BNewAmount.budget_id'=>$budgetid),'order'=>array('BItem.category_id ASC')));
		$this->set('currentBudget',$currentBudget);

		// previous budget
		$originalOnly = true;
		$currentSubmission = $budget['Budget']['submission_no'];
		$this->BOldAmount->contain(array(
									'BItem',
									'BItem.BCategory',
									'User'
								));
		$conditionPrevious = array('BOldAmount.budget_id'=>$budgetid,'BOldAmount.submission_no'=>$budget['Budget']['submission_no'],'BOldAmount.original'=>1);
		if($this->request->is('post')){
			if($this->request->data['Filter']['original_only'] == 0){
				unset($conditionPrevious['BOldAmount.original']);
				$originalOnly =false;
			}
			
			$conditionPrevious['BOldAmount.submission_no']=$this->request->data['Filter']['submission'];

			$currentSubmission = $this->request->data['Filter']['submission'];
		}
		// debug($conditionPrevious);
		$previousBudget = $this->BOldAmount->find('all',array('conditions'=>$conditionPrevious,'order'=>array('BItem.category_id ASC','BOldAmount.item_id ASC')));
		$this->set('previousBudget',$previousBudget);

		$this->BOldAmount->recursive = -1;
		$submissions = $this->BOldAmount->find('all',
										array('conditions'=>array('BOldAmount.budget_id'=>$budgetid)),
										array('fields'=>array('DISTINCT submission_no')));
		$submissionList = Set::combine($submissions,'{n}.BOldAmount.submission_no','{n}.BOldAmount.submission_no');
		$this->set('submissionList',$submissionList);
		// debug($submissionList);exit();

		$this->set('currentSubmission',$currentSubmission);
		$this->set('originalOnly',$originalOnly);
		$this->set('budgetID',$budgetid);

	}

	/* Action Helper */


	public function createBudget(){
		$user = $this->getAuth();

		if($this->request->is('post')){

			// manual form validation
			$valid = true;
			$data = $this->request->data;
			$this->Budget->set($data);
			if( !$this->Budget->validates() ){
				$this->Session->setFlash('<strong> Error creating the budget </strong><br><small> Please fill in all required field </small>','flash.error');
				$this->redirect($this->referer());
			}
			//end manual form validation

			$this->Budget->create();
			$this->request->data['Budget']['user_id'] = $user['user_id'];
			$this->request->data['Budget']['department_id'] = $user['department_id'];
			$this->Department->contain(array(
										'Group'=>array('fields'=>array('group_id','company_id')),
									));
			$extraData = $this->Department->find('first',array('conditions'=>array('Department.department_id'=>$user['department_id'])));
			$this->request->data['Budget']['group_id'] = $extraData['Group']['group_id'];
			$this->request->data['Budget']['company_id'] = $extraData['Group']['company_id'];

			// $this->request->data['Budget']['submission_no'] = 1;
			if($this->Budget->save($this->request->data)){
				$budgetid = $this->Budget->id;
				$encBudgetID = $this->encrypt($budgetid);
				$this->Session->setFlash('The budget has been successfully created <br><small> Please proceed by adding item to the budget </small>','flash.success');
				$this->redirect(array('controller'=>'budget','action'=>'request',$encBudgetID));
			}
		}
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

	/*
	*	Edit the budget amount of item before confirm the budget by requestor
	*	@ Nizam
	*/
	public function editBudgetItem(){
		if($this->request->is('post')){
			$user = $this->getAuth();
			$amountid = $this->request->data['BNewAmount']['amount_id'];
			$userid = $user['user_id'];

			// manual form validation
			$this->BNewAmount->set($this->request->data);
			if( !$this->BNewAmount->validates() ){
				$this->Session->setFlash('<strong> Error updating budget </strong><br><small> Please fill in all required field with the correct format </small>','flash.error');
				$this->redirect($this->referer()); //bring back to form
			}
			// end manual form validation

			// now update the new amount
			$this->BNewAmount->id = $amountid;
			if($this->BNewAmount->save($this->request->data)){
				$this->Session->setFlash('<b> The budget item has been successfully updated </b><br><small> Thank You </small>','flash.success');
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
				$this->Session->setFlash('<b> This budget has been confirm / submitted </b><br><small> Please consult administrator </small>','flash.error');
				$this->redirect(array('controller'=>'budget','action'=>'index'));
			}

			// manual form validation
			if( empty($this->request->data['BReviewer']['reviewer']) || empty($this->request->data['BReviewer']['reviewer']) ){
				$this->Session->setFlash('<b> Error confirming the budget </b><br><small>You must select the reviewer and approver</small>','flash.error');
				$this->redirect($this->referer());
			}
			// end manual form validation

			// cannot have same reviewer and approver
			$intersected = array_intersect($this->request->data['BReviewer']['reviewer'], $this->request->data['BReviewer']['approver']);
			if(!empty($intersected)){
				$this->Session->setFlash('<b> A reviewer cannot be an approver also </b><br><small>Please reselect the reviewer and approver</small>','flash.error');
				$this->redirect($this->referer());
			}		

			// debug($this->request->data);exit();
			$budgetid = $this->request->data['Budget']['budget_id'];
			$this->Budget->id = $budgetid;
			$this->Budget->saveField('remark',$this->request->data['Budget']['remark']);

			// $this->Budget->recursive = -1;
			$this->Budget->contain(array('Department'));
			$budget = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));

			// save the reviewer
			$seq=1;
			foreach($this->request->data['BReviewer']['reviewer'] as $r):
				$reviewer['BReviewer']['budget_id'] = $budgetid;
				$reviewer['BReviewer']['user_id'] = $r;
				$reviewer['BReviewer']['sequence'] = $seq++;
				$reviewer['BReviewer']['approval_type'] = 'reviewer';
				$this->BReviewer->create();
				if($this->BReviewer->save($reviewer)){
					$reviewerid = $this->BReviewer->id;
					$status['BStatus']['reviewer_id'] = $reviewerid;
					$status['BStatus']['status'] = 'pending';
					$status['BStatus']['submission_no'] = $budget['Budget']['submission_no'];
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
					$status['BStatus']['submission_no'] = $budget['Budget']['submission_no'];
					$status['BStatus']['budget_id'] = $budgetid;
					$this->BStatus->create();
					$this->BStatus->save($status);
				}
			endforeach;

			// send email to reviewer
			$this->sendReviewEmail($budgetid);

			// add notification to the requestor -- stating the budget has been created
			$notiTo = $budget['Budget']['user_id'];
			// $notiText = "You have submitted a budget request";
			$notiText = "<b> Budget : </b> ".$budget['Budget']['title']. "<br>".
							"<b> Dept : </b> ".$budget['Department']['department_name']."<br>".
							"<b> Action : </b> Submitted budget";

			$encBudgetID = $this->encrypt($budgetid);
			$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
			$this->UserNotification->record($notiTo, $notiText, $notiLink);

			$this->Session->setFlash('The budget has been submitted for review <br><small> You will be notified if the status changed later </small>','flash.success');
			$this->redirect(array('controller'=>'budget','action'=>'index'));
		}

		

	}

	/*
	*	copy new budget to old budget when confirming the budget by requestor
	*	@ Nizam 
	*	(tested working on 16/2/2015)
	*/
	private function copyNewToOld($budgetid){
		// get the budget
		$this->Budget->contain(array('BNewAmount'));
		$budget = $this->Budget->find('first',array('conditions'=>array('Budget.budget_id'=>$budgetid)));

		//copy the budget to old record to keep track of older data (for comparison)
		foreach($budget['BNewAmount'] as $new):
			$old['BOldAmount']['budget_id'] = $new['budget_id'];
			$old['BOldAmount']['user_id'] = $new['user_id'];
			$old['BOldAmount']['amount'] = $new['amount'];
			$old['BOldAmount']['item_id'] = $new['item_id'];
			$old['BOldAmount']['department_id'] = $new['department_id'];
			$old['BOldAmount']['submission_no'] = $budget['Budget']['submission_no'];
			$old['BOldAmount']['original'] = 1; // original budget created by requestor

			$this->BOldAmount->create();
			$this->BOldAmount->save($old);
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
			return $this->redirect(array('controller'=>'user','action'=>'userDashboard'));
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

	public function saveBudget($budgetid){
		$this->Session->setFlash('<strong> Budget saved </strong><br><small> The budget has been successfully saved </small>','flash.success');
		$this->redirect(array('controller'=>'budget','action'=>'index'));
	}

	public function deleteBudget($budgetid){
		$encBudgetID = $budgetid;
		$budgetid = $this->decrypt($budgetid);
		if($this->Budget->delete($budgetid)){
			$this->Session->setFlash('<b> The budget has been successfully deleted </b><br><small> Thank you </small>','flash.success');
			$this->redirect($this->referer());
		}
	}

	public function sendReviewEmail($budgetid){
		$this->layout = 'mems-email';
		// get current status
		$this->BStatus->contain(array(
								'BReviewer',
								'BReviewer.User',
								'Budget',
								'Budget.User'=>array('fields'=>array('staff_name','email_address')),
								'Budget.Department'));
		$status = $this->BStatus->find('first',array('conditions'=>array('BStatus.budget_id'=>$budgetid,'BStatus.status'=>'pending'),'order'=>array('BReviewer.sequence ASC')));
		// debug($status);exit();
		$this->Budget->contain(array('User','Department'));
		$budget = $this->Budget->findByBudgetId($budgetid);

		// if status empty --> means all is approved?
		if(empty($status)){ // send to requestor

			// add notification to the requestor -- stating the budget has been fully approved
			$notiTo = $budget['Budget']['user_id'];
			// $notiText = "Your budget request has been approved";
			$notiText = "<b> Budget : </b> ".$budget['Budget']['title']. "<br>".
							"<b> Dept : </b> ".$budget['Department']['department_name']."<br>".
							"<b> Pending : </b> Budget approved";

			$encBudgetID = $this->encrypt($budget['Budget']['budget_id']);
			$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
			$this->UserNotification->record($notiTo, $notiText, $notiLink);

			
			// update budget_status in budget table
			$this->Budget->id  = $budgetid;
			$this->Budget->saveField('budget_status','approved');			

			$toRequestor= $budget['Budget']['user_id'];
			// generate link
			$encBudgetID = $this->encrypt($budgetid);
			$link = $this->goLink($budget['Budget']['user_id'],array('controller'=>'budget','action'=>'dashboard',$encBudgetID));

			$email = "Your budget request have been approved.<br>";
			$email .= $this->requestorBudgetTable($budgetid,$budget);
			$email .= "You may go to the budget dashboard by clicking the button below <br>";

			
			// debug($link);exit();
			$email .= "<a href='{$link}' class='btn btn-success'> Go to budget dashboard </a>";

			$toRequestor = $budget['Budget']['user_id'];
			$subject = "Budget request approved";

			$this->emailMe($toRequestor,$subject,$email);

			

		}
		else{ // send to reviewer

			// add notification to the requestor -- stating the budget has been reviewed -- only when status is not 1 --coz 1 send review email -- not been reviewed
			if($status['BReviewer']['sequence'] != 1){
				$notiTo = $budget['Budget']['user_id'];
				// $notiText = "Your budget request has been reviewed";
				$notiText = "<b> Budget : </b> ".$budget['Budget']['title']. "<br>".
							"<b> Dept : </b> ".$budget['Department']['department_name']."<br>".
							"<b> Action : </b> Budget reviewed";

				$encBudgetID = $this->encrypt($budgetid);
				$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
				$this->UserNotification->record($notiTo, $notiText, $notiLink);
			}
			
			// add notification to the reviewer -- stating the budget need to be reviewed
			$notiTo = $status['BReviewer']['user_id'];
			// $notiText = "Please review the budget request";
			$notiText = "<b> Budget : </b> ".$budget['Budget']['title']. "<br>".
							"<b> Dept : </b> ".$budget['Department']['department_name']."<br>".
							"<b> Pending : </b> Review budget";

			$encBudgetID = $this->encrypt($budgetid);
			$notiLink = array('controller'=>'budget','action'=>'review',$encBudgetID);
			$this->UserNotification->record($notiTo, $notiText, $notiLink);


			// generate link
			$encBudgetID = $this->encrypt($budgetid);
			$link = $this->goLink($status['BReviewer']['user_id'],array('controller'=>'budget','action'=>'review',$encBudgetID));

			$email = "You are required to review the following budget request.<br>";
			$email .= $this->budgetTable($budgetid,$status['Budget']);
			$email .= "You may go to the review page by clicking the button below <br>";

			
			// debug($link);exit();
			$email .= "<a href='{$link}' class='btn btn-success'> Review the budget request </a>";

			$toReviewer = $status['BReviewer']['user_id'];
			$subject = "Please review the budget request";

			$this->emailMe($toReviewer,$subject,$email);

			
		}

		
		// $this->set('email',$email);
		// $this->render('email');
		// debug($status);exit();
	}

	public function sendRejectedEmail($budgetid){
		$this->Budget->contain(array('User','Department'));
		$budget = $this->Budget->findByBudgetId($budgetid);
		$toRequestor= $budget['Budget']['user_id'];
		//generate link
		$encBudgetID = $this->encrypt($budgetid);
		$link = $this->goLink($budget['Budget']['user_id'],array('controller'=>'budget','action'=>'request',$encBudgetID));

		$email = "Your budget request have been rejected.<br>";
		$email = "Please review it again and resubmit your budget request.<br>";
		$email .= $this->rejectedBudgetTable($budgetid,$budget);
		$email .= "You may go to the budget request page by clicking the button below <br>";

		
		// debug($link);exit();
		$email .= "<a href='{$link}' class='btn btn-success'> Go to budget request </a>";

		$toRequestor = $budget['Budget']['user_id'];
		$subject = "Budget request rejected";

		$this->emailMe($toRequestor,$subject,$email);

		// add notification to the requestor -- stating the budget has been rejected
		$notiTo = $budget['Budget']['user_id'];
		// $notiText = "Your budget request has been rejected";
		$notiText = "<b> Budget : </b> ".$budget['Budget']['title']. "<br>".
							"<b> Dept : </b> ".$budget['Department']['department_name']."<br>".
							"<b> Pending : </b> Budget rejected";

		$encBudgetID = $this->encrypt($budgetid);
		$notiLink = array('controller'=>'budget','action'=>'dashboard',$encBudgetID);
		$this->UserNotification->record($notiTo, $notiText, $notiLink);
	}

	private function budgetTable($budgetid,$budgetData = array()){
		$budgetAmount = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		$totalBudget = $budgetAmount[0]['totalBudget'];

		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Title </td>
							<td>".$budgetData['title']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Quarter (Year)</td>
							<td>".$budgetData['quarter'] . " (".$budgetData['year'].")". " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Department </td>
							<td> ".
								$budgetData['Department']['department_name']."<br>". 
								"<small>Requestor : ".$budgetData['User']['staff_name']."</small>".
							"</td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Amount </td>
							<td> RM ".number_format($totalBudget,2,".",","). " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Current Submission No. </td>
							<td>".$budgetData['submission_no']. " </td>
						</tr>
					</table>";

		return $htmlTable;
	}

	private function requestorBudgetTable($budgetid,$budgetData = array()){
		$budgetAmount = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		$totalBudget = $budgetAmount[0]['totalBudget'];

		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Title </td>
							<td>".$budgetData['Budget']['title']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Quarter (Year)</td>
							<td>".$budgetData['Budget']['quarter'] . " (".$budgetData['Budget']['year'].")". " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Department </td>
							<td> ".
								$budgetData['Department']['department_name']."<br>". 
								"<small>Requestor : ".$budgetData['User']['staff_name']."</small>".
							"</td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Amount </td>
							<td> RM ".number_format($totalBudget,2,".",","). " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Current Submission No. </td>
							<td>".$budgetData['Budget']['submission_no']. " </td>
						</tr>
					</table>";

		return $htmlTable;
	}

	private function rejectedBudgetTable($budgetid,$budgetData = array()){
		$budgetAmount = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		$totalBudget = $budgetAmount[0]['totalBudget'];

		$this->BStatus->contain(array('BReviewer','BReviewer.User'));
		$rejectedStatus = $this->BStatus->findByBudgetIdAndStatusAndSubmissionNo($budgetid,'rejected',$budgetData['Budget']['submission_no']);
		// debug($rejectedStatus);exit();

		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Title </td>
							<td>".$budgetData['Budget']['title']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Quarter (Year)</td>
							<td>".$budgetData['Budget']['quarter'] . " (".$budgetData['Budget']['year'].")". " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Amount </td>
							<td> RM ".$totalBudget. " </td>
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

	public function budgetExcel($budgetid){
		$this->Budget->contain(array('Department'));
		$budget = $this->Budget->findByBudgetId($budgetid);
		$this->set('budget',$budget);

		// Budget item data
		$this->BNewAmount->contain(array(
									'BItem',
									'BItem.BCategory'
								));
		$budgetItem = $this->BNewAmount->find('all',array('conditions'=>array('BNewAmount.budget_id'=>$budgetid),'order'=>array('BItem.category_id ASC')));
		$this->set('budgetItem',$budgetItem);

		$this->layout = 'mems-excel';
        $this->excelConfig =  array(
            'filename' => $budget['Budget']['title'].'_'.$budget['Budget']['quarter'].'_'.$budget['Budget']['year'].'.xlsx'
        );
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

  //   public function preloader($gif=null){
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

	// }

}