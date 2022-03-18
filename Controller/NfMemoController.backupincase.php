<?php

/*

*	=========================================================================

*	UNITAR MeMs 2015 

*	@vendor GR Tech Sdn Bhd

*	@team Aisyah,Syikin,Nizam

*	=========================================================================

*	

*	[ File ]

*		RequestorController.php

*			- Controller to control the action for NfMemo

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

*/

App::uses('AppController', 'Controller');



class NfMemoController extends AppController {

	public $layout = 'mems';
	public $uses = array('Department','NfMemo','User','NfStatus','NfReviewer','NfRemark','NfRemarkAssign','NfRemarkFeedback');

	/*

	*	index()

	*	< description of function >

	*	@ < author of function editor >

	*/	
	public function index(){

		$memo=$this->NfMemo->find('all',array());	//debug($memo); exit();					
		$this->set('memo',$memo);	
	}
	/*

	*	request()

	*	< description of function >

	*	@ < author of function editor >

	*/	

	public function request($memo_id=null){
		$user = $this->getAuth();

		$department_id=$user['department_id'];
	    $this->request->data['NfMemo']['department_id']=$department_id; 

   		$this->Department->recursive = 1;
   		$deptName=$this->Department->find('first',array('conditions'=>array('Department.department_id'=>$user['department_id'])));  
		$this->set('deptName',$deptName);	

		
			if ($this->request->is('post')|| $this->request->is('put')){ 
				
				if (empty($this->request->data['NfMemo']['memo_id'])){

					$this->NfMemo->create();
					$userid=$user['user_id']; //save userid
					$this->request->data['NfMemo']['user_id']=$userid;

					$this->request->data['NfMemo']['remark']='';
					$this->request->data['NfMemo']['date_required']=date('Y-m-d',strtotime($this->request->data['NfMemo']['date_required']));
					
					//debug($this->request->data);exit();
					
				}
				if ($this->NfMemo->save($this->request->data)){
					$memo_id = $this->NfMemo->id;
					$this->redirect(array('action' => 'confirm/'.$memo_id));
					$this->Session->setFlash(__('The Memo has been created successfully.'.$memo_id),'flash.success');
				}

				else{
						$this->Session->setFlash(__('The Memo could not be created. Please, try again.'),'flash.error');

					}
			}
			else{
					$this->Session->setFlash(__('Please complete the fields.'));
				}

			if($memo_id !=null){
					$memo = $this->NfMemo->find('first',array(
						'conditions' => array('NfMemo.memo_id' => $memo_id),
						'contain'=>array(
							'User'=>array('fields'=>array('staff_name','designation'),
							'Department'=>array('fields'=>array('department_name'))),
							'NfReviewer',
							// 'FVendorAttachment',
							)

						)); 
						$this->request->data = $memo;//debug($NfMemo); exit();
				}
				
			

	}


	public function confirm($memo_id=null){  		
		
	
		$this->set('memo_id',$memo_id);

		$reviewers = $this->User->find('list',array('conditions'=>array('User.reviewer'=>1)));
		$this->set('reviewers',$reviewers);
		$recommender = $this->User->find('list',array('conditions'=>array('User.reviewer'=>1)));
		$this->set('recommender',$recommender);
		$approvers = $this->User->find('list',array('conditions'=>array('User.approver'=>1)));
		$this->set('approvers',$approvers);

	}
	/*

	*	confirm()

	*	< description of function >

	*	@ < author of function editor >

	*/	

