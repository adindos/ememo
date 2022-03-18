<?php

App::uses('AppModel', 'Model');

class BArchive extends AppModel {
	public $primaryKey = 'archive_id';
 
	public $hasMany = array(
		
	);

	public $belongsTo = array(
		'Company' => array(
			'className' => 'Company',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);
}
