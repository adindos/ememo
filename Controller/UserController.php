
<?php



App::uses('AppController', 'Controller');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');


class UserController extends AppController{


var $uses = array('Setting','Company','Group','User','Budget','FMemo','NfMemo','BReviewer','BStatus','FReviewer','FStatus','NfStatus','NfReviewer');


public function index(){

		
}


public function login(){

	$this->layout = 'mems-login';

	if($this->Auth->loggedIn()){
		$user = $this->Auth->user();
	 //debug($user);exit;

		if($user['role_id']=='18'){
				return $this->redirect(array('controller'=>'user','action'=>'statistic'));
			}else{		
		return $this->redirect(array('controller'=>'user','action'=>'userDashboard'));}
	}

	if($this->request->is('post')){
		// $user = $this->User->findByEmail($this->request->data['User']['email']);

		$this->User->recursive = -1;
		//change this back to staff_id for production
		//$user = $this->User->find('first', array('conditions' => array('email' => $this->request->data['User']['email'])));
		$user = $this->User->find('first', array('conditions' => array('staff_id' => $this->request->data['User']['email'])));


		if($user['User']['status'] == 'disabled'){
			$this->Session->setFlash('<strong> Login Failed </strong><br/><small> You have been disabled, please contact the administrator</small>','flash.error');
			$this->redirect($this->referer());
		}


		if(empty($user)){

			$this->Session->setFlash('<strong> Login Failed </strong><br/><small> You have not been added to the system, please contact the administrator </small>','flash.error');
			$this->redirect($this->referer());

		}
		
		if($this->Auth->login()){
			$user = $this->Auth->user();
			//debug($user); exit;
			if($user['role_id']=='18'){
				return $this->redirect(array('controller'=>'user','action'=>'statistic'));
			}else{

			return $this->redirect(array('controller'=>'user','action'=>'userDashboard'));}
		}
		else{
			$this->Session->setFlash('<strong> Login Failed </strong><br/><small> Please make sure the username and password is correct</small>','flash.error');
		}

	}

}



	public function logout(){
		return $this->redirect($this->Auth->logout());
		

	}



	public function hasher($raw=null){

		$this->layout = 'mems-coder';
		$passwordHasher = new BlowfishPasswordHasher();
		$hashed = $passwordHasher->hash($raw);

		if($raw == null)

			$hashed = "Provide raw password!";

		$this->set('hashed',$hashed);

	}


