<?php

App::uses('AppModel', 'Model');

class BReviewer extends AppModel {
	public $primaryKey = 'reviewer_id';
	public $displayField = '';

	public $validate = array(

		);

	public $belongsTo = array(
		'Budget' => array(
			'className' => 'Budget',
			'foreignKey' => 'budget_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),

	);

	public $hasMany = array(
		'BStatus' => array(
			'className' => 'BStatus',
			'foreignKey' => 'reviewer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

}
