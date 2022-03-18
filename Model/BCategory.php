<?php

App::uses('AppModel', 'Model');

class BCategory extends AppModel {
	public $primaryKey = 'category_id';
	public $displayField = 'category';

	// public $validate = array(
	// 	'category' => array(
	// 		'rule' => array('notEmpty'),
	// 		'allowEmpty' => false,
	// 		'required' => true,		
	// 		),
	// 	);


	public $hasMany = array(
		
		'BItem' => array(
			'className' => 'BItem',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),

	);

}
