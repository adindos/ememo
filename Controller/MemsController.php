<?php
App::uses('AppController', 'Controller');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class MemsController extends AppController{
	public $uses = array('Token','User','UserNotification');

	/*
	*	Link management (lm)
	*	- redirect the user to the specified page
	*/
	public function go($token = null){
		//logout user
		if($this->Auth->loggedIn()){
			$this->Auth->logout();
		}

		// first get token
		$token = $this->params->query['token'];
		$tokenData = $this->Token->findByUniqueToken($token);

		if(!$tokenData){
			$this->Session->setFlash('<b> Token not exist </b><br><small> The token does not exist </small>','flash.error');
			$this->redirect(array('controller'=>'user','action'=>'login'));
		}

		//dont autologin disabled user
		$this->User->recursive = -1;
		$tmpUser = $this->User->findByUserId($tokenData['Token']['user_id']);
		if($tmpUser['User']['status'] == 'disabled'){
			$this->Session->setFlash('<b> Login Failed </b><br><small> You have been disabled by the administrator </small>','flash.error');
			$this->redirect(array('controller'=>'user','action'=>'login'));
		}

		$tokenCompleted = $tokenData['Token']['completed'];
		// then check token timeout
		$tokenExpired = false;
		$tokenCreated = new DateTime($tokenData['Token']['created']);

		$current = new DateTime(date('Y-m-d h:i:s'));
		$timediff = $current->diff($tokenCreated);

		if($timediff->d >= 3){ // right now the timeout is 3 days
			$tokenExpired = true;
		}

		$passMatch = false;
		$isLoggedIn = false;
		$failedLogin = false;		

		// lockscreen and login
		if($this->request->is('post')){
			//check if password match
			// $this->User->recursive = -1;
			// $tmpUser = $this->User->findByUserId($tokenData['Token']['user_id']);
			// $pass = $this->request->data['User']['password'];
			// $storedPass = $tmpUser['User']['password'];
			// $passwordHasher = new BlowfishPasswordHasher();

			if( $this->Auth->login() ){
				$isLoggedIn = true;
				$tokenExpired = false;
				$tokenCompleted = false;
			}
			else{
				$failedLogin = true;
				$this->Session->setFlash('<b> Login failed </b><br><small>Please try again </small>','flash.error');
			}
		}

		// if token not expired / not completed / success login - redirect to page
		if( (!$tokenExpired && !$tokenCompleted) || $isLoggedIn){
			$this->User->contain(array('Department','Role'));
			$user = $this->User->read(null,$tokenData['Token']['user_id']);
			$user['User']['Department'] = $user['Department']; //associated user data
			$user['User']['Role'] = $user['Role']; // associated user data

			// auto login
			$this->Auth->login($user['User']); 
			// flag as completed
			$this->Token->id = $tokenData['Token']['id'];
			// debug($this->Token->id);exit();

			$url = $tokenData['Token']['go_url'];

			$chunks = explode('/',$url);
			foreach($chunks as $key=>$c):
				if(strlen($c) > 30):
					$chunks[$key] = rawurlencode($c);
				endif;
			endforeach;
			$finalURL = implode('/',$chunks);
			 debug($chunks);
			// debug($finalURL);
			
			$this->Token->saveField('completed',1);

			$this->redirect($finalURL);
		}
		// if token timeout/completed - require password
		else{
			$this->User->recursive = -1;
			$user = $this->User->read(null,$tokenData['Token']['user_id']);
			$this->set('user',$user);
			$this->set('token',$token);

			if(!$failedLogin):
				if($tokenCompleted)
					$this->Session->setFlash('<b> Token already used </b><br><small> You already use the token. Please enter your password to continue </small>','flash.info');
				if($tokenExpired)
					$this->Session->setFlash('<b> Token expired </b><br><small> Please enter your password to proceed </small>','flash.warning');
			endif;

			$this->layout = 'mems-lockscreen';
			$this->render('lockscreen');
		}


	}

	/*
	*	Notification management ()
	*	- redirect the user to the specified page and set seen
	*/
	public function notification($token = null){
		// debug($this->params->query);exit();
		// debug(!(isset($this->params->query['noti'])));exit();
		if(!(isset($this->params->query['noti']))){
			$this->Session->setFlash('<strong> Incorrect notification </strong><br><small>Please try again </small>','flash.error');
			$this->redirect(array('controller'=>'user','action'=>'userDashboard'));
		}
		// debug($token);exit();
		$token = $this->params->query['noti'];

		// debug($token);exit();
		$this->UserNotification->recursive = -1;
		$notification = $this->UserNotification->findByToken($token);
		// debug($notification);exit();
		$this->UserNotification->id = $notification['UserNotification']['id'];
		if($this->UserNotification->saveField('seen',1)){
			
			// $url = $notification['UserNotification']['link'];
			// $first = substr($url,0,strrpos($url,"/")+1);
			// $last = substr($url,strrpos($url,"/")+1);
			// $encoded = rawurlencode($last);
			// $finalURL = $first.$encoded;
			$url = $notification['UserNotification']['link'];

			$chunks = explode('/',$url);
			// debug($chunks);
			foreach($chunks as $key=>$c):
				if(strlen($c) > 30):
					$chunks[$key] = rawurlencode($c);
				endif;
			endforeach;
			$finalURL = implode('/',$chunks);

			$this->redirect($finalURL);
		}
	}

	/*
	*	Mark notification as seen
	*	
	*/
	public function markSeen(){
		if($this->request->is(array('post','ajax'))){
			$token = $this->request->data['token'];
			$this->UserNotification->recursive = -1;
			$notification = $this->UserNotification->findByToken($token);
			$this->UserNotification->id = $notification['UserNotification']['id'];
			$this->UserNotification->saveField('seen',1);
		}	

		$this->layout = 'mems-empty';
		$this->render('empty');

		echo "The task has been successfully marked as seen!";
	}

	/*
	*	Clear all notification
	*	
	*/
	public function clearAllNotifications(){
		$user = $this->getAuth();
		if($this->request->is(array('post','ajax'))){
			$this->UserNotification->updateAll(
					array('UserNotification.seen'=>1),
					array('UserNotification.user_id' => $user['user_id'],
						'UserNotification.seen' => 0 )
				);
		}	

		$this->layout = 'mems-empty';
		$this->render('empty');

		echo "All task have been cleared!";
	}

	// Testing functions
	public function unitarEmail($to){
		$message = "hello.. this is a test";
		$subject = 'test email';
		// debug($to);exit();
		// $message = nl2br($message);
		$email = new CakeEmail('unitar');
		$email->emailFormat('html');
		$email->viewVars(array('message'=>$message));
		$email->template('mems','mems');
		$email->to($to);
		$email->subject("UNITAR eMEMO: ".$subject);
		return $email->send(); 
	}
}