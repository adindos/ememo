<?php

App::uses('AppModel', 'Model');

class BOldDepartment extends AppModel {
	public $primaryKey = 'b_dept_id';
	public $displayField = 'department_id';

	public $belongsTo = array(
		'Budget' => array(
			'className' => 'Budget',
			'foreignKey' => 'budget_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Department' => array(
			'className' => 'Department',
			'foreignKey' => 'department_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);
	public $hasMany = array(
		'BOldItemAmount' => array(
			'className' => 'BOldItemAmount',
			'foreignKey' => 'b_dept_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);

}
