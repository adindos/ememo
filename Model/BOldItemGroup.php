<?php

App::uses('AppModel', 'Model');

class BOldItemGroup extends AppModel {
	public $primaryKey = 'item_group_id';
	public $displayField = 'group_type';


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
		'BOldItemAmount' => array(
			'className' => 'BOldItemAmount',
			'foreignKey' => 'item_group_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);

}
