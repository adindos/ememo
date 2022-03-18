
<?php

	App::uses('AppController', 'Controller');

	class FmemodashboardController extends AppController{
		public $layout = 'mems';

		var $uses = array('User','FMemo','FMemoBudget','FStatus');


		/*
		 * Renders all dashboard information.
		 *
		 * @param () 
		 * @return ()
		 *
		 * latest modified 10/March @ Faridi
		 */	
		public function memoDashboard($memo_id){

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

 


		}









}