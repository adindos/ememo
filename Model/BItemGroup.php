<?php

App::uses('AppModel', 'Model');

class BItemGroup extends AppModel {
	public $primaryKey = 'item_group_id';
	public $displayField = 'group_type';

	// public $virtualFields = array('totalBudget'=>'SUM(amount)');

	// public $validate = array(
	// 		'item_id'=>array(
	// 				'rule'=>'notEmpty',
	// 				'required'=>true,
	// 				'allowEmpty'=>false,
	// 				'on'=>'create',
	// 				'message'=>'Please select the item',
	// 			),
	// 		'amount'=>array(
	// 				'notEmpty'=>array(
	// 						'rule'=>'notEmpty',
	// 						'required'=>true,
	// 						'allowEmpty'=>false,
	// 						'message'=>'Please enter the budget amount',
	// 					),
	// 				'numeric'=>array(
	// 						'rule'=>'numeric',
	// 						'message'=>"Please enter a numeric value",
	// 					),
	// 			),
	// 	);

	public $belongsTo = array(
		'Budget' => array(
			'className' => 'Budget',
			'foreignKey' => 'budget_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);
	public $hasMany = array(
		'BItemAmount' => array(
			'className' => 'BItemAmount',
			'foreignKey' => 'item_group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);

}
