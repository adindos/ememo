<?php

/*

*	=========================================================================

*	UNITAR MeMs 2015 

*	@vendor GR Tech Sdn Bhd

*	@team Syikin,Aisyah,Nizam

*	=========================================================================

*	

*	[ File ]

*		RemarkController.php

*			- Controller to control the action for Remark

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



class NfRemarkController extends AppController {

	public $uses = array('NfMemo','NfRemark','NfRemarkAssign','NfRemarkFeedback','NfReviewer','NfStatus','NfActivities');

	public $layout = 'mems';


	/*

	*	index()

	*	< description of function >

	*	@ < author of function editor >

	*/

	public function index($module_id=null,$type=null,$remark_id=null){
		
		$user = $this->getAuth();
		//to identify remark from which module
		if ($type=='budget'){
			
			$mainModel='Budget';
			$reviewerModel='BReviewer';
			$remarkModel='BRemark';
			$feedbackModel='BRemarkFeedback';
			$assignModel='BRemarkAssign';
			$modelId='budget_id';

		}

		if ($type=='financial'){
			
			$mainModel='FMemo';
			$reviewerModel='FReviewer';
			$remarkModel='FRemark';
			$feedbackModel='FRemarkFeedback';
			$assignModel='FRemarkAssign';
			$modelId='memo_id';

		}

		if ($type=='nonfinancial'){
			
			$mainModel='NfMemo';
			$reviewerModel='NfReviewer';
			$remarkModel='NfRemark';
			$feedbackModel='NfRemarkFeedback';
			$assignModel='NfRemarkAssign';
			$modelId='memo_id';

		}

		//retrieve reviewer list for this module
		$this->$reviewerModel->contain(array('User'));
		$reviewer=$this->$reviewerModel->find('list',array('conditions'=>array("$reviewerModel.$modelId"=>$module_id,"$reviewerModel.user_id !=" =>$user['user_id']),'fields'=>array('User.user_id','User.staff_name')));
		
		//find the requestor to append to the remark reviewer list
		$this->$mainModel->contain(array('User'));
		$requestor=$this->$mainModel->find('first',array('conditions'=>array("$mainModel.$modelId"=>$module_id,"$mainModel.user_id !=" =>$user['user_id']),'fields'=>array('User.user_id','User.staff_name')));
		
		//append requestor to reviewer list
		$reviewer[$requestor['User']['user_id']]=$requestor['User']['staff_name'];
		//debug($user['user_id']);exit;
		
		//retrieve basic info on this module 
		$module_info=$this->$mainModel->find('first',array('conditions'=>array("$mainModel.$modelId"=>$module_id),'fields'=>array('user_id')));
		
		//retrieve remarks assigned to the user for this module 
		$this->$remarkModel->contain(array("$assignModel"=>array('User'=>array('fields'=>array('staff_name'))),'User'=>array('fields'=>array('staff_name','user_id'))));
		$remark=$this->$remarkModel->find('all',array('conditions'=>array("$remarkModel.$modelId"=>$module_id),'order'=>array("$remarkModel.created"=>'ASC')));
		

		$remarkInfo=array();
		if(!empty($remark)){

			foreach ($remark as  $value) {
				foreach ($value["$assignModel"] as $val) {

					if ($val['user_id']==$user['user_id']){

						$temp=array();
						$temp['remark_id']=$value["$remarkModel"]['remark_id'];
						$temp['subject']=$value["$remarkModel"]['subject'];
						$temp['created']=$value["$remarkModel"]['created'];
						$temp['creator']=$value['User']['staff_name'];
						$temp['creator_id']=$value['User']['user_id'];
						$temp['assign']=$this->$assignModel->find('all',array('fields'=>array(''),'conditions'=>array("$assignModel.remark_id"=>$temp['remark_id']),'contain'=>array('User'=>array('fields'=>array('staff_name')))));
						// $temp['feedback']=$this->$feedbackModel->find('all',array('conditions'=>array("$feedbackModel.remark_id"=>$temp['remark_id']),'order'=>array("$feedbackModel.created"=>'ASC'),'contain'=>array('User'=>array('fields'=>array('staff_name')))));
						$remarkInfo[]=$temp;
						

						break;
					}
				}
			}
		}
		$feedback=array();
		//debug($remarkInfo);exit;
		$subject='Please select a remark to display.';
		//if a remark is selected, find the related feedback information to the remark
		if (!empty($remark_id)){
			$this->$feedbackModel->contain(array('User'=>array('fields'=>array('staff_name'))));
			$feedbackTmp=$this->$feedbackModel->find('all',array('conditions'=>array("$feedbackModel.remark_id"=>$remark_id),'order'=>array("$feedbackModel.created"=>'ASC')));
			$tmp=array();
			foreach ($feedbackTmp as $value) {
				$tmp['staff_name']=$value['User']['staff_name'];
				$tmp['created']=$value["$feedbackModel"]['created'];
				$tmp['feedback']=$value["$feedbackModel"]['feedback'];
				$tmp['feedback_id']=$value["$feedbackModel"]['feedback_id'];
				$feedback[]=$tmp;
			}
			//find the subject for this remark
			$query=$this->$remarkModel->find('first',array('conditions'=>array("$remarkModel.remark_id"=>$remark_id),'fields'=>array("subject")));
			$subject=$query["$remarkModel"]['subject'];
		}

		//debug ($feedback);exit;
 		$this->set('remarkInfo',$remarkInfo);
 		$this->set('reviewer',$reviewer);
 		$this->set('feedback',$feedback);
 		$this->set('type',$type);
 		$this->set('module_id',$module_id);
 		$this->set('remark_id',$remark_id);
 		$this->set('subject',$subject);


		//set add remark button to be invisible to requestor
		$add=true;
		if ($user['user_id']==$module_info["$mainModel"]['user_id'])
			$add=false;
 		
 		$this->set('add',$add);

	}

	/*

	*	add()

	*	add new remark 

	*	@ aisyah

	*/

	public function add($module_id=null,$type=null){
		
		$user = $this->getAuth();
		//debug ($this->request->data);exit;
		if ($this->request->is('post')|| $this->request->is('put')){
			if ($type=='budget'){
			
			$mainModel='Budget';
			$reviewerModel='BReviewer';
			$remarkModel='BRemark';
			$feedbackModel='BRemarkFeedback';
			$assignModel='BRemarkAssign';
			$modelId='budget_id';

		}

		if ($type=='financial'){
			
			$mainModel='FMemo';
			$reviewerModel='FReviewer';
			$remarkModel='FRemark';
			$feedbackModel='FRemarkFeedback';
			$assignModel='FRemarkAssign';
			$modelId='memo_id';

		}

		if ($type=='nonfinancial'){
			
			$mainModel='NfMemo';
			$reviewerModel='NfReviewer';
			$remarkModel='NfRemark';
			$feedbackModel='NfRemarkFeedback';
			$assignModel='NfRemarkAssign';
			$modelId='memo_id';

		}

			if (!empty($this->request->data["$remarkModel"]['subject'])&&!empty($this->request->data["$feedbackModel"]['feedback'])){
				
				$userid=$user['user_id'];
				$this->$remarkModel->create();
				$this->request->data["$remarkModel"]['user_id']=$userid;
				//$this->request->data["$remarkModel"]["$modelId"]=$module_id;

				if ($this->$remarkModel->save($this->request->data)) {

					$remarkid=$this->$remarkModel->id;

					$this->$assignModel->create();

					$this->request->data["$assignModel"]['remark_id']=$remarkid;

					$this->request->data["$assignModel"]['user_id']=$userid;
					//$this->request->data["$assignModel"]['viewed']=1;
					$this->$assignModel->save($this->request->data);

					foreach($this->request->data["$assignModel"]['selectedUser'] as $uid){

						$this->$assignModel->create();

						$this->request->data["$assignModel"]['remark_id']=$remarkid;
						//$this->request->data["$assignModel"]['viewed']=0;
						$this->request->data["$assignModel"]['user_id']=$uid;

						$this->$assignModel->save($this->request->data);

					}

					$this->$feedbackModel->create();

					$this->request->data["$feedbackModel"]['remark_id']=$remarkid;
					
					$this->request->data["$feedbackModel"]['user_id']=$userid;

					if ($this->$feedbackModel->save($this->request->data)) {

						$this->Session->setFlash(__('<b>The remark has been added successfully.</b>'),'flash.success');
						return $this->redirect(array('action' => 'index',$module_id,$type,$remarkid));

					}

				} 
				
				else {

					$this->Session->setFlash(__('<b>The remark could not be created. Please try again.</b>'),'flash.error');

				}

			}
		}
		return $this->redirect($this->referer());

	}

	/*

	*	reply()

	*	handles feedback reply to remark 

	*	@ aisyah

	*/

	

	public function reply($type=null){
		
		$user = $this->getAuth();
		//debug ($this->request->data);exit;
		if($this->request->is('post')){
			if ($type=='budget'){
				$feedbackModel='BRemarkFeedback';
			}

			if ($type=='financial'){
				$feedbackModel='FRemarkFeedback';
			}

			if ($type=='nonfinancial'){
				$feedbackModel='NfRemarkFeedback';
			}

			$this->request->data["$feedbackModel"]['user_id']=$user['user_id'];
			
			$this->$feedbackModel->create();
			
			if ($this->$feedbackModel->save($this->request->data)){
				$this->Session->setFlash('The feedback has been added successfully.','flash.success');
			}

			else{
				$this->Session->setFlash('Problem adding feedback. Please try again.','flash.error');

			}
		}

		return $this->redirect($this->referer());




	}

	/*

	*	edit()

	*	edit feedback 

	*	@ aisyah

	*/

	

	// public function edit($type=null){
		
	// 	$user = $this->getAuth();
	// 	if ($this->request->is('post')){
	// 		if ($type=='budget'){
	// 			$feedbackModel='BRemarkFeedback';
	// 		}

	// 		if ($type=='financial'){
	// 			$feedbackModel='FRemarkFeedback';
	// 		}

	// 		if ($type=='nonfinancial'){
	// 			$feedbackModel='NfRemarkFeedback';
	// 		}

	// 		$this->$feedbackModel->id=$this->request->data["$feedbackModel"]['feedback_id'];
	// 		if($this->$feedbackModel->saveField('feedback',$this->request->data["$feedbackModel"]['feedback'])){

	// 			$this->Session->setFlash('<b>Feedback has been successfully updated. </b>','flash.success');
	// 		}
			
	// 	}
	// }


	/*

	*	deleteFeedback()

	*	delete feedback 

	*	@ aisyah

	*/

	

	public function delete($module_id=null,$type=null,$remark_id=null){
		
		$user = $this->getAuth();

		if ($type=='budget'){
				$remarkModel='BRemark';
			}

			if ($type=='financial'){
				$remarkModel='FRemark';
			}

			if ($type=='nonfinancial'){
				$remarkModel='NfRemark';
			}
		if (!$this->$remarkModel->exists($remark_id)) {

			$this->Session->setFlash(__('Invalid remark.'),'flash.error');

		}

		if($this->$remarkModel->delete($remark_id)){

			$this->Session->setFlash(__('<b>Remark has been deleted successfully.</b>'),'flash.success');
			return $this->redirect(array('action'=>'index',$module_id,$type));
				
		}

		else
			$this->Session->setFlash(__('<b>Problem deleting remark. Please try again.</b>'),'flash.error');


		return $this->redirect($this->referer());

	}

	/*

	*	back()

	*	go back to view page 

	*	@ aisyah

	*/

	public function back($module_id=null,$type=null){
		
		$user = $this->getAuth();
		//to identify remark from which module
		if ($type=='budget'){
			$mainController='Budget';
			$view='review';
		}

		if ($type=='financial'){
			$mainController='Financial';
			$view='review';
		}

		if ($type=='nonfinancial'){
			$mainController='NFinancial';
		}

		return $this->redirect(array('controller'=>"$mainController",'action'=>'review',$module_id));

	}

}