<?php

App::uses('AppController', 'Controller');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class CoderController extends AppController{
	public $layout = 'mems-coder';

	public function hasher($raw=null){

		$passwordHasher = new BlowfishPasswordHasher();
		$hashed = $passwordHasher->hash($raw);

		if($raw == null)
			$hashed = "Provide raw password!";

		$this->set('hashed',$hashed);
	}

	public function translateID($id)
	{
		return $this->decrypt($id);
	}

	// public function logs($type=null){
	// 	$logLocation = "../tmp/logs/";
	// 	if($type==null || $type=='error')
	// 		$file = "error.log"; 

	// 	$fileLocation = $logLocation.$file;

	// 	$logData = file_get_contents($fileLocation);
	// 	echo $logData;
	// }
}