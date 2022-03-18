<?php


/* change send email noti to only one noti per person(if they hv multiple roles in the memo)

*	=========================================================================

*	UNITAR MeMs 2015 

*	@vendor GR Tech Sdn Bhd

*	@team Syikin,Aisyah,Nizam

*	=========================================================================

*	

*	[ File ]

*		CommentController.php

*			- Controller to control the action for comment

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



class CommentController extends AppController {

	public $uses = array('FMemo','FComment','FReply','FReviewer','NfMemo','NfComment','NfReply','NfReviewer','User','NfMemoTo','FMemoTo','NfCommentAttachment','FCommentAttachment');

	public $layout = 'mems';



	/*

	*	index()

	*	Main page for comment

	*	@ Aisyah

	*/

	public function index($module_id=null,$type=null,$comment_id=null){
		
		$user = $this->getAuth();
		$module_id=$this->decrypt($module_id);
		//debug($comment_id);exit;

		
		

		//to identify comment from which module

		if ($type=='financial'){
			
			$mainModel='FMemo';
			$commentModel='FComment';
			$replyModel='FReply';
			$attchModel='FCommentAttachment';
			$folder='fcomment-attachment';
		}

		if ($type=='nonfinancial'){
			
			$mainModel='NfMemo';
			$commentModel='NfComment';
			$replyModel='NfReply';
			$attchModel='NfCommentAttachment';
			$folder='fcomment-attachment';
		}

		if (!empty($comment_id)){
			$comment_id=$this->decrypt($comment_id);
			if (!$this->$commentModel->exists($comment_id)) {

				throw new ForbiddenException();

			}
		}

		//retrieve basic info on this module 
		if($type=='financial')
			$this->$mainModel->contain(array('User.Department','FMemoTo','FMemoTo.User'=>array('fields'=>array('staff_name','User.designation'))));
		elseif($type=='nonfinancial')
			$this->$mainModel->contain(array('User.Department','NfMemoTo','NfMemoTo.User'=>array('fields'=>array('staff_name','User.designation'))));			
			$module_info=$this->$mainModel->find('first',array('conditions'=>array("$mainModel.memo_id"=>$module_id)));
		
		//retrieve comments assigned to the user for this module 
		$this->$commentModel->contain(array('User'=>array('fields'=>array('staff_name','user_id'))));
		$comment=$this->$commentModel->find('all',array('conditions'=>array("$commentModel.memo_id"=>$module_id),'order'=>array("$commentModel.created"=>'ASC')));
		

		$commentInfo=array();
		if(!empty($comment)){

			foreach ($comment as  $value) {
				

				$temp=array();
				$temp['comment_id']=$value["$commentModel"]['comment_id'];
				$temp['title']=$value["$commentModel"]['title'];
				$temp['created']=$value["$commentModel"]['created'];
				$temp['creator']=$value['User']['staff_name'];
				$temp['creator_id']=$value['User']['user_id'];
				
				
				$commentInfo[]=$temp;
						
			}
		}
		$reply=array();
		$subject='<h5>Please select a comment to display.</h5>';
		//if a comment is selected, find the related reply information to the comment
		if (!empty($comment_id)){
			$this->$replyModel->contain(array('User'=>array('fields'=>array('staff_name')),"$attchModel"));
			$replyTmp=$this->$replyModel->find('all',array('conditions'=>array("$replyModel.comment_id"=>$comment_id),'order'=>array("$replyModel.created"=>'ASC')));
			$tmp=array();
			foreach ($replyTmp as $value) {
				$tmp['staff_name']=$value['User']['staff_name'];
				$tmp['created']=$value["$replyModel"]['created'];
				$tmp['reply']=$value["$replyModel"]['reply'];
				$tmp['reply_id']=$value["$replyModel"]['reply_id'];
				$tmp['attachment']=$value["$attchModel"];
				$reply[]=$tmp;
			}
			//find the subject for this comment
			$query=$this->$commentModel->find('first',array('conditions'=>array("$commentModel.comment_id"=>$comment_id),'fields'=>array("title","user_id")));
			$subject=$query["$commentModel"]['title'];
			$creatorId=$query["$commentModel"]['user_id'];
 			$this->set('creatorId',$creatorId);

 			$this->set('comment_id',$this->encrypt($comment_id));

		}

 		$this->set('commentInfo',$commentInfo);
 		$this->set('module_info',$module_info);
 		$this->set('reply',$reply);
 		$this->set('type',$type);
 		$this->set('module_id',$this->encrypt($module_id));
 		if (empty($comment_id))
 			$this->set('comment_id',$comment_id);

 		$this->set('subject',$subject);
 		
 		

	}

	/*

	*	add()

	*	add new comment 

	*	@ aisyah

	*/

	public function add($module_id=null,$type=null){
		
		$user = $this->getAuth();
		$module_id=$this->decrypt($module_id);
		//debug ($this->request->data);exit;

		if ($this->request->is('post')|| $this->request->is('put')){
			

			if ($type=='financial'){
				$mainModel='FMemo';
				
				$commentModel='FComment';
				$replyModel='FReply';
				$attchModel='FCommentAttachment';
				$folder='fcomment-attachment';
			}

			if ($type=='nonfinancial'){
				$mainModel='NfMemo';
				
				$commentModel='NfComment';
				$replyModel='NfReply';
				$attchModel='NfCommentAttachment';
				$folder='nfcomment-attachment';
				
			}
         

			if (!empty($this->request->data["$replyModel"]['reply'])){
				
				$user_id=$user['user_id'];
				$this->$commentModel->create();
				$this->request->data["$commentModel"]['user_id']=$user_id;
				$this->request->data["$commentModel"]["memo_id"]=$this->decrypt($this->request->data["$commentModel"]["memo_id"]);
				$this->request->data["$commentModel"]['title']='Comment by '.$user['staff_name'].' ('.$user['designation'].')';

				//$this->request->data["$commentModel"]["memo_id"]=$module_id;
				//debug ($this->request->data);exit;

				if ($this->$commentModel->save($this->request->data)) {	

					$comment_id=$this->$commentModel->id;
					$this->$replyModel->create();
					$this->request->data["$replyModel"]['comment_id']=$comment_id;					
					$this->request->data["$replyModel"]['user_id']=$user_id;										

					if ($this->$replyModel->save($this->request->data)) {
						$reply_id=$this->$replyModel->id;
						//add attachment to reply, if exist
						if(!empty($this->request->data["$replyModel"]['files'])){
							if (!(count($this->request->data["$replyModel"]['files'])==1&&empty($this->request->data["$replyModel"]['files'][0]['tmp_name']))){
								// assume filetype is false
								$typeOK = false;
								// list of permitted file types, 
								$permitted = array('application/msword','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf');

								foreach ($this->request->data["$replyModel"]['files'] as $value) {
									$file = $value;

									$filename = null;

									// check filetype is ok
									foreach($permitted as $var) {
										if($var == $file['type']) {
											$typeOK = true;
											break;
										}
									}
									
									if ($typeOK){
										//check filesize less than 10mb
										if ($file['size'] <= 10485760){


								            if(!empty($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
								                 $filename = time().'___'.basename($file['name']); 
								                 if(move_uploaded_file($file['tmp_name'], WWW_ROOT.'files'.DS.$folder.DS.$filename)){

								                 	$this->$attchModel->create();
										            $this->request->data["$attchModel"]['filename'] = $filename;
										            $this->request->data["$attchModel"]['reply_id'] = $reply_id;
										            $this->$attchModel->save($this->request->data);
								                 }
								            }
								            else{

									        	$this->Session->setFlash(__('<b>'.basename($file['name']).' cannot be uploaded. Please try again.</b>'),'flash.error');
												return $this->redirect(array('action' => 'index',$this->encrypt($module_id),$type,$this->encrypt($comment_id)));
									        }
								        }

								        else{

									        	$this->Session->setFlash(__('<b>'.basename($file['name']).' is too big. Maximum file size is 10Mb.</b>'),'flash.error');
												return $this->redirect(array('action' => 'index',$this->encrypt($module_id),$type,$this->encrypt($comment_id)));
									        }
							        }

							        else{

							        	$this->Session->setFlash(__('<b>'.basename($file['name']).' cannot be uploaded. Acceptable file types: pdf, word, excel.</b>'),'flash.error');
										return $this->redirect(array('action' => 'index',$this->encrypt($module_id),$type,$this->encrypt($comment_id)));
							        }
					        	}
					        }
						}//debug ($reply_id);exit;
						//send email and add notifications to all assigned ppl
						$this->sendCommentEmail($module_id,$type,$reply_id);
						$this->Session->setFlash(__('<b>The comment has been added successfully.</b>'),'flash.success');					
						return $this->redirect(array('action' => 'index',$this->encrypt($module_id),$type,$this->encrypt($comment_id)));				

					}

				} 

				
				else {

					$this->Session->setFlash(__('<b>The comment could not be created. Please try again.</b>'),'flash.error');

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

	*	handles reply reply to comment 

	*	@ aisyah

	*/

	public function reply($module_id=null,$type=null,$staff=null){
		
		$user = $this->getAuth();
		$module_id=$this->decrypt($module_id);
		//debug ($this->request->data);exit;
		if($this->request->is('post')|| $this->request->is('put')){
			
			if ($type=='financial'){
				$replyModel='FReply';
				$attchModel='FCommentAttachment';
				$folder='fcomment-attachment';
				
			}

			if ($type=='nonfinancial'){
				$replyModel='NfReply';
				$attchModel='NfCommentAttachment';
				$folder='nfcomment-attachment';
			
			}

			$this->request->data["$replyModel"]['user_id']=$user['user_id'];
			
			$this->$replyModel->create();
			$this->request->data["$replyModel"]['comment_id']=$this->decrypt($this->request->data["$replyModel"]['comment_id']);
			
			if ($this->$replyModel->save($this->request->data)){
				$reply_id=$this->$replyModel->id;
				//debug($this->request->data["$replyModel"]['files']);exit;
				//add attachment to reply, if exist
				if(!empty($this->request->data["$replyModel"]['files'])){
					if (!(count($this->request->data["$replyModel"]['files'])==1&&empty($this->request->data["$replyModel"]['files'][0]['tmp_name']))){
						// assume filetype is false
						$typeOK = false;
						// list of permitted file types, 
						$permitted = array('application/msword','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/pdf');

						foreach ($this->request->data["$replyModel"]['files'] as $value) {
							$file = $value;

							$filename = null;

							// check filetype is ok
							foreach($permitted as $var) {
								if($var == $file['type']) {
									$typeOK = true;
									break;
								}
							}
							
							if ($typeOK){
								//check filesize less than 10mb
								if ($file['size'] <= 10485760){


						            if(!empty($file['tmp_name']) && is_uploaded_file($file['tmp_name'])) {
						                 $filename = time().'___'.basename($file['name']); 
						                 if(move_uploaded_file($file['tmp_name'], WWW_ROOT.'files'.DS.$folder.DS.$filename)){

						                 	$this->$attchModel->create();
								            $this->request->data["$attchModel"]['filename'] = $filename;
								            $this->request->data["$attchModel"]['reply_id'] = $reply_id;
								            $this->$attchModel->save($this->request->data);
						                 }
						            }
						            else{

							        	$this->Session->setFlash(__('<b>'.basename($file['name']).' cannot be uploaded. Please try again.</b>'),'flash.error');
										return $this->redirect(array('action' => 'index',$this->encrypt($module_id),$type,$this->encrypt($comment_id)));
							        }
						        }

						        else{

							        	$this->Session->setFlash(__('<b>'.basename($file['name']).' is too big. Maximum file size is 10Mb.</b>'),'flash.error');
										return $this->redirect(array('action' => 'index',$this->encrypt($module_id),$type,$this->encrypt($comment_id)));
							        }
					        }

					        else{

					        	$this->Session->setFlash(__('<b>'.basename($file['name']).' cannot be uploaded. Acceptable file types: pdf, word, excel.</b>'),'flash.error');
								return $this->redirect(array('action' => 'index',$this->encrypt($module_id),$type,$this->encrypt($comment_id)));
					        }
			        	}
			        }
				}//debug ($reply_id);exit;
				//send email and add notifications to all assigned ppl
				$this->sendCommentEmail($module_id,$type,$reply_id);

				$this->Session->setFlash('The reply has been added successfully.','flash.success');
			}

			else{
				$this->Session->setFlash('Problem adding reply. Please try again.','flash.error');

			}
		}

		return $this->redirect($this->referer());

	}

	/*

	*	downloadAttachment()

	*	< description of function >

	*	@ < author of function editor >

	*/

	public function downloadAttachment($type=null,$attachment_id=null){
		
		$attachment_id=$this->decrypt($attachment_id);
		if ($type=='financial'){
				$mainModel='FMemo';
				
				$commentModel='FComment';
				$replyModel='FReply';
				$attchModel='FCommentAttachment';
				$folder='fcomment-attachment';
			}

			if ($type=='nonfinancial'){
				$mainModel='NfMemo';
				
				$commentModel='NfComment';
				$replyModel='NfReply';
				$attchModel='NfCommentAttachment';
				$folder='nfcomment-attachment';
				
			}
		if (!$this->$attchModel->exists($attachment_id)) {

			$this->Session->setFlash(__('<b>Invalid attachment.</b>'),'flash.error');

		}

		$this->$attchModel->recursive=-1;	
	    $attachment=$this->$attchModel->find('first',array('conditions'=>array("$attchModel.attachment_id"=>$attachment_id)));
	    $tmpName=explode('___',$attachment["$attchModel"]['filename']);
	    if (count($tmpName)>1)
	    	$filename=$tmpName[1];
	    else
	    	$filename=$attachment["$attchModel"]['filename'];

	   	$this->response->file(WWW_ROOT.'files'.DS.$folder.DS.$attachment["$attchModel"]['filename'], array('download' => true, 'name' => $filename));
	    // Return response object to prevent controller from trying to render
	    // a view

	    //record activity
		//$this->Activity->record('downloaded an attachment from a conversation',0);

	    return $this->response;

	}
	

	
	/*

	*	delete()

	*	delete comment 

	*	@ aisyah

	*/


	public function delete($module_id=null,$type=null,$comment_id=null){
		
		$user = $this->getAuth();
		$module_id=$this->decrypt($module_id);
		$comment_id=$this->decrypt($comment_id);

		if ($type=='financial'){
			$commentModel='FComment';
		}

		if ($type=='nonfinancial'){
			$commentModel='NfComment';
		}

		if (!$this->$commentModel->exists($comment_id)) {

			$this->Session->setFlash(__('Invalid comment.'),'flash.error');

		}

		if($this->$commentModel->delete($comment_id)){

			$this->Session->setFlash(__('<b>comment has been deleted successfully.</b>'),'flash.success');
			return $this->redirect(array('action'=>'index',$this->encrypt($module_id),$type));
				
		}

		else
			$this->Session->setFlash(__('<b>Problem deleting comment. Please try again.</b>'),'flash.error');


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

		if ($type=='financial'){
			$mainController='FMemo';
			//$view='review';
		}

		if ($type=='nonfinancial'){
			$mainController='NfMemo2';
		}

		return $this->redirect(array('controller'=>"$mainController",'action'=>'review',$this->encrypt($module_id)));

	}

	public function sendCommentEmail($module_id=null,$type=null,$reply_id=null){
		$user = $this->getAuth();
		//debug ($type);exit;
		if ($type=='financial'){
			$mainModel='FMemo';
			$commentModel='FComment';
			$replyModel='FReply';
			$assignModel='FMemoTo';
			$reviewerModel='FReviewer';

		}

		if ($type=='nonfinancial'){
			$mainModel='NfMemo';
			$commentModel='NfComment';
			$replyModel='NfReply';
			$assignModel='NfMemoTo';
			$reviewerModel='NfReviewer';

		}
		
		$this->$replyModel->contain(array("$commentModel","User","User.Department","$commentModel.$mainModel"));
		$reply = $this->$replyModel->find('first',array('conditions'=>array("$replyModel.reply_id"=>$reply_id)));
		$comment_id=$reply["$replyModel"]['comment_id'];
		$encModuleID=$this->encrypt($module_id);
		$encCommentID=$this->encrypt($comment_id);
		
		//retrieve verifier list for this module
		$this->$assignModel->recursive=-1;
		$assigned = $this->$assignModel->find('all',array('conditions'=>array("$assignModel.memo_id"=>$module_id,"$assignModel.user_id NOT"=>$user['user_id']),'fields'=>array("$assignModel.user_id")));

		foreach ($assigned as $value){
			$commentTo[$value["$assignModel"]['user_id']]=$value["$assignModel"]['user_id'];
		}
		//retrieve reviewer list for this module
		$this->$reviewerModel->recursive=-1;
		$reviewer=$this->$reviewerModel->find('all',array('conditions'=>array("$reviewerModel.memo_id"=>$module_id,"$reviewerModel.user_id !=" =>$user['user_id']),'fields'=>array("$reviewerModel.user_id")));

		if (!empty($reviewer)){
			foreach ($reviewer as $value) {
				$commentTo[$value["$reviewerModel"]['user_id']]=$value["$reviewerModel"]['user_id'];
			}
		}
		//find the requestor to append to the commentto list
		$this->$mainModel->recursive=-1;
		$requestor=$this->$mainModel->find('first',array('conditions'=>array("$mainModel.memo_id"=>$module_id,"$mainModel.user_id !=" =>$user['user_id']),'fields'=>array("$mainModel.user_id")));
		
		//append requestor to commentto list
		if (!empty($requestor))
			$commentTo[$requestor["$mainModel"]['user_id']]=$requestor["$mainModel"]['user_id'];

		//added by Nizam on 7/5/2014 to find the memo
		// $this->$mainModel->contain(array('Department'));
		// $memo = $this->$mainModel->findByMemoId($module_id);


		foreach ($commentTo as  $value) {
			
			$toAssigned= $value;
			//generate link
			$link = $this->goLink($toAssigned,array('controller'=>'comment','action'=>'index',$encModuleID,$type,$encCommentID));

			$email = "You have an unread comment/reply as follows:<br>";
			//$email = "Please review it again and resubmit your memo request.<br>";
			$email .= $this->commentTable($reply,$type);
			$email .= "You may go to the comment page by clicking the button below <br>";

			
			// debug($link);exit();
			$email .= "<a href='{$link}' class='btn btn-success'> Go to comment page </a>";

			//$toRequestor = $memo['FMemo']['user_id'];
			$subject = "New comment/reply";

			$this->emailMe($toAssigned,$subject,$email);

			// add notification to the assigned ppl -- stating there is a new comment/reply
			$notiTo = $toAssigned;
			// $notiText = "You have an unread comment/reply";
			// $notiText = "<b> Ref.No : </b> ".$memo["$mainModel"]['ref_no']. "<br>".
			// 			"<b> Dept : </b> ".$memo['Department']['department_name']."<br>".
			// 			"<b> Pending : </b> Comment";
			$notiText = "<b> Ref.No : </b> ".$reply["$commentModel"]["$mainModel"]['ref_no']. "<br>".
						"<b> Subject : </b> ".$reply["$commentModel"]["$mainModel"]['subject']."<br>".
						"<b> Dept : </b> ".$reply['User']['Department']['department_name']."<br>".
						"<b> Pending : </b> Comment";

			$notiLink = array('controller'=>'comment','action'=>'index',$encModuleID,$type,$encCommentID);
			//$this->UserNotification->record($notiTo, $notiText, $notiLink);
			#update code for ememo2
			#ememo2
			$notiType="comment";
			$this->UserNotification->record($notiTo, $notiText, $notiLink,$notiType);
		}
		
	}

	private function commentTable($replyData = array(),$type){
		

		if ($type=='financial'){
			
			$mainModel='FMemo';
			//$reviewerModel='FReviewer';
			$commentModel='FComment';
			$replyModel='FReply';
			//$assignModel='FcommentAssign';
			//

		}

		if ($type=='nonfinancial'){
			
			$mainModel='NfMemo';
			//$reviewerModel='NfReviewer';
			$commentModel='NfComment';
			$replyModel='NfReply';
			//$assignModel='NfcommentAssign';
			//

		}
		$htmlTable = "<table style='width:90%;border-collapse:collapse;padding: 10px 15px;margin:20px;background:#fff;border:1px solid #d9d9d9' border='1' cellpadding='6'>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'>Memo Created </td>
							<td>".date('d M Y',strtotime($replyData["$commentModel"]["$mainModel"]['created'])). " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Subject </td>
							<td>".$replyData["$commentModel"]["$mainModel"]['subject']. " </td>
						</tr>
						<tr>
							<td style='width:30%;background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Reference No. </td>
							<td>".$replyData["$commentModel"]["$mainModel"]['ref_no']. " </td>
						</tr>
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Comment / Reply </td>
							<td>".$replyData["$replyModel"]['reply']. " </td>
						</tr>						
						<tr>
							<td style='background:#777;color:#FFF;font-weight:bold;padding: 10px;'> Added by </td>
							<td> ".
								$replyData['User']['staff_name'].' ('.$replyData['User']['designation'].')'."<br>". 
								"<small>Department : ".$replyData['User']['Department']['department_name']."</small>".
							"</td>
						</tr>
						
						
					</table>";

		return $htmlTable;
	}


}