	public function confirmAdd ($memo_id=null){
		
		if($this->request->is('post')){
		 //debug($this->request->data);exit();
			$memo_id = $this->request->data['NfMemo']['memo_id']; 
			$this->NfMemo->id = $memo_id;
			$this->NfMemo->saveField('remark',$this->request->data['NfMemo']['remark']);

			$this->NfMemo->recursive = -1;
			$memo = $this->NfMemo->find('first',array('conditions'=>array('NfMemo.memo_id'=>$memo_id)));

			$seq=1;
			foreach($this->request->data['NfReviewer']['reviewer'] as $r):
				$reviewer['NfReviewer']['memo_id'] = $memo_id;
				$reviewer['NfReviewer']['user_id'] = $r;
				$reviewer['NfReviewer']['sequence'] = $seq++;
				$reviewer['NfReviewer']['approval_type'] = 'reviewer';
				$this->NfReviewer->create();
				if($this->NfReviewer->save($reviewer)){
					$reviewerid = $this->NfReviewer->id;
					$status['NfStatus']['reviewer_id'] = $reviewerid;
					$status['NfStatus']['status'] = 'pending';
					$status['NfStatus']['submission_no'] = $memo['NfMemo']['submission_no'];
					$status['NfStatus']['memo_id'] = $memo_id;
					$this->NfStatus->create();
					$this->NfStatus->save($status);
				}
			endforeach;

			//save the recommender
			foreach($this->request->data['NfReviewer']['recommender'] as $rec):
				$recommender['NfReviewer']['memo_id']=$memo_id;
				$recommender['NfReviewer']['user_id'] = $rec;
				$recommender['NfReviewer']['sequence'] = $seq++;
				$recommender['NfReviewer']['approval_type'] = 'recommender';
				$this->NfReviewer->create();
				if($this->NfReviewer->save($recommender)){
					$recommenderId=$this->NfReviewer->id;
					$status['NfStatus']['reviewer_id'] = $recommenderId;
					$status['NfStatus']['status'] = 'pending';
					$status['NfStatus']['submission_no'] = $memo['NfMemo']['submission_no'];
					$status['NfStatus']['memo_id'] = $memo_id;
					$this->NfStatus->create();
					$this->NfStatus->save($status);

				}
			endforeach;

			// save the approver
			foreach($this->request->data['NfReviewer']['approver'] as $a):
				$approver['NfReviewer']['memo_id'] = $memo_id;
				$approver['NfReviewer']['user_id'] = $a;
				$approver['NfReviewer']['sequence'] = $seq++;
				$approver['NfReviewer']['approval_type'] = 'approver';
				$this->NfReviewer->create();
				if($this->NfReviewer->save($approver)){
					$approverid = $this->NfReviewer->id;
					$status['NfStatus']['reviewer_id'] = $approverid;
					$status['NfStatus']['status'] = 'pending';
					$status['NfStatus']['submission_no'] = $memo['NfMemo']['submission_no'];
					$status['NfStatus']['memo_id'] = $memo_id;
					$this->NfStatus->create();
					$this->NfStatus->save($status);
				}
			endforeach;

			# send email to reviewer ***
			#$this->sendReviewEmail($memo_id);			

			$this->Session->setFlash('The memo has been submitted for review <br><small> You will be notified if the status changed later </small>','flash.success');
			$this->redirect(array('controller'=>'NfMemo','action'=>'dashboard',$memo_id));

		}
	}	
	

