<?php

/*

*	=========================================================================

*	UNITAR MeMs 2015 

*	@vendor GR Tech Sdn Bhd

*	@team Syikin,Aisyah,Nizam,Faridi

*	=========================================================================

*	[ File ]

*		Company.php

*			- Model for Company table

*	[ Description ]

*		< some description here >

*	[ HELP ]

*		primaryKey - the primary key for the model/table

*		displayField - will show the displayfield but the value will be primarykey (select list)

*		validate - validation criteria

*

*	[--(TO DO at the bottom of the page)--]

*/



App::uses('AppModel', 'Model');



class Company extends AppModel {

	/*

	* primary key for the model / table

	*/

	public $primaryKey = 'company_id';



	/*

	* DisplayField for the model (to be shown as list)

	*/

	public $displayField = 'company';



	/*

	*	validation using cakephp 

	*	disabled if not use to avoid problem later.

	*/

	public $validate = array(

			'year_established' => array(

				'rule' => array('date', 'y'),
				'message' => 'Year value invalid',
				'allowEmpty' => true,
				'required' => false,			
			),

			'company' => array(

				'rule' => array('notEmpty'),
 				'allowEmpty' => false,
				'required' => true,

				)

		);



	/*

	* Model relationship

	*	- Relationship between each model

	*/



	/*

	* 	hasMany - this model has many defined model

	*/

	public $hasMany = array(

		'Group' => array(

			'className' => 'Group',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''

		),


		'BArchive' => array(

			'className' => 'BArchive',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''

		),


		'Budget' => array(

			'className' => 'Budget',
			'foreignKey' => 'company_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''

		),

	);
	
	

	/*

	*	belongsTo - this model belongs to defined model (child/foreign)

	*/

	// public $belongsTo = array(

	// 	'Group' => array(

	// 		'className' => 'Group',

	// 		'foreignKey' => 'group_id',

	// 		'conditions' => '',

	// 		'fields' => '',

	// 		'order' => ''

	// 	),

	// );



	/*

	*	hasOne - this model can have only one of defined model

	*/

	// public $hasOne = array(

	// 	'<ModelName>' => array(

	// 		'className' => '<ModelClassName>',

	// 		'foreignKey' => '<foreignKey>',

	// 		'conditions' => '',

	// 		'fields' => '',

	// 		'order' => ''

	// 	),

	// );



	/*

	*	hasAndbelongsToMany - too complex to explain. and don't think we gonna use this. Check CakePHP docs for reference.

	*/

	// public $hasAndBelongsToMany  = array(

	// 	'<ModelName>' => array(

	// 		'className' => '<ModelClassName>',

	// 		'foreignKey' => '<foreignKey>',

	// 		'conditions' => '',

	// 		'fields' => '',

	// 		'order' => ''

	// 	),

	// );




/*

*	========================================================================

*	| EXTRA:																|

	|	This is a to do list for the controller. Please update accordingly. |

*	========================================================================

*	To Do : 

*	[/] Project model template (Nizam)

*

*	(please mark checked in box if completed)

*

*	[CakePHP]

*		 @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)

*/



/*
*
*	Executes before the deletion function Do not let a Company be deleted if it
*	still contains children.
*	A call of $this->Company->delete($id) from CompanyController.php has set $this->id .
* 	@type CallBack function
* 	
*	latest modified 23/Feb @ Faridi
*/



	public function beforeDelete($cascade = false) {


		$this->Group->recursive = -1;
	    $count1 = $this->Group->find("count", array(
	        "conditions" => array("company_id" => $this->id)
	    ));

	    $this->BArchive->recursive = -1;
	    $count2 = $this->BArchive->find("count", array(
	        "conditions" => array("company_id" => $this->id)
	    ));


	    if ($count1 == 0 && $count2 == 0) {
	        return true;
	    }




	    return false;
	}
}

