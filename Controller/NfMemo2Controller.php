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

*/

App::uses('AppController', 'Controller');



class NfMemo2Controller extends AppController {

	public $uses = array('Setting','Department','NfMemo','NfComment','NfMemoBudget','NfRemark','NfRemarkAssign','NfRemarkFeedback','NfReviewer','NfStatus','NfActivities','BNewAmount','BItem','Staff','NfMemoTo','NfAttachment','Group',);

	public $layout = 'mems';


#region "Page Events"
	public function index(){
		
		$user = $this->getAuth();
		// Check user role
		if(!$user['Role']['my_request_memo']){
			throw new ForbiddenException();
		}

		$userid=$user['user_id'];
		//default condition without filter		
		$conditions = array('NfMemo.user_id'=>$userid);

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
                array_push($conditions,array('NfMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
            }
            if(!empty($toData)){
                array_push($conditions,array('NfMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
            }

        }

		$this->NfMemo->contain(array(
								'NfStatus'=>array('fields'=>array('status','submission_no')),
								'NfRemark.NfRemarkAssign'=>array('conditions'=>array('NfRemarkAssign.user_id'=>$userid),'fields'=>array('NfRemarkAssign.user_id')),
								'Department'=>array('fields'=>array('department_name')),
								'Department.Group'=>array('fields'=>array('group_name'))
								));

		$memo=$this->NfMemo->find('all',array('conditions'=> $conditions,'order'=>array('NfMemo.created'=>'Desc')));
    
        $this->set('memo',$memo);
		
		$setting=$this->Setting->find('first');
		$this->set('setting',$setting);

		
	}

	

	public function myMemo(){
		
		$user = $this->getAuth();
		// Check user role
		if(!$user['Role']['my_memo_memo']){
			throw new ForbiddenException();
		}
		$userid=$user['user_id'];
		//$this->FMemo->contain(array('NfStatus'));
		$this->NfMemoTo->contain(array(
								'NfMemo.NfStatus'=>array('fields'=>array('status','submission_no')),
								'NfMemo',
								'NfMemo.NfComment',
								'NfMemo.Department'=>array('fields'=>array('department_name')),
								'NfMemo.Department.Group'=>array('fields'=>array('group_name'))));
		
		$memo=$this->NfMemoTo->find('all',array('conditions'=>array('NfMemoTo.user_id'=>$userid,'NfMemo.submission_no NOT'=>0),'order'=>array('NfMemo.created'=>'DESC')));
		$this->set('memo',$memo);	
		// $this->set('roleid',$user['role_id']);
		

	}


	public function dashboard($memo_id){
		$user = $this->getAuth();
		

		$encrypted = $memo_id; // save encrypted id
		$memo_id = $this->decrypt($memo_id);


		$this->NfMemo->contain(array('Department','User','User.Department'));
		$memo = $this->NfMemo->find('first', array('conditions' => array('memo_id' => $memo_id)));

		$memo['encrypted'] = $encrypted;

		$this->set('memo', $memo);

		// reviewer
		$this->NfStatus->contain(array(
							'NfReviewer',
							'NfReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$reviewers = $this->NfStatus->find('all',array('conditions'=>array('NfStatus.submission_no'=>$memo['NfMemo']['submission_no'],'NfReviewer.memo_id'=>$memo_id,'NfReviewer.approval_type'=>'reviewer'),'order'=>array('sequence ASC')));
		$this->set('reviewers',$reviewers);

		// approver
		$this->NfStatus->contain(array(
							'NfReviewer',
							'NfReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$approvers = $this->NfStatus->find('all',array('conditions'=>array('NfStatus.submission_no'=>$memo['NfMemo']['submission_no'],'NfReviewer.memo_id'=>$memo_id,'NfReviewer.approval_type'=>'approver'),'order'=>array('sequence ASC')));
		$this->set('approvers',$approvers);

		// recommender
		$this->NfStatus->contain(array(
							'NfReviewer',
							'NfReviewer.User'=>array('fields'=>array('staff_name','designation')),
						));
		$recommenders = $this->NfStatus->find('all',array('conditions'=>array('NfStatus.submission_no'=>$memo['NfMemo']['submission_no'],'NfReviewer.memo_id'=>$memo_id,'NfReviewer.approval_type'=>'recommender'),'order'=>array('sequence ASC')));
		$this->set('recommenders',$recommenders);

		// check if there is pending action for current user
		$userid=$user['user_id'];
		$this->NfStatus->contain(array('NfReviewer'));
		$isMyPending = $this->NfStatus->find('first',array('conditions'=>array('NfStatus.status'=>'pending',
																	'NfReviewer.user_id'=>$userid,
																	'NfStatus.memo_id'=>$memo_id,
																	'NfStatus.submission_no'=>$memo['NfMemo']['submission_no']
																	)));
		if($isMyPending){
			$this->Session->setFlash('<b> This memo is waiting for your review </b><br><small>Please go to review page by clicking the <em><b>View Memo Details</b></em> button below</small>','flash.info');
		}

		// setting 
		$setting = $this->Setting->find('first');
		$this->set('setting',$setting);

	}
	
	public function allRequest(){
		
		$user = $this->getAuth();
		// Check user role
		if(!$user['Role']['all_request_memo']){
			throw new ForbiddenException();
		}
		$department_id=$user['department_id'];
		
		//if superadmin, allow to see requests from all departments Aisyah 28/9/15
		if ($user['role_id']==17){
			$conditions = array('NfMemo.submission_no NOT'=>0);
			
		}
		//if financial admin, allow to see approved request from all departments cikin.my 2/3/16
		else if ($user['role_id']==18){
			$conditions = array('NfMemo.submission_no NOT'=>0,'NfMemo.memo_status'=>'approved');
			
		}
		
		else{
			$conditions = array('NfMemo.submission_no NOT'=>0,'NfMemo.department_id'=>$department_id);
			
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
                array_push($conditions,array('NfMemo.department_id'=>$deptData));
            }
            if(!empty($userData)){
                array_push($conditions,array('NfMemo.user_id'=>$userData));
            }
            if(!empty($fromData)){
                array_push($conditions,array('NfMemo.created >='=>date('Y-m-d 00:00:00',strtotime($fromData))));
            }
            if(!empty($toData)){
                array_push($conditions,array('NfMemo.created <='=>date('Y-m-d 23:59:59',strtotime($toData))));
            }

             $this->NfMemo->contain(array('NfStatus','User','Department'=>array('fields'=>array('department_name')),
            'Department.Group'=>array('fields'=>array('group_name'))));
           
            $memo=$this->NfMemo->find('all',array('conditions'=> $conditions,'order'=>array('NfMemo.created'=>'Desc')));
        
            $this->set('memo',$memo);
           
        }

        if (!isset($memo))
           $this->Session->setFlash('<b> Please select the filter on the non-financial memo you want to view. </b>','flash.info');

        $this->set('groups',$this->Group->find('list',array('order'=>'group_name Asc')));
        $this->set('departments',$this->Department->find('list',array('order'=>'code_name Asc')));
        $this->set('allUsers',$this->User->find('list',array('order'=>'staff_name Asc')));

		// setting 
		$setting = $this->Setting->find('first');
		$this->set('setting',$setting);	

	}

	public function myReview(){
		
		$user = $this->getAuth();
		// Check user role
		if(!$user['Role']['request_management_memo']){
			throw new ForbiddenException();
		}
		$this->NfMemo->contain(array('NfStatus'));
		
		//also should display when other previous reviewer has approved
		$this->NfStatus->contain(array(
								'NfMemo',
								'NfMemo.User'=>array('fields'=>array('staff_name')),
								'NfMemo.NfStatus',
								'NfMemo.Department'=>array('fields'=>array('department_name')),
								'NfMemo.Department.Group'=>array('fields'=>array('group_name')),
								'NfReviewer',
							)
						);
		$pendingMemo = $this->NfStatus->find('all',array(
									'conditions'=>array('NfStatus.status'=>'pending','NfReviewer.user_id'=>$user['user_id'],'NfStatus.submission_no = NfMemo.submission_no',
														"NOT EXISTS (SELECT * FROM nf_statuses WHERE nf_statuses.reviewer_id < NfReviewer.reviewer_id AND nf_statuses.memo_id = NfStatus.memo_id AND nf_statuses.status ='pending')")
									,'order'=>array('NfMemo.created'=>'Desc'))); // this is truee laaa -- just need to make sure when equal to same find,use equal like in submission_no
		


	

		$this->set('pendingMemo',$pendingMemo);
		// debug($pendingBudget);exit();

		$this->NfStatus->contain(array(
								'NfMemo',
								'NfMemo.User'=>array('fields'=>array('staff_name')),
								'NfMemo.NfStatus',
								'NfMemo.Department'=>array('fields'=>array('department_name')),
								'NfMemo.Department.Group'=>array('fields'=>array('group_name')),
								'NfReviewer',
							)
						);
		$reviewedMemo = $this->NfStatus->find('all',array('conditions'=>array(
													"OR"=>array(array("NfStatus.status"=>"approved"),array("NfStatus.status"=>"rejected")),
													"NfStatus.submission_no = NfMemo.submission_no",
													"NfReviewer.user_id"=>$user["user_id"]
												),'order'=>array('NfMemo.created'=>'Desc')));


		$this->set('reviewedMemo',$reviewedMemo);
		$setting = $this->Setting->find('first');
		$this->set('setting',$setting);
	}

	public function request($memo_id=null,$edit=null){
		$user = $this->getAuth();
		$setting = $this->Setting->find('first');

		//phase 2:check if memo is locked
		if ($setting['Setting']['nonfinancial_memo']){
			$this->Session->setFlash(__('<b>The access to Non-Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}
		//check user privilege, by default disable all access
		$editFlag=false;

   		//find staff list
   		$this->Staff->recursive = -1;
		$staffs=$this->User->find('list',array('conditions'=>array('User.status'=>'enabled','User.user_id NOT'=>$user['user_id'])));
		$this->set('staffs',$staffs);	
		
		$selected=array();
	

		
		//if (!empty($memo_id)){
			
			if (($this->request->is('post')||$this->request->is('put'))&&$memo_id=='new'){
				if($user['requestor']){
				
					if (!empty($this->request->data['NfMemo']['subject'])){

						$this->request->data['NfMemo']['user_id']=$user['user_id'];
						$this->request->data['NfMemo']['department_id']=$user['department_id'];
						$this->request->data['NfMemo']['submission_no']=0;
						if ($this->request->data['NfMemo']['date_required']!='')
							$this->request->data['NfMemo']['date_required']=date('Y-m-d',strtotime($this->request->data['NfMemo']['date_required']));
						$this->NfMemo->create();
						$this->NfMemo->save($this->request->data);
						$memo_id=$this->NfMemo->id;
						//after saving memo, save 2 default users to fmemoto
						$this->User->recursive=-1;
						$defaultTo=$this->User->find('all',array('conditions'=>array('staff_id'=>array('iskandarsham','niksharifidin'))));
						if (!empty($defaultTo)){

							foreach ($defaultTo as $value) {
								$this->request->data['NfMemoTo']['user_id']=$value['User']['user_id'];
								$this->request->data['NfMemoTo']['memo_id']=$memo_id;
								$this->NfMemoTo->create();
								$this->NfMemoTo->save($this->request->data);
							}
						}

					}
					else{
						
						$this->Session->setFlash(__('<b>Please Fill in all required fields.</b>'),'flash.error');
						return $this->redirect($this->referer());
					}
				}
				else{

					$this->Session->setFlash(__('<b>You are not authorized to create/edit the memo.</b>'),'flash.error');
					return $this->redirect(array('controller'=>'User','action'=>'userDashboard'));

				}
				
			}else{


				$encrypted = $memo_id; // save encrypted id
				$memo_id = $this->decrypt($memo_id);


			}
			

			if (!$this->NfMemo->exists($memo_id)) {

				throw new ForbiddenException();

			}

			$this->NfMemo->contain(array('NfAttachment','Department','User'=>array('fields'=>'staff_name','designation')));
			$memo=$this->NfMemo->find('first',array('conditions'=>array('NfMemo.memo_id'=>$memo_id)));
			$this->NfStatus->contain('NfReviewer');
			$status=$this->NfStatus->find('all',array('conditions'=>array('NfStatus.memo_id'=>$memo_id,'NfStatus.submission_no'=>$memo['NfMemo']['submission_no']),'order'=>array('NfReviewer.sequence'=> 'ASC')));
			//if requestor, allow access only if save/any reviewer/recommender/finance reject
			if ($user['user_id']==$memo['NfMemo']['user_id']){
				if ($memo['NfMemo']['submission_no']==0)
						$editFlag=true;
				else{

					foreach ($status as $value) {
					//if ($value['NfStatus']['status']=='rejected'&&$value['NfReviewer']['approval_type']!='approver'){
						if ($value['NfStatus']['status']=='rejected'){
							$editFlag=true;
							break;
						}
					}
				}	
			}

			else{//if user is one of the reviewers
				for($i=0;$i<count($status);$i++){

					if($status[$i]['NfReviewer']['user_id']==$user['user_id']){
						
						if ($status[$i]['NfStatus']['status']=='pending'){
							if ($i==0||$status[$i-1]['NfStatus']['status']=='approved'){//first reviewer or status before is approved
								$editFlag=true;
								break;
							}
							
						}
					}
					
				}

			}

			if ($editFlag){
				$this->request->data=$memo;
				$department_id=$memo['NfMemo']['department_id'];
				
				//selected staff
				$this->NfMemoTo->contain(array('User'=>array('fields'=>array('User.user_id','User.staff_name','User.designation'))));//added jun 1 2015
				$selectedStaff=$this->NfMemoTo->find('all',array('conditions'=>array('NfMemoTo.memo_id'=>$memo_id)));
				if (!empty($selectedStaff)){
					foreach ($selectedStaff as $value) {
					$selected[]=$value['NfMemoTo']['user_id'];
				  }					
			    }


			    $encrypted = $this->encrypt($memo_id);


			    $this->set('encrypted',$encrypted);
				$this->set('department_id',$department_id);
				$this->set('edit',$edit);	
				$this->set('memo_id',$encrypted);	
				$this->set('selected',$selected);
				$this->set('selectedStaff',$selectedStaff);
			}

			else{

				$this->Session->setFlash(__('<b>You are not authorized to create/edit the memo.</b>'),'flash.error');
				return $this->redirect(array('controller'=>'User','action'=>'userDashboard'));
				
				
			}

		// }

		// else{

		// 	$this->Session->setFlash(__('<b>Please contact system administrator.</b>'),'flash.error');
		// 	return $this->redirect($this->referer());

		// }
	
	}

	public function review($memo_id=null){

			
			$user = $this->getAuth();
			$encrypted = $memo_id;
			$memo_id = $this->decrypt($memo_id);
			
			if (!$this->NfMemo->exists($memo_id)) {

				throw new ForbiddenException();

			}
			$userid = $user['user_id'];
			$memo = $this->NfMemo->find('first',array(
				'conditions' => array('NfMemo.memo_id' => $memo_id),
				'contain'=>array(
					'User'=>array('fields'=>array('staff_name','designation'),'Department'=>array('fields'=>array('department_name'))
					),
					'NfReviewer',
					'NfAttachment',
					'NfMemoTo'=>array('User'=>array('fields'=>array('User.staff_name','User.staff_id','User.designation'))),
					//'NfMemoBudget',
					//'NfMemoBudget.BItem'

				)

			)); //debug($memo); exit();
			$submission_no=$memo['NfMemo']['submission_no'];
			//debug($memo);exit; 
			//check user privilege, by default disable all buttons
			$editFlag=false;
			$approvalFlag=false;
			$remarkFlag=false;
			$commentFlag=false;

			// $financeFlag=false;

			$this->NfStatus->contain('NfReviewer');
			$status=$this->NfStatus->find('all',array('conditions'=>array('NfStatus.memo_id'=>$memo_id,'NfStatus.submission_no'=>$submission_no),'order'=>array('NfReviewer.sequence'=> 'ASC')));
			//find status before to check if it got rejected by approver
			// $approverReject=$this->NfStatus->find('first',array('conditions'=>array('NfStatus.memo_id'=>$memo_id,'NfStatus.submission_no'=>$submission_no-1,'NfReviewer.approval_type'=>'approver','NfStatus.status'=>'rejected')));
			

			if($user['user_id']==$memo['NfMemo']['user_id']){//if user is the memo requestor,only allow edit if the current submission status if any of reviewer reject
			//if($user['user_id']==$memo['NfMemo']['user_id'] && $user['requestor']=>1){//test
				foreach ($status as $value) {
					//if ($value['NfStatus']['status']=='rejected'&&$value['NfReviewer']['approval_type']!='approver'){
					if ($value['NfStatus']['status']=='rejected'){
						$editFlag=true;
						break;
					}
				}

				
				$remarkFlag=true;
				$commentFlag=true;

			}

			// else{//if user is one of the reviewers  //disabled because user can be reviewer
				for($i=0;$i<count($status);$i++){

					if($status[$i]['NfReviewer']['user_id']==$user['user_id']){					
						$commentFlag=true;
						$remarkFlag=true;

						
						if ($status[$i]['NfStatus']['status']=='pending'){
							if ($i==0||$status[$i-1]['NfStatus']['status']=='approved'){//first reviewer or status before is approved
								
								$editFlag=true;
								$approvalFlag=true;

								break;
							}
						}
					}
					
				}

			// }

			foreach ($memo['NfMemoTo'] as $value) {
				//if ($value['NfStatus']['status']=='rejected'&&$value['NfReviewer']['approval_type']!='approver'){
				if ($value['user_id']==$user['user_id']){
					$commentFlag=true;
					break;
				}
			}

			//phase 2: check if memo is disabled , if yes disable edit/approval
			$setting = $this->Setting->find('first');
			if($setting['Setting']['nonfinancial_memo']){
				$editFlag=false;
				$approvalFlag=false;

			}

			//debug($memo);exit;
	 		$this->set('memo',$memo);
	 		//$this->set('approverReject',$approverReject);
	 		$this->set('editFlag',$editFlag);
	 		$this->set('remarkFlag',$remarkFlag);
	 		$this->set('approvalFlag',$approvalFlag);
 			$this->set('commentFlag',$commentFlag);

	 		// $this->set('financeFlag',$financeFlag);
	 		//debug($memo);exit;
	 		$reviewer=$this->getReviewer($memo_id,'reviewer',$submission_no);
	 		$recommender=$this->getReviewer($memo_id,'recommender',$submission_no);
	 		// $finance=$this->getReviewer($memo_id,'finance',$submission_no);
	 		$approver=$this->getReviewer($memo_id,'approver',$submission_no);
			
			$remark_reviewer=array();
			$remark_recommender=array();
			// $remark_finance=array();
			$remark_approver=array();
			
			if (!empty($reviewer)){
				foreach ($reviewer as $value) {
					
					$remark_reviewer[]=$this->getRemark($memo_id,$value['NfReviewer']['reviewer_id']);
				}
				
			}

			if (!empty($recommender)){
				foreach ($recommender as $value) {
					
					$remark_recommender[]=$this->getRemark($memo_id,$value['NfReviewer']['reviewer_id']);
				}
				
			}

			// if (!empty($finance)){
			// 	foreach ($finance as $value) {
					
			// 		$remark_finance[]=$this->getRemark($memo_id,$value['NfReviewer']['user_id']);
			// 	}
				
			// }

			if (!empty($approver)){
				foreach ($approver as $value) {
					
					$remark_approver[]=$this->getRemark($memo_id,$value['NfReviewer']['reviewer_id']);
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
	 		$this->set('memo_id',$this->encrypt($memo_id));
	}

	public function confirm($memo_id){

		//phase 2:check if memo is locked
		$setting = $this->Setting->find('first');
		if ($setting['Setting']['nonfinancial_memo']){
			$this->Session->setFlash(__('<b>The access to Non-Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$encrypted = $memo_id;
		$memo_id = $this->decrypt($encrypted);

		$user = $this->getAuth();

		if (!$this->NfMemo->exists($memo_id)) {

			throw new ForbiddenException();

		}
		//#changed may7 2015
		//$this->NfMemo->recursive=-1;
		$this->NfMemo->contain(array('Department'=>array('fields'=>array('group_id'))));
		$memo=$this->NfMemo->find('first',array('conditions'=>array('NfMemo.memo_id'=>$memo_id)));
		//if requestor, allow access only if save/any reviewer/recommender/finance reject
		if ($user['user_id']!=$memo['NfMemo']['user_id']||$memo['NfMemo']['submission_no']!=0){
			$this->Session->setFlash(__('<b>You are not authorized to create/edit the memo.</b>'),'flash.error');
			return $this->redirect(array('controller'=>'User','action'=>'userDashboard'));
			
		}
		$this->set('memo_id',$memo_id);
//#changed may 7 2015
	//select all reviewer under requestor department for filtering reviewer
		//$reviewers = $this->User->find('list',array('conditions'=>array('User.reviewer'=>1,'User.status'=>'enabled','User.user_id NOT'=>$user['user_id'])));
		//$reviewers = $this->User->find('list',array('conditions'=>array('User.reviewer'=>1,'User.status'=>'enabled','User.department_id'=>$user['department_id']))); //last edited May 5,2015
		$reviewers = $this->User->find('list',array('conditions'=>array('User.reviewer'=>1,'User.status'=>'enabled'))); //last edited june 1,2015
		$this->set('reviewers',$reviewers);

//#changed may 7 2015
		//select all recommender under requestor group for filtering recommender
		$this->Department->recursive=-1;
		$depts=$this->Department->find('all',array('conditions'=>array('Department.group_id'=>$memo['Department']['group_id']),'fields'=>array('department_id')));
		$dept=array();
		foreach ($depts as $value) {
			$dept[]=$value['Department']['department_id'];
		}

		//$recommenders = $this->User->find('list',array('conditions'=>array('User.recommender'=>1,'User.status'=>'enabled','User.user_id NOT'=>$user['user_id'])));
		//$recommenders = $this->User->find('list',array('conditions'=>array('User.recommender'=>1,'User.status'=>'enabled','User.department_id'=>$user['department_id'])));// last edited jun 1,2015
		$recommenders = $this->User->find('list',array('conditions'=>array('User.recommender'=>1,'User.status'=>'enabled')));
		$this->set('recommenders',$recommenders);
		// $finances = $this->User->find('list',array('conditions'=>array('User.finance'=>1,'User.status'=>'enabled','User.user_id NOT'=>$user['user_id'])));
		// $this->set('finances',$finances);
		$approvers = $this->User->find('list',array('conditions'=>array('User.approver'=>1,'User.status'=>'enabled','User.user_id NOT'=>$user['user_id'])));
		$this->set('approvers',$approvers);
	}	
#endregion

#region "CRUD"
		

	public function addMemo($memo_id=null,$edit=null){
		
		//phase 2:check if memo is locked
		$setting = $this->Setting->find('first');
		if ($setting['Setting']['nonfinancial_memo']){
			$this->Session->setFlash(__('<b>The access to Non-Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$encMemoID = $memo_id;
		$memo_id = $this->decrypt($memo_id);

		$user = $this->getAuth();
		if (!$this->NfMemo->exists($memo_id)) {

			throw new ForbiddenException();

		}
		//debug($this->request->data);exit;
		if ($this->request->is('post')||$this->request->is('put')){
			
			$this->NfMemo->id=$memo_id;
			//$this->request->data['NfMemo']['user_id']=$user['user_id'];
			//$this->request->data['NfMemo']['submission_no']=0;
			if (($this->request->data['NfMemo']['date_required']!=''))
				$this->request->data['NfMemo']['date_required']=date('Y-m-d',strtotime($this->request->data['NfMemo']['date_required']));

			if ($this->NfMemo->save($this->request->data)){ 
				$memo_id=$this->NfMemo->id;

				//start attachment	
				if(!empty($this->request->data['NfAttachment']['files'])){
					if (!(count($this->request->data['NfAttachment']['files'])==1&&empty($this->request->data['NfAttachment']['files'][0]['tmp_name']))){
						// assume filetype is false
						$typeOK = false;
						// list of permitted file types, 
						$permitted = array('application/msword','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf');

						foreach ($this->request->data['NfAttachment']['files'] as $value) {
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
						                 if(move_uploaded_file($file['tmp_name'], WWW_ROOT.'files'.DS.'nf-attachment'.DS.$filename)){

						                 	$this->NfAttachment->create();
								            $this->request->data['NfAttachment']['filename'] = $filename;
								            $this->request->data['NfAttachment']['memo_id'] = $memo_id;
								            $this->NfAttachment->save($this->request->data);
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
				// enf of attachment
				$this->set('memo_id',$encMemoID);
				$this->set('edit',$edit);	

				if (isset($this->request->data['save'])){
				 	$this->Session->setFlash(__('<b>Memo has been saved.</b>'),'flash.success');
				 	return $this->redirect(array('action'=>'request',$encMemoID,$edit));

				 }
			}
		}
		return $this->redirect(array('action'=>'request',$encMemoID,$edit));
	}

	//< save memo data and validate  staff are filled >
	public function validateMemo($memo_id=null,$edit=null){
		//phase 2:check if memo is locked
		$setting = $this->Setting->find('first');
		if ($setting['Setting']['nonfinancial_memo']){
			$this->Session->setFlash(__('<b>The access to Non-Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$encrypted = $this->encrypt($memo_id);
		$user = $this->getAuth();
		if (!$this->NfMemo->exists($memo_id)) {

			throw new ForbiddenException();

		}
		//debug($this->request->data);exit;
		if ($this->request->is('post')||$this->request->is('put')){
			$this->NfMemo->id=$memo_id;
			if (($this->request->data['NfMemo']['date_required']!=''))
				$this->request->data['NfMemo']['date_required']=date('Y-m-d',strtotime($this->request->data['NfMemo']['date_required']));

			if ($this->NfMemo->save($this->request->data)){
				$memo_id=$this->NfMemo->id;
				$this->set('memo_id',$encrypted);
				$errorMsg='';
				$errorFlag=false;
				//check upload files:
				if(!empty($this->request->data['NfAttachment']['files'])){
					if (!(count($this->request->data['NfAttachment']['files'])==1&&empty($this->request->data['NfAttachment']['files'][0]['tmp_name']))){
						// assume filetype is false
						$typeOK = false;
						// list of permitted file types, 
						$permitted = array('application/msword','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf');

						foreach ($this->request->data['NfAttachment']['files'] as $value) {
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
						                 if(move_uploaded_file($file['tmp_name'], WWW_ROOT.'files'.DS.'nf-attachment'.DS.$filename)){

						                 	$this->NfAttachment->create();
								            $this->request->data['NfAttachment']['filename'] = $filename;
								            $this->request->data['NfAttachment']['memo_id'] = $memo_id;
								            $this->NfAttachment->save($this->request->data);
						                 }
						            }
						            else{

							        	$errorMsg=$errorMsg.'<b>'.basename($file['name']).' cannot be uploaded. Please try again.</b><br>';
						            	$errorFlag=true;
							        }
						        }

						        else{

							        	$errorMsg=$errorMsg.'<b>'.basename($file['name']).' is too big. Maximum file size is 10Mb.</b><br>';
						            	$errorFlag=true;
							        }
					        }

					        else{

					        	$errorMsg=$errorMsg.'<b>'.basename($file['name']).' cannot be uploaded. Acceptable file types: pdf, word, excel.</b><br>';
						        $errorFlag=true;
					        }
			        	}
			        }
				}

				//check if at least one staff to send memo to has been added
				$to=$this->NfMemoTo->find('all',array('conditions'=>array('NfMemoTo.memo_id'=>$memo_id)));
				//check for new vendor has to attach min 3 quotations
				// $attachmentCount=$this->FVendorAttachment->find('count',array('conditions'=>array('FVendorAttachment.memo_id'=>$memo_id)));
				
				if (empty($to)){
					$errorMsg=$errorMsg.'<b>Please select at least one staff to send memo to.</b><br>';
					$errorFlag=true;
					// $this->Session->setFlash(__('<b>Please select at least one staff to send memo to.</b>'),'flash.error');
					// return $this->redirect(array('action'=>'request',$encrypted,$edit));
				}

	   //      	elseif (($this->request->data['NfMemo']['vendor']=='0')&&($attachmentCount<3)){

				// 	$this->Session->setFlash(__('<b>Please attach at least three (3) quotations for new vendor.</b>'),'flash.error');
				// 	return $this->redirect(array('action'=>'request',$memo_id,$edit));
				// }
				if (empty($this->request->data['NfMemo']['subject'])){
					$errorMsg=$errorMsg.'<b>Please fill in all required fields.</b><br>';
					$errorFlag=true;
					// $this->Session->setFlash(__('<b>Please select at least one staff to send memo to.</b>'),'flash.error');
					// return $this->redirect(array('action'=>'request',$encMemoID,$edit));
				}
				if ($errorFlag){
					$this->Session->setFlash(__($errorMsg),'flash.error');
					return $this->redirect(array('action'=>'request',$encrypted,$edit));
				}
				else{
					//debug($edit);exit;
					if ($edit){
		//#lines edited according to the changes in financial controlleer.cikin.my.may 7 2015.715am
						//check the relationship of user and memo 
						$this->NfMemo->contain('NfReviewer','Department');
						$memo=$this->NfMemo->findByMemoId($memo_id); //debug($memo); exit();

						$this->NfStatus->contain('NfReviewer');
						$status=$this->NfStatus->find('all',array('conditions'=>array('NfStatus.memo_id'=>$memo_id,'NfStatus.submission_no'=>$memo['NfMemo']['submission_no']),'order'=>array('NfReviewer.sequence'=> 'ASC')));
						$resubmitFlag=false;
                        //user is the creator of the memo, thus update submission no and reinsert status for another round of approval
						if($user['user_id']==$memo['NfMemo']['user_id']){

							foreach ($status as $value) {								
									if ($value['NfStatus']['status']=='rejected'){
										$resubmitFlag=true;
										break;
									}
								}
							}
						if($resubmitFlag){//user is the creator of the memo & there is rejected status which is not from approver, thus update submission no and reinsert status for another round of approval
							$this->NfMemo->id=$memo_id;
							$submission_no=$memo['NfMemo']['submission_no']+1;
							$this->NfMemo->saveField('submission_no',$submission_no);

							
							foreach ($memo['NfReviewer'] as $value) {
								
								//$id = $value['reviewer_id'];
								$status['NfStatus']['reviewer_id'] = $value['reviewer_id'];
								$status['NfStatus']['status'] = 'pending';
								$status['NfStatus']['submission_no'] = $submission_no;
								$status['NfStatus']['memo_id'] = $memo_id;
								$this->NfStatus->create();
								$this->NfStatus->save($status);
					
							}


							// send review email if resubmit --> does not go to confirmBudget
							$this->sendReviewEmail($memo_id);

							// add notification to the requestor -- stating the memo has been created
							$notiTo = $memo['NfMemo']['user_id'];
							// $notiText = "You have re-submitted a financial memo request";
							$notiText = "<b> Ref. No : </b> ".$memo['NfMemo']['ref_no']. "<br>".
										"<b> Subject : </b> ".$memo['NfMemo']['subject']."<br>".
										"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
										"<b> Info : </b> Memo resubmitted";

							$notiLink = array('controller'=>'NfMemo2','action'=>'dashboard',$encrypted);
							
							#code update for ememo2
							$notiType = "memo"; 
							$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
					        #end code update

							$this->Session->setFlash(__('<b>Memo has been re-submitted for review.</b>'),'flash.success');

							return $this->redirect(array('controller'=>'NfMemo2','action'=>'dashboard',$encrypted));
						}

						else{//user is one of the reviewer/approver/finance/recommender

							$this->Session->setFlash(__('<b>Memo has been updated successfully.</b>'),'flash.success');

							return $this->redirect(array('controller'=>'NfMemo2','action'=>'dashboard',$encrypted));

						}
				  	}

					else{//first submission so go to add reviewer page

						return $this->redirect(array('action'=>'confirm',$encrypted));
					}
					
				}
			}

		}
	}	

	public function addStaff($memo_id=null,$edit=null){		
		//phase 2:check if memo is locked
		$setting = $this->Setting->find('first');
		if ($setting['Setting']['nonfinancial_memo']){
			$this->Session->setFlash(__('<b>The access to Non-Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$user = $this->getAuth();
		$encrypted = $memo_id;
		$memo_id = $this->decrypt($memo_id);


		if (!$this->NfMemo->exists($memo_id)) {

			$this->Session->setFlash(__('<b>Invalid id.</b>'),'flash.error');

		}
		if ($this->request->is('post')||$this->request->is('put')){
		
				$to=$this->NfMemoTo->find('all',array('conditions'=>array('NfMemoTo.memo_id'=>$memo_id)));
					
				//delete first all staff before re-add to ensure no redundancy
				if (!empty($to)){

					foreach ($to as $value) {
						$this->NfMemoTo->delete($value['NfMemoTo']['to_id']);
					}
				}
				//save selected staff 
				if(!empty($this->request->data['NfMemoTo']['selectedStaff'])){
					
					foreach ($this->request->data['NfMemoTo']['selectedStaff'] as $value) {
						$this->request->data['NfMemoTo']['user_id']=$value;
						$this->request->data['NfMemoTo']['memo_id']=$memo_id;
						$this->NfMemoTo->create();
						$this->NfMemoTo->save($this->request->data);

					}

				}

				$this->Session->setFlash('<b>Successfully add/remove staff(s).</b>','flash.success');

		}

		return $this->redirect(array('action'=>'request',$encrypted,$edit));
	}

	/*
	*	deleteMemo
	*	< delete  nonfinancial memo  >
	*	@ Aisyah
	*/
	public function deleteMemo($memo_id=null){

		$setting = $this->Setting->find('first');
		//phase 2:check if memo is locked
		if ($setting['Setting']['nonfinancial_memo']){
			$this->Session->setFlash(__('<b>The access to Non-Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$memo_id=$this->decrypt($memo_id);
		$memo = $this->NfMemo->findByMemoId($memo_id); //debug($memo); exit;
		
		if (!$this->NfMemo->exists($memo_id)) {

			$this->Session->setFlash(__('<b>Invalid memo.</b>'),'flash.error');

		}

		// debug($this->request->params); exit();
		#ememo2 :: if memo submitted,  send email first before delete 
		if($memo['NfMemo']['submission_no']>0){			
 			$this->sendDeleteEmail($memo_id);
		}

		if($this->NfMemo->delete($memo_id)){
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
		if ($setting['Setting']['nonfinancial_memo']){
			$this->Session->setFlash(__('<b>The access to Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		if (!empty($this->request->data['memoid'])):
			foreach ($this->request->data['memoid'] as $id) {
				$memo_id=$this->decrypt($id);
				$memo = $this->NfMemo->findByMemoId($memo_id); //debug($memo); exit;
				#ememo2 :: if memo submitted,  send email first before delete 
				if($memo['NfMemo']['submission_no']>0){			
		 			$this->sendDeleteEmail($memo_id);
				}

				if($this->NfMemo->delete($memo_id)){
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



	public function confirmMemo(){

		//phase 2:check if memo is locked
		$setting = $this->Setting->find('first');
		if ($setting['Setting']['nonfinancial_memo']){
			$this->Session->setFlash(__('<b>The access to Non-Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$user = $this->getAuth(); // debug($this->request->data); exit();
		if ($this->request->is('post')||$this->request->is('put')){

			$memo_id = $this->request->data['NfMemo']['memo_id'];
			$this->NfMemo->id = $memo_id;
			$encMemoID=$this->encrypt($memo_id); //#added may 7 2015
			$this->NfMemo->saveField('remark',$this->request->data['NfMemo']['remark']);
			
			// $this->NfMemo->recursive=-1;
			$this->NfMemo->contain(array('Department'));
			$memo=$this->NfMemo->findByMemoId($memo_id);

			if ($user['user_id']!=$memo['NfMemo']['user_id']||$memo['NfMemo']['submission_no']!=0){
				
				$this->Session->setFlash(__('<b>You are not authorized to create/edit the memo.</b>'),'flash.error');
				return $this->redirect(array('controller'=>'User','action'=>'userDashboard'));
			}
			
			if (!empty($this->request->data['NfReviewer']['reviewer'])&&!empty($this->request->data['NfReviewer']['approver'])){

					// save the reviewer
				$seq=1;
				foreach($this->request->data['NfReviewer']['reviewer'] as $rev):
					$reviewer['NfReviewer']['memo_id'] = $memo_id;
					$reviewer['NfReviewer']['user_id'] = $rev;
					$reviewer['NfReviewer']['sequence'] = $seq++;
					$reviewer['NfReviewer']['approval_type'] = 'reviewer';
					$this->NfReviewer->create();
					if($this->NfReviewer->save($reviewer)){
						$reviewerid = $this->NfReviewer->id;
						$status['NfStatus']['reviewer_id'] = $reviewerid;
						$status['NfStatus']['status'] = 'pending';
						$status['NfStatus']['submission_no'] = 1;
						$status['NfStatus']['memo_id'] = $memo_id;
						$this->NfStatus->create();
						$this->NfStatus->save($status);
					}
				endforeach;
				
				if (!empty($this->request->data['NfReviewer']['recommender'])){
					// save the recommender
					foreach($this->request->data['NfReviewer']['recommender'] as $rec):
						$recommender['NfReviewer']['memo_id'] = $memo_id;
						$recommender['NfReviewer']['user_id'] = $rec;
						$recommender['NfReviewer']['sequence'] = $seq++;
						$recommender['NfReviewer']['approval_type'] = 'recommender';
						$this->NfReviewer->create();
						if($this->NfReviewer->save($recommender)){
							$recommenderid = $this->NfReviewer->id;
							$status['NfStatus']['reviewer_id'] = $recommenderid;
							$status['NfStatus']['status'] = 'pending';
							$status['NfStatus']['submission_no'] = 1;
							$status['NfStatus']['memo_id'] = $memo_id;
							$this->NfStatus->create();
							$this->NfStatus->save($status);
						}
					endforeach;
				}

								
				// save the approver
				foreach($this->request->data['NfReviewer']['approver'] as $app):
					$approver['NfReviewer']['memo_id'] = $memo_id;
					$approver['NfReviewer']['user_id'] = $app;
					$approver['NfReviewer']['sequence'] = $seq++;
					$approver['NfReviewer']['approval_type'] = 'approver';
					$this->NfReviewer->create();
					if($this->NfReviewer->save($approver)){
						$approverid = $this->NfReviewer->id;
						$status['NfStatus']['reviewer_id'] = $approverid;
						$status['NfStatus']['status'] = 'pending';
						$status['NfStatus']['submission_no'] = 1;
						$status['NfStatus']['memo_id'] = $memo_id;
						$this->NfStatus->create();
						$this->NfStatus->save($status);
					}
				endforeach;

								
				//if everything is okay then only generate ref no to indicate form is completed				
				$paddedId=str_pad($memo_id,10,"0",STR_PAD_LEFT);
				$ref_no=date('m/Y',strtotime($memo['NfMemo']['created'])).'/'.$paddedId;
				$this->NfMemo->saveField('ref_no',$ref_no);
				$this->NfMemo->saveField('submission_no',1);
				$this->NfMemo->saveField('created',date('Y-m-d h:i:s'));

				#send email to reviewer
				$this->sendReviewEmail($memo_id);

				// add notification to the requestor -- stating the memo has been created/resubmit
				$notiTo = $memo['NfMemo']['user_id'];
				// $notiText = "You have submitted a non-financial memo request";
				$notiText = "<b> Ref. No : </b> ".$ref_no. "<br>".
							"<b> Subject : </b> ".$memo['NfMemo']['subject']."<br>".
							"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
							"<b> Info : </b> Memo submitted";

				//$encMemoID = $this->encrypt($memo_id);
				$notiLink = array('controller'=>'NfMemo2','action'=>'dashboard',$encMemoID);
				//$this->UserNotification->record($notiTo, $notiText, $notiLink);

				#code update for ememo2
				$notiType = "memo"; 
				$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
		        #end code update

				//send email to all reviewers except the first one
				$this->NfReviewer->recursive = -1;
				$reviewers = $this->NfReviewer->find('all',array('conditions'=>array(
														'NfReviewer.memo_id'=>$memo_id, //only for this 
														'NfReviewer.sequence != 1',
														// 'Budget.submission_no'=>1, //only for first submission
													)));

				$this->NfMemo->contain(array('User','Department'));
				$memo = $this->NfMemo->findByMemoId($memo_id);
				$rev=array();
				foreach($reviewers as $r):
					$rev[$r['NfReviewer']['user_id']]=$r['NfReviewer']['user_id'];
				endforeach;

				foreach($rev as $uid):
					// add notification to the other reviewers 
					$notiTo = $uid;
					// $notiText = "Your budget request has been approved";
					$notiText = "<b> Ref. No : </b> ".$ref_no. "<br>".
							"<b> Subject : </b> ".$memo['NfMemo']['subject']."<br>".
							"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
							"<b> Info : </b> Memo added";

					$notiLink = array('controller'=>'NfMemo2','action'=>'dashboard',$encMemoID);
					//$this->UserNotification->record($notiTo, $notiText, $notiLink);

					#code update for ememo2
					$notiType = "memo"; 
					$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
			        #end code update
					//send email to other -- requested on 3/7/2015 and cancelled on 14/7/2015 --not going to do it again
                    // $toReviewer= $uid;
                    // $link = $this->goLink($uid,array('controller'=>'NfMemo2','action'=>'dashboard',$encMemoID));

                    // $email = "This is to inform you that a new memo has been created with the following parameters.<br>";
                    // $email .= $this->requestorMemoTable($memo_id,$memo);
                    // $email .= "You have been added to the memo as a Reviewer/ Recommender/ Finance/ Approver. <br> You will be notified via email when your further action on this memo is required. <br> Thank You";
                    // $email .= "<a href='{$link}' class='btn btn-success'> Go to memo dashboard </a>";
                    // $subject = $memo['NfMemo']['subject']." (Memo created)";

                    // $this->emailMe($toReviewer,$subject,$email);

				endforeach;

				$this->Session->setFlash('<b>The memo has been submitted for review.</b> <br><small> You will be notified if the status changed later </small>','flash.success');
				$this->redirect(array('controller'=>'NfMemo2','action'=>'dashboard',$encMemoID));

			}

			else {
				$this->Session->setFlash('<b>You must select at least one(1) reviewer/approver</b>','flash.error');
				//$this->redirect(array('controller'=>'NfMemo2','action'=>'confirm',$this->encrypt($memo_id)));
				return $this->redirect(array('controller'=>'NfMemo2','action'=>'confirm',$encMemoID));
			}
			
		}
		//return $this->redirect(array('action'=>'confirmMemo',$encrypted,$edit));
	}

	public function approveRejectMemo(){
		
		//phase 2:check if memo is locked
		$setting = $this->Setting->find('first');
		if ($setting['Setting']['nonfinancial_memo']){
			$this->Session->setFlash(__('<b>The access to Non-Finacial Memo is currently disabled.</b><br><small>Please contact administrator for further information</small> '),'flash.error');
			return $this->redirect($this->referer());
		}

		$user = $this->getAuth();
		$user_id = $user['user_id'];
		if ($this->request->is('post')||$this->request->is('put')){
			//if (($this->request->data['NfStatus']['status']!='')){
			if (empty($this->request->data['NfStatus']['status'])){//edited may 7 2015
				$this->Session->setFlash('<b>Please select Approve or Reject before proceeding.</b>','flash.error');
				return $this->redirect($this->referer());
			}

			$encrypted=$this->request->data['NfStatus']['memo_id'];
			$this->request->data['NfStatus']['memo_id'] = $this->decrypt($this->request->data['NfStatus']['memo_id']);
			
			$memo_id = $this->request->data['NfStatus']['memo_id'];
			// debug($this->request->data);exit();
			$this->NfStatus->contain(array('NfReviewer','NfMemo'));
			//$NfStatus = $this->NfStatus->find('first',array('conditions'=>array('NfReviewer.memo_id'=>$memo_id),'order'=>array('NfStatus.submission_no DESC')));
			$NfStatus = $this->NfStatus->find('first',array('conditions'=>array('NfStatus.status'=>'pending','NfReviewer.user_id'=>$user_id,'NfReviewer.memo_id'=>$memo_id),'order'=>array('NfStatus.submission_no DESC','NfReviewer.sequence ASC')));
			// $NfStatus = $this->NfStatus->find('first',array('conditions'=>array('NfReviewer.budget_id'=>$budgetid)));
			// debug($NfStatus);exit();
			if(empty($NfStatus)){
				$this->Session->setFlash("<b>You don't have the privilege to approve/reject the memo</b> <br><small> Please consult administrator </small>",'flash.error');
				return $this->redirect($this->referer());
			}

			if ($NfStatus['NfStatus']['submitted']!=0){				
				$this->Session->setFlash(__('<b>You have already approved/rejected the memo.</b>'),'flash.error');
				return $this->redirect(array('controller'=>'User','action'=>'userDashboard'));
			}

			$statusid = $NfStatus['NfStatus']['status_id'];
			$submissionNo = $NfStatus['NfStatus']['submission_no'];
			$approval_type=$NfStatus['NfReviewer']['approval_type'];
			$remark = $this->request->data['NfStatus']['remark'];
			$status = $this->request->data['NfStatus']['status'];
			$this->request->data['NfStatus']['submitted']=1;
			
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
			#
				// 	$this->NfStatus->id = $statusid;
				// 	$data['NfStatus']['remark'] = $remark;
				// 	$data['NfStatus']['status'] = $status;
				// 	if( $this->NfStatus->save($data) ){
				// 			#only send email after status changed
				// 			if($status == 'approved'){
				// 				$this->sendReviewEmail($memo_id);
				// 			}
				// 			elseif($status == 'rejected'){
				// 				$this->sendRejectedEmail($memo_id);
				// 			}

				// 			$this->Session->setFlash("<b>You have ".$status." the memo </b><br><small> Thank You </small>",'flash.success');
				// 			$this->redirect(array('controller'=>'NfMemo2','action'=>'dashboard',$encrypted));
				// 	}
				// }
				// else{
				// 	$this->Session->setFlash('<b>Please select Approve or Reject before proceeding.</b>','flash.error');
				// 	return $this->redirect($this->referer());
				// }
				// }
			//#lines edited according to the chabges in finance controller. may 7 2015
			$this->NfStatus->id = $statusid;
			if( $this->NfStatus->save($this->request->data)){
				/*for financial only */
				//if ($approval_type=='approver'&&$status == 'rejected'){
					// //send email to finance saying that he has to review again, then update approver reject column in fmemo
					// $this->NfMemo->id = $memo_id;
					// //$this->FMemo->saveField('approver_reject',1);
					// //resubmit memo to finance for review
					// $submission_no=$NfStatus['NfMemo']['submission_no']+1;
					// $this->NfMemo->saveField('submission_no',$submission_no);
					// //insert new status 
					// $this->NfReviewer->contain(array('NfStatus'=>array('conditions'=>array('NfStatus.submission_no'=>$submission_no-1))));
					// $reviewers = $this->NfReviewer->find('all',array('conditions'=>array('NfReviewer.memo_id'=>$memo_id),'order'=>array('NfReviewer.sequence ASC')));
					// //debug($reviewers);exit;
					// foreach ($reviewers as $value) {
						
					// 	//$id = $value['reviewer_id'];
					// 	if($value['NfReviewer']['approval_type']!='approver'){
					// 		$data['NfStatus']['status'] = $value['NfStatus'][0]['status'];
					// 		$data['NfStatus']['remark'] = $value['NfStatus'][0]['remark'];
					// 	}
					// 	else
					// 		$data['NfStatus']['status'] = 'pending';

					// 	$data['NfStatus']['reviewer_id'] = $value['NfReviewer']['reviewer_id'];
					// 	$data['NfStatus']['submission_no'] = $submission_no;
					// 	$data['NfStatus']['memo_id'] = $memo_id;
					// 	$this->NfStatus->create();
					// 	$this->NfStatus->save($data);
			
					// }
					// //$this->sendApproverRejectedEmail($memo_id); for financial only
					// $this->sendRejectedEmail($memo_id);
					// $this->Session->setFlash("<b>You have ".$status." the memo. </b><br><small> The memo has been resubmitted to finance for another round of review. </small>",'flash.success');
				
				//}
				/*for financial only */
				//else{
					// only send email after status changed
					if($status == 'approved'){
						$this->sendReviewEmail($memo_id);
					}
					elseif($status == 'rejected'){
						$this->sendRejectedEmail($memo_id);
					}
					$this->Session->setFlash("<b>You have ".$status." the memo. </b><br><small> Thank You </small>",'flash.success');

				//}

				$this->redirect(array('controller'=>'NfMemo2','action'=>'dashboard',$encrypted));

			}

		}
	}


	/*

	*	downloadAttachment()

	*	< download attachment in non fin memo >

	*	@ cikin.my

	*/

	public function downloadAttachment($attachment_id=null){
		$attachment_id=$this->decrypt($attachment_id);
		if (!$this->NfAttachment->exists($attachment_id)) {

			$this->Session->setFlash(__('<b>Invalid attachment.</b>'),'flash.error');

		}

		$this->NfAttachment->recursive=-1;	
	    $attachment=$this->NfAttachment->find('first',array('conditions'=>array('NfAttachment.attachment_id '=>$attachment_id)));
	    $tmpName=explode('___',$attachment['NfAttachment']['filename']);
	    if (count($tmpName)>1)
	    	$filename=$tmpName[1];
	    else
	    	$filename=$attachment['NfAttachment']['filename'];

	   	$this->response->file(WWW_ROOT.'files'.DS.'nf-attachment'.DS.$attachment['NfAttachment']['filename'], array('download' => true, 'name' => $filename));
	    // Return response object to prevent controller from trying to render
	    // a view

	    //record activity
		//$this->Activity->record('downloaded an attachment from a conversation',0);

	    return $this->response;

	}

	/*
	*	deleteAttachment
	*	< delete  attachment from non financial memo form >
	*	@ cikin.my
	*/
	public function deleteAttachment($attachment_id=null){
		$attachment_id=$this->decrypt($attachment_id);

		if (!$this->NfAttachment->exists($attachment_id)) {

			$this->Session->setFlash(__('<b>Invalid attachment.</b>'),'flash.error');

		}

		$attachment = $this->NfAttachment->find('first',array('conditions'=>array('NfAttachment.attachment_id'=>$attachment_id)));
		$filepath = WWW_ROOT.'files'.DS."nf-attachment".DS.$attachment['NfAttachment']['filename'];
		unlink($filepath);
		if($this->NfAttachment->delete($attachment_id))
			$this->Session->setFlash(__('<b>Attachment deleted successfully.</b>'),'flash.success');
		else
			$this->Session->setFlash(__('<b>Failed to delete the attachment. Please try again.</b>'),'flash.error');


			$this->redirect($this->referer());	
	}
#endregion

#region "Supporting Functions"
	private function getReviewer($memo_id=null,$reviewer_type=null,$submission_no=null){

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
				
			)

		));

		return($reviewer_query);

	}

	private function getRemark($memo_id=null,$reviewer_uid=null){

		$remark_query=$this->NfRemark->find('all',array(
			'conditions' => array(
		
				'NfRemark.memo_id' => $memo_id,
				'NfRemark.reviewer_id'=>$reviewer_uid,
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

	public function pdf($memo_id=null){
		$user = $this->getAuth();
		$encrypted = $memo_id;
		$memo_id=$this->decrypt($memo_id);

		if (!$this->NfMemo->exists($memo_id)) {

			throw new ForbiddenException();

		}
		$userid = $user['user_id'];
		$memo = $this->NfMemo->find('first',array(
			'conditions' => array('NfMemo.memo_id' => $memo_id),
			'contain'=>array(
				'User'=>array('fields'=>array('staff_name','designation'),'Department'=>array('fields'=>array('department_name'))
				),
				'NfReviewer',
				'NfAttachment',
				'NfMemoTo'=>array('User'=>array('fields'=>array('User.staff_name','User.designation'))),
				//'NfMemoBudget',
				//'NfMemoBudget.BItem'

			)

		));
		$submission_no=$memo['NfMemo']['submission_no'];
		
 		$this->set('memo',$memo);
 		
 		$reviewer=$this->getReviewer($memo_id,'reviewer',$submission_no);
 		$recommender=$this->getReviewer($memo_id,'recommender',$submission_no);
 		// $finance=$this->getReviewer($memo_id,'finance',$submission_no);
 		$approver=$this->getReviewer($memo_id,'approver',$submission_no);
		
		$remark_reviewer=array();
		$remark_recommender=array();
		// $remark_finance=array();
		$remark_approver=array();
		
		if (!empty($reviewer)){
			foreach ($reviewer as $value) {
				
				$remark_reviewer[]=$this->getRemark($memo_id,$value['NfReviewer']['reviewer_id']);
			}
			
		}

		if (!empty($recommender)){
			foreach ($recommender as $value) {
				
				$remark_recommender[]=$this->getRemark($memo_id,$value['NfReviewer']['reviewer_id']);
			}
			
		}

		// if (!empty($finance)){
		// 	foreach ($finance as $value) {
				
		// 		$remark_finance[]=$this->getRemark($memo_id,$value['NfReviewer']['user_id']);
		// 	}
			
		// }

		if (!empty($approver)){
			foreach ($approver as $value) {
				
				$remark_approver[]=$this->getRemark($memo_id,$value['NfReviewer']['reviewer_id']);
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

 		$subject=$memo['NfMemo']['subject'];
 		$department=$memo['User']['Department']['department_name'];
 		$date=date('d.m.Y', strtotime($memo['NfMemo']['created'])) ;
 		$ref_no=$memo['NfMemo']['ref_no'];


 		$this->layout = 'mems-pdf-memo';
		$this->pdfConfig = array(
		'orientation'=>'portrait',
		// 'filename'=>'UNITARMemo'.'_'.$subject.'_'.$department.'_'.$date.'.pdf',
		# ememo changes requested on 27 june 2016 : e-memo reference number + e-memo title + date.
		'filename'=>$ref_no.'_'.$subject.'_'.$date.'.pdf',
		'download'=>true,
		);
		$this->render('pdf');

	}
	


#endregion	

#region "Generate Email funtion"

	public function sendReviewEmail($memo_id){
		$this->layout = 'mems-email';
		// get current status
		$this->NfStatus->contain(array(
								'NfReviewer',
								'NfReviewer.User',
								'NfMemo',
								'NfMemo.User'=>array('fields'=>array('staff_name','email_address')),
								'NfMemo.Department'));
		$status = $this->NfStatus->find('first',array('conditions'=>array('NfStatus.memo_id'=>$memo_id,'NfStatus.status'=>'pending'),'order'=>array('NfReviewer.sequence ASC')));
		// debug($status);exit();

		$this->NfMemo->contain(array('User','Department'));
		$memo = $this->NfMemo->findByMemoId($memo_id);
		$encMemoID = $this->encrypt($memo_id);

		// if status empty --> means all is approved?
		if(empty($status)){ // send to requestor
			
			// update memo_status in memo table cikin.my 9 3 2016
			$this->NfMemo->id  = $memo_id;
			$this->NfMemo->saveField('memo_status','approved');	 

			$toRequestor= $memo['NfMemo']['user_id'];

			// add notification to the requestor -- stating the budget has been fully approved
			$notiTo = $memo['NfMemo']['user_id'];
			// $notiText = "Your non-financial memo request has been approved";
			$notiText = "<b> Ref. No : </b> ".$memo['NfMemo']['ref_no']. "<br>".
							"<b> Subject : </b> ".$memo['NfMemo']['subject']."<br>".
							"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
							"<b> Info : </b> Memo approved";

			//$encMemoID = $this->encrypt($memo_id);
			$notiLink = array('controller'=>'NfMemo2','action'=>'dashboard',$encMemoID);
			//$this->UserNotification->record($notiTo, $notiText, $notiLink);

			#code update for ememo2
			$notiType = "memo"; 
			$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
	        #end code update

			// generate link
			//$encMemoID = $this->encrypt($memo_id);
			$link = $this->goLink($memo['NfMemo']['user_id'],array('controller'=>'NfMemo2','action'=>'dashboard',$encMemoID));

			$email = "Your Memo request has been approved.<br>";
			$email .= $this->requestorMemoTable($memo_id,$memo);
			$email .= "You may go to the memo dashboard by clicking the button below <br>";

			
			// debug($link);exit();
			$email .= "<a href='{$link}' class='btn btn-success'> Go to memo dashboard </a>";

			$toRequestor = $memo['NfMemo']['user_id'];
			$subject = $memo['NfMemo']['subject']." (Memo request approved)";

			$this->emailMe($toRequestor,$subject,$email);

		}
		else{ // send to reviewer

			// add notification to the requestor -- stating the memo has been reviewed -- only when status is not 1 --coz 1 send review email -- not been reviewed
			if($status['NfReviewer']['sequence'] != 1){
				$notiTo = $memo['NfMemo']['user_id'];
				// $notiText = "Your non-financial memo request has been reviewed";
				$notiText = "<b> Ref. No : </b> ".$memo['NfMemo']['ref_no']. "<br>".
							"<b> Subject : </b> ".$memo['NfMemo']['subject']."<br>".
							"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
							"<b> Info : </b> Memo reviewed";

				$notiLink = array('controller'=>'NfMemo2','action'=>'dashboard',$encMemoID);
				//$this->UserNotification->record($notiTo, $notiText, $notiLink);

				#code update for ememo2
				$notiType = "memo"; 
				$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
		        #end code update

				// also send email to requester each time reviewer approve -- requested by unitar on 8/7/2015
				$approvedBy = $this->getAuth();
				$link = $this->goLink($memo['NfMemo']['user_id'],array('controller'=>'NfMemo2','action'=>'dashboard',$encMemoID));

				$email = "Your memo request has been approved by ".$approvedBy['staff_name'] .".<br>";
				$email .= $this->requestorMemoTable($memo_id,$memo);
				$email .= "You may go to the dashboard page by clicking the button below <br>";

				$email .= "<a href='{$link}' class='btn btn-success'> Memo Dashboard </a>";

				$toRequestor = $status['NfMemo']['user_id'];
				$subject = $status['NfMemo']['subject']." (Memo reviewed)";

				$this->emailMe($toRequestor,$subject,$email);
			}
			
			// add notification to the reviewer -- stating the memo need to be reviewed
			$notiTo = $status['NfReviewer']['user_id'];
			// $notiText = "Please review the non-financial memo request";
			$notiText = "<b> Ref. No : </b> ".$memo['NfMemo']['ref_no']. "<br>".
							"<b> Subject : </b> ".$memo['NfMemo']['subject']."<br>".
							"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
							"<b> Pending : </b> Review of memo";

			$notiLink = array('controller'=>'NfMemo2','action'=>'review',$encMemoID);
			//$this->UserNotification->record($notiTo, $notiText, $notiLink);

			#code update for ememo2
			$notiType = "memo"; 
			$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
	        #end code update

			// generate link
			$link = $this->goLink($status['NfReviewer']['user_id'],array('controller'=>'NfMemo2','action'=>'review',$encMemoID));

			$email = "You are required to review the following memo request.<br>";
			$email .= $this->requestorMemoTable($memo_id,$memo);
			$email .= "You may go to the review page by clicking the button below <br>";

			
			// debug($link);exit();
			$email .= "<a href='{$link}' class='btn btn-success'> Review the memo request </a>";

			$toReviewer = $status['NfReviewer']['user_id'];
			$subject = $memo['NfMemo']['subject']." (Please review the memo request)";

			$this->emailMe($toReviewer,$subject,$email);
		}

		
		// $this->set('email',$email);
		// $this->render('email');
		// debug($status);exit();
	}

	public function sendRejectedEmail($memo_id){
		$this->NfMemo->contain(array('User','Department'));
		$memo = $this->NfMemo->findByMemoId($memo_id);
		$toRequestor= $memo['NfMemo']['user_id'];
		//generate link
		$encMemoID = $this->encrypt($memo_id);
		$link = $this->goLink($memo['NfMemo']['user_id'],array('controller'=>'NfMemo2','action'=>'dashboard',$encMemoID));

		$email = "Your memo request has been rejected.<br>";
		$email = "Please review it again and resubmit your memo request.<br>";
		$email .= $this->rejectedMemoTable($memo_id,$memo);
		$email .= "You may go to the memo request page by clicking the button below <br>";

		
		// debug($link);exit();
		$email .= "<a href='{$link}' class='btn btn-success'> Go to memo request </a>";

		$toRequestor = $memo['NfMemo']['user_id'];
		$subject = $memo['NfMemo']['subject']." (Memo request rejected)";

		$this->emailMe($toRequestor,$subject,$email);

		// add notification to the requestor -- stating the memo has been rejected
		$notiTo = $memo['NfMemo']['user_id'];
		// $notiText = "Your non-financial memo request has been rejected";
		$notiText = "<b> Ref. No : </b> ".$memo['NfMemo']['ref_no']. "<br>".
					"<b> Subject : </b> ".$memo['NfMemo']['subject']."<br>".
					"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
					"<b> Info : </b> Memo rejected";

		//$encMemoID = $this->encrypt($memo_id);
		$notiLink = array('controller'=>'NfMemo2','dashboard',$encMemoID);
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
							<td>".date('d M Y',strtotime($memoData['NfMemo']['created'])). " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Subject </td>
							<td>".$memoData['NfMemo']['subject']. " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Reference No. </td>
							<td>".$memoData['NfMemo']['ref_no']. " </td>
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
							<td>".$memoData['NfMemo']['submission_no']. " </td>
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

	private function rejectedMemoTable($memo_id,$memoData = array()){
		// $budgetAmount = $this->BNewAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BNewAmount.budget_id'=>$budgetid)));
		// $totalBudget = $budgetAmount[0]['totalBudget'];

		$this->NfStatus->contain(array('NfReviewer','NfReviewer.User'));
		$rejectedStatus = $this->NfStatus->findByMemoIdAndStatusAndSubmissionNo($memo_id,'rejected',$memoData['NfMemo']['submission_no']);
		// debug($rejectedStatus);exit();

		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Memo Created </td>
							<td>".date('d M Y',strtotime($memoData['NfMemo']['created'])). " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Subject </td>
							<td>".$memoData['NfMemo']['subject']. " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Reference No. </td>
							<td>".$memoData['NfMemo']['ref_no']. " </td>
						</tr>						
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Current Submission No. </td>
							<td>".$memoData['NfMemo']['submission_no']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Rejected By </td>
							<td>".$rejectedStatus['NfReviewer']['User']['staff_name']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Remark </td>
							<td>".$rejectedStatus['NfStatus']['remark']. " </td>
						</tr>
					</table>";

		return $htmlTable;
	}

	#ememo2 :: email to notify memo is deleted.
	public function sendDeleteEmail($memo_id){
		$this->layout = 'mems-email';
		$this->NfMemo->contain(array('User','Department','NfReviewer'=>array('User')));
		$memo = $this->NfMemo->findByMemoId($memo_id);			
		
	    #1.notify all reviewers on deleted memo
		if(!empty($memo)){
			foreach ($memo['NfReviewer'] as $rev) {
			

				#EMAIL
				$email = "Please be informed that this memo has no longer exist. It has been removed from the system.<br>";
				$email .= $this->requestorMemoTable($memo_id,$memo);
				$email .= "For any enquiry, please contact your Administrator. Thank you. <br>";			
			
				$toReviewer = $rev['user_id'];
				$subject = $memo['NfMemo']['subject']." (Deletion of memo from the system)";	

				$this->emailMe($toReviewer,$subject,$email);

				#NOTIFICATION
				$notiTo = $toReviewer;			
				$notiText = "<b> Ref. No : </b> ".$memo['NfMemo']['ref_no']. "<br>".
							"<b> Subject : </b> ".$memo['NfMemo']['subject']."<br>".
							"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
							"<b> Info : </b> Memo removed";
				$notiLink = array('controller'=>'NfMemo2','action'=>'myReview');
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
		
			$toRequestor = $memo['NfMemo']['user_id'];
			$subject = $memo['NfMemo']['subject']." (Deletion of memo from the system)";	

			$this->emailMe($toRequestor,$subject,$email);

			#NOTIFICATION
			$notiTo = $toRequestor;			
			$notiText = "<b> Ref. No : </b> ".$memo['NfMemo']['ref_no']. "<br>".
						"<b> Subject : </b> ".$memo['NfMemo']['subject']."<br>".
						"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
						"<b> Info : </b> Memo removed";
			$notiLink = array('controller'=>'NfMemo2','action'=>'index');
			//$this->UserNotification->record($notiTo, $notiText, $notiLink);


			#code update for ememo2
			$notiType = "memo"; 
			$this->UserNotification->record($notiTo, $notiText, $notiLink, $notiType); 
		    #end code update

		}
		
	}
	
#endregion





}
