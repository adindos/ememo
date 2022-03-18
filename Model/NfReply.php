<?php

/*

*	=========================================================================

*	UNITAR MeMs 2015 

*	@vendor GR Tech Sdn Bhd

*	@team Syikin,Aisyah,Nizam

*	=========================================================================

*	

*	[ File ]

*		NfReply.php

*			- Model for Non Financial comment reply table

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



class NfReply extends AppModel {

	/*

	* primary key for the model / table

	*/

	public $primaryKey = 'reply_id';



	/*

	* DisplayField for the model (to be shown as list)

	*/

	public $displayField = 'reply';



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

		'NfCommentAttachment' => array(

			'className' => 'NfCommentAttachment',

			'foreignKey' => 'reply_id',

			'conditions' => '',

			'fields' => '',

			'order' => ''

		),
		
		
	);
	
	

	/*

	*	belongsTo - this model belongs to defined model (child/foreign)

	*/

	public $belongsTo = array(

		'NfComment' => array(

			'className' => 'NfComment',

			'foreignKey' => 'comment_id',

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

