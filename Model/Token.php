<?php

App::uses('AppModel', 'Model');

class Token extends AppModel {
	public $primaryKey = 'id';
	// public $displayField = '';

	public $hasMany = array(
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
