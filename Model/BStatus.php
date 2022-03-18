<?php

App::uses('AppModel', 'Model');

class BStatus extends AppModel {
	public $primaryKey = 'status_id';
	public $displayField = 'status';

	public $validate = array();

	public $belongsTo = array(
		'BReviewer' => array(
			'className' => 'BReviewer',
			'foreignKey' => 'reviewer_id',
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
	);

}
