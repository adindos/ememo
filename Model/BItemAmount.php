<?php

App::uses('AppModel', 'Model');

class BItemAmount extends AppModel {
	public $primaryKey = 'item_amount_id';
	public $displayField = 'amount';

	public $validate = array();

	public $belongsTo = array(
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
		'BItemGroup' => array(
			'className' => 'BItemGroup',
			'foreignKey' => 'item_group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BDepartment' => array(
			'className' => 'BDepartment',
			'foreignKey' => 'b_dept_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);

	public $hasMany = array(
		'FMemoBudget' => array(
			'className' => 'FMemoBudget',
			'foreignKey' => 'item_amount_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'FMemoBudgetTransfer' => array(
			'className' => 'FMemoBudget',
			'foreignKey' => 'transferred_item_amount_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);
}