	public function review($memo_id=null){
		$user = $this->getAuth();
		$user_id=$user['user_id'];

		#check accessible of the memo for reviewer
		if(!$this->isInMemo($memo_id)){
			$this->Session->setFlash('<b> Not authorized </b><br><small> You are not authorized to view the memo. <small>','flash.error');
			$this->redirect(array('controller'=>'NfMemo','action'=>'index'));
		}	

		#get the related memo details
		$this->Department->recursive = 1;
   		$deptName=$this->Department->find('first',array('conditions'=>array('Department.department_id'=>$user['department_id'])));  
		$this->set('deptName',$deptName);
	
		$memo = $this->NfMemo->find('first',array(
				'conditions' => array('NfMemo.memo_id' => $memo_id),
				'contain'=>array(
				'User'=>array('fields'=>array('staff_name','designation'),'Department'=>array('fields'=>array('department_name'))
				),
				'NfReviewer',
				// 'FVendorAttachment',
				)

		)); 

		#check if the memo is rejected.for requestor		
		$this->NfStatus->contain(array('NfMemo'));
		$IsRejected= $this->NfStatus->find('first',array(
			'conditions'=>array(
				'NfStatus.submission_no'=>$memo['NfMemo']['submission_no'],
				'NfStatus.memo_id'=>$memo_id,
				'NfStatus.status'=>'rejected',
				'NfMemo.user_id'=>$user['user_id'])));
		//debug($IsRejected); exit();			


		# check if user already approve / reject memo
		$this->NfStatus->contain(array('NfReviewer','NfMemo'));
		$userStatus = $this->NfStatus->find('first',array(
			'conditions'=>array(
				'NfStatus.submission_no'=>$memo['NfMemo']['submission_no'],
				'NfStatus.memo_id'=>$memo_id,
				'NfStatus.status'=>'pending',
				'NfReviewer.user_id'=>$user['user_id'])));
		 //debug($userStatus);exit();

		#check for the status of previous memo is still pending or not
		$previousPending = false;
		if(!empty($userStatus)){
			$this->NfStatus->contain(array('NfReviewer','NfMemo'));
			$previousPending = $this->NfStatus->find('count',array(
				'conditions'=>array(
					'NfStatus.submission_no'=>$memo['NfMemo']['submission_no'],
					'NfStatus.memo_id'=>$memo_id,
					'NfStatus.status'=>'pending',
					'NfReviewer.sequence <'=>$userStatus['NfReviewer']['sequence'])));
		} 
		
		

		#check user privilege
		$edit=true;
		$approval=true;
		$remark=true;
		//$showButton = true;

		

		#if user already reviewed the memo or the status is still pending
		if(empty($userStatus) || $previousPending){
			//$showButton = false;
			$edit=false;
			$approval=false;
			$remark=false;

		}
		
		#if the current user is in memo.requestor
		if($user['user_id'] ==  $memo['NfMemo']['user_id']){
			//$showButton = false;
			$edit=false;
			$approval=false;
			$remark=false; 

			#if the memo is rejected. enable edit(requestor)
			if(!empty($IsRejected)){
				$edit=true;				
			}						

		}

		// debug($showButton);exit();
		$this->set('edit',$edit);
		$this->set('approval',$approval);
        $this->set('remark',$remark);
 		$this->set('memo',$memo);
 		$this->set('edit',$edit);
 		$this->set('remark',$remark);
 		$this->set('approval',$approval);
 		//debug($memo);exit;
 		$reviewer=$this->query1($memo_id,'reviewer',$memo['NfMemo']['submission_no']); //debug($reviewer); exit();
 		$recommender=$this->query1($memo_id,'recommender',$memo['NfMemo']['submission_no']); 
 		//$finance=$this->query1($memo_id,'finance',$memo['NfMemo']['submission_no']);
 		$approver=$this->query1($memo_id,'approver',$memo['NfMemo']['submission_no']); 
		
		$remark_reviewer=array();
		$remark_recommender=array();
		//$remark_finance=array();
		$remark_approver=array();
		
		if (!empty($reviewer)){
			foreach ($reviewer as $value) {
				
				$remark_reviewer[]=$this->query2($memo_id,$value['NfReviewer']['user_id']);
			}
			
		}

		if (!empty($recommender)){
			foreach ($recommender as $value) {
				
				$remark_recommender[]=$this->query2($memo_id,$value['NfReviewer']['user_id']);
			}
			
		}

		// if (!empty($finance)){
		// 	foreach ($finance as $value) {
				
		// 		$remark_finance[]=$this->query2($memo_id,$value['NfReviewer']['user_id']);
		// 	}
			
		// }

		if (!empty($approver)){
			foreach ($approver as $value) {
				
				$remark_approver[]=$this->query2($memo_id,$value['NfReviewer']['user_id']);
			}
			
		}

		$this->set('reviewer',$reviewer);
 		$this->set('recommender',$recommender);
 		// $this->set('finance',$finance);
 		$this->set('approver',$approver);
 		$this->set('remark_reviewer',$remark_reviewer);
 		$this->set('remark_recommender',$remark_recommender);
 		// $this->set('remark_finance',$remark_finance);
 		$this->set('remark_approver',$remark_approver);
 		$this->set('memo_id',$memo_id);

	}	

	private function query1($memo_id=null,$reviewer_type=null,$submission_no=null){

		$reviewer_query = $this->NfReviewer->find('all',array(
			'conditions' => array(
				
				'NfReviewer.memo_id' => $memo_id,
				'NfReviewer.approval_type'=>$reviewer_type,
			),
			'order'=>array(
				'NfReviewer.sequence'=>'ASC'
			),
			'contain'=>array(
				'NfStatus'=>array(
					'conditions'=>array('NfStatus.submission_no'=>$submission_no,'NfStatus.memo_id'=>$memo_id)
				),
				'User'=>array(
					'fields'=>array('user_id','staff_name','designation'),
					'Department'=>array('fields'=>array('department_name')),
				),
				// 'FRemark'=>array(
				// 	'conditions'=>array('FRemark.memo_id'=>$memo_id),
				// 	'fields'=>array('subject'),
				// 	'order'=>array('FRemark.created'=>'ASC'),
				// 	'User'=>array('fields'=>array('name')),
				// 	'FRemarkFeedback'=>array('User'=>array('fields'=>array('name')),'order'=>array('FRemarkFeedback.created'=>'ASC')),
				// 	'FRemarkAssign'=>array('User'=>array('fields'=>array('name'))),

				// ),
			)

		));

		return($reviewer_query);

	}

