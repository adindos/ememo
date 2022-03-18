<?php

App::uses('AppModel', 'Model');

class BOldItemAmount extends AppModel {
	public $primaryKey = 'item_amount_id';
	public $displayField = 'amount';

	public $validate = array();

	public $belongsTo = array(
		
		'BOldItemGroup' => array(
			'className' => 'BOldItemGroup',
			'foreignKey' => 'item_group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BOldDepartment' => array(
			'className' => 'BOldDepartment',
			'foreignKey' => 'b_dept_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
		'Budget' => array(
			'className' => 'Budget',
			'foreignKey' => 'budget_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Item' => array(
			'className' => 'Item',
			'foreignKey' => 'item_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);

	
	
}