	/*
	 * Provides the functionality to display the User Dashbaord and get the values needed by the dashboard.
	 *
	 * @input 
	 * @output 
	 *
	 * latest modified 4/March @ Faridi ptimized by aisyah 3/10/16
	 */
	public function userDashboard(){

		$userInfo = $this->getAuth();
 	
 		$this->set('userInfo',$userInfo);
 		$setting = $this->Setting->find('first');
		$this->set('setting',$setting);

 		//if admin, select all dept

 		//admins will see stats for all depts
		if(in_array($userInfo['Role']['role_id'], array(17,18))){
			//1. counts

			// get the count for all budgets
			$this->Budget->recursive = -1;
			$budgetCount = $this->Budget->find('count',array('conditions'=>array('Budget.submission_no NOT'=>0)));

			//get total fmemo
	 		$this->FMemo->recursive = -1;
	 		$financialCount = $this->FMemo->find('count',array('conditions'=>array('FMemo.submission_no NOT'=>0)));

	 		//get total nfmemo
	 		$this->NfMemo->recursive = -1;
	 		$xfinancialCount = $this->NfMemo->find('count',array('conditions'=>array('NfMemo.submission_no NOT'=>0)));

	 		$this->set('budgetCount', $budgetCount);
			$this->set('financialCount', $financialCount);
			$this->set('xfinancialCount', $xfinancialCount);


		}
		else{//other user will see dept specific data
			$this->Group->recursive=-1;
			$company=$this->Group->find('first', array('conditions'=>array('Group.group_id'=>$userInfo['Department']['group_id'])));
			// debug ($company);exit;
			// get the count for all budgets
			$this->Budget->recursive = -1;

			$budgetCount = $this->Budget->find('count',array('conditions'=>array('Budget.company_id' =>$company['Group']['company_id'] ,'Budget.submission_no NOT'=>0)));

			//get total fmemo
			$this->FMemo->recursive = 0;
	 		$financialCount = $this->FMemo->find('count', array('conditions' => array('Department.department_id' => $userInfo['Department']['department_id'],'FMemo.submission_no NOT'=>0)));
			//get total nfmemo
			$this->NfMemo->recursive = 0;
	 		$xfinancialCount = $this->NfMemo->find('count', array('conditions' => array('Department.department_id' => $userInfo['Department']['department_id'],'NfMemo.submission_no NOT'=>0)));

	 		$this->set('budgetCount', $budgetCount);
			$this->set('financialCount', $financialCount);
			$this->set('xfinancialCount', $xfinancialCount);

		}
 		
		//retrieve data for pending review budget		

		$this->BStatus->contain(array(
								'Budget'=>array('fields'=>array('year','submission_no','budget_id','created')),
								'Budget.User'=>array('fields'=>array('staff_name')),
								'Budget.BStatus'=>array('fields'=>array('submission_no','status')),
								'Budget.Company'=>array('fields'=>array('company')),
								// 'Budget.Department'=>array('fields'=>array('department_name')),
								'BReviewer'=>array('fields'=>array('reviewer_id'))
							)
						);
		$pendingBudget = $this->BStatus->find('all',array(
									'conditions'=>array('BStatus.status'=>'pending','BReviewer.user_id'=>$userInfo['user_id'],'BStatus.submission_no = Budget.submission_no',
														"NOT EXISTS (SELECT * FROM b_statuses WHERE b_statuses.reviewer_id < BReviewer.reviewer_id AND b_statuses.budget_id = BStatus.budget_id AND b_statuses.status ='pending')"),'order'=>'Budget.created DESC','limit'=>5,'fields'=>array('budget_id')
									)); 

		$this->set('budgetForAction', $pendingBudget);
 
		//retrieve data for pending review financial memo
		$this->FStatus->contain(array(
								'FMemo'=>array('fields'=>array('subject','ref_no','submission_no','memo_id','created')),
								'FMemo.User'=>array('fields'=>array('staff_name')),
								'FMemo.FStatus'=>array('fields'=>array('submission_no','status')),
								'FMemo.Department'=>array('fields'=>array('department_name')),
								'FReviewer'=>array('fields'=>array('reviewer_id'))
							)
						);
		$pendingMemo = $this->FStatus->find('all',array(
									'conditions'=>array('FStatus.status'=>'pending','FReviewer.user_id'=>$userInfo['user_id'],'FStatus.submission_no = FMemo.submission_no',
														"NOT EXISTS (SELECT * FROM f_statuses WHERE f_statuses.reviewer_id < FReviewer.reviewer_id AND f_statuses.memo_id = FStatus.memo_id AND f_statuses.status ='pending')")
									,'order'=>array('FMemo.created DESC'),'limit'=>5,'fields'=>array('memo_id'))); 

		//debug ($pendingMemo);exit;

		$this->set('financialmemos', $pendingMemo);

		//retieve data for pending review non financial memo
		$this->NfStatus->contain(array(
								'NfMemo'=>array('fields'=>array('subject','ref_no','submission_no','memo_id','created')),
								'NfMemo.User'=>array('fields'=>array('staff_name')),
								'NfMemo.NfStatus'=>array('fields'=>array('submission_no','status')),
								'NfMemo.Department'=>array('fields'=>array('department_name')),
								'NfReviewer'=>array('fields'=>array('reviewer_id'))
							)
						);
		$nfpendingMemo = $this->NfStatus->find('all',array(
									'conditions'=>array('NfStatus.status'=>'pending','NfReviewer.user_id'=>$userInfo['user_id'],'NfStatus.submission_no = NfMemo.submission_no',
														"NOT EXISTS (SELECT * FROM nf_statuses WHERE nf_statuses.reviewer_id < NfReviewer.reviewer_id AND nf_statuses.memo_id = NfStatus.memo_id AND nf_statuses.status ='pending')")
									,'order'=>array('NfMemo.created DESC'),'limit'=>5,'fields'=>array('memo_id'))); 

		//debug ($nfpendingMemo);
		$this->set('xfinancialmemos', $nfpendingMemo);



		// retrieve data for "My Budget"

		$this->Budget->contain(array('Company'=>array('fields'=>'company'),'BStatus'=>array('fields'=>array('submission_no','status'))));
		$userBudgets = $this->Budget->find('all', array('conditions' => array('Budget.user_id' => $userInfo['user_id'],'Budget.submission_no NOT'=>0),'fields'=>array('Budget.budget_id','Budget.year','Budget.submission_no','Budget.created'),'order' => 'Budget.created DESC', 'limit' => 5));

		//debug ($userBudgets);
		
		$this->set('userBudgets', $userBudgets);
		

		// retrieve data for "My Financial Memo"

		$this->FMemo->contain(array('FStatus'=>array('fields'=>array('submission_no','status'))));
		$userFinancialmemos = $this->FMemo->find('all', array('conditions' => array('FMemo.user_id' => $userInfo['user_id'],'FMemo.submission_no NOT'=>0),'fields'=>array('FMemo.memo_id','FMemo.subject','FMemo.submission_no','FMemo.created','FMemo.ref_no'),'order' => 'FMemo.created DESC', 'limit' => 5));

		
		$this->set('userFinancialmemos', $userFinancialmemos);

		// retrieve data for "My Non-Fiancial Memo"
		$this->NfMemo->contain(array('NfStatus'=>array('fields'=>array('submission_no','status'))));
		$userXfinancialmemos = $this->NfMemo->find('all', array('conditions' => array('NfMemo.user_id' => $userInfo['user_id'],'NfMemo.submission_no NOT'=>0),'fields'=>array('NfMemo.memo_id','NfMemo.subject','NfMemo.submission_no','NfMemo.created','NfMemo.ref_no'),'order' => 'NfMemo.created DESC', 'limit' => 5));

		$this->set('userXfinancialmemos', $userXfinancialmemos);



	}