	private function query2($memo_id=null,$reviewer_uid=null){

		$remark_query=$this->NfRemark->find('all',array(
			'conditions' => array(
		
				'NfRemark.memo_id' => $memo_id,
				'NfRemark.user_id'=>$reviewer_uid,
			),
			'fields'=>array('subject'),
			'order'=>array('NfRemark.created'=>'ASC'),
			'contain'=>array(
				'User'=>array('fields'=>array('staff_name')),
				'NfRemarkFeedback'=>array('fields'=>array('feedback','created'),'User'=>array('fields'=>array('staff_name')),'order'=>array('NfRemarkFeedback.created'=>'ASC')),
				'NfRemarkAssign'=>array('fields'=>array(''),'User'=>array('fields'=>array('staff_name'))),
			)

		));

		return($remark_query);

	}

	public function approveRejectMemo(){
		$user = $this->getAuth();
		$userid = $user['user_id'];

		$memo_id = $this->request->data['NfStatus']['memo_id'];
		// debug($this->request->data);exit();
		$this->NfStatus->contain(array('NfReviewer'));
		$NfStatus = $this->NfStatus->find('first',array('conditions'=>array('NfReviewer.user_id'=>$userid,'NfReviewer.memo_id'=>$memo_id),'order'=>array('NfStatus.submission_no DESC')));

		$statusid = $NfStatus['NfStatus']['status_id'];
		$submissionNo = $NfStatus['NfStatus']['submission_no'];
		#access check
		if(empty($NfStatus)){
			$this->Session->setFlash("You don't have the privileges to approve/reject the memo <br><small> Please consult the administrator </small>",'flash.error');
			$this->redirect($this->referer());
		}
		$remark = $this->request->data['NfStatus']['remark'];
		$status = $this->request->data['NfStatus']['status'];

		if($status == 'rejected'){
			$this->NfStatus->updateAll(array(
				'NfStatus.status'=>"'pending-rejected'",
				),
				array(
					'NfStatus.submission_no'=>$submissionNo,
					'NfStatus.memo_id'=>$memo_id,
					'NfStatus.status'=>'pending',
				)
			);
		}
		$this->NfStatus->id = $statusid;
		if( $this->NfStatus->updateAll(array(
				'NfStatus.remark'=>"'".$remark."'",
				'NfStatus.status'=>"'".$status."'",
				),
				array(
					'NfStatus.submission_no'=>$submissionNo,
					'NfStatus.status_id'=>$statusid)) 
			){

		#only send email after status changed
		// if($status == 'approved'){
		// 	$this->sendReviewEmail($memo_id);
		// }
		// elseif($status == 'rejected'){
		// 	$this->sendRejectedEmail($memo_id);
		// }

		$this->Session->setFlash("You have ".$status." the memo <br><small> Thank You </small>",'flash.success');
		$this->redirect(array('controller'=>'NfMemo','action'=>'dashboard',$memo_id));
	}

	}

