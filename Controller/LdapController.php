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
class LdapController extends AppController {



	public function test(){


		$this->User->recursive = -1;
		$user = $this->User->find('first',array('conditions' => array('staff_id' => "vctest1")));

		debug($user);



		// using ldap bind
		$ldapusr  = 'grt';     // ldap rdn or dn
		$ldappass = 'Unitar2020';  // associated password


		// connect to ldap server
		// $ldapconn = ldap_connect("172.17.17.152:389") or die ("Could not connect to LDAP server.");
		$ldapconn = ldap_connect("172.17.17.212:389") or die ("Could not connect to LDAP server.");


		ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);


		if($ldapconn){


			$ldapbind = ldap_bind($ldapconn,$ldapusr,$ldappass);

			$ldap_base_dn="DC=unitarklj1,DC=edu,DC=my";



			// verify binding
			if ($ldapbind) {
			    echo "LDAP bind successful...";

			    // debug($ldapbind);


			    // $search_filter = '(samaccountname=staffemail)';
			    $search_filter = '(samaccountname=zolkipli)';


			    if($ldapbind = ldap_bind($ldapconn,'vctest1@unitar.my','Unitar2014'))
			       echo "ok";
			   	else
			   		echo "not okay";

				$attributes = array('samaccountname','displayName');
			    // $result = ldap_search($ldapconn, $ldap_base_dn,$search_filter,$attributes);
			    $result = ldap_search($ldapconn, $ldap_base_dn,$search_filter,$attributes) or die ("Error in search query: ".ldap_error($ldapconn));
			    //fetch all users
			    $info = ldap_get_entries($ldapconn, $result);

			    debug($info);


			    debug($info[0]['displayname'][0]);

			    // print number of entries found
			    echo "Number of entries found: " . ldap_count_entries($ldapconn, $result);

			 //   // SHOW ALL DATA
			 //    echo '<h1>Dump all data</h1><pre>';
			 //    print_r($info);    
			 //    echo '</pre>';
			    
			} else {
			    echo "LDAP bind failed...";
			}

		}

	}



	public function searchUser(){

		$this->autoRender = false;

		if($this->request->is('post')){

			$username = $this->request->data['username'];

			$checkLdap = $this->checkUserNameLdap($username);

			if($checkLdap){
				$checkdb = $this->checkUserNameDB($username);

				if($checkdb){
				
					$userData = array();
					$userData['username'] = $username;
					$userData['email'] = $username."@unitar.my";
					$userData['staff_name'] = $checkLdap; 


					$User = json_encode($userData);

					return $User;
				}

				return false;

			}

			return false;

		
		}


		return false;

}



	public function checkUserNameLdap($username){



		// using ldap bind
		$ldapusr  = 'grt';     // ldap rdn or dn
		$ldappass = 'Unitar2020';  // associated password


		// connect to ldap server
		// $ldapconn = ldap_connect("172.17.17.152:389") or die ("Could not connect to LDAP server.");
		$ldapconn = ldap_connect("172.17.17.212:389") or die ("Could not connect to LDAP server.");


		ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);


		if($ldapconn){

			$ldapbind = ldap_bind($ldapconn,$ldapusr,$ldappass);
			$ldap_base_dn="DC=unitarklj1,DC=edu,DC=my";

			// verify binding
			if ($ldapbind) {

			    $search_filter = "(samaccountname=".$username.")";
			    $search_filter=preg_replace('/\s+/', '', $search_filter);
	

				$attributes = array('samaccountname','displayName');
 			    $result = ldap_search($ldapconn, $ldap_base_dn,$search_filter,$attributes);
 			    $info = ldap_get_entries($ldapconn, $result);


 			    if($info['count'] != 0){
 			    	return $info[0]['displayname'][0];

 			    }else{

 			    	return false;
 			    }
							    
			} else {
			    echo "LDAP bind failed...";
			}


			return false;

		}


		return false;


	}




	public function checkUserNameDB($username)
	{

		$username =preg_replace('/\s+/', '', $username);


		$this->User->recursive = -1;
		$user = $this->User->find('first',array('conditions' => array('staff_id' => $username)));



		if(sizeof($user) > 0){
			return false;
		}else{
			return true;
		}



	}




	



}
