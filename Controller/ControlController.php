<?php

/*

*	=========================================================================

*	UNITAR MeMs 2015 

*	@vendor GR Tech Sdn Bhd

*	@team Syikin,Aisyah,Nizam

*	=========================================================================

*	

*	[ File ]

*		ControlController.php

*			- Controller to control the action for disable/enable memo

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

class ControlController extends AppController {
	
	public $uses = array('Setting');

	public $layout = 'mems';
	/*

	*	index()

	*	< description of function >

	*	@ < author of function editor >

	*/

	public function index() {
		$user = $this->getAuth();
		// Check user role
		if(!($user['Role']['role_id']==17  || $user['finance'])){//only finance admin/admin can ACCess this page
			throw new ForbiddenException();
		}

		$setting=$this->Setting->find ('first');
		 $this->set('setting',$setting);
		  if ($setting['Setting']['financial_memo']&&$setting['Setting']['nonfinancial_memo']){

		  	$this->Session->setFlash(__('<b>Both Financial Memo and Non-financial Memo access are disabled at the moment</b><br><small> Creation/Review/Editing of any Memo is disabled for now </small>'),'flash.info');
		  }
		 else{
			 if ($setting['Setting']['financial_memo']){

			 	$this->Session->setFlash(__('<b>Financial Memo access is disabled at the moment</b><br><small> Creation/Review/Editing of any Financial Memo is disabled for now </small>'),'flash.info');
			 }
			 elseif ($setting['Setting']['nonfinancial_memo']){

			 	$this->Session->setFlash(__('<b>Non-Financial Memo access is disabled at the moment</b><br><small> Creation/Review/Editing of any Non-financial Memo is disabled for now </small>'),'flash.info');
			 }
		}

		
	}

	public function disable($memo) {
		
		$user = $this->getAuth();
		// Check user role
		if(!in_array($user['Role']['role_id'], array(17,18))){//only finance admin/admin can ACCess this page
			throw new ForbiddenException();
		}

		$setting=$this->Setting->find ('first');
		if ($this->request->is('post')||$this->request->is('put')){
				$this->Setting->id=$setting['Setting']['setting_id'];
			
			if ($memo=='fmemo'){
				$this->Setting->saveField('financial_memo',1);
			}

			elseif($memo=='nfmemo'){
				$this->Setting->saveField('nonfinancial_memo',1);

			}


		}

		return $this->redirect($this->referer());
	}

	public function enable($memo) {
		
		$user = $this->getAuth();
		// Check user role
		if(!in_array($user['Role']['role_id'], array(17,18))){//only finance admin/admin can ACCess this page
			throw new ForbiddenException();
		}

		$setting=$this->Setting->find ('first');
		if ($this->request->is('post')||$this->request->is('put')){
				$this->Setting->id=$setting['Setting']['setting_id'];
			
			if ($memo=='fmemo'){
				$this->Setting->saveField('financial_memo',0);
			}

			elseif($memo=='nfmemo'){
				$this->Setting->saveField('nonfinancial_memo',0);

			}


		}

		return $this->redirect($this->referer());
	}

}