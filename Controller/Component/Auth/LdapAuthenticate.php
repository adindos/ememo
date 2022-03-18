<?php 


App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class LdapAuthenticate extends BaseAuthenticate {


	/**
	 * Helper function to connect to the LDAP server
	 * Looks at the plugin's settings to get the LDAP connection details
	 * @throws CakeException
	 * @return LDAP connection as per ldap_connect()
	 */
		private function __ldapConnect($url) {
			
			$ldapConnection = @ldap_connect($url);
			ldap_set_option($ldapConnection, LDAP_OPT_REFERRALS, 0);
			ldap_set_option($ldapConnection, LDAP_OPT_PROTOCOL_VERSION, 3);
			if (!$ldapConnection) {
				throw new CakeException("Could not connect to LDAP authentication server");
			}

			$bind = @ldap_bind($ldapConnection, $this->settings['ldap_bind_dn'], $this->settings['ldap_bind_pw']);

			if (!$bind) {
				throw new CakeException("Could not bind to LDAP authentication server - check your bind DN and password");
			}

			return $ldapConnection;
		}




		/**
		 * Authentication hook to authenticate a user against an LDAP server.
		 * @param CakeRequest $request The request that contains login information.
		 * @param CakeResponse $response Unused response object.
		 * @return mixed. False on login failure. An array of User data on success.
		 */
			public function authenticate(CakeRequest $request, CakeResponse $response) {
				// This will probably be cn or an email field to search for
			
				$fields = $this->settings['form_fields'];

				$userField = $fields['username'];
				$passField = $fields['password'];
				
				// Definitely not authenticated if we haven't got the request data...
				if (!isset($request->data[$this->settings['userModel']])) {
				
					return false;
				}

				// We need to know the username, or email, or some other unique ID
				$submittedDetails = $request->data[$this->settings['userModel']];
 
				if (!isset($submittedDetails[$userField])) {
				
					
					return false;
				}

				// Make sure it's a valid string...
				$username = $submittedDetails[$userField];
				if (!is_string($username)) {
				
					return false;
				}

				// Make sure they gave us a password too...
				$password = $submittedDetails[$passField];
				if (!is_string($password) || empty($password)) {
					
					return false;
				}

				// Get the ldap_filter setting and insert the username
				$ldapFilter = $this->settings['ldap_filter'];

				$ldapFilter = preg_replace('/%USERNAME%/', $username, $ldapFilter);

				
				// If we've got a list of fields to search for their username (or
				// most likely, email address) details, get all those attributes too
				$attribs = array("$username");
 
			
				$url='172.17.17.212:389';		
				$base_dn='DC=unitarklj1,DC=edu,DC=my';
								
		
				// Connect to LDAP server and search for the user object
				 $ldapConnection = $this->__ldapConnect($url);

				// Suppress warning when no object found
				$results = ldap_search($ldapConnection, $base_dn, $ldapFilter, $attribs);


				// Failed to find user details, not authenticated.
				if (!$results || ldap_count_entries($ldapConnection, $results) == 0) {
					
					
					return false;
				}

				// Got multiple results, sysadmin did something wrong!
				if (ldap_count_entries($ldapConnection, $results) > 1) {
					
					
					return false;
				}

				// Found the user! Get their details
				$ldapUser = ldap_get_entries($ldapConnection, $results);


				$ldapUser = $ldapUser[0];

				$results = array();

				// Now try to re-bind as that user
				$bind = @ldap_bind($ldapConnection, $ldapUser['dn'], $password);

 
				// If the password didn't work, bomb out
				if (!$bind) {
					
					return false;
				}


 
				// Look up the user in our DB based on the unique field (username, email or whatever)
				// NB this is nicked from BaseAuthenticate but without the password check
				$userModel = $this->settings['userModel'];
				
				list($plugin, $model) = pluginSplit($userModel);

				$dbUser = ClassRegistry::init($userModel)->find('first', array(
					'conditions' => array("staff_id"=>$username),
					'recursive'	=> 1
				));


 
				$object = Array();

				$object = $dbUser['User'];

				$object['Role'] = $dbUser['Role'];
				$object['Department'] = $dbUser['Department'];
				

				
				// // // Ensure there's nothing in the password field
				// unset($dbUser["$model"][$fields['password']]);

				
				// 	// ...and return the user object.
				return $object;
				
			}










}
