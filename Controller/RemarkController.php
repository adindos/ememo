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



class RemarkController extends AppController {

	public $uses = array('FMemo','FRemark','FRemarkAssign','FRemarkFeedback','FReviewer','NfMemo','NfRemark','NfRemarkAssign','NfRemarkFeedback','NfReviewer','Budget','BRemark','BRemarkAssign','BRemarkFeedback','BReviewer','User','BItemAmount');

	public $layout = 'mems';


	/*

	*	index()

	*	Main page for remark

	*	@ Aisyah

	*/

	public function index($module_id=null,$type=null,$remark_id=null){
		
		$user = $this->getAuth();
		$module_id=$this->decrypt($module_id);
		//debug($remark_id);exit;
		

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

		if (!empty($remark_id)){
			$remark_id=$this->decrypt($remark_id);
			if (!$this->$remarkModel->exists($remark_id)) {

				throw new ForbiddenException();

			}
		}

		//retrieve reviewer list for this module
		$this->$reviewerModel->contain(array('User'));
		$reviewer=$this->$reviewerModel->find('list',array('conditions'=>array("$reviewerModel.$modelId"=>$module_id,"$reviewerModel.user_id !=" =>$user['user_id']),'fields'=>array('User.user_id','User.staff_name')));


		//check if user is assigned to more than 1 as reviewer/recommender/finance/approver
		$this->$reviewerModel->recursive=-1;
		$createAs=$this->$reviewerModel->find('list',array('conditions'=>array("$reviewerModel.$modelId"=>$module_id,"$reviewerModel.user_id" =>$user['user_id']),'fields'=>array("$reviewerModel.reviewer_id","$reviewerModel.approval_type")));
		
		//find the requestor to append to the remark reviewer list
		$this->$mainModel->contain(array('User'));
		$requestor=$this->$mainModel->find('first',array('conditions'=>array("$mainModel.$modelId"=>$module_id,"$mainModel.user_id !=" =>$user['user_id']),'fields'=>array('User.user_id','User.staff_name')));
		
		//append requestor to reviewer list
		if (!empty($requestor)){
			if($requestor['User']['user_id']!=$user['user_id'])
				$reviewer[$requestor['User']['user_id']]=$requestor['User']['staff_name'];
		}
		
		//retrieve basic info on this module 
		if($type=='financial')
			$this->$mainModel->contain(array('User.Department','FMemoTo','FMemoTo.User'=>array('fields'=>array('staff_name','User.designation'))));
		elseif($type=='nonfinancial')
			$this->$mainModel->contain(array('User.Department','NfMemoTo','NfMemoTo.User'=>array('fields'=>array('staff_name','User.designation'))));
		elseif($type=='budget'){
			$this->$mainModel->contain(array('User.Department'));
			$this->BItemAmount->recursive=-1;
			$budgetItem = $this->BItemAmount->find('all',array('conditions'=>array('BItemAmount.budget_id'=>$module_id),'fields'=>array('sum(amount) as total')));
			//debug($budgetItem);exit;
 		$this->set('total',$budgetItem[0][0]['total']);

		}
		$module_info=$this->$mainModel->find('first',array('conditions'=>array("$mainModel.$modelId"=>$module_id)));
		
		//retrieve remarks assigned to the user for this module 
		$this->$remarkModel->contain(array("$assignModel"=>array('User'=>array('fields'=>array('staff_name'))),'User'=>array('fields'=>array('staff_name','user_id'))));
		$remark=$this->$remarkModel->find('all',array('conditions'=>array("$remarkModel.$modelId"=>$module_id),'order'=>array("$remarkModel.created"=>'ASC')));
		
		$isReviewer=$this->$reviewerModel->find('all',array('conditions'=>array("$reviewerModel.$modelId"=>$module_id,"$reviewerModel.user_id"=>$user['user_id'])));
		//debug($isReviewer);exit;
		//set add remark button to be invisible to requestor
		$add=false;
		if (!empty($isReviewer)){
			$add=true;

		}

		$remarkInfo=array();
		if(!empty($remark)){
			$counter=0;

			foreach ($remark as  $value) {
				foreach ($value["$assignModel"] as $val) {
					if ($val['user_id']==$user['user_id']){

						$temp=array();
						$temp['remark_id']=$value["$remarkModel"]['remark_id'];
						$temp['subject']=$value["$remarkModel"]['subject'];
						$temp['created']=$value["$remarkModel"]['created'];
						$temp['creator']=$value['User']['staff_name'];
						$temp['creator_id']=$value['User']['user_id'];
						if ($temp['creator_id']==$user['user_id'])
							$counter++;
						$temp['assign']=$this->$assignModel->find('all',array('fields'=>array(''),'conditions'=>array("$assignModel.remark_id"=>$temp['remark_id']),'contain'=>array('User'=>array('fields'=>array('staff_name')))));
						
						$remarkInfo[]=$temp;
						

						break;
					}
				}

			}

			if ($counter>=count($isReviewer))
				$add=false;

		}
		$feedback=array();
		$subject='<h5>Please select a remark to display.</h5>';
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
			$query=$this->$remarkModel->find('first',array('conditions'=>array("$remarkModel.remark_id"=>$remark_id),'fields'=>array("subject","user_id")));
			$subject=$query["$remarkModel"]['subject'];
			$creatorId=$query["$remarkModel"]['user_id'];
 			$this->set('creatorId',$creatorId);
 			$this->set('remark_id',$this->encrypt($remark_id));

		}

