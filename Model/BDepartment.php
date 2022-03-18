<?php

App::uses('AppModel', 'Model');

class BDepartment extends AppModel {
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
		'BItemAmount' => array(
			'className' => 'BItemAmount',
			'foreignKey' => 'b_dept_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
	);

}
