<?php

App::uses('AppModel', 'Model');

class Budget extends AppModel {
	public $primaryKey = 'budget_id';
	public $displayField = 'year';

	public $validate = array(
			
			'year'=>array(
					'rule'=>'notEmpty',
					'required'=>true,
					'allowEmpty'=>false,
					'message'=>'Budget year is required',
				),
			'company_id'=>array(
					'rule'=>'notEmpty',
					'required'=>true,
					'allowEmpty'=>false,
					'message'=>'Company name is required',
				)
	);

	public $hasMany = array(
		'BItemAmount' => array(
			'className' => 'BItemAmount',
			'foreignKey' => 'budget_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BDepartment' => array(
			'className' => 'BDepartment',
			'foreignKey' => 'budget_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		
		'BItemGroup' => array(
			'className' => 'BItemGroup',
			'foreignKey' => 'budget_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BReviewer' => array(
			'className' => 'BReviewer',
			'foreignKey' => 'budget_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BStatus' => array(
			'className' => 'BStatus',
			'foreignKey' => 'budget_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'BRemark' => array(

			'className' => 'BRemark',

			'foreignKey' => 'budget_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

			),
		'FMemoBudget' => array(

			'className' => 'FMemoBudget',

			'foreignKey' => 'budget_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),

	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),

		
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
