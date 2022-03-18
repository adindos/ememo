<?php

/*

*	=========================================================================

*	UNITAR MeMs 2015 

*	@vendor GR Tech Sdn Bhd

*	@team Syikin,Aisyah,Nizam

*	=========================================================================

*	

*	[ File ]

*		FMemo.php

*			- Model for financial memo table

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



class FMemo extends AppModel {

	/*

	* primary key for the model / table

	*/

	public $primaryKey = 'memo_id';



	/*

	* DisplayField for the model (to be shown as list)

	*/

	public $displayField = 'subject';



	/*

	*	validation using cakephp 

	*	disabled if not use to avoid problem later.

	*/

	// public $validate = array(

	// 		'<type>' => array(

	// 			'rule' => array('<rule>'),

	// 			'message' => '<Your custom message here>',

	// 			'allowEmpty' => false,

	// 			'required' => false,

	// 			'last' => false, // Stop validation after this rule

	// 			'on' => 'create', // Limit validation to 'create' or 'update' operations

	// 		),

	// 	);



	/*

	* Model relationship

	*	- Relationship between each model

	*/



	/*

	* 	hasMany - this model has many defined model

	*/

	public $hasMany = array(

		'FRemark' => array(

			'className' => 'FRemark',

			'foreignKey' => 'memo_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

			),
		'FComment' => array(

			'className' => 'FComment',

			'foreignKey' => 'memo_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

			),

		'FReviewer' => array(

			'className' => 'FReviewer',

			'foreignKey' => 'memo_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

			),


		'FStatus' => array(

			'className' => 'FStatus',

			'foreignKey' => 'memo_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

			),

		'FVendorAttachment' => array(

			'className' => 'FVendorAttachment',

			'foreignKey' => 'memo_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'FMemoTo' => array(

			'className' => 'FMemoTo',

			'foreignKey' => 'memo_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'FMemoBudget' => array(

			'className' => 'FMemoBudget',

			'foreignKey' => 'memo_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),

		

	);
	
	

	/*

	*	belongsTo - this model belongs to defined model (child/foreign)

	*/

	public $belongsTo = array(

		'User' => array(

			'className' => 'User',

			'foreignKey' => 'user_id',

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