	public function dashboard($memo_id=null){
		$user = $this->getAuth();
		$userid = $user['user_id'];
		$department_id=$user['department_id'];

		#non-financial memo details
		$this->NfMemo->contain(array(
							'User'=>array('fields'=>array('staff_name','designation')),
							'User.Department'=>array('fields'=>array('department_name')),
							'Department',
						));
		$memo = $this->NfMemo->find('first',array('conditions'=>array('NfMemo.memo_id'=>$memo_id)));
		$this->set('memo',$memo);
		//debug($memo); exit();			

		# reviewer
		$this->NfStatus->contain(array(
							'NfReviewer',
							'NfReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$reviewers = $this->NfStatus->find('all',array('conditions'=>array('NfStatus.submission_no'=>$memo['NfMemo']['submission_no'],'NfReviewer.memo_id'=>$memo_id,'NfReviewer.approval_type'=>'reviewer'),'order'=>array('sequence ASC')));
		$this->set('reviewers',$reviewers);
		//debug($reviewers); exit();

		# Recommender
		$this->NfStatus->contain(array(
							'NfReviewer',
							'NfReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$Recommenders = $this->NfStatus->find('all',array(
			'conditions'=>array(
				'NfStatus.submission_no'=>$memo['NfMemo']['submission_no'],
				'NfReviewer.memo_id'=>$memo_id,
				'NfReviewer.approval_type'=>'Recommender'),
				'order'=>array('sequence ASC')));
		$this->set('Recommenders',$Recommenders);
		//debug($Recommenders); exit();

		# approver
		$this->NfStatus->contain(array(
							'NfReviewer',
							'NfReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$approvers = $this->NfStatus->find('all',array(
			'conditions'=>array(
				'NfStatus.submission_no'=>$memo['NfMemo']['submission_no'],
				'NfReviewer.memo_id'=>$memo_id,
				'NfReviewer.approval_type'=>'approver'),
				'order'=>array('sequence ASC')));
		$this->set('approvers',$approvers);




		
		//debug($r); exit();
		$this->set('memo_id',$memo_id);
	}

	public function editmemo($memo_id=null){
		$user = $this->getAuth();
		$userid = $user['user_id'];

		$memo = $this->NfMemo->find('first',array(
				'conditions' => array('NfMemo.memo_id' => $memo_id),
				'contain'=>array(
					'User'=>array('fields'=>array('staff_name','designation'),
					'Department'=>array('fields'=>array('department_name'))),
					'NfReviewer',	
					)

				)); 
				

		if($this->request->is(array('post','put'))){
			$this->NfMemo->id = $memo_id;
		    $this->NfMemo->saveField('submission_no',$memo['NfMemo']['submission_no']+1);
			$this->request->data['NfMemo']['date_required']=date('Y-m-d',strtotime($this->request->data['NfMemo']['date_required']));
			if($this->NfMemo->save($this->request->data)){
				$memo_id = $this->NfMemo->id;
				$this->redirect(array('action' => 'confirm/'.$memo_id));
				$this->Session->setFlash(__('The Memo has been Update successfully.'.$memo_id),'flash.success');

			}
			else{
				$this->Session->setFlash(__('The Memo could not be update. Please, try again.'),'flash.error');
			}
		}
		else{			
			
			$this->request->data = $memo;//debug($NfMemo); exit();
				$this->set('memo_id',$memo_id);
				$this->set('memo',$memo);
			

		}		

	}

	public function memoAllrequestNon(){
		$user = $this->getAuth();

		$memo=$this->NfMemo->find('all',array('conditions'=>array('Department.department_id'=>$user['department_id'])));	//debug($memo); exit();					
		$this->set('memo',$memo);
	}

	public function memolistManagementNon(){
		$user = $this->getAuth();
	
		
		$memo=$this->NfMemo->find('all',array());	//debug($memo); exit();					
		$this->set('memo',$memo);	


	
		

		$this->NfStatus->contain(array('NfReviewer','NfMemo'));
		$memoListMgt = $this->NfStatus->find('all',array(
			'conditions'=>array(
				'NfStatus.submission_no'=>'NfMemo.submission_no',
				'NfStatus.status'=>'pending',
				'NfReviewer.user_id'=>$user['user_id'])));
		$this->set('memoListMgt',$memoListMgt);	
		//debug($memoListMgt); exit();
	}

	/*

	*	isInMemo()

	*	< check if the reviewer are in the memo or not >

	*	@ < author of function editor >

	*/	
	public function isInMemo($memo_id){
		$user = $this->getAuth();

		$this->NfReviewer->recursive = -1;;
		$reviewer = $this->NfReviewer->find('first',array('conditions'=>array('NfReviewer.user_id'=>$user['user_id'],'NfReviewer.memo_id'=>$memo_id)));
		// get budget detail
		$this->NfMemo->recursive = -1;
		$memo = $this->NfMemo->find('first',array('conditions'=>array('NfMemo.memo_id'=>$memo_id)));

		return ((!empty($reviewer)) || ($user['user_id'] ==  $memo['NfMemo']['user_id']));
	}


		
		




		

		



	

		





}
