<?php

/*

*	=========================================================================

*	UNITAR MeMs 2015 

*	@vendor GR Tech Sdn Bhd

*	@team Syikin,Aisyah,Nizam,Faridi

*	=========================================================================

*	

*	[ File ]

*		Group.php

*			- Model for department table

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



class Group extends AppModel {

	/*

	* primary key for the model / table

	*/

	public $primaryKey = 'group_id';



	/*

	* DisplayField for the model (to be shown as list)

	*/

	public $displayField = 'group_name';



	/*

	*	validation using cakephp 

	*	disabled if not use to avoid problem later.

	*/

		// public $validate = array(

		// 		'company_id' => array(

		// 			'rule' => array('notEmpty'),
	 // 				'allowEmpty' => false,
		// 			'required' => true,		
		// 		),

		// 		'group_name' => array(

		// 			'rule' => array('notEmpty'),
	 // 				'allowEmpty' => false,
		// 			'required' => true,

		// 			)

		// 	);



	/*

	* Model relationship

	*	- Relationship between each model

	*/



	/*

	* 	hasMany - this model has many defined model

	*/

	public $hasMany = array(

		'Department' => array(

			'className' => 'Department',

			'foreignKey' => 'group_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),

	);
	
	

	/*

	*	belongsTo - this model belongs to defined model (child/foreign)

	*/

	public $belongsTo = array(

		'Company' => array(

			'className' => 'Company',

			'foreignKey' => 'company_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),

	);



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
	*
	*	Executes before the deletion function Do not let a Role be deleted if it
	*	still contains children.
	*	A call of $this->Role->delete($id) from RoleController.php has set $this->id .
	* 	@type CallBack function
	* 	
	*	latest modified 6/March @ Faridi
	*/



		public function beforeDelete($cascade = false) {


			$this->Department->recursive = -1;
		    $count = $this->Department->find("count", array(
		        "conditions" => array("group_id" => $this->id)
		    ));


		    if ($count == 0) {
		        return true;
		    }

		    return false;
		}




}



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

