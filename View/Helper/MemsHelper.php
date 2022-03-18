<?php
/*
*	eMEMO Helper
*	- function for easier access in view
*/

App::uses('AppHelper', 'View/Helper');

class MemsHelper extends AppHelper {

	/*
	*	Random Generator
	*/
	public function random(){
		return uniqid().time();
	}

	/*
	*	Convert cardinal no. to ordinal no.
	*	http://stackoverflow.com/questions/3109978/php-display-number-with-ordinal-suffix
	*/
	public function ordinal($number){
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if (($number %100) >= 11 && ($number%100) <= 13)
		   $abbreviation = $number. 'th';
		else
		   $abbreviation = $number. $ends[$number % 10];

		return $abbreviation;
	}

	/********** Encryption Settings - Same as in AppController but to be used in views *************/


	

	/**
	 * Returns an encrypted & utf8-encoded
	 */
	function encrypt($pure_string) {
		$encryption_key = "!@#$%^&*";

		$encrypted_string = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($encryption_key), $pure_string, MCRYPT_MODE_CBC, md5(md5($encryption_key))));   	
		$encrypted_string = str_replace('/','SlashSlash',$encrypted_string);
 		$encrypted_string = urlencode($encrypted_string);
 		// $encrypted_string = rawurlencode($encrypted_string);
 

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

	    return $decrypted_string;
	}






}
