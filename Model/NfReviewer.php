<?php

/*

*	=========================================================================

*	UNITAR MeMs 2015 

*	@vendor GR Tech Sdn Bhd

*	@team Syikin,Aisyah,Nizam

*	=========================================================================

*	

*	[ File ]

*		FReviewer.php

*			- Model for Financial Status table

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



class NfReviewer extends AppModel {

	/*

	* primary key for the model / table

	*/

	public $primaryKey = 'reviewer_id';

	/*

	* 	hasMany - this model has many defined model

	*/

	public $hasMany = array(

		'NfStatus' => array(

			'className' => 'NfStatus',
			'foreignKey' => 'reviewer_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''

		),
		
	);
	
	

	/*

	*	belongsTo - this model belongs to defined model (child/foreign)

	*/

	public $belongsTo = array(

		'NfMemo' => array(

			'className' => 'NfMemo',
			'foreignKey' => 'memo_id',
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

