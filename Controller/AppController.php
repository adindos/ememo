<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $uses = array('User','Token','UserNotification');

	public $layout = 'mems';

	public $helper = array(
			'Mems',
		);

	public $components = array(
			'Session',
			'DebugKit.Toolbar',
			'RequestHandler' => array(
			    'viewClassMap' => array(
			        'xls' => 'CakePHPExcel.Excel',
			        'xlsx' => 'CakePHPExcel.Excel'
			    )
			),
			'Auth' => array(
					'authenticate'=>array(
							'User' => array(
				            	'userModel' => 'User',
				                'passwordHasher' => 'Blowfish',
				                'fields' => array(
				                    'username' => 'email',
				                    'password' => 'password'
				                ),
				                'scope' => array('User.status' => 'enabled')
				            ),

				            'Ldap'  => array(
				            	'userModel' => 'User',
				            	'ldap_bind_dn'   => 'grt',
				            	'ldap_bind_pw'   => 'Unitar2020',
				            	'ldap_filter'    => '(sAMAccountName=%USERNAME%)',
				            	'form_fields'    => array ('username' => 'email', 'password' => 'password')

				            	),
						),
					'loginAction'=>array(
							'controller'=>'user',
							'action'=>'login'
						),
					'loginRedirect'=>array(
							'controller'=>'user',
							'action'=>'userDashboard',
						),
					'logoutRedirect'=>array(
							'controller'=>'user',
							'action'=>'login',
						),
				)
		);


	/*
	*	debugme()
	*		print_r enclosed in <pre> tag
	*		Same like debug() but with no error!
	*/
	public function debugme($arr){
		echo "<pre>";
		print_r($arr);
		echo "</pre>";
	}

	public function getAuth(){
		return $this->Auth->User();
	}

	/*
	*	emailMe() - param : $user-> recipient user id, $subject-> title of email, $message -> content of email , $options -> extra options
	*	< reformatting the date to be saved in database : in form of HH:ii >
	*	@ Nizam
	*/
	public function emailMe($userid,$subject,$message,$options=array()){
		//replace class='btn btn-primary' with its style
		$message = str_replace(
				"class='btn btn-success'",
				" style='font-size:17px;padding:10px 15px;vertical-align:middle;font-weight:bold;display:block;text-decoration: none;color:#FFF !important;text-align:center;margin : 10px 0;background-color:#78CD51;'",
				$message);

		// get and show greeting to user which being emailed to
		$toUser = $this->getUserByUserId($userid);
		$to = $toUser['User']['email_address'];
		$to  = str_replace(' ','',$to);


		$name = $toUser['User']['staff_name'];
		$message = "<h3>Hi,".$name."</h3>" . $message ;

		// $message = nl2br($message);
		
		try{
			$email = new CakeEmail('unitar');
			$email->emailFormat('html');
			$email->viewVars(array('message'=>$message));
			$email->template('mems','mems');
			$email->to($to);
			$email->subject("UNITAR eMEMO: ".$subject);
			
			$emailSent = $email->send();
			$logText = "Email successfully sent to ". $to;
			CakeLog::write('email',$logText);
		}
		catch(Exception $e){
			$errorMessage = $e->getMessage();
			$errorLogText = "Email not sent to ". $to. "\n" . $errorMessage ."\n";
			CakeLog::write('email',$errorLogText);
		}
		
	}

	/*
	*	Generate go link
	*	- generate go link 
	*/
	public function goLink($touser=null,$url=array()){
		$uniqueToken = $this->generateToken();

		$data['Token']['unique_token']  = $uniqueToken;
		$data['Token']['user_id']  = $touser;
		$data['Token']['go_url']  = ACCESS_URL.implode("/",$url);

		$this->Token->create();
		$this->Token->save($data);

		return ACCESS_URL."go?token=".$uniqueToken;
	}


	/*
	*	Generate token
	*	- generate token 
	*/
	private function generateToken(){
		return uniqid().time();
	}

	/*
	*	Check token
	*	- check for token timeout
	*/
	private function checkToken(){

	}

	/*
	*	Get User by its user id
	*	- Return user with recursive -1 by accepting email as parameter
	*/
	public function getUserByUserId($userid){
		$this->User->recursive = -1;
		return $this->User->findByUserId($userid);
	}






	public function beforeFilter(){
		parent::beforeFilter();

		$allowedWithoutLogin = array('hasher','ui','go','ldap');
		$this->Auth->allow($allowedWithoutLogin);

		$user = $this->Auth->user();
		$this->set('activeUser',$user);

		//notification
		// $notifications = $this->UserNotification->find('all',array('conditions'=>array('UserNotification.user_id'=>$user['user_id'],'UserNotification.seen'=>0),
		// 															'order'=>'UserNotification.created DESC'));
		// $this->set('notifications',$notifications);

		# code update for ememo2
		#=======================
		//notification old version ememo
		$notifications = $this->UserNotification->find('all',array('conditions'=>array('UserNotification.user_id'=>$user['user_id'],'UserNotification.seen'=>0),'order'=>'UserNotification.created DESC'));

		$notifications_null = $this->UserNotification->find('all',array('conditions'=>array('UserNotification.user_id'=>$user['user_id'],'UserNotification.seen'=>0,'UserNotification.type'=>null),'order'=>'UserNotification.created DESC'));
		// remarks
		$notifications_r = $this->UserNotification->find('all',array('conditions'=>array('UserNotification.user_id'=>$user['user_id'],'UserNotification.seen'=>0,'UserNotification.type'=>'remark'),'order'=>'UserNotification.created DESC'));
		//comment
		$notifications_c = $this->UserNotification->find('all',array('conditions'=>array('UserNotification.user_id'=>$user['user_id'],'UserNotification.seen'=>0,'UserNotification.type'=>'comment'),'order'=>'UserNotification.created DESC'));
		//memo:		
		$notification_memo = $this->UserNotification->find('all',array('conditions'=>array('UserNotification.user_id'=>$user['user_id'],'UserNotification.seen'=>0,'UserNotification.type'=>'memo'),'order'=>'UserNotification.created DESC'));
		

		//BUdget	
		$notifications_b = $this->UserNotification->find('all',array('conditions'=>array(
			'UserNotification.user_id'=>$user['user_id'],'UserNotification.seen'=>0,
				'UserNotification.type'=> array('budget-submitted','budget-approved','budget-rejected','budget-added','budget-to review')),
					'order'=>'UserNotification.created DESC'));

		// debug($notifications_b); exit;
		$this->set('notifications',$notifications);
		$this->set('notifications_null',$notifications_null);
		$this->set('notifications_r',$notifications_r);
		$this->set('notifications_c',$notifications_c);
		$this->set('notification_memo',$notification_memo);		
		$this->set('notifications_b',$notifications_b);

		#end of code update for ememo2
		#-----------------------------

	}



	/********** Encryption Settings *************/


	/**
	 * Returns an encrypted & utf8-encoded
	 */
	function encrypt($pure_string) {
		$encryption_key = "!@#$%^&*";

		$encrypted_string = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($encryption_key), $pure_string, MCRYPT_MODE_CBC, md5(md5($encryption_key))));   	
		$encrypted_string = str_replace('/','SlashSlash',$encrypted_string);

		$encrypted_string = urlencode($encrypted_string);
		// $encrypted_string = rawurlencode($encrypted_string); // nizam 3/4


	    return $encrypted_string;
	}

	/**
	 * Returns decrypted original string
	 */
	function decrypt($encrypted_string) {
		$encryption_key = "!@#$%^&*";

		$encrypted_string = urldecode($encrypted_string);
		// $encrypted_string = $encrypted_string; // nizam 3/4
		$encrypted_string = str_replace('SlashSlash','/',$encrypted_string);
		$decrypted_string = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($encryption_key), base64_decode($encrypted_string), MCRYPT_MODE_CBC, md5(md5($encryption_key))), "\0");

		// debug((int)$decrypted_string);
	    return (int)$decrypted_string;
	}




	


}
