<?php

App::uses('AppModel', 'Model');

class Item extends AppModel {
	public $primaryKey = 'item_id';
	var $virtualFields = array(
	    'code_item' => 'CONCAT(Item.item_code, " - ", Item.item)'
	);
	public $displayField = 'code_item';

	// public $validate = array(

	// 	'category_id' => array(

	// 		'rule' => array('notEmpty'),
	// 		'allowEmpty' => false,
	// 		'required' => true,		
	// 		),

	// 	'item' => array(

	// 		'rule' => array('notEmpty'),
	// 		'allowEmpty' => false,
	// 		'required' => true,		
	// 		),

	// 	);





	public $belongsTo = array(
		'Parent' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);

	public $hasMany = array(
		'Children' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BItemAmount' => array(
			'className' => 'BItemAmount',
			'foreignKey' => 'item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);

}
