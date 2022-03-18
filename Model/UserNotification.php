<?php

App::uses('AppModel', 'Model');

class UserNotification extends AppModel {

	/*
	* primary key for the model / table
	*/
	public $primaryKey = 'id';

	/*
	* DisplayField for the model (to be shown as list)
	*/
	public $displayField = 'text';

	/*
	*	belongsTo - this model belongs to defined model (child/foreign)
	*/
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

	public function record($userid=null, $text=null, $link=null, $type=null ){

		$this->data['UserNotification']['token'] = uniqid().time();
		$this->data['UserNotification']['user_id'] = $userid;
		$this->data['UserNotification']['text'] = $text;
		// if(is_array($link) )
		// 	$this->data['UserNotification']['link'] = serialize($link);
		// else
		// 	$this->data['UserNotification']['link'] = $link;
		$this->data['UserNotification']['link'] = ACCESS_URL.implode("/",$link);

		#ememo2
		$this->data['UserNotification']['type'] = $type;

		// debug($this->data);
		$this->create($this->data);
		$this->save($this->data);
	}

}