 		$this->set('remarkInfo',$remarkInfo);
 		$this->set('module_info',$module_info);
 		$this->set('reviewer',$reviewer);
 		$this->set('feedback',$feedback);
 		$this->set('type',$type);
 		$this->set('module_id',$this->encrypt($module_id));
 		if (empty($remark_id))
 			$this->set('remark_id',$remark_id);

 		
 		$this->set('subject',$subject);
 		$this->set('createAs',$createAs);
 		//debug($createAs);exit;

		
 		
 		$this->set('add',$add);

	}

	/*

	*	add()

	*	add new remark 

	*	@ aisyah

	*/

	public function add($module_id=null,$type=null){
		
		$user = $this->getAuth();
		$module_id=$this->decrypt($module_id);
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

			if (!empty($this->request->data["$feedbackModel"]['feedback'])){
				
				$user_id=$user['user_id'];
				//check only 1 remark per type
				$this->$remarkModel->recursive=-1;
				$addedRemark=$this->$remarkModel->find('all',array('conditions'=>array("$remarkModel.reviewer_id"=>$this->request->data["$remarkModel"]['reviewer_id'],"$remarkModel.$modelId"=>$this->decrypt($this->request->data["$remarkModel"]["$modelId"]))));
				//debug($addedRemark);exit;

				if (!empty($addedRemark)){

						$this->Session->setFlash(__('<b>Limited to one remark only per reviewer/recommender/finance/approver.</b>'),'flash.error');
						return $this->redirect($this->referer());
				}

				$this->$remarkModel->create();
				$this->request->data["$remarkModel"]['user_id']=$user_id;
				$this->request->data["$remarkModel"]["$modelId"]=$this->decrypt($this->request->data["$remarkModel"]["$modelId"]);
				$this->$reviewerModel->recursive=-1;
				$createAs=$this->$reviewerModel->find('first',array('conditions'=>array("$reviewerModel.reviewer_id"=>$this->request->data["$remarkModel"]['reviewer_id']),'fields'=>array("$reviewerModel.approval_type")));
				$this->request->data["$remarkModel"]['subject']='Remark by '.$user['staff_name'].' ('.$createAs["$reviewerModel"]['approval_type'].')';
				//$this->request->data["$remarkModel"]["$modelId"]=$module_id;

				if ($this->$remarkModel->save($this->request->data)) {

					$remark_id=$this->$remarkModel->id;

					$this->$assignModel->create();

					$this->request->data["$assignModel"]['remark_id']=$remark_id;

					$this->request->data["$assignModel"]['user_id']=$user_id;
					//$this->request->data["$assignModel"]['viewed']=1;
					$this->$assignModel->save($this->request->data);

					if (!empty($this->request->data["$assignModel"]['selectedUser'])){
						foreach($this->request->data["$assignModel"]['selectedUser'] as $uid){

							$this->$assignModel->create();

							$this->request->data["$assignModel"]['remark_id']=$remark_id;
							//$this->request->data["$assignModel"]['viewed']=0;
							$this->request->data["$assignModel"]['user_id']=$uid;

							$this->$assignModel->save($this->request->data);

						}
					}

					$this->$feedbackModel->create();

					$this->request->data["$feedbackModel"]['remark_id']=$remark_id;
					
					$this->request->data["$feedbackModel"]['user_id']=$user_id;

					if ($this->$feedbackModel->save($this->request->data)) {
						$feedback_id=$this->$feedbackModel->id;
						//send email and add notifications to all assigend ppl
						$this->sendRemarkEmail($module_id,$type,$feedback_id);

						$this->Session->setFlash(__('<b>The remark has been added successfully.</b>'),'flash.success');
						return $this->redirect(array('action' => 'index',$this->encrypt($module_id),$type,$this->encrypt($remark_id)));

					}

				} 
				
				else {

					$this->Session->setFlash(__('<b>The remark could not be created. Please try again.</b>'),'flash.error');

				}

			}

			else {

					$this->Session->setFlash(__('<b>Please fill in all fields.</b>'),'flash.error');

				}
		}
		return $this->redirect($this->referer());

	}

	/*

	*	reply()

	*	handles feedback reply to remark 

	*	@ aisyah

	*/

	public function reply($module_id=null,$type=null){
		
		$user = $this->getAuth();
		$module_id=$this->decrypt($module_id);
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
			$this->request->data["$feedbackModel"]['remark_id']=$this->decrypt($this->request->data["$feedbackModel"]['remark_id']);
			
			if ($this->$feedbackModel->save($this->request->data)){
				$feedback_id=$this->$feedbackModel->id;
				//$module_id=
				//send email and add notifications to all assigend ppl
				$this->sendRemarkEmail($module_id,$type,$feedback_id);

				$this->Session->setFlash('The feedback has been added successfully.','flash.success');
			}

			else{
				$this->Session->setFlash('Problem adding feedback. Please try again.','flash.error');

			}
		}

		return $this->redirect($this->referer());

	}

	
	/*

	*	delete()

	*	delete remark 

	*	@ aisyah

	*/


	public function delete($module_id=null,$type=null,$remark_id=null){
		
		$user = $this->getAuth();
		$module_id=$this->decrypt($module_id);
		$remark_id=$this->decrypt($remark_id);

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
			return $this->redirect(array('action'=>'index',$this->encrypt($module_id),$type));
				
		}

		else
			$this->Session->setFlash(__('<b>Problem deleting remark. Please try again.</b>'),'flash.error');


		return $this->redirect($this->referer());

	}

	/*

	*	back()

	*	go back to review page 

	*	@ aisyah

	*/

	public function back($module_id=null,$type=null){
		
		$user = $this->getAuth();
		$module_id=$this->decrypt($module_id);
		//to identify remark from which module
		if ($type=='budget'){
			$mainController='Budget';
			$view='review';
		}

		if ($type=='financial'){
			$mainController='FMemo';
			$view='review';
		}

		if ($type=='nonfinancial'){
			$mainController='NfMemo2';
		}

		return $this->redirect(array('controller'=>"$mainController",'action'=>'review',$this->encrypt($module_id)));

	}

	public function sendRemarkEmail($module_id=null,$type=null,$feedback_id=null){
		$user = $this->getAuth();

		//to identify remark from which module
		if ($type=='budget'){
			
			$mainModel='Budget';
			//$reviewerModel='BReviewer';
			$remarkModel='BRemark';
			$feedbackModel='BRemarkFeedback';
			$assignModel='BRemarkAssign';
			//$modelId='budget_id';

		}

		if ($type=='financial'){
			
			$mainModel='FMemo'; // uncomment by nizam on 7/5/2015
			//$reviewerModel='FReviewer';
			$remarkModel='FRemark';
			$feedbackModel='FRemarkFeedback';
			$assignModel='FRemarkAssign';
			//$modelId='memo_id';

		}

		if ($type=='nonfinancial'){
			
			$mainModel='NfMemo'; // uncomment by nizam on 7/5/2015
			//$reviewerModel='NfReviewer';
			$remarkModel='NfRemark';
			$feedbackModel='NfRemarkFeedback';
			$assignModel='NfRemarkAssign';
			//$modelId='memo_id';

		}
		
		$this->$feedbackModel->contain(array("$remarkModel","$remarkModel.$mainModel","User","User.Department"));
		$feedback = $this->$feedbackModel->find('first',array('conditions'=>array("$feedbackModel.feedback_id"=>$feedback_id)));
		$remark_id=$feedback["$feedbackModel"]['remark_id'];
		//$this->$assignModel->contain(array("$assignModel"=>array('conditions'=>array("$assignModel.user_id NOT"=>$user['user_id']))));
		$this->$assignModel->recursive=-1;
		$assigned = $this->$assignModel->find('all',array('conditions'=>array("$assignModel.remark_id"=>$remark_id,"$assignModel.user_id NOT"=>$user['user_id'])));
		$encModuleID=$this->encrypt($module_id);
		$encRemarkID=$this->encrypt($remark_id);

		//added by Nizam on 7/5/2015 for user notification
		// if($type=='budget'){
		// 	$this->Budget->contain(array('Department'));
		// 	$budget = $this->Budget->findByBudgetId($module_id);
		// }
		// else{
		// 	$this->$mainModel->contain(array('Department'));
		// 	$memo = $this->$mainModel->findByMemoId($module_id);
		// }

		foreach ($assigned as  $value) {
			$toAssigned= $value["$assignModel"]['user_id'];
			//generate link
			$link = $this->goLink($toAssigned,array('controller'=>'Remark','action'=>'index',$encModuleID,$type,$encRemarkID));

			$email = "You have an unread remark/feedback as follows:<br>";
			//$email = "Please review it again and resubmit your memo request.<br>";
			$email .= $this->remarkTable($feedback,$type);
			$email .= "You may go to the remark page by clicking the button below <br>";

			
			// debug($link);exit();
			$email .= "<a href='{$link}' class='btn btn-success'> Go to remark page </a>";

			//$toRequestor = $memo['FMemo']['user_id'];
			
			//$subject = " New remark/feedback ";

			
			// $notiText = "You have an unread remark/feedback";

			if($type == 'budget'){
				$subject = $feedback["$remarkModel"]["$mainModel"]['title'].' (New remark/feedback) ';
				$sub="<b> Title : </b> ".$feedback["$remarkModel"]["$mainModel"]['title'];
			}
			else{
				$subject = $feedback["$remarkModel"]["$mainModel"]['subject'].' (New remark/feedback) ';
				$sub="<b> Ref.No : </b> ".$feedback["$remarkModel"]["$mainModel"]['ref_no']. "<br>".
					"<b> Subject : </b> ".$feedback["$remarkModel"]["$mainModel"]['subject'];

			}
				
			$notiText = $sub. "<br>".
					"<b> Dept : </b> ".$feedback['User']['Department']['department_name']."<br>".
					"<b> Pending : </b> Remark";
			
			// else{
			// 	$subject = $memo["$mainModel"]['subject'].' (New remark/feedback) ';

			// 	$notiText = "<b> Ref.No : </b> ".$memo["$mainModel"]['ref_no']. "<br>".
			// 			"<b> Subject : </b> ".$memo["$mainModel"]['subject']."<br>".
			// 			"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
			// 			"<b> Pending : </b> Remark";
			// }

			$this->emailMe($toAssigned,$subject,$email);

			// add notification to the assigned ppl -- stating there is a new remark/feedback
			$notiTo = $toAssigned;
			
			$notiLink = array('controller'=>'Remark','action'=>'index',$encModuleID,$type,$encRemarkID);

			#update code for ememo2
			#ememo2
			$notiType="remark";
			$this->UserNotification->record($notiTo, $notiText, $notiLink,$notiType);
		}
		
	}

	private function remarkTable($feedbackData = array(),$type){
		// $budgetAmount = $this->BItemAmount->find('first',array('fields'=>array('SUM(amount) as totalBudget'),'conditions'=>array('BItemAmount.budget_id'=>$budgetid)));
		// $totalBudget = $budgetAmount[0]['totalBudget'];
		//to identify remark from which module
		if ($type=='budget'){
			
			$mainModel='Budget';
			$title='Budget';
			$remarkModel='BRemark';
			$feedbackModel='BRemarkFeedback';
			$sub='title';
			//$modelId='budget_id';

		}

		if ($type=='financial'){
			
			$mainModel='FMemo';
			$title='Memo';
			$remarkModel='FRemark';
			$feedbackModel='FRemarkFeedback';
			$sub='subject';
			//$assignModel='FRemarkAssign';
			//$modelId='memo_id';

		}

		if ($type=='nonfinancial'){
			
			$mainModel='NfMemo';
			$title='Memo';
			$sub='subject';
			//$reviewerModel='NfReviewer';
			$remarkModel='NfRemark';
			$feedbackModel='NfRemarkFeedback';
			//$assignModel='NfRemarkAssign';
			//$modelId='memo_id';

		}
		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'>".$title.
						" Created </td>
							<td>".date('d M Y',strtotime($feedbackData["$remarkModel"]["$mainModel"]['created'])). " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Subject/Title </td>
							<td>".$feedbackData["$remarkModel"]["$mainModel"]["$sub"]. " </td>
						</tr>";
						if ($type!='budget'){
							$htmlTable=$htmlTable. "<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Reference No. </td>
							<td>".$feedbackData["$remarkModel"]["$mainModel"]['ref_no']. " </td>
						</tr>";

						}
						
						$htmlTable=$htmlTable."
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Remark / Feedback </td>
							<td>".$feedbackData["$feedbackModel"]['feedback']. " </td>
						</tr>						
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Added by </td>
							<td> ".
								$feedbackData['User']['staff_name'].' ('.$feedbackData['User']['designation'].')'."<br>". 
								"<small>Department : ".$feedbackData['User']['Department']['department_name']."</small>".
							"</td>
						</tr>
						
						
					</table>";

		return $htmlTable;
	}


}