	/*
	 * Provides the functionality to display data for the statistics page /mems/user/statics
	 *
	 * @input 
	 * @output 
	 *
	 * latest modified 4 March @ Faridi optimized 3/10/16 by aisyah
	 */
	public function statistic()
	{
		$user = $this->getAuth();
		$this->set('user', $user);

		//admins will see stats for all depts
		if(in_array($user['Role']['role_id'], array(17,18))){
		//1.  budget 
			// get the count for all budgets
			$this->Budget->recursive = -1;
			$budgetCount = $this->Budget->find('count',array('conditions'=>array('Budget.submission_no NOT'=>0)));
			
			// get the count for all approved budgets
			$counterBudgetApporved = $this->Budget->find('count', array('conditions' => array('Budget.budget_status' => 'approved','Budget.submission_no NOT'=>0),'order' => 'Budget.created DESC'));

			//get data for all budgets
			$this->Budget->contain(array('User'=>array('fields'=>array('staff_name')),
				'Company'=>array('fields'=>array('company')),
				'BStatus'=>array('fields'=>array('submission_no','status'))
				));
			$budgets = $this->Budget->find('all', array('conditions'=>array('Budget.submission_no NOT'=>0),'fields'=>array('Budget.budget_id','Budget.year','Budget.submission_no','Budget.created'),'order' => 'Budget.created DESC'));

	 		$this->set('budgets', $budgets);
			$this->set('budgetCount', $budgetCount);
			$this->set('counterBudgetApporved', $counterBudgetApporved);
		
			//2.financial memos

			//get total fmemo
	 		$this->FMemo->recursive = -1;
	 		$financialCount = $this->FMemo->find('count',array('conditions'=>array('FMemo.submission_no NOT'=>0)));
	 		//get total approved fmemo
	 		$counterFinancialApporved = $this->FMemo->find('count',array('conditions'=>array('FMemo.submission_no NOT'=>0,'FMemo.memo_status'=>'approved')));

	 		//get fmemo data
	 		//$this->FMemo->contain(array('User','Department','FReviewer','FStatus'));
 			$this->FMemo->contain(array('User'=>array('fields'=>array('staff_name')),
				'Department'=>array('fields'=>array('department_name')),
				'FStatus'=>array('fields'=>array('submission_no','status'))
				));
 			//for adminFinance , show only approved fmemo
 			if($user['Role']['role_id'] == '18'){
			
				$financialmemos = $this->FMemo->find('all', array('conditions'=>array('FMemo.submission_no NOT'=>0,'FMemo.memo_status'=>'approved'),'fields'=>array('FMemo.memo_id','FMemo.subject','FMemo.ref_no','FMemo.submission_no','FMemo.created'),'order' => 'FMemo.created DESC'));	
			}

			else{
				$financialmemos = $this->FMemo->find('all', array('conditions'=>array('FMemo.submission_no NOT'=>0),'fields'=>array('FMemo.memo_id','FMemo.subject','FMemo.ref_no','FMemo.submission_no','FMemo.created'),'order' => 'FMemo.created DESC'));
			}

			$this->set('financialmemos', $financialmemos);
			$this->set('financialCount', $financialCount);
			$this->set('counterFinancialApporved', $counterFinancialApporved);

			//3. non financial memos

			//get total nfmemo
	 		$this->NfMemo->recursive = -1;
	 		$xfinancialCount = $this->NfMemo->find('count',array('conditions'=>array('NfMemo.submission_no NOT'=>0)));
	 		//get total approved nfmemo
	 		$counterXFinancialApporved = $this->NfMemo->find('count',array('conditions'=>array('NfMemo.submission_no NOT'=>0,'NfMemo.memo_status'=>'approved')));

	 		//get nfmemo data
	 		//$this->NfMemo->contain(array('User','Department','NfReviewer','NfStatus'));
 			$this->NfMemo->contain(array('User'=>array('fields'=>array('staff_name')),
				'Department'=>array('fields'=>array('department_name')),
				'NfStatus'=>array('fields'=>array('submission_no','status'))
				));			
			$xfinancialmemos = $this->NfMemo->find('all', array('conditions'=>array('NfMemo.submission_no NOT'=>0),'fields'=>array('NfMemo.memo_id','NfMemo.subject','NfMemo.ref_no','NfMemo.submission_no','NfMemo.created'),'order' => 'NfMemo.created DESC'));
			

			$this->set('xfinancialmemos', $xfinancialmemos);
			$this->set('xfinancialCount', $xfinancialCount);
			$this->set('counterXFinancialApporved', $counterXFinancialApporved);


		}
		//other user than admins will see department specific stats
		else{
			//1.budget
			$this->Group->recursive=-1;
			$company=$this->Group->find('first', array('conditions'=>array('Group.group_id'=>$user['Department']['group_id'])));
			// get the count for all budgets
			$this->Budget->recursive = 0;
			$budgetCount = $this->Budget->find('count', array('conditions' => array('Budget.company_id' => $company['Group']['company_id'],'Budget.submission_no NOT'=>0)));
			// get the count for all approved budgets
			$counterBudgetApporved = $this->Budget->find('count', array('conditions' => array('Budget.company_id' => $company['Group']['company_id'],'Budget.budget_status' => 'approved','Budget.submission_no NOT'=>0)));
			//get data for all budgets
			$this->Budget->contain(array('User'=>array('fields'=>array('staff_name')),
				'Company'=>array('fields'=>array('company')),
				'BStatus'=>array('fields'=>array('submission_no','status'))
				));
			$budgets = $this->Budget->find('all', array('conditions' => array('Budget.company_id' => $company['Group']['company_id'],'Budget.submission_no NOT'=>0),'fields'=>array('Budget.budget_id','Budget.year','Budget.submission_no','Budget.created'),'order' => 'Budget.created DESC'));
	 		
	 		$this->set('budgets', $budgets);
			$this->set('budgetCount', $budgetCount);
			$this->set('counterBudgetApporved', $counterBudgetApporved);

			//2.financial memos
			//get total fmemo
			$this->FMemo->recursive = 0;
	 		$financialCount = $this->FMemo->find('count', array('conditions' => array('Department.department_id' => $user['Department']['department_id'],'FMemo.submission_no NOT'=>0)));
	 		//get total approved fmemo
	 		$counterFinancialApporved = $this->FMemo->find('count',array('conditions'=>array('Department.department_id' => $user['Department']['department_id'],'FMemo.submission_no NOT'=>0,'FMemo.memo_status'=>'approved')));

	 		//get fmemo data
	 		$this->FMemo->contain(array('User'=>array('fields'=>array('staff_name')),
				'Department'=>array('fields'=>array('department_name')),
				'FStatus'=>array('fields'=>array('submission_no','status'))
				));
			$financialmemos = $this->FMemo->find('all', array('conditions'=>array('Department.department_id' => $user['Department']['department_id'],'FMemo.submission_no NOT'=>0),'fields'=>array('FMemo.memo_id','FMemo.subject','FMemo.ref_no','FMemo.submission_no','FMemo.created'),'order' => 'FMemo.created DESC'));	
			

			$this->set('financialmemos', $financialmemos);
			$this->set('financialCount', $financialCount);			
			$this->set('counterFinancialApporved', $counterFinancialApporved);
			
			//3.non financial memos
			//get total nfmemo
			$this->NfMemo->recursive = 0;
	 		$xfinancialCount = $this->NfMemo->find('count', array('conditions' => array('Department.department_id' => $user['Department']['department_id'],'NfMemo.submission_no NOT'=>0)));
	 		//get total approved nfmemo
	 		$counterXFinancialApporved = $this->NfMemo->find('count',array('conditions'=>array('Department.department_id' => $user['Department']['department_id'],'NfMemo.submission_no NOT'=>0,'NfMemo.memo_status'=>'approved')));

	 		//get nfmemo data
	 		$this->NfMemo->contain(array('User'=>array('fields'=>array('staff_name')),
				'Department'=>array('fields'=>array('department_name')),
				'NfStatus'=>array('fields'=>array('submission_no','status'))
				));	
			$xfinancialmemos = $this->NfMemo->find('all', array('conditions'=>array('Department.department_id' => $user['Department']['department_id'],'NfMemo.submission_no NOT'=>0),'fields'=>array('NfMemo.memo_id','NfMemo.subject','NfMemo.ref_no','NfMemo.submission_no','NfMemo.created'),'order' => 'NfMemo.created DESC'));	
			

			$this->set('xfinancialmemos', $xfinancialmemos);
			$this->set('xfinancialCount', $xfinancialCount);			
			$this->set('counterXFinancialApporved', $counterXFinancialApporved);


			
		}


	}




	/*
		Beauty is more important in computing than anywhere else in technology because software is so complicated. 
		Beauty is the ultimate defence against complexity.

		        — David Gelernter
	*/



}