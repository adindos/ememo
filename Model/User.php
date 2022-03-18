<?php

/*

*	=========================================================================

*	UNITAR MeMs 2015 

*	@vendor GR Tech Sdn Bhd

*	@team Syikin,Aisyah,Nizam,Faridi

*	=========================================================================

*	

*	[ File ]

*		User.php

*			- Model for User table

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



class User extends AppModel {

	/*

	* primary key for the model / table

	*/

	public $primaryKey = 'user_id';



	/*

	* DisplayField for the model (to be shown as list)

	*/

	public $displayField = 'staff_name';



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

		'FMemo' => array(

			'className' => 'FMemo',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'FRemark' => array(

			'className' => 'FRemark',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'FRemarkFeedback' => array(

			'className' => 'FRemarkFeedback',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'FRemarkAssign' => array(

			'className' => 'FRemarkAssign',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'FComment' => array(

			'className' => 'FComment',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'FReply' => array(

			'className' => 'FReply',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),


		'NfMemo' => array(

			'className' => 'NfMemo',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'NfRemark' => array(

			'className' => 'NfRemark',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'NfRemarkFeedback' => array(

			'className' => 'NfRemarkFeedback',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'NfRemarkAssign' => array(

			'className' => 'NfRemarkAssign',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'NfComment' => array(

			'className' => 'NfComment',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'NfReply' => array(

			'className' => 'NfReply',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),

		'Budget' => array(

			'className' => 'Budget',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'BRemark' => array(

			'className' => 'BRemark',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'BRemarkFeedback' => array(

			'className' => 'BRemarkFeedback',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'BRemarkAssign' => array(

			'className' => 'BRemarkAssign',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),

		'FReviewer' => array(

			'className' => 'FReviewer',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'NfReviewer' => array(

			'className' => 'NfReviewer',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		'BReviewer' => array(

			'className' => 'BReviewer',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),

		'FMemoTo' => array(

			'className' => 'FMemoTo',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),

		'NfMemoTo' => array(

			'className' => 'NfMemoTo',

			'foreignKey' => 'user_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),

	);
	
	

	/*

	*	belongsTo - this model belongs to defined model (child/foreign)

	*/

	public $belongsTo = array(

		'Department' => array(

			'className' => 'Department',

			'foreignKey' => 'department_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),

		'Role' => array(

			'className' => 'Role',

			'foreignKey' => 'role_id',

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

